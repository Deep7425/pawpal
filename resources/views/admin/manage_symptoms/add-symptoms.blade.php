@extends('layouts.admin.Masters.Master')
@section('title', 'Add Syamptoms')
@section('content') 
         
<div class="layout-wrapper layout-2">
     <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
                <div class="container-fluid flex-grow-1 container-p-y appointment-master">
              
			<div class="row mb-2 form-top-row">
				<div class="col-sm-3">
					<div class="btn-group"> 
                     <a class="btn btn-primary" href="{{ route('symptoms.SymptomsMaster') }}"> <i class="fa fa-list"></i>  Symptoms List</a>  
                   </div>
				</div>
			</div>
				 
                <div class="layout-content card">
				{!! Form::open(array('route' => 'symptoms.addSymptoms', 'id' => 'addSymptoms', 'class' => 'col-sm-12')) !!}
									<input type="hidden" name="tags" id="symptom_tags_val">
									<div class="form-group row" style="margin-top:15px;">
									<div class="col-md-3 sya-field">
                                        <div class="form-group">
                                            <label>Spaciality</label>
                                            <select class="form-control" id="exampleSelect1" name="spaciality_id[]" size="1" multiple >
												@foreach(getSpecialityList() as $index => $spaciality)
													<option value="{{$spaciality->id}}">{{$spaciality->specialities}}</option>
												@endforeach
											</select>
											<span class="help-block"></span>
                                        </div>
										</div>
										
										<div class="col-md-3 sya-field">
										<div class="form-group">
                                            <label>Symptom Name</label>
                                            <input type="text" name="symptom" class="form-control" placeholder="Enter Symptom Name">
											<span class="help-block"></span>
                                        </div>
										</div>
										<div class="col-md-3 sya-field">
										<div class="form-group">
                                            <label>Symptom Name (Hindi)</label>
                                            <input type="text" name="symptom_hindi" class="form-control" placeholder="Enter Symptom Name Hindi">
											<span class="help-block"></span>
                                        </div>
										</div>
										<div class="col-md-3 sya-field">
										<div class="form-group">
											<label>Disease</label>
											<input type="text" name="disease" class="form-control" placeholder="Enter Disease Name">
											<span class="help-block"></span>
										</div>
										</div>
										</div>
										<div class="form-group row">
										<div class="col-md-6">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" name="description" id="exampleTextarea" rows="5"></textarea>
                                        </div>
										</div>
										<div class="col-md-6">
										<div class="form-group">
                                            <label>Description (Hindi)</label>
                                            <textarea class="form-control" name="description_hindi" id="exampleTextareaHindi" rows="5"></textarea>
                                        </div>
										</div>
										<div class="col-md-6">
										<div class="form-group">
											<label>Cause</label>
											<textarea class="form-control" name="cause" id="exampleCause" rows="5"></textarea>
											<span class="help-block"></span>
										</div>
										</div>
										<div class="col-md-6">
										<div class="form-group">
											<label>Cause (Hindi)</label>
											<textarea class="form-control" name="cause_hindi" id="exampleCauseHindi" rows="5"></textarea>
											<span class="help-block"></span>
										</div>
										</div>
										
										<div class="col-md-6">
										<div class="form-group">
											<label>Treatment</label>
											<textarea class="form-control" name="treatment" id="exampleTreatment" rows="5"></textarea>
										</div>
										</div>
										<div class="col-md-6">
										<div class="form-group">
											<label>Treatment (Hindi)</label>
											<textarea class="form-control" name="treatment_hindi" id="exampleTreatmentHindi" rows="5"></textarea>
										</div>
										</div>
										<div class="col-md-6 symptoms">
										<div class="form-group">
											<label>Symptoms Tags</label>
											 <button type="button" class="btn btn-info btn-xs form-control" data-toggle="modal" data-target="#tagsAddModal">Tags</i>
										</div>
										</div>
										<div class="col-md-6 status">
                                        <div class="form-check">
                                          <label>Status</label><br>
                                          <label class="radio-inline">
                                              <input type="radio" name="status" value="1" checked="checked">Active</label>
                                              <label class="radio-inline"><input type="radio" name="status" value="0" >Inctive</label>
                                        </div>                                       
                                            </div>
                                        <div class="reset-button col-md-12">
                                           <button type="reset" class="btn btn-warning">Reset</button>
                                           <button type="submit" class="btn btn-success submit">Save</button>
										</div>
									 {!! Form::close() !!}
			    </div>	


             </div>
        </div>
     </div>

	 <div class="modal fade" id="tagsAddModal" role="dialog" data-backdrop="static" data-keyboard="false">
	  <div class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-header">
			   <button type="button" class="close" data-dismiss="modal">×</button>
			  <h4 class="modal-title">Add Symptoms Tags</h4>
			</div>
			<div class="modal-body">
				
					<div class="panel-heading">
						<div class="btn-group"> 
							  
						</div>
					</div>
					<div class="panel-body">
						<div id="addedRows">
							<div class="form-group">
								<label>Tag</label>
								<input type="text" class="form-control tag_names" placeholder="Enter Tag Name" name="tags[]">
							</div>
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-default form-control" onclick="addMoreRows();">Add More Rows</button>
						</div>
						<div class="reset button">
						<button type="button" class="btn btn-primary addTags">Save</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					  </div>
					</div>
				
			</div>
		</div>   
	 </div>   
	</div>   

</div>





            <script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>

		   <script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
			<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
			<script type="text/javascript">
				
				$(document).ready(function() {
					$('#exampleSelect1').multiselect({
					includeSelectAllOption: true,
					enableFiltering: true,
					enableCaseInsensitiveFiltering: true,
				});
				});

			jQuery(document).ready(function(){
				jQuery("#addSymptoms").validate({
					rules: {
						'spaciality_id[]': {
							required: true
						},
						symptom: "required",
					 },
					messages:{
					},
					errorPlacement: function(error, element){
						error.appendTo(element.next());
					},ignore: ":hidden",
					submitHandler: function(form) {
						var options = $('#exampleSelect1 > option:selected');
						if(options.length == 0) {
							alert('Select Atleast One Spaciality');
							return false;
						}
						$(form).find('.submit').attr('disabled',true);
						jQuery('.loading-all').show();
						jQuery.ajax({
							type: "POST",
							dataType : "JSON",
							url: "{!! route('symptoms.addSymptoms')!!}",
							data:  new FormData(form),
							contentType: false,
							cache: false,
							processData:false,
							success: function(data) {
								 if(data==1)
								 {
								  jQuery('.loading-all').hide();
								  $(form).find('.submit').attr('disabled',false);
								  document.location.href='{!! route("symptoms.SymptomsMaster")!!}';
								 }
								 else if(data==2)
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
			 <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
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
@endsection		   
       

