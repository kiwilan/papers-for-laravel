<?php

namespace Kiwilan\Papers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kiwilan\Papers\Papers
 */
class Papers extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Kiwilan\Papers\Papers::class;
    }
}
