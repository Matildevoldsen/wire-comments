<?php

namespace WireComments\Facades;

use Illuminate\Support\Facades\Facade;
use WireComments\Skeleton\WireCommentsServiceProvider;

/**
 * @see \VendorName\Skeleton\Skeleton
 */
class WireComments extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return WireCommentsServiceProvider::class;
    }
}
