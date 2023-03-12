<?php

namespace App\Services;

use App\Models\LoginToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserLoginService
{


    /**
     * @param array<string, string> $userData
     * @return string|User
     *
     * This function is used to log in a user.
     */
    public function login(array $userData): string|User
    {
        $user = User::where("email", $userData['email'])->first();

        // Check if the user exists and if the password is correct.
        if (!$this->checkLoginCredentials($user, $userData)) {
            return "Email of wachtwoord is incorrect.";
        }

        // Check if the email is verified.
        if(!$user->email_verified_at){
            return "Email is nog niet bevestigd.";
        }

        return $user;
    }

    /**
     * @param User|null $user
     * @param array<string, string> $userData
     * @return bool|null
     *
     * This function is used to check if the user exists and if the password is correct.
     */
    private function checkLoginCredentials(?User $user, array $userData): ?bool
    {
        // Check if the user exists
        if (!$user) {
            return null;
        }

        // Check if the password is correct
        if (!Hash::check($userData['password'], $user->password)) {
            return null;
        }

        return true;
    }

}
