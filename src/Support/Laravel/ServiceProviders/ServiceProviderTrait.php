<?php namespace Wibleh\Messagely\ServiceProviders;

use Wibleh\Messagely\Messagely;

trait ServiceProviderTrait
{
    public function register()
    {
        $this->app->singleton('messagely', function () {
            $session = $this->app->make('session.store');

            return new Messagely($session);
        });
    }
}
