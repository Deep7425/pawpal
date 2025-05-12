@extends('layouts.admin.Masters.Master')
@section('title', 'Appointments Master')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	<?php $userModule = checkAdminUserModulePermission(50); ?>
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
                        <h1>Appointment</h1>
                        <small>Appointment list</small>
                        <ol class="breadcrumb hidden-xs">
                            <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                            <li class="active">Appointments</li>
                        </ol>
                    </div>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-sm-12">
						<?php $subscribedDocs = getSetting("specialist_doctor_user_ids");?>
                            <div class="panel panel-bd lobidrag">
                                <div class="panel-heading">
                                    <div class="left">
                                    <div class="btn-group">

                                    </div>
									<div class="btn-group">
                                        <a class="btn btn-success" href="javascript:void();">{{$appointments->total()}}</a>
                                    </div>
									<div class="btn-group">
										 <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
										<!--<a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForPDF()' title='PDF'><img src='{{ url("/img/pdf-icon.png") }}'/></a>-->
									</div>
									<div class="btn-group">
										<p><span class="fa fa-circle" style="color: #e89e44;" title="Next Day Appointment"></span></p>
                                    </div>
                                    </div>
                                    <div class="right">
                                    <div class="btnbtn-success123">
									@if(!isset($_GET['today_appt']))
										<div class="btn-group">
											<a class="btn btn-success" href="{{ route('admin.hgAppointments',['today_appt'=>base64_encode(1),'start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}">Past Appointments ({{getTotalAppointmentByDate()}})</a>
										</div>
									@else
										<div class="btn-group">
											<a class="btn btn-success" href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}">Back</a>
										</div>
									@endif
									</div>
                                    <div class="pagination-top">
                                            <div class="dataTables_length">
											<!-- <label>Page</label>-->
											{!! Form::open(array('route' => 'admin.hgAppointments', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
											 <input type="hidden" name="today_appt"  value="@if(isset($_GET['today_appt'])) {{base64_decode($_GET['today_appt'])}} @endif"/>
											 <input type="hidden" name="id"  value="@if(isset($_GET['id'])) {{base64_decode($_GET['id'])}} @endif"/>
											 <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>
												<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!-- <option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option> -->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
													<option value="300" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '300') selected @endif @endif>300</option>
													<option value="500" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '500') selected @endif @endif>500</option>
													<option value="1000" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '1000') selected @endif @endif>1000</option>
												</select>
                                            </div>
                                        </div>
                                        </div>
								     </div>
                                     <div class="panel-body">
                                    <div class="row">
                                     <div class="panel-header panel-headerTop123">

  										<div class="col-sm-2">
  											<div class="dataTables_length">
  												<label>Date Type</label>
  												<select class="form-control" name="date_type">
  													<option value="1" @if((app('request')->input('date_type'))!='') @if(base64_decode(app('request')->input('date_type')) == '1') selected @endif @endif >Created At</option>
  													<option value="2" @if((app('request')->input('date_type'))!='') @if(base64_decode(app('request')->input('date_type')) == '2') selected @endif @endif>Appointment</option>

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
												<label>Appointment</label>
												<select class="form-control" name="app_type">
													<option value="">All</option>
													<option value="2" @if((app('request')->input('app_type'))!='') @if(base64_decode(app('request')->input('app_type')) == '2') selected @endif @endif >Pending</option>
													<option value="3" @if((app('request')->input('app_type'))!='') @if(base64_decode(app('request')->input('app_type')) == '3') selected @endif @endif>Confirm</option>
													<option value="4" @if((app('request')->input('app_type'))!='') @if(base64_decode(app('request')->input('app_type')) == '4') selected @endif @endif>Cancel</option>
												</select>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="dataTables_length">
												<label>Type</label>
												<select class="form-control" name="type">
													<option value="">All</option>
													<option value="1" @if((app('request')->input('type'))!='') @if(base64_decode(app('request')->input('type')) == '1') selected @endif @endif >In-Clinic</option>
													<option value="2" @if((app('request')->input('type'))!='') @if(base64_decode(app('request')->input('type')) == '2') selected @endif @endif>Tele Consult</option>
													<option value="3" @if((app('request')->input('type'))!='') @if(base64_decode(app('request')->input('type')) == '3') selected @endif @endif>PLAN</option>
													<option value="4" @if((app('request')->input('type'))!='') @if(base64_decode(app('request')->input('type')) == '4') selected @endif @endif>Follow Up</option>
												</select>
											</div>
										</div>
                                        <div class="col-sm-2">
											<div class="dataTables_length">
												<label>Payment Status</label>
												<select class="form-control" name="pay_sts">
													<option value="">All</option>
													<option value="1" @if((app('request')->input('pay_sts'))!='') @if(base64_decode(app('request')->input('pay_sts')) == '1') selected @endif @endif >Paid</option>
													<option value="2" @if((app('request')->input('pay_sts'))!='') @if(base64_decode(app('request')->input('pay_sts')) == '2') selected @endif @endif>Free Direct</option>
													<option value="4" @if((app('request')->input('pay_sts'))!='') @if(base64_decode(app('request')->input('pay_sts')) == '4') selected @endif @endif>Free By Plan</option>
													<option value="3" @if((app('request')->input('pay_sts'))!='') @if(base64_decode(app('request')->input('pay_sts')) == '3') selected @endif @endif>Cash</option>
												</select>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="dataTables_length">
												<label>By Doctor</label>
												<select class="form-control" name="user_id" id="exampleSelectMultiple">
												<option value="">select Doctor</option>
													@if(count($practices) > 0)
														@foreach ($practices as $pra)
															<option value="{{$pra->user_id}}"
															 @if(old('user_id') == $pra->user_id) selected @endif >{{@$pra->first_name}} {{@$pra->last_name}} ({{$pra->email}}) @if(in_array($pra->user_id,$subscribedDocs))(PLAN)@endif</option>
														@endforeach
													@endif
												</select>
											</div>
										</div>


										<div class="col-sm-2">
											<div class="dataTables_length">
												<label>From</label>
												<select class="form-control" name="app_from">
													<option value="">All</option>
													<option value="1" @if((app('request')->input('app_from'))!='') @if(base64_decode(app('request')->input('app_from')) == '1') selected @endif @endif >Web</option>
													<option value="2" @if((app('request')->input('app_from'))!='') @if(base64_decode(app('request')->input('app_from')) == '2') selected @endif @endif>App</option>
													<option value="3" @if((app('request')->input('app_from'))!='') @if(base64_decode(app('request')->input('app_from')) == '3') selected @endif @endif>Paytm</option>
												</select>
											</div>
										</div>

										<div class="col-sm-2">
											<div class="dataTables_length">
												<label>Prescription</label>
												<select class="form-control" name="pres_type">
													<option value="">All</option>
													<option value="1" @if((app('request')->input('pres_type'))!='') @if(base64_decode(app('request')->input('pres_type')) == '1') selected @endif @endif >Done</option>
													<option value="0" @if((app('request')->input('pres_type'))!='') @if(base64_decode(app('request')->input('pres_type')) == '0') selected @endif @endif>Pending</option>
												</select>
											</div>
										</div>
										 <div class="col-sm-2">
										   <div class="dataTables_length">
											<label>Organization</label>
											 <select class="form-control" name="code">
												<option value="">Select</option>
												@if(count(getOrganizations())>0)
													@foreach(getOrganizations() as $raw)
													<option value="{{$raw->id}}" @if(isset($_GET['code'])) @if(base64_decode($_GET['code']) == $raw->id) selected @endif @endif>{{$raw->title}}</option>
													@endforeach
												@endif
											  </select>
											</div>
										  </div>
										  <div class="col-sm-2">
												<div class="dataTables_length">
												<label>Status</label>
												<select class="form-control" name="appintmentstatus">
														<option value="" >Select</option>
														<option value="1"  @if(base64_decode(app('request')->input('appintmentstatus')) == '1') selected @endif >Open</option>
			
														<option value="2"  @if(base64_decode(app('request')->input('appintmentstatus')) == '2') selected @endif >Closed</option>
													
												</select>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="dataTables_length">
												<label>Name & Mobile No.</label>
													<div class="input-group custom-search-form">
														<input name="search" type="text" class="form-control capitalizee" placeholder="search by name and mobile" value="@if(isset($_GET['search'])){{ base64_decode($_GET['search']) }}@endif"/>
													</div>
											  </div>
											</div>
											<div class="col-sm-1">
												<div class="dataTables_length">
													<label>Filter</label>
													<span class="input-group-btn">
													<button class="btn btn-primary form-control" type="submit">
													  <span class="glyphicon glyphicon-search"></span>
													</button>
													</span>
											   </div>
											</div>
										{!! Form::close() !!}
										</div>
                                   </div>
								   
                          <div class="table-responsive ptTbl ">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                         <th>S.No.</th>
										 <th>Appointment Time / order ID</th>
										 <th>Disease</th>
										 <th>Lab Test</th>
										 <th>Diagnostic Imaging</th>
										 <th>Doctor Name(Id)(Mobile)@if(Session::get('id') == 22)(A/c)@endif</th>
										 <th>Patient Name(Pid)(Mobile)<br>(Other Mobile)</th>
										 <th>Gender</br>Age</th>
										 <th>Type</th>
										 <th><span style="width:60px;">Doc Fee To Pay</span></th>
										 <th>Consultation Fee(Rs.)</th>
										 <th>Total Pay(Rs.)</th>
										 <th>Payment Status / Spec. Appt</th>
										 <th>From</th>
										 <th>Note</th>
										 <th>Documents Uploaded</th>
										 <th>Rating/Appointment Status</th>
										 <th>Created At</th> 
										 <th>Created By</th>
										 <th style="text-align:center;" title="Status">Status</th>
										 <th colspan="2" style="text-align:center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($appointments->count() > 0)
								@foreach($appointments as $index => $element)
									<?php $working_status =  json_decode($element->working_status);
										  $user_id = Session::get('userdata')->id;
										$is_next = 0;
										$specialist_appointment_cnt = 0;
										if(strtotime($element->start) > strtotime(date("Y-m-d 23:59:59"))) {
											$is_next = 1;
										}
										$appointmentIds = [];
										if(isset($element->AppointmentOrder->PlanPeriods) && count($element->AppointmentOrder->PlanPeriods)>0){
											$appointment_ids = "";
											foreach($element->AppointmentOrder->PlanPeriods as $val) {
												if($val->status == 1){
													$specialist_appointment_cnt = $val->specialist_appointment_cnt;
												}
												$appointment_ids .= $val->appointment_ids.",";
											}
											if(!empty($appointment_ids)) {
												$appointmentIds = explode(",",$appointment_ids);
											}
										}
									?>
									@if(!empty($element->AppointmentOrder) && !empty($element->AppointmentOrder->meta_data))
										@php $meta_data = json_decode($element->AppointmentOrder->meta_data);
											 $ldocN = "Dr. ".$meta_data->doc_name;
										@endphp
									@endif
                                    <tr class="tr-appointment" @if($is_next == '1') style="background-color: #e89e44;" @endif 
						@if(in_array($element->Patient->mobile_no,['9739977204','8826897971','9636257186','9769148411','9414430699']))
									style="background-color: #7fff3133;" @endif >
										<td>
											<label>{{$index+($appointments->currentpage()-1)*$appointments->perpage()+1}}.</label>
										</td>
										<td>{{date('d-m-Y h:i A',strtotime($element->start))}}
										@if(!empty($element->NotifyUserSms) && $element->NotifyUserSms->status == '0')</br>
										<div class="fa-video-camera123 blink">
										<i class="fa fa-video-camera" aria-hidden="true" title="video calling.."></i>
										</div>
										@elseif(!empty($element->NotifyUserSms) && $element->NotifyUserSms->status == '1')</br>
											<div class="fa-video-camera123">
											<i class="fa fa-video-camera" aria-hidden="true" title="video calling.."></i>
											</div>
										@endif
										<br>{{@$element->AppointmentOrder->id}}
										</td>
										<td>{{getChiefComplaints($element->chiefComplaints)}}</td>
										<td>
										   @if(@$element->PatientLabsOne->appointment_id) 
											<strong style="text-align:left; float:left;">Yes </strong> 
											@else 
											No 
										   @endif
										</td>
										<td>		
											@if(@$element->PatientDiagnosticImagings->appointment_id) 
											<strong style="text-align:left; float:left;">Yes </strong> 
											@else 
											No 
											@endif
										</td>
										<td>Dr. {{@$element->User->DoctorInfo->first_name}} {{@$element->User->DoctorInfo->last_name}}   ({{@$element->User->id}})  ({{@$element->User->DoctorInfo->mobile}}) @if(!empty($element->User->DoctorInfo->acc_no) && Session::get('id') == 22)({{replacewithStar(@$element->User->DoctorInfo->acc_no,4)}})@endif</td>
										<td class="showApptDtls" data-pid="{{base64_encode($element->Patient->id)}}" data-mobile="{{base64_encode($element->Patient->mobile_no)}}">{{@$element->Patient->first_name}} {{@$element->Patient->last_name}}  ({{@$element->Patient->id}})  ({{$element->Patient->mobile_no}}) @if(!empty($element->Patient->other_mobile_no))<br>({{$element->Patient->other_mobile_no}})@endif</td>
										<td>{{$element->Patient->gender}} @if(!empty($element->Patient->dob)) </br> <span>{{get_patient_age($element->Patient->dob)}}</span>@endif</td>
										<td>@if($element->type == '3') Tele Consult @else In Clinic @endif</td>
										<td><span style="width:60px;">
											<?php 
												$docFeeToPay=0;
												if(!empty($appointmentOrder) && @$appointmentOrder->type == "0"){
													$docFeeToPay= @$element->Doctors->DoctorData->plan_consult_fee;
												}elseif(!empty($appointmentOrder) && @$appointmentOrder->type != "0" && $element->type == '3'  && @$meta_data->isDirectAppt == '0'){
													$docFeeToPay= number_format(@$appointmentOrder->order_subtotal,2);
												}elseif(!empty($appointmentOrder) && @$appointmentOrder->type != "0" && $element->type != '3')
												{
													$docFeeToPay= 0;
												}
												else{
													$docFeeToPay= @number_format(@$element->Doctors->DoctorData->plan_consult_fee,2);
												}
											?>	
											{{$docFeeToPay}}
											</span>
				                        </td>
										<td>@if(!empty($element->AppointmentOrder)){{@$element->AppointmentOrder->order_subtotal}}@else {{number_format($element->consultation_fees,2)}}@endif</td>
										<td>@if(!empty($element->AppointmentOrder)){{@$element->AppointmentOrder->order_total}}@else {{number_format($element->consultation_fees,2)}}@endif</td>
										<td>@if((!empty($element->AppointmentTxn) && @$element->AppointmentOrder->type == "1") || empty($element->AppointmentOrder)) Paid @elseif(@$element->AppointmentOrder->type == "0") Free @elseif(@$element->AppointmentOrder->type == "2") Cash @endif
										@if(checkAppointmentIsElite($element->id,@$element->AppointmentOrder->order_by) == 1) 
										@if(in_array($element->id,$appointmentIds)) )<span style="color: #ef6666; font-weight: bold;">(PLAN)</span> @endif
										<p title="Remaining Specialist Appointment">{{$specialist_appointment_cnt}}</p>
										@endif
										@if($element->visit_type == '6')
											<span style="color: #ef6666; font-weight: bold;">(Follow Up)</span>
										@endif
										</td>
                                  

										<td>
										@if(isset($element->AppointmentOrder))
											@if($element->AppointmentOrder->order_from == '1') APP
											@elseif($element->AppointmentOrder->order_from == '0')
											WEB @elseif($element->AppointmentOrder->order_from == '2') Admin
											@endif
											@if(isset($meta_data->isPaytmTab) && $meta_data->isPaytmTab == "true")
												(Paytm)
											@elseif(isset($meta_data->organization))
												{{$meta_data->organization}}
											@endif
										@else
											@if($element->app_click_status == '5') APP @elseif($element->app_click_status == '6') WEB @endif
										@endif
										@if(isset($element->AppointmentOrder) && $element->AppointmentOrder->hg_miniApp == 1)
										(Help India)
										@elseif(isset($element->AppointmentOrder) && $element->AppointmentOrder->hg_miniApp == 2)
										(DOIT Emitra)
										@endif
										</td>
										<td>{{$element->note}}</td>
										<td>{{$element->is_document_uploaded == 1 ? 'Done' : 'Pending'}}</td>
										
										<td class="ratingDiv">
											<div class="ratingDivTop">
											@if(isset($element->AppointmentOrder))
												@if(empty($element->AppointmentOrder->rating))
												<select class="update_rating" appId="{{base64_encode($element->id)}}">
													<option value="">Select</option>
													<option @if($element->AppointmentOrder->rating == '1') selected @endif value="1">1 STAR</option>
													<option @if($element->AppointmentOrder->rating == '2') selected @endif value="2">2 STAR</option>
													<option @if($element->AppointmentOrder->rating == '3') selected @endif value="3">3 STAR</option>
													<option @if($element->AppointmentOrder->rating == '4') selected @endif value="4">4 STAR</option>
													<option @if($element->AppointmentOrder->rating == '5') selected @endif value="5">5 STAR</option>
												</select>
												<p class="text-success starDiv" style="display:none;">{{$element->AppointmentOrder->rating}} STAR</p>
												@else
												<p class="text-success starDiv">{{$element->AppointmentOrder->rating}} STAR</p>
												@endif
											@endif
											@if(!$working_status)
											<select class="update_appoint" apptId="{{$element->id}}">
												<option @if($working_status == NULL)  @endif value="1">Open</option>
												<option @if($working_status && $working_status->status == '2')  @endif value="2">Close</option>
											</select>
											<span class="cancel-btn label-default label label-success show_working_status" style="display:none;">{{"Closed"}}</span> 
											@else
												<span class="cancel-btn label-default label label-success show_working_status">{{"Closed"}}</span>
											@endif
										  </div>
										<br>
										</td>

										

										<td>@if(isset($element->AppointmentOrder) && !empty($element->AppointmentOrder)) {{date('d-m-Y h:i A',strtotime($element->AppointmentOrder->created_at))}} @else {{date('d-m-Y h:i A',strtotime($element->created_at))}} @endif</td>
									
									<!-- Created By -->
										<td>{{@$element->admin->name}}</td>

										<td>
										<span class="cancel-btn label-default label @if($element->status != '1') label-danger @elseif($element->appointment_confirmation == '1') label-success @else label-warning @endif">@if($element->status != '1') Cancelled @elseif($element->appointment_confirmation == '1') Confirmed @else Pending @endif</span>
										@if($element->status == '0')
											@if($element->cancel_reason ==  "other") <span class="text-success">{{$element->other_cancel_reason}}</span>
											@else <span class="text-success">{{$element->cancel_reason}}</span>
											@endif
										@endif
										@if(!empty($working_status) && $working_status->status == 1)
										<span class="label-default label label-success">Working...</span>
										@endif
										@if($element->visit_status == '1')
											<span class="text-success">Done</span>
										@else
										    <span style="color: red">Pending</span>
										@endif
										</td>
										<td>
											<div class="viewSubscription123 pptHere">
												<ul>
													<li>
														<a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
															<ul>
																<?php
																$to_time = strtotime($element->start);
																$from_time = strtotime($element->end);
																$slot_duration = round(abs($to_time - $from_time) / 60,2);
																 ?>

																<li><span slot_duration="{{$slot_duration}}" app_id="{{base64_encode($element->id)}}" pId="{{$element->pId}}" user_id="{{$user_id}}" start_time="{{date('Y-m-d',strtotime($element->start))}}" end_time="{{date('Y-m-d',strtotime($element->end))}}" s_time="{{date('H:i',strtotime($element->start))}}" e_time="{{date('H:i',strtotime($element->end))}}" doc_id="{{$element->doc_id}}" app_type="@if($element->type == 3){{base64_encode(1)}}@else{{base64_encode(2)}}@endif" visit_type="{{$element->visit_type}}" class="label-default label label-info switchOrder"><i class="fa fa-exchange" aria-hidden="true"></i> Switch</span></li>
																@if(isset($element->AppointmentOrder) && $element->AppointmentOrder->switch_apt == '1')
																	<li class="" title="Switched From {{@$ldocN}}"><span>Switched From<br/>{{@$ldocN}}</span></li>
																@endif
																<li><span class="label-default label label-info showApptDtls" data-pid="{{base64_encode($element->Patient->id)}}" data-mobile="{{base64_encode($element->Patient->mobile_no)}}"><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Show All Appointment</span></li>
											                    @if($element->type != '3')
																<li><span app_id="{{base64_encode($element->id)}}" user_id="{{$user_id}}" working-user-id="@if(!empty($working_status)){{$working_status->user_id}}@endif" class="label-default label label-info  confirm_by_doc"><i class="fa fa-check-square-o" aria-hidden="true"></i> Confirm</span></li>
																@endif
																<li><span app_id="{{base64_encode($element->id)}}" user_id="{{$user_id}}" working-user-id="@if(!empty($working_status)){{$working_status->user_id}}@endif" class="label-default label label-info cancel_by_team "><i class="fa fa-ban" aria-hidden="true"></i> Cancel</span></li>
																<li><span app_id="{{base64_encode($element->id)}}" user_id="{{$user_id}}" working-user-id="@if(!empty($working_status)){{$working_status->user_id}}@endif" status="@if(!empty($working_status)){{$working_status->status}}@endif" class="label-default label @if(!empty($working_status) && $working_status->status == 1) label-danger @elseif(!empty($working_status) && $working_status->status == 2) label-success @else label-primary @endif   @if(empty($working_status) || $working_status->status != 2) start_for_work @endif " title="Support Team work manage by Individual">@if(!empty($working_status) && $working_status->status == 1)<i class="fa fa-stop-circle-o" aria-hidden="true"></i> Stop @elseif(!empty($working_status) && $working_status->status == 2)<i class="fa fa-times-circle-o" aria-hidden="true"></i> Closed @else <i class="fa fa-play-circle" aria-hidden="true"></i> Start @endif</span></li>
																<li><a href="javascript:void(0);" id="addApptNote" apptId="{{base64_encode($element->id)}}" pNote="{{$element->note}}" title="Add Note"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Add Note</a></li>
																<li><button class="SMsBtn" onclick="smsView({{$element}},'{{date('d-m-Y',strtotime($element->start))}}','{{date('h:i A',strtotime($element->start))}}')"><i class="fa fa-commenting-o" aria-hidden="true"></i>SMS</button></li>
																@if($element->visit_status == '1')
																<li><button class="btn btn-info sendPres" appId="{{$element->id}}" title="Share Prescription via whatsApp"><i class="fa fa-share-alt" aria-hidden="true"></i> Share Prescription </button></li>
																@endif
																@if($element->visit_status == '1')
																<li><button class="btn btn-info sendPresToPharmacy" appId="{{$element->id}}" title="Share Prescription To Pharmacy"><i class="fa fa-share-alt" aria-hidden="true"></i>Pres Share To Pharmacy</button></li>
																@endif
																@if($userModule && $element->visit_status == '1')
																<li>
																<li><button class="btn btn-info showPrescription" appId="{{$element->id}}" title="Show Prescription"><i class="fa fa-share-alt" aria-hidden="true"></i>Show Prescription</button></li>
																</li>
																@endif
																<!--<li><button class="btn btn-info RefundOrder" app_id="{{base64_encode($element->id)}}" user_id="{{$user_id}}"><i class="fa fa-undo"></i>Refund Amount</button></li>-->
														</ul>
														</li>
												</ul>
											</div>

										</td>

									</tr>
								@endforeach
								@else
									<tr><td colspan="15">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
			<div class="page-nation text-right">
				<ul class="pagination pagination-large">
					{{ $appointments->appends($_GET)->links() }}
				</ul>
			</div>
			</div>
		</div>
		</div>
	</div>
