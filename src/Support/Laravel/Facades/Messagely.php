<?php namespace Wibleh\Messagely\Support\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Messagely extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'messagely';
    }
}