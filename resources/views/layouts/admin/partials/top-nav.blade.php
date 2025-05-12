<style>
.navbar-nav>.notifications-menu>.dropdown-menu,
.navbar-nav>.messages-menu>.dropdown-menu,
.navbar-nav>.tasks-menu>.dropdown-menu {
    width: 320px;
    padding: 0;
    margin: 0;
    top: 50%;
}

.dropdown-menu {
    top: 50%;
}

.label-success {
    color: #fff;
    background-color: #ff4a00;
    border: 2px solid #ff4a00;
    border-radius: 60px;
    position: absolute;
}

.label-warning {
    color: #fff;
    border-radius: 60px;
    position: absolute;
    right: 5px;
}

.user-image {
    position: relative;
    bottom: 30px;
    left: 3px;
}
</style>


<div class="layout-wrapper layout-2">
    <nav style="z-index:199; border-radius:0; height:70px"
        class="layout-navbar navbar navbar-expand-lg align-items-lg-center bg-dark container-p-x" id="layout-navbar">

        <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
            <i class="ion ion-md-menu align-middle"></i>
        </a>

        <!-- Brand demo (see assets/css/demo/demo.css) -->
        <a href="index-2.html" class="navbar-brand app-brand demo d-lg-none py-0 mr-4">
            <span class="app-brand-logo demo">

                <img src="{{ URL::asset('assets/img/logo-dark.png') }}" alt="Brand Logo" class="img-fluid">

            </span>



            <span class="app-brand-text demo font-weight-normal ml-2">Health Gennie</span>
        </a>

        <!-- Sidenav toggle (see assets/css/demo/demo.css) -->
        <div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center mr-auto">
            <a class="nav-item nav-link px-0 mr-lg-4" href="javascript:">
                <i class="ion ion-md-menu text-large align-middle"></i>
            </a>
        </div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse collapse" id="layout-navbar-collapse">


		<li style = "list-style-type:none; margin-right:40%" class="dropdown messages-menu" >
					
					<a href="#" class="" data-toggle="dropdown">
					<i class="fa-solid fa-user-tie"></i>
						<span >{{@Session::get('userdata')->name}}</span>
					</a>
					<!-- <span id="currentTime"></span> -->
	 			</li>
     
            <div class="navbar-custom-menu" style="position:relative; left:850px; top:15px;">


                <ul class="nav navbar-nav" style="list-style-type:none;">
                    <!-- Claim Doctor -->
                    @if(Session::get('id') == 1)

				


                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-stethoscope"></i>
                            <span class="label label-success">{{getTodayClaimDoctors()}}</span>
                        </a>
                        <ul class="dropdown-menu">
                        
						<li class="header">
                                <?php $totClaimDoc = getTodayClaimDoctorsFour(); ?>
                                @if(count($totClaimDoc)>0) {{count($totClaimDoc)}} Doctors @endif</li>
                            <li>
                                <ul class="menu">
                                    @if(count($totClaimDoc)>0)
                                    @foreach($totClaimDoc as $doc)
                                    <li>
                                        <a href="#" class="border-gray">
                                            <div class="pull-left">
                                                <img src="@if(!empty($doc->profile_pic)) <?php echo url("/")."/public/doctorImage/".$doc->profile_pic;?> @else {{ URL::asset('img/camera-icon.jpg') }} @endif"
                                                    class="img-thumbnail" alt="User Image">
                                            </div>
                                            <h4>{{$doc->first_name}} {{$doc->last_name}}</h4>
                                            <p>{{$doc->email}}</p>
                                            <p><span>@if(getDateDifference(strtotime(date("Y-m-d
                                                    H:i:s")),date(strtotime($doc->updated_at)))['hours'] > 0)
                                                    {{getDateDifference(strtotime(date("Y-m-d H:i:s")),date(strtotime($doc->updated_at)))['hours']}}
                                                    hour ago
                                                    @else
                                                    {{getDateDifference(strtotime(date("Y-m-d H:i:s")),date(strtotime($doc->updated_at)))['mins']}}
                                                    minute ago
                                                    @endif</span></p>
                                            <span
                                                class="label label-success pull-right">{{date('g:i A',strtotime($doc->updated_at))}}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                    @else
                                    <li class="footer"><a href="#" class="border-gray"><i class="fa fa-stethoscope"></i>
                                            Today not claimed any doctors</a></li>
                                    @endif
                                </ul>
                            </li>
                            <li class="footer"><a href="{{route('admin.claimDoctorsList')}}">See all claim doctors <i
                                        class=" fa fa-arrow-right"></i></a></li>
                        </ul>
                    </li>
                    <!-- Notifications -->
                    <li class="dropdown notifications-menu app-notify-div">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="pe-7s-bell"></i>
                            <?php $totAppt = getTodayAppointment(); ?>
                            <span class="label label-warning getTodayTotalApp">{{$totAppt}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><i class="fa fa-bell"></i> <span
                                    class="getTodayTotalApp">{{$totAppt}}</span> Appointment Notifications</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}"
                                            class="border-gray"><i class="fa fa-inbox"></i> Total Appointments <span
                                                class=" label-success label label-default pull-right getTodayTotalApp">{{$totAppt}}</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d')),'app_type'=>base64_encode(2)]) }}"
                                            class="border-gray"><i class="fa fa-cart-plus"></i> Pending Appointments
                                            <span
                                                class=" label-success label label-default pull-right">{{getTodayPendingAppointment()}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d')),'app_type'=>base64_encode(3)]) }}"
                                            class="border-gray">
                                            <i class="fa-solid fa-money-bill"></i> Confirm Appointments <span
                                                class="label-success label label-default pull-right">{{getTodayConfirmAppointment()}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d')),'app_type'=>base64_encode(4)]) }}"
                                            class="border-gray"><i class="fa fa-cart-plus"></i> Cancel Appointments
                                            <span
                                                class="label-success label label-default pull-right">{{getTodayCancelAppointment()}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a
                                    href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}">
                                    See all Appointments <i class=" fa fa-arrow-right"></i></a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <!-- user -->
                    <li class="dropdown dropdown-user admin-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="user-image">
                                <img src="{{ URL::asset('css/assets/dist/img/logo.png') }}" class="img-circle"
                                    height="40" width="50" alt="User Image">
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.editProfile') }}"><i class="fa fa-user"></i> Edit Profile</a>
                            </li>
                            <li><a href="{{ route('admin.logout') }}"><i class="fa fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

</div>
<!-- [ Layout container ] End -->

<!-- Overlay -->
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<script>

	function updateTime() {
        // Get the current date and time
        var currentDate = new Date();

        // Extract the time part
        var hours = currentDate.getHours();
        var minutes = currentDate.getMinutes();
        var seconds = currentDate.getSeconds();

        // Add leading zeros if necessary
        hours = (hours < 10 ? "0" : "") + hours;
        minutes = (minutes < 10 ? "0" : "") + minutes;
        seconds = (seconds < 10 ? "0" : "") + seconds;

        // Construct the time string
        var formattedTime = hours + ":" + minutes + ":" + seconds;

        // Update the content of the span with the formatted time
        document.getElementById('currentTime').textContent = formattedTime;
    }

    // Call the updateTime function initially to display the time immediately
    updateTime();

    // Update the time every second
    setInterval(updateTime, 1000);
</script>