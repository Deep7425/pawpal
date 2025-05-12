@extends('amp.layouts.Masters.Master')
@section('title', 'User Prescription | Health Gennie')
@section('description', "View your prescription by clicking on view button and also you can upload your records to see from anywhere")
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
	@include('users.sidebar')
	<style>
	.dropdown-content {
	  display: none;
	  position: absolute;
	  right: 0;
	  background-color: #f9f9f9;
	  min-width: 160px;
	  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	  z-index: 1;
	}

	.dropdown-content a {
	  color: black;
	  padding: 8px 16px;
	  text-decoration: none;
	  background:#efefef !important;
	  border-bottom:1px solid #fff;
	  display: block;
	}
	.dropdown-content a:hover{ background:#14bef0 !important; color:#fff;}
	.dropdown-content a:hover {background-color: #f1f1f1;}
	.dropdown:hover .dropdown-content {display: block;}
	</style>
	<div class="dashboard-right">
		<div class="container-inner appointment-data-div appointmetn-list">
		   @if(session()->get('message'))
			  <div class="alert alert-success">
				<strong>Success!</strong> {{ session()->get('message') }}
			  </div>
		  @endif
			@if(isset($user) && count($user) > 0)
			<div class="listing my-priscription-head">
				  <div class="my-priscription-tag">
						<p>My Prescriptions({{$user->total()}})</p>
				  </div>
				  <div class="btn text-center heading-four btn-positive" data-toggle="modal" data-target="#uploadPrescriptionModal">Upload Prescriptions</div>
			</div>
		    @endif
			@if(isset($user) && count($user) > 0)
				@foreach($user as $app)
				@if($app->type == '1')
				<div class="listing">
					<div class="date-wrapper">
						<p class="date-highlight"><span class="app-time-highlighted">@if(!empty($app->aptDate)) {{date('j',strtotime($app->aptDate))}} @endif</span><div class="schedule-date">@if(!empty($app->aptDate)){{date('F , Y',strtotime($app->aptDate))}}
						<br/><strong>{{date('g:i A',strtotime($app->aptTime))}}</strong>
						@endif</p></div>
					</div>
					<div class="profile-detail">
						<a href="javascript:void(0);"><h3>Visited Dr. {{ucfirst(@$app->doc_name)}} for Consultation</h3></a>
						<ul>
							<li>
								Prescription
							</li>
						</ul>
					</div>
					<div class="dropdown" style="float:right; width:45px; text-align:center;">
					  <i class="fa fa-ellipsis-v detail-btn"></i>
					  <div class="dropdown-content">
						<a class="sharePrescription" href="javascript:void(0);" data-id="{{@$app->id}}" href="javascript:void(0);" data-src="{{$app->prescription}}">Share</a>
						<a class="viewPDFPres" href="javascript:void(0);" app_id="{{@$app->id}}" Dtype="2" src="{{$app->prescription}}" >Download</a>
					  </div>
					</div>
						@if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '1')
						  <div class="list-bottom sponsered-btn">
							<div class="cal-doctor"><a class="btn viewPDFPres" href="javascript:void(0);" src="{{$app->prescription}}" app_id="{{@$app->id}}" Dtype="2" ><img src="{{ URL::asset('img/view.png')}}"  alt="icon" />View</a></div>
							</div>
						@else
						 <div class="list-bottom sponsered-btn">
							<div class="cal-doctor"><a class="btn viewPDFPres" src="{{$app->prescription}}" href="javascript:void(0);" app_id="{{@$app->id}}" Dtype="1"><img src="{{ URL::asset('img/view.png')}}" alt="icon" />View</a></div>
						 </div>
						@endif
					</div>
				@else
					<div class="listing">
						<div class="date-wrapper">
							<p class="date-highlight"><span class="app-time-highlighted">@if(!empty($app->aptDate)) {{date('j',strtotime($app->aptDate))}} @endif</span><div class="schedule-date">@if(!empty($app->aptDate)){{date('F , Y',strtotime($app->aptDate))}}
							<br/><strong>{{date('g:i A',strtotime($app->aptTime))}}</strong>
							@endif</p></div>
						</div>
						<div class="profile-detail">
							<a href="javascript:void(0);"><h3>{{ucfirst(@$app->doc_name)}}</h3></a>
							<ul>
								<li>
									Prescription
								</li>
							</ul>
						</div>
						<div class="dropdown" style="float:right; width:45px; text-align:center;">
						  <i class="fa fa-ellipsis-v detail-btn"></i>
						  <div class="dropdown-content">
							<a class="deletePrescription" href="javascript:void(0);" data-id="{{$app->id}}" data-type="0" >Delete</a>
							<a class="sharePrescription" href="javascript:void(0);" data-id="{{@$app->id}}" href="javascript:void(0);" data-src="{{$app->prescription}}">Share</a>
							<a href="{{$app->prescription}}" download>Download</a>
						  </div>
						</div>
						@if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '1' && $app->file_type == 'pdf')
							<div class="list-bottom sponsered-btn">
								<div class="cal-doctor"><a class="btn" href="{{$app->prescription}}" download ><img src="{{ URL::asset('img/view.png')}}" alt="icon" />View</a></div>
							</div>
						@else
							<div class="list-bottom sponsered-btn">
								<div class="cal-doctor"><a class="btn @if($app->file_type == 'pdf') viewPDFPresOwn @else viewPrescription @endif" src="{{$app->prescription}}" href="javascript:void(0);" app_id="{{@$app->id}}" doc_id="{{@$app->doc_id}}"><img src="{{ URL::asset('img/view.png')}}" alt="icon" />View </a></div>
							</div>
						@endif
						  <div class="viewPrescriptionSection" style="display:none"><div class="prescription-file-view"><img src="{{$app->prescription}}" width="50" height="50" class="viewPresFile"/></div></div>
					</div>
				@endif
				@endforeach
				@if(isset($user) && !empty($user))
				<div class="pages-section">
				{{ $user->appends($_REQUEST)->links() }}
				</div>
				@endif
				@else
				<div class="registration-wrap user-info profile-exam profile-exam123">
					<div class="profile-examNoPrescription">
						<img width="140" src="img/No_Prescription.png" />
						<p>No Prescription Uploaded </p>
						<div class="btn text-center heading-four btn-positive" data-toggle="modal" data-target="#uploadPrescriptionModal">Upload records</div>
					</div>
				</div>
		  @endif
		  <!-- The Modal -->
		<div class="viewPresFileModal modal" data-keyboard="false" data-backdrop="static">
			<div class="view-file-full">
			  <span class="close" data-dismiss="modal">&times;</span>
			  <img class="modal-content img01" width="150" height="100%" src="" />
			</div>
		</div>

		<div class="viewPdfFileModal modal" data-keyboard="false" data-backdrop="static">
			<div class="view-file-full">
			  <span class="close" data-dismiss="modal">&times;</span>
			  <iframe class="modal-content pdf01" style="width:100%; height:450px;" src="" ></iframe>
			</div>
		</div>

		  <div class="modal fade myModalss-billing-items cld" id="uploadPrescriptionModal" role="dialog" data-backdrop="static" data-keyboard="false">
		  {!! Form::open(array('route' => 'uploadPriscription','method' => 'POST', 'id' => 'uploadPriscription', 'enctype' => 'multipart/form-data')) !!}
		  {{ csrf_field() }}
			  <div class="modal-dialog">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">Upload Prescription</h4>
					</div>
					<div class="modal-body">
					  <div class="aad-items-billable-section">
						<label>File<i class="required_star">*</i></label>
						<span class="help-block"></span>
						<div class="prescription_image_browse">
							<div class="image_apload" style="display:none;">
								<p class="user-presFilePdf user-presFile" style="display:none;"></p>
								<img style="background-size: cover;display:none;" class="user-presFileImg user-presFile" src="" alt="icon"/>
							</div>
							<span>
								<label class="btn btn-default" for="upload-file">
								<input  type="hidden" name="prescription_imageBlob" class="prescription_imageBlob" />
								<input id="upload-file" type="file" name="prescription" class="myFile" onchange="openFileProfile(event)"/>
									<i class="fa fa-upload" aria-hidden="true"></i>
								</label>
							</span>
							<p class="errCls"></p>
						</div>
					  </div>
					  <div class="aad-items-billable-section">
						<label>Doctor Name<i class="required_star">*</i></label>
						<input type="text" class="form-control" name="doc_name" placeholder="Doctor Name"/>
						<span class="help-block"></span>
					  </div>
					 <!-- <div class="aad-items-billable-section">
						<label>Prescription For<i class="required_star">*</i></label>
						 <input type="text" class="form-control" name="record_for"  placeholder="Prescription For" />
						 <span class="help-block"></span>
					  </div> -->
					  <div class="aad-items-billable-section">
						<label>Date<i class="required_star">*</i></label>
						 <input type="text" readonly class="form-control presDate" name="aptDate" placeholder="dd/mm/yyyy"/><span class="help-block"></span><i class="fa fa-calendar presDateCal" aria-hidden="true"></i>
					  </div>
					  <div class="aad-items-billable-section">
						<label>Time</label>
						<input type="time" class="form-control" name="aptTime" />
						 <span class="help-block"></span>
					  </div>
					  <!--<div class="aad-items-billable-section">
						<label>Type<i class="required_star">*</i></label>
						<label class="fa fa-prescription" for="record_type2">Prescription<input id="record_type2" type="radio" value="2" class="form-control" name="record_type" checked /></label>
						<label class="fa fa-file-chart-line" for="record_type1">Report<input id="record_type1" type="radio" value="1" class="form-control" name="record_type"/></label>
						<label class="fa fa-file-invoice" id="record_type3">Invoice<input id="record_type3" type="radio" value="3" class="form-control" name="record_type"/></label>
						<span class="help-block"></span>
					  </div>-->
				  </div>
				  <div class="modal-footer">
					<button name="submit" type="submit" class="btn btn-default finalSubmit">Save</button>
					<button name ="clear" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				  </div>
				</div>
			</div>
		  {!! Form::close() !!}
		</div>
		</div>
	</div>
