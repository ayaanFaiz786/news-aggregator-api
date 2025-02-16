<?php

namespace App\Repository;

use App\Interfaces\UserInterface;
use App\Models\User;

class UserRepository implements UserInterface
{
    public function createUser($data)
    {
        User::create($data);
    }

    public function getUserByEmail($email)
    {
        User::where('email', $email)->first();
    }
}
