@extends('layouts.admin.Masters.Master')
@section('title', 'Subscription Plans')
@section('content') 
	<div class="content-wrapper">
             @if (Session::has('message'))
				 <div class="alert alert-info">{{ Session::get('message') }}</div>
			 @endif
                <section class="content-header">
                    <div class="header-icon">
                        <i class="pe-7s-box1"></i>
                    </div>
                    <div class="header-title">
                        <form action="#" method="get" class="sidebar-form search-box pull-right hidden-md hidden-lg hidden-sm">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button type="submit" name="search" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>  
                        <h1>Subscriptions</h1>
                        <small>Subscriptions list</small>
                        <ol class="breadcrumb hidden-xs">
                            <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                            <li class="active">Subscriptions</li>
                        </ol>
                    </div>
                </section>
                <!-- Main content -->
                <section class="content">
				 <div class="row">
						<div class="panel panel-bd lobidrag">
						<div class="panel-heading">
							<div class="btn-group"> 
								<a class="btn btn-success" href="{{ route('subscription.newSubscription',['id'=>base64_encode($user->id)]) }}">Make Subscriptions</a>  
							</div>
						</div>
						<div class="panel-body">
						<h3>User Details</h3>
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
								<td>{{@$user->first_name}} {{@$user->last_name}}</td>
								<td>{{@$user->gender}}</td>
								<td>{{@$user->email}}</td>
								<td style="text-align: left">{{@$user->mobile_no}}</td>
							  </tr>
						  </table>
						  </div>
						  </div>
							<h3>Subscription</h3>
							<div class="SubscriptionSe">
							<div class="SubscriptionSe223">
							<table class="table table-bordered table-hover">
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
							  @if($UsersSubscriptions->count() > 0)
							  @foreach($UsersSubscriptions as $index => $subs)
								  <tr>
									<th>{{$subs->id}}</th>
									<td>@if($subs->payment_mode == "1") Online Payment @elseif($subs->payment_mode == "2") Cheque @elseif($subs->payment_mode == "3") Cash @elseif($subs->payment_mode == "4") Admin Online @elseif($subs->payment_mode == "5") Free @endif</td>
									<td>{{$subs->tax}}</td>
									<td>{{$subs->order_total}}</td>
									<td>@if($subs->order_status == "0") pending @elseif($subs->order_status == "1") completed @elseif($subs->order_status == "2") Cancelled @elseif($subs->order_status == "3") Failure Transaction @endif</td>
									<td>{{getNameByLoginId($subs->added_by)}}</td>
									<td style="text-transform: uppercase; ">{{date('d-m-Y g:i A',strtotime($subs->created_at))}}</td>
									<td>
										<span class="label-default label label-success" onclick="viewSubscriptionDetails({{$subs->id}});">View</span>
									</td>
								 </tr>
							 @endforeach
							 @else
								<tr><td colspan="9" style="text-align: left;">You don't have any subscription </td></tr>
								<tr><td colspan="9" style="text-align: left;"><a class="btn btn-success" href="{{ route('subscription.newSubscription',['id'=>base64_encode($user->id)]) }}">Make Subscription Now</a> </td></tr>
							 @endif
							</table>
							</div>
							</div>
					  </div>
				</div>
			</div>
		</section>
</div>	
<div class="modal fade" id="viewPlan" role="dialog" data-backdrop="static" data-keyboard="false">
</div>
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