<?php

namespace App\Http\Middleware;

use Closure;
class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next, $role){
         $work_area = auth()->user()->work_area;
         $type = auth()->user()->type;
         if(($work_area==$role) && ($type=="User")){
           return $next($request);
         }

         else
           return redirect()->route('cis.login_form');
     }
}
