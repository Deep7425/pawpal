	<style>
		.star-rate-div {
			float: left;
			height: 46px;
			padding: 0 10px;
		}
		.star-rate-div:not(:checked) > input {
			position:absolute;
			top:-9999px;
		}
		.star-rate-div:not(:checked) > label {
			float:right !important;
			width:1em;
			overflow:hidden;
			white-space:nowrap;
			cursor:pointer;
			font-size:30px;
			color:#ccc;
		}
		.star-rate-div:not(:checked) > label:before {
			content: '★ ';
		}
		.star-rate-div > input:checked ~ label {
			color: #ffc700;
		}
		.star-rate-div:not(:checked) > label:hover,
		.star-rate-div:not(:checked) > label:hover ~ label {
			color: #deb217;
		}
		.star-rate-div > input:checked + label:hover,
		.star-rate-div > input:checked + label:hover ~ label,
		.star-rate-div > input:checked ~ label:hover,
		.star-rate-div > input:checked ~ label:hover ~ label,
		.star-rate-div > label:hover ~ input:checked ~ label {
			color: #c59b08;
		}

</style>
	<div class="modal-dialog">
		<div class="modal-content">
		{!! Form::open(array('method' => 'POST', 'id' => 'feedback-form', 'class' => 'clinic-details')) !!}
		<input type="hidden" value="{{$doctor->id}}" name="doc_id"/>
		<input type="hidden" value="{{@Auth::id()}}" name="user_id"/>
		<input type="hidden" value="{{@$appointment_id}}" name="appointment_id"/>
		<div class="modal-header">
			<button type="button" class="close closeFeedbackModal" data-dismiss="modal">&times;</button>
		  <h2>Patient Feedback Form</h2>
		  <span>Your experience will help over many people choose the right Doctor.</span>
		</div>
		 <div class="modal-body">
				 <div class="doctor-listtop doctor-listtop2">
					<div class="doctor-listtop-img">
                        <p>
                            Dear Patient / Relative / Visitor,
                            Your continuing suggestions & feedback help to make our Hospital a better organization. Kindly spare a
                            few moments to complete the following, so that we can strive to fulfill your expectations.
                            <br>
                            

                        </p>
					</div>
                    <div class="scroller">
                    <div class="form-fields">
                    	<p style="margin: 0px; padding: 0px 0px 5px;">Warm Regards,</p>
                    	<strong>{{@$doctor->clinic_name}}</strong>
                    </div>
					<div class="form-fields form-field-mid pad-r1 form-group">
						  <label>Would you recommend this professional?<i class="required_star">*</i></label>
								<div class="recommend-field form-control">
									<input type="radio" id="radio-one" name="recommendation" value="1" checked />
									<label for="radio-one">Yes</label>
									<input type="radio" id="radio-two" name="recommendation" value="0" />
									<label for="radio-two">No</label>
								</div>
								<span class="help-block"><label for="recommendation" generated="true" class="error" style="display:none;"></label></span>




					</div>

					<div class="form-fields form-field-mid pad-r1 form-group">
					  <label>How long was the wait time in the office before you were seen?<i class="required_star">*</i></label>
						<div class="waitingTime form-control">
							<p>
						    <input type="radio" id="test1" value="1" name="waiting_time" checked>
						    <label for="test1">Less than 5 min</label>
						  </p>
						  <p>
						    <input type="radio" id="test2" value="2" name="waiting_time">
						    <label for="test2">5 min to 10 min</label>
						  </p>
						  <p>
						    <input type="radio" id="test3" value="3" name="waiting_time">
						    <label for="test3">10 min to 30 min</label>
						  </p>
						 <p>
							<input type="radio" id="test4" value="4" name="waiting_time">
							<label for="test4">30 min to 1 hour</label>
						</p>
						<p>
						 <input type="radio" id="test5" value="5" name="waiting_time">
						 <label for="test5">More than 1 hour</label>
					 </p>
					 	<span class="help-block"></span>
						</div>


					 	<div class="tooltip"><i class="fa fa-question-circle-o" aria-hidden="true"></i>
							<span class="tooltiptext">5 Stars : Right Away
								<br>4 Stars : Less than 30 minutes</br>3 Stars : Between 30 and 60 minutes</br>2 Stars : Over an hour
								</br>1 Stars : Over 2 hours!
							</span>
						</div>
					</div>

					<div class="form-fields form-field-mid pad-r1 form-group">
					  <label>Reason to visit?<i class="required_star">*</i></label>
						<div class="form-control">
							<select name="visit_type">
								<option value="">Select Reason</option>
								<option value="1" >Consultation</option>
								<option value="2">Procedure</option>
								<option value="3">Follow up</option>
							</select>
							<span class="help-block"></span>
						</div>
					</div>

					<div class="form-fields form-field-mid pad-r1 form-group">
					  <label>Compliment</label>
						<div class="checkbox-wrapper">
							<div class="form-control complimentCheckBox">
								<input type="checkbox" id="comple1" name="suggestions[]" value="1">
								<label for="comple1">Quality of Medical Care</label>

								<input type="checkbox" id="comple2" name="suggestions[]" value="2">
								<label for="comple2">Staff Assistance/ Support</label>

								<input type="checkbox" id="comple3" name="suggestions[]" value="3">
								<label for="comple3">Caring & Compassionate</label>

								<input type="checkbox" id="comple4" name="suggestions[]" value="4">
								<label for="comple4">Outstanding Customer Service</label>

								<input type="checkbox" id="comple5" name="suggestions[]" value="5">
								<label for="comple5">Timely Problem/ Issue Resolution</label>

								<input type="checkbox" id="comple6" name="suggestions[]" value="6">
								<label for="comple6">Superior Facilities</label>
							</div>

						</div>
					</div>


					<div class="form-fields form-field-mid pad-r1 form-group">
					  <label>How would you rate this professional’s bedside manner?<i class="required_star">*</i></label>
					  <div class="star-rate-div form-control">
						<input type="radio" id="rating5" name="rating" value="5" checked />
						<label for="rating5" title="5 star"></label>
						<input type="radio" id="rating4" name="rating" value="4" />
						<label for="rating4" title="4 star"></label>
						<input type="radio" id="rating3" name="rating" value="3" />
						<label for="rating3" title="3 star"></label>
						<input type="radio" id="rating2" name="rating" value="2" />
						<label for="rating2" title="2 star"></label>
						<input type="radio" id="rating1" name="rating" value="1" />
						<label for="rating1" title="1 star"></label>
					  </div>

					 	<div class="tooltip"><i class="fa fa-question-circle-o" aria-hidden="true"></i>
							<span class="tooltiptext">5 Stars : Excellent
								<br>4 Stars : Good</br>3 Stars : Satisfactory</br>2 Stars : Unsatisfactory
								</br>1 Stars : Awful!
							</span>
						</div>
					  <span class="help-block"></span>
					</div>

					<div class="form-fields form-field-mid pad-r1 form-group">
					  <label>What did you think about your visit?<i class="required_star">*</i></label>
					  <div class="form-control">
						<textarea name="experience" class="" placeholder="Experience" cols="10" ></textarea>
						<span class="help-block"></span>
					  </div>
					  <span class="help-block"></span>
					</div>

					<div class="form-fields form-field-mid pad-r1 form-group">
					  <div class="form-control">
						<label><input style="margin-top: 3px;" type="checkbox" name="publish_status" value="1"/>Make it publicly unidentified.</label>
						<p><strong>Note:</strong> Doctor can access identity, if required.</p>
					  </div>
					  <span class="help-block"></span>
					</div>

				 </div>
				<div class="slotsMainDiv tab-content"></div>
		  </div>
          </div>
		  <div class="modal-footer">
			<button name="submit" type="submit" class="btn btn-default feedback-form-submit">Save</button>
			<button type="reset" class="btn btn-default">Clear</button>
			<button name="clear" type="button" class="btn btn-default closeFeedbackModal" data-dismiss="modal">Cancel</button>
		  </div>
		 {!! Form::close() !!}
		</div>
	</div>
