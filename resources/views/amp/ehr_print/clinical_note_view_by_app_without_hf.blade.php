<html lang="en">
  <head>
    <meta charset="utf-8">
    <header http-equiv="Access-Control-Allow-Origin" content="*">
    <title>Invoice</title>
    <style>
	body{ background:#fff;}
    	.mail_dp:after {
  clear: both;
  content: "";
  display: inline-block;
  height: 100%;
  vertical-align: middle;
}
.mail_dp {
  display: table;
  height: 75px;
  line-height: 75px;
  max-height: 75px !important;
  text-align: center;
  margin: 0px auto;
}
.mail_dp img {
  height: 75px;
  margin: 0 auto;
  max-width: 100%;
  object-fit: contain;
  padding: 10px;
  vertical-align:middle;
}
.GrowthChart { width:48%; float:left; padding: 10px 0px 0px 0px; margin-right: 2%;}
.GrowthChart img { width:100% !important; float:left; height: auto !important;}
@media only screen and (min-width:300px) and (max-width:640px) {
.table-section { width: 100%; float:left; padding: 0px 0px 0px 0px;}
.table-section tbody tr td span { display: none;}
.table-section tbody tr td strong {width: 100% !important; padding-top: 10px;}
.table-section tbody tr td p {width: 100% !important; float:left;}
.table-section tbody tr td {
    width: 50% !important;
    padding: 0px !important;
    margin: 0px;
    vertical-align: top;
}
.cmk-block ul { width:100% !important;}
.subjective-section-top { width:100%; float:left; padding:0px 0px 0px 0px; overflow-y: auto;}
.subjective-block-top  { width: 700px; float: left;}
.content { display: inherit !important; float: left;}
}
</style>
  </head>
  <body style="font-family: sans-serif;">
  	<div style="width: 100%; float:left; padding:0px 8px; margin-top: 100px;">
  	<div style="width:100%; margin: 0px auto; min-width:100%;">

  	<div style="width:100%; padding:0px 0px 10px 0px;" class="clearfix">
      <div style=" width:100%;border-top:1px solid #189ad4; padding-top: 10px; margin: 0px auto;" id="project">
      	<table class="table-section" style="" cellpadding="0" cellspacing="0">
      		<tbody>
      			<tr>
      				<td style="margin-bottom: 5px;"><strong style="margin-right:0px; width:126px; float:left;">Patient Name <span style="text-align:right; width: 50px; color: #189ad4; padding-left:15px; padding-right: 10px;"> :</span> </strong><p style=" margin-top: 0px; margin-bottom: 0px;"> {{$patient->Patient->first_name}} {{$patient->Patient->last_name}}</p></td>
      				<td style="margin-bottom: 5px; width:450px;"><strong style="margin-right:0px; width:126px; float:left;">Patient ID <span style="text-align:right; width: 50px; color: #189ad4;padding-left:17px; padding-right: 13px;"> :</span> </strong><p style=" margin-top: 0px; margin-bottom: 0px;"> {{@$patient->Patient->PatientRagistrationNumbers->reg_no}}</p></td>
        </tr>
        <tr>
        <td style="margin-bottom: 5px; width:629px;"><strong style="margin-right:0px; width:125px;float:left;">Age/Gender <span style="text-align:right; width: 50px; color: #189ad4;padding-left:25px; padding-right: 15px;"> :</span></strong><p style="padding: 0px; margin: 0px;">{{get_patient_age($patient->Patient->dob)}}/{{($patient->Patient->gender=="Male")?'M':'F'}}</p></td>
        <td style="margin-bottom: 5px;"><strong style="margin-right:0px; width:125px;float:left;">Contact No. <span style="text-align:right; width: 50px; color: #189ad4; padding-left:2px; padding-right: 15px;"> :</span> </strong>  {{$patient->Patient->mobile_no}}</td>
        </tr>
		 <tr>
        <td style="margin-bottom: 5px; width:629px;"><strong style="margin-right:0px; width:125px;float:left;">Date <span style="text-align:right; width: 50px; color: #189ad4;padding-left:76px; padding-right: 13px;"> :</span></strong><p style="padding: 0px; margin: 0px;">{{date('Y-m-d',strtotime($patient->start))}}</p></td>
        </tr>
        </tbody>
     </table>
     </div>

    <?php //echo "<pre>";print_r($rows);?>

      <div class="content" style="width:100%;border-top: 1px solid #189ad4; margin: 0px auto; display: table;">
      	<div style="width:100%;background: #f1f1f1; padding: 10px 10px 10px 10px; float: left;">
         <h2 style="margin: 0px; padding: 0px;">
           @if(count($pVitals) > 0)
			<strong style="font-size: 15px; font-weight: 700; color: #000;">Vitals :</strong></h2>
          <div class="subjective-section-top">
            <div class="subjective-box subjective-block-top">
              <table style="width:100%; margin-top: 0px;">
                <thead>
                   <tr>
                     <th style="font-size: 13px; text-align: left;">Temprature</th>
                      <th style="font-size: 13px; text-align: left;">Blood Presssure</th>
                       <th style="font-size: 13px; text-align: left;">Pulse Rate</th>
                        <th style="font-size: 13px; text-align: left;">Height</th>
                         <th style="font-size: 13px; text-align: left;">Weight</th>
                          <th style="font-size: 13px; text-align: left;">BMI</th>
                          <th style="font-size: 13px; text-align: left;">Head Circumference</th>
                   </tr>
                 </thead>
                <tbody>
                  <tr>
                    <td style="font-size: 13px; text-align: left;">{{$pVitals->temprature}} °C</td>
                    <td style="font-size: 13px; text-align: left;">{{$pVitals->bp_systolic}}/{{$pVitals->bp_diastolic}} MmHg</td>
                    <td style="font-size: 13px; text-align: left;">{{$pVitals->pulse_rate}} /Min</td>
                    <td style="font-size: 13px; text-align: left;">{{$pVitals->heightCm}} Cm</td>
                    <td style="font-size: 13px; text-align: left;">{{$pVitals->weight}} Kg</td>
                    <td style="font-size: 13px; text-align: left;">{{$pVitals->bmi}}</td>
                    <td style="font-size: 13px; text-align: left;">{{$pVitals->head_circumference}} Cm</td>
                  </tr>
                </tbody>
              </table>
              </div>
            </div>
			@endif
            </div>
        <div class="cmk-block" style="width:100%; margin: 0px auto; display: table;">
          <ul id="clinicData" style="width:50%; float: left; margin:0px; padding: 0px;">
            @if(count($chiefComplaints) > 0)
            <li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
              <strong style="font-size: 15px; font-weight: 700; color: #000;">Chief Complaint (s) :</strong>
            </li>
				<div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
				<?php $coms = json_decode($chiefComplaints->data); ?>
				<?php  $names=[];$len = 0;?>
				<p style="padding: 0px; margin: 0px;">
				<?php 
					foreach($coms as $com){
						$len += strlen($com->complaint_name);
						echo $com->complaint_name.",";
						if($len >38){
							$len = 0;
							echo "<br>";
						}
					} 
				?>
				</p>
				</div>
               @endif

           @if(count($pDiagnos) > 0) 
			<li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
               <strong style="font-size: 15px; font-weight: 700; color: #000;">Diagnosis :</strong>
             </li>
               <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
               @foreach ($pDiagnos as $diagno)
                        <p style="padding: 0px; margin: 0px;">{{$diagno->Diagnosis->medDesc}} {{$diagno->notes}}</p>
               @endforeach
              </div>
              @endif

            @if(count($labs) > 0)
			<li style="padding: 10px 0px 0px; margin: 10px 0px 0px; list-style: none;">
             <strong style="font-size: 15px; font-weight: 700; color: #000;">Lab Order :</strong>
			</li>
             <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
             @foreach ($labs as $lab)
                      <p style="padding: 0px; margin: 0px;">{{$lab->Labs->title}} : {{$lab->instructions}}</p>
             @endforeach
            </div>
            @endif

			@if(count($patientDiagnosticImagings) > 0)
			<li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
            <strong style="font-size: 15px; font-weight: 700; color: #000;">Diagnostic Imaging :</strong>
			</li>
            <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
            @foreach ($patientDiagnosticImagings as $dim)
                      <p style="padding: 0px; margin: 0px;">{{$dim->RadiologyMaster->title}} : {{$dim->instructions}}</p>
            @endforeach
			</div>
			@endif

			@if(count($procedures) > 0)
			<li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
            <strong style="font-size: 15px; font-weight: 700; color: #000;">Procedures :</strong>
			</li>
            <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
            @foreach ($procedures as $procedure)
                     <p style="padding: 0px; margin: 0px;">{{$procedure->Procedures->name}} : {{$procedure->notes}} </p>
            @endforeach
           </div>
           @endif

           @if(count($pAllergies) > 0)
           <li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
             <strong style="font-size: 15px; font-weight: 700; color: #000;">Allergies : </strong>
           </li>
         <?php  //print_r($pAllergies);die; ?>
             <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
             @foreach ($pAllergies as $pAllergy)
                      <p style="padding: 0px; margin: 0px;">{{$pAllergy->Allergies->title}} : {{$pAllergy->notes}}</p>
             @endforeach
            </div>
          @endif

          @if(count($examinations) > 0)
			<li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
            <strong style="font-size: 15px; font-weight: 700; color: #000;">Clinical Examination :</strong></li>
            <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
            @foreach ($examinations as $examination)
                     <p style="padding: 0px; margin: 0px;">{{$examination->BodySites->name}} : {{$examination->observation}}</p>
            @endforeach
           </div>
          @endif
		 
		@if(count($immunizations) > 0)
		<li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
		<strong style="font-size: 15px; font-weight: 700; color: #000;">Patient Immunizations :</strong>
		</li>
		<div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
			@foreach ($immunizations as $element)
				<p style="padding: 0px; margin: 0px;">@if(!empty($element->Immunizations->name)) {{$element->Immunizations->name}},  Dose {{@$element->dose_no}} @endif @if(!empty($element->given_date)) ({{$element->given_date}})  @endif</p>
			@endforeach
		</div>
		@endif

        @if(count($proce_order) > 0)
		<li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
           <strong style="font-size: 15px; font-weight: 700; color: #000;">Procedure Orders :</strong>
         </li>
           <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
           @foreach ($proce_order as $porder)
                <p style="padding: 0px; margin: 0px;">{{$porder->Procedures->name}} : {{$porder->notes}} </p>
           @endforeach
          </div>
          @endif

        	@if(count($pReferral) > 0)
			<li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
			<strong style="font-size: 15px; font-weight: 700; color: #000;">Patient Referral :</strong>
			</li>
           <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
           <p style="padding: 0px; margin: 0px;">Referral to Dr. @if($pReferral->referral_to != 0) {{getRefdocDetails($pReferral->referral_to)->doctor_name}} @else {{$pReferral->referral_to_other}} @endif</p>
           </div>
           @endif

            @if(count($dentals) > 0)
			<li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
           <strong style="font-size: 15px; font-weight: 700; color: #000;">Patient Dentals  : </strong>
           <b>
               @foreach ($dentals as $dental)
               <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
                <p style="padding: 0px; margin: 0px;">{{$dental->Procedures->name}} : {{$dental->dental_id}} </p>
                </div>
               @endforeach
           </b>
           </li>
         	@endif

       	 @if(count($followUp) > 0)
			<li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
    		 <strong style="font-size: 15px; font-weight: 700; color: #000;">Follow up date  : </strong>
    		 <b>
    		 <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
    			@if($followUp->follow_up_date != "") <p style="padding: 0px; margin: 0px;">{{$followUp->follow_up_date}}</p> @endif
    			</div>
          
    		 </b>
    		 </li>
		 @endif

        </ul>
        <ul style="width:50%; float: left; padding: 0px; margin: 0px 0px 0px;">
         	 @if(count($treatments) > 0)
			<li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
            <strong style="font-size: 15px; font-weight: 700; color: #000;">Treatment : </strong>
          </li>
            <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b;">
            @foreach ($treatments as $treatment)
                   <p style="padding: 0px; margin: 0px;"><b>@if(getItemType($treatment->ItemDetails->item_type) != "Miscellaneous") {{getItemType($treatment->ItemDetails->item_type)}} : @endif</b> {{$treatment->ItemDetails->item_name}} {{$treatment->strength}} {{$treatment->unit}} {{$treatment->frequency}} for  {{$treatment->duration}} days {{$treatment->route}} </p>
            @endforeach
           </div>
          @endif

         	   @if(count($eyes) > 0)
          <li style="padding: 0px; margin: 10px 0px 0px; list-style: none;">
            <strong style="font-size: 15px; font-weight: 700; color: #000;">Eyes Examination :</strong>
          </li>
            <div class="subjective-box" style="font-size: 13.5px; font-weight: 300; color: #2b2b2b; margin-top: 6px;">
					<table>
                        <thead>
                          <tr>
                          <th style="border-bottom: 0px; border-right:1px solid #bdbdbd;" colspan="2"></th>
                          <th style="width: 50.66px; text-align: center; padding: 3px 0px 3px 3px; border-right:1px solid #bdbdbd;border-top:1px solid #bdbdbd;border-bottom:1px solid #bdbdbd; font-size: 12px; vertical-align: top;">SPHERE</th>
                          <th style="width: 50.66px; text-align: center; padding: 3px 0px 3px 3px; border-right:1px solid #bdbdbd;border-top:1px solid #bdbdbd;border-bottom:1px solid #bdbdbd; font-size: 12px; vertical-align: top;">CYLINDER</th>
                          <th style="width: 30.66px; text-align: center; padding: 3px 0px 3px 3px; border-right:1px solid #bdbdbd;border-top:1px solid #bdbdbd;border-bottom:1px solid #bdbdbd; font-size: 12px; vertical-align: top;">AXIS</th>
                          <th style="width: 40.66px; text-align: center; padding: 3px 0px 3px 3px; border-right:1px solid #bdbdbd;border-top:1px solid #bdbdbd;border-bottom:1px solid #bdbdbd; font-size: 12px; vertical-align: top;">PRISM</th>
                          <th style="width: 40.66px; text-align: center; padding: 3px 0px 3px 3px; border-right:1px solid #bdbdbd;border-top:1px solid #bdbdbd;border-bottom:1px solid #bdbdbd; font-size: 12px; vertical-align: top;">BASE</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                          <td style="border-left: 1px solid #ccc;width:80px; border-right:1px solid #ccc; border-top:1px solid #ccc; text-align: center; padding-left: 0px;" rowspan="2">DISTANCE</td>
                          <td style="width: 40.66px; border-right:1px solid #bdbdbd; text-align: center; padding: 3px 4px; border-top:1px solid #ccc; font-size: 12px;">OD</td>
                          <td style="width: 50.66px; border-right:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->d_od_s}}</td>
                          <td style="width: 50.66px; border-right:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px; font-size: 12px;">{{$eyes->d_od_c}}</td>
                          <td style="width: 30.66px; border-right:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->d_od_a}}</td>
                          <td style="width: 40.66px; border-right:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->d_od_p}}</td>
                          <td style="width: 40.66px;border-right:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->d_od_b}}</td>
                          </tr>
                          <tr>
                          <td style="width: 40.66px; border-right:1px solid #bdbdbd; border-top:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">OS</td>
                          <td style="width: 50.66px; border-right:1px solid #bdbdbd; border-top:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->d_os_s}}</td>
                          <td style="width: 50.66px; border-right:1px solid #bdbdbd; border-top:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->d_os_c}}</td>
                          <td style="width: 30.66px; border-right:1px solid #bdbdbd; border-top:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->d_os_a}}</td>
                          <td style="width: 40.66px; border-right:1px solid #bdbdbd; border-top:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->d_os_p}}</td>
                          <td style="width: 40.66px; border-top:1px solid #bdbdbd;border-right:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->d_os_b}}</td>
                          </tr>
                          <tr>
                          <td style="border-left: 1px solid #bdbdbd;width:80px; border-right:1px solid #bdbdbd; border-top:1px solid #bdbdbd; text-align: center; padding: 3px 4px;border-bottom:1px solid #bdbdbd;" rowspan="2">ADD</td>
                          <td style="width:40.66px; border-right:1px solid #ccc; border-top:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">OD</td>
                          <td style="width: 50.66px; border-right:1px solid #ccc; border-top:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->a_od_s}}</td>
                          <td rowspan="2" colspan="4" style="border-bottom: 0px solid #fff; border-right: 0px; border-top:1px solid #bdbdbd;text-align: center; padding: 3px 4px;"></td>
                          </tr>
                          <tr>
                            <td style="width: 40.66px; border-right:1px solid #bdbdbd; border-top:1px solid #bdbdbd;border-bottom:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">OS</td>
                            <td style="width: 50.66px; border-right:1px solid #bdbdbd; border-top:1px solid #bdbdbd;border-bottom:1px solid #bdbdbd; text-align: center; padding: 3px 4px; font-size: 12px;">{{$eyes->a_os_s}}</td>
                          </tr>
                        </tbody>
                     </table>
                    </div>
                  @endif
         
        </ul>
        <div style="width:100%; margin-left:0px; margin-right: 0px; margin-top: 10px; float: left;">
        	 @if(get_age($patient->Patient->dob) <= 20)
				@if(!empty($chart) || !empty($chart_height))
			    <li style="padding: 0px; margin: 0px 0px 0px; list-style: none;">
				   <strong style="font-size: 15px; font-weight: 700; color: #000;">Growth Chart : </strong>
				</li>
				@endif
				<div class="GrowthChart">
				@if(!empty($chart))
				  <img src="{{$chart}}" width="300" height="150" style="margin-top: 0px;">
				@endif
				</div>

				<div class="GrowthChart">

				@if(!empty($chart_height))
				  <img src="{{$chart_height}}" width="300" height="150" style="margin-top:0px;">
				@endif
				</div>
			@endif	
         </div>
        </div>

      </div>
	  
				<footer style="width:100%;margin-top:75px; bottom:0px; left:0px; right:0px; color: #000; padding-right: 10px; padding-left: 10px; padding-top: 5px; padding-bottom: 5px;height: 45px; margin: 0px auto; display: table;">
				@if($patient->User->DoctorInfo->sign_view == 1)
				<span style="width:auto; text-align:left; right: 0px; float:right;">
					<img class="sign_image" style="width:120px; height:70px; background-color: #fff; color:#000;" src="<?php
					$image_res = getEhrUrl()."/public/doctor/signature/".$patient->User->DoctorInfo->doctor_sign;
					if(!empty($patient->User->DoctorInfo->doctor_sign)) {
							echo $image_res;
					}?>"/>
				</span>
				@endif
				<div style="text-align:right; margin-bottom:0px; width:100%; float:left; border-bottom: 2px solid #189ad4;" class="authority-signature"> 
				<p style="font-size: 14px; font-weight: 700; color: #189ad4; padding-top:0px; margin-right: 0px;margin-left: 0px; width: auto; float:right;">(Authority Signature)</p>
				<div style="width:auto; float:left; padding: 13px 0px 0px 0px; text-align:right; font-size: 13px;">Powered By Healthgennie</div>
				</div>
				</footer>	
    </div>
    </div>
    </div>
  </body>
</html>
