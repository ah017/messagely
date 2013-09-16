<?php namespace Wibleh\Messagely;

use Illuminate\Support\ServiceProvider;

class MessagelyServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('wibleh/messagely');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['messagely'] = $this->app->share(function($app) {
			return new Messagely;
		});
		
		$this->app->booting(function() {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Messagely', 'Wibleh\Messagely\Facades\Messagely');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('messagely');
	}

}