<?php

namespace App\Services;

use App\Models\LoginToken;
use App\Models\User;
use Illuminate\Support\Str;

class LoginTokenService
{
    /**
     * @param User $user
     * @return LoginToken
     */
    public function createTokenForUser(User $user): LoginToken
    {
        $token = new LoginToken();
        $token->token = Str::random(60);
        $token->user_id = $user->id;
        $token->save();

        return $token;
    }

    public function authenticateUserByToken(string $token): ?User
    {
        $loginToken = LoginToken::where("token", $token)->first();
        if(!$loginToken){
            return null;
        }

        return $loginToken->user;
    }
}
