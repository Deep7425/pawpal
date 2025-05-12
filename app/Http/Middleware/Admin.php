<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Admin{
    public function handle($request, Closure $next){
        $userid=Session::get('id');
        if($userid==""){
                return redirect("admin")->send();
        }
        return $next($request);
    }
}
