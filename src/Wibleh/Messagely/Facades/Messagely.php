<?php namespace Wibleh\Messagely\Facades;

use Illuminate\Support\Facades\Facade;

class Messagely extends Facade
{
	protected static function getfacadeAccessor() { return 'messagely'; }
}