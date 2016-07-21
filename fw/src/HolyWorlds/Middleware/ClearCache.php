<?php
namespace HolyWorlds\Middleware;

use Closure;
use Penoaks\Http\Middleware\BaseMiddleware;

class ClearCache extends BaseMiddleware
{
	public function handle( $request, Closure $next )
	{
		if ( !$this->fw->environment( 'production' ) )
		{
			$cachedViewsDirectory = $this->fw->buildPath( 'storage' ) . '/framework/views/';
			$files = glob( $cachedViewsDirectory . '*' );
			foreach ( $files as $file )
				if ( is_file( $file ) )
					@unlink( $file );
		}

		return $next( $request );
	}
}
