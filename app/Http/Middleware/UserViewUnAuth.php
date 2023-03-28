<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Session;

class UserViewUnAuth
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
        $web_token =  Session::get('AuthOwnerWebToken');
        if(!empty($web_token))
        {
            $user = User::where('web_token','=',$web_token)->first();
            if(empty($user))
            {
                Session::put('AuthOwnerWebToken','');
                return redirect('/');
            }

            if($user->user_type == 'ADMIN')
            {
                Session::put('AuthOwnerWebToken', '');
                return redirect('/');
            }

            if($user->user_type == 'Owner')
            {
                // Session::put('AuthOwnerWebToken', '');
                return redirect('/owner/dashboard');
            }

        }


        return $next($request);
    }
}
