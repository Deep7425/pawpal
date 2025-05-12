<div class="SendAppLink hideforPaytm">
<div class="col-md-6">
    <div class="Get_company">
        <div class="Get_company_content">
            <h5>Get the link to download App</h5>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="Sendapp_Link">
        <input class="LinkSendMobileNo NumericFeild" type="text" placeholder="Mobile Number" maxlength="12">
        <span class="appLinkSendSuccess" style="display:none;"></span>
        <button type="button" class="btn btn-default SendLink">Send Link</button>
    </div>
</div>
</div>
<div class="container-fluid footer_top">
<div class="container">
<div class="footer">
<div class="col-md-3">
<div class="footer_section">
    <h5>Health Gennie</h5>
    <p>Health Gennie is a Preventive Health care IT concept based on the complete Health Care for anyone in need. With the Right health care at the right time.</p>
</div>
</div>
<div class="col-md-3">
<div class="footer_block">
    <h5>Learn More</h5>
    <ul>
        <li><a href="{{route('aboutUs')}}">About Us</a></li>
		<li><a href="{{route('termsConditions')}}">Terms & Conditions</a></li>
         <li><a href="{{route('privacyPolicy')}}">Privacy Policy</a></li>
        <li><a href="{{route('contactUs')}}">Contact Us</a></li>
        <!--<li><a href="{{route('driveDashboard')}}">Elite</a></li>-->
    </ul>
</div>
</div>
<div class="col-md-3">
<div class="footer_block  last hideforPaytm">
<h5>Follow Us </h5>
<ul>
<li><a title="Facebook" href="https://www.facebook.com/HealthGennie/" target="_blank" ><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
<li><a title="Twitter" href="https://twitter.com/HEALTHGENNIE1" target="_blank"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li>
<li><a title="Instagram" href="https://www.instagram.com/healthgennie/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
<li><a title="Linkedin" href="https://www.linkedin.com/company/health-gennie" target="_blank"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
</ul>
</div>
<div class="footer_block  last hideforPaytm">
<h5 class="ClassCallNow12">Call Now </h5>
<a class="ClassCallNow" href="tel:+91-8929920932"><div class="content-center">
    <div class="pulse"> <i class="fa fa-phone" aria-hidden="true"></i></div>
</div>+91-8929920932</a>
</div>
</div>
<div class="col-md-3 Get_company_top">
    <div class="Get">
      <div class="col-md-12">
        <div class="Get_company_search">
          <div class="Get_company_content">
            <h5>Subscribe for updates</h5>
          </div>
          <input class="email_subcription" type="email" placeholder="Enter Your Email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" />
          <button type="button" class="btn btn-default email_subcription_btn">Subscribe</button>

        </div>
        <span class="EmailSubcriptionMsg" style="color: rgb(255, 0, 0);"></span>
      </div>
  </div>
</div>
</div>
</div></div>
<div class="container-fluid footer_bottom">
<div class="container">
<div class="footer_bottom_section">
<div class="col-md-12">
<div class="footer_bottom_block" style="text-align:center;">
<p>© Copyright 2021 Health Gennie®. All rights reserved.</p>
</div>
</div>
</div></div>
</div>
<script>
		var acc = document.getElementsByClassName("accordion");
		var i;
		for (i = 0; i < acc.length; i++) {
		  acc[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.display === "block") {
			  panel.style.display = "none";
			} else {
			  panel.style.display = "block";
			}
		  });
		}
    $(".LinkSendMobileNo").on("keyup paste", function(){
      if(this.value.length == 10 ){
        $('.appLinkSendSuccess').hide();
        $('.appLinkSendSuccess').text('Please Enter 10 Digits Mobile No.');
        $('.appLinkSendSuccess').css({"color": "red"});
      }
    });

    jQuery(document).on("click", ".SendLink", function () {
			var mobileNo = $(".Sendapp_Link").find(".LinkSendMobileNo").val();
      flag = true;
  		if(mobileNo == "") {
          $(".Sendapp_Link").find(".email_subcription").css('border','1px solid red');
          $('.appLinkSendSuccess').show();
          $('.appLinkSendSuccess').text('Please Enter 10 Digits Mobile No.');
          $('.appLinkSendSuccess').css({"color": "red"});

          flag = false;
        }
        if(mobileNo.length < 10 ){
          $(".Sendapp_Link").find(".email_subcription").css('border','1px solid red');
          $('.appLinkSendSuccess').show();
          $('.appLinkSendSuccess').text('Please Enter 10 Digits Mobile No.');
          $('.appLinkSendSuccess').css({"color": "red"});
          flag = false;
        }

        if (flag == true) {
          jQuery('.loading-all').show();
          jQuery.ajax({
            type: "POST",
            dataType : "JSON",
            url: "{!! route('SendAppLink') !!}",
            data:{'mobile_no':mobileNo},
            success: function(data) {
              jQuery('.loading-all').hide();
              if(data == 1) {
                $('.appLinkSendSuccess').show();
                $('.appLinkSendSuccess').text('Link Sent successfully ');
                $(".Sendapp_Link").find(".LinkSendMobileNo").val('');
                $('.appLinkSendSuccess').css({"color": "green"});
              }
              else {
                $('.appLinkSendSuccess').show();
                $('.appLinkSendSuccess').text(data[0]);
                $('.appLinkSendSuccess').css({"color": "red"});
              }
            },
            error: function(error) {
              if(error.status == 401 || error.status == 419) {
                //alert("Session Expired,Please logged in..");
                location.reload();
              }
              else{
                alert("Oops Something goes Wrong.");
              }
              jQuery('.loading-all').hide();
            },
          });
        }
		});
</script>