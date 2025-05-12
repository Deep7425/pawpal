@extends('layouts.admin.Masters.Master')
@section('title', 'Users List')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	 <!-- =============================================== -->
            <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="header-icon">
                        <i class="pe-7s-box1"></i>
                    </div>
                    <div class="header-title">
                        <form action="#" method="get" class="sidebar-form search-box pull-right hidden-md hidden-lg hidden-sm">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button type="submit" name="search" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                        <h1>Users</h1>
                        <small>Users list</small>
                        <ol class="breadcrumb hidden-xs">
                            <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                            <li class="active">Users</li>
                        </ol>
                    </div>
                </section>
                <!-- Main content -->
                <section class="content">


                    <div class="row">
					@if(session()->get('message'))
					<div class="alert alert-success">
					<strong>Success!</strong> {{ session()->get('message') }}
					</div>
					@endif
                        <div class="col-sm-12">
                            <div class="panel panel-bd lobidrag">
                                <div class="panel-heading">
                                  {{app('request')->input('facility')}}
                                    <div class="btn-group">
                                        <a class="btn btn-success" href="javascript:void();">{{$patients->total()}}</a>
                                    </div>
                                     <div class="btn-group">
                                      <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
                                      <form method="post" class="UserDataExcelImports" enctype="multipart/form-data" action="{{ route('admin.userExcelImport') }}">
                                      {{ csrf_field() }}
                                      <div class="form-group">
                                      <table class="table">
                                        <tr>
                                         <td style="width: 100px;">
                                        <input type="file" class="file-input" name="select_file" required>
                                         </td>
                                         <td>
                                        <input type="submit" name="upload"  class="btn btn-primary" value="Upload"/>
                                         </td>
                                         <td>
                                         <a href="{{asset('public/users-sample.xls')}}" class="btn btn-success btn-icon-split" download>
                                           <span class="icon text-white-50">
                                             <i class="fa fa-download"></i>
                                           </span>
                                           <span class="text">Sample Download</span>
                                           </a>
                                         </td>
                                        </tr>
                                       </table>
                                      </div>
                                       </form>
                                    </div>
                                    <div class="btn-group">
                          						<a class="btn btn-primary" href="{{route('admin.addUser')}}"><i class="fa fa-user"></i> Add User</a>
                          					</div>
                          					<div class="TOPMENU" id="example_length">
                                                    {!! Form::open(array('route' => 'admin.patientList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                                                        <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>

														<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
															<!-- <option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option> -->
															<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
															<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
															<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
															<option value="500" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '500') selected @endif @endif>500</option>
															<option value="1000" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '1000') selected @endif @endif>1000</option>
															<option value="2000" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '2000') selected @endif @endif>2000</option>
														</select>
                                                </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="panel-header panel-headerTop123">

											<div class="col-sm-3">
												<div class="dataTables_length">
													<label>State</label>
													<select class="form-control state_id searchDropDown" name="state_id">
													  <option value="">Select State</option>
														@foreach (getStateList(101) as $state)
															<option value="{{ $state->id }}" @if(isset($_GET['state_id'])) @if(base64_decode($_GET['state_id']) == $state->id) selected @endif @endif>{{ $state->name }}</option>
														@endforeach
													</select>
											   </div>
										   </div>
										   <div class="col-sm-3">
												<div class="dataTables_length">
												<label>City</label>
													<select class="form-control city_id searchDropDown" name="city_id">
														<option value="">Select City</option>
														@if(!empty(old('state_id')))
														@foreach (getCityList(old('state_id')) as $city)
															<option value="{{ $city->id }}" @if(isset($_GET['city_id'])) @if(base64_decode($_GET['city_id']) == $city->id) selected @endif @endif >{{ $city->name }}</option>
														@endforeach
														@endif
													</select>
											   </div>
										   </div>
										  <div class="col-sm-2">
                                            <div class="dataTables_length">
												<label>From</label>
												<div class="input-group date">
												   <input type="text" autocomplete="off" class="form-control fromStartDate" name="start_date" value="@if((app('request')->input('start_date'))!=''){{ base64_decode(app('request')->input('start_date')) }}@endif"/>
												   <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i>
												   </span>
												</div>
                                           </div>
                                       </div>
									   <div class="col-sm-2">
                                            <div class="dataTables_length">
												<label>To</label>
												<div class="input-group date">
												   <input type="text" autocomplete="off" class="form-control toStartDate" name="end_date" value="@if((app('request')->input('end_date'))!=''){{ base64_decode(app('request')->input('end_date')) }}@endif"/>
												   <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
												</div>
                                           </div>
                                       </div>
                                       <div class="col-sm-2">
										   <div class="dataTables_length">
											<label>Organization</label>
											 <select class="form-control" name="organization_type">
												<option value="">Select</option>
												@if(count($OrganizationList))
													<option value="blank" @if((app('request')->input('organization_type'))!='') @if(base64_decode(app('request')->input('organization_type')) == 'blank') selected @endif @endif>Without Organization</option>
													@foreach($OrganizationList as $raw)
													<option value="{{$raw->id}}" @if(isset($_GET['organization_type'])) @if(base64_decode($_GET['organization_type']) == $raw->id) selected @endif @endif>{{$raw->title}}</option>
													@endforeach
												@endif
											  </select>
											</div>
										  </div>
									    <div class="col-sm-3">
										   <div class="dataTables_length">
											<label>Type</label>
											 <select class="form-control userTypeFilter" name="user_type">
											   <option value="">User Type</option>
												 <option value="2" @if(isset($_GET['user_type'])) @if(base64_decode($_GET['user_type']) == 2) selected @endif @endif>Registered (HealthGennie)</option>
												 <option value="3" @if(isset($_GET['user_type'])) @if(base64_decode($_GET['user_type']) == 3) selected @endif @endif>Registered (Doctor Portal)</option>
												 <!--<option value="4" @if(old('user_type') == 4) selected @endif>Registered (Appointment Not Done)</option>-->
												 <option value="5" @if(isset($_GET['user_type'])) @if(base64_decode($_GET['user_type']) == 5) selected @endif @endif>Registered (HealthGennie) (Appointment Done)</option>
												 <option value="1" @if(isset($_GET['user_type'])) @if(base64_decode($_GET['user_type']) == 1) selected @endif @endif>Subscribed</option>
											 </select>
											</div>
										  </div>
										  <div class="col-sm-3 registeredFromDiv" @if(isset($_GET['user_type']) && base64_decode($_GET['user_type']) == "2") style="display:block;" @else style="display:none;" @endif>
										   <div class="dataTables_length">
										  <label>Registered From</label>
										   <select class="form-control registeredFromSelect" name="reg_type">
											 <option value="">Select..</option>
											<option value="1" @if(isset($_GET['reg_type'])) @if(base64_decode($_GET['reg_type']) == 1) selected @endif @endif>Android</option>
											 <option value="2" @if(isset($_GET['reg_type'])) @if(base64_decode($_GET['reg_type']) == 2) selected @endif @endif>IOS</option>
											 <option value="3" @if(isset($_GET['reg_type'])) @if(base64_decode($_GET['reg_type']) == 3) selected @endif @endif>Web</option>
											 <option value="4" @if(isset($_GET['reg_type'])) @if(base64_decode($_GET['reg_type']) == 4) selected @endif @endif>Paytm</option>
										   </select>
										  </div>
										  </div>
										  <div class="col-sm-3">
										   <div class="dataTables_length">
											<label>Leads Type</label>
											 <select class="form-control" name="lead_type">
											   <option value="">Type</option>
												 <option value="6" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 6) selected @endif @endif>Pending</option>
												 <option value="2" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 2) selected @endif @endif>Call Established</option>
												 <option value="3" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 3) selected @endif @endif>Not Reachable/Switched Off</option>
												 <option value="4" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 4) selected @endif @endif>Follow Up</option>
												 <option value="5" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 5) selected @endif @endif>Lost</option>
												 <option value="1" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 1) selected @endif @endif>Won</option>
											 </select>
											</div>
										  </div>
                                        <div class="col-sm-4">
                                            <div class="dataTables_length">
											<label>Name</label>
                                                <div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="search by name and mobile" value="@if(isset($_GET['search'])){{ base64_decode($_GET['search']) }}@endif"/>
												</div>
                                          </div>
										</div>
									  <div class="col-sm-2">
                                            <div class="dataTables_length">
                                                <label>Filter</label>
												<div class="input-group custom-search-form">
													<span class="input-group-btn">
                                                      <button class="btn btn-primary" type="submit">
                                                          SEARCH
                                                      </button>
                                                  </span>
												</div><!-- /input-group -->
											  {!! Form::close() !!}
                                          </div>
                                      </div>

									  <div class="col-sm-12">
									  <div class="dataTables_length">
									   <label>Send Campaign</label>
									  <a href="javascript:void(0);" class="btn btn-info btn-sm sendMsgModal" ><i class="fa fa-paper-plane" aria-hidden="true">Campaign</i></a>
									  </div>
									  </div>
                                  </div>

                              </div>
                              <div class="table-responsive ptTbl AppointmentptTbl">
                                <table class="table table-bordered table-hover">
                                    <thead class="success">
                                        <tr>
                                            <th><input type="checkbox" class="selecAll" /></th>
                                            <th>S.No.</th>
                                            <th class="tab-appointment" title="Appointment">Appt</th>
                                            <th>Organization</th>
                                            <th>Reg. Type</th>
                                            <!--<th>Picture</th> -->
                                            <th>Name / Gender(PID)</th>
                                            <th>Mobile No</th>
                                            <th>Other Mobile</th>
                                            <th>Address</th>
                                            <th>city</th>
                                            <th>state</th>
                                            <th>Location</th>
                                            <th>Manage Leads</th>
											<!-- <th>Childs</th> -->
                                            <th style="width:80px;">Date</th>
                                            <!--<th>Subscription</th>-->
                                            <th style="width:40px; text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									@if($patients->count() > 0)
										@foreach($patients as $index => $pat)
                                        <tr class="tbrow">
											<th><input type="checkbox" value="{{$pat->id}}" class="sub_chk" /></th>
                                            <td>
                                               <label>{{$index+($patients->currentpage()-1)*$patients->perpage()+1}}.</label>
                                            </td>
											<td class="tab-appointment12">@if(!empty($pat->pId)){{getTotalAppointmentByUser($pat->pId)}} @else 0 @endif</td>
											<td>{{@$pat->OrganizationMaster->title}}</td>
                                            <td>
                                            	@if($pat->device_type == '1') Android
                                            	@elseif($pat->device_type == '2') IOS
                                                @else  @if($pat->login_type == '3') Paytm @else Web @endif  @endif
                                            </td>
                                            <!--<td>
												<img src="<?php
												// if(!empty($pat->urls)){
													// $urlss = json_decode($pat->urls);
													// if(!empty($pat->image)){
														// echo $urlss->url."/public/patients_pics/".$pat->image;
													// }
													// else { echo url("/")."/img/camera-icon.jpg"; }
												// }
												// else { echo url("/")."/img/camera-icon.jpg"; }
												?>" class="img-circle" alt="User Image" height="50" width="50"/>
											</td>-->
											<td>{{$pat->first_name}} {{$pat->last_name}} @if(!empty($pat->gender))/ {{$pat->gender}}@endif @if(!empty($pat->pId))({{$pat->pId}})@endif</td>
											<td>{{$pat->mobile_no}}</td>
											<td>{{@$pat->other_mobile_no}}</td>
											<td>{{$pat->address}}</td>
											<td>{{getCityName($pat->city_id)}}</td>
											<td>{{getStateName($pat->state_id)}}</td>
											<td>
												@if(!empty($pat->location_meta))
													@php $location_meta = json_decode($pat->location_meta); @endphp	@if(!empty($location_meta->locality)){{@$location_meta->locality}}, @endif @if(!empty($location_meta->subAdministrativeArea)){{@$location_meta->subAdministrativeArea}}, @endif @if(!empty($location_meta->administrativeArea)){{@$location_meta->administrativeArea}}@endif
												@endif
											</td>
											<td><a href="javascript:void(0);" pkey="{{base64_encode(@$pat->id)}}" r_from="2" class="btn btn-info btn-sm manageSprt" title="Manage Leads"><img src='{{ url("/img/customer-care-icon.png") }}'/>Manage Leads</a></td>
											<!-- <td>{{$pat->childUsers->count()}}</td> -->
											<td><div class="viewSubscription12">{{date('d-m-Y g:i A',strtotime($pat->created_at))}}</div></td>
											<!--<td>@if(in_array($pat->id, json_decode($UsersSubscriptions))) <span class="label-default label label-success lable-sm"> Subscribed </span> @endif</td>-->
											<td>
											<div class="viewSubscription123">
												<ul>
													<li>
														<a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
															<ul>
																@if(!empty($pat->pId))
																<li><a href="{{ route('admin.hgAppointments',['id'=>base64_encode($pat->pId)]) }}" title="view Subscription"><i class="fa fa-phone-square" aria-hidden="true"></i> Go To Appointments</a></li>
																@endif
																<li><a href="{{ route('subscription.viewSubscription',['id'=>base64_encode($pat->id)]) }}" title="view Subscription"><span class="fa fa-eye"></span> view Subscription</a></li>
																@if(!empty($pat->pId))<li><button title="View All child users of this patient" class="showChildUser" pid="{{base64_encode($pat->pId)}}"><i class="fa fa-eye" aria-hidden="true"></i>Show Child Users</button></li>@endif
																<li><a href="{{route('admin.editUser', base64_encode($pat->id))}}" title="edit user"><span class="fa fa-user"></span>  Edit User</a></li>
																<li><a href="javascript:void(0);" id="makeMedOrder" ppid="{{$pat->id}}" pId="{{base64_encode($pat->id)}}" title="Make Medicine Order"><img width="18" src="/img/Medicine-Manage-1.png"> Make Medicine Order</a></li>
																<li><a href="javascript:void(0);" id="addAppt" pId="{{base64_encode($pat->id)}}" title="Create Payment Link For Appointment"><i class="fa fa-calendar-check-o"></i>Paid Appointment</a></li>
																<li><button id="addFreeAppt" pId="{{base64_encode($pat->id)}}" totAppt="{{getTotalAppointmentByUser($pat->pId)}}" title="Create an Free Appointment"><i class="fa fa-calendar-check-o"></i>Free Appointment</button></li>
																@if(!empty(getOrderUrl($pat->id)))
																<!--<li><button title="Copy Payment LINK" class="copyBtn" onclick="copyToClipboard('{{getOrderUrl($pat->id)}}',this)">Copy Payment Link</button></li>-->
																@endif
																<li><button title="Show All Payment Links" class="showLinks" userId="{{base64_encode($pat->id)}}"><i class="fa fa-link" aria-hidden="true"></i> Payment Links</button></li>
																<li><button title="Create Lab Order" class="btn btn-default createNewOrder" type="button" pname="{{base64_encode($pat->first_name." ".$pat->last_name)}}" address="{{base64_encode(@$pat->address)}}" age="{{base64_encode(@$pat->age)}}" gender="{{base64_encode(@$pat->gender)}}" mobile_no="{{base64_encode(@$pat->mobile_no)}}"  email="{{base64_encode(@$pat->email)}}" id="{{base64_encode(@$pat->id)}}"><i class="fa fa-flask" aria-hidden="true"></i>Create Lab Order</button></li>
															</ul>
													</li>
												</ul>
											</div>
											</td>
										</tr>
									@endforeach
									@else
										<tr><td colspan="19">No Record Found </td></tr>
									@endif
                    				</tbody>
								</table>
							</div>
						<div class="page-nation text-right">
							<ul class="pagination pagination-large">
							{{ $patients->appends($_GET)->links() }}
							  <!--  <li class="disabled"><span>«</span></li>
								<li class="active"><span>1</span></li>
								<li><a href="#">2</a></li>
								<li class="disabled"><span>...</span></li><li>
								<li><a rel="next" href="#">Next</a></li> -->
							</ul>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section> <!-- /.content -->
	<div class="modal md-effect-1 md-show" id="sendMsgModal" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4 class="modal-title">Send Campaign</h4>
			</div>
			<div class="modal-body">
				<div class="panel panel-bd lobidrag">
					<div class="panel-heading">
						<div class="btn-group">
							<a class="btn btn-primary" href="{{ route('admin.patientList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}"> <i class="fa fa-list"></i> User List</a>
						</div>
					</div>
					<div class="panel-body">
						{!! Form::open(array('id' => 'sendUserBulkSms','name'=>'sendUserBulkSms')) !!}
            <input name="ids" value="" id="ids" type="hidden"/>
						<input name="Allids" value="{{json_encode($AllPatientsIds)}}" id="Allids" type="hidden"/>
            <div class="form-group">
              <p>
                <label class="radio-inline"><input type="checkbox" name="smsFor" value="1">Send to ALL (Include Filter)</label>
								<span class="help-block"></span>
							</p>
            </div>
						<div class="form-group">
              <label>Campaign Type</label>
							<p>
								<label class="radio-inline"><input type="checkbox" name="smsType[]" value="1">SMS</label>
								<label class="radio-inline"><input type="checkbox" class="smsType"  name="smsType[]" value="2">Notification</label>
								<span id="smsType" class="help-block"></span>
							</p>
						</div>
						<div class="form-group notificationDiv" style="display:none;">
							<label>Subject (Max:20 Character Allowed)</label>
							<input name="subject" value="" class="form-control subject" type="text"/>
							<span class="help-block"></span>
						</div>
						<div class="form-group">
							<label>Message (Max:255 Character Allowed)</label>
							<textarea value="" name="msg" class="form-control msg" placeholder="Text Message"></textarea>
							<span class="help-block"></span>
						</div>
						<div class="reset-button">
						   <button type="reset" class="btn btn-warning">Reset</button>
						   <button type="submit" class="btn btn-success send" id="upload-btn">Send</button>
						</div>
						{!! Form::close() !!}
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		</div>
	</div>
  <div class="modal fade" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content ">
    		<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal">×</button>
    			<h4 class="modal-title">Add Note</h4>
    		</div>
    		<div class="modal-body">
            {!! Form::open(array('id' => 'addNote','name'=>'addNote')) !!}
            <input type="hidden" name="id" id="patient_id" value="">
            <div class="form-group">
              <label>Note:</label>
              <textarea type="text" name="note" rows="5" class="form-control" id="patient_note" placeholder="Write Note..."></textarea>
              <span class="help-block"></span>
            </div>

            <div class="reset-button">
               <button type="reset" class="btn btn-warning">Reset</button>
               <button type="submit" class="btn btn-success submit">Save</button>
            </div>
           {!! Form::close() !!}
    		</div>
    		<div class="modal-footer">
    			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    		</div>
    	</div>

    	</div>
  </div>

  <div class="modal md-effect-1 md-show appoint" id="switchDoctorModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Appointment Link</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-body SwitchAppointment">
					{!! Form::open(array('id' => 'createApptLnk','name'=>'createApptLnk')) !!}
					<input name="user_id" value="" type="hidden"/>
					<input name="pId" value="" type="hidden"/>
					<input name="time" value="" type="hidden"/>
					<input name="apptBy" value="{{base64_encode(Session::get('id'))}}" type="hidden"/>
					<div class="form-groupTopMenu apooint">
					<div class="form-group">
						<label>Appt Type</label>
						<select class="form-control chooseType" name="app_type">
							<option value="">Select</option>
							<option value="{{base64_encode(1)}}">Tele</option>
							<option value="{{base64_encode(2)}}">In Clinic</option>
						</select>
						<span class="help-block"><label for="doctorSelectMultiple" generated="true" class="error" style="display:none;"></label></span>
					</div>
					<div class="form-group">
						<label>Doctors</label>
						<select class="form-control chooseDoc" name="doc_id">
							<option value="">Select Doctor</option>
							@if(count($practices) > 0)
								@foreach($practices as $pra)
									@if(!empty($pra->oncall_fee) || !empty($pra->consultation_fees))
									<option value="{{$pra->user_id}}">{{@$pra->first_name}} {{@$pra->last_name}} ({{@$pra->email}})
									@if(!empty($pra->consultation_fee))({{@$pra->consultation_fee}})@endif @if(!empty($pra->oncall_fee))({{@$pra->oncall_fee}}) @endif
									@if(in_array($pra->user_id,getSetting("specialist_doctor_user_ids")))(PLAN)@endif</option>
									@endif
								@endforeach
							@endif
						</select>
						<span class="help-block"><label for="doctorSelectMultiple" generated="true" class="error" style="display:none;"></label></span>
					</div>
					<div class="form-group appDateSection">
						<div class="appointment-popup-block appDate">
						  <label>Date</label>
						  <div class="dateDiv">
						  <input type="text" class="form-control start_timeCal startDate" name="appstart_date" value="{{date('Y-m-d')}}"  required readonly />
						  <span class="input-group-addon start_timeCalIcon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						  </div>
						  </div>
					</div>
					<!--<div class="form-group">
						<label>Appointment Create By</label>
						<select class="form-control" name="apptBy">
							<option value="">Select</option>
							@forelse(getAdmins() as $raw)
								<option value="{{$raw->id}}">{{$raw->name}}</option>
							@empty
							@endforelse
						</select>
						<span class="help-block"></span>
					</div>-->
					</div>
					<div class="slot-div"></div>
					<div class="wApt" style="display:none;">
						<label><input type="radio" name="appttype" class="apptType" value="1" />For Payment Link</label>
						<label><input type="radio" name="appttype" class="apptType" value="2" />For Direct Paid Appointment</label>
					</div>
					<div style="display:none;" class="paidApptBlock">
						<div class="form-group">
							<label>Payment Received By</label>
							<select class="form-control receivedBy" name="receivedBy">
								<option value="">Select</option>
								@forelse(getAdmins() as $raw)
									<option value="{{$raw->id}}" @if(Session::get('id') == $raw->id) selected @endif >{{$raw->name}}</option>
								@empty
								@endforelse
							</select>
							<span class="help-block"></span>
						</div>
						<div class="form-group">
							<label>Payment Mode:</label>
							<select class="form-control payment_mode_type" name="payment_mode">
								<option value="">Select Payment Mode</option>
								<option value="1">Online</option>
								<option value="2">Cash</option>
							</select>
							<span class="help-block">
							</span>
						</div>
						<div class="form-group txnId" style="display:none;">
							<label>Txn ID</label>
							<input type="text" class="form-control" name="tracking_id" />
							<span class="help-block"></span>
						</div>
					</div>
					<div class="reset-button">
					   <button type="submit" class="btn btn-success switchBtn" style="display:none;">SUBMIT</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
	</div>
