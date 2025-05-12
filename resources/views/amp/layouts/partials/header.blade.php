<head>
@php 
    $title = "Book FREE Doctor Consultation and Appointment | Health Gennie";
	$description = "Book free doctor consultation and appointment from the comfort of your home via phone/video call and get digital prescriptions on your phone. Also read about health issues and get solutions.";
	$slug = basename($_SERVER['REQUEST_URI']);
	$og_type = url("/");
	$og_image = url("/").'/img/imgpsh_fullsize_anim.webp';
	
	//$canonicalUrl =  basename($_SERVER['REQUEST_URI']);
	// if (strpos($canonicalUrl, '?') !== false) {
		// $canonicalUrl = explode("?",$canonicalUrl)[0];
	// }
	$canonicalUrl = URL::current();
@endphp
@if($controller == "searchController" || $controller == "DocController")
@php
if(Session::get('info_type')) {
	if(Session::get('locality_id')) {
		$uri =  $_SERVER['REQUEST_URI'];
		if($uri) {
			$urlX = explode("/",$uri);
			$slug = @$urlX[2]; 
			if (strpos($slug, '?') !== false) {
				$slug = explode("?",$slug)[0];
			}
		}
	}
	else{
		if (strpos($slug, '?') !== false) {
			$slug = explode("?",$slug)[0];
		}
	}
	
	$infoKeyWordData = getTitleBySlug(Session::get('info_type'),$slug); 
	$title = $infoKeyWordData['keyword'];
	if(Session::get('info_type') == "Speciality"){
	 $title = 'Best '.$title;
	}
	
	$description = $infoKeyWordData['description'];
	if(empty($description)) {
		$description = "Book free doctor consultation and appointment from the comfort of your home via phone/video call and get digital prescriptions on your phone. Also read about health issues and get solutions.";
	}
	if(empty($title)){
		$title = Session::get('search_from_search_bar');
	}
}
elseif($action = "addDoc"){
	$title = 'Create Your Doctor Profile for Free';
	$description = "Now provide online consultation to your patients with Health Gennie. Registration charges free. Easy video consultaion and digital prescription sharing.";
}
else{ $description = "Book free doctor consultation and appointment from the comfort of your home via phone/video call and get digital prescriptions on your phone. Also read about health issues and get solutions."; }
@endphp
@if(Session::get('search_from_search_bar'))
	<title>{{trim($title)}}</title>
	<meta name="description" content="@if($description){{@$description}}@else @yield('description') @endif"/>
	<meta property="og:description" content="@if($description){{@$description}}@else @yield('description') @endif"/>
	<meta property="og:title" content="@if($title){{@$title}}@else @yield('title') @endif"/>
@else
	<title>@yield('title')</title>
	<meta name="description" content="@if($description){{@$description}}@else @yield('description') @endif"/>
	<meta property="og:description" content="@if($description){{@$description}}@else @yield('description') @endif"/>
	<meta property="og:title" content="@if($title){{@$title}}@else @yield('title') @endif"/>
@endif	
@elseif($controller == "BlogsController")
	@php $title_data = getBlogNameBySlug($slug); if(!empty($title_data)) { $title = $title_data->title; $og_image = $title_data->image; $og_video = $title_data->video; }
	else { $title = 'Health & Fitness Blogs | Health Gennie';}
	$description = "Valuable health & fitness blogs. If you are interested in a wide range of medical topics, these blogs are worth checking out | Health Gennie";	
	$og_type = 'artical';
	@endphp
	<title>{{trim($title)}}</title>
	<meta name="description" content="@if($description){{@$description}}@else @yield('description') @endif"/>
	<meta property="og:description" content="@if($description){{@$description}}@else @yield('description') @endif"/>
	<meta property="og:title" content="@if($title){{@$title}}@else @yield('title') @endif"/>
@elseif($controller == "LabController")
	@php $title = 'Book Diagnostic Tests from Home at Best Prices | Health Gennie';
		 $description = 'Book diagnostic tests near you from NABL & ISO certified lab at low cost with quality reports. Free sample collection at the comfort of your Home | Health Gennie';
	@endphp
	<title>{{trim($title)}}</title>
	<meta name="description" content="@if($description){{@$description}}@else @yield('description') @endif"/>
	<meta property="og:description" content="@if($description){{@$description}}@else @yield('description') @endif"/>
	<meta property="og:title" content="@if($title){{@$title}}@else @yield('title') @endif"/>
@elseif($controller == "HomeController" && $action == "index")
	<title>{{trim($title)}}</title>
	<meta name="description" content="@if($description){{@$description}}@else @yield('description') @endif"/>
	<meta property="og:description" content="@if($description){{@$description}}@else @yield('description') @endif"/>
	<meta property="og:title" content="@if($title){{@$title}}@else @yield('title') @endif"/>	
