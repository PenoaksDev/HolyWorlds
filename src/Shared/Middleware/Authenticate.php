<?php
namespace Shared\Middleware;

use Closure;
use Penoaks\Support\Facades\Auth;

/*
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class Authenticate
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Foundation\Http\Request $request
	 * @param  \Closure $next
	 * @param  string|null $guard
	 * @return mixed
	 */
	public function handle( $request, Closure $next, $guard = null )
	{
		if ( Auth::guard( $guard )->guest() )
		{
			if ( $request->ajax() || $request->wantsJson() )
			{
				return response( 'Unauthorized.', 401 );
			}
			else
			{
				return redirect()->guest( 'login' );
			}
		}

		return $next( $request );
	}
}
