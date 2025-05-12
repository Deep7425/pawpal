<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ehr\CityLocalities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
//use Illuminate\Mail\Mailer;
class LocalityController extends Controller {
    
    
	
		
	public function localityMaster(Request $request) {
		$search = '';
		if ($request->isMethod('post')) {
		$params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
		 if (!empty($request->input('state_id'))) {
             $params['state_id'] = base64_encode($request->input('state_id'));
         }
		 if (!empty($request->input('city_id'))) {
             $params['city_id'] = base64_encode($request->input('city_id'));
         }
		 if ($request->input('top_status')!= "") {
             $params['top_status'] = base64_encode($request->input('top_status'));
         }
		 if (!empty($request->input('page_no'))) {
             $params['page_no'] = base64_encode($request->input('page_no'));
         }
         return redirect()->route('admin.localityMaster',$params)->withInput();
		}
		else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = CityLocalities::whereIn('status', array(1,0));
			if(!empty($search)) {
				$query->where('name','like','%'.$search.'%');
			}
			if(!empty($request->input('state_id'))) {
				$state_id = base64_decode($request->input('state_id'));
				$query->where('state_id',$state_id);
			}
			if(!empty($request->input('city_id'))) {
				$city_id = base64_decode($request->input('city_id'));
				$query->where('city_id',$city_id);
			}
			if(!empty($request->input('top_status'))) {
				$top_status = base64_decode($request->input('top_status'));
				$query->where('top_status',$top_status);
			}
			$page = 25;
			if(!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no')); 
			}
			$localities = $query->orderBy('id', 'desc')->paginate($page);
		}
		return view('admin.locality.locality-master',compact('localities'));
	}

    public function addLocality(Request $request){
		if($request->isMethod('post')) {
            $data = $request->all();
		
			$fileName = "";
			if($request->hasFile('image')) {
                  $image  = $request->file('image');
                  $fullName = str_replace(" ","",$image->getClientOriginalName());
                  $onlyName = explode('.',$fullName);
                  if(is_array($onlyName)){
                    $fileName = $onlyName[0].time().".".$onlyName[1];
                  }
                  else{
                    $fileName = $onlyName.time();
                  }
                  $request->file('image')->move(public_path("/newsFeedFiles"), $fileName);
            }
			if(!empty($data['name'])){
				$locality_name = explode(',',$data['name']);
				$slug = explode(',',$data['slug']);
				foreach($locality_name as $i =>  $name){
					
					$city_l = CityLocalities::where('name','LIKE',$name)->where(['city_id'=>$data['city_id'],'state_id'=>$data['state_id']])->first();
					if(empty($city_l)) {
						CityLocalities::create([
							'name' => $name,
							'slug' => $slug[$i],
							'city_id' => $data['city_id'],
							'state_id' => $data['state_id'],
							'country_id' => $data['country_id'],
							'status' => $data['status'],
							'top_status' => 1,
						]);
					}
				}
			}
			
			Session::flash('message', "Locality Added Successfully");
			return 1;
		}
		return view('admin.locality.add-locality');
	}
	public function editLocality(Request $request) {
        $id = $request->id;
        $locality = CityLocalities::Where( 'id', '=', $id)->first();
        return view('admin.locality.edit-locality',compact('locality'));
    }
	public function updateLocality(Request $request){
        if($request->isMethod('post')) {
			$data = $request->all();
			CityLocalities::where('id', $data['id'])->update(array(
				'name' => $data['name'],
				'slug' => $data['slug'],
                'city_id' => $data['city_id'],
                'state_id' => $data['state_id'],
                'country_id' => $data['country_id'],
                'status' => $data['status'],
			));
			Session::flash('message', "Locality Updated Successfully");
			return 1;
		}
		return 2;
	}
	public function deleteLocality(Request $request) {
		$id = $request->id; 
		CityLocalities::where('id', $id)->delete();
		Session::flash('message', "Locality Deleted Successfully");
		return 1;
    }
	
	public function updateLocalityStatusTop(Request $request) {
		$id = $request->id;
		$top_status = $request->top_status;
		$city_id = $request->city_id;
		if($top_status != '0') {
			CityLocalities::where('id', $id)->update(array('top_status' => 0));
			return 0;
		}
		else{
			//$max = CityLocalities::where('city_id',$city_id)->max('top_status');
			//$max_id = $max+1;
			CityLocalities::where('id', $id)->update(array('top_status' => 1));
			return 1;
		}
    }
	


}