</div>
<script>
$('.viewPDFPres').click(function() {
	$('.loading-all').show();
	var Dtype = $(this).attr('Dtype');
	var app_id = $(this).attr('app_id');
	var pdfF = $(this).attr('src');
	$.ajax({
	type: "POST",
	dataType : "HTML",
	url: "{!! route('getNotePrintOfWeb') !!}",
	data:{'app_id':app_id},
	success: function(data) {
		if(data == "1") {
			$(".viewPdfFileModal").find(".pdf01").attr("src",pdfF);
			if(Dtype == "1") {
				$(".viewPdfFileModal").modal("show");
			}
			else{
				var link = document.createElement('a');
				link.href = pdfF;
				link.download = "prescription.pdf";
				link.click();
			}
		}
		else{
			$.alert('Prescription Not Found..');
		}
		jQuery('.loading-all').hide();	
	 }
	});
});

jQuery(document).on("click", ".viewPresFile", function (e) {
	jQuery('.loading-all').show();
	var image = $(this).attr('src');
	$(".viewPresFileModal").find(".img01").attr("src",image);
	setTimeout(function() {
		jQuery('.loading-all').hide();
		$(".viewPresFileModal").modal("show");
	},1000);
});

jQuery(document).on("click", ".viewPDFPresOwn", function (e) {
	jQuery('.loading-all').show();
	var pdfF = $(this).attr('src');
	$(".viewPdfFileModal").find(".pdf01").attr("src",pdfF);
	setTimeout(function() {
		jQuery('.loading-all').hide();
		$(".viewPdfFileModal").modal("show");
	},1000);
});

