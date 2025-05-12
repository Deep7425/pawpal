@extends('layouts.admin.Masters.Master')
@section('title', 'Subscription Plans')
@section('content') 

<link rel="stylesheet">


<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y view-subs">
                  <div class="row">

				  <div class="col-sm-3">
				  <div class="btn-group"> 
								<a class="btn btn-success" style = "background-color:#ff4a00; border:1px solid #ff4a00;" href="{{ route('subscription.newSubscription',['id'=>base64_encode($user->id)]) }}">Make Subscriptions</a>  
							</div>
				  </div>
				  
				  </div>
			<div class="layout-content table-responsive feedback" style="padding-top:15px;"> 
			
			<div class="SubscriptionSe22">
						  <table class="table table-bordered table-hover">
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
								<td style="text-align: left">{{@$user->mobile_no}}</td>
							  </tr>
							  </tbody>
						  </table>
						  </div>
						  </div>
							<h3>Subscription</h3>
							<div class="SubscriptionSe">
							<div class="SubscriptionSe223 table-responsive">
							<table class="table table-bordered table-hover">
							<thead>
								<tr>
								<th>Subscription Number</th>
								<th>Payment Mode</th>
								<th>Tax</th>
								<th>Payble Amount</th>
								<th>Order Status</th>
								<th>Added By</th>
								<th>Subscription Date</th>
								<th>Action</th>	
							  </tr>
							  </thead>
							  @if($UsersSubscriptions->count() > 0)
							  @foreach($UsersSubscriptions as $index => $subs)
							  <tbody>
								  <tr>
									<td>{{$subs->id}}</td>
									<td>@if($subs->payment_mode == "1") Online Payment @elseif($subs->payment_mode == "2") Cheque @elseif($subs->payment_mode == "3") Cash @elseif($subs->payment_mode == "4") Admin Online @elseif($subs->payment_mode == "5") Free @endif</td>
									<td>{{$subs->tax}}</td>
									<td>{{$subs->order_total}}</td>
									<td>@if($subs->order_status == "0") pending @elseif($subs->order_status == "1") completed @elseif($subs->order_status == "2") Cancelled @elseif($subs->order_status == "3") Failure Transaction @endif</td>
									<td>{{getNameByLoginId($subs->added_by)}}</td>
									<td>{{date('d-m-Y g:i A',strtotime($subs->created_at))}}</td>
									<td>
										<span class="label-default label label-success" onclick="viewSubscriptionDetails({{$subs->id}});">View</span>
									</td>
								 </tr>
							
							 @endforeach
							 @else
								<tr><td colspan="9" style="text-align: left;">You don't have any subscription </td></tr>
								<tr><td colspan="9" style="text-align: left;"><a class="btn btn-success" style = "background-color:#ff4a00; border:1px solid #ff4a00;" href="{{ route('subscription.newSubscription',['id'=>base64_encode($user->id)]) }}">Make Subscription Now</a> </td></tr>
							 @endif
							 </tbody>	 
							</table>
							</div>
			
			
            </div>

		    </div>
        </div> 
     </div>  
	 

 <div class="modal fade" id="viewPlan" role="dialog" data-backdrop="static" data-keyboard="false"></div>



 <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
 <!-- <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->

<script>
function viewSubscriptionDetails(id) {
	jQuery('.loading-all').show();
	jQuery.ajax({
	type: "POST",
	dataType : "HTML",
	url: "{!! route('subscription.viewPlan')!!}",
	data:{"_token":"{{ csrf_token() }}",'id':id},
	success: function(data) {
	
	  jQuery('.loading-all').hide();
	  jQuery("#viewPlan").html(data);
	  jQuery('#viewPlan').modal('show');
	},
	error: function(error)
	{    
		jQuery('.loading-all').hide();
		alert("Oops Something goes Wrong.");
	}
  });
}
</script>	
@endsection