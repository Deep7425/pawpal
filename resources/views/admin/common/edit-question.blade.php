<div class="modal-dialog add-hospital modal-lg AddOrganization">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Question</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.hQMaster') }}"> <i class="fa fa-list"></i>Question List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'updateQuestion','name'=>'updateQuestion')) !!}
					<div class="row">
					<input type=hidden value="{{$object->id}}" name="id" class="editId"/>
					<div class="form-group col-sm-3">
						<label>Order </label>
						<input value="{{@$object->order_id}}" type="text" name="order_id" class="form-control" placeholder="Enter Title">
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-3">
						<label>Title</label>
						<input value="{{@$object->title}}" type="text" name="title" class="form-control" placeholder="Enter Title">
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-3">
						<label>Level Type</label>
						<select name="level_type" class="form-control">
							<option value="">Select</option>
							<option value="high" @if($object->level_type == 'high') selected @endif>High</option>
							<option value="low" @if($object->level_type == 'low') selected @endif>Low</option>
							<option value="male" @if($object->level_type == 'male') selected @endif>Male</option>
							<option value="female" @if($object->level_type == 'female') selected @endif>Female</option>
						</select>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-3">
						<label>Language</label>
						<select name="lang" class="form-control langEdit">
							<option value="">Select Language</option>
							<option value="1" @if($object->lang == '1') selected @endif>English</option>
							<option value="2" @if($object->lang == '2') selected @endif>Hindi</option>
						</select>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-3">
						<label>Type</label>
						<select name="type" class="form-control questionTypeEdit">
							<option value="">Select Type</option>
							<option value="1" @if($object->type == '1') selected @endif>Weight Manager</option>
							<option value="2" @if($object->type == '2') selected @endif>Diabetes Manager</option>
							<option value="3" @if($object->type == '3') selected @endif>Bp Manager</option>
							<option value="4" @if($object->type == '4') selected @endif>Medicine Remainder</option>
							<option value="5" @if($object->type == '5') selected @endif>Temp Manager</option>
						</select>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-3">
						<label>Answer Type</label>
						<select name="answer_type" class="form-control">
							<option value="">Select Answer Type</option>
							<option value="1" @if($object->answer_type == '1') selected @endif>Check Boxes</option>
							<option value="2" @if($object->answer_type == '2') selected @endif>Radio Boxes</option>
							<option value="3" @if($object->answer_type == '3') selected @endif>Select Box</option>
							<option value="4" @if($object->answer_type == '4') selected @endif>Input Type</option>
						</select>
						<span class="help-block"></span>
					</div>
				    <div class="form-group col-sm-3">
				    	
		
				    </div>
					<?php $questions = getQuesByType($object->type,$object->lang,$object->id); $getSpecialityList = getSpecialityList(); ?>
					<div class="answerDiv" @if(!empty($object->meta_data)) style="display:block;" @else style="display:none;" @endif>
						<div class="answerList">
							@if(!empty($object->meta_data))
							<?php $meta_data = json_decode($object->meta_data); $i = 1; ?>
							
							@if(count((array)$meta_data) > 0)
								@foreach($meta_data as $key => $raw)
									<div id="rowCount{{$i}}" class="qsec">
										@if($i > 1)<a class="close-button" cc="{{$key}}" href="javascript:void(0);" onclick="removeRow('{{$i}}');"><i class="fa fa-times" aria-hidden="true"></i></a>@endif
										<div class="form-group col-sm-3">
											<label>Answer</label>
											<input type="text" class="form-control tag_names" placeholder="{{$i}} Answer" value="{{$raw->answer}}" name="HQ[{{$i}}][answer]"/>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-3">
											<label>Next Question</label>
											<select name="HQ[{{$i}}][id]" class="form-control questionsListing">
												<option value="">Select Question</option>
												@if(count($questions) > 0)
    @foreach($questions as $key => $qus)
        <option value="{{$qus->id}}" {{$raw->id == $qus->id ? 'selected' : '' }}>{{$qus->title}}</option>
    @endforeach
