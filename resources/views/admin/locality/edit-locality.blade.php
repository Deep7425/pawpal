<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Locality</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="">
					<div class="btn-group"> 
						<a class="btn btn-primary" href="{{ route('admin.localityMaster') }}"> <i class="fa fa-list"></i>  Locality List</a> 
					</div>
				</div>
				<div class="panel-body" style="padding-top:15px;">
					{!! Form::open(array('id' => 'updateLocality','name'=>'updateLocality', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
					<input type=hidden value="{{$locality->id}}" name="id"/>
					<div class="col-md-6">
						<label>Locality Name</label>
						<input value="{{@$locality->name}}" type="text" name="name" class="form-control" placeholder="Enter Locality Name">
						<span class="help-block"></span>
					</div>
					<div class="col-md-6">
						<label>Slug</label>
						<input value="{{@$locality->slug}}" type="text" name="slug" class="form-control" placeholder="Enter Locality Slug">
						<span class="help-block"></span>
					</div>
					<div class="col-md-6">
						<label>Country</label>
						<select class="form-control country_id" name="country_id" id="country_id">
						<option value="">Select country</option>
						@foreach(getCountriesList() as $country)
							<option value="{{$country->id}}" @if(@$locality->country_id == $country->id) selected @endif  >{{$country->name}}</option>
						@endforeach
						</select>
						<span class="help-block"></span>
					</div>
					<div class="col-md-6">
						<label>State</label>
						<select class="form-control state_id" name="state_id">
						  <option value="">Select State</option>
							@if(!empty($locality->country_id))
							@foreach (getStateList($locality->country_id) as $state)
								<option value="{{ $state->id }}" @if($locality->state_id == $state->id) selected @endif >{{ $state->name }}</option>
							@endforeach
							@endif
						</select>
						<span class="help-block"></span>
					</div>
					 <div class="col-md-6">
						<label>City</label>
						<select class="form-control city_id" name="city_id">
							<option value="">Select City</option>
							@if(!empty($locality->state_id))
							@foreach (getCityList($locality->state_id) as $city)
								<option value="{{ $city->id }}" @if($locality->city_id == $city->id) selected @endif >{{ $city->name }}</option>
							@endforeach
							@endif
						</select>
						<span class="help-block"></span>
					</div>
					<div class="col-md-6 form-check">
					  <label>Status</label><br>
					  <label class="radio-inline">
						  <input type="radio" name="status" value="1" @if(@$locality->status == '1') checked="checked" @endif>Active</label>
						  <label class="radio-inline"><input type="radio" name="status" value="0"  @if(@$locality->status == '0') checked="checked" @endif>Inctive</label>
					</div>                                       
					<div class="col-md-12">
					<div class="reset-button">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success submit">Update</button>

					   </div></div></div>
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
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script type="text/javascript">
jQuery(document).ready(function () {
$(document.body).on('click', '.submit', function(){ 
		jQuery("#updateLocality").validate({
		//  jQuery("form[name='updateLocality']").validate({	
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
					url: "{!! route('admin.updateLocality')!!}",
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
});



</script>