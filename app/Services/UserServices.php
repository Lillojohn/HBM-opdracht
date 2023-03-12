<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserServices
{

    public function __construct(private UserVerificationService $verificationService){
    }

    /**
     * @param array<string, string> $userData
     * @return User|null
     *
     * This function is used to create a user.
     */
    public function createUser(array $userData): ?User
    {
        // Check if there is an account already made with the email
        if($this->checkIfEmailIsAlreadyInUse($userData['email'])){
            return null;
        }

        // Add an uuid as the primary key to the user.
        $userData['id'] =  Str::uuid()->toString();

        // Hash the password
        $userData['password'] = Hash::make($userData['password']);

        // Create the user
        $user = User::create($userData);

        // Send the verification email to the user.
        $this->verificationService->sendVerificationEmail($user);

        return $user;
    }

    /**
     * @param string $email
     * @return bool
     *
     * This function is used to check if the email is already in use.
     */
    private function checkIfEmailIsAlreadyInUse(string $email): bool
    {
        return !! User::where("email", $email)->first();
    }
}
