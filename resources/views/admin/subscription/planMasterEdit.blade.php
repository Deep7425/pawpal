<div class="modal-dialog modal-dialog111">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Plan Details</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('plans.planMaster') }}"> <i class="fa fa-list"></i>  Plan List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'planEdit','name'=>'updatePlansMaster')) !!}
					<div class="row">
					<input type=hidden value="{{$plan->id}}" name="id"/>
						<div class="form-group col-sm-6">
							<label>Plan Title:</label>
							<input type="text" class="form-control planTitle" placeholder="Enter Title Here" name="plan_title" value="{{$plan->plan_title}}">
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>Slug:</label>
							<input type="text" class="form-control planSlug" placeholder="Enter Slug Here" name="slug" value="{{$plan->slug}}"  >
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>Plan Price:</label>
							<input type="text" class="form-control" placeholder="Plan Price" name="price" value="{{$plan->price}}">
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>Discount Price:</label>
							<input type="text" class="form-control" placeholder="Discount Price" name="discount_price" value="{{$plan->discount_price}}">
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-3">
							<label>Plan Duration:</label>
							<input type="text" class="form-control" placeholder="Plan Duration" name="plan_duration" value="{{$plan->plan_duration}}">
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-3">
							<label>Type:</label>
							<select class="form-control" name="plan_duration_type">
								<option value="">Select Duration Type</option>
								<option value="d" @if($plan->plan_duration_type == "d") selected @endif>Day</option>
								<option value="m" @if($plan->plan_duration_type == "m") selected @endif>Month</option>
								<option value="y" @if($plan->plan_duration_type == "y") selected @endif>Year</option>
							</select>
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>Total Appointments Count:</label>
							<input type="text" class="form-control" placeholder="Appointments Count" name="appointment_cnt" value="{{$plan->appointment_cnt}}">
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>Specialist Appointments Count:</label>
							<input type="text" class="form-control" placeholder="Specialist Appointments Count" name="specialist_appointment_cnt"  value="{{$plan->specialist_appointment_cnt}}"/>
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>Max Appointments Fee:</label>
							<input type="text" class="form-control" placeholder="Per Appointments Max Fee" name="max_appointment_fee" value="{{$plan->max_appointment_fee}}">
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>Lab Package Title :</label>
							<input type="text" class="form-control" placeholder="Lab Package Title" name="lab_pkg_title" value="{{$plan->lab_pkg_title}}">
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>Lab Package Code:</label>
							<input type="text" class="form-control" placeholder="Lab Package Code" name="lab_pkg" value="{{$plan->lab_pkg}}">
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>Plan Type:</label>
							<select class="form-control" name="type">
								<option value="">Select Type</option>
								<option value="1" @if($plan->type == "1") selected @endif>Normal - en</option>
								<option value="2" @if($plan->type == "2") selected @endif>Instant Appointment Plan - en</option>
                <option value="3" @if($plan->type == "3") selected @endif>Normal - hi</option>
                <option value="4" @if($plan->type == "4") selected @endif>Instant Appointment Plan - hi</option>
							</select>
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
							<label>Total Patient Count:</label>
							<input type="text" class="form-control" placeholder="Total Patient Count" name="max_patient_count" value="{{$plan->max_patient_count}}">
							<span class="help-block">
							</span>
						</div>
						<div class="form-group col-sm-6">
								<label>For Best Plan</label>
								<select class="form-control" name="is_best">
									<option value="">Select Type</option>
									<option value="1" @if($plan->is_best == "1") selected @endif>Yes</option>
									<option value="0" @if($plan->is_best == "0") selected @endif>No</option>
								</select>
								<span class="help-block">
								</span>
							</div>
						<div class="form-group col-sm-12">
							<label>Content:</label>
							<textarea rows="5" cols="5" class="form-control" id="exampleContent" placeholder="Content" name="content" value="{{$plan->content}}">{{$plan->content}}</textarea>
						</div>
						<div class="col-md-12">
							<div class="reset-button">
							   <button type="reset" class="btn btn-warning">Reset</button>
							   <button type="submit" class="btn btn-success submit">Update</button>
							</div>
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







<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 
    <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
	<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script type="text/javascript">
// When the browser is ready...

	jQuery(document).ready(function () {
$(document.body).on('click', '.submit', function(){

		jQuery("#planEdit").validate({
		rules: {
				plan_title: "required",
				price: "required",
				slug: "required",
				max_appointment_fee: "required",
				plan_duration: "required",
				plan_duration_type: "required",
				promotional_sms_limit: "required",
				type: "required",
		},
		// Specify the validation error messages
		messages: {
				plan_title: "Please enter plan title",
				plan_type: "Please select plan type",
				price: "Please enter plan price",
				discount_price: "Please enter discount price",
				plan_duration: "Please enter plan duration",
				plan_duration_type: "Please select plan plan type",
				promotional_sms_limit: "Please enter sms limits",
		},
			errorPlacement: function(error, element) {
					 error.appendTo(element.next());
				},
			submitHandler: function(form) {
				jQuery('.loading-all').show();
				jQuery('.submit').attr('disabled',true);
					jQuery.ajax({
						type: "POST",
            dataType : "JSON",
						url: "{!! route('plans.updatePlansMaster')!!}",
						data:  new FormData(form),
						contentType: false,
						cache: false,
						processData:false,
						success: function(data) {
							 if(data==1) {
								jQuery('.submit').attr('disabled',false);
								document.location.href = "{!! route('plans.planMaster')!!}";
							 }
							 else {
								alert("System Problem");
							 }
							 jQuery('.submit').attr('disabled',false);
							 jQuery('.loading-all').hide();
						 },
						 error: function(error){
							 jQuery('.submit').attr('disabled',false);
							 jQuery('.loading-all').hide();
							 alert("Oops Something goes Wrong.");
						 }
				});
			}
		});
  });
	});

	</script>

  <script>
  	CKEDITOR.config.removePlugins = 'maximize';
  	CKEDITOR.config.allowedContent = true;
  	CKEDITOR.replace('exampleContent');
  	CKEDITOR.on('instanceReady', function () {
  	$.each(CKEDITOR.instances, function (instance) {
  			CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
  			CKEDITOR.instances[instance].document.on("paste", CK_jQ);
  			CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
  			CKEDITOR.instances[instance].document.on("blur", CK_jQ);
  			CKEDITOR.instances[instance].document.on("change", CK_jQ);
  		});
  	});

  	function CK_jQ() {
  		for (instance in CKEDITOR.instances) {
  			CKEDITOR.instances[instance].updateElement();
  		}
  	}
	// jQuery(document).on("keyup", ".planTitle", function() {
        // var str = this.value;
        // str = str.replace(/[^a-zA-Z0-9\s]/g, "");
        // str = str.toLowerCase();
        // str = str.replace(/\s/g, '-');
        // $('.planSlug').val(str);
    // });
</script>