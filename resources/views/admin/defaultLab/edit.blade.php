<div class="modal-dialog modal-dialog111">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Defalut Labs</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="" style="margin-bottom:10px;">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('plans.planMaster') }}"> <i class="fa fa-list"></i>  Defalut Labs List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'editLab','name'=>'admin.defLab.edit')) !!}
					<input type=hidden value="{{$lab->id}}" name="id"/>
					<div class="row">
					<div class="form-group col-sm-6">
						<label>Title:</label>
						<input type="text" class="form-control planTitle" placeholder="Enter Title Here" name="title" value="{{$lab->title}}">
						<span class="help-block">
						</span>
					</div>
					<div class="form-group col-sm-6">
						<label>Short Name:</label>
						<input type="text" class="form-control planSlug" placeholder="Enter Short Name" name="short_name" value="{{$lab->short_name}}"  >
						<span class="help-block">
						</span>
					</div>
					<div class="col-md-12">
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
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>

<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
		<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
<script type="text/javascript">
// When the browser is ready...
jQuery(document).ready(function () {
$(document.body).on('click', '.submit', function(){
		jQuery("#editLab").validate({
		rules: {
			title: "required",
			short_name: "required",
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
				url: "{!! route('admin.defLab.update')!!}",
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