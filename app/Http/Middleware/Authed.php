<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class Authed
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
        $uuid = Cookie::get('uuid');

        if(!is_null($uuid)){
            Cookie::queue(Cookie::make('uuid', $uuid, 10));

        }else{
            return redirect()->route('login');
        }
        return $next($request);
    }
}
