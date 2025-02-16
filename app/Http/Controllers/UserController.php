<?php

namespace App\Http\Controllers;

use App\Interfaces\UserPreferenceInterface;
use App\Models\Article;
use App\Models\UserPreference;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $preferenceRepository;

    public function __construct(UserPreferenceInterface $preferenceRepository)
    {
        $this->preferenceRepository = $preferenceRepository;
    }

    public function setPreferences(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sources' => 'array',
                'sources.*' => 'string',
                'categories' => 'array',
                'categories.*' => 'string',
                'authors' => 'array',
                'authors.*' => 'string',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            if (is_null($request->sources) && is_null($request->categories) && is_null($request->authors)) {
                return response()->json(['message' => 'Please add sources, categories, or authors to add preferences.'], 500);
            }
    
            $preferences = $this->preferenceRepository->updateOrCreatePreference($request);
            if ($preferences) {
                return response()->json(['message' => 'Preferences updated successfully']);
            } else {
                return response()->json(['message' => 'Failed to update preferences'], 500);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPreferences(Request $request)
    {
        try {
            $user = $request->user();
    
            $preferences = $this->preferenceRepository->getPreferences($user->id);
            return response()->json([
                'sources' => $preferences ? $preferences->sources : [],
                'categories' => $preferences ? $preferences->categories : [],
                'authors' => $preferences ? $preferences->authors : [],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
