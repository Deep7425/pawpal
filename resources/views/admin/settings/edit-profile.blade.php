@extends('layouts.admin.Masters.Master')
@section('title', 'Update Profile')
@section('content')
<div class="content-wrapper">
    <!-- Modal content-->
    <!-- <div class="modal-content "> -->
    <section class="content-header">
                    <div class="header-icon">
                        <i class="pe-7s-box1"></i>
                    </div>
                    <div class="header-title">
                        <form action="#" method="get" class="sidebar-form search-box pull-right hidden-md hidden-lg hidden-sm">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button type="submit" name="search" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                        <h1>Profile</h1>
                        <small>Update Profile</small>
                        <ol class="breadcrumb hidden-xs">
                            <li class="active">Profile</li>
                        </ol>
                    </div>
                </section>
		<div class="modal-body edit-profile">
			<div class="panel panel-bd lobidrag form-top-row">
				<div class="panel-heading">
					<div class="btn-group">
						
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'updateProfile','name'=>'updateProfile', 'enctype' => 'multipart/form-data')) !!}
                    
					<div class="row">
					<input type=hidden value="{{$user->id}}" name="id"/>
					<input type=hidden value="edit" name="action"/>
					<div class="form-group col-sm-3">
						<label>Full Name</label>
						<input value="{{@$user->name}}" type="text" name="name" class="form-control" placeholder="Enter Name">
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-3">
						<label>E-Mail</label>
						<input value="{{@$user->email}}" type="text" name="email" class="form-control" placeholder="Enter E-Mail">
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-3">
						<label>Mobile No.</label>
						<input value="{{@$user->mobile_no}}" type="text" name="mobile_no" class="form-control" placeholder="Enter Mobile No.">
						<span class="help-block"></span>
					</div>
                    <div class="form-group col-sm-3">
						<label>Password</label>
                    <input value="" type="password" name="password" id="adminPassword" class="form-control" placeholder="Enter Password">
						<input value="{{@$user->password}}" type="hidden" name="current_password" class="form-control">
						<span class="help-block"></span>
					</div>
                    <div class="form-group col-sm-3">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Enter Confirm Password ">
                        <span class="help-block"></span>
                    </div>


					<!-- <div class="form-check">
					  <label>Status</label><br>
					  <label class="radio-inline">
						  <input type="radio" name="status" value="1" 	@if(@$user->status == '1') checked="checked" @endif>Active</label>
						  <label class="radio-inline"><input type="radio" name="status" value="0"  @if(@$user->status == '0') checked="checked" @endif>Inctive</label>
					</div> -->
                    <div class="col-sm-12">
					   <p><strong>Note: </strong>Leave the password blank if you don't want to update it.</p>
					</div>

					<div class="reset-button col-sm-12">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success submit">Update</button>
					</div>
					</div>
				 {!! Form::close() !!}

				</div>
			</div>

		</div>
		<!-- <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div> -->
	<!-- </div> -->

	</div>
	   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script type="text/javascript">

$(document.body).on('click', '.submit', function(){
		// jQuery("#updateProfile").validate({
		 jQuery("form[name='updateProfile']").validate({
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
        },
        password: {
            minlength: 5,
        },
        password_confirm: {
            minlength: 5,
            equalTo: "#adminPassword"
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
					url: "{!! route('admin.updateProfile')!!}",
					data:  new FormData(form),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data) {
						 if(data==1)
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
                          location.href='{{route("admin.home")}}';
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

</script>
@endsection