@extends('layouts.admin.Masters.Master')
@section('title', 'Health Question Master')
@section('content')
    <!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">
                   <div class="row mb-2 ml-1 form-top-row">
                         
				        <div class="btn-group">
                            <a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i>  Add Health Question</a>
                        </div>

						<div class="btn-group">
                            <a class="btn btn-success" href="javascript:void();">{{$object->total()}}</a>
                        </div>

						<div class="btn-group head-search">

						<div class="ml-sm-2">
											{!! Form::open(array('route' => 'admin.hQMaster', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
											<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												</select>
                                            </div>
											<div class="ml-sm-2" >
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="Search By Title" value="{{ old('search') }}"/>
												</div>
											</div>

											<div class="ml-sm-2">
												<div class="input-group custom-search-form">
													<select name="type" class="form-control">
														<option value="">Select Type</option>
														<option value="1"  @if((app('request')->input('type'))!='') @if(base64_decode(app('request')->input('type')) == '1') selected @endif @endif >Weight Manager</option>
														<option value="2" @if((app('request')->input('type'))!='') @if(base64_decode(app('request')->input('type')) == '2') selected @endif @endif>Diabetes Manager</option>
														<option value="3" @if((app('request')->input('type'))!='') @if(base64_decode(app('request')->input('type')) == '3') selected @endif @endif>Bp Manager</option>
														<option value="4" @if((app('request')->input('type'))!='') @if(base64_decode(app('request')->input('type')) == '4') selected @endif @endif>Medicine Remainder</option>
														<option value="5" @if((app('request')->input('type'))!='') @if(base64_decode(app('request')->input('type')) == '5') selected @endif @endif>Temp Manager</option>
													</select>
												</div>
											</div>

											<div class=" ml-sm-2">
												<div class="input-group custom-search-form">
													<select name="lang" class="form-control">
														<option value="">Select Language</option>
														<option value="1"  @if((app('request')->input('lang'))!='') @if(base64_decode(app('request')->input('lang')) == '1') selected @endif @endif >English</option>
														<option value="2" @if((app('request')->input('lang'))!='') @if(base64_decode(app('request')->input('lang')) == '2') selected @endif @endif>Hindi</option>
													</select>
												</div>
											</div>

											<div class="ml-sm-2">
												<div class="input-group custom-search-form">
													<span class="input-group-btn">
													  <button class="btn btn-primary" type="submit">
														  SEARCH
													  </button>
													</span>
												</div>
											{!! Form::close() !!}
											</div>


						</div>


				   </div>                
				   <?php $getSpecialityList = getSpecialityList(); ?>

			   <div class="layout-content">
			   <div class="table-responsive table-container">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Order</th>
                                        <th>Language</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Level Type</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if(count((array)$object) > 0)
								@foreach($object as $index => $row)
                                    <tr>
										<td>
											<label>{{$index+($object->currentpage()-1)*$object->perpage()+1}}.</label>
										</td>
										<td>{{$row->order_id}}</td>
										<td>@if($row->lang == '1') English @elseif($row->lang == '2') Hindi @endif</td>
										<td>{{$row->title}}</td>
										<td>@if($row->type == '1') Weight Manager @elseif($row->type == '2') Diabetes Manager @elseif($row->type == '3') Bp Manager @elseif($row->type == '4') Medicine Remainder @elseif($row->type == '5') Temp Manager @endif</td>
										<td>@if($row->level_type == 'high') High @elseif($row->level_type == 'low') Low @elseif($row->level_type == 'male') Male @elseif($row->level_type == 'female') Female @endif</td>
										<td>
											<button onclick="editOrg({{$row->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
											<button onclick="deleteOrg({{$row->id}});" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa-solid fa-trash"></i></button>
										</td>
									</tr>
								@endforeach
								@else
									<tr><td colspan="3">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
		

              </div>
			  <div class="page-nation text-right d-flex justify-content-end mb-sm-2 mt-sm-2">
				<ul class="pagination pagination-large">
					{{ $object->appends($_GET)->links() }}
				</ul>
			</div>

         </div>
        </div>


		<div class="modal fade AddOrganization add-hospital" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
  		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal">×</button>
  			<h4 class="modal-title">Add Question</h4>
  		</div>
  		<div class="modal-body">
  			<div class="panel panel-bd lobidrag ">
  				<div class="panel-heading PackageTypeNew">

  				</div>
  				<div class="panel-body">
  					{!! Form::open(array('id' => 'addOrganization','name'=>'addOrganization', 'enctype' => 'multipart/form-data')) !!}
  					<div class="row">
					<div class="form-group col-sm-3">
  						<label>Title</label>
  						<input value="" type="text" name="title" class="form-control" placeholder="Enter Title">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-3">
						<label>Level Type</label>
						<select name="level_type" class="form-control">
							<option value="">Select</option>
							<option value="high">High</option>
							<option value="low">Low</option>
							<option value="male">Male</option>
							<option value="female">FeMale</option>
						</select>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-3">
						<label>Language</label>
						<select name="lang" class="form-control lang">
							<option value="">Select Language</option>
							<option value="1">English</option>
							<option value="2">Hindi</option>
						</select>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-3">
						<label>Type</label>
						<select name="type" class="form-control questionType">
							<option value="">Select Type</option>
							<option value="1">Weight Manager</option>
							<option value="2">Diabetes Manager</option>
							<option value="3">Bp Manager</option>
							<option value="4">Medicine Remainder</option>
							<option value="5">Temp Manager</option>
						</select>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-3">
						<label>Answer Type</label>
						<select name="answer_type" class="form-control answer_type">
							<option value="">Select Answer Type</option>
							<option value="1">Check Boxes</option>
							<option value="2">Radio Boxes</option>
							<option value="3">Select Box</option>
							<option value="4">Input Type</option>
						</select>
						<span class="help-block"></span>
					</div>
					<?php $getSpecialityList = getSpecialityList(); ?>
					<div class="answerDiv" style="display:none;">
						<div class="answerList">
							<div id="rowCount1" class="qsec">
								<div class="form-group col-sm-3">
									<label>Answer</label>
									<input type="text" class="form-control tag_names" placeholder="1 Answer" name="HQ[1][answer]"/>
									<span class="help-block"></span>
								</div>
								<div class="form-group col-sm-3">
									<label>Next Question</label>
									<select name="HQ[1][id]" class="form-control questionsListing">
										<option value="">Select Question</option>
									</select>
									<span class="help-block"></span>
								</div>
								<div class="form-group col-sm-3">
									<label>Speciality</label>
									<select class="form-control" name="HQ[1][speciality_id]">
										<option value="">Speciality</option>
										@foreach($getSpecialityList as $spc)
											<option value="{{ $spc->id }}">{{ $spc->spaciality }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-12">
									<label>Note</label>
									<textarea id="editId1" class="form-control ckeditorQ" rows="2" name="HQ[1][note]" value=""></textarea>
								</div>
							</div>
						</div>
						

						<div class="form-group col-sm-12 AddMoreAnswerText">
							<button type="button" class="btn btn-default form-control" onclick="addMoreRows(this);">Add More Answer</button>
						</div>
					</div>
					<!--<div class="form-group">
						<label>Answer (Enter Comma separated values)</label>
						<textarea name="answer" class="form-control" placeholder="Enter Answer"></textarea>
						<span class="help-block"></span>
					</div>-->
  					<div class="reset-button">
  					   <button type="reset" class="btn btn-warning">Reset</button>
  					   <button type="submit" class="btn btn-success submit" id="submit-btn">Submit</button>
  					</div></div>
  				 {!! Form::close() !!}
		 		<div class="hideDiv" style="display: none;">
					<div  id="rowCount__number__" class="qsec">
						<a class="close-button" href="javascript:void(0);" onclick="removeRow('__number__');"><i class="fa fa-times" aria-hidden="true"></i></a>
						<div class="form-group col-sm-6">
							<label>Answer</label>
							<input type="text" class="form-control tag_names" placeholder="__number__ Answer" name="HQ[__number__][answer]" /><span class="help-block"></span>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-6">
							<label>Next Question</label>
							<select name="HQ[__number__][id]" class="form-control questionsListing">
				                <option value="">Select Question</option>
				            </select>
							<span class="help-block"></span>
						</div>
					    <div class="form-group col-sm-6">
							<label>Speciality</label>
							<select class="form-control" name="HQ[__number__][speciality_id]">
								<option value="">Speciality</option>
								@foreach($getSpecialityList as $spc)
									<option value="{{ $spc->id }}">{{ $spc->spaciality }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-sm-12">
							<label>Note</label>
							<textarea id="editId__number__" class="form-control ckeditorQ" rows="2" name="HQ[__number__][note]" value=""></textarea>
						</div>
					</div>
				</div>
  				</div>
  			</div>

  		</div>
  		<div class="modal-footer">
  			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  		</div>
  	</div>

  	</div>
</div>



   <div class="modal fade" id="EditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>




<script src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script>

<script>
$(document.body).on('click', '.submit', function(){
// jQuery("#modifySubAdmin").validate({
jQuery("form[name='addOrganization']").validate({
rules: {
title: {
required: true,
minlength: 1,
maxlength: 100,
},
type:"required",
 },
messages:{
},
errorPlacement: function(error, element){
error.appendTo(element.parent().find('.help-block'));
},ignore: ":hidden",
submitHandler: function(form) {
	flag = true;
	$(".answerDiv .answerList").find(".tag_names").each(function(){
		if($(this).val() == ""){
			flag = false;
		}
	});
	if(flag == true){
	$(form).find('.submit').attr('disabled',true);
	jQuery('.loading-all').show();
	jQuery.ajax({
		type: "POST",
		dataType : "JSON",
		url: "{!! route('admin.addQuestion')!!}",
		data:  new FormData(form),
		contentType: false,
		cache: false,
		processData:false,
		success: function(data) {
			 if(data==1)
			 {
			  jQuery('.loading-all').hide();
			  $(form).find('.submit').attr('disabled',false);
				location.reload();
			 }
			 else
			 {
			  jQuery('.loading-all').hide();
			  $(form).find('.submit').attr('disabled',false);
			  alert("Oops Something Problem");
			 }
		},
error: function(error)
{
  jQuery('.loading-all').hide();
  console.log("error-1")
  alert("Oops Something goes Wrong.");
}
	});
}
else{
	alert("Answer field is required..");
}
}
});
});
function editOrg(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editQuestion')!!}",
    data:{'id':id},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#EditModal").html(data);
      jQuery('#EditModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
		console.log("error-2")
        alert("Oops Something goes Wrong.");
    }
  });
}

function deleteOrg(id) {
	if(confirm('Are you sure want to delete?') == true){
		jQuery('.loading-all').show();
		jQuery.ajax({
		type: "POST",
		dataType : "JSON",
		url: "{!! route('admin.deleteQuestion')!!}",
		data:{'id':id},
		success: function(data)
		{
		 if(data==1)
		 {
		  location.reload();
		 }
		 else
		 {
		  alert("Oops Something Problem");
		 }
		jQuery('.loading-all').hide();
		},
		error: function(error)
		{
			jQuery('.loading-all').hide();
			console.log("error-3")
			alert("Oops Something goes Wrong.");
		}
	  });
	}
}
$(document.body).on('change', '.answer_type', function(){
	if($(this).val()){
		$(".answerDiv").show();
	}
	else{
		$(".answerDiv").hide();
	}
});
var rowCount = 1;
function addMoreRows(current) {
	// rowCount++;
	if($(current).closest('form').find(".answerDiv .answerList").find(".qsec").length > 0){
		rowCount = $(current).closest('form').find(".answerDiv .answerList").find(".qsec").length+1;
	}
	var recRowHtml = $(current).closest('.panel-body').find(".hideDiv").html();
	recRowHtml = recRowHtml.replace(/__number__/gi, rowCount);
	// console.log(recRowHtml);
	// var recRow = '<div id="rowCount'+rowCount+'" class="qsec"><div class="form-group col-sm-6"><label>Next Question</label><div class="rowCountTop"><input type="text" class="form-control tag_names" placeholder="'+rowCount+' Answer" name="HQ['+rowCount+'][answer]"/><span class="help-block"></span></div><div class="rowCountTop"><select name="HQ['+rowCount+'][order_id]" class="form-control"><option value="">Select Question</option><option value="1">A</option><option value="2">B</option><option value="3">C</option></select><span class="help-block"></span></div><a class="close-button" href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="fa fa-times" aria-hidden="true"></i></a></div></div>';
	$(current).closest('form').find('.answerDiv .answerList').append(recRowHtml);
	setCkdr();
	setCkdre();
}
function removeRow(removeNum) {
	jQuery('#rowCount'+removeNum).remove();
}
jQuery(document).on("change", ".questionType", function (e) {
  var lang = $(".lang").val();
  var id = $(".editId").val();
  var type = this.value;
  getQuestion(type,lang,id);
});
jQuery(document).on("change", ".lang", function (e) {
  var type = $(".questionType").val();
  var lang = this.value;
  var id = $(".editId").val();
  getQuestion(type,lang,id);
});
function getQuestion(type,lang,id){
	var $el = jQuery('.questionsListing');
	$el.empty();
	jQuery.ajax({
		  url: "{!! route('admin.getQuestions') !!}",
		  type : "POST",
		  dataType : "JSON",
		  data:{'type':type,'lang':lang,'id':id},
		  success: function(result){
		  jQuery(".questionsListing").html('<option value="">Select Question</option>');
		  jQuery.each(result,function(index, element) {
			  $el.append(jQuery('<option>', {
				 value: element.id,
				 text : element.title
			  }));
		  });
	 }});
}
</script>
<script>
function setCkdr(){
$(".ckeditorQ").each(function(){
	var ckid = $(this).attr('id');
	if(ckid != "editId__number__"){
		CKEDITOR.replace(ckid);
	}
});
}
CKEDITOR.config.removePlugins = 'maximize';
CKEDITOR.config.allowedContent = true;
setCkdr();
CKEDITOR.on('instanceReady', function () {
	$.each(CKEDITOR.instances, function(instance) {
		CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
		CKEDITOR.instances[instance].document.on("paste", CK_jQ);
		CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
		CKEDITOR.instances[instance].document.on("blur", CK_jQ);
		CKEDITOR.instances[instance].document.on("change", CK_jQ);
	});
});
function CK_jQ() {
	for (instance in CKEDITOR.instances) {
		CKEDITOR.instances[instance].updateElement();
	}
}
</script>
@endsection