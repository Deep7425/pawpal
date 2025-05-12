@extends('layouts.admin.Masters.Master')
@section('title', 'Claim Doctor list')
@section('content') 


<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
          <div class="container-fluid flex-grow-1 container-p-y non-hg-doctors page">
							<div class="row form-top-row">
								
									<div class="btn-group"> 
											<a class="btn btn-success" href="javascript:void();">{{$doctors->total()}}</a>
										</div>	
										<div class="btn-group excel mar-r5">
											 <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
										</div>	
										<div class="btn-group"> 
											<a class="btn btn-success" href="{{ route('admin.addDoctor') }}">Add New Doctor</a>
										</div>
										<div class="row-right">
											<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
												<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
												<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
												<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
												<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
											</select>
										</div>
									</div>
								

							
								<div class="layout-content card appointment-master">
								<div class="row">
							<!--<div class="col-sm-3">
                                                    <div class="dataTables_length">
													@if($type == '1')
														{!! Form::open(array('route' => 'admin.claimDoctorsList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
													@elseif($type == '2')
														{!! Form::open(array('route' => 'admin.nonClaimDoctorsList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
													@endif	
                                                        <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>	
														<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                                                <option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>
                                                                <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
                                                                <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
                                                                <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
                                                        </select>
                                                        </div>
                                                    </div>-->
													 <div class="col-sm-3">
														<div class="dataTables_length length123">
															<select class="form-control state_id searchDropDown" name="state_id">
															  <option value="">Select State</option>
																@foreach (getStateList(101) as $state)
																	<option value="{{ $state->id }}" @if(old('state_id') == $state->id) selected @endif >{{ $state->name }}</option>
																@endforeach
															</select>
													   </div>
												   </div>
												   <div class="col-sm-3">
														<div class="dataTables_length length123">
															<select class="form-control city_id searchDropDown" name="city_id">
																<option value="">Select City</option>
																@if(!empty(old('state_id')))
																@foreach (getCityList(old('state_id')) as $city)
																	<option value="{{ $city->id }}" @if(old('city_id') == $city->id) selected @endif >{{ $city->name }}</option>
																@endforeach
																@endif
															</select>
													   </div>
												   </div>
												   
												   <div class="col-sm-3">
														<div class="dataTables_length length123">
															<select class="form-control locality_id searchDropDown" name="locality_id">
																<option value="">Select Locality</option>
																@if(!empty(old('city_id')))
																@foreach (getLocalityList(old('city_id')) as $locality)
																	<option value="{{ $locality->id }}" 
																	@if(old('locality_id') == $locality->id) selected @endif >{{ $locality->name }}</option>
																@endforeach
																@endif
															</select>
													   </div>
												   </div>
												   
												   <div class="col-sm-3">
														<div class="dataTables_length length123">
															<select class="form-control searchDropDown" name="speciality_id">
																<option value="">Select Speciality</option>
																@foreach(getSpecialityList() as $spc)
																	<option value="{{ $spc->id }}" 
																	@if(old('speciality_id') == $spc->id) selected @endif >{{ $spc->spaciality }}</option>
																@endforeach
															</select>
													   </div>
												   </div>
                                                     <div class="col-sm-3 col-xs-12">
                                                        <div class="dataTables_length">
                                                            <div class="input-group custom-search-form">
                                                                <input name="search" type="text" class="form-control capitalizee" placeholder="search by name" value="{{ old('search') }}"/>
                                                          </div>
                                                      </div>
                                                  </div>
												  <div class="col-sm-3 col-xs-12">
                                                        <div class="dataTables_length">
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
							</div>	</div>	
                                
		                         <div class="layout-content">
		                              <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Picture</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Registration No.</th>
														<th>Reg. Year</th>
                                                        <th>Reg. Council</th>
                                                        <th>Last Obtained Degree</th>
                                                        <th>Degree Year</th>
                                                        <th>University</th>
                                                        <th>Mobile No</th>
                                                        <th>Speciality</th>
														<th>Clinic Name</th>
														<th>Address</th>
                                                        <th>State</th>
                                                        <th>City</th>
                                                        <th>Locality</th>
                                                        <th>Consultation Fee</th>
                                                        <th>Experience</th>
                                                        <th>Clinic Image</th>
														<th>Update Time</th>
														<!--<th>Claimed Timing</th>-->	
														<th>Recommend </th>
														<th>Register From</th>
                                                        <th>Verify Status</th>
														<th>Note</th>
														<th>Created By</th>
                                                        <th style="width:110px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
												@if($doctors->count() > 0)
													@foreach($doctors as $index => $doc) 
                                                    <tr>  
														 <td>
															<label>{{$index+($doctors->currentpage()-1)*$doctors->perpage()+1}}.</label>   
														 </td>
														 <td><img src="<?php
																if(!empty($doc->profile_pic)) {
																	echo getPath("public/doctor/ProfilePics/".$doc->profile_pic);
																}
																else { echo url("/")."/img/camera-icon.jpg"; }
															?>" class="img-circle" alt="User Image" height="50" width="50" /></td>
														 <td>{{$doc->first_name}} {{$doc->last_name}}</td>
														 <td>{{$doc->email}}</td>
														 <td>{{@$doc->reg_no}}</td>
														 <td>{{@$doc->reg_year}}</td>
														 <td>{{@$doc->RegCouncil->council_name}}</td>
														 <td>{{@$doc->last_obtained_degree}}</td>
														 <td>{{@$doc->degree_year}}</td>
														 <td>{{@$doc->University->name}}</td>
														 <td>{{$doc->mobile_no}}</td>
														 <td>{{@$doc->docSpeciality->specialities}}</td>
														 <td>{{$doc->clinic_name}}</td>
														  <td>{{$doc->address_1}}</td>
														 <td>{{getStateName($doc->state_id)}}</td>
														 <td>{{getCityName($doc->city_id)}}</td>
														 <td>{{getLocalityName($doc->locality_id)}}</td>
														 <td>{{$doc->consultation_fees}}</td>
														 <td>@if($doc->experience != null){{$doc->experience}} Year @endif</td>
														 <td><img src="<?php
															if(!empty($doc->clinic_image)){
																echo url("/")."/public/clinic/".$doc->clinic_image; 
															}
															else { echo url("/")."/img/camera-icon.jpg"; }
														?>" class="img-circle" alt="User Image" height="50" width="50" />
														 </td>
														 <td>@if(!empty($doc->updated_at)){{date('d-m-Y g:i A',strtotime($doc->updated_at))}}@else Not Updated @endif</td>
														 <!--<td>@if(!empty($doc->created_at)){{date('d-m-Y g:i A',strtotime($doc->created_at))}}@else Not Updated @endif</td>-->
														 <td>{{@$doc->recommend}}</td>
														 <td>@if(!empty($doc->member_id)) Dcotor Portal @else Online @endif</td>
														 <td>
														  @if($doc->claim_status == '1')
															<span style="color: #000;" class="verify_doc_by_admin label-default label @if($doc->varify_status == '1') label-success @else label-default @endif" data-id="{{$doc->id}}" data-val="{{$doc->varify_status}}">@if($doc->varify_status == '1') Active @else Activate @endif
															</span>
														  @endif
														  </td>
														<td>{{$doc->admin_note}}</td>
														<td>{{@$doc->admin->name}}</td>
														 <td style="width:110px;">
														 	<div class="QR-Code-top QR-Code-top123" style="width: 110px;">
															@if(checkAdminUserModulePermission(38))
															<button onclick="editDoc({{$doc->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
															@endif
															<button onclick="deleteDoctor({{$doc->id}});" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>
															<a href="javascript:void(0);" class="btn btn-info btn-sm" id="addPatientNote" pId="{{base64_encode($doc->id)}}" pNote="{{$doc->admin_note}}" title="Add Note"><i class="fa-regular fa-note-sticky" aria-hidden="true"></i></a>
														</div>
														</td>
													</tr>
													@endforeach
												@else
													<tr><td colspan="11">No Record Found </td></tr>
												@endif
												</tbody>
											</table>
										</div>

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
			<input type="hidden" name="note_type" value="3">
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
<div class="modal fade" id="doctorEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div>

<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 
<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>

<script type="text/javascript">
$(".searchDropDown").select2();
function editDoc(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editDoctor')!!}",
    data:{'id':id,'type':'claim'},
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
    jQuery('.loading-all').show();
	$("#chnagePagination").submit();
}

	jQuery(document).on("click", ".verify_doc_by_admin", function () {
		var doc_id = $(this).attr('data-id');
		var verify_status = $(this).attr('data-val');
		var currEvent = this;
		if(doc_id) {
			var confirmm = confirm("Are you sure want to verify this doctor?");
			if(confirmm) {
				 jQuery('.loading-all').show();
				jQuery.ajax({
					type: "POST",
					url: "{!! route('admin.varifyClaimDoctor') !!}",
					data: {'id':doc_id,'verify_status':verify_status},
					success: function(data){
						if(data == 1){
							alert('Doctor Verified Successfully.');
							location.reload();
						}
						else{
							jQuery('.loading-all').hide();
							alert('Oops somthings goes wrong.');
						}
					},
					error: function(error) {
						if(error.status == 401){
							//alert("Session Expired,Please logged in..");
							location.reload();
						}
						else{
							 jQuery('.loading-all').hide();
							alert("Oops Something goes Wrong.");
						}
					}
				});
			}
		}
	});
	
	function deleteDoctor(id) {
		if(confirm('Are you sure want to delete?') == true) {
			jQuery('.loading-all').show();
			jQuery.ajax({
				type: "POST",
				dataType : "JSON",
				url: "{!! route('admin.deleteDoctorInfo')!!}",
				data:{'id':id},
				success: function(data) {
					if(data==1) {
					  location.reload();
					}
					else {
					  alert("Oops Something Problem");
					}
					jQuery('.loading-all').hide();
				},
				error: function(error) {
					jQuery('.loading-all').hide();
					alert("Oops Something goes Wrong.");
				}
			});
		}
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
		
		 function ForExcel() {
			  jQuery("#file_type").val("excel");
			  $("#chnagePagination").submit();
			  jQuery("#file_type").val("");
			}
			
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
</script>
@endsection	
