<!DOCTYPE html>
<html lang="en">
 <head>
 <title>Health Gennie</title>
	<meta name="description" content="Book Appointment Online" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
	<meta name="google-site-verification" content="cyIzmJahbpMDVzOW3EP7TROPGr8-7nkOCKUdygkEDpk" />

    <link rel="shortcut icon" href="{{ URL::asset('img/favicon.ico') }}"/>

    <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&amp;subset=latin-ext" rel="stylesheet">


    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Karla&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">

    <script src="{{ URL::asset('js/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('css/assets/plugins/jQuery/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('css/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src='{{ URL::asset("js/bootstrap.min.js") }}'></script>
    <style>
    	.form-fields.typeField { width: 100%; float:left; padding: 0px 0px 0px 0px;}
.form-fields.typeField label { width: 100%; padding: 0px 0px 0px 0px; margin: 0px;}
.form-fields.typeField .radio-wrap { width: 50%; float:left; padding: 0px 0px 0px 0px;}
    </style>
 </head>
 <body>

<div class="wrapper-content">
<div class="right-section new-tabs-section">
  <div class="right-block">
     <div class="container">
      <div class="tab-content" style="width: 50%;
    float: none;
    border: 1px solid #ddd;
    border-radius: 10px;
    margin: 5% auto;
    padding: 60px 0 150px 0; box-shadow:0 5px 15px #ddd;">
        <div class="tabs-reload tab-pane active">
          <div class="right-box">
             <div class="aad-inventory-section">

               <div class="Get_Widget_top" style="width:100%; padding: 10px 15px;">
               <h3 style="color: #3766b8; margin-bottom: 5px; float: none;  width: 100%;  border-bottom: 0px solid #3766b8;  padding: 0 0 8px;  font-weight: 600; text-align: center;  font-size: 30px; margin: 0 auto !important;">Download Health Gennie App</h3>
    
    <h4 style="color: #222; margin-bottom: 50px; float: left; width: 100%; padding: 0 0 8px; font-weight: 600; text-align: center;
    font-size: 16px;
">Through QR Code</h4>
             
               <div class="qrPrintMaincl" style="width: 35%; float: none; text-align: center; margin: 0 auto;">
               <p style="width:auto; border: 2px solid #3766b8; border-radius: 3px; display:inline-block;">
                <img style="max-width:100%; background:#003d76;"  src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->merge(URL::asset('img/qrlogoGennie.png'), 0.3, true)
                        ->size(400)->errorCorrection('H')
                        ->generate($url)) !!} "/>
                </p>
                </div>
                
                <div style=" float:left; width:auto;padding: 5px 30px 26px; width:100%; text-align:center;">
                
                
               <div class="top-section-search" style="display:none;">
			    <button type="button" class="btn btn-default printqrCode" style="border-radius: 3px !important;
					padding: 8px 35px;
					background: #3766b8;
					border: 0;
					color: #fff;
					font-weight: 600;
					width: 40%;" >Print</button>	
             </div>
             </div>



            </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div></div>
<div class="printData" style="display:none;"></div>
<script src="{{ URL::asset('js/jquery-printme.js') }}"></script>
<script>
 /* $(document).on("click", ".printqrCode", function (e) {
    jQuery.ajax({
    type: "POST",
	headers: {'X-CSRF-TOKEN':'{{csrf_token()}}'},
    dataType : 'html',
    url: "{!! route('qrcode.print') !!}",
    success: function(res) {
      $('.loading-all').hide();
      $('.printData').html(res);
      $('.printData').printMe();
    }
    });
  });  */
</script>
</body>
</html>