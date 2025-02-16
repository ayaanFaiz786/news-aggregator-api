<?php

namespace App\Interfaces;

interface UserInterface
{
    public function createUser($data);

    public function getUserByEmail($emailId);
}
