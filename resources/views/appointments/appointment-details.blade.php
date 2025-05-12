@extends('layouts.Masters.Master')
@section('title', 'Appointment Details | Health Gennie')
@section('description', "To schedule an appointment use book an appointment button on the left menu. You can also rebook an appointment with the same doctor.")
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
@include('users.sidebar')
	<div class="dashboard-right appoint-detail">
	     <div class="appointmentDetails">
	     	<div class="doc-details">
	     		<?php
	     			if(!empty(@$appointment->user->doctorInfo->profile_pic)) {
					  $image_url = getPath("public/doctor/ProfilePics/".@$appointment->user->doctorInfo->profile_pic);
					  if(does_url_exists($image_url)) {
						$image_url = $image_url;
					  }
					  else{
						$image_url = null;
					  }
					}
	     		?>
	     		 <div class="doc-img"><img @if(@$appointment->user->doctorInfo->profile_pic != null) src="{{$image_url}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="icon"  /> </div>
	     		<p class="doc-name"> <strong>Dr. {{ucfirst(@$appointment->user->doctorInfo->first_name)}} {{@$appointment->user->doctorInfo->last_name}}</strong> </p>
				<div class="doc-fees-appt-time">
	     		<div class="doc-fees">
	     			<label>Consultation Fees</label>
	     			@if(isset($appointment->Appointmentorder) && $appointment->Appointmentorder->type == '0')
						<strong> <strike>{{number_format(getSetting("tele_main_price")[0],2)}}/-</strike> FREE </strong>
					@else
	     			<p>₹{{@$appointment->user->doctorInfo->oncall_fee}}/-</p>
					@endif
	     		</div>
	     		<div class="appt-dateTime">
	     			<label>Date & Time :</label>
	     			<p>{{date('j F , Y',strtotime($appointment->start))}} {{date('g:i A',strtotime($appointment->start))}}</p>
	     		</div>
	     	</div>
	     	</div>
	     	
	     	<div class="user-details">
				<?php
				if(!empty(@$appointment->patient->image)) {
					$image_url = getPath("public/patients_pics/".$appointment->patient->image);
				}
				else{
					$image_url = null;
				} ?>
	     		<div class="user-img"><img class="top-user-img" @if($image_url != null) src="{{$image_url}}" @else src="{{ URL::asset('img/avatar_2x.png') }}" @endif/> </div>
	     		<div class="content--filed-wrapper">
					<div class="user-name"><p> {{$appointment->patient->first_name}} {{$appointment->patient->last_name}} </p></div>
					<div class="user-mobile"><p> {{@$appointment->patient->mobile_no}}</p></div>
					<div class="user-email"><p>{{@$appointment->patient->email}} </p></div>
					<div class="download-div">
						@if($appointment->status != '0')
						<a href="{{ route('downloadReceipt',['aPiD' => base64_encode($appointment->id) ]) }}" class="downloadReceipt btn btn-info">Download Details</a>
						@endif
					</div>
					<div class="registration-wrap user-info profile-exam profile-exam123">
	     		@if(count($documents) > 0)
	     		@foreach($documents as $document)
	     		<div class="user-documents">
	     			<div class="document-div">
	     				<span class="removeDocument" docName="{{$document['doc_name']}}"><i class="fa fa-times" aria-hidden="true"></i></span>
	     				<img src="{{$document['document']}}" alt="Document" class="showImg" filename="{{$document['document']}}"/>
	     			</div>
	     		</div>
	     		@endforeach
	     		@else
	     			<div class="profile-examNoPrescription">
						<img width="140" src="../img/No_Prescription.png" />
						<p>No Documents Uploaded </p>
					</div>
	     		@endif
				<div class="profile-examNoPrescription">
					<div class="btn text-center heading-four btn-positive" data-toggle="modal" data-target="#uploadPrescriptionModal">Upload records</div>
				</div>
			</div>
			</div>
	     	</div>
	     	
	     	
	     </div>
	</div>
