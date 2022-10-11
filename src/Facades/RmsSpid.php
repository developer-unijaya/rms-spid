<?php

namespace DeveloperUnijaya\RmsSpid\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VendorName\Skeleton\Skeleton
 */
class RmsSpid extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \DeveloperUnijaya\RmsSpid\RmsSpid::class;
    }
}