@else 		  
	<title>@yield('title')</title>
	<meta name="description" content="@yield('description')"/>
	<meta property="og:description" content="@yield('description')"/>
	<meta property="og:title" content="@yield('title')"/>
@endif
	<meta property="og:type" content="{{$og_type}}"/>
	<meta property="og:url" content="<?php echo url("/").$_SERVER['REQUEST_URI'];?>" />
	<meta property="og:image" content="{{@$og_image}}"/>
	<meta property="og:site_name" content="Health Gennie"/>
	<meta name="twitter:card" content="summary">
	<meta name="twitter:creator" content=“@Healthgennie1”>
	<meta property="twitter:url" content="https://www.healthgennie.com/" />
	<meta property="twitter:title" content="Book FREE Doctor Consultation and Appointment | Health Gennie" />
	<meta property="twitter:description" content="Book free doctor consultation and appointment from the comfort of your home via phone/video call and get digital prescriptions on your phone. Also read about health issues and get solutions." />
	<meta property="fb:app_id" content="2377613272544920" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Cache-control: max-age=31536000" content="public">
	<meta name="google-site-verification" content="cyIzmJahbpMDVzOW3EP7TROPGr8-7nkOCKUdygkEDpk" />
	<meta name="robots" content="index, follow" />
	<link rel="canonical" href="<?php echo $canonicalUrl;?>"/>
    <link rel="shortcut icon" href="/img/favicon.ico"/>
	
	<link rel="preload" as="style" href="/css/assets/bootstrap/css/bootstrap.min.css" media="all" type="text/css" defer async onload="this.onload=null;this.rel='stylesheet'" />
	<noscript><link rel="stylesheet" href="/css/assets/bootstrap/css/bootstrap.min.css"></noscript>
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="all"  />

	<link rel="stylesheet" href="/css/common.css?v=12" type="text/css" media="all" />
	
	<link rel="preload" as="style" href="/css/fonts/font-awesome.min.css" media="all" defer async onload="this.onload=null;this.rel='stylesheet'" />
	<noscript><link rel="stylesheet" href="/css/fonts/font-awesome.min.css"></noscript>

