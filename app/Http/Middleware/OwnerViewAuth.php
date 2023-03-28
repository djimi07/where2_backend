<?php

namespace App\Http\Middleware;
use App\Models\User;
use Closure;
use Session;
class OwnerViewAuth

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
        if(empty($web_token))
        {
            Session::put('AuthOwnerWebToken', '');
            return redirect('/owner');
        }
        $user = User::where('web_token','=',$web_token)->first();
        if(empty($user))
        {
            Session::put('AuthOwnerWebToken', '');
            return redirect('/owner');
        }

        if($user->user_type != 'USER')
        {
            Session::put('AuthOwnerWebToken', '');
            return redirect('/owner');
        }
        if($user->is_owner == 0)
        {
            Session::put('AuthOwnerWebToken', '');
            return redirect('/owner');
        }
        return $next($request);
    }
}
