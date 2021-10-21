<?php

namespace Tecnobit\LaravelHubSpot;

use Illuminate\Support\ServiceProvider;

class HubSpotServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 */
	public function register()
	{
		//Bind the HubSpot wrapper class
		$this->app->bind('Tecnobit\LaravelHubSpot\HubSpot', function ($app) {
			return HubSpot::createWithApiKey(env('HUBSPOT_API_KEY', config('hubspotbit.api_key')));
		});
	}

	/**
	 * Perform post-registration booting of services.
	 */
	public function boot()
	{
		// config
		$this->publishes([
			__DIR__.'/config/hubspot.php' => config_path('hubspotbit.php'),
		], 'config');
	}
}