</div>
<div class="modal md-effect-1 md-show" id="patApptModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">All Links</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-body">
					<table class="table">
						<thead>
							<tr>
                <td>S.No.</td>
								<td>Link Type</td>
								<td>Order No.</td>
								<td>Appointment Date</td>
								<td>Type</td>
								<td>Doctor Name</td>
								<td>Status</td>
								<td>Total Pay (Rs.)</td>
								<td>Link</td>
								<td>Date</td>
							</tr>
						</thead>
						<tbody class="upperTr"></tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
	</div>
</div>
</div> <!-- /.content-wrapper -->
<div class="modal fade" id="makeOrderModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!-- The Modal -->
<div class="modal fade" id="addAddressModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Add Address</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                        {!! Form::open(array('id' => 'addUserAddress','name'=>'addUserAddress', 'enctype' => 'multipart/form-data')) !!}
                          <input type="hidden" class="form-control" name="user_id" id="userId" value="">
                          <div class="col-sm-6 form-group">
                              <label>Address<i class="required_star">*</i></label>
                              <input type="text" class="form-control" placeholder="Address" name="address" maxlength="100" value="{{@$user->address}}" />
                              <span class="help-block"></span>
                          </div>
                          <div class="col-sm-6 form-group">
                              <label>Pincode<i class="required_star">*</i></label>
                              <input type="text" placeholder="Pincode" class="NumericFeild form-control" name="pincode" maxlength="6" value="" />
                              <div class="icon-container inputBoxLoader" style="display: none;">
                                  <i class="loader"></i>
                              </div>
                              <span class="help-block"></span>
                          </div>
                          <div class="col-sm-6 form-group">
                              <label>Locality<i class="required_star">*</i></label>
                              <input type="text" placeholder="Locality" name="locality" class="form-control" maxlength="50" value="{{@$user->CityLocalities->name}}" />
                              <span class="help-block"></span>
                          </div>
                          <div class="col-sm-6 form-group">
                              <label>Landmark<i class="required_star">*</i></label>
                              <input type="text" placeholder="Landmark" name="landmark" class="form-control" maxlength="44" value="{{@$user->CityLocalities->name}}" />
                              <span class="help-block"></span>
                          </div>
                          <div class="col-sm-12 form-group labelName" style="display: none;">
                              <label>Name<i class="required_star">*</i></label>
                              <input type="text" placeholder="Name" class="form-control" name="label_name" value="" />
                              <span class="help-block"></span>
                          </div>
                          <div class="col-sm-12 form-group">
                              <p><input id="label_type_1" type="radio" name="label_type" class="labelType" value="1" checked /><label for="label_type_1">Home</label></p>

                              <p><input id="label_type_2" type="radio" name="label_type" class="labelType" value="2" /><label for="label_type_2">Office</label></p>

                              <p><input id="label_type_3" type="radio" name="label_type" class="labelType" value="3" /><label for="label_type_3">Other</label></p>
                              <span class="help-block"></span>
                          </div>
                          <div class="reset-button">
                					   <button type="reset" class="btn btn-warning">Reset</button>
                					   <button type="submit" class="btn btn-success saveAddress">Save</button>
                					</div>
                        {!! Form::close() !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="patientChildModel" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/moment.min.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function(){

	jQuery(document).on("click", ".selecAll", function (e) {
		var ids = [];
		if(!this.checked) {
			$('.sub_chk').prop('checked', false);
      	$("#sendUserBulkSms").find("#ids").val('');
		}else{
		$('.sub_chk').prop('checked', true);
		$(".sub_chk").each(function(i){
			if(this.checked){
				ids.push(this.value)
			}
		});
    	$("#sendUserBulkSms").find("#ids").val(JSON.stringify(ids));
		}
	});
	$('.sub_chk').click(function(e) {
		var flag = 0;
		var ids = [];
		$(".sub_chk").each(function(i){
			if(this.checked){
				ids.push(this.value);
			}
			else{
				flag = 1;
			}
		});
		if(flag == 1){
			$('.selecAll').prop('checked', false);
		}
		else if(flag == 0) {
			$('.selecAll').prop('checked', true);
		}
		$("#sendUserBulkSms").find("#ids").val(JSON.stringify(ids));
		console.log(ids);
    });
	$(".fromStartDate").datepicker({
		  changeMonth: true,
		  changeYear: true,
		  dateFormat: 'yy-mm-dd',
		//minDate: new Date(),
		onSelect: function (selected) {
			var dt = new Date(selected);
			dt.setDate(dt.getDate());
			$(".toDOB").datepicker("option", "minDate", dt);
		}
	});
	jQuery('.fromStartDate_cal').click(function () {
		jQuery('.fromStartDate').datepicker('show');
	});

	$(".fromFollowupDate").datepicker({
		  changeMonth: true,
		  changeYear: true,
		  dateFormat: 'dd-mm-yy',
		  minDate: new Date(),
		  onSelect: function (selected) {
				var dt = new Date(selected);
				updateCallStatus($(this).attr("user_id"),selected);
			}
	});
	jQuery('.fromfollowup_cal').click(function () {
		jQuery('.fromFollowupDate').datepicker('show');
	});
});

