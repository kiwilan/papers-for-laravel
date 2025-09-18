<?php

namespace Kiwilan\Papers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kiwilan\Papers\Services\Directory
 */
class Directory extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Kiwilan\Papers\Services\Directory::class;
    }
}
