<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminModules;
use Closure;
use Illuminate\Support\Facades\Session;

class CheckModuleAccessMiddleware extends Controller {

    /**

     * Handle an incoming request.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \Closure  $next

     * @return mixed

     */

    public function handle($request, Closure $next, $group = null){
		$user_id = Session::get('userdata')->id;
		$permissions = Admin::select('module_permissions')->Where('id', $user_id)->first();
		$module_id = AdminModules::select(['id','module_name'])->where(['slug'=>$group])->first();
		if(!empty($permissions->module_permissions)){
			$access = explode(',',$permissions->module_permissions);
			if(in_array($module_id->id,$access)){
			  return $next($request);
			}else{
			  return abort(401);
			}
		  }else{
			return abort(401);
		 }
        return $next($request);
    }

}
