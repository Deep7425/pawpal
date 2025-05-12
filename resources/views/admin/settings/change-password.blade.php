<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Change Password</h4>
		</div>
		<div class="modal-body ml-2 mt-2">
			<div class="panel panel-bd lobidrag feedback">
				<div class="panel-body">
					{!! Form::open(array('id' => 'modifySubAdmin','name'=>'modifySubAdmin', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
          <input type=hidden value="{{$user->id}}" name="id"/>
					<input type=hidden value="changePassword" name="action"/>

         <div class="form-group col-md-6">
						<label>Password</label>
            <input value="" type="password" name="password" id="adminPassword" class="form-control" placeholder="Enter Password">
						<span class="help-block"></span>
					</div>
          <div class="form-group col-md-6">
            <label>Confirm Password</label>
            <input type="password" name="password_confirm" class="form-control" placeholder="Enter Confirm Password ">
            <span class="help-block"></span>
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
			<button type="button" id = "close" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>

	</div>


	<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 
	 
	<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script type="text/javascript">

$(document.body).on('click', '.submit', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='modifySubAdmin']").validate({
			rules: {
        password: "required",
        password: {
          required: true,
          minlength: 6
        },
        password_confirm: {
          required: true,
          equalTo: "#adminPassword"
        }
			 },
			messages:{
        password: {
          required: "Please enter the password!",
          minlength: "Password should contain at least 6 character!"
        },
        password_confirm: {
          required: "Enter Confirm Password",
          equalTo: "Password Does Not Match"
        }
			},
			errorPlacement: function(error, element){

				error.appendTo(element.parent().find('.help-block'));
			},ignore: ":hidden",
			submitHandler: function(form) {
				$(form).find('.submit').attr('disabled',true);
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.modifySubAdmin')!!}",
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
						 else  if(data==2)
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
						  alert('Email Id already exists');
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
	$(document.body).on('click', '#close', function(){
		location.reload(true);
	});

</script>
