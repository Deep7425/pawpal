@extends('layouts.admin.Masters.Master')
@section('title', 'Feedback List')
@section('content')


<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
         
                <div class="container-fluid flex-grow-1 container-p-y">

				<div class="col-sm-2 mb-3">
						           <div class="btn-group">
                                        <a class="btn btn-success" href="javascript:void();">{{$feedbacks->total()}}</a>
                                    </div>
						 </div>
				        
										
			

				 <div class="layout-content card" >


				 <div class="row mt-2 ml-1">
                          
						

						  <div class="col-sm-2">
								 <div class="dataTables_length">
									  <label>Paginate By</label>
										{!! Form::open(array('route' => 'admin.feedbackPatAll', 'id' => 'chnagePagination', 'method'=>'POST')) !!}

									   <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
										 <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
										 <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
										 <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
										 <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
										 <option value="300" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '300') selected @endif @endif>300</option>
									   </select>
								 </div>
						 </div>
	   
						  <div class="col-sm-3">
								 <div class="dataTables_length">
									 <label>&nbsp </label>
									 <div class="input-group custom-search-form">
										 <span class="input-group-btn">
											<button class="btn btn-primary form-control" type="submit"> SEARCH</button>
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
										 <th>Doctor Name</th>
										 <th>User Name</th>
										 <th>Visit type</th>
										 <th>Rating</th>
										 <th>Recommendation</th>
										 <th>Waiting Time</th>
										 <th>Publish Status</th>
										 <th>Status</th>
										 <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($feedbacks->count() > 0)
								@foreach($feedbacks as $index => $element)
                                    <tr>
										<td>
											<label>{{$index+($feedbacks->currentpage()-1)*$feedbacks->perpage()+1}}.</label>
										</td>
										<td>Dr. @if($element->resource==1){{@$element->DoctorsInfo->first_name}} {{@$element->DoctorsInfo->last_name}} @else {{@$element->Doctors->first_name}} {{@$element->Doctors->last_name}} @endif</td>
										<td>@if(!empty($element->user_id)){{getUserName($element->user_id)}}@endif</td>
										<td>@if($element->visit_type == '1') Consultation @elseif($element->visit_type == '2') Procedure @elseif($element->visit_type == '3') Follow up @endif</td>
										<td>
										  @if(!empty($element->rating))
											<div class="review-number">
												{{$element->rating}}<span class="glyphicon glyphicon-star"></span>
											</div>
										  @endif
										</td>
										<td>
										  @if($element->recommendation == 1) Yes @else No @endif
									   </td>
										<td>
										  @if($element->recommendation == 1) Less than 5 min @endif
										  @if($element->recommendation == 2) 5 min to 10 min @endif
										  @if($element->recommendation == 3) 10 min to 30 min @endif
										  @if($element->recommendation == 4) 30 min to 1 hour @endif
										  @if($element->recommendation == 5) More than 1 hour @endif
										</td>
										<!-- <td><span class="label-default label @if($element->publish_status == '1') label-success @else label-danger @endif">@if($element->publish_status == '1') Publish @else Not @endif</span></td> -->

										<td><a class="btn @if($element->publish_admin == '0') btn-success @else btn-danger @endif changeStatus" type="1"  status="{{$element->publish_admin}}" data-id="{{$element->id}}" href="javascript:void();">@if($element->publish_admin == '0') Publish @else Unpublish @endif</a></td>
										<td><a class="btn @if($element->status == '0') btn-success @else btn-danger @endif changeStatus" type="2"  status="{{$element->status}}" data-id="{{$element->id}}" href="javascript:void();">@if($element->status == '0') Active @else Inactive @endif</a></td>
										<!-- <td><span class="label-default label @if($element->status == '1') label-success @else label-danger @endif">@if($element->status == '1') Active @else Cancel @endif</span></td> -->
										<td><div style="width: 75px;"><a href="javascript:void();" class="btn btn-info btn-sm" onclick="viewFeedback({{$element->id}});"><i class="fa fa-eye" aria-hidden="true"></i></a>
										<a href="javascript:void(0);" pkey="{{base64_encode(@$element->id)}}" r_from="3" class="btn btn-info btn-sm manageSprt" title="Manage Leads"><img src='{{ url("/img/customer-care-icon.png") }}'/></a>
										</div> 
										</td>
									</tr>
								@endforeach
								@else
									<tr><td colspan="8">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>

						<div class="page-nation d-flex justify-content-end mb-2">
							<ul class="pagination pagination-large ">
								{{ $feedbacks->appends($_GET)->links() }}
							</ul>
		             	</div>

		          </div>
          </div>
      </div>
   </div>
   <div class="modal fade" id="viewModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
   


<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 

<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>

<script>
function chnagePagination(e) {
	$("#chnagePagination").submit();
}
function viewFeedback(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.viewFeedback')!!}",
    data:{'id':id},
    success: function(data)
    {
		console.log("ee")
      jQuery('.loading-all').hide();
      jQuery("#viewModal").html(data);
      jQuery('#viewModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.-1");
    }
  });
}
jQuery(document).ready(function(){
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
				  $(confirm_btn).closest('td .confirm_by_doc').addClass('label-success');
				  $(confirm_btn).closest('td .confirm_by_doc').removeClass('label-warning');
				  $(confirm_btn).closest('td .confirm_by_doc').html('Confirm');
				  $(confirm_btn).closest('td .confirm_by_doc').removeClass('confirm_by_doc');
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
						alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						alert("Oops Something goes Wrong.-2");
					}
				}
			});
		}
	});
});
jQuery('.changeStatus').on('click', function() {
  var type = $(this).attr('type');
  var id = $(this).attr('data-id');
  var status = $(this).attr('status');
    if (type == 1) {
      if (status == 1) {
        var text = 'Are you sure to Unpublish Feedback ?';
      }
      else {
        var text = 'Are you sure to Publish  Feedback ?';
      }
    }
    else {
      if (status == 1) {
        var text = 'Are you sure to Inactive Feedback ?';
      }
      else {
        var text = 'Are you sure to Active Feedback ?';
      }
    }
  if (confirm(text)) {
    jQuery.ajax({
      url: "{!! route('admin.changeFeedbackStatus') !!}",
     // type : "POST",
      dataType : "JSON",
      data:{'type':type, 'id':id, 'status':status},
      success: function(result){
        location.reload();

    }}
    );
  }
  else {
    return false;
  }
})

</script>
@endsection
