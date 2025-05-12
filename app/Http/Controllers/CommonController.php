<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Patients;
use App\Models\Appointments;
use App\Models\Plans;
use App\Models\PlanPeriods;
use App\Models\UserSubscribedPlans;
use App\Models\UsersSubscriptions;
use App\Models\UserSubscriptionsTxn;
use App\Models\Doctors;
use App\Models\ReferralMaster;
use App\Models\Plans as userPlan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\EmailTemplate;
use App\Models\Templates;
use App\Models\Pages;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Softon\Indipay\Facades\Indipay;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use PaytmWallet;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Response;

class CommonController extends Controller
{
    /*
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

   public function applyWalletAmt(Request $request) {

    $data=$request->all();
	$user_id = Auth::id();

    $user_array=array();
    $user_array['type']=$data['type'];
    $user_array['wallet_amount']=@$data['wallet_amount'];
 
         $availAmount = 0; $isApplicable = 0;
        if($user_array['type'] == 1) {
            $avail_limit = getSetting("reward_appt_avail_limit")[0];
            if($user_array['wallet_amount'] > $avail_limit) {
                $availAmount = (float) $avail_limit;
            }
            else{
                $availAmount = (float) $user_array['wallet_amount'];
            }
            
          
        }
        else if($user_array['type'] == 2) {
            $avail_limit = getSetting("reward_subs_avail_limit")[0];
            if($user_array['wallet_amount'] > $avail_limit) {
                $availAmount =(float)  $avail_limit;
            }
            else{
                $availAmount = (float)$user_array['wallet_amount'];
            }
            
            $isApplicable = 1;
        }
        else if($user_array['type'] == 3) {
            $avail_limit = getSetting("reward_labs_avail_limit")[0];
            if($user_array['wallet_amount'] > $avail_limit) {
                $availAmount = (float)$avail_limit;
            }
            else{
                $availAmount = (float)$user_array['wallet_amount'];
            }
           
            $isApplicable = 1;
        }
        $res = ['availAmount'=>$availAmount,'isApplicable'=>$isApplicable];
		return Response::json(['success' => $res ], 200);
    
}

}
