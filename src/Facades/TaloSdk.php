<?php

namespace TuVendor\TaloLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class TaloSdk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TuVendor\TaloLaravel\Support\Talo::class;
    }
}
