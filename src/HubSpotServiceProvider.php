<?php

namespace Tecnobit\LaravelHubSpot;

use Illuminate\Support\ServiceProvider;
use HubSpot\Factory;

class HubSpotServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 */
	public function register()
	{
		//Bind the HubSpot wrapper class
		$this->app->singleton(HubSpot::class, function () {
			$handlerStack = \GuzzleHttp\HandlerStack::create();
			$handlerStack->push(
				\HubSpot\RetryMiddlewareFactory::createRateLimitMiddleware(
					\HubSpot\Delay::getConstantDelayFunction()
				)
			);
			$handlerStack->push(
				\HubSpot\RetryMiddlewareFactory::createInternalErrorsMiddleware(
					\HubSpot\Delay::getExponentialDelayFunction(2)
				)
			);
			$client = new \GuzzleHttp\Client(['handler' => $handlerStack]);
		    if(config('hubspot.api_key')){
			    return Factory::createWithApiKey(config('hubspot.api_key'));
            }else{
                return Factory::createWithAccessToken(config('hubspot.access_token'),$client);
            }
		});
	}

	/**
	 * Perform post-registration booting of services.
	 */
	public function boot()
	{
		// config
		$this->publishes([
			__DIR__.'/config/hubspot.php' => config_path('hubspot.php'),
		], 'config');
	}
}
