<?php

/*
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */

use HolyWorlds\Middleware\Authenticate;
use Milky\Account\Middleware\RedirectIfAuthenticated;
use Milky\Auth\Middleware\AuthenticateWithBasicAuth;

ini_set( 'display_errors', 'On' );

require_once realpath( __DIR__ . '/vendor/autoload.php' );

try
{
	( new Dotenv\Dotenv( __DIR__ ) )->load();
}
catch ( Dotenv\Exception\InvalidPathException $e )
{

}

/*
$fw->bindings->singleton( 'exceptionHandler', \HolyWorlds\Exceptions\Handler::class );
$fw->bindings->singleton( 'consoleKernel', \HolyWorlds\Console\Kernel::class );

$fw->withFacades();

$app = $fw->bindings->make( 'app' );

$fw->providers->add( new \HolyWorlds\Providers\AppServiceProvider( $fw ) );
$fw->providers->add( new \HolyWorlds\Providers\AuthServiceProvider( $fw ) );
$fw->providers->add( new \HolyWorlds\Providers\EventServiceProvider( $app ) );
$fw->providers->add( new \HolyWorlds\Providers\RouteServiceProvider( $fw ) );

$fw->providers->add( new \Krucas\Notification\NotificationServiceProvider( $app ) );

/* \Laravel\Socialite\SocialiteServiceProvider::class,
\TeamTeaTime\Filer\FilerServiceProvider::class,
\GrahamCampbell\Markdown\MarkdownServiceProvider::class,
\Conner\Tagging\Providers\TaggingServiceProvider::class,
\MaddHatter\LaravelFullcalendar\ServiceProvider::class,
\Slynova\Commentable\ServiceProvider::class,
\Intervention\Image\ImageServiceProvider::class,
/

$fw->providers->add( new \Barryvdh\Debugbar\ServiceProvider( $app ) );
$fw->providers->add( new \Fenos\Notifynder\NotifynderServiceProvider( $app ) );

$fw->providers->add( new \HolyWorlds\Providers\FinalServiceProvider( $app ) );

$fw->boot();




$fw->commands->add( [
	\Shared\Commands\MigrateHolyWorlds::class,
	\Shared\Commands\PushServer::class
] );

$fw->providers->add( [
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
	*

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
*/

\Milky\Exceptions\Handler::extend( \HolyWorlds\Exceptions\Handler::class );

$fw = fw( realpath( __DIR__ . '/../' ) );

$fw->boot();

$factory = \Milky\Http\HttpFactory::i();

$factory->addMiddleware( [
	\Milky\Http\Middleware\CheckForMaintenanceMode::class,
	// \HolyWorlds\Middleware\ClearCache::class,
] );

$factory->addMiddlewareGroup( 'api', [
	'throttle:60,1',
] );

$factory->addMiddlewareGroup( 'web', [
	\Milky\Http\Middleware\EncryptCookies::class,
	\Milky\Http\Session\SessionManager::class,
	\Milky\Http\Middleware\ShareSessionMessages::class,
	\Milky\Http\Middleware\VerifyCsrfToken::class,
	\Milky\Http\Middleware\AddQueuedCookiesToResponse::class,
] );

$factory->addRouteMiddleware( 'auth', Authenticate::class );
$factory->addRouteMiddleware( 'auth.basic', AuthenticateWithBasicAuth::class );
// $factory->addRouteMiddleware( 'can', Authorize );
$factory->addRouteMiddleware( 'guest', RedirectIfAuthenticated::class );
// $factory->addRouteMiddleware( 'throttle', ThrottlesRequests::class );

/*
$fw->addRouteMiddleware( 'auth', [
	HolyWorlds\Middleware\Authenticate::class,
] );
*/

$factory->setRootControllerNamespace( 'HolyWorlds\Controllers' );

$r = $factory->router();

require_once __DIR__ . '/src/routes.php';
loadRoutes( $r );

$response = $factory->routeRequest();

$response->send();

$factory->terminate( $factory->request(), $response );
