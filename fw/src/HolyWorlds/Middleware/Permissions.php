<?php
namespace HolyWorlds\Middleware;

use Closure;
use Penoaks\Support\Facades\Auth;
use Models\PermissionDefaults;
use HolyWorlds\Support\Util;

class Permissions
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle( $request, Closure $next, $perm = null )
	{
		if ( $perm == null || empty( $perm ) )
		{
			return $next( $request );
		}

		if ( Auth::check() )
		{
			if ( self::checkPermission( $perm ) )
			{
				return $next( $request );
			}
			else
			{
				return view( "permissions.denied" );
			} // TODO Redirect to login with error message?
		}

		return redirect( 'auth/login' );
	}

	public static function checkPermission( $permission, $entity = null )
	{
		if ( empty( $permission ) )
		{
			return true;
		}

		if ( $entity === null )
		{
			if ( !Auth::check() )
			{
				return false;
			}
			$entity = Auth::user();
		}

		$def = PermissionDefaults::find( $permission );

		foreach ( $entity->permissions as $p )
		{
			if ( empty( $p->permission ) )
			{
				continue;
			} // Ignore empty permissions

			try
			{
				if ( preg_match( Util::prepareExpression( $p->permission ), $permission ) )
				{
					return empty( $p->value ) ? ( $def === null ? true : $def->value_assigned ) : $p->value;
				}
			}
			catch ( Exception $e )
			{
				// Ignore preg_match() exceptions
			}
		}

		foreach ( $entity->groups() as $group )
		{
			$result = self::checkPermission( $permission, $group ); // TODO Compare group results and sort by weight
			if ( $result !== false )
			{
				return $result;
			}
		}

		return $def === null ? false : $def->value_default;
	}
}
