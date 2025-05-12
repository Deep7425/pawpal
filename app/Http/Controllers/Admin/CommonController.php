<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\HealthQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\MedicineProductDetails as MedicineDetails;
use App\Models\MedicineCategory;
use App\Models\Admin\MentalHealthAases;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaytmChecksum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


use Illuminate\Pagination\LengthAwarePaginator as Paginator;
//use Illuminate\Mail\Mailer;
class CommonController extends Controller {

	public function hQMaster(Request $request) {
		$search = '';
		if ($request->isMethod('post')) {
		$params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
		 if (!empty($request->input('type'))) {
             $params['type'] = base64_encode($request->input('type'));
         }
		 if (!empty($request->input('lang'))) {
             $params['lang'] = base64_encode($request->input('lang'));
         }
		 if (!empty($request->input('page_no'))) {
             $params['page_no'] = base64_encode($request->input('page_no'));
         }
         return redirect()->route('admin.hQMaster',$params)->withInput();
		}
		else {
         $filters = array();
			   $lang = base64_decode($request->input('lang'));
			   $type = base64_decode($request->input('type'));
			   $search = base64_decode($request->input('search'));
			   $query = HealthQuestion::where('delete_status',1);
			   if(!empty($search)){
					$query->where("title",'like','%'.$search.'%');
			   }
			   if(!empty($type)){
					$query->where("type",$type);
			   }
			   if(!empty($lang)){
					$query->where("lang",$lang);
			   }
			    $page = 25;
				if(!empty($request->input('page_no'))){
					$page = base64_decode($request->input('page_no'));
				}
			   $object = $query->orderBy('type', 'asc')->paginate($page);
			   return view('admin.common.question-master',compact('object'));
		}
	}


    public function addQuestion(Request $request){
		if($request->isMethod('post')) {
            $data = $request->all();
			// if(count($data['hq'])>0){
				// foreach($data['hq'] as $value){
					// $value['answer']
				// }
				// $answer = explode(",",$data['answer']);
			// }
			$lastOrder = HealthQuestion::select("order_id")->where(['lang'=>$data['lang'],'type'=>$data['type'],'delete_status'=>1])->orderBy('order_id', 'DESC')->whereNotNull("order_id")->first();
			$order_id = 1;
			if(!empty($lastOrder)){
				$order_id = $lastOrder->order_id + 1;
			}
			$HealthQuestion = HealthQuestion::create([
				'title' => $data['title'],
                'type' => $data['type'],
                'level_type' => $data['level_type'],
                'order_id' => $order_id,
				'answer_type' => $data['answer_type'],
				'lang' => $data['lang'],
				'meta_data' => (isset($data['HQ']) ? json_encode($data['HQ']) : null),
                // 'status' => $data['status'],
			]);
			// HealthQuestion::Where('id', $HealthQuestion->id)->update(['order_id'=>$HealthQuestion->id]);
			Session::flash('message', "Question Added Successfully");
			return 1;
		}
	}
	public function editQuestion(Request $request) {
		$id = $request->id;
		$object = HealthQuestion::Where('id','=',$id)->first();
		return view('admin.common.edit-question',compact('object'));
  }
	
