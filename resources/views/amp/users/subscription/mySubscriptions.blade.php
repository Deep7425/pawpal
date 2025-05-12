@extends('amp.layouts.Masters.Master')
@section('title', 'My Subscriptions | Health Gennie')
@section('description', "View your membership details after subscription. If you are not registered on Elite portal then choose a plan according to your needs and subscribed yourself to get benefits.")
@section('content')
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
@include('users.sidebar')
<div class="dashboard-right">
    <div class="container-inner my-subscription-plans">
      <div class="registration-wrap user-info profile-exam">
	  @if($UsersSubscriptions->count() > 0)
        <table class="table">
          <thead>
            <tr>
              <th>Plan Name</th>
				<th>Transaction Number</th>
				<th>Payment Mode</th>
				<th>Tax</th>
				<th>Payble Amount</th>
				<th>Payment Status</th>
				<th>Plan Status</th>
				<th>Action</th>
			</tr>
          </thead>
          <tbody>
            @foreach($UsersSubscriptions as $index => $subs)
              <tr>
			  @php $plan_title = []; if(count($subs->UserSubscribedPlans) > 0) 
					foreach($subs->UserSubscribedPlans as $plan){
						if(!empty($plan->meta_data)){
							$plan_data = json_decode($plan->meta_data);
							$plan_title[] = $plan_data->lab_pkg;
						}
					}
					$plan_title_data = implode(",",$plan_title);
			  @endphp
              <td>{{@$plan_title_data}}</td>
              <td>{{@$subs->UserSubscriptionsTxn->tracking_id}}</td>
              <td>@if($subs->payment_mode == "1") Online Payment @elseif($subs->payment_mode == "2") Cheque @elseif($subs->payment_mode == "3") Cash @endif</td>
              <td>₹ {{$subs->tax}}</td>
              <td>₹ {{$subs->order_total}}</td>
              <td>@if($subs->order_status == "0") pending @elseif($subs->order_status == "1") completed @elseif($subs->order_status == "2") Cancelled @elseif($subs->order_status == "3") Failure Transaction @endif</td>
			   <th>@if(@$subs->PlanPeriods->status == "1") Active @else Deactivated @endif</th>
              <td>
                <a href="{{route('viewSubscription',base64_encode($subs->id))}}" class="label-default label label-success">View</a>
              </td>
             </tr>
           @endforeach
          </tbody>
        </table>
		 @else
			<div class="not-found">No Subscription Found</div>
			<div class="plan-btn"><a class="btn btn-success" href="{{ route('drive') }}">Select a plan </a></div>
		   @endif
      </div>
    </div>
    </div>
 </div>

@endsection
