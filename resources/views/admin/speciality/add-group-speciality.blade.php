@extends('layouts.admin.Masters.Master')
@section('title', 'Add Group Speciality')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y user-list">    
		
                <!-- Content Header (Page header) -->
         
                 <!-- Main content -->
               
                            
                                    <div class="row form-top-row">
                                        <a class="btn btn-primary" href="{{ route('admin.specialityGroupMaster') }}"> <i class="fa fa-list"></i>  Speciality Group List</a>
                                    </div>
                              
                                <div class="layout-content card ad-group">
										{!! Form::open(array('id' => 'addGroupSpeciality','name'=>'addGroupSpeciality', 'enctype' => 'multipart/form-data')) !!}
										<div class="row">
										<div class="form-group col-sm-3">
											<label>Group Name</label>
											<input type="text" name="group_name" class="form-control" placeholder="Enter Group Name"/>
											<span class="help-block"></span>
										</div>
										
										<div class="reset-button col-sm-12">
										   <button type="reset" class="btn btn-warning">Reset</button>
										   <button type="submit" class="btn btn-success submit">Add</button>
										</div>
										</div>
							</div></div></div></div>

						
              <!-- /.content -->
           </div>

		   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

		   <script type="text/javascript">
			$(document.body).on('click', '.submit', function(){
				 jQuery("form[name='addGroupSpeciality']").validate({
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
							url: "{!! route('admin.addGroupSpeciality')!!}",
							data:  new FormData(form),
							contentType: false,
							cache: false,
							processData:false,
							success: function(data) {
								 if(data==1)
								 {
								  jQuery('.loading-all').hide();
								  $(form).find('.submit').attr('disabled',false);
								  document.location.href='{!! route("admin.specialityGroupMaster")!!}';
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
			</script>
@endsection
