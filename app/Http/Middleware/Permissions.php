<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

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
        if ( Auth::check() )
        {
            if ( $perm == null || empty( $perm ) )
                return $next($request);

            if ( Auth::user()->hasPermission( $perm ) )
                return $next($request);
            else
            {
                return view( "permissions.denied" );
            }

            return $next($request);
        }

        return redirect('auth/login');
    }
}
