@include('layouts.admin.partials.header')
    <body class="">
	
     
    <!-- /.content-wrapper -->
    <!-- jQuery -->
    <script src="{{ URL::asset('css/assets/plugins/jQuery/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
    <!-- bootstrap js -->
    <script src="{{ URL::asset('css/assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

	
		


<div class="layout-wrapper layout-2 login">

    <div class="layout-container"  style = "padding-top:0px !important;">

	
        <!--<div class="back-link">
            <a href="index-2.html" class="btn btn-success">Back to Dashboard</a>
        </div>-->
        <div class="loginBgImage12">
        <div class="loginBgImage">
        	<div class="loginBgImage123"><img src="img/loginBgImage.png" /></div>
        </div>
        <div class="container-center123">
            <div class="panel panel-bd">
                <div class="panel-heading">
                    <div class="view-header">
                        <div class="header-icon">
                            <i class="pe-7s-unlock"></i>
                        </div>
                        <div class="header-title">
                            <h3>Login</h3>
                            <h5>Please enter your credentials to login.</h5>
                        </div>
                    </div>

         
		  <!-- @if (session('error_msg'))
		 
			  <h4 style = "color:red;">{{ session('error_msg') }}</h4>
		
		  @endif -->
	

                </div>

       

                <div class="panel-body card" >
                    <form action="{{ route('admin.login') }}" method="POST" style = "margin : 15px;">
						@csrf
                        <div class="form-group">
                            <label class="control-label" for="username">Username</label>
                            <input type="text" placeholder="admin" title="Please enter you email" required="" value="" name="email" id="username" class="form-control">
                            <span class="help-block small">Your unique email to app</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="password">Password</label>
                            <input type="password" title="Please enter your password" placeholder="******" required="" value="" name="password" id="password" class="form-control">
                            <span class="help-block small">Your strong password</span>
                        </div>
                        <div >
                            <button class="btn btn-primary" type="submit" style = "margin-bottom : 10px;">Login</button>
                            <!--<a class="btn btn-warning" href="register.html">Register</a>-->
                        </div>
                    </form>
                </div>
            
                <footer class="main-footers common">
                    <div class="form-control">
                        <strong>Copyright &copy;<span id="copyright-year"></span> <a href="#">HealthGennie Patient Portal</a>.</strong> All rights reserved.
                    </div>
		        </footer>

            </div>
        </div>
       
</div>
</div>


		</div>

        <script>
    document.getElementById('copyright-year').textContent = new Date().getFullYear();
</script>

    </body>
</html>
