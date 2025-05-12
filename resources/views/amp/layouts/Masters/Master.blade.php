<!DOCTYPE html>
<html amp lang="en"> 
@include('amp.layouts.partials.header') 
<body class="home" @if($controller != "LabController") onLoad="initialize();" @endif>
@include('amp.layouts.partials.top-nav') 
@yield('content')	
	
<div class="modal fade myModal-enquiry" id="enquiryModal" role="dialog" data-backdrop="static" data-keyboard="false">
  {!! Form::open(array('route' => 'enquiryFromSubmit', 'id' => 'enquiryFromSubmit')) !!}
  {{ csrf_field() }}
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <div id="success-alert" style="display:none;"><p class="alert alert-success">Thanks for your interest we will contact soon..</p></div>
      <h4 class="modal-title"><a href="#"><img src="{{ URL::asset('img/logo.png') }}" /></a></h4>
      <h2>Get Your First Instant Consultation FREE</h2>
    </div>
    <div class="modal-body">
     <div class="items" id="email">
        <label>Email Id:</label>
        <input type="text" class="form-control"  name="email"/>
        <span class="help-block"></span>
      </div>
      <div class="items-section12">
      <div class="items-section" id="name">
        <label>Name:<i class="required_star">*</i></label>
        <input type="text" class="form-control"  name="name"/>
        <span class="help-block"></span>
      </div>
	  <div class="items-section" id="mobile">
        <label>Mobile No.:<i class="required_star">*</i></label>
        <input type="text" class="form-control" name="mobile"/>
        <span class="help-block"></span>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button name ="submit" type="submit"  class="btn btn-default submitBtn">SUBMIT</button>
    <button name ="clear" type="button" class="btn btn-default closePopup" data-dismiss="modal">NO THANKS</button>
  </div>
</div>
</div>
  {!! Form::close() !!}
</div>
<div class='modal fade @if($controller == "DocController" && $action == "liveDoctors" || $action == "onCallDoctorDetalis") liveDocSts @endif' id="doctorAppointmentSlot" role="dialog" data-backdrop="static" data-keyboard="false"></div>@include('amp.layouts.partials.footer') @include('amp.layouts.partials.footer_scripts') <div class="loading-all" style="display:none"><span><img src="{{ URL::asset('img/turningArrow.gif') }}"/></span></div> <div class="modal-for-share-patient-experience"> <div class="modal fade" id="patientFeedBackForm" role="dialog" data-backdrop="static" data-keyboard="false"></div> </div><script async src="https://www.googletagmanager.com/gtag/js?id=UA-118614143-1"></script>
<script> 
jQuery(document).ready(function(){
	 jQuery("#enquiryFromSubmit").validate({
		 rules: {
			name: {required:true,maxlength:255},
			mobile:{required:true,minlength:10,maxlength:10,number: true},
			email: {email: true,maxlength:100},
		 },
		  messages: {
			mobile: {
				required:"Please enter valid phone number.",
				minlength:"Please enter at least 10 number.",
				maxlength:"Please enter no more than 10 number.",
			},
			email: {
				required:"Please enter valid email."
			},
		  },
			errorPlacement: function(error, element) {
			error.appendTo(element.next());
		},
		submitHandler: function(form) {
			jQuery('.loading-all').show();
			jQuery("#enquiryFromSubmit").find('.submitBtn').attr('disabled',true);
			jQuery.ajax({
			type: "POST",
			dataType : "JSON",
			url: "{!! route('enquiryFromSubmit')!!}",
			data:  new FormData(form),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data){
				 jQuery('.loading-all').hide();
				 jQuery("#enquiryFromSubmit").find('.submitBtn').attr('disabled',false);
				 if(data.status==1) {
				  // location.reload();
				  $("#success-alert").show();
				  $.cookie('enquiryModal','1');
				  setTimeout(function(){
						jQuery("#enquiryFromSubmit").trigger('reset');
						jQuery('#enquiryModal').modal('hide');
				  }, 1000);
				 }
				 else if(data.status == 3) {
					console.log(data.errors);
					if(data.errors.email){
						jQuery.each(data.errors.email, function(key, value){
							$("#email").find(".help-block").append('<label for="email" generated="true" class="error">'+value+'</label>');
						});
					}
					if(data.errors.mobile){
						jQuery.each(data.errors.mobile, function(key, value){
							$("#mobile").find(".help-block").append('<label for="mobile" generated="true" class="error">'+value+'</label>');
						});
					}
					if(data.errors.name){
						jQuery.each(data.errors.name, function(key, value){
							$("#name").find(".help-block").append('<label for="name" generated="true" class="error">'+value+'</label>');
						});
					}
				 }
				 else if(data.status == 2){
					jQuery('#enquiryModal').modal('hide');
				 }
				 else{
					jQuery('#enquiryModal').modal('hide');
				 }
			   }
			});
		}
	});
});
function gtag(){dataLayer.push(arguments)}window.dataLayer=window.dataLayer||[],gtag("js",new Date),gtag("config","UA-118614143-1"); 
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Health Gennie",
  "url": "https://www.healthgennie.com/",
  "logo": "https://www.healthgennie.com/img/logo.png",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+91 8929920932",
    "contactType": "appointment",
    "contactOption": "technical support",
    "areaServed": "India",
    "availableLanguage": "en"
  },
  "sameAs": [
    "https://www.facebook.com/HealthGennie/",
    "https://twitter.com/healthgennie1",
    "https://www.instagram.com/healthgennie/",
    "https://www.youtube.com/channel/UCejlGuFFdjrlURsJeFJzOVw",
    "https://www.linkedin.com/company/health-gennie/",
    "https://in.pinterest.com/gennie0070/",
    "https://play.google.com/store/apps/details?id=io.Hgpp.app",
    "https://apps.apple.com/in/app/health-gennie-care-at-home/id1492557472"
  ]
}
</script>
</body></html>