<div class="modal-dialog modal-dialog111">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Collection Labs</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="" style="margin-bottom:10px;">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('plans.planMaster') }}"> <i class="fa fa-list"></i>  Collection Labs List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'editLab','name'=>'admin.labCollection.edit')) !!}
					<input type=hidden value="{{$lab->id}}" name="id"/>
					<div class="row">
					<div class="col-md-6 pad-left0">
					
						<label>Company Name</label>
						<select name="company_id" class="form-control">
							<option value="">Select Company</option>
							@forelse(getLabCompanies() as $raw)
							<option value="{{$raw->id}}" @if($raw->id == $lab->company_id) selected @endif>{{$raw->title}}</option>
							@empty
							@endforelse
						</select>
						<span class="help-block"></span>
			
					</div>
					<div class="col-md-6">
					
						<label>Lab Name</label>
						<input name="lab_name" value="{{$lab->DefaultLabs->title}}" type="text" class="form-control labSearch" placeholder="Enter Lab Name"/>
						<input type="hidden" name="lab_id" class="form-control" value="{{$lab->lab_id}}"/>
						<span class="help-block"></span>
						<div class="suggesstion-box" style="display:none;"></div>
					
					</div>
					<div class="col-md-6">
					
						<label>Method</label>
						<input type="text" name="method" class="form-control" placeholder="Enter Method" value="{{$lab->method}}"/>
						<span class="help-block"></span>
					
					</div>
					<div class="col-md-6">
					
						<label>Instruction</label>
						<input type="text" name="instruction" class="form-control" placeholder="Enter instruction" value="{{$lab->instruction}}"/>
						<span class="help-block"></span>
					</div>
				
					<div class="col-md-6">
					
						<label>Information</label>
						<textarea type="text" name="information" class="form-control" placeholder="Enter Information" value="{{$lab->information}}">{{$lab->information}}</textarea>
						<span class="help-block"></span>
				
					</div>
					<div class="col-md-6">
					
						<label>Cost</label>
						<input type="text" name="cost" class="form-control" placeholder="Enter Cost" value="{{$lab->cost}}"/>
						<span class="help-block"></span>
					
					</div>
					<div class="col-md-6">
					
						<label>Offer Rate</label>
						<input type="text" name="offer_rate" class="form-control" placeholder="Enter Offer Rate" value="{{$lab->offer_rate}}"/>
						<span class="help-block"></span>
					
					</div>
					
					<div class="col-md-6">
						<label>Reporting</label>
						<input type="text" name="reporting" class="form-control" placeholder="Enter Reporting" value="{{$lab->reporting}}"/>
						<span class="help-block"></span>
					</div>
				
					<?php  $sub_lab_id = explode(",",$lab->sub_lab_id);?>
					<div class="col-md-12">
						<div class=" labDropDown">
							<label>Sub Lab</label>
							<select name="sub_lab_id[]" id="exampleSelect22" data-show-subtext="true" data-live-search="true" class="form-control" multiple>
								<option value="">Select Company</option>
								@foreach($defaultLab as $data)
								<option value="{{$data->id}}" @if(in_array($data->id,$sub_lab_id)) selected @endif>{{$data->title}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="reset-button">
						   <button type="reset" class="btn btn-warning">Reset</button>
						   <button type="submit" class="btn btn-success submit">Update</button>
						</div>
					</div></div>
				 {!! Form::close() !!}
				</div>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>


<script type="text/javascript">
// When the browser is ready...
jQuery(document).ready(function () {
$('#exampleSelect22').multiselect({
	includeSelectAllOption: true,
	enableFiltering: true,
	enableCaseInsensitiveFiltering: true,
});
$(document.body).on('click', '.submit', function(){
		jQuery("#editLab").validate({
		rules: {
			company_id: {required:true},
			lab_name: {required:true},
		},
		// Specify the validation error messages
		messages: {
			title: "Please enter title",
			short_name: "Please enter short name",
		},
		errorPlacement: function(error, element) {
				 error.appendTo(element.next());
		},
		submitHandler: function(form) {
			jQuery('.loading-all').show();
			jQuery('.submit').attr('disabled',true);
			jQuery.ajax({
				type: "POST",
				dataType : "JSON",
				url: "{!! route('admin.labCollection.update')!!}",
				data:  new FormData(form),
				contentType: false,
				cache: false,
				processData:false,
				success: function(data) {
					 if(data==1) {
						jQuery('.submit').attr('disabled',false);
						location.reload();
					 }
					 else {
						alert("System Problem");
					 }
					 jQuery('.submit').attr('disabled',false);
					 jQuery('.loading-all').hide();
				 },
				 error: function(error){
					 jQuery('.submit').attr('disabled',false);
					 jQuery('.loading-all').hide();
					 alert("Oops Something goes Wrong.");
				 }
			});
		}
	});
  });
});
</script>
