@extends('layouts.admin.Masters.Master')
@section('title', 'Add Thyrocare Package')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y appoint-list">

            <div class="row mb-2 ml-1 form-top-row">
            
               <div class="btn-group" style="float:left;">
                                        <a class="btn btn-primary" href="{{ route('admin.thyrocarePackageMaster') }}"> <i class="fa fa-list"></i> Package Group List</a>
                                    </div>
             </div>


                        <!-- Form controls -->
                        <div class="layout-content card appointment-master pad-20">
                            <div class=" lobidrag">
                               


                                <div class="panel-body" style="width: 100%; float: left;">

                                    {!! Form::open(array('route' => 'admin.addThyrocarePackage', 'id' => 'addThyrocarePackage','enctype' => 'multipart/form-data' , 'class' => 'col-sm-12')) !!}
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label>Group Name</label>
                                            <input type="text" name="group_name" class="form-control" placeholder="Enter Group Name">
											<span class="help-block"></span>
                                        </div>
										<div class="form-group col-sm-3">
											<label>Image</label>
											<input type="file" name="image" class="form-control" placeholder="Select Image">
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
										</div>
                                        </div>
									 {!! Form::close() !!}
                               </div>
                           </div>
                       </div>
                       </div>
                       </div>
                       </div>
                       </div>




         
       
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

		   <script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#addThyrocarePackage").validate({
					rules: {
					group_name: "required",
					image: "required",
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
							url: "{!! route('admin.addThyrocarePackage')!!}",
							data:  new FormData(form),
							contentType: false,
							cache: false,
							processData:false,
							success: function(data) {
								 if(data==1)
								 {
								  jQuery('.loading-all').hide();
								  $(form).find('.submit').attr('disabled',false);
								  document.location.href='{!! route("admin.thyrocarePackageMaster")!!}';
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
