
<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update SubAdmin</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag feedback">
				<div style="padding-bottom:15px;">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.subadminList') }}"> <i class="fa fa-list"></i>  Subadmin List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'modifySubAdmin','name'=>'modifySubAdmin', 'enctype' => 'multipart/form-data')) !!}
         			<div class="row">
					<input type=hidden value="{{$user->id}}" name="id"/>
					<input type=hidden value="edit" name="action"/>
					<div class="form-group col-md-6">
						<label>Full Name</label>
						<input value="{{@$user->name}}" type="text" name="name" class="form-control" placeholder="Enter Name">
						<span class="help-block"></span>
					</div>
					<div class="form-group col-md-6">
						<label>E-Mail</label>
						<input value="{{@$user->email}}" type="text" name="email" class="form-control" placeholder="Enter E-Mail">
						<span class="help-block"></span>
					</div>
					<div class="form-group col-md-6">
						<label>Mobile No.</label>
						<input value="{{@$user->mobile_no}}" type="text" name="mobile_no" class="form-control" placeholder="Enter Mobile No.">
						<span class="help-block"></span>
					</div>
         <!-- <div class="form-group">
						<label>Password</label>
            <input value="" type="password" name="password" id="adminPassword" class="form-control" placeholder="Enter Password">
						<input value="{{@$user->password}}" type="hidden" name="current_password" class="form-control">
						<span class="help-block"></span>
					</div>
          <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirm" class="form-control" placeholder="Enter Confirm Password ">
            <span class="help-block"></span>
          </div> -->


					<div class="form-check col-md-6">
					  <label>Status</label><br>
					  <label class="radio-inline">
						  <input type="radio" name="status" value="1" 	@if(@$user->status == '1') checked="checked" @endif>Active</label>
						  <label class="radio-inline"><input type="radio" name="status" value="0"  @if(@$user->status == '0') checked="checked" @endif>Inctive</label>
					</div>

					<div class="reset-button">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success submit">Update</button>
					</div></div>
				 {!! Form::close() !!}

				</div>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button" id = "close" class="btn btn-default close" data-dismiss="modal">Close</button>
		</div>
	</div>

	</div>

	<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
	

	

	
<script type="text/javascript">

$(document.body).on('click', '.submit', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='modifySubAdmin']").validate({
			rules: {
				name: "required",
        email: {
          required: true,
          // Specify that email should be validated
          // by the built-in "email" rule
          email: true
        },
        mobile_no: {
          required: true,
          minlength: 10,
          number: true
        }
			 },
			messages:{
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

	$(document.body).on('click', '.close', function(){
		location.reload(true);
	})


</script>
