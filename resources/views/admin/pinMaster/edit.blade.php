<div class="modal-dialog modal-dialog111">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title"> Labs Pin Code</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="" style="padding-bottom:15px;">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('lab.company.pin') }}"> <i class="fa fa-list"></i>  Defalut Labs List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'editLab','name'=>'admin.pinMaster.edit')) !!}
					
					<div class="row">
						<input type=hidden value="{{$lab->id}}" name="id"/>
          <div class="col-md-6 pad-left0">
  				<div class="form-group">
  					<label>Company Name</label>
  					<select name="company_id" class="form-control ">
  						@forelse(getLabCompanies() as $raw)
  						<option value="{{$raw->id}}" @if($lab->company_id == $raw->id) selected @endif>{{$raw->title}}</option>
  						@empty
  						@endforelse
  					</select>
  					<span class="help-block"></span>
  				</div>
  				</div>
  				<div class="col-md-6">
  				<div class="form-group labDropDown">
  					<label>Pin </label>
  				<input  name="pincode"  type="number" class="form-control" value="{{$lab->pincode}}"/>
  					<span class="help-block"></span>
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

<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
setValue();
});
function setValue(){
	$('#exampleSelect2').multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
	});
}
// When the browser is ready...
jQuery(document).ready(function () {
$(document.body).on('click', '.submit', function(){
		jQuery("#editLab").validate({
		rules: {
      company_id: {required:true},
      pincode: {required:true,rangelength: [6, 6],number:true},
		},
		// Specify the validation error messages
		messages: {
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
				url: "{!! route('admin.labPackage.pin.update')!!}",
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