$(".toStartDate").datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat: 'yy-mm-dd',
	//minDate: new Date(),
	onSelect: function (selected) {
		var dt = new Date(selected);
		dt.setDate(dt.getDate());
		$(".toDOB").datepicker("option", "minDate", dt);
	}
});
jQuery('.toStartDate_cal').click(function () {
	jQuery('.toStartDate').datepicker('show');
});
$(".searchDropDown").select2();
$('.selectDivCity').multiselect({
	includeSelectAllOption: true,
	enableFiltering: true,
	enableCaseInsensitiveFiltering: true,
});
jQuery(document).on("change", ".state_id", function (e) {
//jQuery('.state_id').on('change', function() {
  var cid = this.value;
  var $el = jQuery('.city_id');
  $el.empty();
  jQuery.ajax({
	  url: "{!! route('getCityList') !!}",
	  // type : "POST",
	  dataType : "JSON",
	  data:{'id':cid},
	  success: function(result){
	  jQuery(".panel-header").find("select[name='city_id']").html('<option value="">Select City</option>');
	  jQuery.each(result,function(index, element) {
		  $el.append(jQuery('<option>', {
			 value: element.id,
			 text : element.name
		  }));
	  });
  }}
  );
});
function chnagePagination(e) {
	$("#chnagePagination").submit();
}