jQuery("#uploadPriscription").validate({
	rules: {
		doc_name: {required:true,maxlength:150},
		aptDate: "required",
	},
	messages: {
		doc_name : {required:"Title is required",maxlength:"Title length is no more than 150 character"},
		aptDate : "Date is required",
	},
	errorPlacement: function(error, element) {
		 error.appendTo(element.next());
	},ignore: ":hidden",
	submitHandler: function(form) {
		$('.loading-all').show();
		$(".finalSubmit").attr("disabled",true);
		if($("#uploadPriscription").find(".prescription_imageBlob").val() || $("#uploadPriscription").find(".prescription_imageBlob").val() != "" || $("#uploadPriscription").find(".myFile").val() != "") {
			// $(form).submit();
			  jQuery.ajax({
			  type: "POST",
			  url: "{!! route('uploadPriscription')!!}",
			  data:  new FormData(form),
			  contentType: false,
			  cache: false,
			  processData:false,
			  success: function(data) {
					$(".finalSubmit").attr("disabled",false);
					 if(data==1) {
						jQuery('.loading-all').hide();
						document.location.href = '{!! route("myPriscription")!!}';
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
					// location.reload();
					},
				}
			  });
		}
	}
});

$( function() {
	$( ".presDate" ).datepicker({
	  dateFormat: 'dd-mm-yy',
	  // minDate: '-30D',
	  maxDate: '+10D',
	  // changeMonth: true,
		// changeYear: true,
	});
	$('.presDate').datepicker('setDate', 'today');
});
jQuery('.presDateCal').click(function () {
	jQuery('.presDate').datepicker('show');
});

function myFunction(current) {
	$('.toggle-wrapper').removeClass("chooseEle");
	$(current).closest('.toggle-wrapper').addClass("chooseEle");
	$('.toggle-wrapper').each(function(){
		if(!$(this).hasClass('chooseEle')) {
			$(this).find('.toggle-wrapper-content').slideUp();
		}
	});
	$(current).closest('.toggle-wrapper').find('.toggle-wrapper-content').slideToggle();
}

 // var preFileCheck = '';
