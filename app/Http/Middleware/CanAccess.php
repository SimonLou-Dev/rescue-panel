<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class CanAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Session::get('user_grade')[0]->perm_0){
            return $next($request);
        }else{
            if($request->getRequestUri() == '/'){
                Auth::logout();
                Session::flush();
            }
            return redirect()->route('ANA');
        }
    }
}
