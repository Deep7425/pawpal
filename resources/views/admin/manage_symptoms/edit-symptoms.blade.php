<div class="modal-dialog modal-dialog111">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-bs-dismiss="modal">×</button>
			<h4 class="modal-title">Update Symptoms</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group"> 
						<a class="btn btn-primary" href="{{ route('symptoms.SymptomsMaster') }}"> <i class="fa fa-list"></i>  Symptoms List</a> 
					</div>
				</div>
				<div class="panel-body panel-body123-2">
					{!! Form::open(array('id' => 'updateSpecialitySymptoms','name'=>'updateSpecialitySymptoms')) !!}
					<input type=hidden value="{{$symptom->id}}" name="id"/>
					<input type="hidden" name="tags" id="symptom_tags_val">
					<div class="form-group row">
					<div class="col-md-6 sya-field">
					<div class="form-group">
						<label>Spaciality</label>
						<select class="form-control" id="exampleSelect1" name="spaciality_id[]" size="1" multiple>
							@foreach(getSpecialityList() as $index => $spaciality)
								<option value="{{$spaciality->id}}" @if(in_array($spaciality->id ,$spaciality_id)) selected @endif>{{$spaciality->specialities}}</option>
							@endforeach
						</select>
						<span class="help-block"></span>
					</div>
					</div>

					

					<div class="col-md-6">
					<div class="form-group">
						<label>Symptoms Name</label>
						<input value="{{@$symptom->symptom}}" type="text" name="symptom" class="form-control" placeholder="Enter Symptoms Name">
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label>Symptoms Name(Hindi)</label>
						<input value="{{@$symptom->symptom_hindi}}" type="text" name="symptom_hindi" class="form-control" placeholder="Enter Symptoms Name In Hinid">
						<span class="help-block"></span>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group">
						<label>Disease</label>
						<input value="{{@$symptom->disease}}" type="text" name="disease" class="form-control" placeholder="Enter Disease Name">
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group form-group123">
						<label>Symptoms Tags</label> 
						 <button type="button" data="@if(count($symptom->SymptomTags)>0){{$symptom->SymptomTags}}@endif" class="btn btn-info btn-xs form-control edit_tags" data-toggle="modal">Tags</button>
					</div>
					</div>
					</div>
					<div class="form-group row">
					
					<div class="col-md-6 desc">
					<div class="form-group">
						<label>Description</label>
						<textarea value="{{@$symptom->description}}" class="form-control" name="description" id="exampleTextarea" rows="5">{{@$symptom->description}}</textarea>
					</div>
					</div>
					<div class="col-md-6 desc">
					<div class="form-group">
						<label>Description (Hindi)</label>
						<textarea value="{{@$symptom->description_hindi}}" class="form-control" name="description_hindi" id="exampleTextareaHindi" rows="5">{{@$symptom->description_hindi}}</textarea>
					</div>
					</div>
					<div class="col-md-6 desc">
					<div class="form-group">
						<label>Cause</label>
						<textarea value="{{@$symptom->cause}}" class="form-control" name="cause" id="exampleCause" rows="5">{{@$symptom->cause}}</textarea>
					</div>
					</div>
					<div class="col-md-6 desc">
					<div class="form-group">
						<label>Cause (Hindi)</label>
						<textarea value="{{@$symptom->cause_hindi}}" class="form-control" name="cause_hindi" id="exampleCauseHindi" rows="5">{{@$symptom->cause_hindi}}</textarea>
					</div>
					</div>
					<div class="col-md-6 desc">
					<div class="form-group">
						<label>Treatment</label>
						<textarea value="{{@$symptom->treatment}}" class="form-control" name="treatment" id="exampleTreatment" rows="5">{{@$symptom->treatment}}</textarea>
					</div>
					</div>
					<div class="col-md-6 desc">
					<div class="form-group">
						<label>Treatment (Hindi)</label>
						<textarea value="{{@$symptom->treatment_hindi}}" class="form-control" name="treatment_hindi" id="exampleTreatmentHindi" rows="5">{{@$symptom->treatment_hindi}}</textarea>
					</div>
					</div>
					</div>
					<div class="form-group row">
					<div class="col-md-6">
					<div class="form-check">
					  <label>Status</label><br>
					  <label class="radio-inline">
						  <input type="radio" name="status" value="1" @if(@$symptom->status == '1') checked="checked" @endif>Active</label>
						  <label class="radio-inline"><input type="radio" name="status" value="0"  @if(@$symptom->status == '0') checked="checked" @endif />Inctive</label>
					</div>                                       
					  </div>
					 <div class="col-md-6"> 
					<div class="reset-button">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success submit">Update</button>
					</div>
					</div>
					</div>
				 {!! Form::close() !!}
				 
				</div>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
		</div>
	</div>

	</div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
  <!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->
