<?php

namespace WireComments;

class WireCommentsService
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'wire-comments';
    }
}
