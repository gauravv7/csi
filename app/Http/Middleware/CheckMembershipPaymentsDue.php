<?php

namespace App\Http\Middleware;

use App\Payment;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckMembershipPaymentsDue
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
        if ( ($user = Auth::user()->user()) ) {
            if (!Payment::filterByServiceAndMember(1, $user->id)->first()) {
                $entity = $user->getEntity();
                return redirect()->route('createSubmitToPayments', ['entity' => $entity]);
            } 
        } else {
            return redirect()->guest('/login');
        }
        return $next($request);
    }
}
