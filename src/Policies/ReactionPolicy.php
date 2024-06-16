<?php

namespace WireComments\Policies;


use WireComments\Models\Reaction;

class ReactionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function unreact($user, Reaction $reaction): bool
    {
        return $user->id === $reaction->user_id;
    }
}
