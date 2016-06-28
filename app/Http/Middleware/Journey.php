<?php

namespace App\Http\Middleware;

use Closure;

class Journey extends Authenticate
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
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }
    
        $user = \Auth::user();
        if ($user) {
            $userId = $user->id;
            $vehicle = \App\Models\Vehicle::retrieveByUserId($userId);
            // Admin Always gets in Dun Care
            if (\Auth::user()->isAdmin() || ($vehicle && count($vehicle))) {
                return $next($request);
            } else {
                $request->session()->flash('error', 'No Vehicles Found, Unable to Create a Journey!');
                return redirect('/vehicle/create');
            }
        } else {
            return redirect()->guest('auth/login');
        }
    }
}
