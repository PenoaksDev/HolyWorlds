<?php namespace Shared;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */

use Penoaks\Barebones\Kernel as FrameworkKernel;
use Penoaks\Console\Scheduling\Schedule;
use Penoaks\Framework;

class Kernel extends FrameworkKernel
{
	/**
	 * @var array
	 */
	protected $middleware = [
		\Penoaks\Http\Middleware\CheckForMaintenanceMode::class,
		\HolyWorlds\Middleware\ClearCache::class,
	];

	/**
	 * @var array
	 */
	protected $middlewareGroups = [
		'web' => ,

		'api' => [],
	];

	/**
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => \Shared\Middleware\Authenticate::class,
		'auth.basic' => \Penoaks\Auth\Middleware\AuthenticateWithBasicAuth::class,
		'can' => \Penoaks\Http\Middleware\Authorize::class,
		'guest' => \Shared\Middleware\RedirectIfAuthenticated::class,
		'throttle' => \Penoaks\Routing\Middleware\ThrottleRequests::class,
		'perms' => \Shared\Middleware\Permissions::class,
	];

	/**
	 * Init your project kernel from here
	 */
	public function boot( Framework $fw )
	{
		/**
		 * The Console commands provided by your project.
		 */
		$fw->commands->add( [
			\Shared\Commands\MigrateHolyWorlds::class,
			\Shared\Commands\PushServer::class
		] );

		$fw->providers->add( [
			/*
			 * Framework Service Providers...
			 */
			\Penoaks\Auth\AuthServiceProvider::class,
			\Penoaks\Broadcasting\BroadcastServiceProvider::class,
			\Penoaks\Bus\BusServiceProvider::class,
			\Penoaks\Cache\CacheServiceProvider::class,
			\Penoaks\Cookie\CookieServiceProvider::class,
			\Penoaks\Database\DatabaseServiceProvider::class,
			\Penoaks\Encryption\EncryptionServiceProvider::class,
			\Penoaks\Filesystem\FilesystemServiceProvider::class,
			\Penoaks\Providers\FoundationServiceProvider::class,
			\Penoaks\Hashing\HashServiceProvider::class,
			\Penoaks\Mail\MailServiceProvider::class,
			\Penoaks\Pagination\PaginationServiceProvider::class,
			\Penoaks\Pipeline\PipelineServiceProvider::class,
			\Penoaks\Queue\QueueServiceProvider::class,
			\Penoaks\Redis\RedisServiceProvider::class,
			\Penoaks\Auth\Passwords\PasswordResetServiceProvider::class,
			\Penoaks\Session\SessionServiceProvider::class,
			\Penoaks\Translation\TranslationServiceProvider::class,
			\Penoaks\Validation\ValidationServiceProvider::class,
			\Penoaks\View\ViewServiceProvider::class,

			/*
			 * Application Service Providers...
			 */
			\Shared\Providers\ProjectServiceProvider::class,
			\Shared\Providers\AuthServiceProvider::class,
			\Shared\Providers\EventServiceProvider::class,
			\Shared\Providers\RouteServiceProvider::class,
			// \Krucas\Notification\NotificationServiceProvider::class,
			// \Laravel\Socialite\SocialiteServiceProvider::class,
			\TeamTeaTime\Filer\FilerServiceProvider::class,
			\GrahamCampbell\Markdown\MarkdownServiceProvider::class,
			\Conner\Tagging\Providers\TaggingServiceProvider::class,
			\MaddHatter\LaravelFullcalendar\ServiceProvider::class,
			\Slynova\Commentable\ServiceProvider::class,
			\Intervention\Image\ImageServiceProvider::class,
			\Barryvdh\Debugbar\ServiceProvider::class,
			\Fenos\Notifynder\NotifynderServiceProvider::class,
		] );

		if ( $fw->runningInConsole() )
			$fw->providers->add( [
				\Penoaks\Providers\ArtisanServiceProvider::class,
				\Penoaks\Console\ScheduleServiceProvider::class,
				\Penoaks\Database\MigrationServiceProvider::class,
				\Penoaks\Database\SeedServiceProvider::class,
				\Penoaks\Providers\ComposerServiceProvider::class,
				\Penoaks\Queue\ConsoleServiceProvider::class
			] );

		$fw->loadAliases( [
			/*
			'App' => \Penoaks\Support\Facades\App::class,
			'Artisan' => \Penoaks\Support\Facades\Artisan::class,
			'Auth' => \Penoaks\Support\Facades\Auth::class,
			'Blade' => \Penoaks\Support\Facades\Blade::class,
			'Cache' => \Penoaks\Support\Facades\Cache::class,
			'Config' => \Penoaks\Support\Facades\Config::class,
			'Cookie' => \Penoaks\Support\Facades\Cookie::class,
			'Crypt' => \Penoaks\Support\Facades\Crypt::class,
			'DB' => \Penoaks\Support\Facades\DB::class,
			'Event' => \Penoaks\Support\Facades\Event::class,
			'File' => \Penoaks\Support\Facades\File::class,
			'Gate' => \Penoaks\Support\Facades\Gate::class,
			'Hash' => \Penoaks\Support\Facades\Hash::class,
			'Lang' => \Penoaks\Support\Facades\Lang::class,
			'Logging' => \Penoaks\Support\Facades\Logging::class,
			'Mail' => \Penoaks\Support\Facades\Mail::class,
			'Password' => \Penoaks\Support\Facades\Password::class,
			'Queue' => \Penoaks\Support\Facades\Queue::class,
			'Redirect' => \Penoaks\Support\Facades\Redirect::class,
			'Redis' => \Penoaks\Support\Facades\Redis::class,
			'Request' => \Penoaks\Support\Facades\Request::class,
			'Response' => \Penoaks\Support\Facades\Response::class,
			'Route' => \Penoaks\Support\Facades\Route::class,
			'Schema' => \Penoaks\Support\Facades\Schema::class,
			'Session' => \Penoaks\Support\Facades\Session::class,
			'Storage' => \Penoaks\Support\Facades\Storage::class,
			'URL' => \Penoaks\Support\Facades\URL::class,
			'Validator' => \Penoaks\Support\Facades\Validator::class,
			'View' => \Penoaks\Support\Facades\View::class,
			*/

			// 'Socialite' => \Laravel\Socialite\Facades\Socialite::class,
			'Calendar' => MaddHatter\LaravelFullcalendar\Facades\Calendar::class,
			'Eloquent' => \Penoaks\Database\Eloquent\Model::class,
			'Image' => \Intervention\Image\Facades\Image::class,
			'Markdown' => \GrahamCampbell\Markdown\Facades\Markdown::class,
			'Notification' => \Krucas\Notification\Facades\Notification::class,
			'Notifynder' => \Fenos\Notifynder\Facades\Notifynder::class,
			'BBCodeParser' => Support\BBCodeParser::class,
			'BBHasher' => Support\BBHasher::class,
		] );
	}

	/**
	 * Define the application's command schedule.
	 *
	 * @param  Schedule $schedule
	 * @return void
	 */
	protected function schedule( Schedule $schedule )
	{
		// $schedule->command('inspire')->hourly();
	}
}