$(document.body).on('click', '.sendMsgModal', function(){
	// if($("#sendUserBulkSms").find("#ids").val()){
	// 	$(".msg").val("");
	// 	$(".subject").val("");
	// 	$("#sendMsgModal").modal("show");
	// }
	// else alert("Please select patient form list..");
  $(".msg").val("");
  $(".subject").val("");
  $("#sendMsgModal").modal("show");
});

jQuery("#sendUserBulkSms").validate({
	rules: {
		msg: {
		  required: true,
		  minlength: 5,
		  maxlength: 255,
		},
		subject: {
		  required: true,
		  minlength: 2,
		  maxlength: 20,
		},
    smsFor: {
        required: function(element) {
            if ($('#ids').val() != "") {
                return false;
            } else {
                return true;
            }
        },
    },
		ids : "required",
		"smsType[]": "required",
	 },
	messages:{
	},
	errorPlacement: function(error, element){
		if (element.attr("name") == 'smsType[]') {
			$("#smsType").html(error);
		}
    else if (element.attr("name") == 'smsFor') {
      $(element).closest('.form-group').find('.help-block').append(error);
    }
		else {
			error.appendTo(element.parent().find('.help-block'));
		}
	},ignore: ":hidden",
	submitHandler: function(form) {
		$(form).find('.send').attr('disabled',true);
		jQuery('.loading-all').show();
		jQuery.ajax({
			type: "POST",
			dataType : "JSON",
			url: "{!! route('admin.sendUserBulkSms')!!}",
			data:  new FormData(form),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data) {
				 if(data==1)
				 {
				  jQuery('.loading-all').hide();
				  $(form).find('.send').attr('disabled',false);
					$.alert("Message Send Successfully..");
					//location.reload();
					$("#sendMsgModal").modal("hide");

				 }
				 else
				 {
				  jQuery('.loading-all').hide();
				  $(form).find('.send').attr('disabled',false);
				  $.alert("Oops Something Problem");
				 }
			},
  error: function(error)
  {
	  jQuery('.loading-all').hide();
	  $.alert("Oops Something goes Wrong.");
  }
		});
	}
});

