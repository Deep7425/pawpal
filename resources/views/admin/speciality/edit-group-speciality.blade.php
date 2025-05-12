<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Speciality Group</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag update-page">
				<div class="">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.specialityGroupMaster') }}"> <i class="fa fa-list"></i>Speciality Group List</a>
					</div>
				</div>
				<div class="panel-body" style="padding-top:15px">
					{!! Form::open(array('id' => 'updateGroupSpeciality','name'=>'updateGroupSpeciality', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
					<input type=hidden value="{{$speciality->id}}" name="id"/>
					<div class="form-group col-md-6">
						<label>Group Name</label>
						<input value="{{@$speciality->group_name}}" type="text" name="group_name" class="form-control" placeholder="Enter Group Name"/>
						<span class="help-block"></span>
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
<script type="text/javascript">
	$(document.body).on('click', '.submit', function(){
		 jQuery("form[name='updateGroupSpeciality']").validate({
			rules: {
				group_name: "required",
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
					url: "{!! route('admin.updateGroupSpeciality')!!}",
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
						else if(data==2)
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
						  alert("Speciality Group Name Already Exists");
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
	$('.btn-default').click(function() {
    $('.modal').modal('hide');
});

$('.close').click(function() {
    $('.modal').modal('hide');
});
</script>
