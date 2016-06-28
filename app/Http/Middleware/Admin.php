<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Admin extends Authenticate
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

    if (!Auth::user()->isAdmin()) {
      return response()->make('You are not authorized.', 401);
    }

    return $next($request);
  }
}
