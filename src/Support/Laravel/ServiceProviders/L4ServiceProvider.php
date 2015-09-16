<?php namespace Wibleh\Messagely\ServiceProviders;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class L4ServiceProvider extends LaravelServiceProvider
{
    use ServiceProviderTrait;

    public function boot()
    {
        $this->package('wibleh/messagely');
    }

    public function provides()
    {
        return array('messagely');
    }
}