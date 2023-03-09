<?php

namespace App\Services;

use App\Http\Controllers\UserController;
use App\Mail\VerificationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserVerificationService
{
    public function verifyUserById(string $id): string
    {
        $user = User::where("id", $id)->first();
        if (!$user) {
            return "Account is niet gevonden";
        }

        if ($user->email_verified_at) {
            return "Email was al bevestigd.";
        }

        $user->update([
            "email_verified_at" => Carbon::now()
        ]);
        
        return "Email is succesvol bevestigd.";
    }

    public function sendVerificationEmail(User $user): void
    {
        $verificationLink = route("verifyUser", ["id" => $user->id]);

        Mail::to($user->email)->send(new VerificationMail($verificationLink));
    }
}
