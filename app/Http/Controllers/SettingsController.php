<?php

namespace App\Http\Controllers\Admin;;

use App\Events\CallEvent;
use App\Http\Controllers\AgoraToken\RtcTokenBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
// use App\Models\Admin\Admin;
use App\Models\User;
use App\Models\NewsFeeds;
use App\Models\Admin\AdminModules;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Speciality;
use App\Models\Doctors;
use App\Models\SpecialityGroup;
use App\Models\AuMarathonReg;
use App\Models\OrganizationMaster;
use App\Models\CampData;
use App\Models\CampTitleMaster;
use App\Models\Pages;
use App\Models\Settings;
use App\Models\PpSliders;
use App\Models\ReferralMaster;
use App\Models\ehr\EmailTemplate;
use App\Models\ehr\Speciality as DocSpeciality;
use App\Models\CovidHospitalDoctors;
use App\Models\CovidHospital;
use App\Models\ehr\Appointments;
use App\Models\ehr\VideoCall;
use App\Models\OrganizationPayment;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher;

//use Illuminate\Mail\Mailer;
class SettingsController extends Controller
{

	public function UserPermission(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
		} else {
			$users = Admin::orderBy('id', 'ASC')->get();
			return view('admin.settings.user-permission', compact('users'));
		}
	}

	public function LoadUserPermission(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			if (isset($data['save_permissions']) && $data['save_permissions'] = 'save_permissions') {
				Admin::Where('id', $data['user_id'])->update(['module_permissions' => implode(",", $data['modules_access'])]);
			}
			$permissions = Admin::select('module_permissions')->Where('id', $data['user_id'])->first();
			$permissions = $permissions->module_permissions;
			$modules = AdminModules::orderBy('id', 'ASC')->get();
			return view('admin.settings.load-permissions', compact('modules', 'permissions'));
		}
	}
	public function addSubAdmin(Request $request)
	{

		if ($request->isMethod('post')) {
			$data = $request->all();
			$password = Hash::make($data['password']);
			$admin = Admin::where("email", $data['email'])->first();
			if (empty($admin)) {
				Admin::create([
					'login_id' => Session::get('id'),
					'name' => $data['name'],
					'email' => $data['email'],
					'mobile_no' => $data['mobile_no'],
					'password' => $password,
					'status' => $data['status'],
				]);
				Session::flash('successMsg', "User Added Successfully");
				return 1;
			} else {
				return 2;
			}
		}
		return view('admin.settings.add-subadmin');
	}
	public function subadminList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.subadminList', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = Admin::where("delete_status", 1)->where("id", "!=", "1");
			if (!empty($search)) {
				$query->where('name', 'like', '%' . $search . '%');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$users = $query->orderBy('id', 'desc')->paginate($page);
			return view('admin.settings.subadmin-list', compact('users'));
		}
	}
	public function editSubAdmin(Request $request)
	{
		$id = $request->id;
		$user = Admin::Where('id', '=', $id)->first();
		return view('admin.settings.edit-subadmin', compact('user'));
	}
	public function modifySubAdmin(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			if ($data['action'] == 'edit') {
				// if (!empty($data['password'])) {
				// $password = Hash::make($data['password']);
				// }
				// else {
				// $password = $data['current_password'];
				// }
				$admin = Admin::where("email", $data['email'])->where('id', '!=', $data['id'])->first();
				if (empty($admin)) {
					Admin::Where('id', '=', $data['id'])->update([
						'name' => $data['name'],
						'email' => $data['email'],
						'mobile_no' => $data['mobile_no'],
						// 'password' => $password,
						'status' => $data['status'],
					]);
					Session::flash('successMsg', "Updated Successfully");
					return 1;
				} else {
					return 2;
				}
			} elseif ($data['action'] == 'delete') {
				Admin::where('id', $data['id'])->update(['delete_status' => 0]);
				Session::flash('successMsg', "Deleted Successfully");
				return 1;
			} elseif ($data['action'] == 'statusChange') {
				if ($data['status'] == '1') {
					Admin::where('id', $data['id'])->update(['status' => '0']);
					Session::flash('successMsg', "User Inactive Successfully");
				} else {
					Admin::where('id', $data['id'])->update(['status' => '1']);
					Session::flash('successMsg', "User Active Successfully");
				}
				return 1;
			} elseif ($data['action'] == 'openChangePassModal') {
				$user = Admin::select('id')->Where('id', '=', $data['id'])->first();
				return view('admin.settings.change-password', compact('user'));
			} elseif ($data['action'] == 'changePassword') {
				$password = Hash::make($data['password']);
				Admin::Where('id', '=', $data['id'])->update(['password' => $password]);
				Session::flash('successMsg', "Password Change Successfully");

				return 1;
			}
		}
	}

	public function specialityAll(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('grp_speciality'))) {
				$params['grp_speciality'] = base64_encode($request->input('grp_speciality'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.specialityAll', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$grp_speciality = base64_decode($request->input('grp_speciality'));
			$query = Speciality::where("delete_status", 1);
			if (!empty($search)) {
				$query->where(DB::raw('concat(specialities," ",IFNULL(spaciality,""))'), 'like', '%' . $search . '%');
			}
			if (!empty($grp_speciality)) {
				$query->where('group_id', $grp_speciality);
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$specialities = $query->orderBy("order_no", "ASC")->paginate($page);
		}
		return view('admin.speciality.speciality-master', compact('specialities'));
	}


	public function addSpeciality(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$fileName = "";
			$fileImgName = "";
			$speciality_already = Speciality::Where('specialities', '=', $data['specialities'])->first();
			$orderNo_already = "";
			if (!empty($data['order_no'])) {
				$orderNo_already = Speciality::Where('order_no', '=', $data['order_no'])->count();
			}
			if (!empty($speciality_already)) {
				return 2;
			}
			if (!empty($orderNo_already) && $orderNo_already > 0) {
				return 3;
			} else {
				if ($request->hasFile('speciality_image')) {
					$speciality_image  = $request->file('speciality_image');
					$fullName = str_replace(" ", "", $speciality_image->getClientOriginalName());
					$onlyName = explode('.', $fullName);
					if (is_array($onlyName)) {
						$fileImgName = $onlyName[0] . time() . "." . $onlyName[1];
					} else {
						$fileImgName = $onlyName . time();
					}
					$request->file('speciality_image')->move(public_path("/speciality-images"), $fileImgName);
				}
				if ($request->hasFile('speciality_icon')) {
					$speciality_icon  = $request->file('speciality_icon');
					$fullName = str_replace(" ", "", $speciality_icon->getClientOriginalName());
					$onlyName = explode('.', $fullName);
					if (is_array($onlyName)) {
						$fileName = $onlyName[0] . time() . "." . $onlyName[1];
					} else {
						$fileName = $onlyName . time();
					}
					$request->file('speciality_icon')->move(public_path("/speciality-icon"), $fileName);
				}
				Speciality::create([
					'specialities' => $data['specialities'],
					'slug' => $data['slug'],
					'alt_tag' => $data['alt_tag'],
					'spaciality' => $data['spaciality'],
					'spaciality_hindi' => $data['spaciality_hindi'],
					'speciality_text' => $data['speciality_text'],
					'keywords' => $data['keywords'],
					'order_no' => $data['order_no'],
					'tags' => $data['tags'],
					'description' => $data['description'],
					'spec_desc' => $data['spec_desc'],
					'spec_desc_hindi' => $data['spec_desc_hindi'],
					'manage_spec_desc' => $data['manage_spec_desc'],
					'speciality_icon' => $fileName,
					'speciality_image' => $fileImgName,
					'group_id' =>  $data['group_id'],
				]);
				DocSpeciality::create([
					'specialities' => $data['specialities'],
					'slug' => $data['slug'],
					'spaciality' => $data['spaciality'],
					'group_id' =>  $data['group_id']
				]);
				Session::flash('message', "Speciality Group Added Successfully");
				return 1;
			}
		}
		return view('admin.speciality.add-speciality');
	}

	public function editSpeciality(Request $request)
	{
		$id = $request->id;
		$speciality = Speciality::Where('id', '=', $id)->first();
		return view('admin.speciality.edit-speciality', compact('speciality'));
	}
	public function updateSpeciality(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$speciality_already = Speciality::Where('specialities', '=', $data['specialities'])->where('id', '!=', $data['id'])->first();

			$orderNo_already = "";
			if (!empty($data['order_no']) && $data['order_no'] != $data['old_order_no']) {
				$orderNo_already = Speciality::Where('order_no', '=', $data['order_no'])->count();
			}

			if (!empty($speciality_already)) {
				return 2;
			} else if (!empty($orderNo_already) && $orderNo_already > 0) {
				return 3;
			} else {
				$fileName = "";
				$fileImgName = "";
				if ($request->hasFile('speciality_icon')) {
					$filename = public_path() . '/speciality-icon/' . $data['speciality_icon_old'];
					if (file_exists($filename)) {
						File::delete($filename);
					}
					$speciality_icon  = $request->file('speciality_icon');
					$fullName = str_replace(" ", "", $speciality_icon->getClientOriginalName());
					$onlyName = explode('.', $fullName);
					if (is_array($onlyName)) {
						$fileName = $onlyName[0] . time() . "." . $onlyName[1];
					} else {
						$fileName = $onlyName . time();
					}
					$request->file('speciality_icon')->move(public_path("/speciality-icon"), $fileName);
				} else {
					$fileName = $data['speciality_icon_old'];
				}
				if ($request->hasFile('speciality_image')) {
					$fileImgName = public_path() . '/speciality-images/' . $data['speciality_image_old'];
					if (file_exists($fileImgName)) {
						File::delete($fileImgName);
					}
					$speciality_image  = $request->file('speciality_image');
					$fullName = str_replace(" ", "", $speciality_image->getClientOriginalName());
					$onlyName = explode('.', $fullName);
					if (is_array($onlyName)) {
						$fileImgName = $onlyName[0] . time() . "." . $onlyName[1];
					} else {
						$fileImgName = $onlyName . time();
					}
					$request->file('speciality_image')->move(public_path("/speciality-images"), $fileImgName);
				} else {
					$fileImgName = $data['speciality_image_old'];
				}

				Speciality::where('id', $data['id'])->update(array(
					'specialities' => $data['specialities'],
					'slug' => $data['slug'],
					'alt_tag' => $data['alt_tag'],
					'spaciality' => $data['spaciality'],
					'spaciality_hindi' => $data['spaciality_hindi'],
					'speciality_text' => $data['speciality_text'],
					'keywords' => $data['keywords'],
					'order_no' => $data['order_no'],
					'tags' => $data['tags'],
					'description' => $data['description'],
					'spec_desc' => $data['spec_desc'],
					'spec_desc_hindi' => $data['spec_desc_hindi'],
					'manage_spec_desc' => $data['manage_spec_desc'],
					'speciality_icon' => $fileName,
					'speciality_image' => $fileImgName,
					'group_id' => $data['group_id'],
					'status' => $data['status']
				));

				DocSpeciality::where('id', $data['id'])->update(array(
					'specialities' => $data['specialities'],
					'slug' => $data['slug'],
					'spaciality' => $data['spaciality'],
					'group_id' => $data['group_id'],
					'status' => $data['status']
				));

				Session::flash('message', "Speciality Updated Successfully");
				return 1;
			}
		}
		return 2;
	}



	public function specialityGroupMaster(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.specialityGroupMaster', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = SpecialityGroup::where("delete_status", 1);
			if (!empty($search)) {
				$query->where('group_name', 'like', '%' . $search . '%');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$specialities = $query->orderBy('id', 'asc')->paginate($page);
		}
		return view('admin.speciality.speciality-group-master', compact('specialities'));
	}


	public function addGroupSpeciality(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$speciality_already = SpecialityGroup::Where('group_name', '=', $data['group_name'])->first();
			if (!empty($speciality_already)) {
				return 2;
			} else {
				SpecialityGroup::create([
					'group_name' => $data['group_name'],
				]);
				Session::flash('message', "Speciality Group Added Successfully");
				return 1;
			}
		}
		return view('admin.speciality.add-group-speciality');
	}
	public function editGroupSpeciality(Request $request)
	{
		$id = $request->id;
		$speciality = SpecialityGroup::Where('id', '=', $id)->first();
		return view('admin.speciality.edit-group-speciality', compact('speciality'));
	}
	public function updateGroupSpeciality(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$speciality_already = SpecialityGroup::Where('group_name', '=', $data['group_name'])->where('id', '!=', $data['id'])->first();
			if (!empty($speciality_already)) {
				return 2;
			} else {
				SpecialityGroup::where('id', $data['id'])->update(array(
					'group_name' => $data['group_name']
				));
				Session::flash('message', "Speciality Group Updated Successfully");
				return 1;
			}
		}
		return 2;
	}
	public function AuMarathonReg(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if ($request->input('t_status') != "") {
				$params['t_status'] = base64_encode($request->input('t_status'));
			}
			return redirect()->route('admin.AuMarathonReg', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$t_status = base64_decode($request->input('t_status'));
			$query = AuMarathonReg::orderBy('id', 'desc');
			if (!empty($search)) {
				$query->where(DB::raw('concat(name," ",IFNULL(mobile_no,""))'), 'like', '%' . $search . '%');
			}
			if ($t_status != "") {
				$query->where('t_status', $t_status);
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$registrations = $query->paginate($page);
		}
		return view('admin.au-registrations', compact('registrations'));
	}

	public function updateTshirtStatus(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			if ($data['status'] == 0) {
				AuMarathonReg::where('id', $data['id'])->update(['t_status' => 1]);
				return 1;
			} else {
				AuMarathonReg::where('id', $data['id'])->update(['t_status' => 0]);
				return 2;
			}
		}
	}


	public function organizationMaster(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.organizationMaster', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = OrganizationMaster::where("delete_status", 1);
			if (!empty($search)) {
				$query->where('title', 'like', '%' . $search . '%');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$organizations = $query->orderBy('id', 'desc')->paginate($page);
			return view('admin.organization_master.organizationMaster', compact('organizations'));
		}
	}

	public function addOrganization(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$OrganizationMaster = OrganizationMaster::where("title", $data['title'])->first();
			// dd($data);
			$fileName = "";
			if ($request->hasFile('logo')) {
				$image  = $request->file('logo');
				$fullName = str_replace(" ", "", $image->getClientOriginalName());
				$onlyName = explode('.', $fullName);
				if (is_array($onlyName)) {
					$fileName = $onlyName[0] . time() . "." . $onlyName[1];
				} else {
					$fileName = $onlyName . time();
				}
				$request->file('logo')->move(public_path("/organization_logo"), $fileName);
			}

			//  dd($OrganizationMaster);
			if (empty($OrganizationMaster)) {
				OrganizationMaster::create([
					'title' => $data['title'],
					'logo' => $fileName,
				]);
				Session::flash('successMsg', "Added Successfully");
				return 1;
			} else {
				return 2;
			}
		}
	}

	public function editOrganization(Request $request)
	{
		$id = $request->id;
		$organization = OrganizationMaster::Where('id', '=', $id)->first();
		return view('admin.organization_master.edit-organization', compact('organization'));
	}
	public function modifyOrganization(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();

			if ($data['action'] == 'edit') {
				$fileName = "";
				if ($request->hasFile('logo')) {
					$filename = public_path() . '/organization_logo/' . $data['old_logo'];
					if (file_exists($filename)) {
						File::delete($filename);
					}
					$image  = $request->file('logo');
					$fullName = str_replace(" ", "", $image->getClientOriginalName());
					$onlyName = explode('.', $fullName);
					if (is_array($onlyName)) {
						$fileName = $onlyName[0] . time() . "." . $onlyName[1];
					} else {
						$fileName = $onlyName . time();
					}
					$request->file('logo')->move(public_path("/organization_logo"), $fileName);
				} else {
					$fileName = $data['old_logo'];
				}
				OrganizationMaster::Where('id', '=', $data['id'])->update([
					'title' => $data['title'],
					'logo' => $fileName,
				]);
				Session::flash('successMsg', "Updated Successfully");
				return 1;
			} elseif ($data['action'] == 'delete') {
				OrganizationMaster::where('id', $data['id'])->update(['delete_status' => 0]);
				Session::flash('successMsg', "Deleted Successfully");
				return 1;
			}
		}
	}



	public function campMaster(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('camp_id'))) {
				$params['camp_id'] = base64_encode($request->input('camp_id'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.campMaster', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$camp_id = base64_decode($request->input('camp_id'));
			$query = CampData::with(["CampTitleMaster", "user"]);
			if (!empty($search)) {
				$query->where('thy_ref_order_no', 'like', '%' . $search . '%');
			}
			if (!empty($camp_id)) {
				$query->where('camp_id', $camp_id);
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$camps = $query->orderBy('id', 'desc')->paginate($page);
			return view('admin.camp_master.campMaster', compact('camps'));
		}
	}

	public function addCamp(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$user = User::where('mobile_no', $request->get('mobile_no'))->orWhere('email', '=', $request->get('email'))->where('parent_id', 0)->first();
			if (empty($user)) {
				$password = rand(100000, 999999);
				$user = User::create([
					'first_name' => ucfirst($request->get('first_name')),
					'last_name' => $request->get('last_name'),
					'email' =>  $request->get('email'),
					'mobile_no' =>  $request->get('mobile_no'),
					'device_type' =>  3,
					'urls' =>  json_encode(getEhrFullUrls()),
					'password' => bcrypt($password),
					'parent_id' => 0,
					'status' =>  1,
				]);
			}

			$camp_id = $data['camp_id'];
			if ($data['camp_id'] == '0') {
				$camp = CampTitleMaster::create([
					'title' => $data['other_title'],
				]);
				$camp_id = $camp->id;
			}

			CampData::create([
				'user_id' => $user->id,
				'thy_lead_id' => $data['thy_lead_id'],
				'thy_ref_order_no' => $data['thy_ref_order_no'],
				'camp_id' => $camp_id
			]);
			$name = $user->first_name . " " . $user->last_name;
			$app_link = "www.healthgennie.com/download";
			$message =  urlencode("Dear " . $name . " You are registered successfully.\nFor Better Experience Download Health Gennie App\n" . $app_link);
			$this->sendSMS($user->mobile_no, $message);

			$to = $user->email;
			if (!empty($to)) {
				$EmailTemplate = EmailTemplate::where('slug', 'usercampmail')->first();
				if ($EmailTemplate) {
					$body = $EmailTemplate->description;
					$mailMessage = str_replace(array('{{name}}'), array($name), $body);
					$datas = array('to' => $to, 'from' => 'info@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'content' => $mailMessage, 'subject' => $EmailTemplate->subject);
					try {
						Mail::send('emails.all', $datas, function ($message) use ($datas) {
							$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
						});
					} catch (\Exception $e) {
						// Never reached
					}
				}
			}
			Session::flash('successMsg', "Added Successfully");
			return 1;
		}
	}

	public function editCamp(Request $request)
	{
		$id = $request->id;
		$camp = CampData::Where('id', '=', $id)->first();
		return view('admin.camp_master.edit-camp', compact('camp'));
	}
	public function updateCamp(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();

			$user = User::Where('id', '=', $data['user_id'])->update([
				'first_name' => ucfirst($request->get('first_name')),
				'last_name' => $request->get('last_name'),
				'email' =>  $request->get('email'),
				'mobile_no' =>  $request->get('mobile_no'),
			]);
			$camp_id = $data['camp_id'];
			if ($data['camp_id'] == '0') {
				$camp = CampTitleMaster::create([
					'title' => $data['other_title'],
				]);
				$camp_id = $camp->id;
			}
			CampData::Where('id', '=', $data['id'])->update([
				'thy_lead_id' => $data['thy_lead_id'],
				'thy_ref_order_no' => $data['thy_ref_order_no'],
				'camp_id' => $camp_id
			]);
			Session::flash('successMsg', "Updated Successfully");
			return 1;
		}
	}
	public function AddDynamicPage(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$page = Pages::create([
				'title' => $data['title'],
				'slug' => $data['slug'],
				'lng' => $data['lng'],
				'description' => $data['description']
			]);
			Session::flash('successMsg', "Added Successfully");
			return 1;
		}
	}

	public function PagesList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.PagesList', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = Pages::where("delete_status", 1);
			if (!empty($search)) {
				$query->where(DB::raw('concat(slug," ",ISNULL(title)," ")'), 'like', '%' . $search . '%');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$pages = $query->orderBy('slug', 'ASC')->paginate($page);
		}
		return view('admin.settings.pages-list', compact('pages'));
	}
	public function editPageContent(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$page = Pages::where("id", $data['id'])->first();
			return view('admin.settings.edit-page', compact('page'));
		}
	}
	public function updatePageContent(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$page = Pages::where("id", $data['id'])->update([
				'title' => $data['title'],
				'lng' => $data['lng'],
				'description' => $data['description']
			]);
			return 1;
		}
	}

	public function servicesMaster(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.servicesMaster', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = Settings::orderBy("id", "ASC");
			if (!empty($search)) {
				$query->where('id', 'like', '%' . $search . '%');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$services = $query->paginate($page);
			$services2 =  Settings::orderBy("id", "ASC")->get();
			return view('admin.settings.servicesMaster', compact('services', 'services2'));
		}
	}

	public function addServicesMaster(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			if ($data['type'] == 1) {
				if ($data['key'] == "addNew") {
					$alredy = Settings::where('key', 'like', '%' . $data['new_key'] . '%')->count();
					if ($alredy > 0) {
						return 0;
					} else {
						$service = Settings::create(['key' => $data['new_key']]);
						$key = $service->id;
					}
				} else {
					$key = $data['key'];
				}
				$values = Settings::Where('id', '=', $key)->first();

				if (!empty($values->value)) {
					$values = explode(",", $values->value);
					array_push($values, $data['value']);
				} else {
					$values = array();
					$values[] = $data['value'];
				}
				Settings::Where('id', '=', $key)->update(['value' => implode(",", $values)]);
				Session::flash('successMsg', "Added Successfully");
			} elseif ($data['type'] == 2) {
				$values = $data['value'];
				Settings::Where('id', '=', $data['id'])->update(['value' => implode(",", $values)]);
				Session::flash('successMsg', "Added Successfully");
			}
			return 1;
		}
	}


	public function editServices(Request $request)
	{
		$id = $request->id;
		$services = Settings::orderBy("id", "ASC")->get();
		$service = Settings::Where('id', '=', $id)->first();
		return view('admin.settings.editServicesMaster', compact('services', 'service'));
	}


	public function sliderMaster(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.sliderMaster', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = PpSliders::where("delete_status", 1);


			if (!empty($search)) {
				$query->where('title', 'like', '%' . $search . '%');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$sliders = $query->orderBy('id', 'desc')->paginate($page);

			return view('admin.slider_master.sliderMaster', compact('sliders'));
		}
	}

	public function addSlider(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$fileName = "";
			if ($request->hasFile('image')) {
				$image  = $request->file('image');
				$fullName = str_replace(" ", "", $image->getClientOriginalName());
				$onlyName = explode('.', $fullName);
				if (is_array($onlyName)) {
					$fileName = $onlyName[0] . time() . "." . $onlyName[1];
				} else {
					$fileName = $onlyName . time();
				}
				$request->file('image')->move(public_path("/slidersImages"), $fileName);
			}
			PpSliders::create([
				'title' => $data['title'],
				'image' => $fileName,
				'description' => $data['description'],
			]);
			Session::flash('successMsg', "Added Successfully");
			return 1;
		}
	}

	public function editSlider(Request $request)
	{
		$id = $request->id;
		$slider = PpSliders::Where('id', '=', $id)->first();
		return view('admin.slider_master.edit-slider', compact('slider'));
	}
	public function modifySliderMaster(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();

			if ($data['action'] == 'edit') {
				$fileName = "";
				if ($request->hasFile('image')) {
					$filename = public_path() . '/slidersImages/' . $data['old_image'];
					if (file_exists($filename)) {
						File::delete($filename);
					}
					$image  = $request->file('image');
					$fullName = str_replace(" ", "", $image->getClientOriginalName());
					$onlyName = explode('.', $fullName);
					if (is_array($onlyName)) {
						$fileName = $onlyName[0] . time() . "." . $onlyName[1];
					} else {
						$fileName = $onlyName . time();
					}
					$request->file('image')->move(public_path("/slidersImages"), $fileName);
				} else {
					$fileName = $data['old_image'];
				}
				PpSliders::Where('id', '=', $data['id'])->update([
					'title' => $data['title'],
					'image' => $fileName,
					'description' => $data['description'],
				]);
				Session::flash('successMsg', "Updated Successfully");
				return 1;
			} elseif ($data['action'] == 'delete') {
				$res = PpSliders::where('id', $data['id'])->update(['delete_status' => 0]);
				Session::flash('successMsg', "Deleted Successfully");
				return 1;
			}
		}
	}

	public function HosBedList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.HosBedList', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = CovidHospital::with("CovidHospitalDoctors");
			if (!empty($search)) {
				$query->where('name', 'like', '%' . $search . '%');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$hospitals = $query->orderBy('name', 'ASC')->paginate($page);
		}
		return view('admin.settings.hospital-list', compact('hospitals'));
	}

	public function editHosBed(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$hospital = CovidHospital::where("id", $data['id'])->first();
			return view('admin.settings.edit-hospital', compact('hospital'));
		}
	}
	public function AddHosPage(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$hospital = CovidHospital::create([
				'name' => $data['name'],
				'total_general_beds' => $data['total_general_beds'],
				'o_gen_beds' => $data['o_gen_beds'],
				'a_gen_beds' => $data['a_gen_beds'],
				'total_oxygen_beds' => $data['total_oxygen_beds'],
				'o_oxy_beds' => $data['o_oxy_beds'],
				'a_oxy_beds' => $data['a_oxy_beds'],
				'total_icu_beds_w_v' => $data['total_icu_beds_w_v'],
				'o_icu_beds_w_v' => $data['o_icu_beds_w_v'],
				'a_icu_beds_w_v' => $data['a_icu_beds_w_v'],
				'total_icu_beds_v' => $data['total_icu_beds_v'],
				'o_icu_beds_v' => $data['o_icu_beds_v'],
				'a_icu_beds_v' => $data['a_icu_beds_v'],
				'help_line' => $data['help_line'],
				'url' => $data['url'],
				'nodal_officer' => $data['nodal_officer'],
				'asst_nodal_officer' => $data['asst_nodal_officer'],
				'city' => $data['city'],
				'state' => $data['state'],
			]);
			$doctors = $data['doctors'];
			if (count($doctors) > 0) {
				foreach (json_decode($doctors) as $tag) {
					CovidHospitalDoctors::create([
						'hos_id' => $hospital->id,
						'name' => $tag
					]);
				}
			}
			Session::flash('successMsg', "Hospital Added Successfully");
			return 1;
		}
	}
	public function updateHospitalContent(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			// pr($data);
			$hospital = CovidHospital::where("id", $data['id'])->update([
				'name' => $data['name'],
				'total_general_beds' => $data['total_general_beds'],
				'o_gen_beds' => $data['o_gen_beds'],
				'a_gen_beds' => $data['a_gen_beds'],
				'total_oxygen_beds' => $data['total_oxygen_beds'],
				'o_oxy_beds' => $data['o_oxy_beds'],
				'a_oxy_beds' => $data['a_oxy_beds'],
				'total_icu_beds_w_v' => $data['total_icu_beds_w_v'],
				'o_icu_beds_w_v' => $data['o_icu_beds_w_v'],
				'a_icu_beds_w_v' => $data['a_icu_beds_w_v'],
				'total_icu_beds_v' => $data['total_icu_beds_v'],
				'o_icu_beds_v' => $data['o_icu_beds_v'],
				'a_icu_beds_v' => $data['a_icu_beds_v'],
				'help_line' => $data['help_line'],
				'url' => $data['url'],
				'nodal_officer' => $data['nodal_officer'],
				'asst_nodal_officer' => $data['asst_nodal_officer'],
				'city' => $data['city'],
				'state' => $data['state'],
				'status' => $data['status'],
			]);
			$doctors = $data['doctors'];
			if (count($doctors) > 0) {
				CovidHospitalDoctors::where('hos_id', $data['id'])->delete();
				foreach (json_decode($doctors) as $tag) {
					CovidHospitalDoctors::create([
						'hos_id' => $data['id'],
						'name' => $tag
					]);
				}
			}
			return 1;
		}
	}


	public function addReferral(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$referral =  ReferralMaster::Where('code', $data['code'])->Where(['status' => 1, 'delete_status' => 1])->get();
			if (count($referral) > 0) {
				return 2;
			} else {
				$plan_ids = null;
				if (!empty($data['plan_ids'])) {
					$ids = [];
					foreach ($data['plan_ids'] as $slug) {
						$slug_ids = getPlanIdToSlug($slug);
						foreach ($slug_ids as $val) {
							$ids[] = $val;
						}
					}
					$plan_ids = implode(",", $ids);
				}
				$user =  ReferralMaster::create([
					// 'type' => $data['type'],
					'title' => $data['title'],
					'referral_discount_type' => $data['referral_discount_type'],
					'referral_discount' => $data['referral_discount'],
					'code' => $data['code'],
					// 'referral_duration_type' => $data['referral_duration_type'],
					// 'referral_duration' => $data['referral_duration'],
					'code_last_date' => date('Y-m-d', strtotime($data['code_last_date'])),
					'other_text' => $data['other_text'],
					'is_show' => $data['is_show'],
					'max_uses' => $data['max_uses'],
					'plan_ids' => $plan_ids,
					'term_conditions' => $data['term_conditions'],
					'added_by' => Session::get('id'),
				]);
				Session::flash('message', "Referral Added Successfully");
				return 1;
			}
		} else {
			return view('admin.referral_master.add-referral');
		}
	}
	public function editReferral($id)
	{
		$referral = ReferralMaster::Where('id', '=', base64_decode($id))->first();
		return view('admin.referral_master.edit-referral', compact('referral'));
	}
	public function updateReferralsMaster(Request $request)
	{
		// session_unset('message');
		if ($request->isMethod('post')) {
			$data = $request->all();
			$id = $data['id'];
			$ref =  ReferralMaster::Where('code', $data['code'])->Where(['status' => 1, 'delete_status' => 1])->Where('id', '!=', $id)->first();
			if (!empty($ref)) {
				return 2;
			} else {
				$plan_ids = null;
				if (!empty($data['plan_ids'])) {
					$ids = [];
					foreach ($data['plan_ids'] as $slug) {
						$slug_ids = getPlanIdToSlug($slug);
						foreach ($slug_ids as $val) {
							$ids[] = $val;
						}
					}
					$plan_ids = implode(",", $ids);
				}
				$user =  ReferralMaster::where('id', $id)->update(array(
					// 'type' => $data['type'],
					'title' => $data['title'],
					'referral_discount_type' => $data['referral_discount_type'],
					'referral_discount' => $data['referral_discount'],
					'code' => $data['code'],
					// 'referral_duration_type' => $data['referral_duration_type'],
					// 'referral_duration' => $data['referral_duration'],
					'code_last_date' => date('Y-m-d', strtotime($data['code_last_date'])),
					'other_text' => $data['other_text'],
					'is_show' => $data['is_show'],
					'max_uses' => $data['max_uses'],
					'plan_ids' => $plan_ids,
					'term_conditions' => $data['term_conditions'],
				));
				Session::flash('message', "Referral Code Updated Successfully");
				return 1;
			}
		}
	}
	public function deleteReferralMaster($id)
	{
		session_unset('message');
		ReferralMaster::where('id', base64_decode($id))->update(array('delete_status' => '0'));
		Session::flash('message', "Referral code deleted Successfully");
		return redirect()->route('admin.referralMaster');
	}
	public function updateReferralStatus(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			if ($data['status'] == 0) {
				ReferralMaster::where('id', $data['id'])->update(['status' => 1]);
				return 1;
			} else {
				ReferralMaster::where('id', $data['id'])->update(['status' => 0]);
				return 2;
			}
		}
	}


	public function referralMaster(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.referralMaster', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = ReferralMaster::where("delete_status", 1);


			if (!empty($search)) {
				$query->where('title', 'like', '%' . $search . '%');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$referrals = $query->orderBy('id', 'desc')->paginate($page);
			return view('admin.referral_master.referralMaster', compact('referrals'));
		}
	}
	public function viewOrgPay(Request $request)
	{
		$organization_id = base64_decode($request->id);
		$records = OrganizationPayment::with("OrganizationMaster")->where(['organization_id' => $organization_id])->orderBy('id', 'desc')->get();
		return view('admin.organization_master.view-org-pay', compact('records', 'organization_id'));
	}
	public function addNewPay(Request $request)
	{
		$data = $request->all();
		OrganizationPayment::create([
			'organization_id' => $data['organization_id'],
			'amount' => $data['amount'],
			'remaining_amount' => $data['amount'],
		]);
		return 1;
	}


	public function addquestionmaster(Request $request)
	{

		return view('admin.quizQuestion.quiz-question-list');
	}

	
	public function acceptCall(Request $request)
    {
        $videoCall = VideoCall::where('channel_name', $request->channel_name)->first();
        $videoCall->update(['status' => 'accepted']);

        event(new CallEvent($videoCall));

        return response()->json(['status' => 'accepted']);
    }

    public function rejectCall(Request $request)
    {
        $videoCall = VideoCall::where('channel_name', $request->channel_name)->first();
        $videoCall->update(['status' => 'rejected']);

        event(new CallEvent($videoCall));

        return response()->json(['status' => 'rejected']);
    }
}
