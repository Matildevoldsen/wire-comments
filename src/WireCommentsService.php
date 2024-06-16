<?php

namespace WireComments;

class WireCommentsService
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'wire-comments';
    }
}
