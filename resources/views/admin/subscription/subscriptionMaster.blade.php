@extends('layouts.admin.Masters.Master')
@section('title', 'Subscriptions list')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet"
    href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />


    <style>

.viewSubscription123 ul li:hover ul {
    position: absolute;
    left: -130px;
    top: 0;
    width: 150px;
    z-index: 99;

}
    </style>


<div class="layout-wrapper layout-2">
    @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y data-list">

                <div class="row mb-2 form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$subscriptions->total()}}</a>
                    </div>
                    <div class="btn-group file-type">
                        <form method="post" class="UserDataExcelImports" enctype="multipart/form-data"
                            action="{{ route('admin.subcriptionImport') }}">
                            {{ csrf_field() }}
                                <input type="file" id="myFile" name="subcription_plan">
                                <input type="submit" name="upload" class="btn btn-primary" value="Upload" />
                            </div>
                        </form>
                        <div class="btn-group head-search data-list-btn">

                        <div class="col-sm-3">
                            <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                                <option value="25" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='25' ) selected @endif @endif>25</option>
                                <option value="50" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='50' ) selected @endif @endif>50</option>
                                <option value="100" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='100' ) selected @endif @endif>100</option>
                                <option value="500" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='500' ) selected @endif @endif>500</option>
                                <option value="1000" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='1000' ) selected @endif @endif>1000
                                </option>
                            </select>
                        </div>

                           <div class="mar-r5">
                                <div class="btn-group TOPMENU head-small">
                                    <a href="javascript:void(0);" class="btn btn-defaultp excel-btn" onClick='ForExcel()' title='Excel'><img
                                    src='{{ url("/img/excel-icon.png") }}' /></a>
                                </div>
                            </div>

                        <div class="btn-group or">
                            <a href="{{ url("/public/subcription_plan.ods") }}"
                                class="btn btn-success btn-icon-split" download="">
                                <span class="icon text-white-50">
                                    <i class="fa fa-download"></i>
                                </span>
                                <span class="text">Sample Download</span>
                            </a>
                        </div>
                    </div>
                    </div>
                    

                    
              

                <div class="layout-content card appointment-master user-data-form">


                    {!! Form::open(array('route' => 'subscription.subscriptionMaster', 'id' => 'chnagePagination',
                    'method'=>'POST')) !!}
                    <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}" />
                    <div class="row mt-2 ml-1 mr-1">
                    
                        <div class="col-sm-3">
                            <div class="">
                                <label>From</label>
                                <div class="input-group date">
                                    <input type="text" autocomplete="off" class="form-control fromStartDate"
                                        name="start_date"
                                        value="@if((app('request')->input('start_date'))!=''){{ base64_decode(app('request')->input('start_date')) }}@endif" />
                                    <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar"
                                            aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="">
                                <label>To</label>
                                <div class="input-group date">
                                    <input type="text" autocomplete="off" class="form-control toStartDate"
                                        name="end_date"
                                        value="@if((app('request')->input('end_date'))!=''){{ base64_decode(app('request')->input('end_date')) }}@endif" />
                                    <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar"
                                            aria-hidden="true"></i> </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="">
                                <label>Order Status</label>
                                <select class="form-control orderStatus" name="type">
                                    <option value="">All</option>
                                    <option value="0" @if((app('request')->input('type'))!='')
                                        @if(base64_decode(app('request')->input('type')) == '0') selected @endif @endif
                                        >pending</option>
                                    <option value="1" @if((app('request')->input('type'))!='')
                                        @if(base64_decode(app('request')->input('type')) == '1') selected @endif
                                        @endif>completed</option>
                                    <option value="2" @if((app('request')->input('type'))!='')
                                        @if(base64_decode(app('request')->input('type')) == '2') selected @endif
                                        @endif>Cancelled</option>
                                    <option value="3" @if((app('request')->input('type'))!='')
                                        @if(base64_decode(app('request')->input('type')) == '3') selected @endif
                                        @endif>Failure Transaction</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="">
                                <label>Status</label>
                                <select class="form-control sts" name="status">
                                    <option value="">All</option>
                                    <option value="1" @if((app('request')->input('status'))!='')
                                        @if(base64_decode(app('request')->input('status')) == '1') selected @endif
                                        @endif >Active</option>
                                    <option value="0" @if((app('request')->input('status'))!='')
                                        @if(base64_decode(app('request')->input('status')) == '0') selected @endif
                                        @endif >Inactive</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="">
                                <label>Plan Type</label>
                                <select class="form-control" name="plan_id">
                                    <option value="">Select Plan</option>
                                    @foreach(getGenniePlan() as $plan)
                                    <option value="{{$plan->slug}}" @if((app('request')->input('plan_id'))!='')
                                        @if(base64_decode(app('request')->input('plan_id')) == $plan->slug) selected
                                        @endif @endif>{{$plan->plan_title}} -
                                        ({{number_format($plan->price - $plan->discount_price,2)}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="">
                                <label>Payment Mode</label>
                                <select class="form-control" name="payment_mode">
                                    <option value="">All</option>
                                    <option value="1" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '1') selected @endif
                                        @endif >Online Payment</option>
                                    <option value="2" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '2') selected @endif
                                        @endif >Cheque</option>
                                    <option value="3" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '3') selected @endif
                                        @endif >Cash</option>
                                    <option value="4" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '4') selected @endif
                                        @endif >Admin Online</option>
                                    <option value="5" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '5') selected @endif
                                        @endif >Free</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="">
                                <label>Ref Code</label>
                                <select class="form-control" name="code">
                                    <option value="">Select Code</option>
                                    <option value="blank" @if((app('request')->input('code'))!='')
                                        @if(base64_decode(app('request')->input('code')) == 'blank') selected @endif
                                        @endif>Without Ref Code</option>
                                    <option value="emitra" @if((app('request')->input('code'))!='')
                                        @if(base64_decode(app('request')->input('code')) == 'emitra') selected @endif
                                        @endif>E-Mitra</option>
                                    @foreach(getRefCodeAll() as $raw)
                                    <option value="{{$raw->id}}" @if((app('request')->input('code'))!='')
                                        @if(base64_decode(app('request')->input('code')) == $raw->id) selected @endif
                                        @endif>{{$raw->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="">
                                <label>Name / Mobile No.</label>
                                <div class="input-group custom-search-form">
                                    <input name="name" type="text" class="form-control capitalizee"
                                        placeholder="search by name and mobile"
                                        value="{{base64_decode(app('request')->input('name'))}}" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="">
                                <label>Organization</label>
                                <select class="form-control" name="organization_id">
                                    <option value="">Select</option>
                                    @foreach(getOrganizations() as $raw)
                                    <option value="{{$raw->id}}" @if((app('request')->input('organization_id'))!='')
                                        @if(base64_decode(app('request')->input('organization_id')) == $raw->id)
                                        selected @endif @endif>{{$raw->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="dataTables_length">
                                <label>Filter</label>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary form-control" type="submit">
                                    <i class="fas fa-search"></i>
                                      
                                    </button>
                                </span>
                            </div>
                        </div>

                    </div>

                </div>

                {!! Form::close() !!}


                <div class="table-responsive mt-sm-1">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="width:70px;">S.No.</th>
                                <th>Sale By</th>
                                <th>Order ID</th>
                                <th>User Name</th>
                                <th>Mobile</th>
                                <th>Payment Mode</th>
                                <th>Plan Type</th>
                                <th>Tax</th>
                                <th>Payble Amount</th>
                                <th>Order Status</th>
                            
                                <th>Subscription Date</th>
                                <th>Ref Code</th>
                                <th>Note</th>
                                <th>Total Done Appointment</th>
                                <th>Status</th>
                                <th>Created By </th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php $getPermission = checkAdminUserModulePermission(41);?>
                            @if($subscriptions->count() > 0)
                            @foreach($subscriptions as $index => $subs)
                            <tr>
                                <th>{{$index+($subscriptions->currentpage()-1)*$subscriptions->perpage()+1}}.</th>
                                <td>@if($subs->added_by == 0) User @else {{getNameByLoginId($subs->added_by)}}@endif
                                </td>
                                <td>{{@$subs->order_id}}</td>
                                <td>{{@$subs->User->first_name}} {{@$subs->User->last_name}}</td>
                                <td>{{@$subs->User->mobile_no}}</td>
                                <td>@if($subs->payment_mode == "1") Online Payment @elseif($subs->payment_mode ==
                                    "2") Cheque @elseif($subs->payment_mode == "3") Cash @elseif($subs->payment_mode
                                    == "4") Admin Online @elseif($subs->payment_mode == "5") Free @endif</td>
                                <td>@if(!empty($subs->PlanPeriods) &&
                                    !empty($subs->PlanPeriods->Plans)){{$subs->PlanPeriods->Plans->plan_title}}
                                    ({{number_format($subs->PlanPeriods->Plans->price - $subs->PlanPeriods->Plans->discount_price,2)}})@endif
                                </td>
                                <td>{{$subs->tax}}</td>
                                <td>{{$subs->order_total}}</td>
                                <td>@if($subs->order_status == "0") pending @elseif($subs->order_status == "1")
                                    completed @elseif($subs->order_status == "2") Cancelled
                                    @elseif($subs->order_status == "3") Failure Transaction @endif</td>
                                <td>{{date('d-m-Y g:i A',strtotime($subs->created_at))}}</td>
                                <td>{{!empty($subs->ReferralMaster) ? $subs->ReferralMaster->code : $subs->User->mobile_no}}
                                    @if($subs->hg_miniApp == '1')
                                    (Help India)
                                    @elseif($subs->hg_miniApp == '2')
                                    (E-Mitra)
                                    @endif
                                </td>

                                <td>{{$subs->remark}}</td>
                                <td><?php
												$totAppt = 0;
												// $appointment_cnt = @$subs->UserSubscribedPlans[0]->appointment_cnt;
												// if(!empty($appointment_cnt)){
													// $appointment_ids = @$subs->UserSubscribedPlans[0]->PlanPeriods->appointment_ids;
													$appointment_ids = "";
													if(count($subs->UserSubscribedPlans) >0){
														foreach($subs->UserSubscribedPlans as $plan){
															if(!empty($plan->PlanPeriods) && !empty($plan->PlanPeriods->appointment_ids)){
																$appointment_ids .= $plan->PlanPeriods->appointment_ids.",";
															}
														}
													}
													if(!empty($appointment_ids)){
														$apptIds = explode(",",$appointment_ids);
														$totAppt = getTotApptByAppt($apptIds);
														// foreach($apptIds as $appId){
															// if(!empty($appId)){
															// if(checkAppointmentIsExist($appId)){
																// $totAppt++;
															// }}
														// }
													}
												// }
										?>{{$totAppt}}</td>
                                <td>
                                    @if($getPermission)
                                    <div class="toggleSwitch">
                                        <label class="switch"
                                            title="@if(@$subs->PlanPeriods->status == 1) Active @else Deactive @endif">
                                            <input type="checkbox" class="update_status" id="{{$subs->id}}"
                                                status="{{@$subs->PlanPeriods->status}}" @if(@$subs->PlanPeriods->status
                                            == 1) checked @endif>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    @endif
                                </td>

                                <td>{{@$subs->admin->name}}</td>

                                 
<td>

<div class="viewSubscription123 dd pptHere">
    <ul  style="margin : 5px;">

    <li>
    <a href="#"><i class="fa fa-bars" aria-hidden="true" ></i></a>
        <ul >
            <li><span><a href="javascript::void();" id="addSubNote" subId="{{base64_encode($subs->id)}}" sNote="{{$subs->remark}}" title="Add Note"> Add Note</a></span></li>
            <!-- <li><span class="label-default label " onclick="addNote({{$subs->id}});">Add Note</span></li> -->
            <li><span class="" onclick="viewSubscriptionDetails({{$subs->id}});">View</span></li>
            <li><span class=" "><a href="{{route('downloadsubrecadmin',base64_encode($subs->order_id))}}">Download Receipt</a></span></li>
        </ul>
    </li>
    </ul>
</div>

</td>





                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="13">No Record Found </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="page-nation text-right">
                    <ul class="pagination pagination-large">
                        {{ $subscriptions->appends($_GET)->links() }}
                    </ul>
                </div>
                </div>

            </div>

        </div>
        <div class="modal fade" id="subscriptionEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>


<div class="modal fade" id="viewSubscription" role="dialog" data-backdrop="static" data-keyboard="false"></div>



<div class="modal fade" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
					 <div class="modal-dialog feedback" >
						 <!-- Modal content-->
						 <div class="modal-content ">
							 <div class="modal-header">
								 <button type="button" class="close" data-dismiss="modal">×</button>
								 <h4 class="modal-title">Note</h4>
							 </div>
							 <div class="modal-body">
								 {!! Form::open(array('id' => 'addNote','name'=>'addNote')) !!}
								 <input type="hidden" name="id" id="sub_id" value="">
								 <div class="form-group">
									 <label>Note:</label>
									 <textarea type="text" name="note" rows="5" class="form-control" id="sub_note" placeholder="Write Note..."></textarea>
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

</div>



<!-- <script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> -->




<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}">
</script>
<script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script type="text/javascript">

jQuery(document).on("click", "#addSubNote", function () {
	$('#sub_id').val('');
	$('#appt_note').val('');
	var id = $(this).attr('subId');
	var note = $(this).attr('sNote');
	$('#sub_id').val(id);
	$('#sub_note').val(note);
	$('#AddModal').modal('show');
});

$('.btn-default').click(function() {
    $('.modal').modal('hide');
});

$('.close').click(function() {
    $('.modal').modal('hide');
});

function editSubscription(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('subscription.editSubscription')!!}",
        data: {
            "_token": "{{ csrf_token() }}",
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#subscriptionEditModal").html(data);
            jQuery('#subscriptionEditModal').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

function viewSubscriptionDetails(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('subscription.viewPlan')!!}",
        data: {
            "_token": "{{ csrf_token() }}",
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#viewSubscription").html(data);
            jQuery('#viewSubscription').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

function deleteSubscription(id) {
    if (confirm('Are you sure want to delete?') == true) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('subscription.deleteSubscription')!!}",
            data: {
                'id': id
            },
            success: function(data) {
                if (data == 1) {
                    location.reload();
                } else {
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

function chnagePagination(e) {
    $("#chnagePagination").submit();
}
jQuery(document).ready(function() {
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


});

function ForExcel() {
    jQuery("#file_type").val("excel");
    $("#chnagePagination").submit();
    jQuery("#file_type").val("");
}
jQuery(document).on("change", ".orderStatus", function(e) {
    if ($(this).val() == "1") {
        $(".sts option[value='1']").prop('selected', true);
    } else {
        $(".sts option[value='']").prop('selected', true);
    }
});
jQuery(document).on("click", ".update_status", function(e) {
    var id = $(this).attr('id');
    var status = $(this).attr('status');
    var current = $(this);
    if (status == 0) {
        var text = "Are you sure to Active Subscription"
    } else {
        var text = "Are you sure to Deactive Subscription";
    }
    if (confirm(text)) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "HTML",
            url: "{!! route('subscription.changePlanPeriodStatus') !!}",
            data: {
                'id': id,
                'status': status
            },
            success: function(data) {
                jQuery('.loading-all').hide();
                if (status == 1) {
                    $(current).attr('status', '0');
                } else {
                    $(current).attr('status', '1');
                }
            },
            error: function(error) {
                jQuery('.loading-all').hide();
                alert("Oops Something goes Wrong.");
            }
        });
    } else {
        return false;
    }
});



$(document.body).on('click', '.submit', function(){
    console.log("ww")
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
				url: "{!! route('admin.addSubNote')!!}",
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