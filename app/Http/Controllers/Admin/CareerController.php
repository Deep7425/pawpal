<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\ehr\JobCategory;
use App\Models\ehr\JobApplications;
use App\Models\ehr\Jobs;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use DB;
use URL;
use Mail;
use Auth;
use File;
use Hash;
use Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CareerController extends Controller {
    /*
     * Create a new controller instance.
     *
     * @return void
     */
   

   public function careerManage(Request $request){
     $search = '';
     if ($request->isMethod('post')) {
       $params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
         return redirect()->route('careerManage',$params)->withInput();
       }
       else{
           $filters = array();
           $cats = JobCategory::where(['delete_status'=>0])->orderBy('id', 'desc')->paginate(10);
           if ($request->input('search')  != '') {
               $search = base64_decode($request->input('search'));
               $cats = JobCategory::where('title','like','%'.$search.'%')->where(['delete_status'=>0])->orderBy('id', 'desc')->paginate(10);
           }
       }
       return view('admin.career.careerManage',compact('cats'));
   }
   public function addCategory(Request $request){
       if ($request->isMethod('post')) {
          $data = $request->all();
          $user =  JobCategory::create(['title' => $data['job_title']]);
        Session::flash('message', "Category Added Successfully");
        return 1;
     }
     return view('admin.career.categoryAdd');
   }

   public function editCategory(Request $request,$id){
     if($request->isMethod('post')) {
          $data = $request->all();
          $user =  JobCategory::where('id',base64_decode($id))->update(['title' => $data['job_title']]);
          Session::flash('message', "Category Updated Successfully");
        return 1;
     }
     $cats = JobCategory::Where('id', '=', base64_decode($id))->first();
     return view('admin.career.editComp',compact('cats'));
   }

   public function deleteCategory($id){
     JobCategory::where('id',base64_decode($id))->update(['delete_status' => 1]);
     Session::flash('message', "Category Deleted Successfully");
     return redirect()->route('careerManage');
   }

   public function updateCatStatus(Request $request) {
     if($request->isMethod('post')) {
        $data = $request->all();
        if($data['status'] == 0){
          JobCategory::where('id',$data['id'])->update(['status' => 1]);
        }
        else{
          JobCategory::where('id',$data['id'])->update(['status' => 0]);
        }
        return 1;
     }
     Session::flash('message', "Staus Update Successfully");
     return redirect()->route('careerManage');
   }

   public function jobManage(Request $request)
    {
        $search = '';


        if ($request->isMethod('post')) {
            $params = array();
           // dd($request->all());

            if (!empty($request->input('search'))) {
                $params['search'] = base64_encode($request->input('search'));
            }
            if (!empty($request->input('cat_id'))) {
                $params['cat_id'] = base64_encode($request->input('cat_id'));
            }
            return redirect()->route('jobManage', $params)->withInput();


           
        } else {
            $jobs = Jobs::where(['delete_status' => 0]);
            $cats = JobCategory::where(['delete_status' => 0])->orderBy('id', 'asc')->get();
            $search = base64_decode($request->input('search'));
            $category = base64_decode($request->input('cat_id'));
            if ($request->input('cat_id')  != '' && $category == "all") {
                $jobs->where('title', 'like', '%' . $search . '%')->where(['delete_status' => 0]);
            } elseif ($request->input('cat_id')  != '') {
               $jobs->where('cat_id', $category)->Where("delete_status", '0');
            }
            if ($request->input('search')  != '') {
                $jobs->where('title', 'like', '%' . $search . '%')->Where("delete_status", '0');
            }
            $jobs = $jobs->orderBy('id', 'desc')->paginate(10);
            return view('admin.career.jobManage', compact('cats', 'jobs'));
        }
    }

    public function addNewJob(Request $request){
        if ($request->isMethod('post')) {
           $data = $request->all();
           $user =  Jobs::create([
                       'cat_id' => $data['cat_id'],
                       'title' => $data['title'],
                       'experience' => $data['experience'],
                       'description' => $data['description'],
                    ]);
           Session::flash('message', "Job Added Successfully");
         return 1;
      }
      $cats = JobCategory::where(['delete_status'=>0])->orderBy('id', 'desc')->get();
      return view('admin.career.jobAdd',compact('cats'));
    }

    public function editJob(Request $request,$id){

      if($request->isMethod('post')) {
           $data = $request->all();
           $user =  Jobs::where('id',base64_decode($id))->update([
                       'cat_id' => $data['cat_id'],
                       'title' => $data['title'],
                       'status' => $data['status'],
                       'experience' => $data['experience'],
                       'description' => $data['description'],
                    ]);
        Session::flash('message', "Job Updated Successfully");
        return 1;
      }
      $jobs = Jobs::Where('id', '=', base64_decode($id))->first();
      $cats = JobCategory::where(['delete_status'=>0])->orderBy('id', 'desc')->get();
      return view('admin.career.editJob',compact('cats','jobs'));
    }

    public function deleteJob($id){
      Jobs::where('id',base64_decode($id))->update(['delete_status' => 1]);
      Session::flash('message', "Jobs Deleted Successfully");
      return redirect()->route('jobManage');
    }

    public function JobApplications(Request $request) {
        $search = '';
        if($request->isMethod('post')) {
            $params = array();
            if (!empty($request->input('search'))) {
                $params['search'] = base64_encode($request->input('search'));
            }
            if (!empty($request->input('category'))) {
                $params['category'] = base64_encode($request->input('category'));
            }
            return redirect()->route('JobApplications',$params)->withInput();
          }
          else{
               $jobs = JobApplications::orderBy('id', 'desc')->paginate(10);
              if($request->input('search')  != '') {
                   $search = base64_decode($request->input('search'));
                   $jobs = JobApplications::where(DB::raw('concat(first_name," ",last_name," ",email)'),'like', '%'.$search.'%')
                   ->orderBy('id', 'desc')->paginate(10);
              }
          }
       return view('admin.career.jobApplicationManage',compact('jobs'));
     }

     public function deleteJobApplication($id){
       $jobApp_data = JobApplications::where('id',base64_decode($id))->first();
       $urls = json_decode($jobApp_data->urls);

       $filename = $urls->public_path.'/employee/resume/'.$jobApp_data->file_data;
       if(file_exists($filename)){
         File::delete($filename);
       }
       JobApplications::where('id',base64_decode($id))->delete();
       Session::flash('message', "Application Deleted Successfully");
       return redirect()->route('JobApplications');
     }

     public function viewJobApplication(Request $request,$id){
       JobApplications::where('id',base64_decode($id))->update(['is_view' => 1]);
       $jobs = JobApplications::where('id',base64_decode($id))->orderBy("created_at","DESC")->first();
       return view('admin.career.viewJobApplication',compact('jobs'));
     }

}