	public function updateQuestion(Request $request){
        if($request->isMethod('post')) {
			$data = $request->all();
			HealthQuestion::where('id', $data['id'])->update(array(
				'title' => $data['title'],
				'level_type' => $data['level_type'],
				'type' => $data['type'],
				'order_id' => $data['order_id'],
				// 'answer' => $data['answer'],
				'answer_type' => $data['answer_type'],
				'lang' => $data['lang'],
				'meta_data' => (isset($data['HQ']) ? json_encode($data['HQ']) : null),
			));
			Session::flash('message', "Question Updated Successfully");
			return 1;
		}
		return 2;
	}
	public function deleteQuestion(Request $request) {
		$id = $request->id;
		HealthQuestion::where('id',$id)->update(['delete_status'=>0]);
		Session::flash('message', "Question Deleted Successfully");
		return 1;
    }
    public function getQuestions(Request $request){
        if($request->isMethod('post')) {
			$data = $request->all();
			$questions = getQuesByType($data['type'],$data['lang'],@$data['id']);
			$array = array();
			foreach ($questions as $key => $value) {
				$arr['id'] = $value->id;
				$arr['title'] = $value->title;
				$array[] = $arr;
			}
			return $array;
		}
		return 2;
	}
	public function medicineMaster(Request $request) {
		$search = '';
		if ($request->isMethod('post')) {
		$params = array();
		 if (!empty($request->input('search'))) {
			$params['search'] = base64_encode($request->input('search'));
		 }
		 if (!empty($request->input('type'))) {
			$params['type'] = base64_encode($request->input('type'));
		 }
		 if (!empty($request->input('manufacturer'))) {
			$params['manufacturer'] = base64_encode($request->input('manufacturer'));
		 }
		 if (!empty($request->input('pack_in'))) {
			$params['pack_in'] = base64_encode($request->input('pack_in'));
		 }
		 if (!empty($request->input('rx_req'))) {
			$params['rx_req'] = base64_encode($request->input('rx_req'));
		 }
		 if (!empty($request->input('medicine_type'))) {
			$params['medicine_type'] = base64_encode($request->input('medicine_type'));
		 }
		 if (!empty($request->input('page_no'))) {
			$params['page_no'] = base64_encode($request->input('page_no'));
		 }
		 return redirect()->route('admin.medicineMaster',$params)->withInput();
		}
		else {
			 $filters = array();
			 $lang = base64_decode($request->input('lang'));
			 $type = base64_decode($request->input('type'));
			 $search = base64_decode($request->input('search'));
			 $manufacturer = base64_decode($request->input('manufacturer'));
			 $pack_in = base64_decode($request->input('pack_in'));
			 $rx_req = base64_decode($request->input('rx_req'));
			 $medicine_type = base64_decode($request->input('medicine_type'));

			 $manufacturers = MedicineDetails::select('manufacturer')->groupBy('manufacturer')->limit(20)->get();
			 $pack_ins = MedicineDetails::select('pack_in')->groupBy('pack_in')->get();
			 $medicine_types = MedicineDetails::select('medicine_type')->groupBy('medicine_type')->get();
			 $query = MedicineDetails::where('delete_status',1);
			 if(!empty($search)){
				$query->where(DB::raw('concat(name," ",composition_name)') , 'like', '%'.$search.'%');
			 }
			 if(!empty($request->input('manufacturer'))){
				$query->where(DB::raw('manufacturer') , 'like', '%'.$manufacturer);
			 }
			 if(!empty($request->input('pack_in'))){
				$query->where(DB::raw('pack_in') , 'like', '%'.$pack_in);
			 }
			 if(!empty($request->input('rx_req'))){
				 $rx_req = ($rx_req == 1 ? 1 : 0);
				$query->where(DB::raw('rx_req') , 'like', '%'.$rx_req);
			 }
			 if(!empty($request->input('medicine_type'))){
				$query->where(DB::raw('medicine_type') , 'like', '%'.$medicine_type);
			 }
			 $page = 25;
			 if(!empty($request->input('page_no'))){
				$page = base64_decode($request->input('page_no'));
			 }
			 $object = $query->orderBy('id', 'desc')->paginate($page);
			 return view('admin.medicine_master.medicine-master',compact('object','manufacturers','pack_ins','medicine_types'));
		}
	}

