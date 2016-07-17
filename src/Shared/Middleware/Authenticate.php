<?php
namespace Shared\Middleware;

use Closure;
use Penoaks\Support\Facades\Auth;

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
