@extends('layouts.admin.Masters.Master')
@section('title', 'Add Locality')
@section('content') 
          


<div class="layout-wrapper layout-2">
     <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
                <div class="container-fluid flex-grow-1 container-p-y">
                   <div class = "row form-top-row">
                       <div class = "">
					   <div class="btn-group"> 
                            <a class="btn btn-primary" href="{{ route('admin.localityMaster') }}"> <i class="fa fa-list"></i>  Locality List</a>  
                        </div>
					   </div>
                   </div>
				<div class="layout-content card">
				{!! Form::open(array('route' => 'admin.addLocality', 'id' => 'addLocality','enctype' => 'multipart/form-data' , 'class' => 'col-sm-12')) !!}
                                  <div class = "row mt-2 ml-1 mr-1" >      
				                   <div class="form-group col-sm-6">
                                            <label>Locality Name</label>
                                            <textarea type="text" col="10" name="name" class="form-control" placeholder="Enter Locality Name with comma delimited"></textarea>
											<span class="help-block"></span>
                                        </div>
										<div class="form-group col-sm-6">
                                            <label>Locality Name</label>
                                            <textarea type="text" col="10" name="slug" class="form-control" placeholder="Enter Slug with comma delimited"></textarea>
											<span class="help-block"></span>
                                        </div>
                                       <div class="form-group col-sm-3">
											<label>Country</label>
											<select class="form-control country_id" name="country_id" id="country_id">
											<option value="">Select country</option>
											@foreach(getCountriesList() as $country)
												<option value="{{$country->id}}" @if($country->id == '101') selected @endif >{{$country->name}}</option>
											@endforeach
											</select>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-sm-3">
											<label>State</label>
											<select class="form-control state_id" name="state_id">
											  <option value="">Select State</option>
												@foreach (getStateList(101) as $state)
													<option value="{{ $state->id }}" >{{ $state->name }}</option>
												@endforeach
											</select>
											<span class="help-block"></span>
										</div>
										 <div class="form-group col-sm-3">
                                            <label>City</label>
                                            <select class="form-control city_id" name="city_id">
												<option value="">Select City</option>
											</select>
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
									 {!! Form::close() !!}
			    </div>
			    </div>

               </div>
        </div>
     </div>
</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>



	<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#addLocality").validate({
					rules: {
						name: "required",
						slug: "required",
						city_id: "required",
						state_id: "required",
						country_id: "required",
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
							url: "{!! route('admin.addLocality')!!}",
							data:  new FormData(form),
							contentType: false,
							cache: false,
							processData:false,
							success: function(data) {
								 if(data==1)
								 {
								  jQuery('.loading-all').hide();
								  $(form).find('.submit').attr('disabled',false);
								  document.location.href='{!! route("admin.localityMaster")!!}';
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
			
			jQuery('.country_id').on('change', function() {
			  var cid = this.value;
			  var $el = $('.state_id');
			  $el.empty();
			  jQuery.ajax({
				  url: "{!! route('getStateList') !!}",
				 // type : "POST",
				  dataType : "JSON",
				  data:{'id':cid},
				  success: function(result){ 
					jQuery("#addLocality").find("select[name='city_id']").html('<option value="">Select City</option>');
					jQuery("#addLocality").find("select[name='state_id']").html('<option value="">Select State</option>');
					jQuery.each(result,function(index, element) { 
					   $el.append(jQuery('<option>', {
						   value: element.id,
						   text : element.name
					  }));
				  });
				}}
			  );
			})
			jQuery(document).on("change", ".state_id", function (e) {
			//jQuery('.state_id').on('change', function() {
			  var cid = this.value;
			  var $el = jQuery('.city_id');
			  $el.empty();
			  jQuery.ajax({
				  url: "{!! route('getCityList') !!}",
				  // type : "POST",
				  dataType : "JSON",
				  data:{'id':cid},
				  success: function(result){
				  jQuery("#addLocality").find("select[name='city_id']").html('<option value="">Select City</option>');
				  jQuery.each(result,function(index, element) {
					  $el.append(jQuery('<option>', {
						 value: element.id,
						 text : element.name
					  }));
				  });
			  }}
			  );
			});
	</script>
@endsection		   
       

