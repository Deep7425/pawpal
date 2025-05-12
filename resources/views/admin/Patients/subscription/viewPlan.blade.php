<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">View Subscription</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag feedback">
				<div class="pan-head mar-b15">
						<a class="btn btn-primary" href="javascript:void(0);">Subscription No. : {{$UsersSubscriptions->id}} </a>
						<a class="btn btn-primary status" href="javascript:void(0);">Order Status :@if($UsersSubscriptions->order_status == "0") pending @elseif($UsersSubscriptions->order_status == "1") completed @elseif($UsersSubscriptions->order_status == "2") Cancelled @elseif($UsersSubscriptions->order_status == "3") Failure Transaction @endif</a>
						<a class="btn btn-primary status" href="javascript:void(0);">Subscription Date : 
						{{date('F d Y g:i A',strtotime($UsersSubscriptions->created_at))}}</a>
				</div>
				<div class="panel-body">

			  <h3>Plans</h3>
			  <div class="SubscriptionSe">
			<div class="SubscriptionSe22">
			  <table class="table table-bordered table-hover">
				  <tr>
				    <th>Name</th>
				    <th>Duration</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Total Appointment</th>
					<th>Remaining Appointment</th>
					<th>Lab Package</th>
					<th>Price</th>
					<th>Discount</th>
				  </tr>
				  @if($UsersSubscriptions->UserSubscribedPlans->count() > 0)
					@foreach($UsersSubscriptions->UserSubscribedPlans as $index => $plan)
					  <tr>
						<td>{{@$plan->Plans->plan_title}}</td>
						<td>{{@$plan->plan_duration}}@if($plan->plan_duration_type == "d") Day @elseif($plan->plan_duration_type == "m") Month @elseif($plan->plan_duration_type == "y") Year @endif</td>
						<td>{{date('d-m-Y g:i A',strtotime(@$plan->PlanPeriods->start_trail))}}</td>
						<td>{{date('d-m-Y g:i A',strtotime(@$plan->PlanPeriods->end_trail))}}</td>
						<td>{{@$plan->appointment_cnt}}</td>
						<td>{{@$plan->PlanPeriods->remaining_appointment}}</td>
						<td>{{@$plan->lab_pkg}}</td>
						<td>{{@$plan->plan_price}}</td>
						<td>{{@$plan->discount_price}}</td>

					</tr>
					@endforeach
					@else
						<tr><td colspan="9">No Record Found </td></tr>
					@endif
			  </table>
			  </div>
			  </div>
			  <h3>Patient Details</h3>
			  <div class="SubscriptionSe">
			  <div class="SubscriptionSe22">
			  <table class="table table-bordered table-hover">
				  <tr>
					<th>Name</th>
					<th>Gender</th>
					<th>E-Mail</th>
					<th>Mobile No.</th>
				  </tr>
				  <tr>
					<td>{{@$UsersSubscriptions->User->first_name}} {{@$UsersSubscriptions->User->last_name}}</td>
					<td>{{@$UsersSubscriptions->User->gender}}</td>
					<td>{{@$UsersSubscriptions->User->email}}</td>
					<td>{{@$UsersSubscriptions->User->mobile_no}}</td>
				  </tr>
			  </table>
			  </div>
			  </div>
		  </div>
        <div class="PriceDiv">
        	<div class="SubscriptionSe1233">
			<div class="SubscriptionSe2211">
          <table class="table table-bordered table-hover">
          <tbody>
          	<tr>
          		<td style="width:140px;">Date</td>
          		<td style=" text-align:center;">:</td>
          		<td><strong> {{date('jS M, Y',strtotime($UsersSubscriptions->created_at))}}</strong></td>
          	</tr>
          	<tr>
          		<td style="width:140px;">Payment Mode</td>
          		<td style=" text-align:center;">:</td>
          		<td><strong> @if($UsersSubscriptions->payment_mode == "1") Online Payment @elseif($UsersSubscriptions->payment_mode == "2") Cheque @elseif($UsersSubscriptions->payment_mode == "3") Cash @elseif($UsersSubscriptions->payment_mode == "4") Online @endif</strong></td>
          	</tr>
          	<tr>
          		<td style="width:140px;">Total Amount</td>
          		<td style=" text-align:center;">:</td>
          		<td><strong> ₹ {{$UsersSubscriptions->order_subtotal}}</strong></td>
          	</tr>
          	<tr>
          		<td style="width:140px;">Price Discount</td>
          		<td style=" text-align:center;">:</td>
          		<td><strong> ₹ @if(!empty($UsersSubscriptions->discount_price)) {{$UsersSubscriptions->discount_price}} @else 0.00  @endif</strong></td>
          	</tr>
			<?php 
				$ref_code = "";
				if(!empty($UsersSubscriptions->ref_code)) {
					$ref_code = getRefCodeNameById($UsersSubscriptions->ref_code);
				}
				else if(!empty($UsersSubscriptions->meta_data)){
					$meta_data = json_decode($UsersSubscriptions->meta_data,true);
					if($UsersSubscriptions->added_by == "0"){
						$ref_code = @$meta_data['coupon_code'];
					}
					else{
						$ref_code = @$meta_data['ref_code'];
					}
				}
			?>
            <tr>
          		<td style="width:140px;">Referral Code</td>
          		<td style=" text-align:center;">:</td>
          		<td><strong>{{$ref_code}}</strong></td>
          	</tr>
			<tr>
          		<td style="width:140px;">Txn Id</td>
          		<td style=" text-align:center;">:</td>
          		<td><strong>{{@$UsersSubscriptions->UserSubscriptionsTxn->tracking_id}}</strong></td>
          	</tr>
			<tr>
          		<td style="width:140px;">Coupon Discount</td>
          		<td style=" text-align:center;">:</td>
          		<td><strong> ₹ @if(!empty($UsersSubscriptions->coupon_discount)) {{$UsersSubscriptions->coupon_discount}} @else 0.00  @endif</strong></td>
          	</tr>
          	<tr>
          		<td style="width:140px;">Tax</td>
          		<td style=" text-align:center;">:</td>
          		<td><strong>@if(!empty($UsersSubscriptions->tax)) ₹ {{$UsersSubscriptions->tax}}@endif</strong></td>
          	</tr>
          	<tr>
          		<td style="width:140px;">Paid Total</td>
          		<td style=" text-align:center;">:</td>
          		<td><strong> ₹ {{$UsersSubscriptions->order_total}}</strong></td>
          	</tr>
          </tbody>
          </table>
          </div>
          </div>
		</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
	</div>
</div>



<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
