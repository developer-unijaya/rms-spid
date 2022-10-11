<?php

namespace DeveloperUnijaya\RmsSpid\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DeveloperUnijaya\RmsSpid\RmsSpid
 */
class RmsSpid extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \DeveloperUnijaya\RmsSpid\RmsSpid::class;
    }
}