$(document.body).on('change', '.userTypeFilter', function(){
   if($(this).val() == 2) {
	   $(".registeredFromSelect")[0].selectedIndex = 0;
	   $(".registeredFromDiv").show();
   }
   else{
	   $(".registeredFromSelect")[0].selectedIndex = 0;
	   $(".registeredFromDiv").hide();
   }
});
 jQuery(document).on("click", ".smsType", function () {
    if($(this).val() == '2'  && $(this).prop("checked") == true){
      $(".notificationDiv").show();
    }
    else {
      $(".notificationDiv").hide();
    }
  });

 jQuery(document).on("click", "#addPatientNote", function () {
   $('#patient_id').val('');
   $('#patient_note').val('');
   var id = $(this).attr('pId');
   var note = $(this).attr('pNote');
   $('#patient_id').val(id);
   $('#patient_note').val(note);
   $('#AddModal').modal('show');
 });
  $(document.body).on('click', '.submit', function(){
     jQuery("form[name='addNote']").validate({
      rules: {
         note: "required"
     },
    messages:{
    },
    errorPlacement: function(error, element){
      error.appendTo(element.parent().find('.help-block'));
    },ignore: ":hidden",
    submitHandler: function(form) {
      $(form).find('.submit').attr('disabled',true);
      jQuery('.loading-all').show();
      jQuery.ajax({
        type: "POST",
        dataType : "JSON",
        url: "{!! route('admin.addNote')!!}",
        data:  new FormData(form),
        contentType: false,
        cache: false,
        processData:false,
        success: function(data) {
           if(data==1)
           {
            jQuery('.loading-all').hide();
            $(form).find('.submit').attr('disabled',false);
            location.reload();
           }
           else
           {
            jQuery('.loading-all').hide();
            $(form).find('.submit').attr('disabled',false);
            $.alert("Oops Something Problem");
           }
        },
          error: function(error)
          {
            jQuery('.loading-all').hide();
            $.alert("Oops Something goes Wrong.");
          }
      });
       }
    });
  });
  function ForExcel() {
	  jQuery("#file_type").val("excel");
	  $("#chnagePagination").submit();
	  jQuery("#file_type").val("");
	}

