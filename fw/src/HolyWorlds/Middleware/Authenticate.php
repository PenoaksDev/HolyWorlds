<?php namespace HolyWorlds\Middleware;

use Closure;
use Milky\Account\AccountManager;
use Milky\Facades\Redirect;
use Milky\Facades\Response;
use Milky\Http\Request;

class Authenticate
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  Request $request
	 * @param  \Closure $next
	 * @param  string|null $guard
	 * @return mixed
	 */
	public function handle( $request, Closure $next, $guard = null )
	{
		if ( AccountManager::i()->guard( $guard )->guest() )
		{
			if ( $request->ajax() || $request->wantsJson() )
				return Response::make( 'Unauthorized.', 401 );
			else
				return Redirect::guest( 'login' );
		}

		return $next( $request );
	}
}
