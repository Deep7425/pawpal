
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <title>[SUBJECT]</title>
    <style type="text/css">
      body {background: #ffffff; font-family:"Lucida Grande", "Helvetica Neue", Helvetica, Arial, sans-serif; font-size:16px; line-height:150%; color:#444; margin:0; padding:0;}
        p{margin-bottom:14px; margin-top:0;font-family:"Lucida Grande", "Helvetica Neue", Helvetica, Arial, sans-serif;font-size:16px;color:#999999; line-height:150%;}
        td, div {font-family:"Lucida Grande", "Helvetica Neue", Helvetica, Arial, sans-serif;font-size:13px; }

        h1 {margin-top:15px;padding:0; color:#199ad4; font-size:18px; line-height:34px;font-family:"Lucida Grande", "Helvetica Neue", Helvetica, Arial, sans-serif;font-weight: normal;}
        .address a {
          color: white !important;
          text-decoration: none;
      }
      .mail_dp:after {clear: both;content: "";display: inline-block;height: 100%;vertical-align: middle;}
      .mail_dp {display: table;height: 75px;line-height: 75px;max-height: 75px !important;text-align: center;}
      .mail_dp img {height: 75px;margin: 0 auto;max-width: 100%;object-fit: contain;padding: 10px;vertical-align:middle;}

  @media only screen and (max-width:480px)

{

table[class="MainContainer"], td[class="cell"]
	{
		width: 100% !important;
		height:auto !important;
	}

	td[class="specbundle3"]
	{
		width:90% !important;
		float:left !important;
		font-size:14px !important;
		line-height:18px !important;
		display:block !important;
		padding-left:5% !important;
		padding-right:5% !important;
		padding-bottom:20px !important;
	}




}

@media only screen and (max-width:540px)

{

table[class="MainContainer"], td[class="cell"]
	{
		width: 100% !important;
		height:auto !important;
	}

	td[class="specbundle3"]
	{
		width:90% !important;
		float:left !important;
		font-size:14px !important;
		line-height:18px !important;
		display:block !important;
		padding-left:5% !important;
		padding-right:5% !important;
		padding-bottom:20px !important;
	}




	.font{
		font-size:26px !important;
		line-height:29px !important;

		}
}



      </style>

  </head>
    <body paddingwidth="0" paddingheight="0" bgcolor="#d1d3d4"  style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
<!-- main container -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tbody>
    <tr>
          <td colspan="3" class='movableContentContainer' align="center"><div class="movableContent" style="border: 0px; padding-top: 0px; position: relative;">
              <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" class="MainContainer table">
              <tbody>
                  <tr style="padding:5px 0;  background: #28508c;">


                  <td width="165" valign="top" height="60" style="padding-left:5px; box-sizing:border-box;" class="mail_dp"><img data-default="placeholder" data-max-width="135" data-max-height="75" src='{{getEhrUrl()."/images/logoMail.png"}}' alt="Logo" title="Logo" width="135" height="75" ></td>
                  <td class="em_title" width="300" align="center" valign="middle" height="60" style="font-size:26px; color:#FFF; padding-top:10px; line-height:normal;"></td>
                  <td width="135" style="font-size:40px; color:#FFF; padding-top:10px; line-height:normal;" valign="top" height="60">&nbsp;</td>
                </tr>
                <tr bgcolor="#e68f21">
                <td colspan="3" align="center" style="font-size:20px; color:#FFF; padding:5px 0;">{!! $mailTitle !!}</td>
              </tr>
				@if(isset($practiceData->appointment_type) && $practiceData->appointment_type == 3)
				@else  
                <tr>
                  <td valign="middle" style="padding:0 0 0 10px; background:#e2e2e2; border-bottom-left-radius: 10px;"><img data-default="placeholder" data-max-width="135" data-max-height="75" alt="Logo" title="Logo" width="135" height="75" src="<?php if(!empty($practiceData->logo)){ echo getEhrUrl()."/public/doctor/".$practiceData->logo; } else { echo getEhrUrl()."/img/camera-icon.jpg"; }?>" /></td>
                  <td colspan="2" style="padding:0 20px 0 112px; background:#e2e2e2; border-bottom-right-radius: 10px;">
                      <p style="font-size:13px; font-weight:bold; margin-bottom:10px; margin-top:10px; color:#28508c;">{!! $practiceData->clinic_name !!}</p>
                      <p style="font-size:13px; font-weight:bold; margin-bottom:10px; color:#28508c;">
                        {!! $practiceData->address_1 !!},{{getLocalityName($practiceData->locality_id)}}, {{getCityName($practiceData->city_id)}},{{getStateName($practiceData->state_id)}} @if(!empty($practiceData->zipcode)) ,{{$practiceData->zipcode}} @endif ,</br>Ph. No. : {{$practiceData->mobile}}</p></td>
                </tr>
				@endif
                  <tr>
                    <td colspan="3" class="specbundle3" style="background:#FFF; padding:0 10px;">
                      <div class="contentEditableContainer contentTextEditable">
                         {!! $content !!}
                      </div>
                    </td>
                  </tr>
                  <tr>
                  <td colspan="3" style="padding:5px 0;"></td>
                  </tr>
                  <tr>
                  <td colspan="2" style=" background:#e68f21;font-size:12px;color:#FFF; padding:10px 0 10px 10px;">Fitkid Health Tech Pvt. Ltd. All Rights Reserved</td>
                  <td width="200" class="address" style=" background:#e68f21;font-size:12px; color:#FFF !important; padding:10px 10px 10px 0;">C-94 Lal Kothi scheme, Jaipur</td>
                  </tr>
                  <tr>
                  <td colspan="3" style=" background:#28508c;font-size:12px;color:#FFF; padding:15px 0 0 10px">Call us at – <a href='tel:{{getSetting("helpline_number")[0]}}' style="color:#FFF; text-decoration:none;">{{getSetting("helpline_number")[0]}}</a></td>
                  </tr>
                  <tr>
                  <td colspan="2" style=" background:#28508c;font-size:12px;color:#FFF; padding:0 0 10px 10px">Email : <a href="mailto:info@healthgennie.com" style="color:#FFF; text-decoration:none;">info@healthgennie.com</a></td>
                  </tr>
                </tbody>
            </table>
            </div></td>
        </tr>
  </tbody>
    </table>
</body>
  </html>