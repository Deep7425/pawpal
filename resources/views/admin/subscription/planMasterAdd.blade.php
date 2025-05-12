@extends('layouts.admin.Masters.Master')
@section('title', 'Add Subscription Plans')
@section('content')

<div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <div class="layout-container" style = "padding-top: 0px !important;">
                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y appointment-master subscription-master">


					<div class="panel-body panel-body_lab card">
							{!! Form::open(array('route' => 'plans.planMasterAdd', 'id' => 'addPlans', 'class' => 'col-sm-12')) !!}
								
 
                          <div class="row mt-4" >
						  <div class="form-group col-sm-3">
									<label>Plan Title:</label>
									<input type="text" class="form-control planTitle" placeholder="Enter Title Here" name="plan_title">
									<span class="help-block">
									</span>
								</div>
								<div class="form-group col-sm-3">
									<label>Slug:</label>
									<input type="text" class="form-control planSlug" placeholder="Enter Slug Here" name="slug"/>
									<span class="help-block">
									</span>
								</div>
			          
						 <div class="form-group col-sm-3">
									<label>Plan Price:</label>
									<input type="text" class="form-control" placeholder="Plan Price" name="price">
									<span class="help-block">
									</span>
								</div>
								<div class="form-group col-sm-3">
									<label>Discount Price:</label>
									<input type="text" class="form-control" placeholder="Discount Price" name="discount_price">
								</div>
						
							
						
						 <div class="form-group col-sm-3">
									<label>Plan Duration:</label>
									<input type="text" class="form-control" placeholder="Plan Duration" name="plan_duration">
									<span class="help-block">
									</span>
								</div>
								<div class="form-group col-sm-3">
									<label>Plan Duration Type:</label>
									<select class="form-control" name="plan_duration_type">
										<option value="">Select Duration Type</option>
										<option value="d">Day</option>
										<option value="m">Month</option>
										<option value="y">Year</option>
									</select>
									<span class="help-block">
									</span>
								</div>
								<div class="form-group col-sm-3">
									<label>Total Appointments Count:</label>
									<input type="text" class="form-control" placeholder="Appointments Count" name="appointment_cnt">
									<span class="help-block">
									</span>
								</div>

								<div class="form-group col-sm-3">
									<label>Specialist Appointments Count:</label>
									<input type="text" class="form-control" placeholder="Specialist Appointments Count" name="specialist_appointment_cnt"/>
									<span class="help-block">
									</span>
								</div>

						
								<div class="form-group col-sm-3">
									<label>Max Appointments Fee:</label>
									<input type="text" class="form-control" placeholder="Per Appointments Max Fee" name="max_appointment_fee">
									<span class="help-block">
									</span>
								</div>

								<div class="form-group col-sm-3">
    							<label>Lab Package Title :</label>
    							<input type="text" class="form-control" placeholder="Lab Package Title" name="lab_pkg_title" value="">
    							<span class="help-block">
    							</span>
    						</div>
							<div class="form-group col-sm-3">
    							<label>Lab Package Code:</label>
    							<input type="text" class="form-control" placeholder="Lab Package Code" name="lab_pkg" value="">
    							<span class="help-block">
    							</span>
    						</div>
						  
					    
    					
							<div class="form-group col-sm-3">
								<label>Plan Type:</label>
								<select class="form-control" name="type">
									<option value="">Select Type</option>
									<option value="1">Normal - en</option>
									<option value="2">Instant Appointment Plan - en</option>
									  <option value="3">Normal - hi</option>
									  <option value="4">Instant Appointment Plan - hi</option>
								</select>
								<span class="help-block">
								</span>
							</div>

							<div class="form-group col-sm-3">
									<label>Total Patient Count:</label>
									<input type="text" class="form-control" placeholder="Total Patient Count" name="max_patient_count"/>
									<span class="help-block">
									</span>
								</div>
								<div class="form-group col-sm-3">
									<label>For Best Plan</label>
									<select class="form-control" name="is_best">
										<option value="">Select Type</option>
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
									<span class="help-block">
									</span>
								</div>
					   
							
							
							
								<div class="form-group col-sm-12">
									<label>Content:</label>
									<textarea rows="5" cols="5" class="form-control" id="exampleContent" placeholder="Content" name="content"></textarea>
								</div>

								<div class="form-group col-sm-12 reset-button">
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



 <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script type="text/javascript">
   // When the browser is ready...
	jQuery(document).ready(function () {
    $(document.body).on('click', '.submit', function(){
		jQuery("#addPlans").validate({
	    rules: {
				plan_title: "required",
				price: "required",
				slug: "required",
				// discount_price: "required",
				plan_duration: "required",
				plan_duration_type: "required",
				max_appointment_fee: "required",
				appointment_cnt: "required",
				lab_pkg: "required",
				type: "required",
		},
	    // Specify the validation error messages
	    messages: {
				plan_title: "Please enter plan title",
				price: "Please enter plan price",
				discount_price: "Please enter discount price",
				plan_duration: "Please enter plan duration",
				plan_duration_type: "Please select plan plan type",
				appointment_cnt: "Please enter sms limits",
	    },
		    errorPlacement: function(error, element) {
		         error.appendTo(element.next());
		      },
		    submitHandler: function(form) {
		      jQuery('.loading-all').show();
		      jQuery('.submit').attr('disabled',true);
			      jQuery.ajax({
				      type: "POST",
					  url: "{!! route('plans.planMasterAdd')!!}",
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

	CKEDITOR.config.removePlugins = 'maximize';
	CKEDITOR.replace('exampleContent');
  	CKEDITOR.config.allowedContent = true;
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
@endsection
