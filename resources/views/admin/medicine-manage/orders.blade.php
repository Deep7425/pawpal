@extends('layouts.admin.Masters.Master')
@section('title', 'Medicine Order List')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet"
    href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />
<style>
.displyNone {
    display: none;
}
</style>
<!-- =============================================== -->
<!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y med-order">
                @if(session()->get('message'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ session()->get('message') }}
                </div>
                @endif
                <?php
									$start_date = "";
									$end_date = "";
									$status = "";
									$page_no = 25;
									if((app('request')->input('start_date'))!=''){
										$start_date = base64_decode(app('request')->input('start_date'));
									}
									if((app('request')->input('end_date'))!=''){
										$end_date = base64_decode(app('request')->input('end_date'));
									}
									if(isset($_GET['page_no']) && base64_decode($_GET['page_no'])) {
										$page_no = base64_decode($_GET['page_no']);
									}
                  $status = base64_decode(app('request')->input('status'));
                  $prescription = base64_decode(app('request')->input('pres_type'));
								?>

                <div class="row mb-2 form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$orders->total()}}</a>
                    </div>

                    <div class="btn-group">
                        <div class="TOPMENU" id="example_length">
                            {!! Form::open(array('route' => 'admin.medicineOrder', 'id' => 'chnagePagination',
                            'method'=>'POST')) !!}
                            <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}" />
                        </div>
                    </div>

                    <div class="btn-group">

                        <div class="">
                          
                            <div class="input-group date">
                                <input type="text" autocomplete="off" class="form-control fromStartDate"
                                    name="start_date" value="{{$start_date}}" />
                                <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar"
                                        aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class="btn-group">
                        <div class="">
                          
                            <div class="input-group date">
                                <input type="text" autocomplete="off" class="form-control toStartDate" name="end_date"
                                    value="{{$end_date}}" />
                                <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar"
                                        aria-hidden="true"></i> </span>
                            </div>
                        </div>
                    </div>



                    <div class="btn-group head-search">

                        <div class=" ml-sm-2">
                           
                            <select class="form-control" name="status">
                                <option value="">All</option>
                                <option value="0" @if($status=='0' ) selected @endif>Pending</option>
                                <option value="1" @if($status=='1' ) selected @endif>Complete</option>
                                <option value="2" @if($status=='2' ) selected @endif>Cancelled</option>

                            </select>
                        </div>

                        <div class="ml-sm-2">
                          
                            <select class="form-control" name="pres_type">
                                <option value="">All </option>
                                <option value="1" @if($prescription=='1' ) selected @endif>Yes</option>
                                <option value="2" @if($prescription=='2' ) selected @endif>No</option>

                            </select>
                        </div>

                        <div class=" ml-sm-2">
                            
                            <div class="input-group custom-search-form">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        SEARCH
                                    </button>
                                </span>
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>


                </div>

                <div class="layout-content">

                    <div class="table-responsive ">
                        <table class="table table-bordered table-hover">
                            <thead class="success">
                                <tr>
                                    <th>S.No.</th>
                                    <th>Order. No.</th>
                                    <th>Prescription</th>
                                    <th>User Name</th>
                                    <th>Mobile</th>
                                    <th>Order Type</th>
                                    <th>Total Pay(Rs.)</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($orders) > 0)
                                <?php $i = 1; ?>
                                @foreach($orders as $index => $order)
                                <tr class="tbrow page">
                                    <td>
                                        <label>{{$index+($orders->currentpage()-1)*$orders->perpage()+1}}.</label>
                                    </td>
                                    <td class="tab-appointment12">{{@$order->order_id}}</td>
                                    <td class="tab-appointment12">@if($order->pres_type == '1') Yes @else No @endif</td>
                                    <td class="tab-appointment12">{{@$order->User->first_name}}
                                        {{@$order->User->last_name}}</td>
                                    <td class="tab-appointment12">{{@$order->User->mobile_no}}</td>
                                    <td class="tab-appointment12">@if($order->type == '0') Pending @elseif($order->type
                                        == '1') Free @elseif($order->type == '2') Paid @endif</td>
                                    <td class="tab-appointment12">{{number_format(@$order->order_total,2)}}</td>
                                    <td class="tab-appointment12">@if($order->status == '0') Pending
                                        @elseif($order->status == '1') Completed @elseif($order->status == '2')
                                        Cancelled @if(!empty($order->cancel_reason)) ({{$order->cancel_reason}}) @endif
                                        @elseif($order->status == '3') Failure Transaction @endif</td>
                                    <td class="tab-appointment12">
                                        @if(isset($order->created_at)){{date('d-m-Y h:i A',strtotime($order->created_at))}}
                                        @endif</td>
                                    <td>
                                        <div class="viewSubscription123">
                                            <ul>
                                                <li>
                                                    <a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
                                                    <ul>
                                                        <li><span class="label-default label label-success"
                                                                onclick="viewOrderDetails({{$order->id}});">View Order</span>
                                                        </li>
                                                        
                                                        @if($order->status == "0")
                                                        @if($order->pres_type == "2" && empty($order->appId) &&
                                                        $order->User != null)
                                                        
                                                        <li><button orderId="{{$order->order_id}}"
                                                                id="addFreeAppt"
                                                                pId="{{base64_encode($order->User->id)}}"
                                                                title="Create an Free Appointment">Free
                                                                Appointment</button></li>
                                                        @endif
                                                        <!--<li><button title="Create Order" class="completeOrder" orderId="{{base64_encode($order->id)}}" logId="{{base64_encode(Session::get('id'))}}"><i class="fa fa-link" aria-hidden="true"></i>Make Order</button></li>
														<li><button title="Mark order as complete without payment link" class="completeOrder" orderId="{{base64_encode($order->id)}}" logId="{{base64_encode(Session::get('id'))}}" type="1"><i class="fa fa-link" aria-hidden="true"></i>Complete order without paymeny link</button></li>-->
                                                       
