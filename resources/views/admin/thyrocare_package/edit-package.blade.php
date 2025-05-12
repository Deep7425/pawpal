<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Package Group</h4>
		</div>
		<div class="modal-body edit-package">
			<div class="panel panel-bd lobidrag">
				<div class="">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.thyrocarePackageMaster') }}"> <i class="fa fa-list"></i> Package Group List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'updateThyrocarePackage','name'=>'updateThyrocarePackage', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
					<input type=hidden value="{{$package->id}}" name="id"/>
					<div class="col-md-6">
						<label>Group Name</label>
						<input value="{{@$package->group_name}}" type="text" name="group_name" class="form-control" placeholder="Enter Group Name"/>
						<span class="help-block"></span>
					</div>
					
						<div class="col-md-6">
						<label>Image</label>
						<input value="{{@$package->image}}" type="hidden" name="old_image" class="form-control"/>
						<input type="file" name="image" class="form-control"/>
						<span class="help-block"></span>
						</div>
						<div class="col-md-6 old-image">
							@if(!empty($package->image))
							<label>Old Image</label>
							<img src="<?php echo url("/")."/public/thyrocarePackageFiles/".$package->image;?>" class="img-responsive" alt="Banner Image" height="50" width="100" style="text-align:center;">
							@endif
						</div>
					
					<div class="col-md-6">
					  <label>Status</label><br>
					  <label class="radio-inline">
						  <input type="radio" name="status" value="1" @if(@$package->status == '1') checked="checked" @endif>Active</label>
						  <label class="radio-inline"><input type="radio" name="status" value="0"  @if(@$package->status == '0') checked="checked" @endif>Inctive</label>
					</div>

					<div class="reset-button col-md-12">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success submit">Update</button>
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

$(document.body).on('click', '.submit', function(){
		// jQuery("#updateThyrocarePackage").validate({
		 jQuery("form[name='updateThyrocarePackage']").validate({
			rules: {
				title: "required",
        type: "required",
			 },
			messages:{
			},
			errorPlacement: function(error, element){
				error.appendTo(element.next());
			},ignore: ":hidden",
			submitHandler: function(form) {
				$(form).find('.submit').attr('disabled',true);
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.updateThyrocarePackage')!!}",
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
						// document.location.href='{!! route("admin.blogMaster")!!}';
						 }
						 else
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
						  alert("Oops Something Problem");
						 }
					}
				});
			}
		});
	});
</script>
