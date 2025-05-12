@extends('layouts.admin.Masters.Master')
@section('title', 'Add Banner')
@section('content')

<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            
                <div class="container-fluid flex-grow-1 container-p-y adv-banner">
					<div class="row form-top-row">
						<div class="col-sm-3">
						<div class="btn-group">
                             <a class="btn btn-primary" href="{{ route('admin.adBannerMaster') }}"> <i class="fa fa-list"></i>  Banners List</a>
                         </div>
						</div>
					</div>
				<div class="layout-content card" >
				{!! Form::open(array('route' => 'admin.addAdBanner', 'id' => 'addAdBanner','enctype' => 'multipart/form-data' , 'class' => 'col-sm-12')) !!}
			                
				               <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label>Title</label>
                                            <input type="text" name="title" class="form-control" placeholder="Enter Title Name">
											<span class="help-block"></span>
                                        </div>
										<div class="form-group col-sm-3">
											<label>Image</label>
											<input type="file" name="image" class="form-control" placeholder="Select Image">
											<span class="help-block"></span>
										</div>
                                      <div class="form-group col-sm-3">
											<label>Type</label>
                                  <select class="form-control valid" name="type">
                                   <option value="">Select Type</option>
										<option value="1">English</option>
            							<option value="2">Hindi</option>
                                       </select>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-3">
										<label>Area</label>
										  <select class="form-control valid" name="area">
											<option value="">Select Type</option>
											<option value="1" >pop-up</option>
											<option value="2" >Middle</option>
											<option value="3" >Bottom</option>
											<option value="4" >Top</option>
										  </select>
										<span class="help-block"></span>
									  </div>
                    <div class="form-group col-sm-3">
                        <label>Link URL</label>
                        <input type="text" name="link_url" class="form-control" value="" placeholder="Enter Link URL">
                        <span class="help-block"></span>
                    </div>
					<div class="col-sm-3 form-group">
                      <label>Exipre Date:</label>
                      <input type="text" class="form-control datepicker" readonly placeholder="dd-mm-YYYY" name="expiry_date" autocomplete="off"/>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>

		   <script type="text/javascript">
			jQuery(document).ready(function(){
					jQuery( ".datepicker" ).datepicker({
					  dateFormat: 'dd-mm-yy',
					  minDate: new Date(),
					});
				jQuery("#addAdBanner").validate({
					rules: {
						title: "required",
						type: "required",
						image: "required",
						area: "required",
						link_url: "required",
						expiry_date: "required",
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
							url: "{!! route('admin.addAdBanner')!!}",
							data:  new FormData(form),
							contentType: false,
							cache: false,
							processData:false,
							success: function(data) {
								 if(data==1)
								 {
								  jQuery('.loading-all').hide();
								  $(form).find('.submit').attr('disabled',false);
								  document.location.href='{!! route("admin.adBannerMaster")!!}';
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
