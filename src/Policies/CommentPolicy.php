<?php

namespace WireComments\Policies;


use WireComments\Models\Comment;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function reply($user, Comment $comment): bool
    {
        return is_null($comment->parent_id);
    }

    public function edit($user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    public function delete($user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}
