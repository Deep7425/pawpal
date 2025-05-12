<html lang="en">
    @include('layouts.admin.partials.header')
    <body class="hold-transition sidebar-mini">
		<div class="wrapper">
			@include('layouts.admin.partials.top-nav')
			@include('layouts.admin.partials.sidebar')
			@yield('content')
		</div>
		<footer class="main-footer">
			<strong>Copyright &copy; 2019-2020 <a href="#">HealthGennie Patient Portal</a>.</strong> All rights reserved.
		</footer>
		<div class="loading-all" style="display:none"><span><img src="{{ URL::asset('img/turningArrow.gif') }}"/></span></div>
	</body>
	
 @include('layouts.admin.partials.footer_scripts')