<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Message;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->hasPermissionTo('Read-Messages');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Message $message): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Message $message): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Message $message): bool
    {
        return $user->hasPermissionTo('Delete-Message');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, Message $message): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, Message $message): bool
    {
        //
    }
}
