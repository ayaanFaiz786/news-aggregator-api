<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Interfaces\UserInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private UserInterface $userRepository)
    {
    }

    /**
     * @description Register new user.
     * @param RegisterUserRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            $user = $this->userRepository->createUser([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            return ApiResponse::success(self::SUCCESS_STATUS, 'User registered successfully!', $user, 201);
        } catch (Exception $e) {
            logException($e);
            return ApiResponse::error(self::ERROR_STATUS, $e->getMessage(), 500);
        }
    }

    /**
     * @description user login
     * @param LoginUserRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        try {
            $user = $this->userRepository->getUserByEmail($request->email);
            $token = $user->createToken('API Token')->plainTextToken;
    
            return ApiResponse::success(self::SUCCESS_STATUS, 'Login successfull!', ['token' => $token], 200);
        } catch (Exception $e) {
            logException($e);
            return ApiResponse::error(self::ERROR_STATUS, $e->getMessage(), 500);
        }
    }
    
    /**
     * @description user logout
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });
    
            return ApiResponse::success(self::SUCCESS_STATUS, 'Logged out successfully', [], 200);
        } catch (Exception $e) {
            logException($e);
            return ApiResponse::error(self::ERROR_STATUS, $e->getMessage(), 500);
        }
    }
}
