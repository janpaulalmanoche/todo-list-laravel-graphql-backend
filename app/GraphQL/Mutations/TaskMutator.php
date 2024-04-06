<?php

namespace App\GraphQL\Mutations;

use App\Models\Task;

class TaskMutator
{

    public function deleteTasks($_, array $args)
    {
        $tasks = Task::whereIn('id', $args['ids'])->get();
        Task::whereIn('id', $args['ids'])->delete();
        return $tasks;
    }

    public function updateTasks($_, array $args)
    {
        Task::whereIn('id', $args['ids'])->update(['status' => true]);
        return Task::whereIn('id', $args['ids'])->get();
    }
}
