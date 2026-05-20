<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        if ($user->role === 'penulis') {
            return $user->id === $post->user_id;
        }

        return true; // admin and other roles can update
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user): bool
    {
        return $user->role !== 'penulis';
    }
}
