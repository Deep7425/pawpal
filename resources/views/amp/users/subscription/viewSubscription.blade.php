@extends('amp.layouts.Masters.Master')
@section('title', 'My Subscriptions | Health Gennie')
@section('content')
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
@include('users.sidebar')
<div class="dashboard-right">
    <div class="container-inner my-subscription-plans">
      <div class="registration-wrap user-info profile-exam">
        <div class="subscription-header">
          <p class="subs-heading">Subscription Details</p>
          <p class="subs-date">Date : <strong>{{date('F d Y g:i A',strtotime($UsersSubscriptions->created_at))}}</strong></p>
        </div>
        <div class="subscription-header-2">
          @if(!empty($UsersSubscriptions->UserSubscriptionsTxn))<p class="subs-heading">Transaction No. : <strong>{{@$UsersSubscriptions->UserSubscriptionsTxn->tracking_id}}</strong> </p>@endif
          <p class="subs-date">Payment Status : <strong>@if($UsersSubscriptions->order_status == "0") pending @elseif($UsersSubscriptions->order_status == "1") completed @elseif($UsersSubscriptions->order_status == "2") Cancelled @elseif($UsersSubscriptions->order_status == "3") Failure Transaction @endif</strong></p>
        </div>
        <div class="subcribed-plans">
          <table class="table">
            <thead>
              <tr>
                <th>Name</th>
				<th>Duration</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Appointments</th>
				<th>Lab Package</th>
				<th>Price</th>
				<th>Discount</th>
			</tr>
            </thead>
            <tbody>
              @if($UsersSubscriptions->UserSubscribedPlans->count() > 0)
				@foreach($UsersSubscriptions->UserSubscribedPlans as $index => $plan)
				@php $plan_data = json_decode($plan->meta_data); @endphp
				  <tr>
					<td>{{@$plan_data->plan_title}}</td>
					<td>{{@$plan_data->plan_duration}}@if($plan_data->plan_duration_type == "d") Day @elseif($plan_data->plan_duration_type == "m") Month @elseif($plan_data->plan_duration_type == "y") Year @endif</td>
					<td>{{date('d-m-Y',strtotime(@$plan->PlanPeriods->start_trail))}}</td>
					<td>{{date('d-m-Y',strtotime(@$plan->PlanPeriods->end_trail))}}</td>
					<td>{{@$plan->PlanPeriods->remaining_appointment}}</td>
					<td>{{@$plan_data->lab_pkg_title}}</td>
					<td>₹ {{@$plan_data->price}}</td>
					<td>₹ {{@$plan_data->discount_price}}</td>
				</tr>
				@endforeach
				@else
					<tr><td colspan="9">No Record Found</td></tr>
				@endif
            </tbody>
          </table>
			@if($UsersSubscriptions->order_status == '1' && $UsersSubscriptions->PlanPeriods->lab_pkg_remaining == '1')
			<div class="avail-plan">
				<div class="view-icon avail-time-plan">
					<a href="{{route('AvailLabCart',['code'=>base64_encode(@$plan_data->lab_pkg),'plan_id'=>base64_encode(@$UsersSubscriptions->PlanPeriods->id)])}}" class="label-default label label-success">View full body Check</a>
					<div id="lightbox-tooltip" class="data-pack-lab">
						 <div class="lab-test Lab_Test_Details single-lab-detail">
							<div class="package-box_lab">
							   <?php
							   $pkg_data = availPackDetails($plan_data->lab_pkg);
								$groups = array();
								if(!empty($pkg_data) && count($pkg_data->childs) > 0){
									foreach($pkg_data->childs as $element) {
										if($element->group_name != "SUBSET") {
											$groups[$element->group_name][] = $element;
										  }
									}
								}
								?>
								@if(count($groups) > 0)
									@foreach($groups as $group => $tests)
									<div class="toggle-wrapper">
										<button onclick="myFunction(this)">{{$group}}({{count($tests)}})</button>	
										  <div class="toggle-hg">
											  @foreach($tests as $child)									  
											  <div class="toggle-wrapper-content" style="display:none;">
												<div class="package-box">
													<div class="lab-test-block-img">
														<img src="{{ URL::asset('img/lab2-icon.png') }}" />
													</div>
													<div class="lab-test-block">
														<h3>{{$child->name}}</h3>
													</div>
												</div>
											  </div>
											  @endforeach
										  </div>
										  
									  </div>
									@endforeach
								 @endif	
								 <div class="AvailFullBodyCheckDiv">
								 <a href="{{route('AvailLabCart',['code'=>base64_encode(@$plan_data->lab_pkg),'plan_id'=>base64_encode(@$UsersSubscriptions->PlanPeriods->id)])}}" class="label-default label label-success">Avail full body Check</a>
							</div>
							</div>
						</div>         
					</div>
			   </div>
                
            </div>
			@endif	
        </div>
        <div class="subs-trans-info">
          <div class="subs-trans">
            <p class="sub-date">User Info </p>
            <p>{{@$UsersSubscriptions->User->first_name}} {{@$UsersSubscriptions->User->last_name}}</p>
            <p>{{@$UsersSubscriptions->User->mobile_no}}</p>
            <p>{{@$UsersSubscriptions->User->email}}</p>
          </div>
          <div class="subs-trans">
            <p class="trans-heading"><span>Payment Mode : </span><strong> @if($UsersSubscriptions->payment_mode == "1") Online Payment @elseif($UsersSubscriptions->payment_mode == "2") Cheque @elseif($UsersSubscriptions->payment_mode == "3") Cash @endif</strong> </p>
            <p class="trans-heading"><span>Total Amount :</span> <strong> ₹ {{$UsersSubscriptions->order_subtotal}}</strong></p>
            <p class="trans-heading"><span>Price Discount :</span>  <strong> - ₹ @if(!empty($UsersSubscriptions->discount_price)) {{$UsersSubscriptions->discount_price}} @else 0.00  @endif</strong></p>
            <p class="trans-heading"><span>Tax :</span>  <strong> ₹ {{$UsersSubscriptions->tax}}</strong></p>
            <p class="trans-heading"><span>Paid Amount :</span>  <strong> ₹ {{$UsersSubscriptions->order_total}}</strong></p>
          </div>
        </div>
      </div>
    </div>
    </div>
 </div>
<script>
function myFunction(current) {
	$('.toggle-wrapper').removeClass("chooseEle");
	$(current).closest('.toggle-wrapper').addClass("chooseEle");
	$('.toggle-wrapper').each(function(){
		if(!$(this).hasClass('chooseEle')) {
			$(this).find('.toggle-wrapper-content').slideUp();
		}
	});
	$(current).closest('.toggle-wrapper').find('.toggle-wrapper-content').slideToggle();
}
</script>
@endsection