<script>
	jQuery(document).ready(function(){
          jQuery("#feedback-form").validate({
         rules: {
                recommendation: "required",
                experience: "required",
                visit_type: "required",
         },
        messages:{
        },
        errorPlacement: function(error, element){
			 error.appendTo(element.next());
		},ignore: ":hidden",
        submitHandler: function(form) {
			jQuery('.loading-all').show();
			jQuery('.feedback-form-submit').attr('disabled',true);
			jQuery.ajax({
			type: "POST",
			dataType : "JSON",
			url: "{!! route('patients.saveFeedbackForm')!!}",
			data:  new FormData(form),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data) {
				 if(data==1) {
				  jQuery('.loading-all').hide();
				  jQuery("#addLabMaster").trigger('reset');
				  jQuery('.feedback-form-submit').attr('disabled',false);
				  jQuery('#patientFeedBackForm').modal('hide');
				  location.reload();
				 }
				 else {
				  jQuery('.loading-all').hide();
				  jQuery('.feedback-form-submit').attr('disabled',false);
				  alert("System Problem");
				 }
			   }
			 });
          }
      });
  });
$(document).on("click", ".closeFeedbackModal", function (e) {
	var appointment_id = $('#feedback-form input[name="appointment_id"]').val();
	if (appointment_id != "") {
	  	jQuery.ajax({
		type: "POST",
		dataType : "JSON",
		url: "{!! route('patients.saveFeedbackForm')!!}",
		data:{'closeModal':'1'},
		success: function(data) {
			 if(data != 0) {
			 	jQuery('.loading-all').hide();
			  	alert("System Problem");
			 }
		   }
		 });
	}
});

</script>
