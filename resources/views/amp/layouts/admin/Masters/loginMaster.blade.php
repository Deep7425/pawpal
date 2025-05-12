 @include('layouts.admin.partials.header')
    <body class="">
		@yield('content')
		<footer class="main-footers">
			<div class="container form-control" style="text-align:center;">
				<strong>Copyright &copy; 2019-2020 <a href="#">HealthGennie Patient Portal</a>.</strong> All rights reserved.
			</div>
		</footer>
    </body>
     
    <!-- /.content-wrapper -->
    <!-- jQuery -->
    <script src="{{ URL::asset('css/assets/plugins/jQuery/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
    <!-- bootstrap js -->
    <script src="{{ URL::asset('css/assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

	
		
</html>
