<?php

namespace Sedehi\ArtistConsole\Facades;

use Illuminate\Support\Facades\Facade;

class ArtistConsole extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'artistconsole';
    }
}
