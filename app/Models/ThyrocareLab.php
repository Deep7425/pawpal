<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ThyrocareLab extends Authenticatable{
    use Notifiable;
    protected $table = 'thyrocare_labs';
	protected $fillable = ['name','common_name','code','aliasName','type','childs','rate','testCount','benMin','benMultiple','benMax','payType','serum','edta','urine','fluoride','fasting','new','diseaseGroup','units','volume','normalVal','groupName','margin','hc','specimenType','testNames','additionalTests','imageLocation','imageMaster','validTo','hcrInclude','ownPkg','bookedCount','barcodes','category'];
    public $timestamps = false;
	
	function getChildsAttribute($value) {
      return json_decode($value,true);
    }
	function getRateAttribute($value) {
      return json_decode($value,true);
    }
	function getImageMasterAttribute($value) {
      return json_decode($value,true);
    }
	function getCommonNameAttribute($value) {
      return strtoupper($value);
    }
	function getNameAttribute($value) {
      return strtoupper($value);
    }
}