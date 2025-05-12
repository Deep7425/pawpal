<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Speciality</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag update-page">
				<div class="">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.specialityAll') }}"> <i class="fa fa-list"></i>Speciality List</a>
					</div>
				</div>
				<div class="panel-body Block-Specialities">
					{!! Form::open(array('id' => 'updateSpeciality','name'=>'updateSpeciality', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
					<input type=hidden value="{{$speciality->id}}" name="id"/>
					<div class="form-group col-sm-6">
						<label>Specialities</label>
						<input value="{{@$speciality->specialities}}" type="text" name="specialities" class="form-control speTitle" placeholder="Enter Specialities Name"/>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6">
						<label>Slug</label>
						<input value="{{@$speciality->slug}}" type="text" name="slug" class="form-control speSlug" placeholder="Enter Slug(In Small letter without space)"/>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6">
						<label>Specialist</label>
						<input value="{{@$speciality->spaciality}}" type="text" name="spaciality" class="form-control" placeholder="Enter spaciality Name"/>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6">
						<label>Specialist Text</label>
						<input value="{{@$speciality->speciality_text}}" type="text" name="speciality_text" class="form-control" placeholder="Enter speciality Text"/>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6">
						<label>Specialty (Hindi)</label>
						<input value="{{@$speciality->spaciality_hindi}}" type="text" name="spaciality_hindi" class="form-control" placeholder="Enter Specialty in hindi"/>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6">
						<label>Keywords</label>
						<input  type="text" value="{{@$speciality->keywords}}"  name="keywords" class="form-control" placeholder="Enter keywords with comma delemeted"/>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6">
						<label>Order No.</label>
						<input type="number" value="{{@$speciality->order_no}}"  name="order_no" maxlength="4" class="form-control" placeholder="Enter Order No."/>
						<input type="hidden" value="{{@$speciality->order_no}}"  name="old_order_no"/>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6">
						<label>Tags</label>
						<input type="text" value="{{@$speciality->tags}}"  name="tags" class="form-control" placeholder="Enter Tags with comma delemeted"/>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6 Box-Specialities2">
						<label>SEO Description</label>
						<textarea name="description" value="{{@$speciality->description}}" class="form-control" placeholder="Enter SEO Description">{{@$speciality->description}}</textarea>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6  Box-Specialities2">
						<label>Speciality Description</label>
						<textarea name="spec_desc" value="{{@$speciality->spec_desc}}" class="form-control" rows="50" cols="50" placeholder="Enter Speciality Description English">{{@$speciality->spec_desc}}</textarea>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6  Box-Specialities2">
						<label>Speciality Description Hindi</label>
						<textarea name="spec_desc_hindi" class="form-control" rows="50" cols="50" placeholder="Enter Speciality Description Hindi">{{@$speciality->spec_desc_hindi}}</textarea>
						<span class="help-block"></span>
					</div>
					<div class="form-group col-sm-6  Box-Specialities2" style="display: none;">
                        <label>Show Speciality Description</label>
                        <br>
                        <label class="radio-inline"><input type="radio" name="manage_spec_desc" value="0" @if($speciality->manage_spec_desc == 0) checked @endif>English</label>
                        <label class="radio-inline"><input type="radio" name="manage_spec_desc" value="1" @if($speciality->manage_spec_desc == 1) checked @endif>Hindi</label>
                    </div>
					<div class="form-group col-sm-6">
						<label>Group Name</label>
						<select class="form-control searchDropDown group_id" name="group_id">
							<option value="">Speciality Group</option>
							@foreach(getSpecialityGroupList() as $spc)
								<option value="{{ $spc->id }}"
								@if($speciality->group_id == $spc->id) selected @endif >{{ $spc->group_name }}</option>
							@endforeach
						</select>
						 <span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label></span>
					</div>
					
					
						<div class="form-group col-md-6">
							<label>Image</label>
							<input value="{{@$speciality->speciality_icon}}" type="hidden" name="speciality_icon_old" class="form-control"/>
							<input type="file" name="speciality_icon" class="form-control"/>
							<span class="help-block"></span>
						</div>
						<div class="form-group  col-md-6">
							<label>Speciality Image</label>
							<input value="{{@$speciality->speciality_image}}" type="hidden" name="speciality_image_old" class="form-control"/>
							<input type="file" name="speciality_image" class="form-control"/>
							<span class="help-block"></span>
						</div>
						<div class="form-group  old-img col-md-6">
							@if(!empty($speciality->speciality_icon))
							<label style="width:100%; float:left;">Old Image</label>
							<img src="<?php echo url("/")."/public/speciality-icon/".$speciality->speciality_icon;?>" class="img-responsive" alt="Banner Speciality Icon" height="50" width="100" style="text-align:center;" />
							@endif
						</div>
					
						
						<div class="form-group  col-md-6">
							@if(!empty($speciality->speciality_image))
							<label>Old Speciality Image</label>
							<img src="<?php echo url("/")."/public/speciality-images/".$speciality->speciality_image;?>" class="img-responsive" alt="Banner Speciality Image" height="50" width="100" style="text-align:center;"/>
							@endif
						</div>




						<div class="form-group col-sm-6 Status">
					  <label>Status</label><br>
					  <label class="radio-inline">
						  <input type="radio" name="status" value="1" @if(@$speciality->status == '1') checked="checked" @endif>Active</label>
						  <label class="radio-inline"><input type="radio" name="status" value="0"  @if(@$speciality->status == '0') checked="checked" @endif>Inctive</label>
					</div>
					
					<div class="form-group col-sm-6">
						<label>Alt Tag</label>
						<input value="{{@$speciality->alt_tag}}" type="text" name="alt_tag" class="form-control" placeholder="Enter alt tag"/>
						<span class="help-block"></span>
					</div>
					<div class="reset-button col-sm-12">
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
	<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>



<script type="text/javascript">

	
// $('.btn-default').click(function() {
//     $('.modal').modal('hide');
// });

// $('.close').click(function() {
//     $('.modal').modal('hide');
// });


	jQuery('.searchDropDown').on('change', function() {
	  if (this.value != "") {
		$(this).parent('.form-group').find('.help-block .error').hide();
	  }
	});
	$(document.body).on('click', '.submit', function(){

		 jQuery("form[name='updateSpeciality']").validate({
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
					url: "{!! route('admin.updateSpeciality')!!}",
					data:  new FormData(form),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data) {
						 if(data==1)
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
						   jQuery('#spacialityEditModal').modal('hide');
							// location.reload();
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
