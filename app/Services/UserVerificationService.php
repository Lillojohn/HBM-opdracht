<?php

namespace App\Services;

use App\Http\Controllers\UserController;
use App\Mail\VerificationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class UserVerificationService
{
    public function verifyUserById(string $id): JsonResponse
    {
        $user = User::where("id", $id)->first();

        // Message that the user couldn't be found.
        if (!$user) {
            return response()->json([
                'bericht' => "Account is niet gevonden"
            ]);
        }

        // Message that the user's email is already verified.
        if ($user->email_verified_at) {
            return response()->json([
                'bericht' => "Email was al bevestigd."
            ]);
        }

        // Verify the user's email.
        $user->update([
            "email_verified_at" => Carbon::now()->format("Y-m-d H:i:s")
        ]);

        // Message that the user's email is successfully verified.
        return response()->json([
            'bericht' => "Email is succesvol bevestigd. Klik op de link om in te loggen.",
            'link' => route('login')
        ]);
    }

    // Send the verification email to the user.
    public function sendVerificationEmail(User $user): void
    {
        $verificationLink = route("verifyUser", ["id" => $user->id]);

        Mail::to($user->email)->send(new VerificationMail($verificationLink));
    }
}
