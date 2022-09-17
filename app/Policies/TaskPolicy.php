<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return ($user->id === 1);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Task $task
     * @return Response|bool
     */
    public function update(User $user, Task $task): Response|bool
    {
        return ($user->id === $task->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Task $task
     * @return Response|bool
     */
    public function delete(User $user, Task $task): Response|bool
    {
        return ($user->id === $task->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Task $task
     * @return Response|bool
     */
    public function restore(User $user, Task $task): Response|bool
    {
        return ($user->id === $task->user_id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Task $task
     * @return Response|bool
     */
    public function forceDelete(User $user, Task $task): Response|bool
    {
        return ($user->id === $task->user_id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Task $task
     * @param Note $note
     * @return Response|bool
     */
    public function updateNote(User $user, Task $task, Note $note): Response|bool
    {
        return ($user->id === $task->user_id && $note->task_id === $task->id);
    }
}
