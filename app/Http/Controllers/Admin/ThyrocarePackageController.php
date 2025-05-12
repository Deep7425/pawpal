<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ThyrocarePackageGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
//use Illuminate\Mail\Mailer;
class ThyrocarePackageController extends Controller {




	public function thyrocarePackageMaster(Request $request) {
		$search = '';
		if ($request->isMethod('post')) {
		$params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
		 if (!empty($request->input('page_no'))) {
             $params['page_no'] = base64_encode($request->input('page_no'));
         }
         return redirect()->route('admin.thyrocarePackageMaster',$params)->withInput();
		}
		else {
         $filters = array();
			   $search = base64_decode($request->input('search'));
			   $query = ThyrocarePackageGroup::where('delete_status', 1);
			   if(!empty($search)){
					$query->where('group_name','like','%'.$search.'%');
			   }
			    $page = 25;
				if(!empty($request->input('page_no'))){
					$page = base64_decode($request->input('page_no'));
				}
			   $packages = $query->orderBy('id', 'desc')->paginate($page);
		}
		return view('admin.thyrocare_package.package-master',compact('packages'));
	}

    public function addThyrocarePackage(Request $request){
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
                  $request->file('image')->move(public_path("/thyrocarePackageFiles"), $fileName);
            }
			ThyrocarePackageGroup::create([
                'group_name' => $data['group_name'],
                'image' => $fileName,
                'status' => $data['status'],
			]);
			Session::flash('message', "Thyrocare Package Added Successfully");
			return 1;
		}
		return view('admin.thyrocare_package.add-package');
	}
	public function editThyrocarePackage(Request $request) {
        $id = $request->id;
        $package = ThyrocarePackageGroup::Where('id', '=', $id)->first();
        return view('admin.thyrocare_package.edit-package',compact('package'));
    }
	public function updateThyrocarePackage(Request $request){
        if($request->isMethod('post')) {
			$data = $request->all();
			$fileName = "" ;
			if ($request->hasFile('image')) {
			 $filename = public_path().'/thyrocarePackageFiles/'.$data['old_image'];
			 if(file_exists($filename)){
			   File::delete($filename);
			 }
			$image  = $request->file('image');
				$fullName = str_replace(" ","",$image->getClientOriginalName());
				$onlyName = explode('.',$fullName);
				if(is_array($onlyName)){
					$fileName = $onlyName[0].time().".".$onlyName[1];
				}
				else{
					$fileName = $onlyName.time();
				}
				 $request->file('image')->move(public_path("/thyrocarePackageFiles"), $fileName);
		   }
		   else{
			 $fileName = $data['old_image'];
		   }
			ThyrocarePackageGroup::where('id', $data['id'])->update(array(
								'group_name' => $data['group_name'],
                'image' => $fileName,
                'status' => $data['status'],
			));
			Session::flash('message', "Thyrocare Package Updated Successfully");
			return 1;
		}
		return 2;
	}
	public function deleteThyrocarePackage(Request $request) {
		$id = $request->id;
		$banner = ThyrocarePackageGroup::where('id', $id)->first();
		$filename = public_path().'/thyrocarePackageFiles/'.$banner->image;
		if(file_exists($filename)){
		   File::delete($filename);
		}
		ThyrocarePackageGroup::where('id', $id)->update(['delete_status' => '0']);
		Session::flash('message', "Thyrocare Package Deleted Successfully");
		return 1;
		// return redirect()->route('SpecialitySymptomsMaster');
    }



}
