<!DOCTYPE html>
<html lang="en">
<head>
<title>Download Health Gennie App Now</title>
<meta name="description" content="Want to help your friends and family? Share this app with your near and dear ones and make your contribution in keeping them healthy."/>
<meta property="og:description" content="Want to help your friends and family? Share this app with your near and dear ones and make your contribution in keeping them healthy."/>
<meta property="og:title" content="Download Health Gennie App Now"/>
<meta property="og:type" content='{{url("/")}}'/>
<meta property="og:url" content="<?php echo url("/").$_SERVER['REQUEST_URI'];?>" />
<meta property="og:image" content="{{ URL::asset('img/hg-app-icon.png') }}"/>
<meta property="og:site_name" content="Health Gennie"/>	
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="google-site-verification" content="cyIzmJahbpMDVzOW3EP7TROPGr8-7nkOCKUdygkEDpk" />
<meta name="robots" content="index, follow" />
<link rel="shortcut icon" href="{{ URL::asset('img/favicon.ico') }}"/>
<script src="{{ URL::asset('js/jquery.min.js') }}"></script>
</head>
<body>
<p>Download Health Gennie App</p>


<script>
jQuery(document).ready(function () {
	setTimeout(function () {
		var url = 'https://www.healthgennie.com/download';
		window.location = url;
	}, 50);
});
</script>
</body>
</html>