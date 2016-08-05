<?php

/*
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */

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

$fw->addMiddleware( [
	\Penoaks\Http\Middleware\CheckForMaintenanceMode::class,
	\HolyWorlds\Middleware\ClearCache::class,
] );

$fw->addMiddlewareGroup( 'api', [
	'throttle:60,1',
] );

$fw->addMiddlewareGroup( 'web', [
	\HolyWorlds\Middleware\EncryptCookies::class,
	\Penoaks\Cookie\Middleware\AddQueuedCookiesToResponse::class,
	\Illuminate\Session\Middleware\StartSession::class,
	\Krucas\Notification\Middleware\NotificationMiddleware::class,
	\Illuminate\View\Middleware\ShareErrorsFromSession::class,
	\HolyWorlds\Middleware\VerifyCsrfToken::class,
] );

$fw->addRouteMiddleware( 'auth', [
	HolyWorlds\Middleware\Authenticate::class,
] );

$fw->terminate();
*/

\Milky\Exceptions\Handler::extend( \HolyWorlds\Exceptions\Handler::class );

$fw = fw( realpath( __DIR__ . '/../' ) );

$fw->boot();

$factory = \Milky\Http\HttpFactory::i();

$factory->setRootControllerNamespace( 'HolyWorlds\Controllers' );

$r = $factory->router();

require_once __DIR__ . '/src/routes.php';
loadRoutes( $r );

$response = $factory->routeRequest();

$response->send();

$factory->terminate( $factory->request(), $response );