	public function addMedicine(Request $request) {
		$page = 25;
		$query = MedicineDetails::where('delete_status',1);
		$object = $query->orderBy('id', 'desc')->paginate($page);
		$medicine_categories = MedicineDetails::select('medicine_type')->groupBy('medicine_type')->get();
		$pack_ins = MedicineDetails::select('pack_in')->groupBy('pack_in')->get();
		$manufacturers = MedicineDetails::select('manufacturer')->groupBy('manufacturer')->limit(20)->get();
		return view('admin.medicine_master.add-medicine',compact('object','medicine_categories','manufacturers','pack_ins'));
	}
	public function editMedicine(Request $request, $id) {
		$id = base64_decode($id);
		$medicine_categories = MedicineDetails::select('medicine_type')->groupBy('medicine_type')->get();
		$pack_ins = MedicineDetails::select('pack_in')->groupBy('pack_in')->get();
		$manufacturers = MedicineDetails::select('manufacturer')->groupBy('manufacturer')->limit(20)->get();
		$row = MedicineDetails::find($id);
		return view('admin.medicine_master.edit-medicine',compact('row','medicine_categories','pack_ins','manufacturers'));
   }
   public function createMedicine(Request $request) {
		if ($request->isMethod('post')) {
			$data = $request->all();
			if (isset($data['row_id'])) {
				$id = base64_decode($data['row_id']);
				$row = MedicineDetails::find($id);
			}else {
				$row = new MedicineDetails();
			}
			$row->fill($data);
			$row->save();
			return 1;

		}
	}
	public function modifyMedicine(Request $request) {
		if ($request->isMethod('post')) {
			$data = $request->all();
			if ($data['action'] == 'delete') {
				MedicineDetails::where('id',$data['id'])->update(['delete_status' => 0]);
			}
			elseif ($data['action'] == 'manufacturerSearch') {
				$array = array();
				$query = MedicineDetails::select('manufacturer');
				$limit = 20;
				if (!empty($data['searchText'])) {
					$query->where('manufacturer' , 'like', '%'.$data['searchText'].'%');
					$limit = 30;
				}
				$manufacturers = $query->groupBy('manufacturer')->limit($limit)->get();
				if (count($manufacturers) > 0) {
					foreach($manufacturers as $row){
							$array[] = array("name"=>$row->manufacturer);
					}
				}
				return $array;
			}
			return 1;
		}
	}
	public function searchMedicine(Request $request) {
		if ($request->isMethod('post')) {
			$data = $request->all();
			$items = MedicineDetails::select('id','name','price')->where('name' , 'like', '%' .$request->searchText. '%')->limit(50)->get();

			$array = array();
			if (isset($items)) {
				foreach($items as $row){
					$array[] = array("id"=>$row->id,"name"=>$row->name,"price"=>$row->price,'img'=>'');
				}
			}
			return $array;
		}
	}
	public function paytmOrders(Request $request) {
		$search = '';
		if($request->isMethod('post')) {
		 $params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
		 if (!empty($request->input('type'))) {
             $params['type'] = base64_encode($request->input('type'));
         }
		 if (!empty($request->input('start_date'))) {
             $params['start_date'] = base64_encode($request->input('start_date'));
         }
		 if (!empty($request->input('end_date'))) {
             $params['end_date'] = base64_encode($request->input('end_date'));
         }
		 if (!empty($request->input('payment_method'))) {
			$params['payment_method'] = base64_encode($request->input('payment_method'));
		}
		 
				 if (!empty($request->input('page_no'))) {
		             $params['page_no'] = $request->input('page_no');
		         }
         return redirect()->route('admin.paytmOrders',$params)->withInput();
		}
		else {
			if(!empty($request->input('type'))) {
				$type = base64_decode($request->input('type'));
			}
			else{
				$type = "SUCCESS|FAILURE|PENDING";
			}
			
			if(!empty($request->input('payment_method'))){
				$payment_method = base64_decode($request->input('payment_method'));
			}else{
				$payment_method = "paytm";
			}

			if(!empty($request->input('start_date'))) {
				if(!empty($request->input('start_date'))) {
					$start_date = base64_decode($request->input('start_date'));
					$sdt = new \DateTime($start_date);
					$start_date = $sdt->format('c');
					$end_date = date('Y-m-d',strtotime(base64_decode($request->input('start_date')).' +1 day'));
					$edt = new \DateTime($end_date);
					$end_date = $edt->format('c');
				}
			}
			
			// if(!empty($request->input('end_date'))) {
				// if(!empty($request->input('end_date')))	{
					// $end_date = base64_decode($request->input('end_date'));
					// $edt = new \DateTime($end_date);
					// $end_date = $edt->format('c');
				// }
			// }
			$perPage = 10;
			if(!empty($request->input('page_no'))) {
				$perPage = base64_decode($request->input('page_no'));
			}

			// $input = Input::all();
			// if (isset($input['page']) && !empty($input['page'])){ $currentPage = $input['page']; } else { $currentPage = 1; }
			// pr($start_date);

			$paytmParams = array();
			$mid = "yNnDQV03999999736874";
			$paytmParams["body"] = array(
				"mid"           => $mid,
				"fromDate"   => $start_date,
				"toDate"       => $end_date,
				"orderSearchType"   => "TRANSACTION",
				"orderSearchStatus"   => $type,
				"pageNumber"   => 1,
				"pageSize"   => 50,
				// "searchConditions"     => array(
					// "searchKey"     => "VAN_ID",
					// "searchValue"  => "PYI3831611899004",
				// ),
			);
			$pageNos = [];
			$orders = [];
			$ordersPaytm = [];
			$ordersRajor = [];
			$totalOrders = 0;
			for ($x = 1; $x <= 50; $x++) {
				$paytmParams['body']['pageNumber'] = $x;

				$razorPay =  $this->razorpayOrderListAPI($paytmParams);
				
				if ($razorPay instanceof \Illuminate\Http\JsonResponse) {
					$rawContent = $razorPay->getContent();
					$resRazor = json_decode($rawContent, true);
				
				} else {
					\Log::error('Unexpected RazorPay Response 0:', ['response' => $razorPay]);
				}
				
		
				$response = $this->paytmOrderListAPI($paytmParams);
				
				
				if(!empty($response)) {
					$res = json_decode($response,true);

					if(isset($res['body']['orders']) && !empty($res['body']['orders']) || isset($resRazor['body']['orders']) && !empty($resRazor['body']['orders']) ) {
						$ordersPaytm[$x] = @$res['body']['orders'];
					
						$pageNos[] = $x;
						$totalOrders += count(@$res['body']['orders']);
					}else{
						break;
					}
				}

				if (isset($resRazor['data']['items'])) {
					$ordersRajor[$x] = $resRazor['data']['items'];
				
					\Log::info('Orders:', $orders);
				} else {
					\Log::warning('No items found in RazorPay response:', $resRazor);
				}
			}

			if ($payment_method == 'razorpay') {
				$ordersPaytm = null;  // Set Paytm orders to null
			} elseif ($payment_method == 'paytm') {
				$ordersRajor = null;  // Set RazorPay orders to null
			}
               
			
			return view('admin.paytm-order.index',compact('ordersPaytm' ,'ordersRajor' ,'pageNos','totalOrders' ));
		}
	}
	
