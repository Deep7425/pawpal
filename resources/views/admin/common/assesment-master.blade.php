@extends('layouts.admin.Masters.Master')
@section('title', 'Health Question Master')
@section('content')
    <!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">

            <div class="container-fluid flex-grow-1 container-p-y">
                        
			    

                   <div class="row mb-2 mt-2 ml-1 mr-1">
				    @if(session()->get('successMsg'))
                      <div class="alert alert-success">
                        <strong>Success!</strong> {{ session()->get('successMsg') }}
                      </div>
                    @endif
				   </div>

				   <div class="layout-content ">
				<div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Mobile</th>
                                        <th>Age</th>
                                 
                                        <th style="text-align: center;">Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php 
                                    $i=1;
									
									?>
								 @foreach($assesmentresult as $res)
								 <?php 
								$var=0;
								$AnsSum=0;
								 ?>
								 <?php 
                                 $var+=$res->ques_1;
								 $var+=$res->ques_2; 
								 $var+=$res->ques_3;
								 $var+=$res->ques_4;
								 $var+=$res->ques_5;
								 $var+=$res->ques_6;
								 $var+=$res->ques_7;
								 $var+=$res->ques_8;
								 $var+=$res->ques_9;
								 $var+=$res->ques_10;
								 $var+=$res->ques_11;
								 $var+=$res->ques_12;
								 $var+=$res->ques_13;
								 $var+=$res->ques_14;
								 $var+=$res->ques_15;
								 $var+=$res->ques_16;
								 $var+=$res->ques_17;
								 $var+=$res->ques_18;
								 $var+=$res->ques_19;
								 $var+=$res->ques_20;
								 $var+=$res->ques_21;
								 $var+=$res->ques_22;
								 $var+=$res->ques_23;
								 $var+=$res->ques_24;
								 $var+=$res->ques_25;
								 $AnsSum +=$var;
                                  ?>
                                    <tr>
										<td>
											<label>{{ $i}}</label>
										</td>
										<td>{{$res->name}}</td>
										<td>{{$res->gender}}</td>
										<td>{{$res->mobile}}</td>
										<td>{{$res->age}}</td>
									
										<td style="text-align:center;	">
                                        @if($AnsSum<=20)
                                         <b Style="color:green"> Excellent</b>
										@endif
										@if($AnsSum > 20 && $AnsSum < 40)
										<b Style="color:#CD5C5C"> Very Good</b>
										@endif

										@if($AnsSum > 40 && $AnsSum < 60)
										<b Style="color:blue">  Good </b>
										@endif

										@if($AnsSum > 60 && $AnsSum < 80)
										<b Style="color:orange">  Average </b>
										@endif

										@if($AnsSum > 80 && $AnsSum < 90)
										<b Style="color:red"> Poor </b>
										@endif

										
										<!-- <a  class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="View Result"><i class="fa fa-eye" aria-hidden="true"></i></a>
											<button  class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
											<button  class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></button> -->
										</td>
									</tr>
									<?php
									 $i++;
									?>
								@endforeach
								
							
								</tbody>
							</table>
							{{ $assesmentresult->links() }}
						</div>
					
			    </div>
				<div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
				 <ul class="pagination pagination-large"></ul>
			         </div>
            </div>
       </div>
   </div>
   <div class="modal fade AddOrganization" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
  		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal">×</button>
  			<h4 class="modal-title">Add Question</h4>
  		</div>
  		<div class="modal-body">
  			<div class="panel panel-bd lobidrag">
  				<div class="panel-heading">

  				</div>
  				<div class="panel-body">
  					{!! Form::open(array('id' => 'addOrganization','name'=>'addOrganization', 'enctype' => 'multipart/form-data')) !!}
  					<div class="form-group col-sm-6">
  						<label>Title</label>
  						<input value="" type="text" name="title" class="form-control" placeholder="Enter Title">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
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
					<div class="form-group col-sm-6">
						<label>Language</label>
						<select name="lang" class="form-control lang">
							<option value="">Select Language</option>
							<option value="1">English</option>
							<option value="2">Hindi</option>
						</select>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6">
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
					<div class="form-group col-sm-6">
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
								<div class="form-group col-sm-6">
									<label>Answer</label>
									<input type="text" class="form-control tag_names" placeholder="1 Answer" name="HQ[1][answer]"/>
									<span class="help-block"></span>
								</div>
								<div class="form-group col-sm-6">
									<label>Next Question</label>
									<select name="HQ[1][id]" class="form-control questionsListing">
										<option value="">Select Question</option>
									</select>
									<span class="help-block"></span>
								</div>
								<div class="form-group col-sm-6">
									<label>Speciality</label>
									<select class="form-control" name="HQ[1][speciality_id]">
										<option value="">Speciality</option>
									
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
  					</div>
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
							
									<option value="tr">dfg</option>
						
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