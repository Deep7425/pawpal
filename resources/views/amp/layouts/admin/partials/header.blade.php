  <!DOCTYPE html>
  <html>
  <!-- Mirrored from thememinister.com/health/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 Jun 2019 06:06:02 GMT -->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>@yield('title')</title>
		<!-- Favicon and touch icons -->
		<link rel="shortcut icon" href="{{ URL::asset('css/assets/dist/img/ico/favicon.png') }}" type="image/x-icon">
		<!-- Theme style rtl -->
		<!--<link href="assets/dist/css/stylehealth-rtl.css" rel="stylesheet" type="text/css"/>-->
		<link href="{{ URL::asset('css/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>
		<!-- Bootstrap -->
		<link href="{{ URL::asset('css/assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
		<!-- Bootstrap rtl -->
		<!--<link href="assets/bootstrap-rtl/bootstrap-rtl.min.css" rel="stylesheet" type="text/css"/>-->
		<!-- Lobipanel css -->
		<link href="{{ URL::asset('css/assets/plugins/lobipanel/lobipanel.min.css') }}" rel="stylesheet" type="text/css"/>
		<!-- Pace css -->
		<link href="{{ URL::asset('css/assets/plugins/pace/flash.css') }}" rel="stylesheet" type="text/css"/>
		<!-- Font Awesome -->
		<link href="{{ URL::asset('css/assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
		<!-- Pe-icon -->
		<link href="{{ URL::asset('css/assets/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}" rel="stylesheet" type="text/css"/>
		<!-- Themify icons -->
		<link href="{{ URL::asset('css/assets/themify-icons/themify-icons.css') }}" rel="stylesheet" type="text/css"/>
		<!-- End Global Mandatory Style
		=====================================================================-->
		<!-- Start page Label Plugins
		=====================================================================-->
		<!-- Toastr css -->
		<link href="{{ URL::asset('css/assets/plugins/toastr/toastr.css') }}" rel="stylesheet" type="text/css"/>
		<!-- Emojionearea -->
		<link href="{{ URL::asset('css/assets/plugins/emojionearea/emojionearea.min.css') }}" rel="stylesheet" type="text/css"/>
		<!-- Monthly css -->
		<link href="{{ URL::asset('css/assets/plugins/monthly/monthly.css') }}" rel="stylesheet" type="text/css"/>
		<!-- End page Label Plugins
		=====================================================================-->
		<!-- Start Theme Layout Style
		=====================================================================-->
		<!-- Theme style -->
		<link href="{{ URL::asset('css/assets/dist/css/stylehealth.min.css') }}" rel="stylesheet" type="text/css"/>

        <link href="{{ URL::asset('css/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css"/>

		<link rel="stylesheet" href="{{ URL::asset('css/jquery-ui.css') }}">
		<link rel="stylesheet" href="{{ URL::asset('css/admin/style.css') }}">
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet" type="text/css"/>

		<!-- jQuery -->

		<script src="{{ URL::asset('js/jquery.min.js') }}"></script>
		<script src="{{ URL::asset('css/assets/plugins/jQuery/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
        <!-- jquery-ui -->
        <script src="{{ URL::asset('css/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js') }}" type="text/javascript"></script>
        <!-- Bootstrap -->
		<script src="{{ URL::asset('css/assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
		<script src="{{ URL::asset('js/jquery.validate.js') }}"></script>
		<script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('js/select2.min.js') }}"></script>


	</head>