<!-- 

  <script src="{{ asset('assets/libs/chart-am4/core.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/chart-am4/charts.js') }}"></script> -->

<script type="text/javascript">
			
			


	$(document.body).on('click', '.submit', function(){ 
		// jQuery("#updateSpecialitySymptoms").validate({
		 jQuery("form[name='updateSpecialitySymptoms']").validate({	
			rules: {
				'spaciality_id[]': {
					required: true
				},
				symptoms: "required",
			 },
			messages:{
			},
			errorPlacement: function(error, element){
				error.appendTo(element.next());
			},ignore: ":hidden",
			submitHandler: function(form) {
				$(form).find('.submit').attr('disabled',true);
				jQuery('.loading-all').show();
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('symptoms.updateSymptoms')!!}",
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
							// document.location.href='{!! route("symptoms.SymptomsMaster")!!}';
						 }
						  else if(data==3)
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
						  alert("Symptoms Already Exists");
						 }
						 else
						 {
						  if(data.spaciality.length > 0){ 
								var arr = new Array();
								jQuery.each(data.spaciality,function(k,v) {
									arr.push(v.name.specialities+" With symptom "+data.symptom+" is Already Exists.\n");
								});
								if(arr.length > 0){
									alert(arr);
								}
							}
							else{
							  alert("Oops Something Problem");
							}
							jQuery('.loading-all').hide();
							$(form).find('.submit').attr('disabled',false);
						}
					}
				});
			}
		});
	});
	
	var rowCount = 1;
	function addMoreRows() {
		rowCount++;
		var recRow = '<div class="form-group" id="rowCount'+rowCount+'"><input type="text" class="form-control tag_names" placeholder="Enter Tag Name" name="tags[]"><a class="close-button" href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="fa fa-times" aria-hidden="true"></i></a></div>';
		jQuery('#addedRows').append(recRow);
	}
	function removeRow(removeNum) {
		jQuery('#rowCount'+removeNum).remove();
	}
	
	jQuery(document).on("click", ".edit_tags", function (e) {
		if($(this).attr('data')){
			var arr = JSON.parse($(this).attr('data'));

			if(arr.length > 0){
				var recRow = '';
				$("#tagsAddModal").find('#addedRows').html('');
				var i = 1;
				$.each(arr, function( key, value ) {
					recRow += '<div class="form-group" id="rowCount'+i+'"><input type="text" class="form-control tag_names" placeholder="Enter Tag Name" name="tags[]" value="'+value.text+'">';
					if(key > 0){ 
						recRow += '<a class="close-button" href="javascript:void(0);" onclick="removeRow('+i+');"><i class="fa fa-times" aria-hidden="true"></i></a>';
					}
					recRow += '</div>';
					i++;
				});
				$("#tagsAddModal").find('#addedRows').append(recRow);
				$("#tagsAddModal").modal('show');
			}
		}
		else{;
			$("#tagsAddModal").modal('show');
		}
	});
	jQuery(document).on("click", ".addTags", function (e) {
		var arr = new Array();
		 $("#tagsAddModal").find(".tag_names").each(function(){
			if($(this).val()) { 
				arr.push($(this).val());
			}
		 });
		if(arr.length > 0){
			$("#symptom_tags_val").val(JSON.stringify(arr));
		}
		$("#tagsAddModal").find(".close").click();
	});
	
</script>
  <script>
	CKEDITOR.config.removePlugins = 'maximize';
	CKEDITOR.replace('exampleTextarea');
	CKEDITOR.replace('exampleTreatment');
	CKEDITOR.replace('exampleCause');
	
	CKEDITOR.replace('exampleTextareaHindi');
	CKEDITOR.replace('exampleTreatmentHindi');
	CKEDITOR.replace('exampleCauseHindi');
	CKEDITOR.on('instanceReady', function () {
	$.each(CKEDITOR.instances, function (instance) {
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
  