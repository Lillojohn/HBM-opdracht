<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthorizationService;
use App\Services\UserLoginService;
use App\Services\UserServices;
use App\Services\UserVerificationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param UserServices $userServices
     * @param UserVerificationService $userVerificationService
     * @param UserLoginService $userLoginService
     */
    public function __construct(
        private UserServices $userServices,
        private UserVerificationService $userVerificationService,
        private UserLoginService $userLoginService,
        private AuthorizationService $authorizationService
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request): JsonResponse
    {
        // Validation from the POST data
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'password' => 'required|max:255',
            'email' => 'email:rfc,dns|required',
        ]);

        // Message that tells which POST data is incorrect.
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        // Create the account of the User
        $user = $this->userServices->createUser($validator->getData());

        // Message that the user couldn't be created since the email is already being used by another user.
        if (!$user) {
            return response()->json([
                'bericht' => "Email is al in gebruik"
            ], Response::HTTP_CONFLICT);
        }

        // Succes message
        return response()->json([
            'bericht' => "Account geregistreerd voor $user->email. Klik op de link in de email om je account te bevestigen."
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validation from the POST data
        $validator = Validator::make($request->all(), [
            'password' => 'required|max:255',
            'email' => 'email:rfc,dns|required',
        ]);

        // Message that tells which POST data is incorrect.
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        // Login the User
        $user = $this->userLoginService->login($validator->getData());

        // Message that the user couldn't log in.
        if(!$user instanceof User){
            $message = $user;
            return response()->json([
                'bericht' => $message
            ], Response::HTTP_CONFLICT);
        }

        // Create a login token for the user.
        $loginToken = $this->authorizationService->createTokenForUser($user);


        // Success message and set the token as a cookie
        return response()->json([
            'bericht' => "Succesvol ingelogd",
            'token' => $loginToken->token,
            'link' => route('tasks')
        ])->withCookie(cookie('token', $loginToken->token, 60));
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function verifyUser(string $id): JsonResponse
    {
        $validator = Validator::make(["id" => $id], [
            'id' => 'required|max:255',
        ]);

        // Message that tells which POST data is incorrect.
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        // Verify the account of the User and return a message
        return $this->userVerificationService->verifyUserById($id);
    }
}
