<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
// use App\Models\Admin\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use App\Models\ehr\PatientRagistrationNumbers;
use App\Models\ehr\Patients;
use App\Models\ehr\Appointments;
use App\Models\ehr\RoleUser;
use App\Models\ehr\DoctorsInfo;
use App\Models\BulkExportCSV;
use App\Models\ehr\User as ehrUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Exports\AppointmentExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ehr\AppointmentOrder;
use App\Models\Doctors;
use App\Models\ehr\EmailTemplate;
use App\Models\PlanPeriods;
use Illuminate\Support\Facades\Bus;
use ZipArchive;

use App\Jobs\GenerateAppointmentExportJob;	
use App\Models\UserPrescription;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller {



	public function hgAppointments(Request $request) {
		Log::info('Your function has been called', ['request' => $request->all()]);
		
		if ($request->isMethod('post')) {
			// Handle POST request - encode parameters and redirect
			$params = $this->encodeSearchParams($request);
			return redirect()->route('admin.hgAppointments', $params)->withInput();
		}

		// Build base query with optimized eager loading
		$query = Appointments::with([
			'AppointmentTxn',
			'AppointmentOrder.PlanPeriods',
			'User.DoctorInfo.docSpeciality',
			'Patient',
			'NotifyUserSms',
			'Doctors.DoctorData',
			'UserPP.OrganizationMaster',
			'PatientLabs.labs',
			'PatientLabs.LabPack',
			'chiefComplaints',
			'PatientLabsOne',
			'PatientDiagnosticImagings',
			'UserPP.UsersSubscriptions.ReferralMaster'
		])
		->whereIn('app_click_status', [5,6])
		->where("appointments.added_by", "!=", 24)
		->where("appointments.delete_status", 1);

		// Apply filters directly in the query
		$this->applyQueryFilters($query, $request);
		$pay_sts = base64_decode($request->input('pay_sts'));
			$app_from = base64_decode($request->input('app_from'));

		// Get paginated results
		$perPage = !empty($request->input('page_no')) ? base64_decode($request->input('page_no')) : 25;
		
		// Check if export is requested
		$file_type = base64_decode($request->input('file_type'));
		if ($file_type === "excel") {
            return $this->exportToExcel($query);
    
    } elseif ($file_type == "pdf") {
			return $this->exportToPdf($query);
		}

		// Get paginated results for normal view
		$appointments = $query->orderBy('id', 'desc')
							 ->paginate($perPage);

		// Get practices for the view
		$practices = Doctors::select(['first_name', 'last_name', 'email', 'consultation_fees', 'oncall_fee', 'user_id'])
			->with("docSpeciality")
			->where([
				"delete_status" => 1,
				"hg_doctor" => 1,
				"claim_status" => 1,
				"varify_status" => 1
			])
			->orderBy("id", "ASC")
			->get();

		return view('admin.appointments.appointment-master', compact('appointments', 'practices'));
	}

	// Helper method to encode search parameters
	
    private function encodeSearchParams(Request $request)
    {
        $params = [];
        $fields = [
            'search', 'start_date', 'end_date', 'user_id', 'app_type', 
            'type', 'pay_sts', 'app_from', 'page_no', 'file_type',
            'pres_type', 'today_appt', 'date_type', 'id', 'code',
            'appintmentstatus', 'lab_status', 'dia_status', 'by_speciality'
        ];

        foreach ($fields as $field) {
            if ($request->filled($field)) {
                $params[$field] = base64_encode($request->input($field));
            }
        }
        return $params;
    }

    protected function getFilteredAppointmentsCount(Request $request): int
    {
        $query = Appointments::with([
            'AppointmentTxn',
            'AppointmentOrder.PlanPeriods',
            'User.DoctorInfo.docSpeciality',
            'Patient',
            'NotifyUserSms',
            'Doctors.DoctorData',
            'UserPP.OrganizationMaster',
            'PatientLabs.labs',
            'PatientLabs.LabPack',
            'chiefComplaints',
            'PatientLabsOne',
            'PatientDiagnosticImagings',
            'UserPP.UsersSubscriptions.ReferralMaster'
        ])
        ->whereIn('app_click_status', [5,6])
        ->where("appointments.added_by", "!=", 24)
        ->where("appointments.delete_status", 1);

        $this->applyQueryaFilters($query, $request);

        return $query->count();
    }

    protected function applyQueryaFilters($query, Request $request): void
    {
        if ($request->has('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('start', [$startDate, $endDate]);
            }
        }

        if ($request->has('doctor_id') && $request->doctor_id) {
            $query->where('appointments.user_id', $request->doctor_id);
        }

        if ($request->has('organization_id') && $request->organization_id) {
            $query->whereHas('UserPP.OrganizationMaster', function($q) use ($request) {
                $q->where('id', $request->organization_id);
            });
        }

        if ($request->has('status') && $request->status) {
            if ($request->status === 'cancelled') {
                $query->where('appointments.status', '!=', 1);
            } elseif ($request->status === 'confirmed') {
                $query->where('appointments.appointment_confirmation', 1);
            } else {
                $query->where('appointments.appointment_confirmation', 0)
                      ->where('appointments.status', 1);
            }
        }

        if ($request->has('payment_status') && $request->payment_status) {
            if ($request->payment_status === 'paid') {
                $query->whereHas('AppointmentTxn');
            } else {
                $query->whereDoesntHave('AppointmentTxn');
            }
        }
    }

    protected function prepareExportParams(Request $request): array
    {
        return [
            'date_range' => $request->date_range,
            'doctor_id' => $request->doctor_id,
            'organization_id' => $request->organization_id,
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'app_type' => $request->app_type,
            'type' => $request->type,
            'pres_type' => $request->pres_type,
            'today_appt' => $request->today_appt,
            'date_type' => $request->date_type,
            'id' => $request->id,
            'code' => $request->code,
            'appintmentstatus' => $request->appintmentstatus,
            'lab_status' => $request->lab_status,
            'dia_status' => $request->dia_status,
            'by_speciality' => $request->by_speciality,
        ];
    }


protected function filterAppointments($appointments, $request, $app_from)
{
    if ($appointments->isEmpty()) return $appointments;

    // app_from filtering
    if (!empty($app_from)) {
        $appointments = $appointments->filter(function ($raw) use ($app_from) {
            $meta_data = json_decode($raw->AppointmentOrder->meta_data ?? '');
            if ($app_from == '1' && $raw->app_click_status == '6') {
                return empty($raw->AppointmentOrder) || (isset($meta_data->isPaytmTab) && $meta_data->isPaytmTab == "false");
            } elseif ($app_from == '2') {
                return $raw->app_click_status == '5';
            } elseif ($app_from == '3') {
                return $raw->app_click_status == '6' && isset($meta_data->isPaytmTab) && $meta_data->isPaytmTab == "true";
            }
            return false;
        });
    }

    // Filter by type
    if ($request->filled('type') && base64_decode($request->input('type')) == '3') {
        $appointments = $appointments->filter(function ($raw) {
            return checkAppointmentIsElite($raw->id, @$raw->AppointmentOrder->order_by) == 1;
        });
    }

    // Filter by code
    if ($request->filled('code')) {
        $code = base64_decode($request->input('code'));
        $appointments = $appointments->filter(function ($raw) use ($code) {
            if ($code == 9) return @$raw->AppointmentOrder->hg_miniApp == 1;
            if ($code == 10) return @$raw->AppointmentOrder->hg_miniApp == 2;
            return @$raw->UserPP->organization == $code;
        });
    }

    // Lab status filter
    if ($request->filled('lab_status')) {
        $lab_status = base64_decode($request->input('lab_status'));
        $appointments = $appointments->filter(function ($raw) use ($lab_status) {
            return ($lab_status == 1 && !empty($raw->PatientLabsOne)) || ($lab_status == 2 && empty($raw->PatientLabsOne));
        });
    }

    // Diagnostic status filter
    if ($request->filled('dia_status')) {
        $dia_status = base64_decode($request->input('dia_status'));
        $appointments = $appointments->filter(function ($raw) use ($dia_status) {
            return ($dia_status == 1 && !empty($raw->PatientDiagnosticImagings)) || ($dia_status == 2 && empty($raw->PatientDiagnosticImagings));
        });
    }

    // Speciality filter
    if ($request->filled('by_speciality')) {
        $by_speciality = base64_decode($request->input('by_speciality'));
        $appointments = $appointments->filter(function ($raw) use ($by_speciality) {
            return isset($raw->User->DoctorInfo->docSpeciality) && $raw->User->DoctorInfo->docSpeciality->id == $by_speciality;
        });
    }

    return $appointments->values(); // Reset keys
}

	// Helper method to apply filters to query
	private function applyQueryFilters($query, Request $request) {
		// Search filter
		if ($search = base64_decode($request->input('search'))) {
			$query->whereHas('Patient', function($q) use($search) {
				$q->where(DB::raw('concat(IFNULL(first_name,"")," ",IFNULL(last_name,"")," ",IFNULL(mobile_no,""))'), 'like', '%'.$search.'%');
			});
		}

		// Status filter
		if ($status = base64_decode($request->input('appintmentstatus'))) {
			if ($status == '1') {
				$query->whereNull('working_status');
			} else {
				$query->where('working_status->status', $status);
			}
		}

		// Patient ID filter
		if ($pId = base64_decode($request->input('id'))) {
			$p_ids = User::select('pId')
				->from('users as u')
				->where('u.parent_id', $pId)
				->pluck('pId')
				->toArray();
			array_push($p_ids, $pId);
			$query->whereIn('pId', $p_ids);
		}

		// App type filter
		if ($app_type = base64_decode($request->input('app_type'))) {
			if ($app_type == 2) {
				$query->where(['appointment_confirmation' => 0, 'status' => 1]);
			} else if ($app_type == 3) {
				$query->where(['appointment_confirmation' => 1, 'status' => 1]);
			} else if ($app_type == 4) {
				$query->where('status', 0);
			}
		}

		// Prescription type filter
		if ($pres_type = base64_decode($request->input('pres_type'))) {
			$query->where('visit_status', $pres_type);
		}

		// Type filter
		if ($type = base64_decode($request->input('type'))) {
			if ($type == '2') {
				$query->where('type', '3');
			} else if ($type == '1') {
				$query->whereNull('type');
			} else if ($type == '4') {
				$query->where('visit_type', 6);
			}
		}

		// Doctor ID filter
		if ($user_id = base64_decode($request->input('user_id'))) {
			$query->where('doc_id', $user_id);
		}

		// Payment status filter
		if ($pay_sts = base64_decode($request->input('pay_sts'))) {
			$query->where(function($q) use ($pay_sts, $query) {
				if ($pay_sts == '1') {
					$q->whereHas('AppointmentTxn')
					  ->whereHas('AppointmentOrder', function($q2) {
						  $q2->where('type', '1');
					  })
					  ->orWhereDoesntHave('AppointmentOrder');
				} else if ($pay_sts == '2') {
					$q->whereHas('AppointmentOrder', function($q2) {
						$q2->where('type', '0');
					})->whereDoesntHave('AppointmentOrder.PlanPeriods', function($q3) {
						$q3->whereRaw("FIND_IN_SET(appointments.id, appointment_ids)");
					});
				} else if ($pay_sts == '4') {
					$q->whereHas('AppointmentOrder', function($q2) {
						$q2->where('type', '0');
					})->whereHas('AppointmentOrder.PlanPeriods', function($q3) {
						$q3->whereRaw("FIND_IN_SET(appointments.id, appointment_ids)");
					});
				} else if ($pay_sts == '3') {
					$q->whereHas('AppointmentOrder', function($q2) {
						$q2->where('type', '2');
					});
				}
			});
		}

		// App from filter
		if ($app_from = base64_decode($request->input('app_from'))) {
			$query->where(function($q) use ($app_from, $query) {
				if ($app_from == '1') {
					$q->where('app_click_status', '6')
					  ->where(function($q2) {
						  $q2->whereHas('AppointmentOrder', function($q3) {
							  $q3->whereJsonDoesntContain('meta_data->isPaytmTab', 'true');
						  })
						  ->orWhereDoesntHave('AppointmentOrder')
						  ->orWhereHas('AppointmentOrder', function($q3) {
							  $q3->whereNull('meta_data');
						  });
					  });
				} else if ($app_from == '2') {
					$q->where('app_click_status', '5');
				} else if ($app_from == '3') {
					$q->where('app_click_status', '6')
					  ->whereHas('AppointmentOrder', function($q2) {
						  $q2->whereJsonContains('meta_data->isPaytmTab', 'true');
					  });
				}
			});
		}

		// Code filter
		if ($code = base64_decode($request->input('code'))) {
			if ($code == 9) {
				$query->whereHas('AppointmentOrder', function($q2) {
					$q2->where('hg_miniApp', 1);
				});
			} else if ($code == 10) {
				$query->whereHas('AppointmentOrder', function($q2) {
					$q2->where('hg_miniApp', 2);
				});
			} else {
				$userIds = User::where('organization', $code)->pluck('id')->toArray();
				$query->whereIn('pId', $userIds);
			}
		}

		// Lab status filter
		if ($lab_status = base64_decode($request->input('lab_status'))) {
			if ($lab_status == 1) {
				$query->has('PatientLabsOne');
			} else if ($lab_status == 2) {
				$query->doesntHave('PatientLabsOne');
			}
		}

		// Diagnostic status filter
		if ($dia_status = base64_decode($request->input('dia_status'))) {
			if ($dia_status == 1) {
				$query->has('PatientDiagnosticImagings');
			} else if ($dia_status == 2) {
				$query->doesntHave('PatientDiagnosticImagings');
			}
		}

		// Speciality filter
		if ($by_speciality = base64_decode($request->input('by_speciality'))) {
			$query->whereHas('User.DoctorInfo.docSpeciality', function($q) use ($by_speciality, $query) {
				$q->where('id', $by_speciality);
			});
		}

		// Apply date filters
		$this->applyDateFilters($query, $request);
	}

	// Helper method to apply date filters
	private function applyDateFilters($query, Request $request) {
		$today_appt = base64_decode($request->input('today_appt'));
		$start_date = $request->input('start_date') ? date('Y-m-d', strtotime(base64_decode($request->input('start_date')))) : null;
		$end_date = $request->input('end_date') ? date('Y-m-d', strtotime(base64_decode($request->input('end_date')))) : null;
		$date_type = base64_decode($request->input('date_type'));

		if ($today_appt == '1') {
			if ($start_date) {
				$query->whereRaw('date(start) >= ?', [$start_date])
					  ->whereRaw('date(created_at) != ?', [$start_date]);
			}
			if ($end_date) {
				$query->whereRaw('date(start) <= ?', [$end_date])
					  ->whereRaw('date(created_at) != ?', [$end_date]);
			}
		} else {
			if ($start_date || $end_date) {
				$query->where(function($q) use ($start_date, $end_date, $date_type, $query) {
					$dateColumn = $date_type == 2 ? 'start' : 'created_at';
					if ($start_date) {
						$q->whereRaw("date($dateColumn) >= ?", [$start_date]);
					}
					if ($end_date) {
						$q->whereRaw("date($dateColumn) <= ?", [$end_date]);
					}
				});
			}
		}
	}

	// Helper method to handle Excel export
	private function exportToExcel($query) {
		Artisan::call('queue:work', [
			'--queue' => 'bulkExportCSV,default',
			'--stop-when-empty' => true
		]);

		$appointmentData = $query->orderBy('id', 'desc');

		$resource_namespace = 'App\Http\Resources\AppointmentResource';
		$columns = [
			'Sr. No.' , 'AppointmentID' , 'Appointment Date', 'Time', 'Checkout Date', 'Order ID',
			'Disease', 'Lab Test', 'Diagnostic Imaging', 
			'Doctor Name (Id) (Mobile) Speciality Name',
			'Patient Name (Pid) (Mobile)', 'Gender/Age', 'Type',
			'Doc Fee To Pay', 'Consultation Fee (Rs.)', 'Total Pay (Rs.)',
			'Payment Status', 'From', 'Rating', 'Organization',
			'Created At', 'Created At Time', 'Status', 'Payment Status',
			'Appointment Status', 'Waiting Time Hour:Minute', 'Ref Code'
		];

		$data = "appointment";

		$bulkExportCSV = \BulkExportCSV::build($appointmentData, $resource_namespace, $columns , $data);

		$filename = \BulkExportCSV::download($appointmentData, $resource_namespace, $columns , $data);

		return response()->json([
			'status' => 'success',
			'message' => 'Excel Generated Successfully!',
			'file' => $filename,
			'url' => asset('public/storage/exportCSV/' . $filename) // optional: full download URL
		]);
	}

	private function exportToPdf($query) {
		$appointments = $query->orderBy('id', 'desc')->get();
		$pdf = Pdf::loadView('admin.appointments.appointmentPDF', compact('appointments'));
		return $pdf->download('appointment-report.pdf');
	}






	public function ChangeWorkingStatus(Request $request) {
		$data = $request->all();
		$appointment_id = base64_decode($data['id']);
		 $user_id = Session::get('userdata')->id;
		if ($data['status'] == null) {
		  $status = array('status' => 1, 'user_id' => $user_id);
		  Appointments::where('id', $appointment_id)->update(array('working_status' => json_encode($status)));
		}
		elseif ($data['status'] == 1) {
		  $status = array('status' => 2, 'user_id' => $user_id);
		  Appointments::where('id', $appointment_id)->update(array('working_status' => json_encode($status)));
		}
		return 1;
	  }

	public function switchAppointment(Request $request) {
		$data = $request->all();
		$appointment_id = base64_decode($data['app_id']);
		$doc_id = $data['doc_id'];
		$pId = $data['pId'];
		if(!empty($appointment_id) && !empty($doc_id)) {
			$exist_doctor = Appointments::select(["consultation_fees","doc_id","added_by","type"])->where('id', $appointment_id)->first();
			$docInfo = Doctors::select(["consultation_fees","oncall_fee","slot_duration"])->where("user_id",$doc_id)->first();
			$plan_data = PlanPeriods::whereRaw("find_in_set('".$appointment_id."',plan_periods.appointment_ids)")->first();
			if(($exist_doctor->doc_id != $doc_id && checkAppointmentIsElite($appointment_id) == 0) || (!empty($plan_data) && $plan_data->specialist_appointment_cnt <= 0)) {
				if($exist_doctor->type == "3") {
					
				}
				else{
					
				}
			}
			$increment_time = $docInfo->slot_duration*60;
			$startVal = date('Y-m-d',strtotime($data['appstart_date'])).' '.date('H:i:s',$data['time']);
			$endVal = date('Y-m-d',strtotime($data['appstart_date'])).' '.date('H:i:s',$data['time']+$increment_time);
			// $endVal = date('Y-m-d H:i:s',strtotime($data['appstart_date']." ".$data['time'])+$increment_time);
			Appointments::where('id',$appointment_id)->update(array('start' => $startVal,'end' =>  $endVal));
			if(isset($data['markAsFollowup'])) {
				if($data['markAsFollowup'] == '1'){
					Appointments::where('id',$appointment_id)->update(array('visit_type' => 6));
				}
				else{
					Appointments::where('id',$appointment_id)->update(array('visit_type' => 1));
				}
			}
			if($exist_doctor->doc_id != $doc_id) {
				if($exist_doctor->type == "3") {
					$appFee = $docInfo->oncall_fee;
				}
				else{
					$appFee = $docInfo->consultation_fees;
				}
				$existPracticeId = $exist_doctor->added_by;
				$practice =  RoleUser::select(['user_id','role_id','practice_id'])->where(['user_id'=>$doc_id])->first();
				Appointments::where('id', $appointment_id)->update(array('doc_id' => $doc_id,'added_by'=>$practice->practice_id,'consultation_fees'=>$appFee));
				AppointmentOrder::where('appointment_id', $appointment_id)->update(array('doc_id' => $doc_id,'order_subtotal'=>$appFee));
				$is_existPat = Patients::where('id', $pId)->whereRaw("find_in_set('".$practice->practice_id."',patients.practices_id)")->count();
				if($is_existPat == 0) {
					$practices_ids = Patients::select('practices_id')->where('id', $pId)->pluck("practices_id")->toArray();
					if(!in_array($practice->practice_id,$practices_ids)){
						array_push($practices_ids,$practice->practice_id);
					}
					$last_reg_no = PatientRagistrationNumbers::where(['added_by'=>$practice->practice_id,'status'=>1])->max('reg_no');
					$reg_no = 1;
					if(!empty($last_reg_no)){
					  $reg_no = $last_reg_no+1;
					}
					if(countExistsAppointment($pId,$practice->practice_id) == 0) {
						if(($key = array_search($existPracticeId, $practices_ids)) !== false) {
							unset($practices_ids[$key]);
						}
						PatientRagistrationNumbers::where(['pid'=>$pId,"added_by"=>$existPracticeId])->update(array(
							'added_by'=>$practice->practice_id,
							'reg_no'=> $reg_no,
						));
					}
					else{
						PatientRagistrationNumbers::create([
							 'pid' => $pId,
							 'reg_no' =>  $reg_no,
							 'status' =>  1,
							 'added_by' => $practice->practice_id,
						]);
					}
					array_unique($practices_ids);
					$practices_ids =  implode(',',$practices_ids);
					Patients::where('id', $pId)->update(array(
						'added_by'=>$practice->practice_id,
						'practices_id'=>$practices_ids
					));
					User::where('pId', $pId)->update(array(
						'added_by'=>$practice->practice_id,
						'practices_id'=>$practices_ids
					));
				}
				if(checkAppointmentIsElite($appointment_id) == 1) {
					if(in_array($doc_id,getSetting("specialist_doctor_user_ids"))) {
						if($plan_data->specialist_appointment_cnt > 0) {
							$remaining_appointment_count = $plan_data->specialist_appointment_cnt;
							PlanPeriods::where('id',$plan_data->id)->update(array('specialist_appointment_cnt' => ($remaining_appointment_count-1)));
						}
					}
				}



				if(!empty($plan_data)) {
					if(in_array($doc_id,getSetting("specialist_doctor_user_ids")) && !isset($data['is_cs'])) {
						if($plan_data->specialist_appointment_cnt > 0) {	
							$remaining_appointment_count = $plan_data->specialist_appointment_cnt;
							PlanPeriods::where('id',$plan_data->id)->update(array('specialist_appointment_cnt' => ($remaining_appointment_count-1)));
						}
					}
				}

				if(isset($data['is_cs'])) {
					$dt = date('Y-m-d');
					$csPlan = PlanPeriods::where('user_id',$data['userPPId'])->where('status',1)->whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where('counseling_session','>',0)->first();
					if(!empty($csPlan) && $csPlan->counseling_session > 0) {
						$csCount = $csPlan->counseling_session;
						PlanPeriods::where('id',$csPlan->id)->update(array('counseling_session' => ($csCount-1)));
					}
				}

				AppointmentOrder::where('appointment_id', $appointment_id)->update(array('switch_apt' => '1'));
				$this->sendPlanAppointmentMail($appointment_id);
			}
			return 1;
		}
		return 2;
	}

	  public function sendPlanAppointmentMail($appointment_id) {
		if($this->is_connected()==1) {
			$appointment =  Appointments::where('id',$appointment_id)->first();
			$consultation_fees = $appointment->consultation_fees;
			$fees_type = "";
			if($appointment->AppointmentOrder->type == '0'){
				$consultation_fees = '<strike>'.getSetting("tele_main_price")[0].'</strike>';
				$fees_type = "FREE";
			}
			$docData = Doctors::where(['user_id'=>$appointment->doc_id])->first();
			$docData["appointment_type"] = $appointment->type;
			$docName = "Dr. ".ucfirst($docData->first_name)." ".$docData->last_name;
			$patientname = $appointment->patient->first_name.' '.$appointment->patient->last_name;
			$appointDate = date('d-m-Y',strtotime($appointment->start));
			$appointtime = date('h:i A',strtotime($appointment->start));

			if(!empty($appointment->Patient->email)) {
				$EmailTemplate = EmailTemplate::where('slug','teleconsultpatientappointment')->first();
				$to = $appointment->Patient->email;
				if($EmailTemplate && !empty($to)) {
					$body = $EmailTemplate->description;
					$tbl = '<table style="width: 100%;" cellpadding="0" cellspacing="0"><tbody><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Appointment Dr.</td><td style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Dr. '.@$docData->first_name." ".@$docData->last_name.'</td></tr><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Date and Time</td><td style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">'.date('d-m-Y, h:i:sa',strtotime($appointment->start)).'</td></tr><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Payment for Consultations</td><td style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">₹ '.$consultation_fees." ".$fees_type.'</td></tr><tr><td colspan="2" style="font-size: 13px; color:#333; padding:10px 0px 10px;">If you wish to reschedule or cancel your appointment, please contact to our help line number.</td></tr></tbody></table>';

					$mailMessage = str_replace(array('{{pat_name}}','{{clinic_name}}','{{clinic_phone}}','{{appointmenttable}}'),
					array($patientname,$docData->clinic_name,$docData->mobile,$tbl),$body);
					$to_docname = '';
					$datas = array('to' =>$to,'from' => 'noreply@healthgennie.com','mailTitle'=>$EmailTemplate->title,'practiceData'=>$docData,'content'=>$mailMessage,'subject'=>$EmailTemplate->subject);
					try{
					Mail::send('emails.mailtempPractice', $datas, function( $message ) use ($datas) {
						$message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
					});
					}
					catch(\Exception $e){
					  // Never reached
					}
				}
			}
			if(!empty($appointment->Patient->mobile_no)) {
				$message = urlencode("Dear ".ucfirst($appointment->Patient->first_name)." ".$appointment->Patient->last_name." , Your Tele consultation with Dr. ".$appointment->User->DoctorInfo->first_name." ".$appointment->User->DoctorInfo->last_name." on ".$appointDate." and ".$appointtime." has been confirmed. Please keep the Health Gennie app open at the time of consultation. Thanks Team Health Gennie");
				$this->sendSMS($appointment->Patient->mobile_no,$message,'1707161587979652683');
			}
		}
	}

	public function RefundOrder(Request $request) {
	  	if ($request->isMethod('post')) {
		$data = $request->all();
		$ch_app = curl_init();
		curl_setopt($ch_app, CURLOPT_URL, "https://apitest.ccavenue.com/apis/servlet/refundOrder");
		curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch_app, CURLOPT_POST, true);
		$order_array = array(
			'reference_no' => 123446,
			'refund_amount' => 50,
			'refund_ref_no' => 1234,
		);
		$order_data = json_encode($order_array);
		curl_setopt($ch_app, CURLOPT_POSTFIELDS, $order_data);
		curl_setopt($ch_app, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, true);
		$app_output = curl_exec($ch_app);
		curl_close($ch_app);
		$output = json_decode($app_output,true);
		dd($output);
	  }
	}

	public function appointmentRating(Request $request) {
		$data = $request->all();
		$appointment_id = base64_decode($data['appId']);
		if(!empty($appointment_id)) {
			AppointmentOrder::where('appointment_id', $appointment_id)->update(array('rating' => $data['rating']));
		}
		return 1;
	}
	public function showAppts(Request $request) {
		$data = $request->all();
		$pid = base64_decode($data['pid']);
		$mobile = base64_decode($data['mobile']);
		$user = User::select(["id","pId"])->where(["mobile_no"=>$mobile])->where("parent_id",0)->first();
		$user_id = $user->id;

		$remaining_appointment = PlanPeriods::select('remaining_appointment')->where("user_id",$user_id)->where('status',1)->sum('remaining_appointment');
		$rem_appt = "";
		if(!empty($remaining_appointment)){
		$rem_appt = $remaining_appointment;
		}
		$p_ids = User::select("pId")->where(["parent_id"=>$user->pId])->pluck("pId")->toArray();
		array_push($p_ids,$user->pId);
		$appts = Appointments::with(['user.doctorInfo','Patient','AppointmentOrder'])->whereIn('pID',$p_ids)->where('delete_status',1)->orderBy('start','desc')->get();
		return ['appts'=>$appts,'tot_rem_appt'=>$rem_appt];
	  }
	  public function showSlotAdmin(Request $request) {
		$doc_id = $request->doc_id;
		$type = $request->type;
		$visit_type = $request->visit_type;
		$app_id = $request->app_id;
		$pId = $request->pId;
		$date = date('d-m-Y',strtotime($request->date));
		// pr(base64_decode($type));
		$doctor = Doctors::where(['user_id'=>$doc_id])->first(); // pr($doctor);
		if(!empty($doctor->convenience_fee)){
			$charge = $doctor->convenience_fee;
		}
		else{
			$charge = getSetting("service_charge_rupee")[0];
		}
		$consultation_fees = 0;
		if(base64_decode($type) == '1') { 
			$consultation_fees = $doctor->oncall_fee;;
		}
		else if(base64_decode($type) == '2'){ 
			$consultation_fees = 0;
		} 
		$conFee = $consultation_fees + $charge;
		if($doctor->id == 49188){
         $conFee = 99;
		}
		return view('admin.appointments.slots',compact('doctor','type','date','conFee','visit_type','app_id','pId'));
	}
	public function showSlotAdminCampAppt(Request $request) {
		$doc_id = $request->doc_id;
		$pId = $request->pId;
		$date = date('d-m-Y',strtotime($request->date));
		$type = '1';
		$visit_type = 1;
		$doctor = Doctors::where(['user_id'=>$doc_id])->first(); // pr($doctor);
		if(!empty($doctor->convenience_fee)){
			$charge = $doctor->convenience_fee;
		}
		else{
			$charge = getSetting("service_charge_rupee")[0];
		}
		$consultation_fees = 0;
		if(base64_decode($type) == '1') { 
			$consultation_fees = $doctor->oncall_fee;;
		}
		else if(base64_decode($type) == '2'){ 
			$consultation_fees = 0;
		} 
		$conFee = $consultation_fees + $charge;
		if($doctor->id == 49188){
         $conFee = 99;
		}
		return view('admin.appointments.camp-slots',compact('doctor','type','date','conFee','visit_type','pId'));
	}
	public function sendPres(Request $request) {
		$data = $request->all();
		$appt = Appointments::with(['AppointmentOrder','User.DoctorInfo','Patient'])->where("id",$data['appId'])->first();
		$name = $appt->Patient->first_name." ".$appt->Patient->last_name;
		$docName = "Dr. ".$appt->User->DoctorInfo->first_name." ".$appt->User->DoctorInfo->first_name;
		$pdfUrl = url("/")."/rad/".base64_encode($appt->id);
		$tmpName = "pres_share_v3h";
		$post_data = ['parameters'=>[['name'=>'name','value'=>$name],['name'=>'link','value'=>$pdfUrl]],'template_name'=>$tmpName,'broadcast_name'=>'share prescription'];
		
		$presDta = UserPrescription::select(["prescription","type"])->where(['appointment_id'=>$data['appId']])->orderBy("id","DESC")->first();
		$this->writeClinicNoteFile($appt->Patient->patient_number,$presDta->prescription);
		$preUrl = 	getPath("uploads/PatientDocuments/".$appt->Patient->patient_number."/misc/clinicalNotePrint.pdf");
		$curl = curl_init();
		$cfile = getCurlValue($preUrl,'application/pdf','clinicalNotePrint.pdf');
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://live-server-2748.wati.io/api/v1/sendSessionFile/91".$appt->Patient->mobile_no."?caption=prescription",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => array('file' => $cfile),
		  CURLOPT_HTTPHEADER => array(
			paytmAuthToken(),
			"content-type: multipart/form-data",
			"Content-Type: application/pdf",
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response,true);
		if(isset($response['result']) && $response['result'] == 'success') {
			return 1;
		}
		else {
			return sendWhatAppMsg($post_data,$appt->Patient->mobile_no);
		}
		// if(isset($response['ticketStatus']) && ($response['ticketStatus'] == 'CLOSED' || $response['ticketStatus'] == 'EXPIRED')) {
			
		// }
	}
	
	public function sendPresToPharmacy(Request $request) {
		$data = $request->all();
		$appt = Appointments::with(['AppointmentOrder','User.DoctorInfo','Patient'])->where("id",$data['appId'])->first();
		$name = $appt->Patient->first_name." ".$appt->Patient->last_name;
		$docName = "Dr. ".$appt->User->DoctorInfo->first_name." ".$appt->User->DoctorInfo->first_name;
		$pdfUrl = url("/")."/rad/".base64_encode($appt->id);
		$tmpName = "pres_share_v3h";
		$post_data = ['parameters'=>[['name'=>'name','value'=>$name],['name'=>'link','value'=>$pdfUrl]],'template_name'=>$tmpName,'broadcast_name'=>'share prescription'];
		
		$presDta = UserPrescription::select(["prescription","type"])->where(['appointment_id'=>$data['appId']])->orderBy("id","DESC")->first();
		$this->writeClinicNoteFile($appt->Patient->patient_number,$presDta->prescription);
		$preUrl = 	getPath("uploads/PatientDocuments/".$appt->Patient->patient_number."/misc/clinicalNotePrint.pdf");
		$curl = curl_init();
		$cfile = getCurlValue($preUrl,'application/pdf','clinicalNotePrint.pdf');
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://live-server-2748.wati.io/api/v1/sendSessionFile/918905557252?caption=prescription",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => array('file' => $cfile),
		  CURLOPT_HTTPHEADER => array(
			paytmAuthToken(),
			"content-type: multipart/form-data",
			"Content-Type: application/pdf",
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response,true);
		// pr($response);
		if(isset($response['result']) && $response['result'] == 'success') {
			return 1;
		}
		else {
			return sendWhatAppMsg($post_data,8905557252);
		}
		// if(isset($response['ticketStatus']) && ($response['ticketStatus'] == 'CLOSED' || $response['ticketStatus'] == 'EXPIRED')) {
			
		// }
	}
	public function showPrescription(Request $request){
		$data = $request->all();
		
		$appt = Appointments::with(['AppointmentOrder','User.DoctorInfo','Patient'])->where("id",$data['appId'])->first();
		$presDta = UserPrescription::select(["prescription","p_meta_data","type"])->where(['appointment_id'=>$data['appId']])->orderBy("id","DESC")->first();
		return writeClinicNoteFile($appt->Patient->patient_number,$presDta);
	}
	public function cahngeStatus(Request $request){
        try {
		 $id =  Session::get('id');
		 $jsonData = array('status' => $request->status, 'user_id' => Session::get('id'));
		 Appointments::where('id', $request->appId)->update(array('working_status' => json_encode($jsonData)));
        } catch (Exception $e) {
            $message = $e->getMessage();
            var_dump('Exception Message: '. $message);
            exit;
        }
		return response()->json(['success'=>true]);
	}

	public function UploadedDocs(Request $request){
		$app_id = $request->aPiD;
		$appointment = Appointments::with(['patient'])->where('id',$app_id)->first();
	
		$doctor = Doctors::with('docSpeciality')->where('user_id',$appointment->doc_id)->first();
		$patient_number = @$appointment->patient->patient_number;
		$appointment_id = $appointment->id;

		$path = 'uploads/PatientDocuments/'.$patient_number.'/appointments/'.$appointment_id;
		$documents = [];
		$files = [];
		if(Storage::disk('s3')->exists($path)) {
			$files = Storage::disk('s3')->files($path);
		}
		// pr($files);
		if(isset($files) && count($files)){
		 foreach($files as $file) {
			$docName = substr($file, strrpos($file, '/') + 1);
			$file_ext = explode('.', $docName);
			$file_ext_count = count($file_ext);
			$cnt = $file_ext_count - 1;
			$file_extension = $file_ext[$cnt];
			$documents[] = ['doc_name'=>$docName,'file_extension'=>$file_extension,'document'=>getPath($file)];
		 }
		}
		return view($this->getView('admin.appointments.uploaded-docs'),['documents'=>$documents]);
	}



	public function saveApptInfo(Request $request) {
        try {
		if($request->isMethod('post')) {	
		 	$data = $request->all();
			$order = AppointmentOrder::select('meta_data')->where('id',$data['appt_order_id'])->first();
			$meta_data = json_decode($order->meta_data,true);
			$meta_data['patient_name'] = $data['pname'];
			$meta_data['dob'] = $data['pdob'];
			$meta_data['other_mobile_no'] = $data['pmobno'];
			$meta_data['gender'] = $data['pgender'];
			AppointmentOrder::where('id',$data['appt_order_id'])->update([
				'meta_data' => json_encode($meta_data)
			]);
			$first_name = trim(strtok($data['pname'], ' '));
			$last_name = trim(strstr($data['pname'], ' '));
			User::where('pId', $data['pId'])->update(array(
				'first_name' => ucfirst($first_name),
				'last_name' => $last_name,
				'gender' => (isset($data['pgender']) ? $data['pgender'] : null),
				'dob' => (isset($data['pdob']) ? strtotime($data['pdob']) : null),
				'other_mobile_no' => $data['pmobno'],
			));
			Patients::where('id', $data['pId'])->update(array(
				'first_name' => ucfirst($first_name),
				'last_name' => $last_name,
				'gender' => (isset($data['pgender']) ? $data['pgender'] : null),
				'dob' => (isset($data['pdob']) ? strtotime($data['pdob']) : null),
				'other_mobile_no' => $data['pmobno'],
			));
		}
		 return 1;
        } catch (Exception $e) {
            $message = $e->getMessage();
            var_dump('Exception Message: '. $message);
            exit;
        }
	}



	public function reopenPrescription(Request $request) 
	{
		$data = $request->all();
		try {
			DB::beginTransaction();
	
			$appointment = Appointments::where('id', $data['appId'])->update([
				'visit_status' => 0,
				'current_status' => 3,
			]);
	
			$userPrescription = UserPrescription::where('appointment_id', $data['appId'])->delete();
	
			DB::commit();
			return 1;

		} catch (\Exception $e) {
			DB::rollBack();
	
			Log::error('Failed to reopen prescription: ' . $e->getMessage());
			return 2;
	
			
			
		}
		
	}
}