function openFileProfile(event) {
	$(".finalSubmit").attr("disabled",false);
	var input = event.target;
	console.log(event);
	if(input.files.length) {
         // preFileCheck = $("#uploadPriscription").find(".myFile").val();
		var FileSize = input.files[0].size / 1024 / 1024; // 1in MB
		var ext = input.files[0].name.split('.').pop().toLowerCase();
		var reader = new FileReader();
		if(FileSize > 10) {
			$('.myFile').val('');
			$('.prescription_image_browse').find(".errCls .help-block").remove();
			$('.prescription_image_browse').find(".errCls").html('<span style="width:100%" class="help-block">Allowed file size exceeded. (Max. 10 MB)</span>');
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
var id;
jQuery(document).on("click", ".sharePrescription", function (e) {
	id = $(this).attr("data-id");

	$.confirm({
		title: 'Share your prescription!',
		content: '' +
		'<form action="" class="sharePrescriptionForm">' +
		'<div class="form-group">' +
		'<input type="text" placeholder="Name" class="form-control name" required name="name"/></div><div class="form-group"><input type="number" placeholder="Mobile Number" class="form-control mobile" required name="mobile_no"/></div><div class="form-group"><input type="email" placeholder="Email" class="form-control email" required name="email"/>' +
		'</div>' +
		'</form>',
		buttons: {
			formSubmit: {
				text: 'Submit',
				btnClass: 'btn-blue',
				action: function () {
					var mobile = this.$content.find('.mobile').val();
					var email = this.$content.find('.email').val();
					var name = this.$content.find('.name').val();
					if(!name) {
						$.alert('please enter name');
						return false;
					}
					else if(!mobile) {
						$.alert('please enter mobile number');
						return false;
					}
					else if(!email) {
						$.alert('please enter email');
						return false;
					}

					else {
						sharePrescription(id,name,mobile,email);
					}
				}
			},
			cancel: function () {
				//close
			},
		}
	});
});
jQuery(document).on("click", ".deletePrescription", function (e) {
	var id = $(this).attr("data-id");
	var type = $(this).attr("data-type");
	 $.alert({
		title: 'Are you sure?',
		content: 'This will delete the prescription(s) permanently.',
		draggable: false,
		type: 'red',
		typeAnimated: true,
		buttons: {
			Cancel: function(){
				 // $.alert('Canceled!');
			},
			Delete: function(){
				deletePrescription(id,type);
			},
		}
	  });
});

function deletePrescription(id,type){
	 jQuery('.loading-all').show();
	  jQuery.ajax({
	  type: "POST",
	  dataType : "JSON",
	  url: "{!! route('deletePriscription') !!}",
	  data: {'id':id, 'type':type},
	  success: function(data){
		jQuery('.loading-all').hide();
			if(data == '1') {
			  $.alert({
				title: 'File Deleted !',
				content: 'File Deleted Successfully.',
				draggable: false,
				type: 'green',
				typeAnimated: true,
				buttons: {
					ok: function(){
						location.reload();
					},
				}
			  });
			}
			else {
			  $.alert({
				title: 'oops !',
				content: 'File has not  been Delete !',
				draggable: false,
				type: 'red',
				typeAnimated: true,
				buttons: {
					ok: function(){
					// location.reload();
					},
				}
			  });
			}
		},
		error: function(error) {
			jQuery('#saveAddress').attr('disabled',false);
			jQuery('.loading-all').hide();
			  if(error.status == 401) {
				  location.reload();
			  }
		}
	  });
}

function sharePrescription(id,name,mobile,email){
	 jQuery('.loading-all').show();
	  jQuery.ajax({
	  type: "POST",
	  dataType : "JSON",
	  url: "{!! route('sharePrescription') !!}",
	  data: {'id':id, 'name':name,'mobile':mobile,'email':email},
	  success: function(data){
		jQuery('.loading-all').hide();
			if(data == '1') {
			  $.alert({
				title: 'File Shared !',
				content: 'File Shared Successfully.',
				draggable: false,
				type: 'green',
				typeAnimated: true,
				buttons: {
					ok: function(){
						location.reload();
					},
				}
			  });
			}
			else {
			  $.alert({
				title: 'oops !',
				content: 'File has not  been Delete !',
				draggable: false,
				type: 'red',
				typeAnimated: true,
				buttons: {
					ok: function(){
					// location.reload();
					},
				}
			  });
			}
		},
		error: function(error) {
			jQuery('#saveAddress').attr('disabled',false);
			jQuery('.loading-all').hide();
			  if(error.status == 401) {
				  location.reload();
			  }
		}
	  });
}

$(document).ready( function() {
	$(".viewPrescription").click(function() {
		$(this).closest('.listing').find('.viewPrescriptionSection').slideToggle();
	});
});
</script>
<script src="{{ URL::asset('js/zepto.min.js') }}"></script>
<script src="{{ URL::asset('js/binaryajax.js') }}"></script>
<script src="{{ URL::asset('js/exif.js') }}"></script>
<script src="{{ URL::asset('js/canvasResize.js') }}"></script>
@endsection
