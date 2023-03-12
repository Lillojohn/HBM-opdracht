<?php

namespace App\Http\Controllers;

use App\Services\AuthorizationService;
use App\Services\TaskListService;
use Illuminate\Support\Facades\Cookie;

class TaskListController
{
    public function __construct(private TaskListService $taskListService, private AuthorizationService $authorizationservice)
    {
    }

    public function taskListPage(string $search = null)
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
}
