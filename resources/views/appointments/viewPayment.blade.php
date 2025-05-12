@extends('layouts.Masters.Master')
@section('title', 'My Appointments Payment Detail | Health Gennie')
@section('content')
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
@include('users.sidebar')
<div class="dashboard-right">
    <div class="container-inner my-subscription-plans">
      <div class="registration-wrap user-info profile-exam">
        <div class="subscription-header">
          <p class="subs-heading">Payment Details</p>
          <p class="subs-date">Date : <strong>{{date('F d Y g:i A',strtotime($appointment->created_at))}}</strong></p>
        </div>
        <div class="subscription-header-2">
          @if(!empty($appointment->AppointmentTxn))<p class="subs-heading">Transaction No. : <strong>{{@$appointment->AppointmentTxn->tracking_id}}</strong> </p>@endif
          <p class="subs-date">Payment Status : <strong>{{@$appointment->AppointmentTxn->tran_status}}</strong></p>
        </div>
        <div class="subcribed-plans">
          
        </div>
        <div class="subs-trans-info">
          <div class="subs-trans">
            <p class="sub-date">User Info </p>
            <p>{{@$appointment->patient->first_name}} {{@$appointment->patient->last_name}}</p>
            <p>{{@$appointment->patient->mobile_no}}</p>
            <p>{{@$appointment->patient->email}}</p>
          </div>
          <div class="subs-trans">
            <p class="trans-heading"><span>Payment Mode : </span><strong>({{@$appointment->AppointmentTxn->card_name}}) {{@$appointment->AppointmentTxn->tran_mode}}</strong> </p>
            <p class="trans-heading"><span>Total Amount :</span> <strong> ₹ {{@$appointment->AppointmentTxn->payed_amount}}</strong></p>
            <p class="trans-heading"><span>Paid Amount :</span>  <strong> ₹ {{@$appointment->AppointmentTxn->payed_amount}}</strong></p>
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