</section> <!-- /.content -->
</div> <!-- /.content-wrapper -->


<div class="modal md-effect-1 md-show" id="switchDoctorModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Switch Appointment</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<!--<div class="panel-heading">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.hgAppointments') }}"> <i class="fa fa-list"></i>Appointments List</a>
					</div>
				</div>-->
				<div class="panel-body SwitchAppointment">
					{!! Form::open(array('id' => 'switchAppointment','name'=>'switchAppointment')) !!}
					<input name="app_type" value="" type="hidden"/>
					<input name="app_id" value="" type="hidden"/>
					<input name="user_id" value="" type="hidden"/>
					<input name="pId" value="" type="hidden"/>
					<input name="slot_duration" value="" type="hidden"/>
					<input name="time" value="" type="hidden"/>
					<input name="visit_type" value="" type="hidden"/>
                    <div class="form-groupTopMenu">
					<div class="form-group">
						<label>Doctors</label>
						<select class="form-control chooseDoc" name="doc_id">
							<option value="">Select Doctor</option>
							@if(count($practices) > 0)
								@foreach($practices as $pra)
									@if(!empty($pra->oncall_fee) || !empty($pra->consultation_fees))
									<option value="{{$pra->user_id}}">{{@$pra->first_name}} {{@$pra->last_name}} ({{@$pra->email}})
									@if(!empty($pra->consultation_fees))({{@$pra->consultation_fees}})@endif @if(!empty($pra->oncall_fee))({{@$pra->oncall_fee}}) @endif
									@if(in_array($pra->user_id,$subscribedDocs))(PLAN)@endif</option>
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
						  <input type="text" class="form-control start_timeCal startDate" name="appstart_date"  required readonly />
						  <span class="input-group-addon start_timeCalIcon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						  <!--<input type="hidden" name="start"  id="start" >-->
						  </div>
						  <!--<div class="timeDiv">
						  <?php
						  // $starttime = '00:00';
						  // $endtime = '24:00';
						  // $start_time    = strtotime($starttime);
						  // $end_time      = strtotime($endtime);
						  // $practiceSlotVal = 10;
						  // $increment = $practiceSlotVal * 60;
						  ?>
							<div class='input-group date billTimeOnTop' id='datetimepicker3'>
							 <input type="time" class="form-control changestart_time" pattern="^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$" name="appstart_time" required />
             </div>
						  </div>-->
						  </div>
						  <!--<div class="appointment-popup-block appDate">
						  <label>End Time</label>
						  <div class="dateDiv">
						  <input type="text" class="form-control end_timeCal" name="append_date"  required readonly />
						  <span class="input-group-addon end_timeCalIcon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						  </div>
						  <div class="timeDiv">
						  <div class="input-group date billTimeOnTop">
						  <input type="time" class="form-control session_time_down" name="append_time" pattern="^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$"  required />
						  </div>
						  </div>
					  </div>-->
					</div>

					</div>
					<div class="slot-div"></div>
					<div class="reset-button">
					   <button type="submit" class="btn btn-success switchBtn" style="display:none;">Switch</button>
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
			<h4 class="modal-title">Note</h4>
		</div>
		<div class="modal-body">
		{!! Form::open(array('id' => 'addNote','name'=>'addNote')) !!}
		<input type="hidden" name="note_type" value="2">
		<input type="hidden" name="id" id="appt_id" value="">
		<div class="form-group">
		  <label>Note:</label>
		  <textarea type="text" name="note" rows="5" class="form-control" id="appt_note" placeholder="Write Note..."></textarea>
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
<div class="modal md-effect-1 md-show" id="patApptModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">All Appointments</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-body">
					<div class="RemainingAppointment">Remaining Appointment : <span class="totAppt">0</span></div>
					<table class="table">
						<thead>
							<tr>
								<td>S.No.</td>
								<td>Time</td>
								<td>Doctor Name</td>
								<td>Patient</td>
								<td>Type</td>
								<td>Payment Status</td>
								<td>Total Pay (Rs.)</td>
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
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/moment.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#exampleSelectMultiple').multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
	});
	$(".chooseDoc").select2();
});
function chnagePagination(e) {
	$("#chnagePagination").submit();
}

