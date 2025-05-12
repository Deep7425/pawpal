@extends('layouts.admin.Masters.Master')
@section('title', 'Add Subadmin')
@section('content')
    

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="layout-content">
            <div class="container-fluid flex-grow-1 container-p-y ml-1 sub-admin ">
             
              <!-- <h4 class="font-weight-bold py-3 mb-0">Add Subadmin</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Admin</a></li>
                                <li class="breadcrumb-item active"><a href="#!">Add Subadmin</a></li>
                            </ol>
                        </div> -->
            <div class="card card-body panel-body  ">

{!! Form::open(array('route' => 'admin.addSubAdmin', 'id' => 'addSubadmin','enctype' => 'multipart/form-data' , 'class' => 'col-sm-12')) !!}
<div class="row">    
<div class="form-group col-sm-3">
      <label>Full Name</label>
      <input type="text" name="name" class="form-control" placeholder="Enter Name ">
                            <span class="help-block"></span>
    </div>
    <div class="form-group col-sm-3">
      <label>E-Mail</label>
      <input type="text" name="email" class="form-control" placeholder="Enter E-Mail ">
                            <span class="help-block"></span>
    </div>
    <div class="form-group col-sm-3">
      <label>Mobile No.</label>
      <input type="text" name="mobile_no" class="form-control" placeholder="Enter Mobile No. ">
                            <span class="help-block"></span>
    </div>
    <div class="form-group col-sm-3">
      <label>Password</label>
      <input type="password" name="password" id="adminPassword" class="form-control" placeholder="Enter Password ">
                     <span class="help-block"></span>
    </div>
    <div class="form-group col-sm-3">
      <label>Confirm Password</label>
      <input type="password" name="password_confirm" class="form-control" placeholder="Enter Confirm Password ">
                            <span class="help-block"></span>
    </div>


    <div class="form-check col-sm-3">
      <label>Status</label><br>
      <label class="radio-inline">
          <input type="radio" name="status" value="1" checked="checked">Active</label>
          <label class="radio-inline"><input type="radio" name="status" value="0" >Inctive</label>
    </div>
    <div class="reset-button col-sm-12">
       <button type="reset" class="btn btn-warning">Reset</button>
       <button type="submit" class="btn btn-success submit">Save</button>
    </div></div>  
 {!! Form::close() !!}
</div></div>
             
       </div>   
  </div>   
</div>   

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script type="text/javascript">

			jQuery(document).ready(function(){
				jQuery("#addSubadmin").validate({
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
              required: true,
              minlength: 6
            },
            password_confirm : {
              minlength : 6,
              equalTo : "#adminPassword"
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
							url: "{!! route('admin.addSubAdmin')!!}",
							data:  new FormData(form),
							contentType: false,
							cache: false,
							processData:false,
							success: function(data) {
                console.log("Server response:", data);
              
								 if(data==1)
                                
								 {

								  jQuery('.loading-all').hide();
								  $(form).find('.submit').attr('disabled',false);
								  document.location.href='{!! route("admin.subadminList")!!}';
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
