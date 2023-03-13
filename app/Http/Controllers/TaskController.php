<?php

namespace App\Http\Controllers;

use App\Services\AuthorizationService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class TaskController
{
    public function __construct(private TaskService $taskService, private AuthorizationService $authorizationservice)
    {
    }

    public function createTask(Request $request): Response
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'taskListId' => 'required|integer',
        ]);

        // Message that tells which POST data is incorrect.
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        // Get the user
        $token = Cookie::get('token');
        if(!is_string($token) || $token === ""){
            return response()->json([
                "message" => "Token is not a string or not found."
            ], 400);
        }

        $user = $this->authorizationservice->authenticateUserByToken($token);

        // Redirect to the login page if the user is not logged in
        if($user === null){
            return redirect()->route('loginPage');
        }

        // Create a new task
        $this->taskService->createTask($validator->getData(), $user);

        // Redirect back to the previous page
        return back(Response::HTTP_CREATED)->with('status', 'Taak gemaakt!');
    }

    public function getTask(string $id): Response
    {
        $token = Cookie::get('token');
        $user = $this->authorizationservice->authenticateUserByToken($token);

        // Redirect to the login page if the user is not logged in
        if($user === null){
            return redirect()->route('loginPage');
        }

        // Get the task
        $task = $this->taskService->getTask($id, $user);

        // Return the task
        return response()->json([
            "name" => $task->name,
            "description" => $task->description
        ]);
    }

    public function searchTasks(Request $request): Response
    {
        $validator =  Validator::make($request->all(), [
            'search' => 'required|max:255',
        ]);

        // Message that tells which POST data is incorrect.
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        // Redirect to tasks page with the search query
        return redirect()->route('tasks', [
            "search" => $validator->getData()['search']
        ]);
    }

    public function updatePage(string $id): Response|View
    {
        $token = Cookie::get('token');
        $user = $this->authorizationservice->authenticateUserByToken($token);

        if($user === null){
            return redirect()->route('loginPage');
        }

        $task = $this->taskService->getTask($id, $user);

        return view('update-task', [
            "task" => $task,
        ]);
    }

    public function updateTask(Request $request): Response
    {
        $validator =  Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        // Message that tells which POST data is incorrect.
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        // Get the user
        $token = Cookie::get('token');
        $user = $this->authorizationservice->authenticateUserByToken($token);

        // Redirect to the login page if the user is not logged in
        if($user === null){
            return redirect()->route('loginPage');
        }

        // Update the task
        $this->taskService->updateTask($validator->getData(), $user);

        // Redirect back to the previous page
        return redirect()->route('tasks')->with('status', 'Taak aangepast!')->send();;
    }

    public function deleteTask(Request $request): Response
    {
        $validator =  Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        // Message that tells which POST data is incorrect.
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        // Get the user
        $token = Cookie::get('token');
        $user = $this->authorizationservice->authenticateUserByToken($token);

        // Redirect to the login page if the user is not logged in
        if($user === null){
            return redirect()->route('loginPage');
        }

        // Delete the task
        $this->taskService->deleteTask($validator->getData()['id'], $user);

        // Redirect back to the previous page
        return back()->with('status', 'Taak verwijderd!')->send();
    }
}