function smsView(data,date,time) {
	console.log(data);
	var patname= data.patient.first_name+' '+data.patient.last_name;
	var doc = "Dr. "+data.user.doctor_info.first_name.charAt(0).toUpperCase() + data.user.doctor_info.first_name.slice(1)+" "+data.user.doctor_info.last_name;

	var type = 'tele consultaion';
    if(data.type != 3){
    	type = 'In Clinic';
    }
	var smsText = 'This patient('+patname+') of '+type+' appointment with '+doc+' on '+date+' at '+time+'. Doctor Mobile : '+data.user.doctor_info.mobile+'. Patient Mobile : '+data.patient.mobile_no;
    alert(smsText);
	copyText(smsText);
}
jQuery(document).ready(function(){
	setTimeout(location.reload.bind(location), 120000);
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

	jQuery(document).on("click", ".confirm_by_doc", function (e) {
    var current_working_user = $(this).attr('working-user-id');
      var login_user = $(this).attr('user_id');
     if (current_working_user != "" && login_user != current_working_user) {
        alert('Someone working on this appointment');
        return false;
      }
		if(confirm('Are You sure want to confirm this appointment.')) {
			var app_id = $(this).attr('app_id');
			var confirm_btn = this;
			$('.loading-all').show();
			jQuery.ajax({
				url: "{!! route('appointmentConfirm') !!}",
				type : "POST",
				dataType : "JSON",
				data:{'id':app_id},
				success: function(result) {
				// jQuery("#addDoctor").find("select[name='city_id']").html('<option value="">Select City</option>');
				if(result == 1) {
				  jQuery('.loading-all').hide();
				  $(confirm_btn).closest('.tr-appointment').find(".cancel-btn").addClass('label-success');
				  $(confirm_btn).closest('.tr-appointment').find(".cancel-btn").removeClass('label-warning');
				  $(confirm_btn).closest('.tr-appointment').find(".cancel-btn").html('Confirmed');
				  // document.location.href='{!! route("admin.nonHgDoctorsList")!!}';
				}
				else {
				  jQuery('.loading-all').hide();
				  alert("Oops Something Problem");
				}
			  },
			  error: function(error){
					jQuery('.loading-all').hide();
					if(error.status == 401 || error.status == 419){
						//alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						//alert("Oops Something goes Wrong.");
					}
				}
			});
		}
	});

	jQuery(document).on("click", ".cancel_by_team", function (e) {
    var current_working_user = $(this).attr('working-user-id');
      var login_user = $(this).attr('user_id');
      if (current_working_user != "" && login_user != current_working_user) {
        alert('Someone working on this appointment');
        return false;
      }
		if(confirm('Are You sure want to cancel this appointment.')) {
			var app_id = $(this).attr('app_id');
			var user_id = $(this).attr('user_id');
			var can_btn = this;

			$('.loading-all').show();
			jQuery.ajax({
				url: "{!! route('appointmentCancel') !!}",
				type : "POST",
				dataType : "JSON",
				data:{'id':app_id},
				success: function(result) {
				if(result == 1) {
					jQuery('.loading-all').hide();
					$(can_btn).closest('.tr-appointment').find(".cancel-btn").removeClass('label-success');
					$(can_btn).closest('.tr-appointment').find(".cancel-btn").addClass('label-danger');
					$(can_btn).closest('.tr-appointment').find(".cancel-btn").html('Cancelled');
				}
				else {
				  jQuery('.loading-all').hide();
				  alert("Oops Something Problem");
				}
			  },
			  error: function(error){
					jQuery('.loading-all').hide();
					if(error.status == 401 || error.status == 419){
						//alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						//alert("Oops Something goes Wrong.");
					}
				}
			});
		}
	});

  jQuery(document).on("click", ".start_for_work", function (e) {
    var current_working_user = $(this).attr('working-user-id');
      var login_user = $(this).attr('user_id');
	  if (current_working_user != "" && login_user != current_working_user) {
        alert('Someone working on this appointment');
        return false;
      }
    var current_status = $(this).attr('status');
    if (current_status == "") {
      var msg = "Are You sure want to Start Working this appointment";
    }
    else if (current_status == 1) {
      var msg = "Are You sure want to Stop Working this appointment";
    }

		//if(confirm(msg)) {
			var app_id = $(this).attr('app_id');

			var current = this;

			$('.loading-all').show();
			jQuery.ajax({
				url: "{!! route('admin.ChangeWorkingStatus') !!}",
				type : "POST",
				dataType : "JSON",
				data:{'id':app_id,'status':current_status},
				success: function(result) {
				if(result == 1) {
					jQuery('.loading-all').hide();
					  if (current_status == '') {
						$(current).closest('.tr-appointment').find(".start_for_work").removeClass('label-primary');
								$(current).closest('.tr-appointment').find(".start_for_work").addClass('label-danger');
								$(current).closest('.tr-appointment').find(".start_for_work").html('Stop');
					  }
					  else if (current_status == 1) {
						$(current).closest('.tr-appointment').find(".start_for_work").removeClass('label-danger');
						$(current).closest('.tr-appointment').find(".start_for_work").removeClass('start_for_work');
								$(current).closest('.tr-appointment').find(".start_for_work").addClass('label-success');
								$(current).closest('.tr-appointment').find(".start_for_work").html('Closed');
					  }
					  location.reload();

				}
				else {
				  jQuery('.loading-all').hide();
				  alert("Oops Something Problem");
				}
			  },
			  error: function(error){
					jQuery('.loading-all').hide();
					if(error.status == 401 || error.status == 419){
						//alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						//alert("Oops Something goes Wrong.");
					}
				}
			});
		// }
	});

});
function ForExcel() {
	jQuery("#file_type").val("excel");
	$("#chnagePagination").submit();
	jQuery("#file_type").val("");
}
function ForPDF() {
	jQuery("#file_type").val("pdf");
	$("#chnagePagination").submit();
	jQuery("#file_type").val("");
}
jQuery(document).on("click", ".RefundOrder", function (e) {
	 $.confirm({
            title: 'Refund!',
            draggable: false,
            content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Enter Refund Reason</label>' +
                '<textarea placeholder="Write Refund Reason..." class="refund_reason form-control" maxlength="200" name="refund_reason" required> </textarea>' +
                '<span  class="FieldError" style="display:none; color:red;"></span>' +
                '</div>' +
                '</form>',
            buttons: {
                formSubmit: {
                    text: 'Submit',
                    btnClass: 'btn-blue',
                    action: function(){
                        var cancel_reason = this.$content.find('.refund_reason').val();
                        if(!cancel_reason){
                          $('.FieldError').text('Field is Required');
                          $('.FieldError').show();
                            return false;
                        }
                        else if (cancel_reason.length > 200) {
                          $('.FieldError').text('Text exceeded 200 characters');
                          $('.FieldError').show();
                          return false;
                        }

                        RefundOrder(123, cancel_reason);
                    }
                },
                cancel: function(){
                    //close
                },
            },
            onContentReady: function(){
                // you can bind to the form
                var jc = this;
                // this.$content.find('.refund_reason').css('color','red');
                this.$content.find('.refund_reason').on('keyup', function(e){
                	// var current = this.$content.find('.refund_reason');

                	if ($(this).val().length != "") {
				        $('.FieldError').hide();
				      }
				      else {
				        $('.FieldError').show();
				      }

				      if ($(this).val().length == 200) {
				        $('.FieldError').text('Text exceeded 200 characters');

				        setTimeout(function(){ $('.FieldError').hide(); }, 3000);
				      }
				      else {
				        $(this).val($(this).val().substr(0, 200));
				        $('.FieldError').hide();

				      }
                });

                // this.$content.find('form').on('submit', function(e){ // if the user submits the form by pressing enter in the field.
                //     e.preventDefault();
                //     jc.$$formSubmit.trigger('click'); // reference the button and click it
                // });
            }
        });
});

