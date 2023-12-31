<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminAuth
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
        $user = Auth::user();
        if($user->user_type != 'ADMIN')
            return response()->json(['status' => 401,'msg' => 'Unauthenticated.'],401);

        return $next($request);
    }
}
