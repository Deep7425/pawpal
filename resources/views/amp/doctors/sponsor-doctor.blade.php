<div class="modal-dialog">
  <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Update table</h4>
		</div>
			<div class="modal-body">
				<div class="panel panel-bd lobidrag">
					<div class="panel-heading">
						<div class="btn-group">
							<a class="btn btn-primary" href="javascript:location.reload();"> <i class="fa fa-list"></i>  Doctor List </a>
						</div>
					</div>
					<div class="panel-body">
					{!! Form::open(array('route' => 'admin.updateDoctor', 'id' => 'updateDoctor', 'class' => 'col-sm-12')) !!}
					<input type="hidden" name="id" value="{{@$doctor->id}}">
					<input type="hidden" name="doc_claim_type" value="{{@$doctor->member_id}}">
							<div class="col-sm-6 form-group">
								<label>First Name</label>

								<input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{@$doctor->first_name}}">
								<span class="help-block"></span>
							</div>
							<div class="col-sm-6 form-group">
								<label>Last Name</label>
								<input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{@$doctor->last_name}}">
								<span class="help-block"></span>
							</div>

							<div class="col-sm-6 form-group">
								<label>Country</label>
								<select class="form-control country_id" name="country_id" id="country_id">
								<option value="">Select country</option>
								@foreach(getCountriesList() as $country)
									<option value="{{$country->id}}"  @if(@$doctor->country_id == $country->id) selected @endif >{{$country->name}}</option>
								@endforeach
								</select>
								<span class="help-block"></span>
							</div>

							<div class="col-sm-6 form-group">
							 <label>State</label>
								 <select class="form-control state_id" name="state_id">
								  <option value="">Select State</option>
									@if(!empty($doctor->country_id))
									@foreach (getStateList($doctor->country_id) as $state)
										<option value="{{ $state->id }}" @if($doctor->state_id == $state->id) selected @endif >{{ $state->name }}</option>
									@endforeach
									@endif
								</select>
								<span class="help-block"></span>
							 </div>
							 <div class="col-sm-6 form-check">
							  <label>City</label><br>
								<select class="form-control city_id" name="city_id">
									<option value="">Select City</option>
									@if(!empty($doctor->state_id))
									@foreach (getCityList($doctor->state_id) as $city)
										<option value="{{ $city->id }}" @if($doctor->city_id == $city->id) selected @endif >{{ $city->name }}</option>
									@endforeach
									@endif
								</select>
								<span class="help-block"></span>
							  </div>
							  <div class="col-sm-12 reset-button">
                  <button type="submit" class="btn btn-success submit">Save</button>
							 </div>
						{!! Form::close() !!}
					 </div>
				 </div>
			 </div>
		</div>
	</div>
  <script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#updateDoctor").validate({
					rules: {
						first_name: "required",
						last_name: "required",
						mobile_no:{required:true,minlength:10,maxlength:12,number: true},
						country_id: "required",
						state_id: "required",
						city_id: "required",
						"speciality[]": "required",
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
							url: "{!! route('admin.updateDoctor')!!}",
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
								// document.location.href='{!! route("admin.nonHgDoctorsList")!!}';
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
					jQuery("#updateDoctor").find("select[name='city_id']").html('<option value="">Select City</option>');
					jQuery("#updateDoctor").find("select[name='state_id']").html('<option value="">Select State</option>');
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
				  jQuery("#updateDoctor").find("select[name='city_id']").html('<option value="">Select City</option>');
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
