<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Permission;

class Permissions
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $perm = null)
	{
		if ( $perm == null || empty( $perm ) )
			return $next($request);

		if ( Auth::check() )
		{
			if ( Permission::find( $perm )->check() )
				return $next($request);
			else
				return view( "permissions.denied" ); // TODO Redirect to login with error message!
		}

		return redirect('auth/login');
	}
}