function RefundOrder(orderId, cancel_reason) {
  jQuery('.loading-all').show();
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('admin.RefundOrder') !!}",
  data: {'orderId':orderId, 'cancel_reason':cancel_reason},
  success: function(data){
    // jQuery('.loading-all').hide();
    //   if(data.status == '0') {
				// 	jQuery('.loading-all').hide();
				// 	console.log(data.output.RESPONSE);
				// }
    //     if(data.status == '1') {
    //       $.alert({
    //         title: 'Order Cancelled Successfully !',
    //         content: 'Your Order has been cancelled',
    //         draggable: false,
    //         type: 'green',
    //         typeAnimated: true,
    //         buttons: {
    //             ok: function(){
    //             // location.reload();
				// window.location = "{{ route('labOrders') }}";
    //             },
    //         }
    //       });
    //     }
    //     else {
    //       $.alert({
    //         title: 'oops !',
    //         content: 'Order has not  been Cancelled ! <br> please contact Health Gennie customer care',
    //         draggable: false,
    //         type: 'red',
    //         typeAnimated: true,
    //         buttons: {
    //             ok: function(){
    //             location.reload();
    //             },
    //         }
    //       });
    //     }
    },
    error: function(error)
    {
      if(error.status == 401)
      {
          alert("Session Expired,Please logged in..");
          location.reload();
      }
      else
      {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
        jQuery('#saveAddress').attr('disabled',false);
      }
    }
  });
}
function loadSlot(type,date,doc_id,visit_type,app_id,pId) {
	jQuery('.loading-all').show();
	jQuery.ajax({
	  type: "POST",
	  url: "{!! route('showSlotAdmin')!!}",
	  data: {"_token":"{{ csrf_token() }}",'type':type,'doc_id':doc_id,'date':date,'visit_type':visit_type,'app_id':app_id,'pId':pId},
	  success: function(data){
		jQuery('.loading-all').hide();
		 if(data) {
			 console.log(data);
			 $("#switchDoctorModal").find(".slot-div").html(data);
			 $(".conFee").hide();
		 }
		 else{
			 alert("System Problem");
		 }
	   },
	   error: function(error){
		 jQuery('.loading-all').hide();
		 alert("Oops Something goes Wrong.");
	   }
	});
}
$(document).ready(function() {
$(document.body).on('change', '.chooseDoc', function() {
	var type = jQuery("form[name='switchAppointment']").find("input[name='app_type']").val();
	var date =  jQuery("form[name='switchAppointment']").find("input[name='appstart_date']").val();
	var visit_type =  jQuery("form[name='switchAppointment']").find("input[name='visit_type']").val();
	var app_id =  jQuery("form[name='switchAppointment']").find("input[name='app_id']").val();
	var pId =  jQuery("form[name='switchAppointment']").find("input[name='pId']").val();
	var doc_id =  $(this).val();
	loadSlot(type,date,doc_id,visit_type,app_id,pId);
});

$(document.body).on('click', '.switchOrder', function(){
	jQuery('.loading-all').show();
	jQuery("form[name='switchAppointment']").find("input[name='app_id']").val($(this).attr("app_id"));
	jQuery("form[name='switchAppointment']").find("input[name='user_id']").val($(this).attr("user_id"));
	jQuery("form[name='switchAppointment']").find("input[name='pId']").val($(this).attr("pId"));
	jQuery("form[name='switchAppointment']").find("input[name='slot_duration']").val($(this).attr("slot_duration"));
	jQuery("form[name='switchAppointment']").find("input[name='app_type']").val($(this).attr("app_type").trim());
	jQuery("form[name='switchAppointment']").find(".start_timeCal").val($(this).attr("start_time"));
	jQuery("form[name='switchAppointment']").find(".end_timeCal").val($(this).attr("end_time"));
	jQuery("form[name='switchAppointment']").find("input[name='visit_type']").val($(this).attr("visit_type"));
	// alert($(this).attr("doc_id"));
	// jQuery("form[name='switchAppointment']").find(".chooseDoc option[value="+$(this).attr("doc_id")+"]").prop("selected",true);
		var value = $(this).attr("doc_id");
		$('.chooseDoc').val(value);
		$('.chooseDoc').select2().trigger('change');

	var increment = $(this).attr("slot_duration") * 60;
	var result = range(0, (86400-increment),increment);
	var row = '';
	$.each(result, function( key, value ) {
		var time = moment.unix(value).utc().format('hh:mm A');
		var tvalue = moment.unix(value).utc().format('HH:mm');
		row += '<option value="'+tvalue+'" >'+time+'</option>';
	});
	$('.appDateSection').find('.session_time_down').empty();
	$('.appDateSection').find('.session_time_up').empty();
	$('.appDateSection').find('.session_time_up').html(row);
	$('.appDateSection').find('.session_time_down').html(row);
	jQuery("form[name='switchAppointment']").find("input[name='appstart_time']").val($(this).attr("s_time"));
	jQuery("form[name='switchAppointment']").find("input[name='append_time']").val($(this).attr("e_time"));
	// alert($(this).attr("s_time"));
	setTimeout(function(){
		// $('.appDateSection').find(".session_time_down option[value='"+$(this).attr("e_time")+"']").attr("selected","selected");
	},500);

	setTimeout(function(){
		jQuery('.loading-all').hide();
		$("#switchDoctorModal").modal("show");
	},600);

});
function range(start, end, step = 1) {
  const len = Math.floor((end - start) / step) + 1
  return Array(len).fill().map((_, idx) => start + (idx * step))
}
});
jQuery("#switchAppointment").validate({
	rules: {
		doc_id: {
		  required: true,
		},appstart_date: {
		  required: true,
		},appstart_time: {
		  required: true,
		},append_date: {
		  required: true,
		},append_time: {
		  required: true,
		}
	 },
	messages:{
	},
	errorPlacement: function(error, element){
		error.appendTo(element.parent().find('.help-block'));
	},ignore: ":hidden",
	submitHandler: function(form) {
		if(confirm('Are You sure want to switch this appointment.')) {
		jQuery('.loading-all').show();
		$(form).find('.send').attr('disabled',true);
		jQuery.ajax({
			type: "POST",
			dataType : "JSON",
			url: "{!! route('admin.switchAppointment')!!}",
			data:  new FormData(form),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data) {
				 if(data==1){
				  jQuery('.loading-all').hide();
					$(form).find('.send').attr('disabled',false);
					alert("Appointment switched successfully..");
					location.reload();
					$("#switchDoctorModal").modal("hide");
				 }
				 else if(data == 3){
					  jQuery('.loading-all').hide();
					$(form).find('.send').attr('disabled',false);
					alert("Please choose another doctor");
				 }
				 else if(data == 4){
					  jQuery('.loading-all').hide();
					$(form).find('.send').attr('disabled',false);
					alert("Appointment Not switched due to different consult fee.");
				 }
				 else
				 {
				  jQuery('.loading-all').hide();
				  $(form).find('.send').attr('disabled',false);
				  alert("Oops Something Problem");
				 }
			},
			  error: function(error)
			  {
				  jQuery('.loading-all').hide();
				  alert("Oops Something goes Wrong.");
			  }
		});
	  }
	}
});
 jQuery(document).on("click", "#addApptNote", function () {
   $('#appt_id').val('');
   $('#appt_note').val('');
   var id = $(this).attr('apptId');
   var note = $(this).attr('pNote');
   $('#appt_id').val(id);
   $('#appt_note').val(note);
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
            alert("Oops Something Problem");
           }
        },
          error: function(error)
          {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
          }
      });
       }
    });
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
		var practimeslot = jQuery("form[name='switchAppointment']").find("input[name='slot_duration']").val();
		var updatedEndTime =  moment(apostart_time, "HH:mm").add(practimeslot, 'minutes');
		var selectedvar = moment(updatedEndTime).format('HH:mm'); console.log(selectedvar);
		$('.appDateSection').find('.session_time_down').val(selectedvar);
	});

	jQuery(document).on("change", ".startDate", function (){
		var type = jQuery("form[name='switchAppointment']").find("input[name='app_type']").val();
		var doc_id = jQuery("form[name='switchAppointment']").find(".chooseDoc").val();
		var app_id =  jQuery("form[name='switchAppointment']").find("input[name='app_id']").val();
		var pId =  jQuery("form[name='switchAppointment']").find("input[name='pId']").val();
		var date =  $(this).val();
		loadSlot(type,date,doc_id,app_id,pId);
		// $('.appDateSection').find('.end_timeCal').val($(this).val());
	});
	/*jQuery(document).on("change", ".changestart_time", function (){
      var currevent = this;
      var apostart_time = $(this).val();
      var practimeslot = jQuery("form[name='switchAppointment']").find("input[name='slot_duration']").val();
      var updatedEndTime =  moment(apostart_time, "HH:mm:ss").add(practimeslot, 'minutes');
      var selectedvar = moment(updatedEndTime).format('HH:mm:ss'); console.log(selectedvar);
      var stDate = $(currevent).parents('.appDateSection').find("input[name='appstart_date']").val();
      var stDatetimestamp = moment.utc($(currevent).parents('.appDateSection').find("input[name='append_date']").val()+' '+selectedvar).format('X');
      arrsloatEnd = [];
      $(currevent).parents('.appDateSection').find("select[name^='appstart_time']").find('option').each(function(){
        var endstamp = moment.utc($(currevent).parents('.appDateSection').find("input[name='append_date']").val()+' '+$(this).val()).format("YYYY-MM-DD HH:mm:ss");
        var endTimestamp = moment.utc(endstamp).format('X');
        if(endTimestamp >= stDatetimestamp){
        arrsloatEnd.push($(this).val());
        }
      });
      var row = '';
      $.each( arrsloatEnd, function( key, value ) {
          var str = value;
          var time = new moment(str, 'HH:mm:ss');
          row += '<option value="'+value+'" >'+moment(time).format('hh:mm A')+'</option>';
      });
      $(currevent).parents('.appDateSection').find('.session_time_down').empty();
      $(currevent).parents('.appDateSection').find('.session_time_down').html(row);
  });*/
