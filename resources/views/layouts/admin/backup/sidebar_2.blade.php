<!DOCTYPE html>

<html lang="en" class="default-style layout-fixed layout-navbar-fixed">


<!-- Mirrored from html.phoenixcoded.net/empire/bootstrap/default/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 21 Feb 2023 05:55:51 GMT -->

<head>
    <title>Health Gennie</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description"
        content="Empire is one of the unique admin template built on top of Bootstrap 4 framework. It is easy to customize, flexible code styles, well tested, modern & responsive are the topmost key factors of Empire Dashboard Template" />
    <meta name="keywords"
        content="bootstrap admin template, dashboard template, backend panel, bootstrap 4, backend template, dashboard template, saas admin, CRM dashboard, eCommerce dashboard">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <!-- Icon fonts -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.css">
    <link rel="stylesheet" href="assets/fonts/linearicons.css">
    <link rel="stylesheet" href="assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="assets/css/shreerang-material.css">
    <link rel="stylesheet" href="assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/libs/flot/flot.css">

</head>

<style>



</style>


<body>

<?php $userModules = getAdminUserPermissionModule();?>



    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] End -->

    <!-- [ Layout wrapper ] Start -->
  
            <!-- [ Layout sidenav ] Start -->

            <!-- Side-bar -->
            <div  id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
                <!-- Brand demo (see assets/css/demo/demo.css) -->
                <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                    	<img src="{{ URL::asset('css/assets/dist/img/logo.png') }}" style = "width : 20px; height : 10px;" class="img-circle" alt="User Image">
                    </span>
                    <a href="index-2.html" class="app-brand-text demo sidenav-text font-weight-normal ml-2">Healy</a>
                    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                        <i class="ion ion-md-menu align-middle"></i>
                    </a>
                </div>
                <div class="sidenav-divider mt-0"></div>

                <!-- Links -->
                <ul class="sidenav-inner py-1">
  
                    <!-- Dashboards -->
                    <li class="sidenav-item open active">
                        <a href="javascript:" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon feather icon-home"></i>
                            <div>Home</div>
                          
                        </a>
                        @if(in_array(23,$userModules))
                        <ul class="sidenav-menu">
                            <li class="sidenav-item active @if ($controller == "HomeController" && ($action == "Home")) active @endif">
                                <a href="="{{ route('admin.home') }}" class="sidenav-link">
                                    <div>Dashboards</div>
                                </a>
                            </li>
                        </ul>
                        @endif
                        @if(in_array(10,$userModules))
                 <!-- Appointment -->
                    <li class="sidenav-item  @if ($controller == "AppointmentController" && ($action == "hgAppointments")) active @endif">
                        <a href="javascript:" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon feather icon-lock"></i>
                            <div>Appointment</div>
                        </a>
                        <ul class="sidenav-menu @if ($controller == "AppointmentController" && ($action == "hgAppointments")) menu-open @endif">
                       
                            <li class="sidenav-item">
                                <a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($action == "hgAppointments")) style="color: #009688;" @endif class="sidenav-link">
                                    <div>Appointment List</div>
                                </a>
                            </li>

                            @endif

                        </ul>
                    </li>
                    <!-- Users  -->
                    <li class="sidenav-item">
                        <a href="javascript:" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon feather icon-lock"></i>
                            <div>Users</div>
                        </a>
                        <ul class="sidenav-menu">
                       
                            <li class="sidenav-item">
                                <a href="pages_authentication_login-and-register.html" class="sidenav-link">
                                    <div>Login + Register</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a href="pages_authentication_lock-screen-v1.html" class="sidenav-link">
                                    <div>Lock screen v1</div>
                                </a>
                            </li>
                         
                        </ul>
                    </li>

                  <!-- Setting -->

                   <li class="sidenav-item">
                        <a href="javascript:" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon feather icon-lock"></i>
                            <div>Plan Manager</div>
                        </a>
                        <ul class="sidenav-menu">
                       
                            <li class="sidenav-item">
                                <a href="pages_authentication_login-and-register.html" class="sidenav-link">
                                    <div>Login + Register</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a href="pages_authentication_lock-screen-v1.html" class="sidenav-link">
                                    <div>Lock screen v1</div>
                                </a>
                            </li>
                         
                        </ul>
                    </li>

               <!-- labs -->
                    <li class="sidenav-item">
                        <a href="javascript:" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon feather icon-lock"></i>
                            <div>Labs</div>
                        </a>
                        <ul class="sidenav-menu">
                       
                            <li class="sidenav-item">
                                <a href="pages_authentication_login-and-register.html" class="sidenav-link">
                                    <div>Login + Register</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a href="pages_authentication_lock-screen-v1.html" class="sidenav-link">
                                    <div>Lock screen v1</div>
                                </a>
                            </li>
                         
                        </ul>
                       </li>
                        <!-- App Blog -->
                        <li class="sidenav-item">
                        <a href="javascript:" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon feather icon-lock"></i>
                            <div>Labs</div>
                        </a>
                        <ul class="sidenav-menu">
                       
                            <li class="sidenav-item">
                                <a href="pages_authentication_login-and-register.html" class="sidenav-link">
                                    <div>Login + Register</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a href="pages_authentication_lock-screen-v1.html" class="sidenav-link">
                                    <div>Lock screen v1</div>
                                </a>
                            </li>
                         
                        </ul>
                    </li>
               


                    
                </ul>
            </div>



                  


        <!-- Core scripts -->
        <script src="assets/js/pace.js"></script>
        <script src="assets/js/jquery-3.3.1.min.js"></script>
        <script src="assets/libs/popper/popper.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/sidenav.js"></script>
        <script src="assets/js/layout-helpers.js"></script>
        <script src="assets/js/material-ripple.js"></script>

        <!-- Libs -->
        <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="assets/libs/eve/eve.js"></script>
        <script src="assets/libs/flot/flot.js"></script>
        <script src="assets/libs/flot/curvedLines.js"></script>
        <script src="assets/libs/chart-am4/core.js"></script>
        <script src="assets/libs/chart-am4/charts.js"></script>
        <script src="assets/libs/chart-am4/animated.js"></script>

        <!-- Demo -->
        <script src="assets/js/demo.js"></script>
        <script src="assets/js/analytics.js"></script>
     
	    <script>
            $(document).ready(function () {
                // checkCookie();
                $('#exampleModalCenter').modal();
            });

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires=" + d.toGMTString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function getCookie(cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }

            function checkCookie() {
                var ticks = getCookie("modelopen");
                if (ticks != "") {
                    ticks++;
                    setCookie("modelopen", ticks, 1);
                    if (ticks == "2" || ticks == "1" || ticks == "0") {
                        $('#exampleModalCenter').modal();
                    }
                } else {
                    // user = prompt("Please enter your name:", "");
                    $('#exampleModalCenter').modal();
                    ticks = 1;
                    setCookie("modelopen", ticks, 1);
                }
            }
        </script>
        <script src="assets/js/pages/dashboards_index.js"></script>

</body>


</html>