<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class checkUserPaymentsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if ( Auth::user()->user() ) {
            if ($request->ajax() && !Auth::user()->user()->checkMembershipPaymentValidity()) {
                return response('Unauthorized.', 401);
            }
        } else{
            return redirect()->guest('/login');
        }
        return $next($request);
    }
}
