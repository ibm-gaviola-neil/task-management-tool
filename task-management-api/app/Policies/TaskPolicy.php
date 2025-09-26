<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function viewAny(?User $user, Task $tast): bool
    {
        return $user?->id === $tast->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Task $tast): bool
    {
        return $user?->id === $tast->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
}
