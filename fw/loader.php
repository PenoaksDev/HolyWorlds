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

$fw = fw( realpath( __DIR__ . '/../' ) );

$fw->setExceptionHandler( new \HolyWorlds\Exceptions\Handler() );

$fw->boot();

$factory = \Milky\Http\Factory::i();

$factory->setRootControllerNamespace( 'App\Controllers' );

$r = $factory->router();

require_once __DIR__ . '/src/routes.php';
loadRoutes( $r );

$response = $factory->routeRequest();

$response->send();
