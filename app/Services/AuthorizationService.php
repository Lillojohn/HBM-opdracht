<?php

namespace App\Services;

use App\Models\LoginToken;
use App\Models\User;
use Illuminate\Support\Str;

class AuthorizationService
{
    const DIFF_IN_MINUTES = 60;

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

        if($this->deleteExpiredToken($loginToken)){
            return null;
        }

        $user = User::where("id", $loginToken->user_id)->first();

        if($user === null){
            $loginToken->delete();
            return null;
        }

        return $user;
    }

    public function deleteExpiredToken(LoginToken $token): bool
    {
        if ($token->created_at->diffInMinutes(now()) > self::DIFF_IN_MINUTES) {
            $token->delete();
            return true;
        }

        return false;
    }
}
