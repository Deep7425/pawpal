<header class="main-header" id="headerMainDiv">
		<a href="{{ route('admin.home') }}" class="logo"> <!-- Logo -->
			<span class="logo-mini">
				<!--<b>A</b>H-admin-->
				<img src="{{ URL::asset('css/assets/dist/img/mini-logo.png') }}" alt=""/>
			</span>
			<span class="logo-lg">
				<!--<b>Admin</b>H-admin-->
				<img src="{{ URL::asset('css/assets/dist/img/logo.png') }}" alt=""/>
			</span>
		</a>
		<!-- Header Navbar -->
		<nav class="navbar navbar-static-top ">
			<a href="#" class="sidebar-toggle sidebar-toggle-action-click" data-toggle="offcanvas" role="button"> <!-- Sidebar toggle button-->
				<span class="sr-only">Toggle navigation</span>
				<span class="fa fa-tasks"></span>
			</a>
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
				<!-- Claim Doctor -->
			@if(Session::get('id') == 1)
					<li class="dropdown messages-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-stethoscope"></i>
							<span class="label label-success">{{getTodayClaimDoctors()}}</span>
						</a>
						<ul class="dropdown-menu">
							<li class="header"><i class="fa fa-stethoscope"></i>
							@if(count(getTodayClaimDoctorsFour())>0) {{count(getTodayClaimDoctorsFour())}}  Doctors @endif</li>
							<li>
								<ul class="menu">
								@if(count(getTodayClaimDoctorsFour())>0)
									@foreach(getTodayClaimDoctorsFour() as $doc)
									<li>
										<a href="#" class="border-gray">
											<div class="pull-left">
											<img src="@if(!empty($doc->profile_pic)) <?php echo url("/")."/public/doctorImage/".$doc->profile_pic;?> @else {{ URL::asset('img/camera-icon.jpg') }} @endif" class="img-thumbnail" alt="User Image"></div>
											<h4>{{$doc->first_name}} {{$doc->last_name}}</h4>
											<p>{{$doc->email}}</p>
											<p><span>@if(getDateDifference(strtotime(date("Y-m-d H:i:s")),date(strtotime($doc->updated_at)))['hours'] > 0)
												 {{getDateDifference(strtotime(date("Y-m-d H:i:s")),date(strtotime($doc->updated_at)))['hours']}} hour ago
											  @else
												 {{getDateDifference(strtotime(date("Y-m-d H:i:s")),date(strtotime($doc->updated_at)))['mins']}} minute ago
											  @endif</span></p>
											<span class="label label-success pull-right">{{date('g:i A',strtotime($doc->updated_at))}}</span>
										</a>
									</li>
									@endforeach
								@else
									<li class="footer"><a href="#" class="border-gray">Today not claimed any doctors</a></li>
								@endif
								</ul>
							</li>
							<li class="footer"><a href="{{route('admin.claimDoctorsList')}}">See all claim doctors <i class=" fa fa-arrow-right"></i></a></li>
						</ul>
					</li>
					<!-- Notifications -->
					<li class="dropdown notifications-menu app-notify-div">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="pe-7s-bell"></i>
							<span class="label label-warning getTodayTotalApp">{{getTodayAppointment()}}</span>
						</a>
						<ul class="dropdown-menu">
							<li class="header"><i class="fa fa-bell"></i> <span class="getTodayTotalApp">{{getTodayAppointment()}}</span> Appointment Notifications</li>
							<li>
								<ul class="menu">
									<li>
									<a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" class="border-gray"><i class="fa fa-inbox"></i> Total Appointments <span class=" label-success label label-default pull-right getTodayTotalApp">{{getTodayAppointment()}}</span></a>
									</li>
									<li>
									<a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d')),'app_type'=>base64_encode(2)]) }}" class="border-gray"><i class="fa fa-cart-plus"></i> Pending Appointments <span class=" label-success label label-default pull-right">{{getTodayPendingAppointment()}}</span> </a>
									</li>
									<li>
									<a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d')),'app_type'=>base64_encode(3)]) }}" class="border-gray"><i class="fa fa-money"></i> Confirm Appointments  <span class="label-success label label-default pull-right">{{getTodayConfirmAppointment()}}</span> </a>
									</li>
									<li>
									<a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d')),'app_type'=>base64_encode(4)]) }}" class="border-gray"><i class="fa fa-cart-plus"></i> Cancel Appointments <span class="label-success label label-default pull-right">{{getTodayCancelAppointment()}}</span> </a>
									</li>
								</ul>
							</li>
						   <li class="footer">
						   <a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}"> See all Appointments <i class=" fa fa-arrow-right"></i></a>
							</li>
						</ul>
					</li>
				@endif
					<!-- user -->
					<li class="dropdown dropdown-user admin-user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<div class="user-image">
						<img src="{{ URL::asset('css/assets/dist/img/logo.png') }}" class="img-circle" height="40" width="40" alt="User Image">
						</div>
						</a>
						<ul class="dropdown-menu">
							<!--<li><a href="#"><i class="fa fa-users"></i> User Profile</a></li>-->
							<li><a href="{{ route('admin.logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
	</header>
