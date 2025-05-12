
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

@include('layouts.admin.partials.header')
    <body class="hold-transition sidebar-mini">
	
@include('layouts.admin.partials.sidebar') 
	<div class="layout-container">
			@include('layouts.admin.partials.top-nav')
			@yield('content')
		
		</div>
	
		<div class="modal fade" id="manageSprtModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script>
jQuery(document).ready(function () {
jQuery(document).on("click", ".manageSprt", function () {
   var pkey = $(this).attr('pkey');
   var r_from = $(this).attr('r_from');
   $('#manageSprtModal').modal('show');
      jQuery('.loading-all').show();
		jQuery.ajax({
		type: "POST",
		dataType : "HTML",
		url: "{!! route('admin.showHandleQueries')!!}",
		data:{'pkey':pkey,'r_from':r_from},
		success: function(data)
		{
		
		  jQuery('.loading-all').hide();
		  jQuery("#manageSprtModal").html(data);
		  jQuery('#manageSprtModal').modal('show');
		},
		error: function(error)
		{
			jQuery('.loading-all').hide();
			alert("Oops Something goes Wrong.");
		}
	  });
 });
 
$(document.body).on('click','.submitSpFrm', function(){
	 jQuery("form[name='manageSupportSystem']").validate({
	  rules: {
		 note: "required",
		 typ: "required",
		 followUpDate : "required",
	 },
	messages:{
	},
	errorPlacement: function(error, element){
	  error.appendTo(element.parent().find('.help-block'));
	},ignore: ":hidden",
	submitHandler: function(form) {
	  $(form).find('.submitSpFrm').attr('disabled',true);
	  jQuery('.loading-all').show();
	  jQuery.ajax({
		type: "POST",
		dataType : "JSON",
		url: "{!! route('admin.manageSupportSystem')!!}",
		data:  new FormData(form),
		contentType: false,
		cache: false,
		processData:false,
		success: function(data) {
		   if(data==1) {
			jQuery('.loading-all').hide();
			$(form).find('.submitSpFrm').attr('disabled',false);
			location.reload();
		   }
		   else {
			jQuery('.loading-all').hide();
			$(form).find('.submitSpFrm').attr('disabled',false);
			alert("Oops Something Problem");
		   }
		},
		  error: function(error)
		  {
			jQuery('.loading-all').hide();
			alert("Oops Something goes Wrong.");
		  }
	  });
	   }
	});
});
});



</script>
<footer class="main-footer ">
    <strong>Copyright &copy; <span id="copyright-year"></span> <a href="#">HealthGennie Patient Portal</a>.</strong> All rights reserved.
</footer>
		<div class="loading-all" style="display:none"><span><img src="{{ URL::asset('img/turningArrow.gif') }}"/></span></div>
		<script>
    document.getElementById('copyright-year').textContent = new Date().getFullYear();
</script>

@include('layouts.admin.partials.footer_scripts')

</body>
</html>
	
