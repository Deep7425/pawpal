@extends('layouts.admin.Masters.Master')
@section('title', 'HealthGennie Doctors')
@section('content')
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
                            <h1>HealthGennie Doctor</h1>
                            <small>Doctor list</small>
                            <ol class="breadcrumb hidden-xs">
                                <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                                <li class="active">Doctor</li>
                            </ol>
                        </div>
                    </section>
                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-bd lobidrag">
                                    <div class="panel-heading">
                                        <div class="btn-group">
											<a class="btn btn-success" href="javascript:void();">{{$doctors->total()}}</a>
										</div>
										<div class="btn-group">
											 <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
										</div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="panel-header  panel-headerTop123">
											{!! Form::open(array('route' => 'admin.liveDoctorsList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
											<div class="row">
													<div class="col-md-3">
														<div class="dataTables_length">
														<input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>
														    <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                                                <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                                                                <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
                                                                <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
                                                                <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                       <div class="dataTables_length">
                                                         <select class="form-control" name="filter">
															<option value="">All</option>
															  <option value="1" @if((app('request')->input('filter'))!='') @if(base64_decode(app('request')->input('filter')) == '1') selected @endif @endif >Subscribed Doctors</option>
															  <option value="2" @if((app('request')->input('filter'))!='') @if(base64_decode(app('request')->input('filter')) == '2') selected @endif @endif>Trial Doctors</option>
															  <option value="4" @if((app('request')->input('filter'))!='') @if(base64_decode(app('request')->input('filter')) == '4') selected @endif @endif>Claimed Doctors</option>
															  <option value="5" @if((app('request')->input('filter'))!='') @if(base64_decode(app('request')->input('filter')) == '5') selected @endif @endif>Trial Finish</option>
															  <option value="6" @if((app('request')->input('filter'))!='') @if(base64_decode(app('request')->input('filter')) == '6') selected @endif @endif>Plan Doctor</option>
														</select>
                                                       </div>
                                                     </div>
                                                     <div class="col-md-3">
                                                        <div class="dataTables_length">
                                                          <select class="form-control" name="facility">
 															  <option value="">Order By</option>
 															  <option value="1" @if((app('request')->input('facility'))!='') @if(base64_decode(app('request')->input('facility')) == '1') selected @endif @endif>Clinic (ASC)</option>

 														</select>
                                                        </div>
                                                      </div>

													 <div class="col-md-3">
														<div class="dataTables_length length123">
															<select class="form-control state_id searchDropDown" name="state_id">
															  <option value="">State</option>
																@foreach (getStateList(101) as $state)
																	<option value="{{ $state->id }}" @if(old('state_id') == $state->id) selected @endif >{{ $state->name }}</option>
																@endforeach
															</select>
													   </div>
												   </div>
												   <div class="col-md-3">
														<div class="dataTables_length length123">
															<select class="form-control city_id searchDropDown" name="city_id">
																<option value="">City</option>
																@if(!empty(old('state_id')))
																@foreach (getCityList(old('state_id')) as $city)
																	<option value="{{ $city->id }}" @if(old('city_id') == $city->id) selected @endif >{{ $city->name }}</option>
																@endforeach
																@endif
															</select>
													   </div>
												   </div>

												   <div class="col-md-3">
														<div class="dataTables_length length123">
															<select class="form-control locality_id searchDropDown" name="locality_id">
																<option value="">Locality</option>
																@if(!empty(old('city_id')))
																@foreach (getLocalityList(old('city_id')) as $locality)
																	<option value="{{ $locality->id }}"
																	@if(old('locality_id') == $locality->id) selected @endif >{{ $locality->name }}</option>
																@endforeach
																@endif
															</select>
													   </div>
												   </div>

												   <div class="col-md-3">
														<div class="dataTables_length length123">
															<select class="form-control searchDropDown" name="speciality_id">
																<option value="">Speciality</option>
																@foreach(getSpecialityList() as $spc)
																	<option value="{{ $spc->id }}"
																	@if(old('speciality_id') == $spc->id) selected @endif >{{ $spc->spaciality }}</option>
																@endforeach
															</select>
													   </div>
													   </div>
												   <div class="col-md-3">
														<div class="dataTables_length length123">
															<select class="form-control searchDropDown" name="grp_speciality">
																<option value="">Sub Speciality</option>
																@foreach(getSpecialityGroupList() as $spc)
																	<option value="{{ $spc->id }}"
																	@if(old('grp_speciality') == $spc->id) selected @endif >{{ $spc->group_name }}</option>
																@endforeach
															</select>
													   </div>
												   </div>
                           <div class="col-md-3">
                             <div class="dataTables_length">
                             <select class="form-control" name="oncall_status">
                               <option value="">Consult Type (ALL)</option>
                               <option value="3" @if((app('request')->input('oncall_status'))!='') @if(base64_decode(app('request')->input('oncall_status')) == '3') selected @endif @endif>BOTH</option>
							   <option value="1" @if((app('request')->input('oncall_status'))!='') @if(base64_decode(app('request')->input('oncall_status')) == '1') selected @endif @endif>Tele Consult</option>
                               <option value="2" @if((app('request')->input('oncall_status'))!='') @if(base64_decode(app('request')->input('oncall_status')) == '2') selected @endif @endif>In-clinic</option>
                             </select>
                             </div>
                           </div>
						
                                                     <div class="col-md-3">
                                                        <div class="dataTables_length">
															<div class="input-group custom-search-form">
                                                                <input name="search" type="text" class="form-control capitalizee" placeholder="name, clinic name, mobile" value="{{ old('search') }}"/>

                                                          </div>
													  </div>
													</div>
													<div class="col-md-3">
                                                        <div class="dataTables_length">
															<div class="input-group custom-search-form">
                                                                <span class="input-group-btn">
                                                                  <button class="btn btn-primary" type="submit">
                                                                      SEARCH
                                                                  </button>
                                                              </span>
                                                          </div><!-- /input-group -->
													  </div>
													</div>
													</div>
												</div>
													{!! Form::close() !!}
                                              </div>
                                          </div>
                                          <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Type</th>
                                                        <th>Picture</th>
														<th>Doctor Id</th>
                                                        <th>Name</th>
                                                        <th>Name (Hindi)</th>
                                                        <th>Clinic Name</th>
                                                        <th>Practice Type</th>
                                                        <th>Email</th>
                                                        <th>Mobile No</th>
                                                        <th>Clinic Mobile</th>
                                                        <th>Speciality</th>
                                                        <th>Qualification</th>
                                                        <th>Speciality Group</th>
                                                        <th>Address</th>
                                                        <th>State</th>
                                                        <th>City</th>
                                                        <th>Locality</th>
                                                        <th>Tele Fee</th>
                                                        <th>Experience</th>
														<th>Signature</th>
                                                        <th>OPD Timings</th>
                                                        <th>Registration Timing </th>
                                                        <th>Status </th>
                                                        <th>Action</th>
														<th style="width:70px;">Qr Code</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
												@if($doctors->count() > 0)
													@foreach($doctors as $index => $doc)
														<?php
                            // dd($doc);
                            $cityName = "jaipur";
														if(!empty($doc->getCityName)){
															$cityName = $doc->getCityName->slug;
														}	 ?>
                                                    <tr>
														 <td>
															<label>{{$index+($doctors->currentpage()-1)*$doctors->perpage()+1}}.</label>
														 </td>
														 <?php $types = []; if(!empty($doc->oncall_status)){ $types = explode(',',$doc->oncall_status); } ?>
														<td>@if(in_array(1,$types) && in_array(2,$types)) BOTH @elseif(in_array(2,$types)) In-clinic @elseif(in_array(1,$types)) Tele @endif</td>
														 <td><img src="<?php
																if(!empty($doc->profile_pic)) {
																	echo getPath("public/doctor/ProfilePics/".$doc->profile_pic);
																}
																else { echo url("/")."/img/camera-icon.jpg"; }
															?>" class="img-circle" alt="User Image" height="50" width="50" /></td>
															<td>{{@$doc->user_id}}</td>
														 <td>
														 @if ($doc->doc_type == '0')
														 	Dr. {{$doc->first_name}} {{$doc->last_name}}
														 @else ($doc->doc_type == '1')
															 Counselor {{$doc->first_name}} {{$doc->last_name}}
														 @endif
														 </td>
														 <td>{{$doc->name}}</td>
                                                         <td>{{$doc->clinic_name}}</td>
														<td>	@if(substr($doc->member_id,0,3) =='Pra')
															Practice
														@if($doc->practice_type == '1') (Clinic) @elseif($doc->practice_type == '2') (Hospital) @endif
														@elseif(substr($doc->member_id,0,3) =='Doc') Doctor @endif
														 </td>
														 <td>{{$doc->email}}</td>
														 <td>{{$doc->mobile_no}}</td>
														 <td>{{$doc->clinic_mobile}}</td>
														 <td>{{@$doc->docSpeciality->specialities}}</td>
														 <td>{{$doc->qualification}}</td>
														 <td>@if(!empty($doc->docSpeciality))
												         {{@$doc->docSpeciality->SpecialityGroup->group_name}} @endif</td>
														 <td>{{$doc->address_1}}</td>
														 <td>{{getStateName($doc->state_id)}}</td>
														 <td>{{getCityName($doc->city_id)}}</td>
														 <td>{{getLocalityName($doc->locality_id)}}</td>
														 <td>{{$doc->oncall_fee}}</td>
														 <td>@if($doc->experience != null){{$doc->experience}} Year @endif</td>
														 <td>@if(@$doc->DoctorsInfo->doctor_sign) Yes @else No @endif</td>
														 
														 <td>
														   <?php
																$doc_data =  docDetailsByOPDTimings($doc->id);
																if(isset($doc_data[0])){
																	$opdData = json_decode($doc_data[0]);
																	if(!empty($doc_data[0])){
																		if(!empty($opdData)){
																		  foreach($opdData as $opd){
																			 echo "<b>".getworkingdays($opd->days)."</b>:";
																			 $totTime =[];
																			 foreach(@$opd->timings as $mytime){
																				 $totTime[] = '&nbsp;'.date('g:i A',strtotime($mytime->start_time)).' - '.date('g:i A',strtotime($mytime->end_time)).'&nbsp;';
																			 }
																			 echo implode(',',$totTime);
																		  }
																		 }
																		}
																   }
															   ?>
														   </td>
                               <td>@if(!empty($doc->created_at)){{date('d-m-Y g:i A',strtotime($doc->created_at))}}@else Not Updated @endif</td>

														 <td><span class="label-default label @if($doc->status == '1') label-success @else label-danger @endif changeStatus" status="{{$doc->status}}" data-id="{{$doc->id}}">@if($doc->status == '1') Active @else Inactive @endif</span></td>
														 <td>
														 @if(checkAdminUserModulePermission(38))
															<button onclick="editDoc({{$doc->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
														@endif
														</td>
														<td style="width:70px;">
															<div class="QR-Code-top" style="width: 70px;">
														  <a href="@if(!empty($doc->DoctorSlug->name_slug)) {{route('qrcode.index',['action'=>'doctor', 'id'=>$doc->DoctorSlug->name_slug])}} @endif" class="btn btn-info btn-sm" title="Doctor Qr Code"><i class="fa fa-eye" aria-hidden="true"></i></a>
														  <a href="@if(!empty($doc->DoctorSlug->clinic_name_slug)) {{route('qrcode.index',['action'=>'clinic', 'id'=>$doc->DoctorSlug->clinic_name_slug])}} @endif" class="btn btn-info btn-sm" title="Clinic Qr Code"><i class="fa fa-qrcode" aria-hidden="true"></i></a>
														</div>
														</td>
													</tr>
													@endforeach
												@else
													<tr><td colspan="10">No Record Found </td></tr>
												@endif
												</tbody>
											</table>
										</div>
                    <div class="page-nation text-right">
                        <ul class="pagination pagination-large">
							{{ $doctors->appends($_GET)->links() }}
                           <!-- <li class="disabled"><span>«</span></li>
                            <li class="active"><span>1</span></li>
                            <li><a href="#">2</a></li>
                            <li class="disabled"><span>...</span></li><li>
                            <li><a rel="next" href="#">Next</a></li>-->
                        </ul>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section> <!-- /.content -->
<div class="modal fade" id="doctorEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<script type="text/javascript">
$(".searchDropDown").select2();
function editDoc(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editDoctor')!!}",
    data:{'id':id,'type':'live'},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#doctorEditModal").html(data);
      jQuery('#doctorEditModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
}
function chnagePagination(e) {
	$("#chnagePagination").submit();
}


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

	jQuery(document).on("change", ".city_id", function (e) {
		  var cid = this.value;
		  var $el = jQuery('.locality_id');
		  $el.empty();
		  jQuery(".locality_id").prepend($('<option></option>').html('Loading...'));
		  jQuery.ajax({
			  url: "{!! route('getLocalityList') !!}",
			  // type : "POST",
			  dataType : "JSON",
			  data:{'id':cid},
			success: function(result){
			  jQuery(".panel-header").find("select[name='locality_id']").html('<option value="">Select Locality</option>');
			  jQuery.each(result,function(index, element) {
				  $el.append(jQuery('<option>', {
					 value: element.id,
					 text : element.name
				  }));
			  });
			},
			error: function(error) {
				if(error.status == 401){
					location.reload();
				}
				else{
				}
			}
			}
		  );
		});

    jQuery(document).on("click", ".qrCodeStatus", function (e) {
      var id = $(this).attr('data-id');
      var current = $(this);
      if (confirm('Are you sure ?')) {
        jQuery('.loading-all').show();
        jQuery.ajax({
        type: "POST",
        dataType : "HTML",
        url: "{!! route('admin.appliedQRCode') !!}",
        data:{'id':id},
        success: function(data)
        {
          jQuery('.loading-all').hide();
        },
        error: function(error)
        {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
      });
      }
      else{
      return false;
      }

    });
function copyToClipboard(text,btn) {
  var input = document.body.appendChild(document.createElement("input"));
  input.value = text;
  // input.focus();
  input.select();
  document.execCommand('copy');
  input.parentNode.removeChild(input);
  $(btn).text("copied");
  $(btn).prop("disabled",true);
}
function ForExcel() {
  jQuery("#file_type").val("excel");
  $("#chnagePagination").submit();
  jQuery("#file_type").val("");
}
jQuery('.changeStatus').on('click', function() {
  var type = $(this).attr('type');
  var id = $(this).attr('data-id');
  var status = $(this).attr('status');
	  if (status == 1) {
	    var text = 'Are you sure to Inactive?';
	  }
	  else {
	    var text = 'Are you sure to Active?';
	  }
  if (confirm(text)) {
    jQuery.ajax({
      url: "{!! route('admin.changeDoctorStatus') !!}",
     type : "POST",
      dataType : "JSON",
      data:{'id':id, 'status':status},
      success: function(result){
        location.reload();

    }}
    );
  }
  else {
    return false;
  }
});



// jQuery(document).on("click", ".content-wrapper", function () {
	 // jQuery(".copyBtn").text("copy");
	 // jQuery(".copyBtn").prop("disabled",false);
// });
</script>
@endsection
