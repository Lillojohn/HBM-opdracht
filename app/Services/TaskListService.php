<?php

namespace App\Services;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TaskListService
{
    /**
     * @param User $user
     * @return Collection|null
     */
    public function getTaskList(User $user) : ?Collection
    {
        return TaskList::where("user_id", $user->id)->get();
    }

    /**
     * @param User $user
     * @param string $name
     * @param string $description
     * @return TaskList|null
     */
    public function createTaskList(User $user, string $name, string $description) : ?TaskList
    {
        $taskList = new TaskList();
        $taskList->name = $name;
        $taskList->user_id = $user->id;
        $taskList->description = $description;
        $taskList->save();

        return $taskList;
    }
}
