<?php
namespace Shared\Providers;

use Penoaks\Events\Dispatcher;
use Penoaks\Providers\EventServiceProvider as ServiceProvider;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
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

	/**
	 * Register any other events for your application.
	 *
	 * @param  Dispatcher $events
	 * @return void
	 */
	public function boot( Dispatcher $events )
	{
		parent::boot( $events );

		//
	}
}
