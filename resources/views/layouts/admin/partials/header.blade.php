
<!-- Mirrored from thememinister.com/health/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 Jun 2019 06:06:02 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-control: no-cache" content="public">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ URL::asset('css/assets/dist/img/ico/favicon_hg.png') }}" type="image/x-icon">
    <!----------------------------------------------------------------------------------------------------------------------------------------------->
    <!------------------------------------------------------------------------------------------------------------------------------------------- -->
    <link type="text/css" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link type="text/css" href="{{ URL::asset('https://fonts.googleapis.com/css?family=Roboto:300,400,500,700') }}" rel="stylesheet">
    <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

   

    <!-- Icon fonts -->
    <link rel="stylesheet" type="texeet/css" href="{{ URL::asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/fonts/ionicons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/fonts/linearicons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/fonts/open-iconic.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/fonts/pe-icon-7-stroke.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css') }}">



<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />


<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <!-- local css -->


    <link rel="stylesheet" href="{{ URL::asset('css/admin/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/jquery-ui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/bootstrap-material.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/shreerang-material.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/uikit.css') }}">
    
    <!-- select css -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-select/bootstrap-select.css' ) }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-multiselect/bootstrap-multiselect.css' ) }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.css' ) }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css') }} ">

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.css' ) }}">
    <link rel="stylesheet"  type="text/css" href="{{ URL::asset('css/assets/dist/css/stylehealth.min.css') }}"/>
    <link rel="stylesheet"  href="{{ URL::asset('css/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" type="text/css" />
    <link rel="stylesheet" type="text/css" type="text/css" href="{{ URL::asset('css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/select2.min.css') }}"  type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/libs/flot/flot.css') }}">
    <script src="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <!-- jQuery -->
    <link rel="stylesheet" href="{{URL::asset('assets/libs/chartist/chartist.css')}}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
    	 <script src="{{ asset('assets/libs/chart-am4/core.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/chart-am4/charts.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ URL::asset('https://code.jquery.com/ui/1.13.2/jquery-ui.js') }}"></script>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <!-- <script src="{{ URL::asset('js/jquery.min.js') }}"></script> -->

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    
   

    <script src="{{ URL::asset('assets/js/pages/dashboards_index.js') }}"></script>


    


  

   
<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>

    
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>

    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
    <script src="{{ URL::asset('js/form_multiselect.js') }}" ></script>
 
    <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
    <script src="{{ URL::asset('js/moment.min.js') }}"></script>

    <script src="{{ URL::asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="{{ URL::asset('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Flot -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>

<!-- Include your dashboards_index.js script -->


    <!-- datepicker -->
<link href="{{ URL::asset('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css') }}" rel='stylesheet'>


    <!-- <script src="{{ URL::asset('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js' ) }}"></script> -->

    <!-- <script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script>  -->

    <!-- <script src="{{ URL::asset('https://code.jquery.com/jquery-3.6.0.js') }}"></script> -->

    <!-- ------------------------------------------------------------------------------------------------------------- -->

    <script src="{{ URL::asset('assets/js/pace.js') }}"></script>
    <!-- <script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script> -->
    <script src="{{ URL::asset('assets/libs/popper/popper.js') }}"></script>
    <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ URL::asset('assets/js/sidenav.js') }}"></script>
    <script src="{{ URL::asset('assets/js/layout-helpers.js') }}"></script>
    <script src="{{ URL::asset('assets/js/material-ripple.js') }}"></script>


    <script src="{{ URL::asset('assets/libs/eve/eve.js') }}"></script>
    <!-- <script src="{{ URL::asset('assets/libs/flot/flot.js') }}"></script> -->
    <!-- <script src="{{ URL::asset('assets/libs/flot/curvedLines.js') }}"></script> -->

    <!-- <script src="{{ URL::asset('assets/libs/chart-am4/animated.js') }}"></script> -->

    <script src="{{ URL::asset('assets/js/demo.js') }}"></script>
    <script src="{{ URL::asset('assets/js/analytics.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables/datatables.js') }}"></script>

    <script>
    $(document).ready(function() {
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

    $('#report-table').DataTable();
    </script>


</head>