<li><button title="Complete Order" class="completeOrder" orderId="{{base64_encode($order->id)}}" logId="{{base64_encode(Session::get('id'))}}" type="2">Complete Order</button></li>

                                                        <li><button title="Show All Payment Links" class="showLinks"
                                                                orderId="{{@$order->order_id}}" typ="2"
                                                                userId="{{base64_encode(@$order->User->id)}}"></i>Payment
                                                                Links</button></li>
                                                        @endif
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="19">No Record Found </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="page-nation text-right">
                        <ul class="pagination pagination-large">
                            {{ $orders->appends($_GET)->links() }}
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal md-effect-1 md-show" id="patApptModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal">×</button>
                <h4 class="modal-title">Payment Link</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>S.No.</td>
                                    <td>Order No.</td>
                                    <td>Payment Status</td>
                                    <td>Total Pay (Rs.)</td>
                                    <td>Payment Link</td>
                                    <td>Date</td>
                                </tr>
                            </thead>
                            <tbody class="upperTr"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
 
</div>


<div class="modal md-effect-1 md-show appoint" id="switchOrderModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-bs-dismiss="modal">×</button>
			<h4 class="modal-title">Order Manage</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-body SwitchAppointment">
					{!! Form::open(array('id' => 'completeOrderForm','name'=>'completeOrderForm')) !!}
					<input name="orderId" value="" type="hidden"/>
					<input name="logId" value="{{base64_encode(Session::get('id'))}}" type="hidden"/>
					<div class="wApt">
						<label><input type="radio" name="appttype" class="apptType" value="1" />For Payment Link</label>
						<label><input type="radio" name="appttype" class="apptType" value="2" />For Direct Payment</label>
					</div>
					<div class="medBlock" style="display:none;">
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
			<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
		</div>
	</div>
	</div>
</div>



<div class="modal fade viewOrderDetails123" id="viewOrderDetails" role="dialog" data-backdrop="static"
        data-keyboard="false"></div>
<!-- /.content-wrapper -->




<script src="{{ URL::asset('https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js' ) }}"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script src="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ URL::asset('assets/libs/moment/moment.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>

<script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>

<script src="{{ URL::asset('js/bootstrap.js') }}"></script>



<script type="text/javascript">

$(document.body).on('click', '.completeOrder', function() {
    // jQuery("form[name='completeOrderForm']").find("input[name='orderId']").val($(this).attr('orderId'));
    // jQuery('.loading-all').show();
    $("#switchOrderModal").modal("show");
});

jQuery(document).ready(function() {

    $(".fromStartDate").datepicker({
        format: 'yyyy-mm-dd',
        onSelect: function(selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate());
            // Your logic here based on the selected date
        }
    }).on('changeDate', function() {
        $(this).datepicker('hide');
    });
    jQuery('.fromStartDate_cal').click(function() {
        jQuery('.fromStartDate').datepicker('show');
    });
    $(".toStartDate").datepicker({
        format: 'yyyy-mm-dd',
        onSelect: function(selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate());
            // Your logic here based on the selected date
        }
    }).on('changeDate', function() {
        $(this).datepicker('hide');
    });
    jQuery('.toStartDate_cal').click(function() {
        jQuery('.toStartDate').datepicker('show');
    });
});

