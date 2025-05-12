@extends('layouts.Masters.Master')
@section('title', 'Checkout | Health Gennie')
@section('description', "Chhose your plan according to your needs, apply referral code to get discounts and place your order.")
@section('content')
<style>
.modal.fade:not(.in).right .modal-dialog {
    -webkit-transform: translate3d(25%, 0, 0);
    transform: translate3d(25%, 0, 0);
}
</style>

<?php 
$user = Auth::user();
$wLtamnt=0;
if($user->userDetails->wallet_amount){
	$wLtamnt=$user->userDetails->wallet_amount;	
}
  

?>

<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
	@include('users.sidebar')
	<div class="dashboard-right">
		<div class="right-section new-tabs-section checkoutCart">
		 <div class="right-block">
			<div class="container-fluid">
				<div class="container">	
                	<div class="row">
				<div class="col-xs-8 col-sm-12">
						<div class="panel panel-info cartPanel">
							<div class="panel-heading">
								<div class="panel-title">
									<div class="row">
										<div class="col-xs-6 col-sm-6">
											<h5><span class="glyphicon glyphicon-shopping-cart"></span>Checkout</h5>
										</div>
									</div>
								</div>
							</div>
							@if(!empty($plan))
							<form action="{{route('checkOutUserPlan',['id' => base64_encode($plan->id)])}}" method="POST" class="form-horizontal " name="checkOutUserPlan">
							@csrf
							<div class="panel-body cartinnerItem">
							  <?php $subtotal = 0; $total=0;?>
							  <input type="hidden" class="dob_feild" value="{{date('d-m-Y',@Auth::user()->dob)}}" />
							  <input type="hidden" name="dob" value="" />
							  <input type="hidden" name="dob_type" value="" />
							  <input type="hidden" name="isAppt" value="{{$tp}}"/>
							  <input type="hidden" name="plan_id" value="{{$plan->id}}" id="plan_id"/>
							  <input type="hidden" class="referral_user_id" name="referral_user_id" value="" id="referral_user_id"/>
										<div class="row scartPart">
											
											<div class="col-xs-7 col-sm-7 cartinnerItemName">
												<h4 class="product-name">{{$plan->plan_title}}</h4>
												<div class="plan-content">{!!$plan->content!!}</div>
											</div>
											<div class="col-xs-5 col-sm-5 ccModalcdDiv">
											<div class="ccModal cdDiv" role="button" aria-label="Apply Coupon"><img width="20" src="./img/discount-icon.png" /> Apply Coupon</div>
											<div class="removeDiv" style="display:none;"><span class="scd"></span>
												<p><span class="applyCouponText">Offer applied on the bill</span> You saved <strong>₹</strong><strong class="applyCouponAmount"></strong></p>
												<span class="rmvCd">Remove</span>
											</div>
											
											</div>


											<div class="col-xs-2 col-sm-2 cartinnerItemAmt">
												<div class="text-right pull-right">
													<h4 class="prdPrice"><span>‎₹</span><span class="after_coupon_ratee">{{$plan->price - $plan->discount_price}}</span></h4>
												</div>
											</div>
										</div>
							  <?php $subtotal += $plan->price - $plan->discount_price; ?>
							  <?php $total = $subtotal; ?>
									</div>
							<input type="hidden" name="order_subtotal" value="{{$subtotal}}" id="order_subtotal"/>
							<div class="panel-footer footer">
								<div class="text-center footerInner">
								<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;">
								  <h4 class="text-right"><span class="checkoutAmt col-sm-10">Subtotal</span> <strong class="col-sm-2"><span>‎₹</span><span >{{$subtotal}}</span></strong></h4>
								  <input type="hidden" name="order_subtotal" value="{{$subtotal}}" />
								</div>
								<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;" id="coupon_rate_hide">
								  <h4 class="text-right"><span class="checkoutAmt col-sm-10">Coupon Discount</span> <strong class="col-sm-2"><span>‎- ₹ </span><span id="coupon_rate">0</span></strong></h4>
								</div>

								<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;" id="coupon_rate_hide">
								  <h4 class="text-right"><span class="checkoutAmt col-sm-10">@if($wLtamnt)<input type="checkbox" class="healthgennie_cash" name="healthgennie_cash" value='50'/> @endif Health Gennie Cash :</span> <strong class="col-sm-2"><span>‎₹ <span id="walletDiscountAmount">@if($wLtamnt) {{$wLtamnt}} @else 0.00 @endif</span></strong></h4>
								</div>
								<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;" id="coupon_rate_hide">
								  <h4 class="text-right"><span class="walletMessage"></span>
								</div>
								
								<!--<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;">
								  <h4 class="text-right"><span class="checkoutAmt col-sm-10">SGST(9%)</span> <strong class="col-sm-2"><span>‎₹</span><span class="sgst_tax">{{(($subtotal*18)/100)/2}}</span></strong></h4>
								  <input type="hidden" name="tax" value="{{($subtotal*18)/100}}" />
								</div>
								<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;">
								  <h4 class="text-right"><span class="checkoutAmt col-sm-10">CGST(9%)</span> <strong class="col-sm-2"><span>‎₹</span><span class="cgst_tax">{{(($subtotal*18)/100)/2}}</span></strong></h4>
								</div>-->
								  <input type="hidden" name="coupon_discount" value="0" />
								  <input type="hidden" name="tax" value="0" />
									<div class="col-xs-10 col-sm-12 pull-left pull-left2" style="padding:0;">
										<h4 class="text-right"><span class="checkoutAmt col-sm-10">Total amount to pay</span> <strong class="col-sm-2"><span>‎₹</span><span class="after_coupon_rate">{{$total}}</span></strong></h4>
										<input type="hidden" name="before_order_total" value="{{$total}}" class="before_coupon_rate"/>
                                        <input type="hidden" name="walletDiscountAmount" value="0" class="walletDiscountAmount"/>
										<input type="hidden" id="order_total" name="order_total" value="{{$total}}" class="after_coupon_rate"/>
										<input type="hidden" name="ref_code" value="" class="ref_code_applied"/>
									</div>
									<div class="planTrms">
									<div class="col-xs-2 col-sm-12 pull-left checkBtnM mobileTopBtn">
									 <button type="submit" class="btn btn-success updateDoctorBtn pull-right checkoutMYDivPaytm">Place Order</button>
									</div>
                                    <div class="col-xs-2 col-sm-12 pull-left checkBtnM DeshbordTopBtn">
										 <button type="submit" class="btn btn-success updateDoctorBtn pull-right checkoutMYDivPaytm">Place Order</button>
									</div>
									{!!getTermsBySLug('buy-plan-page-content-app','en')!!}
									
									</div>
									
								</div>
							</div>
						</form>		
						@endif
						</div>
					</div>
				</div>
                </div>
			</div>
		</div>
        
		<!--<div class="hg-club">
      	{!!getTermsBySLug('subscription-plan-page-content-bottomapp','en')!!}
      </div>-->	
	  </div>
	   
	</div>
