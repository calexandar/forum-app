<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class LikePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Model $likable): bool
    {
        if (! in_array($likable::class, [Post::class, Comment::class])) {
            return false;
        }

        return $likable->likes()->whereBelongsTo($user)->doesntExist();
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Model $likable): bool
    {
        if (! in_array($likable::class, [Post::class, Comment::class])) {
            return false;
        }

        return $likable->likes()->whereBelongsTo($user)->exists();
    }

}
