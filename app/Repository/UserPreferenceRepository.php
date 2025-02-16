<?php

namespace App\Repository;

use App\Interfaces\UserPreferenceInterface;
use App\Models\UserPreference;

class UserPreferenceRepository implements UserPreferenceInterface
{
    public function updateOrCreatePreference($request)
    {
        $user = $request->user(); 

        $preferences = UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'sources' => $request->sources ? json_encode($request->sources) : null,
                'categories' => $request->categories ? json_encode($request->categories) : null,
                'authors' => $request->authors ? json_encode($request->authors) : null,
            ]
        );
        
        return $preferences;
    }

    public function getPreferences($userId)
    {
        return UserPreference::where('user_id', $userId)->first();
    }
}
