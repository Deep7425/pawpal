@extends('layouts.admin.Masters.Master')
@section('title', 'Add Speciality')
@section('content')
         


<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
                <div class="container-fluid flex-grow-1 container-p-y addSpeciality">

                        <div class="row form-top-row">
							<div class="btn-group">
                        	      <a class="btn btn-primary" href="{{ route('admin.specialityAll') }}"> <i class="fa fa-list"></i>  Speciality List</a>
                        	</div>
						</div>

			     	<div class="layout-content card" >
					 {!! Form::open(array('id' => 'addSpeciality','name'=>'addSpeciality', 'enctype' => 'multipart/form-data')) !!}
                    <div class="row mt-sm-2 mb-sm-2 ml-1 mr-1">
					  <div class="form-group col-sm-3">
											<label>Specialities</label>
											<input type="text" name="specialities" class="form-control speTitle" placeholder="Enter Specialities Name"/>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-3">
											<label>Slug</label>
											<input type="text" name="slug" class="form-control speSlug" placeholder="Enter Slug (In Small letter without space)"/>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-3">
											<label>Specialisty</label>
											<input  type="text" name="spaciality" class="form-control" placeholder="Enter spaciality Name"/>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-3">
											<label>Specialty (Hindi)</label>
											<input type="text" name="spaciality_hindi" class="form-control speTitle" placeholder="Enter Specialty in hindi"/>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-3">
											<label>Specialist Text</label>
											<input  type="text" name="speciality_text" class="form-control" placeholder="Enter speciality Text"/>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-3">
											<label>Order No.</label>
											<input  type="number" name="order_no" class="form-control" maxlength="4" placeholder="Enter Order No."/>
											<span class="help-block"></span>
										</div>
										
										<div class="form-group col-sm-3">
											<label>Keywords</label>
											<input  type="text" name="keywords" class="form-control" placeholder="Enter keywords with comma delemeted"/>
											<span class="help-block"></span>
										</div>
										<div class="col-md-3">
												<label>Icon</label>
												<input type="file" name="speciality_icon" class="form-control"/>
												<span class="help-block"></span>
											</div>
											<div class="form-group col-sm-3">
											<label>Alt Tag</label>
											<input  type="text" name="alt_tag" class="form-control" placeholder="Enter alt tag"/>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-3">
											<label>Tags</label>
											<input  type="text" name="tags" class="form-control" placeholder="Enter tags with comma delemeted"/>
											<span class="help-block"></span>
										</div>
										<div class="col-md-3">
												<label>Speciality Image</label>
												<input type="file" name="speciality_image" class="form-control"/>
												<span class="help-block"></span>
											</div>
											<div class="form-group col-sm-3 Box-Specialities3">
										   <label> &nbsp </label>
											<select class="form-control searchDropDown group_id" name="group_id">
												<option value="">Speciality Group</option>
												@foreach(getSpecialityGroupList() as $spc)
													<option value="{{ $spc->id }}"  >{{ $spc->group_name }}</option>
												@endforeach
											</select>
											 <span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label></span>
										</div>
											<div class="form-group col-sm-6  Box-Specialities2">
											<label>Speciality Description Hindi</label>
											<textarea name="spec_desc_hindi" class="form-control" rows="25" cols="50" placeholder="Enter Speciality Description Hindi"></textarea>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-6  Box-Specialities2" style="display: none;">
				                            <label>Show Speciality Description</label>
				                            <br>
				                            <label class="radio-inline"><input type="radio" name="manage_spec_desc" value="0" checked="checked">English</label>
				                            <label class="radio-inline"><input type="radio" name="manage_spec_desc" value="1">Hindi</label>
				                        </div>
										<div class="form-group col-sm-6  Box-Specialities2">
											<label>Speciality Description English</label>
											<textarea name="spec_desc" class="form-control" rows="25" cols="50" placeholder="Enter Speciality Description English"></textarea>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-12  Box-Specialities2">
											<label>SEO Description</label>
											<textarea name="description" class="form-control" rows="20" cols="50" placeholder="Enter SEO Description"></textarea>
											<span class="help-block"></span>
										</div>
									
									
											<div class="reset-button  col-sm-12">
										   <button type="reset" class="btn btn-warning">Reset</button>
										   <button type="submit" class="btn btn-success submit">Add</button>
										</div>
									 {!! Form::close() !!}
										

					  </div>

			    </div>
          </div>
       </div>
   </div>
</div>

<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>



		   <script type="text/javascript">
			// $(".searchDropDown").select2();
			jQuery('.searchDropDown').on('change', function() {
			  if (this.value != "") {
				$(this).parent('.form-group').find('.help-block .error').hide();
			  }
			});
			$(document.body).on('click', '.submit', function(){
			
				 jQuery("form[name='addSpeciality']").validate({
					rules: {
						specialities: "required",
						description: "required",
						keywords: "required",
						slug: "required",
						spaciality: "required",
						speciality_text: "required",
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
							url: "{!! route('admin.addSpeciality')!!}",
							data:  new FormData(form),
							contentType: false,
							cache: false,
							processData:false,
							success: function(data) {

								 if(data==1)
								 {
								  jQuery('.loading-all').hide();
								  $(form).find('.submit').attr('disabled',false);
								  document.location.href='{!! route("admin.specialityAll")!!}';
								 }
								  else if(data==2)
								 {
								  jQuery('.loading-all').hide();
								  $(form).find('.submit').attr('disabled',false);
								  alert("Specialities Name Already Exists");
								 }
								 else if(data==3)
								 {
								  jQuery('.loading-all').hide();
								  $(form).find('.submit').attr('disabled',false);
								  alert("Order No. already exists");
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
      jQuery(document).on("keyup", ".speTitle", function () {
        var str = this.value;
        str = str.replace(/[^a-zA-Z0-9\s]/g,"");
        str = str.toLowerCase();
        str = str.replace(/\s/g,'-');
        $('.speSlug').val(str);
      });
			</script>
@endsection
