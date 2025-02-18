<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\SetPreferencesRequest;
use App\Interfaces\UserPreferenceInterface;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserPreferenceInterface $preferenceRepository)
    {
    }

    /**
     * @description insert user preference into repository
     * @param SetPreferenceRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function setPreferences(SetPreferencesRequest $request)
    {
        try {
            if (is_null($request->sources) && is_null($request->categories) && is_null($request->authors)) {
                return ApiResponse::error(self::ERROR_STATUS, 'Please add sources, categories, or authors to add preferences.', 400);
            }
    
            $preferences = $this->preferenceRepository->updateOrCreatePreference($request);
            if ($preferences) {
                return ApiResponse::success(self::SUCCESS_STATUS, 'Preferences updated successfully', $preferences, 200);
            }
        } catch (Exception $e) {
            logException($e);
            return ApiResponse::error(self::ERROR_STATUS, $e->getMessage(), 500);
        }
    }

    /**
     * @description fetch user preferences
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function getPreferences(Request $request)
    {
        try {
            $user = $request->user();
    
            $preferences = $this->preferenceRepository->getPreferences($user->id);
            return ApiResponse::success(self::SUCCESS_STATUS, 'Preferences fetched successfully', [
                'sources' => $preferences ? json_decode($preferences->sources) : [],
                'categories' => $preferences ? json_decode($preferences->categories) : [],
                'authors' => $preferences ? json_decode($preferences->authors) : [],
            ], 200);
        } catch (Exception $e) {
            logException($e);
            return ApiResponse::error(self::ERROR_STATUS, $e->getMessage(), 500);
        }
    }
}
