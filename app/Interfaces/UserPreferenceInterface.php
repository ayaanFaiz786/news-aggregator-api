<?php

namespace App\Interfaces;

interface UserPreferenceInterface
{
    public function updateOrCreatePreference($request);

    public function getPreferences($userId);
}