jQuery(document).ready(function () {
  jQuery(".update_rating").on('change',function(){
        jQuery('.loading-all').show();
        var appId =  $(this).attr('appId');
        var rating =  $(this).val();
        var btn = this;
        jQuery.ajax({
          type: "POST",
          url: "{!! route('admin.appointmentRating')!!}",
          data: {"_token":"{{ csrf_token() }}",'appId':appId,'rating':rating},
          success: function(data){
            jQuery('.loading-all').hide();
             if(data == 1) {
				alert("Rating updated successfully..");
				$(btn).parent(".ratingDiv").find(".starDiv").text(rating+" STAR");
				$(btn).parent(".ratingDiv").find(".starDiv").show();
				$(btn).hide();
             }
             else{
                 alert("System Problem");
             }
           },
           error: function(error){
             jQuery('.loading-all').hide();
             alert("Oops Something goes Wrong.");
           }
        });
  });
});

$(document.body).on('click', '.showApptDtls', function(){
	jQuery('.loading-all').show();
	var pid = $(this).attr("data-pid");
	var mobile = $(this).attr("data-mobile");
	var showAppReq;
	if(showAppReq){
		showAppReq.abort();
	}
	showAppReq = jQuery.ajax({
		url: "{!! route('admin.showAppts') !!}",
		type : "POST",
		dataType : "JSON",
		data:{'pid':pid,'mobile':mobile},
		success: function(result) {
			$("#patApptModal").find(".upperTr").html('');
			console.log(result['appts']);
			console.log(result['tot_rem_appt']);
			if(result['appts'].length > 0) {
				var apptTr = '';
				$.each(result['appts'], function(key,value) {
					 key = key+1;
					 // var start = new moment(value.start, 'DD-MM-YYYY HH:mm:ss');
					 var start = moment(value.start).format('DD-MM-YYYY hh:mm:A');
					 var pat_name = value.patient.first_name+" "+value.patient.last_name+"("+value.patient.mobile_no+")";
					 var doc_name = "Dr. "+value.user.doctor_info.first_name+" "+value.user.doctor_info.last_name;
					 var total_rs = '';
					 var payType = '';
					 if(value.appointment_order){
						 total_rs = value.appointment_order.order_total;
						 if(value.appointment_order.type == '1'){
							 payType = "Paid";
						 }
						 else if(value.appointment_order.type == '0'){
							 payType = "Free";
						 }
						 else if(value.appointment_order.type == '2'){
							 payType = "Cash";
						 }
					 }
					 var appt_type = "";
					 if(value.type == '3'){
						 appt_type = "Tele Consult";
					 }
					 else{
						 appt_type = "In-Clinic";
					 }
					 apptTr += '<tr><td>'+key+'.</td><td>'+start+'</td><td>'+doc_name+'</td><td>'+pat_name+'</td><td>'+appt_type+'</td><td>'+payType+'</td><td>'+total_rs+'/-</td></tr>';
				});
				console.log(apptTr);
				$("#patApptModal").find(".upperTr").append(apptTr);
				if(result['tot_rem_appt'] != "") {
					$(".RemainingAppointment").show();
					$("#patApptModal").find(".totAppt").text(result['tot_rem_appt']);
				}
				else{
					$(".RemainingAppointment").hide();
				}

				jQuery('.loading-all').hide();
				$("#patApptModal").modal("show");
			}
			else{
				jQuery('.loading-all').hide();
				$("#patApptModal").modal("show");
			}
	  },
	  error: function(error) {
			jQuery('.loading-all').hide();
			if(error.status == 401 || error.status == 419){
				//alert("Session Expired,Please logged in..");
				// location.reload();
			}
			else{
				//alert("Oops Something goes Wrong.");
			}
		}
	});
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

jQuery(document).on("click", ".makeFollow", function (e) {
	jQuery("form[name='switchAppointment']").find(".switchBtn").show();
});
jQuery(document).on("click", ".chooseSlot", function (e) {
	jQuery("form[name='switchAppointment']").find(".switchBtn").show();
	jQuery("form[name='switchAppointment']").find(".chooseSlot").each(function (e) {
		$(this).removeClass("selectSlot");
	});
	$(this).addClass("selectSlot");
	var slot_time = $(this).attr("slot");
	jQuery("form[name='switchAppointment']").find('input[name="time"]').val(slot_time);
});
jQuery(document).on("click", ".sendPres", function (e) {
	if(confirm('Are You Sure?')) {
		jQuery('.loading-all').show();
		var appId =  $(this).attr('appId');
		jQuery.ajax({
		  type: "POST",
		  url: "{!! route('admin.sendPres')!!}",
		  data: {"_token":"{{ csrf_token() }}",'appId':appId},
		  success: function(data){
			jQuery('.loading-all').hide();
			 if(data == 1) {
				alert("Prescription Send successfully..");
			 }
			 else{
				 alert("System Problem");
			 }
		   },
		   error: function(error){
			 jQuery('.loading-all').hide();
			 alert("Oops Something goes Wrong.");
		   }
		});
	}
});
jQuery(document).on("click", ".sendPresToPharmacy", function (e) {
	if(confirm('Are You Sure?')) {
		jQuery('.loading-all').show();
		var appId =  $(this).attr('appId');
		jQuery.ajax({
		  type: "POST",
		  url: "{!! route('admin.sendPresToPharmacy')!!}",
		  data: {"_token":"{{ csrf_token() }}",'appId':appId},
		  success: function(data){
			jQuery('.loading-all').hide();
			 if(data == 1) {
				alert("Prescription Send successfully..");
			 }
			 else{
				 alert("System Problem");
			 }
		   },
		   error: function(error){
			 jQuery('.loading-all').hide();
			 alert("Oops Something goes Wrong.");
		   }
		});
	}
});
function copyText(text) {
  var input = document.body.appendChild(document.createElement("input"));
  input.value = text;
  input.select();
  document.execCommand('copy');
  input.parentNode.removeChild(input);
}
// function Change_status(id,changestaus){
jQuery(document).on("change", ".update_appoint", function (e) {	
    jQuery('.loading-all').show();
	var apptId = $(this).attr('apptId');
	var btn = this;
	jQuery.ajax({
		  type: "POST",
		  url: "{!! route('admin.cahngeStatus')!!}",
		  data: {"_token":"{{ csrf_token() }}",'appId':apptId,'status':2},
		  success: function(data){
			alert("Status updated successfully");
			jQuery('.loading-all').hide();
			$(btn).parent(".ratingDivTop").find(".update_appoint").hide();
			$(btn).parent(".ratingDivTop").find(".show_working_status").show();
		},
		   error: function(error){
			 jQuery('.loading-all').hide();
			 
			 console.log("============",error);
			 alert("Oops Something goes Wrong.");
		   }
     });
});
jQuery(document).on("click", ".showPrescription", function (e) {
	// if(confirm('Are You Sure?')) {
		jQuery('.loading-all').show();
		var appId =  $(this).attr('appId');
		
		jQuery.ajax({
		  type: "POST",
		  url: "{!! route('admin.showPrescription')!!}",
		  data: {"_token":"{{ csrf_token() }}",'appId':appId},
		  success: function(data){
			
			jQuery('.loading-all').hide();
			 if(data) {
				window.open(data);
			 }
			 else{
				 alert("System Problem");
			 }
		   },
		   error: function(error){
			 jQuery('.loading-all').hide();
			 alert("Oops Something goes Wrong.");
		   }
		});
	// }
});
</script>
@endsection
