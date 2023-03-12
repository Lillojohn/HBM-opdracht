<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;

class TaskService
{
    /**
     * @param array<string, mixed> $taskData
     * @param User $user
     * @return void
     */
    public function createTask(array $taskData, User $user): void
    {
        $task = new Task();
        $task->name = $taskData['name'];
        $task->description = $taskData['description'];
        $task->task_list_id = $taskData['taskListId'];
        $task->user_id = $user->id;
        $task->save();
    }

    /**
     * @param string $id
     * @param User $user
     * @return Task|null
     */
    public function getTask(string $id, User $user): ?Task
    {
        return Task::where([
            ['id', $id],
            ['user_id', $user->id]
        ])->first();
    }

    /**
     * @param array<string, mixed> $taskData
     * @param User $user
     * @return void
     *
     */
    public function updateTask(array $taskData, User $user): void
    {
        $task = Task::where([
            ['id', $taskData['id']],
            ['user_id', $user->id]
        ])->first();
        $task->name = $taskData['name'];
        $task->description = $taskData['description'];
        $task->save();
    }

    /**
     * @param int $taskId
     * @param User $user
     * @return void
     */
    public function deleteTask(int $taskId, User $user): void
    {
        $task = Task::where([
            ['id', $taskId],
            ['user_id', $user->id]
        ])->first();
        $task->delete();
    }
}