</div>
<div class="modal fade right" id="ccModal" role="dialog" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
<div class="modal-header" data-dismiss="modal"><i class="fa fa-times"></i></div>
<div class="ClassInput1234">
  <div class="ClassInput">
		<input class="ClassInput12 form-control" type="text" name="" placeholder="Enter referral code" maxlength="50"/>
		<div class="ApplyBtn"><a class="ApplyBtn123">APPLY</a></div><label class="ArrowClass" style="display:none;">Referral code is required</label>
	</div>
</div>
<div class="modal-content">
  <div class="modal-body">
	<div class="termConditions">
		@forelse(getRefCodes() as $raw)
		<div class="coupon_code">{{$raw['code']}}</div>
		<div class="codeApplyClass"><button class="btn btn-info codeApply" cde_="{{$raw['code']}}">Apply</button></div>
		<div class="ts">
		{!!$raw['term_conditions']!!}
		</div>
		@empty
		<div class="notFound"><img src="../img/coupon-icon.png" /> Coupons Not Available
			<p>Offers will be available soon.</p>
		</div>
		@endforelse
	</div>
  </div>
</div>
</div>
</div>
<script>
$(document).ready(function(){
	$("#isPaytmTab").val(isPaytmTab);
});
jQuery(document).on("click", ".cdDiv", function () {
$("#ccModal").modal("show");
});
jQuery(document).on("click", ".rmvCd", function () {
	$(".removeDiv").hide();
	$(".cdDiv").show();
	removeCode();
});
jQuery(document).on("click", ".ApplyBtn123", function () {
	if(actionPerform($(".ClassInput12").val())){
		ApplyReferralCode($(".ClassInput12").val());	
	}
});
jQuery(document).on("keyup", ".ClassInput12", function () {
	actionPerform($(this).val());
});
function actionPerform($val){
	var flag = false;
	if($val) {
		var flag = true;
		$(".ArrowClass").hide();
	}
	else{
		$(".ArrowClass").text('Referral code is required.');
		$(".ArrowClass").show();
		$(".ClassInput12").focus();
	}
	return flag;
}
jQuery(document).on("click", ".codeApply", function () {
	var cde_ = $(this).attr("cde_");
	ApplyReferralCode(cde_);
});
function removeCode() {
	$('#coupon_rate').text("0");
	var before_coupon_rate = $(".before_coupon_rate").val();
	$('.after_coupon_rate').text(before_coupon_rate);
	$('.after_coupon_rate').val(before_coupon_rate);
}
function codeAfterApplied(cde_){
$(".cdDiv").hide();
$(".removeDiv").find(".scd").text(cde_);
$(".removeDiv").show();
$("#ccModal").modal("hide");
$(".ClassInput12").val('');
}
function ApplyReferralCode(ref_code) {
  jQuery('.coupanApply').attr('disabled',true);
  var plan_id = jQuery("#plan_id").val();
   jQuery('.loading-all').show();
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('ApplyReferralCode') !!}",
  data: {'ref_code':ref_code,'plan_id':plan_id},
  success: function(data){
		if (data.success == 1) {
			verifyRefcode(data);
		}
		else{
			$(".ArrowClass").text('Referral code not matched');
			$(".ArrowClass").show();
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
function verifyRefcode(data){
	$('.ref_code_applied').val(data.ref_code);
	var before_coupon_rate = $(".before_coupon_rate").val();
	var walletamnt=$('.walletDiscountAmount').val();
	
	coupanDiscountAmount = data.coupon_discount;
	$('#coupon_rate').text(coupanDiscountAmount);
	var after_coupon_rate =  before_coupon_rate - coupanDiscountAmount; 
	if(walletamnt!=''){
	var after_coupon_rate=	after_coupon_rate-walletamnt;
	$('.after_coupon_rate').text(after_coupon_rate);
	$('.after_coupon_rate').val(after_coupon_rate);
	}else{
	$('.after_coupon_rate').text('0');
	$('.after_coupon_rate').text(after_coupon_rate);
	$('.after_coupon_rate').val('0');
	$('.after_coupon_rate').val(after_coupon_rate);
	}

	$('.referral_user_id').val(data.referral_user_id);
	$('.removeDiv').find('.applyCouponAmount').text(coupanDiscountAmount);
	$("form[name='checkOutUserPlan']").find('input[name=coupon_discount]').val(coupanDiscountAmount);
	codeAfterApplied(data.ref_code);
}
$(document).ready(function(){
if(isPaytmTab){
	$("form[name='checkOutUserPlan']").submit(function(e) { 
    e.preventDefault();
}).validate({
// Specify the validation rules
rules: {
	// age: "required",
},
messages: {
},
errorPlacement: function(error, element) {
	 error.appendTo(element.next());
},ignore: ":hidden",
submitHandler: function(form) {
	  jQuery('.loading-all').show();
	  jQuery('.subbtn').attr('disabled',true);
	  jQuery.ajax({
	  type: "POST",
	  dataType : "JSON",
	  url: "{!!route('checkOutUserPlanPaytm')!!}",
	  data:  new FormData(form),
	  contentType: false,
	  // cache: false,
	  processData:false,
	  success: function(result) {
			console.log(result);
			if(result.status == '1') {
				const requestObject={
				  "amount": atob(result.amount),
				  "orderId": atob(result.order_id),
				  "txnToken": atob(result.txnToken),
				  "mid": atob(result.MID),
				}
				//alert(atob(result.txnToken));
				var myOrderid = atob(result.order_id);
				console.log(requestObject);
				jQuery('.loading-all').hide();
				function ready (callback) {
					//alert("success");
				if(window.JSBridge) {
				   callback && callback();
				   } else{
				  document.addEventListener('JSBridgeReady', callback, false);
				}}
				ready(function () { //console.log('kapssss');
				 JSBridge.call('paytmPayment',requestObject,
				  function(result) {
				   console.log(result);
				   //var payResult = JSON.stringify(result);
				   if(result.data == false){
					   jQuery.ajax({
						type: "POST",
						url: '{!! url("paymentcancelMiniProgramPlan") !!}',
						data: {'id':myOrderid},
						success: function(data){
							location.reload();
						}
					   });
				   }else{
					   jQuery.ajax({
						type: "POST",
						url: '{!! url("paytmResponseMIniAppPlan") !!}',
						data: {'data':result.data},
						success: function(data){
							console.log(data);
							location.href = data;
						}
					});
				   }
				  });
				});
			}
			else{
				jQuery('.loading-all').hide();
				jQuery('.subbtn').attr('disabled',false);
				alert('Oops Something goes Wrong.');
			}
		},
		error: function(error) {
			jQuery('.loading-all').hide();
			jQuery('.subbtn').attr('disabled',false);
			alert('Oops Something goes Wrong.');
		}
	 });
  }
});
}
var p_age = $('.dob_feild').val();
if(p_age !=""){getPatientAgeAdd(p_age);}
function getPatientAgeAdd(dob) {
	var p_age = showAge(dob);
	if(p_age != "") {
		var age_number = p_age.split(',');
		$("form[name='checkOutUserPlan']").find('input[name=dob]').val(age_number[0]);
		if(age_number[1] == "d"){
			$("form[name='checkOutUserPlan']").find('input[name=dob_type]').val(3);
		}
		else if(age_number[1] == "m"){
			$("form[name='checkOutUserPlan']").find('input[name=dob_type]').val(2);
		}
		else if(age_number[1] == "y"){
			$("form[name='checkOutUserPlan']").find('input[name=dob_type]').val(1);
		}
	}
}
});

function Applywalletcoupon(walletCode) {
 // jQuery('#coupanApply').attr('disabled',true);
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('Applywalletcoupon') !!}",
  data: {'walletCode':walletCode,'isDirect':'1'},
  success: function(data){
		if (data.status == '1') {  
		    $('.walletDiscountAmount').val(data.wallet_amount);
			$('#walletDiscountAmount').text(data.wallet_amount);
			if($('.after_coupon_rate').val()){
            var after_coupon_rate= $('.after_coupon_rate').val();
			}else{
            var after_coupon_rate= $('.after_coupon_rate').text();
			}
		
			 console.log("===========--900909",after_coupon_rate);

			 after_coupon_rate= after_coupon_rate-data.wallet_amount;
			$('.after_coupon_rate').text(after_coupon_rate);
			$('.after_coupon_rate').val(after_coupon_rate);
		    $('#couponCode').val(data.referral_code);
            $('.walletAvailableMsg').text(data.msg);
			$('.walletAvailableMsg').css("color", "green");
			$('.walletAvailableMsg').slideDown();
           $('#Applywalletcoupon').prop('disabled', true);
			
		 // paymentCalculate(2);

		}
		else{
			$('.walletAvailableMsg').text('Invalid Or Expired Coupan Code');
			$('.walletAvailableMsg').css("color", "red");
			$('.walletAvailableMsg').slideDown();
		}
      jQuery('#coupanApply').attr('disabled',false);
    },
    error: function(error)
    {
      if(error.status == 401)
      {
        //  alert("Session Expired,Please logged in..");
          location.reload();
      }
      else
      {
        jQuery('.loading-all').hide();
       // alert("Oops Something goes Wrong.");
        jQuery('#coupanApply').attr('disabled',false);
      }
    }
  });
}
jQuery(document).on("click", "#Applywalletcoupon", function () {
	var walletCode = $('#walletcode').val();
  console.log(walletCode);
	if (walletCode != "") {
		Applywalletcoupon(walletCode);
	}
	else{
		$('.walletAvailableMsg').text('please enter Coupon Code');
		$('.walletAvailableMsg').css("color", "red");
		$('.walletAvailableMsg').slideDown();
	}
});


