<html>
  <head runat="server">

   <style type="text/css" media="print">
    @page
		{
			size:  auto;
			margin: 4mm;


		}
    html
    {
        background-color: #fff; border:5px solid #ddd;
		height:99%;
        margin: 0px;  /* this affects the margin on the html before sending to printer */
    }
    </style>
</head>
    <body style="background-color: #fff; font-family: 'Roboto', sans-serif !important;">
            <table class="table" cellpadding="0" cellspacing="0" width="100%" style="padding:0px 0px; background-color: #fff; font-family: 'Roboto', sans-serif !important;">

        <thead>
        <tr>
            <th style="text-align: left; border-bottom: 1px solid #fff; padding: 10px 10px 10px 10px;">
            <img width="150" src="{{ URL::asset('img/health-gennie-logo23.png')}}"/></th>
        </tr>

        <tr>
            <th align="center" style="font-size:42px; color:#000; padding:20px 0 0px;">अब ऑनलाइन अपॉइंटमेंट बुक करें
</th>
        </tr>

            <tr>
                <th align="center" style="font-size:40px; color:#3766b8; padding:55px 0 0px 0;"><span style=" border-bottom: 2px solid #3766b8; padding-bottom: 6px;">@if($action == "clinic")  {{$user->clinic_name}} @else Dr. {{$user->first_name}} {{$user->last_name}} @endif</span></th>
            </tr>
            <!--
            <tr>
                <th align="center" style="font-size:30px; color:#000; padding:20px 0 0px;">Online</th>
            </tr>-->


        </thead>
        <tbody>
        <tr>
        <td style="position:relative;" align="center">
        <table cellpadding="0" cellspacing="0" style="width: 100%; padding: 80px 20px 0px 20px">
            <tbody>
                <tr>
                    <td style="position: relative;">
                        <span style="font-size:12px; width: 100%; padding:0px 10px 10px 10px ; z-index: 9999; position: absolute; margin-top: -65px; left: -10px;"><img width="172" src="{{ URL::asset('img/mobile-icon234.png')}}"/></span>
                        <img width="170" style="margin-top:-32px; border: 1px solid #3766b8;" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->merge(URL::asset('img/qrlogoGennie.png'), 0.3, true)
                                ->size(400)->errorCorrection('H')
                                ->generate($user->url)) !!} ">
                    </td>
                    <td>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
            <td style="padding: 0px 10px 10px; color:#ff6100; font-size:16px; text-transform: uppercase; font-weight: 500;">
                <h2 style="padding: 0px; margin: 0px;">अपॉइंटमेंट के लिए दिए गए निर्देशों का पालन करें</h2>
            </td>
        </tr>

        <tr>
            <td style="color: #000; font-size: 21px; line-height: 36px; padding: 10px 0px 10px 10px;">
                <span style=" width:35px; height:35px; color: #fff;background-color: #3766b8; font-weight: 700; font-size: 19px; text-align:center; padding: 0px 0px 0px; display: inline-table; border-radius: 100%; margin-right: 10px;">1</span><span style="color:#3766b8;">www.healthgennie.com</span> पर जायें।
</span>
            </td>
        </tr>
        <tr>
            <td style="color: #000; font-size: 21px; line-height: 36px; padding: 0px 0px 10px 10px;">
                <span style=" width:35px; height:35px; color: #fff;background-color: #3766b8; font-weight: 700; font-size: 19px; text-align:center; padding: 0px 0px 0px; display: inline-table; border-radius: 100%; margin-right: 10px;">2</span>सर्च सेक्शन में @if($action == "clinic")  '{{$user->clinic_name}}' @else ‘{{$user->first_name}} {{$user->last_name}}' @endif लिखें।
            </td>
        </tr>

        <tr>
            <td style="color: #000; font-size: 21px; line-height: 36px; padding: 0px 0px 10px 10px;">
                <span style=" width:35px; height:35px; color: #fff;background-color: #3766b8; font-weight: 700; font-size: 19px; text-align:center; padding: 0px 0px 0px; display: inline-table; border-radius: 100%; margin-right: 10px;">3</span>बुक अपॉइंटमेंट पर क्लिक करें।

            </td>
        </tr>
        <tr>
            <td style="color: #000; font-size: 21px; line-height: 36px; padding: 0px 0px 10px 10px;">
                <span style=" width:35px; height:35px; color: #fff;background-color: #3766b8; font-weight: 700; font-size: 19px; text-align:center; padding: 0px 0px 0px; display: inline-table; border-radius:100%; margin-right: 10px;">4</span>अपने अपॉइंटमेंट के समय का चयन करें।

            </td>
        </tr>
        <tr>
            <td style="color: #000; font-size: 21px; line-height: 36px; padding: 0px 0px 10px 10px;">
                <span style=" width:35px; height:35px; color: #fff;background-color: #3766b8; font-weight: 700; font-size: 19px; text-align:center; padding: 0px 0px 0px; display: inline-table; border-radius: 100%; margin-right: 10px;">5</span>आपको अपॉइंटमेंट का कॉन्फ़र्मेशन SMS द्वारा प्राप्त होगा।

            </td>
        </tr>
    </table>
  </td>
                </tr>
            </tbody>
        </table>
            </td>
        </tr>

        <tr>
            <td style="text-align:center; width:100%; padding-top:40px;" align="center">
                <table cellpadding="0" cellspacing="0" align="center" width="100%" style=" text-align:center; width:100%; float:left;">
                    <tr>
                        <td colspan="2" style="font-size:25px; color: #000; padding: 30px 0px 0px 0px; vertical-align:top; text-align: center;">
                        <p style="margin:0px; padding:7px 10px 0 0; width:100%;">बेहतर अनुभव के लिए डाउनलोड करें <span style="color:#3766b8;"><strong>Health Gennie App</strong></span></p>
                    </td>
                    </tr>

                    <tr>
                        <td width="45%" style="font-size:20px; color: #000; padding: 15px 0px 0px 0px; vertical-align:top; text-align: center;">
                        <img style=" margin:10px 0 0 0" width="150" src="{{ URL::asset('img/app-store-button.png')}}"/> <p style="margin:0px; padding:5px 10px 0 0; font-size:20px; font-weight:500;">'Health Gennie - Care at Home'<br></p>
                    </td>
                    <td width="45%" style="font-size:20px; color: #000; padding: 15px 0px 0px 0px; vertical-align:top; text-align: center;">
                        <img style=" margin:10px 0 0 0" width="150" src="{{ URL::asset('img/google-play-button.png')}}"/> <p style="margin:0px; padding:5px 10px 0 0; font-size:20px; font-weight:500;">'Health Gennie - Health Care at Home'<br></p>
                    </tr>

                </table>
            </td>
        </tr>

        </tbody>

        </table>
            <div class="qrFooterprint" style="position:absolute; bottom:15px; text-align:left; left: 0px; width:100%; padding:0px 0px; font-size:20px;">
            <table style="width: 100%; padding: 8px 20px; font-family: 'Roboto', sans-serif !important;" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td style=" color: #000; font-size: 25px; font-weight:600; text-align: center;">सहायता हेतु  संपर्क करे</td>
                </tr>
            </tbody>
            </table>
            <table style="width: 100%; background: #3766b8; padding: 8px 20px; font-family: 'Roboto', sans-serif !important;" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td style="color: #fff; font-size: 18px;">www.healthgennie.com</td>
                        <td style="color: #fff; font-size: 18px;">info@healthgennie.com</td>
                        <td style="color: #fff; font-size: 18px;">+91 8949597147</td>
                    </tr>
                </tbody>
            </table>
        </div>
	</body>
	</html>