function chnagePagination(e) {
    $("#chnagePagination").submit();
}
jQuery(document).on("click", ".page-item", function() {
    var page = $(this).data('page');
    $('.page-item').removeClass('active');
    $(this).addClass('active');
    $('table > tbody  > tr').each(function(index, tr) {
        if ($(tr).attr('page') != page) {
            $(tr).addClass('displyNone');
        } else {
            $(tr).removeClass('displyNone');
        }
    });
    $("html, body").animate({
        scrollTop: 0
    }, "slow");
});
jQuery(document).on("click", "#addFreeAppt", function() {
    if (confirm('Are you sure?') == true) {
        jQuery('.loading-all').show();
        var pId = $(this).attr('pId');
        var orderId = $(this).attr('orderId');
        jQuery.ajax({
            type: "POST",
            url: "{!! route('admin.crtAppt')!!}",
            data: {
                "_token": "{{ csrf_token() }}",
                'pId': pId,
                'from': 2,
                'orderId': orderId
            },
            success: function(data) {
                jQuery('.loading-all').hide();
                if (data == 1) {
                    $.alert("Appointment created successfully..");
                }
            },
            error: function(error) {
                jQuery('.loading-all').hide();
                $.alert("Oops Something goes Wrong.");
            }
        });
    }
});

/*jQuery(document).on("click", ".completeOrder", function () {
	if(confirm('Are you sure?') == true){
	jQuery('.loading-all').show();
	var orderId = $(this).attr('orderId');
	var logId = $(this).attr('logId');
	var type = $(this).attr('type');
	jQuery.ajax({
	  type: "POST",
	  url: "{!! route('admin.completeOrder')!!}",
	  data: {"_token":"{{ csrf_token() }}",'orderId':orderId,'type':type,'logId':logId},
	  success: function(data) {
		 if(data.status==1) {
			 jQuery('.loading-all').hide();
			 $.alert({
				title: 'Success!',
				content: 'Link Create Successfully. Please click button to copy link.',
				draggable: false,
				type: 'green',
				typeAnimated: true,
				buttons: {
					Copy: function() {
						copyText(data.link);
					},
					Cancel : function() {
					}
				}
			  });
		 }
		 else if(data.status==2) {
			 jQuery('.loading-all').hide();
			 $.alert({
				title: 'Success!',
				content: 'Order Complete Successfully',
				draggable: false,
				type: 'green',
				typeAnimated: true,
				buttons: {
					Ok: function(){
						location.reload();
					},
				}
			  });
		 }
		 else {
		  jQuery('.loading-all').hide();
		  $.alert("Oops Something Problem");
		 }
	   },
	   error: function(error){
		 jQuery('.loading-all').hide();
		 $.alert("Oops Something goes Wrong.");
	   }
	});
	}
});*/
jQuery("#completeOrderForm").validate({
    rules: {
        appttype: {
            required: true,
        },
        payment_mode: {
            required: true,
        },
        tracking_id: {
            required: true,
        }
    },
    messages: {},
    errorPlacement: function(error, element) {
        error.appendTo(element.parent().find('.help-block'));
    },
    ignore: ":hidden",
    submitHandler: function(form) {
        if (confirm('Are You sure?')) {
            jQuery('.loading-all').show();
            $(form).find('.send').attr('disabled', true);
            jQuery.ajax({
                type: "POST",
                dataType: "JSON",
                url: "{!! route('admin.completeOrder')!!}",
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == 1) {
                        console.log("qq")
                        jQuery('.loading-all').hide();
                        $(form).find('.send').attr('disabled', false);
                        $("#switchOrderModal").modal("hide");
                        $.alert({
                            title: 'Success!',
                            content: 'Link Create Successfully. Please click button to copy link.',
                            draggable: false,
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                                Copy: function() {
                                    copyText(data.link);
                                },
                                Cancel: function() {

                                }
                            }
                        });
                    } else if (data.status == 2) {
                        jQuery('.loading-all').hide();
                        $(form).find('.send').attr('disabled', false);
                        $.alert("Order Completed Successfully.");
                        $("#switchOrderModal").modal("hide");
                    } else {
                        jQuery('.loading-all').hide();
                        $(form).find('.send').attr('disabled', false);
                        $.alert("Oops Something Problem");
                    }
                },
                error: function(error) {
                    jQuery('.loading-all').hide();
                    $.alert("Oops Something goes Wrong.");
                }
            });
        }
    }
});



