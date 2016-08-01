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
class RedirectIfAuthenticated
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
		if ( Auth::guard( $guard )->check() )
		{
			return redirect( '/' );
		}

		return $next( $request );
	}
}