jQuery(document).ready(function () {
  jQuery(".update_status").on('change',function(){
	    var user_id =  $(this).attr('user_id');
        var status =  $(this).val();
        var btn = this;
		if(status == "3"){
			$(btn).parent(".statusDiv").parent(".tbrow").find(".followUpDate").show();
		}
		else{
			jQuery('.loading-all').show();
			jQuery.ajax({
			  type: "POST",
			  url: "{!! route('admin.updateCallStatus')!!}",
			  data: {"_token":"{{ csrf_token() }}",'user_id':user_id,'status':status},
			  success: function(data){
				jQuery('.loading-all').hide();
				 if(data.status==1) {
				   jQuery(btn).hide();
				   $(btn).parent(".statusDiv").find(".doneBtn").show();
				   $(btn).parent(".statusDiv").parent(".tbrow").find(".callStsDate").text(data.stsDate);
				   $(btn).parent(".statusDiv").parent(".tbrow").find(".followUpDate").hide();

				 }
				 else if(data.status==2){
					 $.alert("Status change successfully..");
				 }
				 else{
					 $.alert("System Problem");
				 }
			   },
			   error: function(error){
				 jQuery('.loading-all').hide();
				 $.alert("Oops Something goes Wrong.");
			   }
			});
		}
  });
});

