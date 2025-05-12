<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class AdminBeforeLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $adminUserid = Session::get('id');
       if($adminUserid !=''){
           return redirect("admin/home")->send();
       }

        return $next($request);
    }
}
