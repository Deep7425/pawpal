
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

@include('layouts.admin.partials.header')
    <body>

	
	
        <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        @include('layouts.admin.partials.sidebar') 
        <!--  App Topstrip -->
        <div class="app-topstrip bg-dark py-6 px-3 w-100 d-lg-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center justify-content-center gap-5 mb-2 mb-lg-0">
            <a class="d-flex justify-content-center" href="#">
              <img src="assets/images/logos/logo-wrappixel.svg" alt="" width="150">
            </a>
          </div>
          <div class="d-lg-flex align-items-center gap-2">
            <div class="d-flex align-items-center justify-content-center gap-2">
            
                @include('layouts.admin.partials.top-nav')
                @yield('content')

            </div>
          </div>
    
        </div>
    
      </div>
        




    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('lib/wow/wow.min.js')}}"></script>
    <script src="{{url('lib/easing/easing.min.js')}}"></script>
    <script src="{{url('lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{url('lib/owlcarousel/owl.carousel.min.js')}}"></script>

    <!-- Template Javascript -->
    <script src="{{url('js/main.js')}}"></script>


</body>
</html>
	