function updateCallStatus(user_id,followupDate) {
	jQuery('.loading-all').show();
	jQuery.ajax({
	  type: "POST",
	  url: "{!! route('admin.updateCallStatus')!!}",
	  data: {"_token":"{{ csrf_token() }}",'user_id':user_id,'status':'3','followupDate':followupDate},
	  success: function(data){
		jQuery('.loading-all').hide();
		 if(data.status==3) {
			$.alert("Follow up saved successfully..");
		 }
		 else{
			 $.alert("System Problem");
		 }
	   },
	   error: function(error){
		 jQuery('.loading-all').hide();
		 $.alert("Oops Something goes Wrong.");
	   }
	});
}
function loadSlot(type,date,doc_id) {
	jQuery('.loading-all').show();
	jQuery.ajax({
	  type: "POST",
	  url: "{!! route('showSlotAdmin')!!}",
	  data: {"_token":"{{ csrf_token() }}",'type':type,'doc_id':doc_id,'date':date},
	  success: function(data){
		jQuery('.loading-all').hide();
		 if(data) {
			 console.log(data);
			 $(".wApt").hide();
			 jQuery("form[name='createApptLnk']").find(".switchBtn").hide();
			 $(".paidApptBlock").hide();
			 $(".apptType").prop('checked',false);
			 jQuery("form[name='createApptLnk']").find('input[name="time"]').val('');
			 $("#switchDoctorModal").find(".slot-div").html(data);
		 }
		 else{
			 $.alert("System Problem");
		 }
	   },
	   error: function(error){
		 jQuery('.loading-all').hide();
		 $.alert("Oops Something goes Wrong.");
	   }
	});
}
$(document.body).on('click', '#addAppt', function(){
	jQuery("form[name='createApptLnk']").find("input[name='pId']").val($(this).attr('pid'));
	jQuery('.loading-all').show();
	setTimeout(function(){
	},500);
	setTimeout(function(){
		jQuery('.loading-all').hide();
		$("#createApptLnk")[0].reset()
		$("#switchDoctorModal").modal("show");
	},700);
});
$(document.body).on('click', '#makeMedOrder', function(){
  var pid = $(this).attr('pid');
  $('#userId').val(pid);
  jQuery('.loading-all').show();
    jQuery.ajax({
    type: "GET",
    dataType : "HTML",
    url: "{!! route('admin.makeMedOrder')!!}",
    data:{'id':pid},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#makeOrderModal").html('');
      jQuery("#makeOrderModal").html(data);
      jQuery('#makeOrderModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
});
$(document).ready(function() {
	$('#exampleSelectMultiple').multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
	});
	$(".chooseDoc").select2();
});
jQuery(".start_timeCal").datepicker({
dateFormat: 'yy-mm-dd',
changeMonth: true,
changeYear: true,
minDate: 0,
});
jQuery(document).on("click", ".start_timeCalIcon", function () {
  jQuery('.start_timeCal').datepicker('show');
});

jQuery(".end_timeCal").datepicker({
dateFormat: 'yy-mm-dd',
changeMonth: true,
changeYear: true

});
jQuery(document).on("click", ".end_timeCalIcon", function () {
  jQuery('.end_timeCal').datepicker('show');
});
jQuery(document).on("change", ".changestart_time", function (){
var apostart_time = $(this).val();
var practimeslot = jQuery("form[name='createApptLnk']").find("input[name='slot_duration']").val();
var updatedEndTime =  moment(apostart_time, "HH:mm").add(practimeslot, 'minutes');
var selectedvar = moment(updatedEndTime).format('HH:mm'); console.log(selectedvar);
$('.appDateSection').find('.session_time_down').val(selectedvar);
});

jQuery(document).on("change", ".startDate", function (){
var type = jQuery("form[name='createApptLnk']").find(".chooseType").val();
var doc_id = jQuery("form[name='createApptLnk']").find(".chooseDoc").val();
var date =  $(this).val();
loadSlot(type,date,doc_id);
// $('.appDateSection').find('.end_timeCal').val($(this).val());
});
$(document.body).on('change', '.chooseDoc', function() {
	var type = jQuery("form[name='createApptLnk']").find(".chooseType").val();
	var date =  jQuery("form[name='createApptLnk']").find("input[name='appstart_date']").val();
	var doc_id =  $(this).val();
	loadSlot(type,date,doc_id);
});

$(document.body).on('change', '.chooseType', function() {
	var doc_id = jQuery("form[name='createApptLnk']").find(".chooseDoc").val();
	var date =  jQuery("form[name='createApptLnk']").find("input[name='appstart_date']").val();
	var type =  $(this).val();
	loadSlot(type,date,doc_id);
});

jQuery(document).on("click", ".tab_class_time_slot", function () {
	$('.tab_class_time_slot').each(function() {
		$(this).closest("li").removeClass("active");
	});
	if($(this).attr('id')== '1' ) {
		$(this).closest("li").addClass("active");
		$("#docMorning_time_slot").addClass('in active');
		$("#docAfternoon_time_slot").removeClass('in active');
		$("#docEvening_time_slot").removeClass('in active');
	}
	else if($(this).attr('id')== '2' ) {
		$(this).closest("li").addClass("active");
		$("#docMorning_time_slot").removeClass('in active');
		$("#docEvening_time_slot").removeClass('in active');
		$("#docAfternoon_time_slot").addClass('in active');
	}
	else if($(this).attr('id')== '3' ) {
		$(this).closest("li").addClass("active");
		$("#docMorning_time_slot").removeClass('in active');
		$("#docAfternoon_time_slot").removeClass('in active');
		$("#docEvening_time_slot").addClass('in active');
	}
});

jQuery(document).on("click", ".chooseSlot", function (e) {
	$(".wApt").show();
	jQuery("form[name='createApptLnk']").find(".chooseSlot").each(function (e) {
		$(this).removeClass("selectSlot");
	});
	$(this).addClass("selectSlot");
	var slot_time = $(this).attr("slot");
	jQuery("form[name='createApptLnk']").find('input[name="time"]').val(slot_time);
});
jQuery("#createApptLnk").validate({
	rules: {
		doc_id: {
		  required: true,
		},appstart_date: {
		  required: true,
		},receivedBy: {
		  required: true,
		},apptBy: {
		  required: true,
		},tracking_id: {
		  required: true,
		}
	 },
	messages:{
	},
	errorPlacement: function(error, element){
		error.appendTo(element.parent().find('.help-block'));
	},ignore: ":hidden",
	submitHandler: function(form) {
		if(confirm('Are You sure?')) {
		jQuery('.loading-all').show();
		$(form).find('.send').attr('disabled',true);
		jQuery.ajax({
			type: "POST",
			dataType : "JSON",
			url: "{!! route('admin.createApptLnk')!!}",
			data:  new FormData(form),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data) {
				 if(data.status==1){
				  jQuery('.loading-all').hide();
					$(form).find('.send').attr('disabled',false);
					// alert(data.link+"\n Link Also send to user");
					$("#switchDoctorModal").modal("hide");
					 $.alert({
						title: 'Success!',
						content: 'Link Create Successfully. Please click button to copy link.',
						draggable: false,
						type: 'green',
						typeAnimated: true,
						buttons: {
							Copy: function(){
								copyText(data.link);
							},
							Cancel : function(){

							}
						}
					  });
				 }
				 else if(data.status==2){
				  jQuery('.loading-all').hide();
					$(form).find('.send').attr('disabled',false);
					$.alert("Appointment Create Successfully.");
					$("#switchDoctorModal").modal("hide");
				 }
				 else
				 {
				  jQuery('.loading-all').hide();
				  $(form).find('.send').attr('disabled',false);
				  $.alert("Oops Something Problem");
				 }
			},
			error: function(error){
				jQuery('.loading-all').hide();
				$.alert("Oops Something goes Wrong.");
			}
		});
	  }
	}
});
function copyToClipboard(text,btn) {
  var input = document.body.appendChild(document.createElement("input"));
  input.value = text;
  input.select();
  document.execCommand('copy');
  input.parentNode.removeChild(input);
  $(btn).text("copied");
  $(btn).prop("disabled",true);
}
function copyText(text) {
  var input = document.body.appendChild(document.createElement("input"));
  input.value = text;
  input.select();
  document.execCommand('copy');
  input.parentNode.removeChild(input);
}
jQuery(document).on("click", ".copyBtn", function () {
	 var input = document.body.appendChild(document.createElement("input"));
	  input.value = $(this).attr('txt');
	  input.select();
	  document.execCommand('copy');
	  input.parentNode.removeChild(input);
	  $(this).text("copied");
	  $(this).prop("disabled",true);
});
jQuery(document).on("click", "#addFreeAppt", function () {
	// var totAppt = $(this).attr("totAppt");
	if(confirm('Are you sure?') == true){
	jQuery('.loading-all').show();
	var pId = $(this).attr('pId');
	jQuery.ajax({
	  type: "POST",
	  url: "{!! route('admin.crtAppt')!!}",
	  data: {"_token":"{{ csrf_token() }}",'pId':pId},
	  success: function(data){
		jQuery('.loading-all').hide();
		 if(data==1) {
			$.alert("Appointment created successfully..");
		 }
	   },
	   error: function(error){
		 jQuery('.loading-all').hide();
		 $.alert("Oops Something goes Wrong.");
	   }
	});
	}
});
jQuery(document).on("click", ".apptType", function () {
	var appt = $(this).val();
	// $('.receivedBy option[value=""]').prop('selected','selected');
	$('.payment_mode_type option[value=""]').prop('selected','selected');
	if(appt == 1){
		$(".paidApptBlock").hide();
		jQuery("form[name='createApptLnk']").find(".switchBtn").show();
	}
	else if(appt == 2){
		$(".paidApptBlock").show();
		jQuery("form[name='createApptLnk']").find(".switchBtn").show();
	}
});
var showAppReq;
$(document.body).on('click', '.showLinks', function(){
	jQuery('.loading-all').show();
	var userId = $(this).attr("userId");
	if(showAppReq){
		showAppReq.abort();
	}
	showAppReq = jQuery.ajax({
		url: "{!! route('admin.loadLinks') !!}",
		type : "POST",
		dataType : "JSON",
		data:{'userId':userId},
		success: function(result) {
			$("#patApptModal").find(".upperTr").html('');
			var apptTr = '';
			if(result['links'].length > 0) {
				$.each(result['links'], function(key,value) {
					 key = key+1;
					 var paySts = 'PENDING';
					 var payCls = 'pen';
					 if(value.status == '1'){
						 paySts = 'DONE';
						 payCls = 'dne';
					 }
					 else if(value.status == '2'){
						 paySts = 'TXN FAILURE';
						 payCls = 'expp';
					 }
					 var apptDate = '';
					 var doc_name = '';
					 var totPay = '';
					 var crtDate = '';
					 var apptType = '';
					 if(value.meta_data){
						 meta_data = JSON.parse(value.meta_data);
						 apptDate = moment(meta_data.appointment_date+" "+meta_data.time).format('DD-MM-YYYY hh:mm A');
						 doc_name = "Dr. "+meta_data.doc_name;
						 if(meta_data.order_total){
							 totPay = "₹ "+meta_data.order_total+" /-";
						 }
						 else{
							totPay = "₹ "+meta_data.finalConsultaionFee+" /-";
						 }
						 if(meta_data.onCallStatus == "1"){
							 apptType = "Tele-Consult";
						 }
						 else{
							apptType = "In-Clinic";
						 }
					 }
           var linkType = "";
           if (value.type == '1') {
             linkType = "Appointment";
           }else if (value.type == '2') {
              linkType = "Medicine";
           }else if (value.type == '4') {
			   linkType = "Labs";
           }else if (value.type == '3') {
              linkType = "Subscription";
              apptType = "";
              doc_name = "";
              apptDate = "";
           }
					 crtDate = moment(value.created_at).format('DD-MM-YYYY hh:mm A');
					 cpyBtn = '<button title="Copy Payment LINK" class="copyBtn" txt="'+value.link+'">Copy</button>';
					 apptTr += '<tr><td>'+key+'.</td><td>'+linkType+'</td><td>'+value.order_id+'</td><td>'+apptDate+'</td><td>'+apptType+'</td><td>'+doc_name+'</td><td class="'+payCls+'"><strong>'+paySts+'</strong></td><td>'+totPay+'</td><td>'+cpyBtn+'</td><td>'+crtDate+'</td></tr>';
				});
				console.log(apptTr);
				$("#patApptModal").find(".upperTr").append(apptTr);
				jQuery('.loading-all').hide();
				$("#patApptModal").modal("show");
			}
			else{
				apptTr += '<tr><td colspan="8">No Record Found..</td></tr>';
				$("#patApptModal").find(".upperTr").append(apptTr);
				jQuery('.loading-all').hide();
				$("#patApptModal").modal("show");
			}
	  },
	  error: function(error) {
			jQuery('.loading-all').hide();
			if(error.status == 401 || error.status == 419){
			}
			else{
			}
		}
	});
});
jQuery(document).on("change", ".payment_mode_type", function () {
	var type = $(this).find('option:selected').val();
	$(".txnId").hide();
	if(type == '1') {
		$(".txnId").show();
	}
});
$(document.body).on('click', '.showChildUser', function(){
  var pid = $(this).attr('pid');
  jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.getAllChild')!!}",
    data:{'pid':pid},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#patientChildModel").html('');
      jQuery("#patientChildModel").html(data);
      jQuery('#patientChildModel').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
});
jQuery(document).on("click", ".createNewOrder", function () {
	var id = $(this).attr('id');
	var pname = $(this).attr('pname');
	var address = $(this).attr('address');
	var age = $(this).attr('age');
	var mobile_no=$(this).attr('mobile_no');
	var gender = $(this).attr('gender');
	var email=$(this).attr('email');
	var locality=$(this).attr('locality');
	var landmark=$(this).attr('landmark');
	var pincode=$(this).attr('pincode');


	// var url = '{!! url("/admin/createlaborder?id='+id+'&pname='+pname+'&age='+age+'&address='+address+'&gender='+gender+'&mobile_no='+mobile_no+'&email='+email+'") !!}';
	// window.open(url);window.location.href = '{{ url("/admin/make-lab-order",["id"=>'+id+',"pname"=>'+pname+']) }}';
	 window.location.href = '{!! url("/admin/make-lab-order?id='+id+'&pname='+pname+'&age='+age+'&address='+address+'&gender='+gender+'&mobile_no='+mobile_no+'&email='+email+'") !!}';

});
</script>
@endsection