<!--	<link rel="preload" as="style" href="/css/fonts/font_raleway.css" media="all" defer async onload="this.onload=null;this.rel='stylesheet'" />
	<noscript><link rel="stylesheet" href="/css/fonts/font_raleway.css"></noscript>
	<link rel="preload" as="style" href="/css/fonts/font_family.css" media="all" defer async  onload="this.onload=null;this.rel='stylesheet'"/>
	<noscript><link rel="stylesheet" href="/css/fonts/font_family.css"></noscript>
	<link rel="preload" as="style" href="/css/fonts/font_kerala.css" media="all" defer async  onload="this.onload=null;this.rel='stylesheet'"/>
	<noscript><link rel="stylesheet" href="/css/fonts/font_kerala.css"></noscript>-->
    
    <link href="https://fonts.googleapis.com/css2?family=Yantramanav:wght@300;400;500;700&display=swap" rel="stylesheet">

	<link rel="preload" as="style" href="/css/carouselMin.css" media="all" defer async onload="this.onload=null;this.rel='stylesheet'" />
	<noscript><link rel="stylesheet" href="/css/carouselMin.css"></noscript>
	<link rel="preload" as="style" href="/css/themeMin.css" media="all" defer async  onload="this.onload=null;this.rel='stylesheet'"/>
	<noscript><link rel="stylesheet" href="/css/themeMin.css"></noscript>
    <link rel="preload" as="style" href="/css/materialize.css" media="all" defer async onload="this.onload=null;this.rel='stylesheet'"/>
	<noscript><link rel="stylesheet" href="/css/materialize.css"></noscript>
	<link rel="preload" as="style" href="/css/font-face-sidebar.css" type="text/css" media="all" async defer onload="this.onload=null;this.rel='stylesheet'"/>
	<noscript><link rel="stylesheet" href="/css/font-face-sidebar.css"></noscript>
	<script src="/css/assets/plugins/jQuery/jquery-1.12.4.min.js" type="text/javascript"></script>
	<script src="/css/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript" defer></script>
	<script src="/css/assets/plugins/jquery.ui.touch-punch.min.js" type="text/javascript" defer></script>
	<script src="/js/bootstrap.min.js" type="text/javascript" async></script>
	<script src="/js/jquery.validate.js" type="text/javascript"></script>
    <script src="/js/jquery.validate.min.js" type="text/javascript"></script>
    <link rel="preload" as="style" href="/css/select2.min.css" type="text/css" media="all" defer async onload="this.onload=null;this.rel='stylesheet'"/>
	<noscript><link rel="stylesheet" href="/css/select2.min.css"></noscript>
	<script src="/js/select2.min.js" type="text/javascript"></script>
	<script src="/js/cookieMin.js" type="text/javascript" ></script>
	<!-- Global site tag (gtag.js) - Google Ads: 681661500 -->
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WCSD8NM');</script>
<!-- End Google Tag Manager -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-681661500"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'AW-681661500');
	</script>
	<!-- Event snippet for Website lead conversion page -->
	@if($controller != "LabController")
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrzKrcKQqGvZQjuMZtDQy3MHOpNjPmjnU&libraries=places" async defer></script>
	@endif
    <script>
	    gtag('event', 'conversion', {'send_to': 'AW-681661500/zk60CPmrktUBELyohcUC'});
	    var isPaytmTab = false;
	    var pagedoc = '';
	    $(document).ready(function(){
	    	pagedoc = document;
	    	checkpermission();
			
	    }); 
	    function checkpermission(){
	    	const ua = window.navigator.userAgent;
            isPaytmTab = /AppContainer/i.test(ua);
			//console.log(isPaytmTab);
			if(isPaytmTab){ 
				pagedoc.title = 'Health Gennie';
				// document.title = 'blah';
				$('.download-app-paytm').hide();
				$('.download-app-paytmchangeDiv').show();
				//$('.download-app-paytm').attr('style', 'display: none !important; opacity: 0;');
	            $('.hideforPaytm').hide();
	            $('.hideforPaytmCart').attr('style', 'display: none !important; opacity: 0;');
	           // $('.hideforPaytmCart').hide();
	            $('.homePageDoctorsforPaytm').show();
	             $('.planCheckforPaytm').show();
	            $('.readOnlyforPaytm').attr("readonly",true);
	            $(".sub-nev-tool").addClass("paytmSet");
	            var userPaytm = '{{Auth::user()}}';
                //console.log(userPaytm);
                if(!userPaytm){
                    userApipaytm();
                }
			}else{
				$('.download-app-paytmchangeDiv').show();
				
			}
	    }
		function closemini(){
			 jQuery('.loading-all').hide();
		    JSBridge.call('popWindow');
		}
		function trymini(){
			jQuery('.loading-all').hide();
			$('#modalpaytmPermissions').modal('toggle'); 
		    checkpermission();
		}
		function tryminiForLogin(){
			jQuery('.loading-all').hide();
			$('#modalpaytmPermissionsForLogin').modal('toggle'); 
			checkpermission();
		}	
		function userApipaytm(){
            jQuery('.loading-all').show();
			function ready (callback) {
				// call if jsbridge is injected
				if(window.JSBridge) {
				callback && callback();
				} else{// listen to jsbridge ready event
				document.addEventListener('JSBridgeReady', callback, false);
			}}
			ready(function () {
				JSBridge.call('paytmFetchAuthCode',{
				clientId:"merchant-health-gennie-prod"},
				function(result) { console.log(result);
					if(result.data){
						console.log(result.data);
						jQuery.ajax({
							type: "POST",
							url: "/loginUserByPaytm",
							data: {'code':result.data.authId},
							success: function(data)
							{
								console.log(data);
								jQuery('.loading-all').hide();
								if(data.success == 1){
									//location.reload();
							$(".paytmDivforreplace").removeClass( "login" ).addClass( "dropdown sub-nev-tool paytmSet" );
							
									$(".paytmDivforreplace").html(data.content);
									
								}
							}
						});
					}else{
						if(result.error){
							if(result.error == '-1'){
								jQuery('.loading-all').hide();
								$("#modalpaytmPermissions").modal('show');
							}else{
								jQuery('.loading-all').hide();
								$("#modalpaytmPermissionsForLogin").modal('show');
								//JSBridge.call('popWindow');
								//alert('laa');
							}
						}
					}
				});
			});
		}

		//document.addEventListener('back',function(){
           //  JSBridge.call('popWindow');
		//});	
	</script>
<style type="text/css">body .navbar-nav li.dropdown.sub-nev-tool.paytmSet {right:10px !important;}
body .navbar-default .paytmSet ul#g-account-menu {
    position: absolute;
    width: 175px;
    height: 163px;
    top: 46px;
    background: #fff;
    padding: 0 10px!important;
    left: -126px;
    box-shadow: 0 1px 4px 0 #c1c0c0;
}
body .navbar-default ul#g-account-menu::after {
    top: -11px;
    left:161px;
    margin-left: 0;
    content: "";
    border-bottom-color: #fff!important;
    position: absolute;
    width: 0;
    height: 0;
    border-color: transparent;
    border-top-color: transparent;
    border-style: solid;
    border-width: 5px;
}
.download-app-paytmchangeDiv{
	display: none;
}
</style>
</head>