<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
     public $setLayout = 'admin';
    
    function __construct(){
     $action     = \Route::getFacadeRoot()->currentRouteAction();
     $actionParam= explode("\\" , $action);
     $route      = explode("@" , $actionParam[count($actionParam)-1]);
     $controller = str_replace("Controller","",$route[0]);   
     $this->setLayout=$this->setLayout.".{$controller}.";
        
    }
    function make($path,$arry=[]){

      try{

        $arry['pageContent']= \View::make($this->setLayout.$path,$arry);

      }catch(\Exception $exception){

        return back()->withError($exception->getMessage())->withInput();
      }
  
      return \View::make("admin.layouts.default",$arry);     
   
      
       //return View::make($this->setLayout.$path,$arry);
    }
}
 