function copyText(text) {
    var input = document.body.appendChild(document.createElement("input"));
    input.value = text;
    input.select();
    document.execCommand('copy');
    input.parentNode.removeChild(input);
}
jQuery(document).on("click", ".copyBtn", function() {
    var input = document.body.appendChild(document.createElement("input"));
    input.value = $(this).attr('txt');
    input.select();
    document.execCommand('copy');
    input.parentNode.removeChild(input);
    $(this).text("copied");
    $(this).prop("disabled", true);
});
var showAppReq;
$(document.body).on('click', '.showLinks', function() {
    jQuery('.loading-all').show();
    var userId = $(this).attr("userId");
    var typ = $(this).attr("typ");
    var orderId = $(this).attr("orderId");
    if (showAppReq) {
        showAppReq.abort();
    }
    showAppReq = jQuery.ajax({
        url: "{!! route('admin.loadLinks') !!}",
        type: "POST",
        dataType: "JSON",
        data: {
            'userId': userId,
            'typ': typ,
            'orderId': orderId
        },
        success: function(result) {

            $("#patApptModal").find(".upperTr").html('');
            var apptTr = '';
            if (result['links'].length > 0) {
                $.each(result['links'], function(key, value) {
                    key = key + 1;
                    var paySts = 'PENDING';
                    var payCls = 'pen';
                    if (value.status == '1') {
                        paySts = 'DONE';
                        payCls = 'dne';
                    } else if (value.status == '2') {
                        paySts = 'EXPIRED';
                        payCls = 'expp';
                    }
                    var totPay = '';
                    var crtDate = '';
                    if (value.meta_data) {
                        meta_data = JSON.parse(value.meta_data);
                        totPay = "₹ " + meta_data.order_total + " /-";
                    }
                    crtDate = moment(value.created_at).format('DD-MM-YYYY hh:mm A');
                    cpyBtn = '<button title="Copy Payment LINK" class="copyBtn" txt="' +
                        value.link + '">Copy</button>';
                    apptTr += '<tr><td>' + key + '.</td><td>' + value.order_id +
                        '</td><td class="' + payCls + '"><strong>' + paySts +
                        '</strong></td><td>' + totPay + '</td><td>' + cpyBtn + '</td><td>' +
                        crtDate + '</td></tr>';
                });
                console.log(apptTr);
                $("#patApptModal").find(".upperTr").append(apptTr);
                jQuery('.loading-all').hide();
                $("#patApptModal").modal("show");
            } else {
                apptTr += '<tr><td colspan="8">No Record Found..</td></tr>';
                $("#patApptModal").find(".upperTr").append(apptTr);
                jQuery('.loading-all').hide();
                $("#patApptModal").modal("show");
            }
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            if (error.status == 401 || error.status == 419) {} else {}
        }
    });
});

function viewOrderDetails(id) {
    jQuery("#viewOrderDetails").html('');
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.viewOrderDetails')!!}",
        data: {
            "_token": "{{ csrf_token() }}",
            'orderId': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            console.log(data);
            jQuery("#viewOrderDetails").html(data);
            jQuery('#viewOrderDetails').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}
jQuery(document).on("change", ".orderSts", function() {
    var val = $(this).val();
    if (val) {
        var delivery_date = $(".delivery_date").val();
        if (delivery_date) {
            var orderId = jQuery("input[name='orderId']").val();
            jQuery('.loading-all').show();
            jQuery.ajax({
                type: "POST",
                url: "{!! route('admin.changeOrderSts')!!}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'order_status': val,
                    'orderId': orderId
                },
                success: function(data) {
                    jQuery('.loading-all').hide();
                    if (data == 1) {
                        if (val == "3") {
                            $(".orderSts").hide();
                            $(".stsDone").show();
                        }
                        $.alert("Status changed successfully..");
                    }
                },
                error: function(error) {
                    jQuery('.loading-all').hide();
                    $.alert("Oops Something goes Wrong.");
                }
            });
        } else {
            $('.orderSts option[value="0"]').prop('selected', 'selected');
            alert("Delivery date is required...");
        }
    }
});
jQuery(document).on("click", ".presBtn", function() {
    if ($(".recordsDiv").hasClass("active")) {
        $(".recordsDiv").removeClass("active");
        $(".recordsDiv").slideUp();
    } else {
        $(".recordsDiv").addClass("active");
        $(".recordsDiv").slideDown();
    }
});
jQuery(document).on("click", ".apptType", function() {
    var appt = $(this).val();
    $('.payment_mode_type option[value=""]').prop('selected', 'selected');
    if (appt == 1) {
        $(".medBlock").hide();
        jQuery("form[name='completeOrderForm']").find(".switchBtn").show();
    } else if (appt == 2) {
        $(".medBlock").show();
        jQuery("form[name='completeOrderForm']").find(".switchBtn").show();
    }
});
jQuery(document).on("change", ".payment_mode_type", function() {
    var type = $(this).find('option:selected').val();
    $(".txnId").hide();
    if (type == '1') {
        $(".txnId").show();
    }
});

</script>
@endsection