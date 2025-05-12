@extends('amp.layouts.Masters.Master')
@section('title', 'Subscription Plan')
@section('content') 
<div class="right-section new-tabs-section pricemain thanks-page">
    <div class="right-block pricing">
        <div class="container-fluid">
            <div class="container">
                <div class="hospital-registration-scetion">
                	
                    <div class="thanku-content">
                    	<div class="thank-img"><img src="img/success-logo.png" width="" /></div>
                    	<h1><strong>Congratulation!!</strong></h1>
                        <p>Your profile has been verified successfully with Health Gennie. Your Subscription is active now. Please check your email for login credentials on Health Gennie.</p>

                        <div class="note-texst">If you have any query, please do not hesitate to call us on  <strong>{{getSetting("helpline_number")[0]}}</strong></div>
						
                        <div class="regards">
                        	Thanks,<br />
                            Health Gennie Team.
                        </div>
                        <a href="{{route('index')}}">Return to home page</a>
                    </div>
                </div>
            </div>
            <div class="pages-section">
                @if(count($practicesSubscriptions)>0){{$practicesSubscriptions->appends($_GET)->links()}}@endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade myModalss-billing-popup" id="BillModalView" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script>

function viewBill(bid) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('subscription.viewBill') !!}",
    data:{'bid':bid},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#BillModalView").html(data);
      jQuery('#BillModalView').modal('show');
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