function paymentCalculate(type, currentRow) {
var reportHardCopy = $(".report_type:checked").val();
var totalAmount = atob($('#totalAmount').val());
var priceDiscount = atob($('#priceDiscount').val());
var paidAmount = atob($('#paidAmount').val());
var paidAmount2 = atob($('#paidAmount2').val());
var coupanDiscount = atob($('#coupanDiscount').val());

var coupanDiscountAmount = atob($('#coupanDiscountAmount').val());
var walletDiscountAmount = atob($('#walletDiscountAmount').val());

$('#paidAmount').val(btoa(paidAmount.toFixed(2)));
$('.paidAmount').text(paidAmount.toFixed(2));
}

$('input[type="checkbox"]').on('change', function() {
  if($(this).is(":checked")) {
   var wallet_amount='{{$wLtamnt}}';
   ajaxReq = jQuery.ajax({
   type: "POST",
   dataType : "JSON",
   url: "{!! route('applyWalletAmt') !!}",
   data: {'type':1,'wallet_amount':wallet_amount},
   success: function(data){
	  if(data.success){
		  //verifyCode(data);
		 var total_fee= $('#order_total').val();

		  var final_amount=total_fee-data.success.availAmount;

		  alert(final_amount);
		 
		  $('#order_total').val(btoa(final_amount));
		  
      //$('#paidAmount2').val(btoa(final_amount));
      $('.after_coupon_rate').text(final_amount);
		  $('.walletMessage').html('<b style="color:green">Health Gennie Cash &#x20b9;'+data.success.availAmount+' Applied On This Order</b>');
		  $('.walletDiscountAmount').val(data.success.availAmount);
	  }
	  else if(data.status == '0'){
		$(".ArrowClass").text(data.msg);
		$(".ArrowClass").show();
	  }
    },
    error: function(error){
      if(error.status == 401){
          location.reload();
      }
      else{
        jQuery('.loading-all').hide();
      }
    }
  });
    }else{

		var total_fee=$('#order_total').val();
		  var walletamt= $('.walletDiscountAmount').val();
		  var final_amount=parseInt(atob(total_fee)) + parseInt(walletamt);;
		  $('#order_total').val(final_amount);
          $('.after_coupon_rate').text(final_amount);
		  $('.walletDiscountAmount').val();
		  $('.walletMessage').html('').delay("slow").fadeIn();;
	}
});

</script>
@endsection