	public function paytmOrderListAPI($paytmParams)
	{
		$merchent_key = "&!VbTpsYcd6nvvQS";
		$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchent_key);
		$paytmParams["head"] = array(
			"signature" => $checksum,
			"tokenType" => "CHECKSUM",
			"requestTimestamp" =>""
		);
		$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
		$url = "https://securegw.paytm.in/merchant-passbook/search/list/order/v2";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		$response = curl_exec($ch);
		return $response;
	}

	public function razorpayOrderListAPI(array $params)
{
    // Razorpay API credentials
    $keyId = 'rzp_test_uCWW4fIrP9ys6C';
    $keySecret = '9hcgBMZWz7gX64mxqR2yLbPC';
    $authHeader = base64_encode("$keyId:$keySecret");

    try {
        // Fetch orders from Razorpay
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $authHeader,
        ])->get("https://api.razorpay.com/v1/payments");

        // Log response for debugging
        Log::info('Razorpay Orders:', [$response->json()]);

        // Check if the response is successful
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response->json(),
            ], $response->status());
        }
    } catch (\Exception $e) {
        // Handle exceptions
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}


	public function assesmentMaster(){
		$assesmentresult=MentalHealthAases::paginate(20);
	
		return view('admin.common.assesment-master',compact('assesmentresult'));
	}

	public function wmhMaster(Request $request)
	{
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');
	
		$query = DB::table('wmh_clicks')
			->select('type', DB::raw('COUNT(*) as count'))
			->groupBy('type');
	
		if ($startDate && $endDate) {
			$query->whereBetween('created_at', [$startDate, $endDate]);		  
		}
	
		$results = $query->get()->toArray();
	
		$viewer = array_column($results, 'count');
	
		return view('admin.wmh.wmhMaster')->with('viewer', json_encode($viewer, JSON_NUMERIC_CHECK));
	}

}
