<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ReminderUserNotificatios;
use App\Models\NewsFeeds;
use App\Models\BlogComment;
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
class BlogsController extends Controller {




	public function blogMaster(Request $request) {
		$search = '';
		if ($request->isMethod('post')) {
		$params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
		 if (!empty($request->input('page_no'))) {
             $params['page_no'] = base64_encode($request->input('page_no'));
         }
         return redirect()->route('admin.blogMaster',$params)->withInput();
		}
		else {
         $filters = array();
			   $search = base64_decode($request->input('search'));
			   $query = NewsFeeds::whereIn('status', array(1,0));
			   if(!empty($search)){
					$query->where(DB::raw('concat(title," ",ISNULL(slug)," ")') , 'like', '%'.$search.'%');
			   }
			    $page = 25;
				if(!empty($request->input('page_no'))){
					$page = base64_decode($request->input('page_no'));
				}
			   $blogs = $query->orderBy('id', 'desc')->paginate($page);
			//    $admin = $query->orderBy('id' , 'desc')->paginate($page);
		} 

		// dd($admin);

		return view('admin.blogs.blog-master',compact('blogs'));
	}


    public function addBlog(Request $request){
		if($request->isMethod('post')) {
            $data = $request->all();
			$fileName = "";
			$videofileName = "";
			if($request->hasFile('image')) {
                  $image  = $request->file('image');
                  $fullName = str_replace(" ","",$image->getClientOriginalName());
                  $onlyName = explode('.',$fullName);
                  if(is_array($onlyName)){
                    $fileName = $onlyName[0].".".$onlyName[1];
                  }
                  else{
                    $fileName = $onlyName;
                  }
				// $filepath = public_path()."/newsFeedFiles/";
				// $request->file('image')->move($filepath, $fileName);
				$filePath = "public/newsFeedFiles/";
				Storage::disk('s3')->put($filePath.$fileName, file_get_contents($image));
            }
			NewsFeeds::create([
                'slug' => $data['slug'],
				'title' => $data['title'],
                'keyword' => $data['keyword'],
                'blog_desc' => $data['blog_desc'],
				'image' => $fileName,
                'video' => $data['video'],
                'description' => $data['description'],
				'publish_date' => date('Y-m-d h:i:s', strtotime($data['publish_date'])),
				'show_date' => date('Y-m-d h:i:s', strtotime($data['show_date'])),
                'status' => $data['status'],
                'type' => (isset($data['type']) ? implode(',',$data['type']) : null),
			]);
			// $this->myNotificationReminderBlog($data["title"],$data["keyword"]);
			Session::flash('message', "Blog Added Successfully");
			return 1;
		}
		return view('admin.blogs.add-blog');
	}

	public function editBlog(Request $request) {
		$id = $request->id;
		$action = $request->action;
		//blog edit
		if ($action == 1) {
			$blog = NewsFeeds::Where('id', '=', $id)->first();
			return view('admin.blogs.edit-blog',compact('blog'));
		}
		// video publish
		elseif ($action == 2) {
			$publish = $request->publish;
			if ($publish == 2) {
				$blog = NewsFeeds::Where('id', '=', $id)->update(['video_publish' => 1]);
			}
			else {
				$blog = NewsFeeds::Where('id', '=', $id)->update(['video_publish' => 2]);
			}
			return 1;
		}

  }
	public function viewBlog(Request $request, $id) {
		$blog = NewsFeeds::Where('slug', '=', $id)->first();
	  return view('admin.blogs.view-blog',compact('blog'));
	}


	
	public function updateBlog(Request $request){
        if($request->isMethod('post')) {
			$data = $request->all();
			$fileName = "" ;
			$videofileName = "";
			if($request->hasFile('image')) {
				$image = $request->file('image');
				$fullName = str_replace(" ","",$image->getClientOriginalName());
				$onlyName = explode('.',$fullName);
				if(is_array($onlyName)){
					$fileName = $onlyName[0].".".$onlyName[1];
				}
				else{
					$fileName = $onlyName;
				}
				$filePath = "public/newsFeedFiles/";
				Storage::disk('s3')->put($filePath.$fileName, file_get_contents($image));
				  
				// $filename = public_path().'/newsFeedFiles/'.$data['old_image'];
				// if(file_exists($filename)){
					// File::delete($filename);
				// }
				// $filepath = public_path()."/newsFeedFiles/";
				// $request->file('image')->move($filepath, $fileName);
				//$this->compress($fileName, $filepath);
		   }
		   else{
			 $fileName = $data['old_image'];
		   }
			$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', trim(strtolower($data['title'])));
			$blog = NewsFeeds::where('id', $data['id'])->first();
			NewsFeeds::where('id', $data['id'])->update(array(
				'title' => $data['title'],
				'slug' => $data['slug'],
				'keyword' => $data['keyword'],
				'blog_desc' => $data['blog_desc'],
				'image' => $fileName,
				'video' => $data['video'],
				'description' => $data['description'],
				'publish_date' => date('Y-m-d h:i:s', strtotime($data['publish_date'])),
				'show_date' => date('Y-m-d h:i:s', strtotime($data['show_date'])),
				'status' => $data['status'],
				'type' => (isset($data['type']) ? implode(',',$data['type']) : null),
			));
			Session::flash('message', "Blog Updated Successfully");
			return 1;
		}
		return 2;
	}
	
