<html>
<head>
<title>Service Unavailable | Health Gennie</title>
<meta name="description" content="Service Unavailable | Health Gennie"/>
<meta property="og:description" content="Service Unavailable | Health Gennie"/>
<meta property="og:site_name" content="Health Gennie"/>	
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="google-site-verification" content="cyIzmJahbpMDVzOW3EP7TROPGr8-7nkOCKUdygkEDpk" />
<meta name="robots" content="index, follow" />
<link rel="shortcut icon" href="{{ URL::asset('img/favicon.ico') }}"/>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,400;0,500;0,600;0,700;1,100&display=swap" rel="stylesheet">
<style>
body { padding:0px; margin:0px;}
.some_maintenance_503 { width:100%; float:left; padding:0px 0px 0px 0px;font-family: 'Karla', sans-serif !important;}
.some_maintenanceTop_503 {
    width: 100%;
    float: left;
    padding: 0px 0px 0px 0px;

}
.some_maintenanceTip_503 {
    width: auto;
    float: left;
    padding: 0px 0px 0px 0px;
    text-align: left;
    position: absolute;
    top: 25%;
    margin: auto;
    left: 6%;
    z-index: 9999;
}

.some_maintenanceTip_503 h1 {
    padding: 0px 0px 10px;
    margin: 0px;
    color: #3d3d3d;
    font-size: 40px;font-family: 'Montserrat', sans-serif;
}
.some_maintenanceTip_503 p {
    padding: 0px 0px 20px;
    margin: 0px;font-family: 'Montserrat', sans-serif;
    font-size: 17px;
    font-weight: 400;
}
.some_maintenance_503 img{
    position: absolute;
    height:100%;
    width: 100%;
  
} 
.mob-error{display:none;}
.some_maintenanceTip_503 .btn-primary {background-color: #ff8269!important;
    color: #fff;
    padding: 12px 40px; cursor: pointer;
    border: 0px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    border-radius: 4px;}
	
@media only screen and (max-width: 639px) {  
	.mob-error{display:block;}
.desktop-error{display:none;}
body .some_maintenanceTip_503{top:3%;}
body .some_maintenanceTip_503 h1{font-size: 32px;}
.some_maintenance_503 img{width:auto; right:0px;}
}

@media only screen and (min-width: 640px) and (max-width: 767px) {	.some_maintenanceTip_503 h1 {font-size:22px;} .some_maintenanceTip_503 p {

    font-size: 14px; 

}}  
@media only screen and (max-width: 720px) { 
.some_maintenanceTop_503 {
    background: url(././img/503-image2.png) no-repeat;
}
}
</style>
</head>
<body>
<div class="some_maintenance_503">
<img src="{{ URL::asset('img/error-mob.jpg') }}" class="mob-error" />
	<img src="{{ URL::asset('img/error-page.jpg') }}" class="desktop-error" />
		<div class="some_maintenanceTOp_503">
			<div class="some_maintenanceTip_503">
				<h1>Oops, Something went wrong.</h1>
				<p>Please click the back button below to go back.</p>
				<button type="submit" onclick="window.history.go(-1); return false;" class="btn btn-primary">Go Back</button>
				<a href="https://www.healthgennie.com" class="btn btn-primary">Home</a>
			</div>
		</div>
</div>
</body>
</html>