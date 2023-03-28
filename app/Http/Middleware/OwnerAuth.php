<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class OwnerAuth
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
        if($user->user_type != 'USER')
            return response()->json(['status' => 401,'msg' => 'Unauthenticated Owner.'],401);

        return $next($request);
    }
}
