@extends('layouts.admin.Masters.Master')
@section('title', 'Runners Lead')
@section('content')
    <!-- Content Wrapper. Contains page content -->
   <!-- Datepicker CSS start-->
<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />
<!-- Datepicker CSS end-->

    <div class="layout-wrapper layout-2">
     <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
                <div class="container-fluid flex-grow-1 container-p-y">

                <div class="row mb-2 ">

            <div class="col-sm-3">
            <div class="btn-group">
                                        <a class="btn btn-success" href="javascript:void();">{{$registrations->total()}}</a>
                                    </div>
									<div class="btn-group">
										 <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
									</div>
            </div>

                </div>
                    <div class="layout-content card">

<div class="row mt-2 ml-1 mr-1">

<div class="col-sm-3">
                                            <div class="dataTables_length length123">
											{!! Form::open(array('route' => 'admin.runnersLeads', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
												<input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>
												<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												</select>
                                            </div>
                                       </div>
                                       <div class="col-sm-3">
                                          <div class="dataTables_length">
                                              <div class="input-group date">
                                                  <input type="text" autocomplete="off" class="form-control fromStartDate" name="from_date" value="@if((app('request')->input('from_date'))!=''){{ base64_decode(app('request')->input('from_date')) }}@endif" placeholder="From Date" />
                                                  <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-sm-3">
                                          <div class="dataTables_length">
                                              <div class="input-group date">
                                                  <input type="text" autocomplete="off" class="form-control toStartDate" name="to_date" value="@if((app('request')->input('to_date'))!=''){{ base64_decode(app('request')->input('to_date')) }}@endif" placeholder="To Date" />
                                                  <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
                                              </div>
                                          </div>
                                      </div>

                    <div class="col-md-3">
                      <div class="dataTables_length">
                        <select class="form-control" name="app_download">
													<option value="">App Download</option>
                          <option value="Yes" @if((app('request')->input('app_download'))!='') @if(base64_decode(app('request')->input('app_download')) == 'Yes') selected @endif @endif>Yes</option>
													<option value="No" @if((app('request')->input('app_download'))!='') @if(base64_decode(app('request')->input('app_download')) == 'No') selected @endif @endif>No</option>
												</select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="dataTables_length">
                        <select class="form-control" name="appointment">
													<option value="">Appointment</option>
                          <option value="Yes" @if((app('request')->input('appointment'))!='') @if(base64_decode(app('request')->input('appointment')) == 'Yes') selected @endif @endif>Yes</option>
													<option value="No" @if((app('request')->input('appointment'))!='') @if(base64_decode(app('request')->input('appointment')) == 'No') selected @endif @endif>No</option>
												</select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="dataTables_length">
                        <select class="form-control" name="plan_sold">
													<option value="">Plan Sold</option>
                          <option value="Yes" @if((app('request')->input('plan_sold'))!='') @if(base64_decode(app('request')->input('plan_sold')) == 'Yes') selected @endif @endif>Yes</option>
													<option value="No" @if((app('request')->input('plan_sold'))!='') @if(base64_decode(app('request')->input('plan_sold')) == 'No') selected @endif @endif>No</option>
												</select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="dataTables_length">
                        <select class="form-control" name="created_by">
                          <option value="">Agent Name</option>
                          @foreach (getSalesTeam() as $key => $user)
                            <option value="{{$user->id}}" @if((app('request')->input('created_by'))!='') @if(base64_decode(app('request')->input('created_by')) == $user->id) selected @endif @endif>{{$user->name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
										<div class="col-sm-3">
											<div class="dataTables_length length123">
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="search by title" value="{{ old('search') }}"/>

												</div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="dataTables_length length123">
												<div class="input-group custom-search-form">
													<span class="input-group-btn">
													  <button class="btn btn-primary" type="submit">
														  SEARCH
													  </button>
													</span>
												</div>
											</div>
										</div>
										{!! Form::close() !!}
</div>
                    <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Mobile No.</th>
                                        <th>App Download</th>
                                        <th>Appointment</th>
                                        <th>Plan Sold</th>
                                        <th>Agent Name</th>
                                        <th>Address</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($registrations->count() > 0)
								@foreach($registrations as $index => $user)
                                    <tr>
										<td>
											<label>{{$index+($registrations->currentpage()-1)*$registrations->perpage()+1}}.</label>
										</td>
										<td>{{$user->name}}</td>
                    <td>{{@$user->mobile_no}}</td>
                    <td>{{@$user->app_download}}</td>
                    <td>{{@$user->appointment}}</td>
                    <td>{{@$user->plan_sold}}</td>
                    <td>{{@$user->SalesTeam->name}}</td>
                    <td>{{@$user->address}}</td>
										<td>{{date('d-M-Y', strtotime($user->created_at))}}</td>

									</tr>
								@endforeach
								@else
									<tr><td colspan="8">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
                  
            <div class="page-nation text-right d-flex justify-content-end mt-2 mb-2">
				<ul class="pagination pagination-large">
					{{ $registrations->appends($_GET)->links() }}
				</ul>
			</div>
                   </div>
                </div>
        </div>
      </div>
      <div class="modal fade" id="localityEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
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
		<input type="hidden" name="type" value="addNote">
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


</div>

<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>

<script>
function chnagePagination(e) {
	$("#chnagePagination").submit();
}

jQuery(document).ready(function () {
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
  jQuery(".update_status").on('click',function(){
      // if(confirm('Are you sure want to change status?')){
        jQuery('.loading-all').show();
        jQuery(this).attr('disabled',true);
        var id =  $(this).attr('id');
        var status =  $(this).attr('status');
        var btn = this;
        jQuery.ajax({
          type: "POST",
          url: "{!! route('admin.updateTshirtStatus')!!}",
          data: {"_token":"{{ csrf_token() }}",'id':id,'status':status},
          success: function(data){
            jQuery(btn).attr('disabled',false);
            jQuery('.loading-all').hide();
             if(data==1){
               jQuery(btn).text("Done");
               $(btn).attr('status','1');
             }
             else if(data==2){
               jQuery(btn).text("Pending");
               $(btn).attr('status','0');
             }
             else{
                 alert("System Problem");
             }
           },
           error: function(error){
             jQuery(btn).attr('disabled',false);
             jQuery('.loading-all').hide();
             alert("Oops Something goes Wrong.");
           }
        });
      // }
  });
});
jQuery('.changeStatus').on('click', function() {
  var id = $(this).attr('data-id');
  var status = $(this).attr('status');
	  if (status == 1) {
	    var text = 'Are you sure to Mark Panding?';
	  }
	  else {
	    var text = 'Are you sure to Mark Done?';
	  }
  if (confirm(text)) {
    jQuery.ajax({
      url: "{!! route('admin.modifyVaccDriveStatus') !!}",
     type : "POST",
      dataType : "JSON",
      data:{'id':id,'status':status,'type':'changeStatus'},
      success: function(result){
        location.reload();

    }}
    );
  }
  else {
    return false;
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
      url: "{!! route('admin.modifyVaccDriveStatus')!!}",
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
</script>
@endsection