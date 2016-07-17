<?php

use Penoaks\Framework\Env;
use Penoaks\Support\Facades\Log;

/*
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */

define( 'FRAMEWORK_START', microtime( true ) );

/* Force the display of errors */
ini_set( 'display_errors', 'On' );

/* Prevent the display of E_NOTICE and E_STRICT */
error_reporting( E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED );

/**
 * Super simple exception handler, should never be seen if framework is operating normally
 */
set_exception_handler( function ( Throwable $e )
{
	echo( "<h1 style='margin-bottom: 0;'>Exception Uncaught</h1><br />\n" );
	echo( "<b>" . ( new ReflectionClass( $e ) )->getShortName() . ": " . $e->getMessage() . "</b><br />\n" );
	echo( "<p>at " . $e->getFile() . " on line " . $e->getLine() . "</p>\n" );
	echo( "<pre>" . $e->getTraceAsString() . "</pre>" );
	die();
} );

/*
 * Creates class aliases for missing classes, intended for loose prototyping.
 * TODO Logging developer warnings when these are used
 * TODO Implement a strict mode for production environments.
 */
spl_autoload_register( function ( $class )
{
	$namespace = explode( '\\', $class );
	$className = array_pop( $namespace );
	if ( class_exists( $className ) ) // Check if we can alias the class to a root class
	{
		developerWarning( $className );

		$reflection = new ReflectionClass( $className );
		if ( $reflection->isUserDefined() )
		{
			class_alias( $className, $class );
		}
		else if ( !$reflection->isFinal() )
		{
			// class_alias() is not allowed to alias non-user defined PHP classes, so instead we artificially extend them.
			$ns = implode( '\\', $namespace );
			eval( "namespace $ns; class $className extends \\$className {}" );
		}
		else
		{
			die( "Class [" . $class . "] was found but we were unable to alias or extend because it's non-user defined and final." );
		}
	}
	else if ( class_exists( "Penoaks\\Support\\" . $className ) ) // Check if we can alias the class to our Support classes
		if ( class_alias( "Penoaks\\Support\\" . $className, $class ) )
			if ( class_exists( 'Logging' ) )
				Log::debug( "Set class alias [" . $class . "] to [Penoaks\\Support\\" . $className . "]" );
} );

function developerWarning( $class = null )
{
	if ( Env::get( 'env' ) == 'production' )
		throw new RuntimeException( "Lazy class loading is prohibited in production environments." );
	else if ( class_exists( 'Logging' ) )
		Log::warning( "Class " . $class . " is being lazy loaded at file " . \Penoaks\Support\Func::lastHop() );
}

/**
 * @param $params
 * @param $paths
 * @return \Penoaks\Framework
 */
function initFramework( $params, $paths )
{
	/* If $paths is not an array, make it one and assume the string is the base path */
	if ( !is_array( $paths ) )
		$paths = ['base' => $paths];

	/* Make sure project base directory is set */
	if ( !array_key_exists( 'base', $paths ) )
		if ( error_reporting() == E_STRICT )
			throw new RuntimeException( "You must specify the project base directory." );
		else
			$paths['base'] = realpath( __DIR__ . '/..' );

	/* Strip trailing slashes */
	foreach ( $paths as $key => $val )
		$paths[$key] = rtrim( $val, '\/' );

	/* Add additional missing paths */
	foreach ( ['src', 'config', 'vendor', 'cache', 'vendor', 'storage'] as $key )
		if ( !array_key_exists( $key, $paths ) )
			$paths[$key] = $paths['base'] . '/' . $key;

	/* Register the Compose Auth Loader */
	$loader = require $paths['vendor'] . '/autoload.php';

	/* Initialize a new instance of the Framework Constructor */
	$fw = new \Penoaks\Framework( $loader, $params, $paths );

	/* Return newly started framework */

	return $fw;
}
