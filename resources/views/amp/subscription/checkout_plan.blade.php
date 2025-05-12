@extends('amp.layouts.Masters.Master')
@section('title', 'Subscription Plan')
@section('content') 
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
										<h5><span class="glyphicon glyphicon-shopping-cart"></span> Checkout</h5>
									</div>
								</div>
							</div>
						</div>
						@if(!empty($plan))
					<form action="{{route('checkoutPlan')}}" method="POST" class="form-horizontal">
						@csrf
						<input type="hidden" name="doc_id" value='{{@$doc_id}}'/>
						<div class="panel-body cartinnerItem">
						  <?php $subtotal = 0; $total=0;?>
						  <input type="hidden" name="plan_id" value="{{$plan->id}}" id="plan_id"/>
									<div class="row scartPart">
										<div class="col-xs-10 col-sm-10 cartinnerItemName">
											<h4 class="product-name">{{$plan->plan_title}}</h4><!--<h4 class="descProd"><small>{{$plan->other_text}}</small></h4>-->
							  <div style="color:#189ad4; padding:0; font-weight:normal; font-size:13px;">@if($plan->plan_type==1)Subscription Plan @else Add-ons @endif</div>
										</div>
										<div class="col-xs-2 col-sm-2 cartinnerItemAmt">
											<div class="text-right pull-right">
												<h4 class="prdPrice"><span>‎₹</span><span class="after_coupon_ratee">{{$plan->plan_price - $plan->discount_price}}</span></h4>
											</div>
										</div>
									</div>
						  <?php $subtotal += $plan->plan_price - $plan->discount_price; ?>
						  <?php $total = $subtotal + ($subtotal*18)/100; ?>
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
							<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;">
							  <h4 class="text-right"><span class="checkoutAmt col-sm-10">SGST(9%)</span> <strong class="col-sm-2"><span>‎₹</span><span class="sgst_tax">{{(($subtotal*18)/100)/2}}</span></strong></h4>
							  <input type="hidden" name="tax" value="{{($subtotal*18)/100}}" />
							</div>
							<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;">
							  <h4 class="text-right"><span class="checkoutAmt col-sm-10">CGST(9%)</span> <strong class="col-sm-2"><span>‎₹</span><span class="cgst_tax">{{(($subtotal*18)/100)/2}}</span></strong></h4>
							</div>
										<div class="col-xs-10 col-sm-12 pull-left" style="padding:0;">
											<h4 class="text-right"><span class="checkoutAmt col-sm-10">Total amount to pay</span> <strong class="col-sm-2"><span>‎₹</span><span class="after_coupon_rate">{{$total}}</span></strong></h4>
							  <input type="hidden" name="before_order_total" value="{{$total}}" class="before_coupon_rate"/>
							  <input type="hidden" name="order_total" value="{{$total}}" class="after_coupon_rate"/>
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
<script>
$(document).bind("contextmenu",function(e) {
	 e.preventDefault();
});
$(document).keydown(function(e){
	  if(event.keyCode == 123) {
		 return false;
	  }
	  if(event.keyCode == 113) {
		 return false;
	  }
	  if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
		 return false;
	  }
	  if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
		 return false;
	  }
	  if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
		 return false;
	  }
	  if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
		 return false;
	  }
});
</script>
@endsection