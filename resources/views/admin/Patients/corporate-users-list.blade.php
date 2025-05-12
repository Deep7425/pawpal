@extends('layouts.admin.Masters.Master')
@section('title', 'Users List')
@section('content')
<!-- =============================================== -->
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />


<div class="layout-wrapper layout-2">
     <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
                <div class="container-fluid flex-grow-1 container-p-y appointment-master">

				<div class="row mb-2 ml-1 form-top-row">
                            
				         
				<div class="panel-heading">
                                  {{app('request')->input('facility')}}
                                    <div class="add-user">
                                        <a class="btn btn-success" href="{{ route('admin.addPatients') }}"><i class="fa fa-plus"></i>Add User</a>
                                    </div>
                                    <div class="number">
                                        <a class="btn btn-success" href="javascript:void();">{{$patients->total()}}</a>
                                    </div>
									
                                    <div class="TOPMENU head-small d-flex justify-content-end ">
									@if(checkAdminUserModulePermission(34))
                                     <div class="btn-group excel">
                                      <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
                                    </div>
									@endif
                                    	<div class="" id="example_length">
                                                    {!! Form::open(array('route' => 'admin.corporateUsers', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                                                        <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>
														<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
															<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
															<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
															<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
															<option value="500" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '500') selected @endif @endif>500</option>
															<option value="1000" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '1000') selected @endif @endif>1000</option>
															<option value="2000" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '2000') selected @endif @endif>2000</option>
														</select>
                                                </div>
                                    </div>    
                                </div> 
				 
				</div>

            	    <div class="layout-content card body-edit">
              
					  <div class="row mt-2 ml-1 mr-1">

					
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
										  <div class="col-sm-2 registeredFromDiv" @if(isset($_GET['user_type']) && base64_decode($_GET['user_type']) == "2") style="display:block;" @else style="display:none;" @endif>
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
										<!--<div class="col-sm-2">
											<div class="dataTables_length">
											<label>Status</label>
											<select class="form-control" name="is_status">
												<option value="">Select..</option>
												<option @if(isset($_GET['is_status'])) @if(base64_decode($_GET['is_status']) == 0) selected @endif @endif value="0">Pending</option>
												<option @if(isset($_GET['is_status'])) @if(base64_decode($_GET['is_status']) == 1) selected @endif @endif value="1">Done</option>
												<option @if(isset($_GET['is_status'])) @if(base64_decode($_GET['is_status']) == 2) selected @endif @endif value="2">Call Not Received</option>
												<option @if(isset($_GET['is_status'])) @if(base64_decode($_GET['is_status']) == 3) selected @endif @endif value="3">Follow Up</option>
											</select>
											</div>
										</div>-->
                                        <div class="col-sm-3">
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
					</div>


					
			  </div>
			  <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="success">
                                        <tr>
                                            <th><input type="checkbox" class="selecAll" /></th>
                                            <th>S.No.</th>
                                            <th>Reg. Type</th>
                                            <th>Name / Gender(PID)</th>
                                            <th>Mobile No</th>
                                            <th>Other Mobile</th>
                                            <th>Address</th>
                                            <th>city</th>
                                            <th>state</th>
                                            <th>Location</th>
                                            <th>Subscribed</th>
                                            <th>Added By</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									@if($patients->count() > 0)
										@foreach($patients as $index => $pat)
                                        <tr class="tbrow">
											<td><input type="checkbox" value="{{$pat->id}}" class="sub_chk" /></td>
                                            <td>
                                               <label>{{$index+($patients->currentpage()-1)*$patients->perpage()+1}}.</label>
                                            </td>
                                            <td>
                                            	@if($pat->device_type == '1') Android
                                            	@elseif($pat->device_type == '2') IOS
                                                @else  @if($pat->login_type == '3') Paytm @else Web @endif  @endif
                                            </td>
											<td>{{$pat->first_name}} {{$pat->last_name}} @if(!empty($pat->gender))/ {{$pat->gender}}@endif @if(!empty($pat->pId))({{$pat->pId}})@endif</td>
											<td>{{$pat->mobile_no}}</td>
											<td>{{@$pat->other_mobile_no}}</td>
											<td>{{$pat->address}}</td>
											<td>{{getCityName($pat->city_id)}}</td>
											<td>{{getStateName($pat->state_id)}}</td>
											<td>
												@if(!empty($pat->location_meta))
													@php $location_meta = json_decode($pat->location_meta); @endphp
													{{getCityName(@$location_meta->city_id)}}
												@endif
											</td>
											<td>
											@if(checkUserSubcription($pat->id) == '1')
												Yes
											@else 
												No
											@endif
											</td>
											<td>
											{{getNameByLoginId($pat->register_by)}}
											</td>
											<td><div class="viewSubscription12">{{date('d-m-Y g:i A',strtotime($pat->created_at))}}</div></td>
											<td>
												<div class="viewSubscription123">
													<a href="{{ route('subscription.viewSubscription',['id'=>base64_encode($pat->id)]) }}" title="view Subscription"><span class="AddPlan">Add Plan</span></a>
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
							<div class="page-nation text-right d-flex justify-content-end mt-2 mb-2">
							<ul class="pagination pagination-large">
							{{ $patients->appends($_GET)->links() }}
							</ul>
						</div> 
				    </div>
         </div>
    </div>

<!-- Modal -->
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


</div>



<script src="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ URL::asset('assets/libs/moment/moment.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- /.content-wrapper -->
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
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

	// $(".fromStartDate").datepicker({
	// 	  changeMonth: true,
	// 	  changeYear: true,
	// 	  dateFormat: 'yy-mm-dd',
	// 	//minDate: new Date(),
	// 	onSelect: function (selected) {
	// 		var dt = new Date(selected);
	// 		dt.setDate(dt.getDate());
	// 		$(".toDOB").datepicker("option", "minDate", dt);
	// 	}
	// });
	// jQuery('.fromStartDate_cal').click(function () {
	// 	jQuery('.fromStartDate').datepicker('show');
	// });

	$(".fromStartDate").datepicker({
                format: 'yyyy-mm-dd',
                onSelect: function (selected) {
                    var dt = new Date(selected);
                    dt.setDate(dt.getDate());
                    // Your logic here based on the selected date
                }
            }).on('changeDate', function () {
                $(this).datepicker('hide');
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

// $(".toStartDate").datepicker({
// 	  changeMonth: true,
// 	  changeYear: true,
// 	  dateFormat: 'yy-mm-dd',
// 	//minDate: new Date(),
// 	onSelect: function (selected) {
// 		var dt = new Date(selected);
// 		dt.setDate(dt.getDate());
// 		$(".toDOB").datepicker("option", "minDate", dt);
// 	}
// });
// jQuery('.toStartDate_cal').click(function () {
// 	jQuery('.toStartDate').datepicker('show');
// });


$(".toStartDate").datepicker({
                            format: 'yyyy-mm-dd',
                onSelect: function (selected) {
                    var dt = new Date(selected);
                    dt.setDate(dt.getDate());
                    // Your logic here based on the selected date
                }
            }).on('changeDate', function () {
                $(this).datepicker('hide');
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
					 alert("Status change successfully..");
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
			alert("Follow up saved successfully..");
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
</script>
@endsection
