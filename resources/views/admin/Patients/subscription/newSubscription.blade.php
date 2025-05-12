@extends('layouts.admin.Masters.Master')
@section('title', 'New Subscription')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css" rel="stylesheet"/>

<div class="layout-wrapper layout-2">
     <div class="layout-inner">
        <div class="layout-container" style ="padding-top: 0px !important;">
                <div class="container-fluid flex-grow-1 container-p-y data-list">

				<div class="row form-top-row">
				<div class="col-sm-3 btn-group pad-0">
					<div class="btn-group"> 
                     <a class="btn btn-primary" href="{{ route('symptoms.SymptomsMaster') }}"> <i class="fa fa-list"></i>  Symptoms List</a>  
                   </div>
				</div>
			</div>
			<div class="table-responsive mar-b15">
				<table class="table table-bordered table-hover">
					<div class="form-group111"><h3>User Info:</h3></div>
							<thead>
								<tr>
									<th>Name</th>
									<th>Gender</th>
									<th>E-Mail</th>
									<th>Mobile No.</th>
								</tr>
							</thead>
							<tbody>			  
								<tr>
									<td>{{@$user->first_name}} {{@$user->last_name}}</td>
									<td>{{@$user->gender}}</td>
									<td>{{@$user->email}}</td>
									<td style="text-align: left;">{{@$user->mobile_no}}</td>
								</tr>
							</tbody>		  
						</table>
					</div>
				<div class="layout-content card user-data-form">
				
				{!! Form::open(array( 'id' => 'addSubscription', 'class' => 'col-sm-12 newSubscri')) !!}
			    <div class="row">	
				               <input type="hidden" name="user_id" value="{{$user->id}}">
								 <div class="form-group111 ml-2" style="width:100%; float:left;"><h3>Plan Info:</h3></div>
								
								 <div class="form-group col-sm-3">
									<label>Plan</label>
									<select class="form-control selectGenniePlan" name="plan_id">
										<option value="">Select Plan</option>
										@foreach(getGenniePlan() as $plan)
											<option value="{{$plan->id}}" price="{{$plan->price}}" discount_price="{{$plan->discount_price}}" plan_duration="{{$plan->plan_duration}}" appointment_cnt="{{$plan->appointment_cnt}}" plan_duration_type="{{$plan->plan_duration_type}}" lab_pkg="{{$plan->lab_pkg}}" specialist_appointment_cnt="{{$plan->specialist_appointment_cnt}}" >{{$plan->plan_title}} - ({{number_format($plan->price - $plan->discount_price,2)}})</option>
										@endforeach
									</select>
									<span class="help-block">
									</span>
								</div>

								<div class="form-group col-sm-3">
									<label>Appointments:</label>
									<input type="text" class="form-control" placeholder="Appointments Count" name="appointment_cnt" readonly >
									<span class="help-block">
									</span>
								</div>

								<div class="form-group col-sm-3">
									<label>Specialist Appointments:</label>
									<input type="text" class="form-control" placeholder="Specialist Appointments Count" name="specialist_appointment_cnt" readonly />
									<span class="help-block">
									</span>
								</div>

								<div class="form-group col-sm-3">
									<label>Plan Duration:</label>
									<input type="text" class="form-control" placeholder="Plan Duration" name="plan_duration" readonly />
									<span class="help-block">
									</span>
								</div>


								<div class="form-group col-sm-3">
									<label>Plan Duration Type:</label>
									<select class="form-control plan_duration_type" name="plan_duration_type" disabled >
										<option value="">Select Duration Type</option>
										<option value="d">Day</option>
										<option value="m">Month</option>
										<option value="y">Year</option>
									</select>
									<span class="help-block">
									</span>
								</div>

								<div class="form-group col-sm-3 select-date">
									<label>Select date</label>
									<div class="input-group date">
									   <input class="yr" type="text" autocomplete="off" value='{{date("Y-m-d")}}' class="form-control subcribedate" name="subcribedate" readonly />
									   <input class="nt" type="text" autocomplete="off" style="text-transform: uppercase; " value='{{strtoupper(date("h:i:sa"))}}' id="subcribetime" name="subcribetime" />
									   <span class="input-group-addon subcribe_date"> <i class="fa fa-calendar ml-2 mt-2" aria-hidden="true"></i>
									   </span>
									</div>
								</div>


								
								<div class="form-group111 ml-2" style="width:100%; float:left;"><h3>Payment Info:</h3></div>

								
									<div class="form-group col-sm-3">
										<label>&nbsp;</label>
										<div class="ref-code-screen save-block Apply_healthGennie">
											<div class="form-address-details CouponBox divForHide">
													<div class="input-box">
															<input type="text" placeholder="Enter Referral Code" class="couponInput couponInputCode" id="couponInputCode" value="" />
															<input type="hidden" name="ref_code" value="" class="ref_code_applied"/>
													</div>
												<button id="coupanApply" type="button" class="btn-add-address coupanApply">Apply</button>
												<strong class="CouponAvailableMsg" style="display:none;"></strong>
											</div>
											<div class="coupanApplyedBox" style="display:none;">
												<div class="save-icon"><img width="13" height="14" src="{{asset('img/right-icon.png')}}" />Congrats! Referral code applied.</div>
												<div class="remove-icon"><a href="javascript:void(0)" class="removeCoupan">Remove</a> </div>
											</div>
								  		</div>
								  <input type="hidden" value="0" name="order_subtotal" class="before_coupon_rate" readonly />
								  <input type="hidden" value="0" readonly name="coupon_discount" />
								  <input type="hidden" value="0" readonly name="referral_user_id" />
								</div>

								<div class="form-group col-sm-3">
									<label>Payble Amount (INR):</label>
									<input type="text" class="form-control after_coupon_rate" readonly placeholder="Payble Total" name="order_total">
									<span class="help-block">
									</span>
								</div>
								<div class="form-group col-sm-3">
									<label>Payment Mode:</label>
									<select class="form-control payment_mode_type" name="payment_mode">
										<option value="">Select Payment Mode</option>
										<option value="3">Cash</option>
										<option value="4">Online</option>
										<option value="2">Cheque</option>
                                        <option value="5">Free</option>
										<option value="6">Payment Link</option>
									</select>
									<span class="help-block">
									</span>
								</div>

								<div class="form-group col-sm-3 trackBlock">
									<label>Txn Id:</label>
									<input type="text" class="form-control" placeholder="Txn Id received from paytm payments" name="tracking_id">
									<span class="help-block">
									</span>
								</div>

								<div class="form-group row payment_mode_cheque" style="display:none;">
								<div class="form-group col-sm-3">
									<label>Cheque Number:</label>
									<input type="text" class="form-control" placeholder="Cheque Number" name="cheque_no">
									<span class="help-block">
									</span>
								</div>
								<div class="form-group col-sm-3">
									<label>Cheque Payee Name:</label>
									<input type="text" class="form-control" placeholder="Cheque Payee Name" name="cheque_payee_name">
									<span class="help-block">
									</span>
								</div>
								<div class="form-group col-sm-3">
									<label>Cheque Bank Name:</label>
									<input type="text" class="form-control" placeholder="Cheque Bank Name" name="cheque_bank_name">
									<span class="help-block">
									</span>
								</div>
								<div class="form-group col-sm-3" >
									<label>Cheque Date:</label>
									<input type="text" class="form-control datepickerss" name="cheque_date" placeholder="dd-mm-YYYY" autocomplete="off">
									<span class="help-block">
									</span>
								</div>
								</div>
								
								<div class="form-group col-sm-3">
									<label>Sale By:</label>
									<select class="form-control" name="added_by">
										<option value="">Select Name</option>
										@forelse(getAdmins() as $raw)
										<option value="{{$raw->id}}" @if(Session::get('userdata')->id == $raw->id) selected @endif>{{$raw->name}}</option>
										@empty
										@endforelse
									</select>
									<span class="help-block">
									</span>
								</div>

								<div class="form-group col-sm-3">
									<label>Corporate</label>
									<select class="form-control" name="organization_id">
										<option value="">Select Name</option>
										@forelse(getOrganizations() as $raw)
										<option value="{{$raw->id}}" @if($user->organization == $raw->id) selected @endif >{{$raw->title}}</option>
										@empty
										@endforelse
									</select>
									<span class="help-block">
									</span>
								</div>
								<div class="form-group col-sm-6">
									<label>Remarks</label>
									<textarea class="form-control" placeholder="Remarks..." name="remark"></textarea>
									<span class="help-block"></span>
								</div>
								
								<div class="reset-button form-group col-sm-12">
								   <button type="reset" class="btn btn-warning">Reset</button>
								   <button type="submit" class="btn btn-success submit">Save</button>
								</div></div></div>
								{!! Form::close() !!}



			    </div>
                </div>
             </div>
         </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery( ".datepickerss" ).datepicker({
			dateFormat: 'dd-mm-yy',
			changeMonth: true,
			changeYear: true
		});
	});
	jQuery(document).on("change", ".selectGenniePlan", function () {
		var $option = $(this).find('option:selected');
		$('#addSubscription').find('input[name="price"]').val($option.attr('price'));
		$('#addSubscription').find('input[name="discount_price"]').val($option.attr('discount_price'));
		$('#addSubscription').find('input[name="plan_duration"]').val($option.attr('plan_duration'));
		$('#addSubscription').find('.plan_duration_type option[value='+$option.attr('plan_duration_type')+']').attr('selected','selected')
		$('#addSubscription').find('input[name="appointment_cnt"]').val($option.attr('appointment_cnt'));
		$('#addSubscription').find('input[name="specialist_appointment_cnt"]').val($option.attr('specialist_appointment_cnt'));
		$('#addSubscription').find('input[name="lab_pkg"]').val($option.attr('lab_pkg'));
		calculatePlanPayment($option);
		removeCoupanCode();
	});

	function calculatePlanPayment($option) {
		var subtotal = $option.attr('price') - $option.attr('discount_price');
		// var tax = (subtotal*18)/100;
		var total = subtotal;
		$('#addSubscription').find('input[name="order_subtotal"]').val(subtotal);
		// $('#addSubscription').find('input[name="tax"]').val(tax);
		$('#addSubscription').find('input[name="order_total"]').val(total);
		$('#addSubscription').find('.before_coupon_rate').val(total);
	}

	jQuery(document).on("keyup keypress paste", ".calculateAmount", function () {
		var subtotal_amount = parseFloat($(this).val()||0);
		// var tax = (subtotal_amount*18)/100;
		var total = subtotal_amount;
		// $('#addSubscription').find('input[name="tax"]').val(tax);
		$('#addSubscription').find('input[name="order_total"]').val(total);
	});

	jQuery(document).on("change", ".payment_mode_type", function () {
		var type = $(this).find('option:selected').val();
		$(".trackBlock").hide();
		$(".payment_mode_cheque").hide();
		if(type == '2'){
			$(".payment_mode_cheque").show();
		}
		else if(type == '4') {
			$(".trackBlock").show();
		}
	});
   // When the browser is ready...
	jQuery(document).ready(function () {
		jQuery("#addSubscription").validate({
	    rules: {
				user_id: "required",
				plan_id: "required",
				
				order_total: {required:true,number: true},
				// tracking_id: "required",
				cheque_no: "required",
				cheque_payee_name: "required",
				payment_mode: "required",
				added_by: "required",
				// organization_id: "required",

		},
	    // Specify the validation error messages
	    messages: {
				user_id: "Please enter user id",
				plan_id: "Please select plan",
	    },
		    errorPlacement: function(error, element) {
		         error.appendTo(element.next());
		      },ignore: ":hidden",
		    submitHandler: function(form) {
		      jQuery('.loading-all').show();
		      jQuery('.submit').attr('disabled',true);
			      jQuery.ajax({
				      type: "POST",
					  url: "{!! route('subscription.newSubscription')!!}",
					  data:  new FormData(form),
				      contentType: false,
				      cache: false,
				      processData:false,
				      success: function(res) {
				
				         if(res.type == 1 && res.data > 1) {
				          jQuery('.submit').attr('disabled',false);
							var cusrl = '{!! url("/admin/viewSubscription?id='+btoa(res.data)+'") !!}';
							window.location = cusrl;
						}else if (res.type == 2) {
						$.alert({
   						title: 'Success!',
   						content: 'Link Create Successfully. Please click button to copy link.',
   						draggable: false,
   						type: 'green',
   						typeAnimated: true,
   						buttons: {
   							Copy: function(){
   								copyText(res.data.link);
                                location.reload();
   							},
   							Cancel : function(){
                            location.reload();

   							}
   						}
   					  });
                   }else {
							alert("System Problem");
				         }
				         jQuery('.submit').attr('disabled',false);
				         jQuery('.loading-all').hide();
				       },
				       error: function(error){
				         jQuery('.submit').attr('disabled',false);
				         jQuery('.loading-all').hide();
				         alert("Oops Something goes Wrong.");
				       }
			    });
		    }
	    });

$("#couponInputCode").on("keyup", function(){
	$('.CouponAvailableMsg').slideUp();
	$('.CouponAvailableMsg').text('');
	$('.CouponAvailableMsg').css("color", "");
});

jQuery(document).on("click", ".coupanApply", function () {
	var ref_code = $('.couponInputCode').val();
	if (ref_code != "") {
		ApplyReferralCode(ref_code);
	}
	else{
		$('.CouponAvailableMsg').text('please enter Referral Code');
		$('.CouponAvailableMsg').css("color", "red");
		$('.CouponAvailableMsg').slideDown();
	}
});

jQuery(document).on("click", ".removeCoupan", function () {
	removeCoupanCode();
});

function ApplyReferralCode(ref_code) {
  var plan_id = jQuery(".selectGenniePlan").val();
  var user_id = jQuery("input[name='user_id']").val();
  if(plan_id) {
	  jQuery('.coupanApply').attr('disabled',true);
	  jQuery('.loading-all').show();
	  jQuery.ajax({
	  type: "POST",
	  dataType : "JSON",
	  url: "{!! route('ApplyReferralCodeAdmin') !!}",
	  data: {'ref_code':ref_code,'plan_id':plan_id,'user_id':user_id},
	  success: function(data){
			if (data.success == 1) {
				$('.coupanApplyedBox').find('.applyCouponCode').text(ref_code);
				$('.ref_code_applied').val(ref_code);
				var before_coupon_rate = $(".before_coupon_rate").val();
				 var coupanDiscountAmount = 0;
				  // if(data.referral_user_id == '38289'){
					  // coupanDiscountAmount = before_coupon_rate * data.coupon_discount / 100;
				  // }
				  // else{
					coupanDiscountAmount = data.coupon_discount;
				  // }
				var after_coupon_rate =  before_coupon_rate - coupanDiscountAmount;
				// $('.after_coupon_rate').text(after_coupon_rate);
				$('.after_coupon_rate').val(after_coupon_rate);
				jQuery("input[name='coupon_discount']").val(coupanDiscountAmount);
				jQuery("input[name='referral_user_id']").val(data.referral_user_id);
				$('.divForHide').slideUp();
				$('.coupanApplyedBox').slideDown();
			}
			else{
				$('.CouponAvailableMsg').text('Referral code not matched');
				$('.CouponAvailableMsg').css("color", "red");
				$('.CouponAvailableMsg').slideDown();
			}
			jQuery('.loading-all').hide();
		  jQuery('.coupanApply').attr('disabled',false);
		},
		error: function(error) {
		  if(error.status == 401) {
			  location.reload();
		  }
		  else
		  {
			jQuery('.loading-all').hide();
			jQuery('.coupanApply').attr('disabled',false);
		  }
		}
	  });
  }
  else{
	  alert("Please select plan.");
  }
}
});
function removeCoupanCode(){
	$('.couponInputCode').val('');
	$('#coupon_rate').text("0");
	var before_coupon_rate = $(".before_coupon_rate").val();
	$('.after_coupon_rate').val(before_coupon_rate);
	$('.divForHide').slideDown();
	$('.coupanApplyedBox').slideUp();
	jQuery("input[name='coupon_discount']").val('0');
	jQuery("input[name='referral_user_id']").val('');
}
function copyText(text) {
  var input = document.body.appendChild(document.createElement("input"));
  input.value = text;
  input.select();
  document.execCommand('copy');
  input.parentNode.removeChild(input);
}

$(".subcribedate").datepicker({
  changeMonth: true,
  changeYear: true,
  dateFormat: 'yy-mm-dd',
  format: 'H:i'
});

jQuery('.subcribe_date').click(function () {
	jQuery('.subcribedate').datepicker('show');
});
$(function(){
   $('#subcribetime').timepicker(); 
});
var times=$('input[name="subcribetime"]').val();
$('input[name="subcribetime"]').val(times);
</script>

@endsection