	public function deleteBlog(Request $request) {
		$id = $request->id;
		NewsFeeds::where('id', $id)->delete();
		Session::flash('message', "Blog Deleted Successfully");
		return 1;
		// return redirect()->route('SpecialitySymptomsMaster');
    }
		public function blogComments(Request $request) {
			$search = '';
			if ($request->isMethod('post')) {
			$params = array();
	         if (!empty($request->input('search'))) {
	             $params['search'] = base64_encode($request->input('search'));
	         }
			 if (!empty($request->input('page_no'))) {
	             $params['page_no'] = base64_encode($request->input('page_no'));
	         }
	         return redirect()->route('admin.blogComments',$params)->withInput();
			}
			else {
	         $filters = array();
				   $search = base64_decode($request->input('search'));
				   $query = BlogComment::with('Blog','user')->orderBy('id', 'desc');
				   if(!empty($search)){
							$query->whereHas('Blog', function($q) use ($search) {
						    $q->where(DB::raw('concat(title," ",ISNULL(slug)," ")') , 'like', '%'.$search.'%');
						  });
				   }
				    $page = 25;
					if(!empty($request->input('page_no'))){
						$page = base64_decode($request->input('page_no'));
					}
				   $comments = $query->orderBy('id', 'desc')->paginate($page);
			}
			return view('admin.blogs.blog-comments',compact('comments'));
		}



		public function blogCommentPublish(Request $request) {
			if ($request->isMethod('post')) {
				$publish = $request->publish;
				$id = $request->id;
				if ($publish == 2) {
					BlogComment::Where('id', '=', $id)->update(['publish' => 1]);
				}
				else {
					BlogComment::Where('id', '=', $id)->update(['publish' => 2]);
				}
			}
			return 1;
		}

	public function compress($source, $destination) {
		$info = getimagesize($destination."/".$source);
		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($destination."/".$source);
		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($destination."/".$source);
		else return $destination;
		imagejpeg($image, $destination."/".$source, 85);
		return $destination;
	}

		public function myNotificationReminderBlog($message,$title) {
			ReminderUserNotificatios::create([
				"module_slug"=> $title,
				"notification"=> $message
			]);
			$users = User::select(["fcm_token","device_type"])->where(['status'=>1])->get();
			if(count($users) > 0){
				foreach($users as $user) {
					$subtitle = $title;
					$tickerText = 'text here...';
					$fcm_token = $user->fcm_token;
					$device_type = $user->device_type;
					if($device_type == 1 && !empty($fcm_token)) {
						$notifyres = Parent::pn($this->notificationKey,$fcm_token,$message,$title,$subtitle,$tickerText,'notifications');
					}
					else if($device_type == 2 && !empty($fcm_token)) {
						$iosnotify = Parent::iosNotificationSend($fcm_token,$message,$title,'notifications');
					}
				}
				return 1;
			}
		}
}
