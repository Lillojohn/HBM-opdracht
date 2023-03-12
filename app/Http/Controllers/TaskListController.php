<?php

namespace App\Http\Controllers;

use App\Services\AuthorizationService;
use App\Services\TaskListService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TaskListController
{
    public function __construct(private TaskListService $taskListService, private AuthorizationService $authorizationservice)
    {
    }

    public function taskListPage(string $search = null): View|RedirectResponse
    {
        $token = Cookie::get('token');
        $user = $this->authorizationservice->authenticateUserByToken($token);

        if($user === null){
            return redirect()->route('loginPage');
        }

        $taskLists = $this->taskListService->getTaskList($user);

        if(count($taskLists->all()) === 0){
            $this->taskListService->createTaskList($user, "Default", "Default task list");
            $taskLists = $this->taskListService->getTaskList($user);

            return view('tasks', [
                "taskLists" => $taskLists,
                "search" => $search
            ]);
        }

        return view('tasks', [
            "taskLists" => $taskLists,
            "search" => $search
        ]);
    }

    public function getTaskLists(): JsonResponse
    {
        $token = Cookie::get('token');
        if(!is_string($token) || $token === ""){
           return response()->json([
               "message" => "Token is not a string or not found."
           ], 400);
        }

        $user = $this->authorizationservice->authenticateUserByToken($token);

        if($user === null){
            return response()->json([
                "message" => "User not found"
            ], 404);
        }

        $taskLists = $this->taskListService->getTaskList($user);

        return response()->json([
            "taskLists" => $taskLists
        ], 200);
    }

    public function createTaskList(Request $request): JsonResponse
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        // Message that tells which POST data is incorrect.
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        $token = Cookie::get('token');
        $user = $this->authorizationservice->authenticateUserByToken($token);

        if($user === null){
            return response()->json([
                "message" => "User not found"
            ], 404);
        }

        $arrayData = $validator->getData();
        $taskList = $this->taskListService->createTaskList($user, $arrayData['name'], $arrayData['description']);

        return response()->json([
            "taskList" => $taskList
        ], 200);
    }
}
