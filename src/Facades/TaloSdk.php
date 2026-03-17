<?php

namespace Virulenta\TaloLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class TaloSdk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Virulenta\TaloLaravel\Support\Talo::class;
    }
}
