<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Session;

class UserViewAuth
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
        $web_token =  Session::get('AuthUserWebToken');
        if(empty($web_token))
        {
            return redirect('/login/service-provider');
        }

        return $next($request);
    }
}
