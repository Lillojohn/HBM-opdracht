<?php

namespace App\Services;

use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserServices
{

    public function __construct(private UserVerificationService $verificationService){
    }

    public function createUser(array $userData): ?User
    {
        // Check if there is an account already made with the email
        if($this->checkIfEmailIsAlreadyInUse($userData['email'])){
            return null;
        }

        // Add an uuid as the primary key to the user.
        $userData['id'] =  Str::uuid()->toString();

        $user = User::create($userData);

        $this->verificationService->sendVerificationEmail($user);

        return $user;
    }

    private function checkIfEmailIsAlreadyInUse(string $email): bool
    {
        return !! User::where("email", $email)->first();
    }
}
