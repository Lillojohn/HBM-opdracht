<?php

namespace App\Services;

use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserServices
{

    public function __construct(private UserVerificationService $verificationService){
    }

    /**
     * @param array $userData
     * @return User|null
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
     */
    private function checkIfEmailIsAlreadyInUse(string $email): bool
    {
        return !! User::where("email", $email)->first();
    }
}