</div>
		  <div class="modal fade myModalss-billing-items cld" id="uploadPrescriptionModal" role="dialog" data-backdrop="static" data-keyboard="false">
		  {!! Form::open(array('route' => 'uploadDocument','method' => 'POST', 'id' => 'uploadDocument', 'enctype' => 'multipart/form-data')) !!}
		  {{ csrf_field() }}
		  <input type="hidden" name="patient_number" value="{{@$appointment->patient->patient_number}}">
		  <input type="hidden" name="appointment_id" value="{{@$appointment->id}}">
			  <div class="modal-dialog">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">Upload Document</h4>
					</div>
					<div class="modal-body">
					  <div class="aad-items-billable-section">
						
						<span class="help-block"></span>
						<div class="prescription_image_browse">
							<div class="image_apload">
								<p class="user-presFilePdf user-presFile" style="display:none;"></p>
								<img style="background-size: cover;" class="top-user-img user-presFileImg user-presFile" src="../img/Upload-Document-pri.png" alt="icon"/>
							</div>
							
							<span id="fileselector">
                 <label class="btn btn-default" for="upload-file">
                     <input id="upload-file-selector" class="prescription_imageBlob" type="hidden" name="prescription_imageBlob">
                     <input id="upload-file" type="file" name="document" class="myFile" onchange="openFileProfile(event)">
                    BROWSE
                 </label>
              </span>
							<p class="errCls"></p>
						</div>
					  </div>
				  </div>
				  <div class="modal-footer">
					<button name="submit" type="submit" class="btn btn-default finalSubmit">Save</button>
					<button name ="clear" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				  </div>
				</div>
			</div>
		  {!! Form::close() !!}
		</div>
		
		<!-- Modal -->
<div class="modal fade" id="documentViewModel" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close closeImageModal">&times;</button>
      </div>
      <div class="modal-body">
          <iframe src="" style="width: 100%; height:570px;" frameborder="0" id="DocumentView"></iframe>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
	<script>
jQuery("#uploadDocument").validate({
	rules: {
		document: "required"
	},
	messages: {
		document : "document is required",
	},
	errorPlacement: function(error, element) {
		 error.appendTo(element.next());
	},ignore: ":hidden",
	submitHandler: function(form) {
		$('.loading-all').show();
		$(".finalSubmit").attr("disabled",true);
		if($("#uploadDocument").find(".prescription_imageBlob").val() || $("#uploadDocument").find(".prescription_imageBlob").val() != "" || $("#uploadDocument").find(".myFile").val() != "") {
			// $(form).submit();
			  jQuery.ajax({
			  type: "POST",
			  url: "{!! route('uploadDocument')!!}",
			  data:  new FormData(form),
			  contentType: false,
			  cache: false,
			  processData:false,
			  success: function(data) {
					$(".finalSubmit").attr("disabled",false);
					 if(data==1) {
						jQuery('.loading-all').hide();
						location.reload();
					 }
					 else{
						 jQuery('.loading-all').hide();
					 }
			   },
			   error: function(error){
					$(".finalSubmit").attr("disabled",false);
					jQuery('.loading-all').hide();
					if(error.status == 401 || error.status == 419) {
						location.reload();
					}
					alert(error);
			   }
			});
		}
		else {
			$(".finalSubmit").attr("disabled",false);
			$('.loading-all').hide();
			 $.alert({
				title: 'Alert !',
				content: 'Prescription file is required!',
				draggable: false,
				type: 'red',
				typeAnimated: true,
				buttons: {
					ok: function(){
					location.reload();
					},
				}
			  });
		}
	}
});
jQuery(document).on("click", ".removeDocument", function (e) {
	if(confirm("Are you sure want to Delete this Document")) {
		var current = this
		var patient_number =  $('#uploadDocument').find('input[name="patient_number"]').val();
		var appointment_id = $('#uploadDocument').find('input[name="appointment_id"]').val();
		var doc_name = $(this).attr('docname');
		jQuery('.loading-all').show();
		jQuery.ajax({
			type: "POST",
			dataType : "JSON",
			url: "{!! route('deleteDocument')!!}",
			data:{'appointment_id':appointment_id,'patient_number':patient_number,'document':doc_name},
			success: function(data) {
				if(data = 1){
					$(current).closest('.document-div').remove();
					jQuery('.loading-all').hide();
					// location.reload();
				}
				else {
					alert("Oops Something goes Wrong.");
				}
				
			},
			error: function(error) {
				jQuery('.loading-all').hide();
				if(error.status == 401){
					alert("Session Expired,Please logged in..");
					location.reload();
				}
				else{
					alert("Oops Something goes Wrong.");
				}
			}
		});
	}
});
		// var preFileCheck = '';
