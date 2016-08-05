<?php namespace HolyWorlds\Middleware;

use Closure;
use Milky\Framework;

class ClearCache
{
	public function handle( $request, Closure $next )
	{
		if ( !Framework::fw()->environment( 'production' ) )
		{
			$cachedViewsDirectory = Framework::fw()->buildPath( '__storage', 'views' );
			$files = glob( $cachedViewsDirectory . '*' );
			foreach ( $files as $file )
				if ( is_file( $file ) )
					@unlink( $file );
		}

		return $next( $request );
	}
}
