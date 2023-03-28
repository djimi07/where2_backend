<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Session;
class AdminViewUnAuth
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
        $web_token =  Session::get('AuthAdminWebToken');
     
        if(!empty($web_token))
        {
            $user = User::where('web_token','=',$web_token)->first();
            if(empty($user))
            {
                Session::put('AuthAdminWebToken', '');
                return redirect('/admin');
            }

            if($user->user_type != 'ADMIN')
            {
                Session::put('AuthAdminWebToken', '');
                return redirect('/admin');
            }
            return redirect('/admin/dashboard');
        }

        return $next($request);
    }
}
