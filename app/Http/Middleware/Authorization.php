<?php

namespace App\Http\Middleware;

use App\Services\AuthorizationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class Authorization
{
    public function __construct(private AuthorizationService $authorizationService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = Cookie::get('token');

        if(!$token){
            return response()->json([
                "message" => "No authorization header found",
                "link" => route('loginPage')
            ], Response::HTTP_UNAUTHORIZED);
        }

        if($this->authorizationService->authenticateUserByToken($token) === null){
            return response()->json([
                "message" => "Invalid token",
                "link" => route('loginPage')
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
