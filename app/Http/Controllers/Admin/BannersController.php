<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BannerMaster;
use App\Models\LabPackage;
use App\Models\Admin\AdMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
//use Illuminate\Mail\Mailer;
class BannersController extends Controller {

	public function offersBannerMaster(Request $request) {
		$search = '';
		if ($request->isMethod('post')) {
		$params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
		 if (!empty($request->input('page_no'))) {
             $params['page_no'] = base64_encode($request->input('page_no'));
         }
         return redirect()->route('admin.offersBannerMaster',$params)->withInput();
		}
		else {
         $filters = array();
			   $search = base64_decode($request->input('search'));
			   $query = BannerMaster::whereIn('status', array(1,0));
			   if(!empty($search)){
					$query->where('title','like','%'.$search.'%');
			   }
			    $page = 25;
				if(!empty($request->input('page_no'))){
					$page = base64_decode($request->input('page_no'));
				}
			   $banners = $query->orderBy('id', 'desc')->paginate($page);
		}
		return view('admin.offers.banner-master',compact('banners'));
	}

     public function addOffersBanner(Request $request){
		if($request->isMethod('post')) {
            $data = $request->all();
						// dd($data);
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
				$filepath = public_path()."/offerBannerFiles/";
                $request->file('image')->move($filepath, $fileName);
				// $this->compress($fileName, $filepath);
            }
			BannerMaster::create([
					'title' => $data['title'],
					'image' => $fileName,
					'type' => $data['type'],
					'link_url' => $data['link_url'],
					'status' => $data['status'],
					'package_id' => $data['package_id'],
					'banner_type' => $data['banner_type'],
			]);
			Session::flash('message', "Offer Banner Added Successfully");
			return 1;
		}
		$labPackageid = labPackage::all();
		return view('admin.offers.add-banner',compact('labPackageid'));
	}
	public function editOffersBanner(Request $request) {
        $id = $request->id;
				$labPackageid = labPackage::all();
        $banner = BannerMaster::Where( 'id', '=', $id)->first();
        return view('admin.offers.edit-banner',compact('banner','labPackageid'));
    }
	public function updateOffersBanner(Request $request){
        if($request->isMethod('post')) {
			$data = $request->all();
			$fileName = "" ;
			if ($request->hasFile('image')) {
			 $filename = public_path().'/offerBannerFiles/'.$data['old_image'];
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
				$filepath = public_path()."/offerBannerFiles/";
				$request->file('image')->move($filepath, $fileName);
				// $this->compress($fileName, $filepath);
		   }
		   else{
			 $fileName = $data['old_image'];
		   }
			BannerMaster::where('id', $data['id'])->update(array(
				'title' => $data['title'],
				'image' => $fileName,
				'type' => $data['type'],
				'link_url' => $data['link_url'],
				'status' => $data['status'],
				'package_id' => $data['package_id'],
				'banner_type' => $data['banner_type'],
			));
			Session::flash('message', "Offer Banner Updated Successfully");
			return 1;
		}
		return 2;
	}
	public function deleteOffersBanner(Request $request) {
		$id = $request->id;
		$banner = BannerMaster::where('id', $id)->first();
		$filename = public_path().'/offerBannerFiles/'.$banner->image;
		if(file_exists($filename)){
		   File::delete($filename);
		}
		BannerMaster::where('id', $id)->delete();
		Session::flash('message', "Offer Banner Deleted Successfully");
		return 1;
		// return redirect()->route('SpecialitySymptomsMaster');
    }

	public function compress($source, $destination) {
		$info = getimagesize($destination."/".$source);
		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($destination."/".$source);
		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($destination."/".$source);
		else return $destination;
		imagejpeg($image, $destination."/".$source, 60);
		return $destination;
	}


	public function adBannerMaster(Request $request) {
		$search = '';
		if ($request->isMethod('post')) {
		$params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
		 if (!empty($request->input('page_no'))) {
             $params['page_no'] = base64_encode($request->input('page_no'));
         }
         return redirect()->route('admin.adBannerMaster',$params)->withInput();
		}
		else {
         $filters = array();
			   $search = base64_decode($request->input('search'));
			   $query = AdMaster::whereIn('status', array(1,0));
			   if(!empty($search)){
					$query->where('title','like','%'.$search.'%');
			   }
			    $page = 25;
				if(!empty($request->input('page_no'))){
					$page = base64_decode($request->input('page_no'));
				}
			   $banners = $query->orderBy('id', 'desc')->paginate($page);
		}
		return view('admin.adv.banner-master',compact('banners'));
	}

     public function addAdBanner(Request $request){
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
				$filepath = public_path()."/adBannerFiles/";
                $request->file('image')->move($filepath, $fileName);
				// $this->compress($fileName, $filepath);
            }
			AdMaster::create([
				'title' => $data['title'],
				'image' => $fileName,
				'type' => $data['type'],
				'expiry_date' => date('Y-m-d h:i:s', strtotime($data['expiry_date'])),
				'area' => $data['area'],
				'link_url' => $data['link_url'],
				'status' => $data['status'],

			]);
			Session::flash('message', "Offer Banner Added Successfully");
			return 1;
		}
		return view('admin.adv.add-banner');
	}
	public function editAdBanner(Request $request) {
        $id = $request->id;
        $banner = AdMaster::Where( 'id', '=', $id)->first();
        return view('admin.adv.edit-banner',compact('banner'));
    }
	public function updateAdBanner(Request $request){
        if($request->isMethod('post')) {
			$data = $request->all();
			$fileName = "" ;
			if ($request->hasFile('image')) {
			 $filename = public_path().'/adBannerFiles/'.$data['old_image'];
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
				$filepath = public_path()."/adBannerFiles/";
				$request->file('image')->move($filepath, $fileName);
				// $this->compress($fileName, $filepath);
		   }
		   else{
			 $fileName = $data['old_image'];
		   }
			AdMaster::where('id', $data['id'])->update(array(
				'title' => $data['title'],
                'image' => $fileName,
				'type' => $data['type'],
				'expiry_date' => date('Y-m-d h:i:s', strtotime($data['expiry_date'])),
				'area' => $data['area'],
				'link_url' => $data['link_url'],
                'status' => $data['status'],
			));
			Session::flash('message', "Offer Banner Updated Successfully");
			return 1;
		}
		return 2;
	}
	public function deleteAdBanner(Request $request) {
		$id = $request->id;
		$banner = AdMaster::where('id', $id)->first();
		$filename = public_path().'/adBannerFiles/'.$banner->image;
		if(file_exists($filename)){
		   File::delete($filename);
		}
		AdMaster::where('id', $id)->delete();
		Session::flash('message', "Offer Banner Deleted Successfully");
		return 1;
		// return redirect()->route('SpecialitySymptomsMaster');
    }




}
