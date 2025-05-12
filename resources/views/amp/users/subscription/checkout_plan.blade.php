@extends('amp.layouts.Masters.Master')
@section('title', 'Checkout | Health Gennie')
@section('description', "Chhose your plan according to your needs, apply referral code to get discounts and place your order.")
@section('content')
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
	@include('users.sidebar')
	<div class="dashboard-right">
		<div class="right-section new-tabs-section checkoutCart">
		 <div class="right-block">
			<div class="container-fluid">
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
							<form action="{{route('checkOutUserPlan',['id' => base64_encode($plan->id)])}}" method="POST" class="form-horizontal">
							@csrf
							<div class="panel-body cartinnerItem">
							  <?php $subtotal = 0; $total=0;?>
							  <input type="hidden" name="plan_id" value="{{$plan->id}}" id="plan_id"/>
										<div class="row scartPart">
											<div class="col-xs-10 col-sm-10 cartinnerItemName">
												<h4 class="product-name">{{$plan->plan_title}}</h4>
												<div class="Subscription_Plan">Subscription Plan</div>
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
								<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;display:none;" id="coupon_rate_hide">
								  <h4 class="text-right"><span class="checkoutAmt col-sm-10">Coupon Discount</span> <strong class="col-sm-2"><span>‎- ₹</span><span id="coupon_rate"></span></strong></h4>
								</div>
								<!--<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;">
								  <h4 class="text-right"><span class="checkoutAmt col-sm-10">SGST(9%)</span> <strong class="col-sm-2"><span>‎₹</span><span class="sgst_tax">{{(($subtotal*18)/100)/2}}</span></strong></h4>
								  <input type="hidden" name="tax" value="{{($subtotal*18)/100}}" />
								</div>
								<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;">
								  <h4 class="text-right"><span class="checkoutAmt col-sm-10">CGST(9%)</span> <strong class="col-sm-2"><span>‎₹</span><span class="cgst_tax">{{(($subtotal*18)/100)/2}}</span></strong></h4>
								</div>-->
								  <input type="hidden" name="tax" value="0" />
											<div class="col-xs-10 col-sm-12 pull-left pull-left2" style="padding:0;">
												<h4 class="text-right"><span class="checkoutAmt col-sm-10">Total amount to pay</span> <strong class="col-sm-2"><span>‎₹</span><span class="after_coupon_rate">{{$total}}</span></strong></h4>
								  <input type="hidden" name="before_order_total" value="{{$total}}" class="before_coupon_rate"/>
								  <input type="hidden" name="order_total" value="{{$total}}" class="after_coupon_rate"/>
											</div>
											
											<div class="ref-code-screen save-block Apply_healthGennie">
											<div class="form-address-details CouponBox divForHide">
											  <div class="input-box">
												<input type="text" placeholder="Enter Referral Code" class="couponInput" id="couponInputCode" value="" />
												  <input type="hidden" name="ref_code" value="" class="ref_code_applied"/>
											  </div>
											  <button id="coupanApply" type="button" class="btn-add-address">Apply</button>
											  <strong class="CouponAvailableMsg" style="display:none;"></strong>
											</div>
											<div class="coupanApplyedBox" style="display:none;">
											  <div class="save-icon"><img width="13" height="14" src="{{asset('img/right-icon.png')}}" />Congrats! Referral code applied.</div>
											  <div class="remove-icon"><a href="javascript:void(0)" class="removeCoupan">Remove</a> </div>
											</div>
										  </div>
							  
											<div class="col-xs-2 col-sm-12 pull-left checkBtnM">
												 <button name ="submit" type="submit" class="btn btn-success updateDoctorBtn pull-right">Place Order</button>
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
	</div>
</div>
<script>
$("#couponInputCode").on("keyup", function(){
	$('.CouponAvailableMsg').slideUp();
	$('.CouponAvailableMsg').text('');
	$('.CouponAvailableMsg').css("color", "");
});

jQuery(document).on("click", "#coupanApply", function () {
	var ref_code = $('#couponInputCode').val();
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
	$('#couponInputCode').val('');
	$('.divForHide').slideToggle();
	$('.coupanApplyedBox').slideToggle();
});
function ApplyReferralCode(ref_code) {
  jQuery('#coupanApply').attr('disabled',true);
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('ApplyReferralCode') !!}",
  data: {'ref_code':ref_code},
  success: function(data){
		if (data > 0) {
			$('.coupanApplyedBox').find('.applyCouponCode').text(ref_code);
			$('.ref_code_applied').val(ref_code);
			$('.divForHide').slideUp();
			$('.coupanApplyedBox').slideDown();
		}
		else{
			$('.CouponAvailableMsg').text('Referral code not matched');
			$('.CouponAvailableMsg').css("color", "red");
			$('.CouponAvailableMsg').slideDown();
		}
      jQuery('#coupanApply').attr('disabled',false);
    },
    error: function(error) {
      if(error.status == 401) {
          location.reload();
      }
      else
      {
        jQuery('.loading-all').hide();
        jQuery('#coupanApply').attr('disabled',false);
      }
    }
  });
}
</script>
@endsection