function openFileProfile(event) {
	$(".finalSubmit").attr("disabled",false);
	var input = event.target;
	console.log(event);
	if(input.files.length) {
         // preFileCheck = $("#uploadDocument").find(".myFile").val();
		var FileSize = input.files[0].size / 1024 / 1024; // 1in MB
		var ext = input.files[0].name.split('.').pop().toLowerCase();
		var reader = new FileReader();
		if(FileSize > 10) {
			$('.myFile').val('');
			$('.prescription_image_browse').find(".errCls .help-block").remove();
			$('.prescription_image_browse').find(".errCls").html('<span style="width:100%" class="help-block">Allowed file size exceeded. (Max. 3 MB)</span>');
			$(".finalSubmit").attr("disabled",true);
		}
		else if($.inArray(ext, ['png','jpg','jpeg','pdf']) >= 0) {
			$(".finalSubmit").attr("disabled",false);
			$('.prescription_image_browse').find(".errCls .help-block").remove();
			reader.addEventListener("load", function () {
				if(ext == "pdf") {
					$(".user-presFileImg").hide();
					$('.user-presFileImg').attr('src',"");
					$('.prescription_imageBlob').val("");
					$('.user-presFilePdf').text("PDF");
					$(".user-presFilePdf").show();
				}
				else {
					$(".user-presFilePdf").hide();
					$('.user-presFilePdf').text("");
					$('.user-presFileImg').attr('src',reader.result);
					$(".user-presFileImg").show();
				}
				$(".image_apload").show();
			},false);
			reader.readAsDataURL(input.files[0]);
			if(ext != "pdf") {
				canvasResize(input.files[0], {
					width: 800,
					height: 600,
					crop: false,
					quality: 60,
					rotate: 0,
					callback: function(data, width, height) {
						console.log(data);
						var raw_image_data = data.replace(/^data\:image\/\w+\;base64\,/, '');
						$('.prescription_imageBlob').val(raw_image_data);
						$('.myFile').val('');
					}
				});
			}
		}
		else {
			$('.myFile').val('');
			$('.prescription_image_browse').find(".errCls .help-block").remove();
			$('.prescription_image_browse').find(".errCls").html('<span style="width:100%" class="help-block">Only formats are allowed : (jpeg,jpg,png,pdf)</span>');
			$(".finalSubmit").attr("disabled",true);
		}
    }
	else{
    	$(".image_apload").hide();
		$('.prescription_imageBlob').val("");
    }
}
jQuery(document).on("click", ".showImg", function (e) {
  var file = $(this).attr('filename');
      jQuery('.loading').hide();
      jQuery('#documentViewModel').modal('show');
      jQuery('#documentViewModel').show();
      jQuery('#DocumentView').attr('src', file);
      setTimeout(function(){
       jQuery("#DocumentView").contents().find("img").css({'width': '100%'});
      }, 300);
      $('#DocumentView').load(function() {
          $(this).contents().find('img').css({'width': '100%'});
      })
});
jQuery(document).on("click", ".closeImageModal", function (e) {
	$("#documentViewModel").hide();
	// jQuery('#documentViewModel').modal('hide');
});
</script>
<script src="{{ URL::asset('js/zepto.min.js') }}"></script>
<script src="{{ URL::asset('js/binaryajax.js') }}"></script>
<script src="{{ URL::asset('js/exif.js') }}"></script>
<script src="{{ URL::asset('js/canvasResize.js') }}"></script>
@endsection
