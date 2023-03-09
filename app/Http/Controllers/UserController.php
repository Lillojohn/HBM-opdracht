<?php

namespace App\Http\Controllers;

use App\Services\UserServices;
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

    private UserServices $userServices;

    /**
     * @param UserServices $userServices
     */
    public function __construct(UserServices $userServices){
     $this->userServices = $userServices;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request): JsonResponse{
        // Validation from the POST data
        $validator = Validator::make($request->all(),[
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
        if(!$user){
            return response()->json([
                'bericht' => "Email is al in gebruik"
            ], Response::HTTP_CONFLICT);
        }

        // Succes message
        return response()->json([
            'bericht' => "Account geregistreerd voor $user->email"
        ]);
    }
}
