<?php

use Milky\Http\Routing\Router;

function loadRoutes( Router $r )
{
	$r->group( ['namespace' => 'App\Controllers'], function ( Router $r )
	{
		$r->get( '/', function ()
		{
			return "<h1>Thank You for Using Milky Framework</h1>"
		} );
	} );
}
