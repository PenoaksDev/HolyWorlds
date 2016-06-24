<?php

namespace App\Http\Middleware;

use Closure;

class ClearCache
{
	public function handle($request, Closure $next)
	{
		if ( !\App::environment('production') )
		{
			$cachedViewsDirectory = app('path.storage') . '/framework/views/';
			$files = glob( $cachedViewsDirectory . '*' );
			foreach( $files as $file )
				if( is_file( $file ) )
					@unlink($file);
		}

		return $next($request);
	}
}
