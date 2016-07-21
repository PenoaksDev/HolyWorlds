<?php

namespace HolyWorlds\Providers;

use Illuminate\Events\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'App\Events\SomeEvent' => [
			'App\Listeners\EventListener',
		],
	];
}
