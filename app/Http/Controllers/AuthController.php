<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            $user = $this->userRepository->createUser([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            return response()->json([
                'message' => 'User registered successfully!',
                'user' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            $user = $this->userRepository->getUserByEmail($request->email);
    
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
    
            $token = $user->createToken('API Token')->plainTextToken;
    
            return response()->json([
                'message' => 'Login successful!',
                'token' => $token
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });
    
            return response()->json(['message' => 'Logged out successfully']);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
