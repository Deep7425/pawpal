@extends('layouts.Masters.Master')
@section('title', 'Subscription health gennie')
@section('content') 
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Health Gennie</title>
<!-- Bootstrap -->
<link href="{{ URL::asset('subcription-asset/style.css') }}" rel="stylesheet">

<div class="container-fluid banner-mobile">
	<!--<h3>Digitize Your<span> Practice </span> Today</h3>-->
	<img class="img-top12" src="{{ URL::asset('subcription-asset/img/practice-digitize-mobile.jpg') }}" />
</div>

<div class="container-fluid banner">
	<!--<h3>Digitize Your<span> Practice </span> Today</h3>-->
	<img class="img-top12" src="{{ URL::asset('subcription-asset/img/practice-digitize.jpg') }}" />
</div>
<div class="container-fluid Patient_Portal wrapper-portal plan-wrapper creatingProfileTop">
<div class="container">
<div class="benifit-content">
	<!--<img width="150" src="{{ URL::asset('img/scanningwoohoo.gif') }}" />-->
	<h3 class="topHeadingHealth"><i>Please upload your <span> document</span> for profile verification.</i></h3>
	<!--<div class="button-subscribe mobile"><a href='{{route("home")}}'>Home</a></div>-->
</div> 

<div class="documentSection">				
<div class="uploadSection">
<div class="col-md-12 ColDocumentsSection">
<label class="DocumentsSection">Documents Section<span>(Documents will be uploaded in the selected section as you select the type)</span></label>	
  <ul class="documets-type documets-type123" id="documetTypes">
  @foreach (getDoctorDocumentType() as $key => $type)
	@if($key != 1 && $key != 2)
	<li class="@if($key == 3) active @endif" data-id="{{$key}}">{{$type}}</li>
	@endif
  @endforeach
  </ul>
  <span class="help-block"></span>
</div>
<div class="col-md-12">
  <div class="dropzone">
	<div class="dz-message needsclick">
	  <strong>Drag Your Image Here</strong>
	</div>
  </div>
</div>
</div>

<div class="userDocuments">
@if(count(@$doctor->DoctorDocuments) > 0)
@foreach (@$doctor->DoctorDocuments as $row)
<?php $extension = pathinfo($row->file_name, PATHINFO_EXTENSION);
$img = url("/")."/public/doctorDocuments/".@$row->file_name;
$path = url("/")."/public/doctorDocuments/".@$row->file_name;
if ($extension == 'pdf') {
  $img = URL::asset('img/pdf.png');
}
?>
<div class="profile-doc-wrapper">
<div class="image-block {{getDocTypeClass($row->type)}}">
  <span class="removeImage" data-id="{{$row->id}}"><i class="fa fa-times" aria-hidden="true"></i></span>
  <img src="{{$img}}" alt="" class="openFile" filename="{{$path}}" width="100"/>
  
</div>
<span class="typeName">{{getDoctorDocumentType($row->type)}}</span>
  @if($row->type == 1)
  <!--<div class="profile_marked">
	<label class="container-checkbox"><input type="checkbox" class="profileMark" name="profile_marked[]" data-id="{{$row->id}}" value="1" @if($row->profile_marked == 1) checked disabled @endif/>{{__('page.reg_make_profile')}}<span class="checkmark"></span></label>
  </div>-->
  @endif
  </div>
@endforeach
@endif
</div>
</div>	
</div>
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
<script type="text/javascript" src="{{ URL::asset('js/dropzone.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
 // jQuery(document).on("click", ".documentClick", function (e) {
	// $(".documentSection").toggle();
 // });


 
 
Dropzone.autoDiscover = false;
$(function() {
var myDropzone = new Dropzone(".dropzone", {
url: "{{route('admin.uploadDoctorDocuments', ['_token' => csrf_token() ])}}",
addRemoveLinks: true,
paramName: "file",
maxFilesize: 0.5,
maxFiles: 1,
acceptedFiles: ".jpeg,.jpg,.png,.pdf",
init: function(){
  let thisDropzone = this; // Closure
  this.on("error", function(file, message) {
	var msg = '<label for="type" generated="true" class="error">'+message+'</label>';
	$('#documetTypes').parent().find('.help-block label').remove();
	$('#documetTypes').parent().find('.help-block').append(msg);
	this.removeFile(file);
  });
  this.on('sending', function(file, xhr, formData){
	var type = $('#documetTypes .active').data('id');
	var id = '{{@$doctor->user_id}}';
	var doc_id = '{{@$doctor->id}}';
	formData.append('doc_id', doc_id);
	formData.append('user_id', id);
	formData.append('type', type);
  });
  this.on("success", function(file, response) {
	var ext = response.file_name.split('.').pop().toLowerCase();
	var img = response.file_name;
	if (ext == 'pdf') {
	  var img = "{{URL::asset('img/pdf.png')}}";
	}
	var typeClass = {"1":"profilePic", "2":"clinicPic", "3":"regCet", "4":"degree", "5":"addPrf"};
	var cls = typeClass[response.type];
	var img = '<div class="image-block '+cls+'"><span class="removeImage" data-id="'+response.id+'"><i class="fa fa-times" aria-hidden="true"></i></span><img src="'+img+'" filename="'+response.file_name+'" class="openFile" alt="" width="100"><span class="typeName">'+response.type_name+'</span></div>';
	$('.userDocuments').append(img);
	this.removeFile(file);
});
}
});
});
});
jQuery(document).on("click", ".openFile", function (e) {	
  var file = $(this).attr('filename');
      jQuery('.loading').hide();
      jQuery('#documentViewModel').modal('show');
      // jQuery('#documentViewModel').show();
      jQuery('#DocumentView').attr('src', file);
      setTimeout(function(){
       jQuery("#DocumentView").contents().find("img").css({'width': '100%'});
      }, 300);
      $('#DocumentView').load(function() {
          $(this).contents().find('img').css({'width': '100%'});
      })
});
jQuery(document).on("click", ".closeImageModal", function (e) {
	// $("#documentViewModel").hide();
	jQuery('#documentViewModel').modal('hide');
});
jQuery(document).on("click", "#documetTypes li", function (e) {
  $('#documetTypes li').removeClass('active');
  $(this).addClass('active');
});
jQuery(document).on("click", ".removeImage", function (e) {
  var id = $(this).data('id');
  var current = $(this);
  if (confirm("Are you sure want to delete this file?")) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.deleteFile') !!}",
    data:{'id':id},
    success: function(data){
      jQuery('.loading-all').hide();
      $(current).closest('.image-block').remove();
    },
    error: function(error){
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
  }
  else{
  return false;
  }
});
</script>  
@endsection