@endif
											</select>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-3">
											<label>Speciality</label>
											<select class="form-control" name="HQ[{{$i}}][speciality_id]">
												<option value="">Speciality</option>
												@foreach($getSpecialityList as $spc)
													<option value="{{ $spc->id }}" {{@$raw->speciality_id == $spc->id ? 'selected' : '' }}>{{ $spc->spaciality }}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group col-sm-12">
											<label>Note</label>
											<textarea id="editIdc{{$i}}" class="form-control ckeditorQe" rows="2" name="HQ[{{$i}}][note]" value="{{@$raw->answer}}">{{@$raw->note}}</textarea>
										</div>
									</div>
									<?php $i++; ?>
								@endforeach
							@endif	
						@else	
							<div id="rowCount1" class="qsec">
								<div class="form-group col-sm-12">
									<label>Answer</label>
									<input type="text" class="form-control tag_names" placeholder="1 Answer" name="HQ[1][answer]"/>
									<span class="help-block"></span>
								</div>
								<div class="form-group col-sm-12">
									<label>Next Question</label>
									<select name="HQ[1][id]" class="form-control questionsListing">
										<option value="">Select Question</option>
										@if(count($questions) > 0)
											@foreach($questions as $key => $qus)
												<option value="{{$qus->id}}">{{$qus->title}}</option>
											@endforeach
										@endif
									</select>
									<span class="help-block"></span>
								</div>
								<div class="form-group col-sm-6">
									<label>Speciality</label>
									<select class="form-control" name="HQ[1][speciality_id]">
										<option value="">Speciality</option>
										@foreach($getSpecialityList as $spc)
											<option value="{{ $spc->id }}">{{ $spc->spaciality }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-sm-6">
									<label>Note</label>
									<textarea id="editIdc1" class="form-control ckeditorQe" rows="2" name="HQ[1][note]" value=""></textarea>
								</div>
							</div>
						@endif
						</div>
					<div class="form-group col-sm-12 AddMoreAnswerText">
						<button type="button" class="btn btn-default form-control" onclick="addMoreRows(this);">Add More Answer</button>
					</div>
					</div>
					<!--<div class="form-group">
						<label>Answer (Enter Comma separated values)</label>
						<textarea value="{{@$object->answer}}" name="answer" class="form-control" placeholder="Enter Answer">{{@$object->answer}}</textarea>
						<span class="help-block"></span>
					</div>-->
					<div class="reset-button">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success update" id="upload-btn">Update</button>
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
				                @if(count($questions) > 0)
										@foreach($questions as $key => $qus)
											<option value="{{$qus->id}}">{{$qus->title}}</option>
										@endforeach
									@endif
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
							<textarea id="editIdc__number__" class="form-control ckeditorQe" rows="2" name="HQ[__number__][note]" value=""></textarea>
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
<script src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document.body).on('click', '.update', function(){
// jQuery("#modifySubAdmin").validate({
 jQuery("form[name='updateQuestion']").validate({
	rules: {
		title: {
		  required: true,
		  minlength: 1,
		  maxlength: 100,
		},type:"required",
		answer:"required",
		order_id:"required",
	 },
	messages:{
	},
	errorPlacement: function(error, element){

		error.appendTo(element.parent().find('.help-block'));
	},ignore: ":hidden",
	submitHandler: function(form) {
		$(form).find('.update').attr('disabled',true);
		jQuery('.loading-all').show();
		jQuery.ajax({
			type: "POST",
			dataType : "JSON",
			url: "{!! route('admin.updateQuestion')!!}",
			data:  new FormData(form),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data) {
				 if(data==1)
				 {
				  jQuery('.loading-all').hide();
				  $(form).find('.update').attr('disabled',false);
					location.reload();
				 }
				 else
				 {
				  jQuery('.loading-all').hide();
				  $(form).find('.update').attr('disabled',false);
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
});
});
$(document.body).on('change', '.answer_type', function(){
	if($(this).val()){
		$(".answerDiv").show();
	}
	else{
		$(".answerDiv").hide();
	}
});
// var rowCount = 1;
// function addMoreRows() {
// 	// rowCount++;
// 	if($(".answerDiv").find(".qsec").length > 0){
// 		rowCount = $(".answerDiv").find(".qsec").length+1;
// 	}
// 	var recRow = '<div id="rowCount'+rowCount+'" class="qsec"><div class="form-group col-sm-6"><label>Next Question</label><div class="rowCountTop"><input type="text" class="form-control tag_names" placeholder="'+rowCount+' Answer" name="HQ['+rowCount+'][answer]"/><span class="help-block"></span></div><div class="rowCountTop"><select name="HQ['+rowCount+'][order_id]" class="form-control"><option value="">Select Question</option><option value="1">A</option><option value="2">B</option><option value="3">C</option></select><span class="help-block"></span></div><a class="close-button" href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="fa fa-times" aria-hidden="true"></i></a></div>';
// 	jQuery('.answerDiv').append(recRow);
// }
function removeRow(removeNum) {
	jQuery('#rowCount'+removeNum).remove();
}
jQuery(document).on("change", ".questionTypeEdit", function (e) {
  var lang = $(".langEdit").val();
  var id = $(".editId").val();
  var type = this.value;
  getQuestion(type,lang,id);
});
jQuery(document).on("change", ".langEdit", function (e) {
  var type = $(".questionTypeEdit").val();
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
function setCkdre(){
$(".ckeditorQe").each(function(){
	var ckid = $(this).attr('id');
	if(ckid != "editIdc__number__"){
		CKEDITOR.replace(ckid);
	}
});
}
CKEDITOR.config.removePlugins = 'maximize';
CKEDITOR.config.allowedContent = true;
setCkdre();
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

$('.btn-default').click(function() {
    $('.modal').modal('hide');
});

$('.close').click(function() {
    $('.modal').modal('hide');
});
</script>