<?php $year = range(1950,date("Y")); ?>
<div class="modal-dialog modal-lg">
<!-- Modal content-->
<div class="modal-content ">
	<div class="modal-header">
	<button type="button" class="close editModalClose" data-dismiss="modal">×</button>
	<h4 class="modal-title">Update Table</h4>
	</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group">
						<a class="btn btn-primary" href="javascript:location.reload();"> <i class="fa fa-list"></i>  Doctor List </a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('route' => 'admin.updateDoctor', 'id' => 'updateDoctor', 'class' => 'col-sm-12','enctype' => 'multipart/form-data')) !!}
					<div class="row">
						<input type="hidden" name="clinic_id"/>
						<input type="hidden" name="id" value="{{@$doctor->id}}">
						<input type="hidden" name="user_id" value="{{@$doctor->user_id}}">
						<input type="hidden" name="type" value="{{$type}}">
						<input type="hidden" name="doc_claim_type" value="{{@$doctor->member_id}}">
						<div class="col-sm-3 form-group CounselorTop">
							<label>First Name</label>
							<select class="form-control" name="doc_type">
								<option value="">Select</option>
								<option value="0" @if(@$doctor->doc_type == 0) selected @endif>Dr.</option>
								<option value="1" @if(@$doctor->doc_type == 1) selected @endif>Counselor</option>
							  </select>
							<input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{@$doctor->first_name}}">
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Last Name</label>
							<input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{@$doctor->last_name}}">
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Name (HINDI)</label>

							<input type="text" class="form-control" name="name" placeholder="Name In Hindi" value="{{@$doctor->name}}">
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group gender">
							<label>Gender</label>
							<p class="GenderTpo"><label><input type="radio" value="Male" name="gender" @if(@$doctor->gender == 'Male') checked="checked" @endif />Male</label>
							<label><input type="radio" value="Female" name="gender" @if(@$doctor->gender == 'Female') checked="checked" @endif/>Female</label></p>
							<span class="help-block">
							</span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Email</label>
							<input doc_id="{{@$doctor->id}}" v_type="0" @if($type == "live" || $type == "hg") readonly @endif type="email" class="form-control verifyDocData" placeholder="Enter Email" aria-describedby="emailHelp"  name="email" value="{{@$doctor->email}}" />
							<span class="help-block"></span>
						</div>

						<div class="col-sm-3 form-group">
							<label>Clinic Email</label>
							<input type="email" class="form-control" placeholder="Clinic Email" aria-describedby="emailHelp" name="clinic_email" value="{{@$doctor->clinic_email}}" />
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Doctor Registration No.</label>
							<input type="text" class="form-control" name="reg_no" placeholder="Doctor Registration No." value="{{@$doctor->reg_no}}">
							<span class="help-block"></span>
						</div>



		  <div class="col-sm-3 form-group">
			<label>Registration Year<i class="required_star">*</i></label>
			  <select name="reg_year" class="form-control">
					<option value="">Select Registration Year</option>
					@foreach($year as $raw)
					<option @if($doctor->reg_year == $raw) selected @endif value="{{$raw}}">{{$raw}}</option>
					@endforeach
			  </select>
			<span class="help-block"></span>
		  </div>
		  <div class="col-sm-3 form-group select_reg_council_div">
			<label>Registered Council<i class="required_star">*</i></label>
			<select name="reg_council" class="form-control multiSelect select_reg_council">
			<option value="">Select Registered Council</option>
			@foreach (getCouncilingData() as $council)
			<option value="{{ $council->id }}" @if($council->id == $doctor->reg_council) selected @endif>{{ $council->council_name }}</option>
			@endforeach
			</select>
			<span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label></span>
		  </div>
		  <div class="col-sm-3 form-group">
			<label>Last Obtained Degree</label>
			<input type="text" class="form-control" name="last_obtained_degree" placeholder="Last Obtained Degree" value="{{@$doctor->last_obtained_degree}}" autocomplete="off" />
			<span class="help-block"></span>
		  </div>
		  <div class="col-sm-3 form-group">
			<label>Degree Year</label>
			  <select name="degree_year" class="form-control">
					<option value="">Select Degree Year</option>
					@foreach($year as $raw)
					<option @if($doctor->degree_year == $raw) selected @endif value="{{$raw}}">{{$raw}}</option>
					@endforeach
			 </select>
			<span class="help-block"></span>
		  </div>

		  <div class="col-sm-3 form-group university_div">
			<label>College/University</label>
			<select name="university" class="form-control multiSelect select_university">
			<option value="">Select College/University</option>
			@foreach (getUniversityList() as $university)
			<option value="{{ $university->id }}" @if($university->id == $doctor->university) selected @endif>{{$university->name}}</option>
			@endforeach

			</select>
			<span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label></span>
		  </div>

						<div class="col-sm-3 form-group">
							<label>Contact No.</label>
							<input v_type="1" doc_id="{{@$doctor->id}}" type="text" class="form-control verifyDocData" name="mobile_no" placeholder="Contact No." value="{{@$doctor->mobile_no}}"/>
							<span class="help-block"></span>
						</div>

						<div class="col-sm-3 form-group">
							<label>Clinic Mobile</label>
							<input type="text" class="form-control" name="clinic_mobile" placeholder="Contact No." value="{{@$doctor->clinic_mobile}}"/>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Doctor Spaciality</label>
							<select class="form-control multiSelect" id="speciality" name="speciality[]">
							  <option value="" selected>Select Speciality</option>
							  @foreach (getSpecialityList() as $specialities)
							  <option value="{{ $specialities->id }}" @if(@$doctor->speciality == $specialities->id) selected @endif>{{ $specialities->specialities }}</option>
							  @endforeach
							</select>
							<span class="help-block"></span>
						</div>
<!-- 
						<div class="col-sm-4 form-group">
							<label>Clinic Name</label>
							<input type="text" class="form-control" name="clinic_name" placeholder="Clinic Name" value="{{@$doctor->clinic_name}}">
							<span class="help-block"></span>
						</div> -->
						<div class="col-sm-3 form-group">
							<label>Experience</label>
							<input type="text" class="form-control" name="experience" placeholder="Experience" value="{{@$doctor->experience}}"/>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Qualification</label>
							<input type="text" class="form-control" name="qualification" placeholder="Qualification" value="{{@$doctor->qualification}}">
							<span class="help-block"></span>
						</div>

						<div class="col-sm-3 form-group">
							<label >Recommend</label>
							<input type="text" class="form-control" name="recommend" placeholder="recommend" value="{{@$doctor->recommend}}" />
							<span class="help-block"></span>
						</div>
						<!--<div class="col-sm-4 form-group">
							<label>Cunsultation Fee</label>
							<input type="text" class="form-control" name="consultation_fees" placeholder="Cunsultation Fee" value="{{@$doctor->consultation_fees}}" />
							<span class="help-block"></span>
						</div>-->
						<!--<div class="col-sm-6 form-group">
							<label>Discounted Cunsultation Fee</label>
							<input type="text" class="form-control" name="consultation_discount" placeholder="Discounted Cunsultation Fee" value="{{@$doctor->consultation_discount}}" />
							<span class="help-block"></span>
						</div>-->
						<div class="col-sm-3 form-group">
							<label>Clinic Speciality</label>
							<select class="form-control multiSelect clinic_speciality" id="clinic_speciality" name="clinic_speciality">
							  <option value="" selected>Select Speciality</option>
							  @foreach (getSpecialityList() as $specialities)
							  <option value="{{$specialities->id }}" @if(@$doctor->clinic_speciality == $specialities->id) selected @endif>{{$specialities->specialities}}</option>
							  @endforeach
							</select>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Follow Up Count</label>
							<input type="text" class="form-control" name="followup_count" placeholder="Follow Up Count" value="{{@$doctor->DoctorData->followup_count}}" />
							<span class="help-block"></span>
						</div>
				


						
						<div class="AddDoctorHeading"><h2>Clinic Details</h2></div>
							<div class="col-sm-3 form-group">
								<label>Clinic Name<i class="required_star">*</i></label>
								<input type="text" class="form-control clinic_nameBySearech" name="clinic_name" placeholder="Clinic Name" value="{{@$doctor->clinic_name}}"/>
								<span class="help-block"></span>
								<i class="btn-reset-clinic" style="display:none;"><button type="button">Reset</button></i>
							   <div class="suggesstion-box" style="display:none;"></div>
							</div>
							<div class="col-sm-3 form-group form-fields typeField">
								<label>Type<i class="required_star">*</i></label>
								<div class="radio-wrap clinicRadio">
								<input type="radio"  @if($doctor->practice_type==1) checked="checked" @endif name="practice_type" id="clinic" value="1"/>
								<label for="clinic">Clinic</label>
								</div>
								<div class="radio-wrap hospitalRadio">
								<input type="radio" name="practice_type" @if($doctor->practice_type==2) checked="checked" @endif id="hospital" value="2" />
								<label for="hospital">Hospital</label>
								</div>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-3 form-group">
								<label>Clinic Mobile</label>
								<input type="text" class="form-control NumericFeild" name="clinic_mobile" placeholder="Contact No." value="{{$doctor->clinic_mobile}}"/>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-3 form-group">
								<label>Clinic Email</label>
								<input type="email" class="form-control" placeholder="Clinic Email" aria-describedby="emailHelp" name="clinic_email" value="{{$doctor->clinic_email}}" />
								<span class="help-block"></span>
							</div>

							<div class="col-sm-3 form-group">
								<label >Recommend</label>
								<input type="text" class="form-control" name="recommend" placeholder="recommend" value="{{$doctor->recommend}}" />
								<span class="help-block"></span>
							</div>
							<div class="col-sm-3 form-group">
								<label>Clinic Speciality<i class="required_star">*</i></label>
								<select class="form-control multiSelect" id="clinic_speciality" name="clinic_speciality">
								  <option value="" selected>Select Speciality</option>
								  @foreach (getSpecialityList() as $specialities)
								  <option value="{{ $specialities->id }}" @if($doctor->clinic_speciality==$specialities->id) selected @endif>{{ $specialities->specialities }}</option>
								  @endforeach
								</select>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-3 form-group">
								<label>Website</label>
								<input type="text" class="form-control" name="website" placeholder="website" value="{{$doctor->website}}" />
								<span class="help-block"></span>
							</div>
							<div class="col-sm-3 form-group">
								<label>Follow Up Count</label>
								<input type="text" class="form-control" name="followup_count" placeholder="Follow Up Count" value=""/>
								<span class="help-block"></span>
							</div>
							
								<div class="col-sm-12 form-group">
									<label>Address</label>
									<textarea class="form-control" rows="5" name="address_1" value="">{{$doctor->address_1}}</textarea>
									<span class="help-block"></span>
								</div>
								
									<div class="col-sm-3 form-group">
									<label>Country<i class="required_star">*</i></label>
									<select class="form-control country_id multiSelect" name="country_id" id="country_id">
									<option value="">Select country</option>
									@foreach(getCountriesList() as $country)
										<option value="{{$country->id}}" @if($doctor->country_id==$country->id) selected @endif>{{$country->name}}</option>
									@endforeach
									</select>
									<span class="help-block"></span>
									</div>

							<div class="col-sm-3 form-group">
							 <label>State<i class="required_star">*</i></label>
							     <?php  $stateName=getStateName($doctor->city_id); ?>
							 	 <select class="form-control state_id multiSelect" name="state_id">
									@if($doctor->state_id)
								  <option value="{{$doctor->state_id}}">{{$stateName}}</option>
								    @endif
								  <option value="">Select State</option>
								  <!-- <option value="">Select State</option> -->
								  
								</select>
								<span class="help-block"></span>
							 </div>
							 <div class="col-sm-3 form-group">
							  <label>City<i class="required_star">*</i></label><br>
								<select class="form-control city_id multiSelect" name="city_id">
								<?php  $cityName=getCityName($doctor->city_id); ?>
								    @if($doctor->city_id)
									<option value="{{$doctor->city_id}}">{{$cityName}}</option>
									@endif
									<option value="">Select City</option>
								</select>
								<span class="help-block"></span>
							  </div>
							   <div class="col-sm-3 form-group">
							  <label>Locality</label><br>
								<select class="form-control locality_id multiSelect" name="locality_id">
								
								<?php  $Locality=getLocalityName($doctor->locality_id); ?>
										@if($doctor->locality_id)
										<option value="{{$doctor->locality_id}}">{{$Locality}}</option>
										@endif
									   <option value="">Select Locality</option>
								</select>
								<span class="help-block"></span>
							  </div>
							  <div class="col-sm-3 form-group">
								<label>Zipcode</label><br>
								<input type="text" class="form-control NumericFeild" name="zipcode" placeholder="Zipcode" value="{{$doctor->zipcode}}"/>
								<span class="help-block"></span>
							  </div>
							  <div class="col-sm-3 form-group">
								<label>Servtel API KEY</label><br>
								<input type="text" class="form-control" name="servtel_api_key" placeholder="Servtel API KEY" value="{{$doctor->servtel_api_key}}" />
								<span class="help-block"></span>
							  </div>
							  
							

						<div class="col-md-12">
							<button type="button" name="button" class="form-control" id="addAddress">Add Address</button>
						</div>
						<div class="col-md-12">
							<div class="row AlternateAddress">
								<?php
									$alternate_address = json_decode(@$doctor->DoctorData->alternate_address);
								?>
								@if(!empty(@$doctor->DoctorData->alternate_address) && count($alternate_address) > 0)
								@foreach ($alternate_address as $add)
									<div class="col-md-4 form-group">
									  <label>Alternate Address</label><br>
									  <textarea class="form-control" rows="2" name="alternate_address[]" value="{{$add}}">{{$add}}</textarea>
									  <span class="help-block"></span>
										<div class="closeAddress"><i class="fa fa-times" aria-hidden="true"></i></div>
									</div>
								@endforeach
								@endif
							</div>
						</div>
						
						<div class="col-sm-6 form-group">
							<label>About For Doctor</label><br>
							<textarea class="form-control" rows="5" id="About" name="content" value="{{@$doctor->content}}">{{@$doctor->content}}</textarea>
							<span class="help-block"></span>
						  </div>
						  <div class="col-sm-6 form-group">
							<label>Note</label><br>
							<textarea class="form-control" rows="5" name="note" value="{{@$doctor->note}}">{{@$doctor->note}}</textarea>
							<span class="help-block"></span>
						  </div>
						  

						  <div class="col-sm-3 radio-wrap">
							<label>Health Gennie Interested</label>
							<p>
								<label><input type="radio" value="1" name="hg_interested" @if(@$doctor->hg_interested == '1') checked="checked" @endif />Yes</label>
								<label><input type="radio" value="0" name="hg_interested" @if(@$doctor->hg_interested == '0') checked="checked" @endif/>No</label>
							</p>
								<span class="help-block"></span>
						 </div>
			   				<div class="col-sm-3 radio-wrap">
						  		<label>In-clinic Fees Show</label><br>
								<p class="GenderTpo">
			  						<label><input type="radio" name="fees_show" value="1" @if(@$doctor->fees_show == '1') checked="checked" @endif />Yes</label>
									<label><input type="radio" name="fees_show" value="0" @if(@$doctor->fees_show == '0') checked="checked" @endif/>No</label>
								</p>
							<span class="help-block"></span>
						 </div>
						 
						<div class="col-sm-3 radio-wrap">
						  	<label>Consultation Type<i class="required_star">*</i></label>
								<?php $types = []; if(!empty($doctor->oncall_status)){ $types = explode(',',$doctor->oncall_status); } ?>
								<div class="ConsultationType">
									<div class="Consultation11">
										<label class="radio-inline"><input type="checkbox" class="oncall_status" name="oncall_status[]" value="2" @if(in_array(2,$types)) checked @endif>In Clinic</label>
										<p class="inclinic_fees"  @if(in_array(2,$types)) style="display:block;" @else style="display:none;" @endif>
											<input class="form-control TopNavnumericFeild" placeholder="Fees" type="text" name="consultation_fees" value="@if(!empty($doctor->consultation_fees)){{$doctor->consultation_fees}}@endif">
											<span class="help-block"></span>
										</p>
									</div>
							<div class="Consultation11">
								<label class="radio-inline"><input type="checkbox" class="oncall_status" name="oncall_status[]" value="1" @if(in_array(1,$types)) checked="checked" @endif />Tele</label>
								<p class="oncall_fee oncall_fee123" @if(in_array(1,$types)) style="display:block;" @else style="display:none;" @endif>
									<input class="form-control TopNavnumericFeild" placeholder="Fees" type="text" name="oncall_fee" value='{{$doctor->oncall_fee}}' />
									<span class="help-block"></span>
								</p>
						  	</div>
						    <span id="oncall_status" class="help-block testtop"></span>
						  </div>
						</div>

						<div class="col-sm-3 form-group">
							<label>Plan Consult Fees</label>
							<input type="text" class="form-control" name="plan_consult_fee" placeholder="Plan Consult Fees" value="{{@$doctor->DoctorData->plan_consult_fee}}" />
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Convenience Fee</label><br>
							<input type="text" class="form-control" name="convenience_fee" placeholder="Convenience Fee" value="{{@$doctor->convenience_fee}}"/>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Account Number</label><br>
							<input type="text" class="form-control" name="acc_no" placeholder="Account Number" value="{{@$doctor->acc_no}}"/>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							  <label>Account Name</label>
							  <input class="form-control" placeholder="Enter Account Name" type="text" name="acc_name" value="{{@$doctor->acc_name}}"/>
							  <span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>IFSC Code</label><br>
							<input type="text" class="form-control" name="ifsc_no" placeholder="IFSC Code" value="{{@$doctor->ifsc_no}}"/>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Bank Name</label><br>
							<input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="{{@$doctor->bank_name}}"/>
							<span class="help-block"></span>
						</div>
						<div class="col-sm-3 form-group">
							<label>Paytm Number</label><br>
							<input type="text" class="form-control" name="paytm_no" placeholder="Paytm Number" value="{{@$doctor->paytm_no}}"/>
							<span class="help-block"></span>
						</div>
						
							<div class="col-sm-6">
								<label>For Plan User</label>
								
								<div class="form-group" style="width:100%;"><label class="radio-inline"><input type="checkbox" name="is_subcribed_user" value="1" @if(in_array($doctor->user_id,getSetting("specialist_doctor_user_ids"))) checked @endif />Mark As Plan Subscribed User For Appointment</label>
								</div>
							</div>
						
							@if($type == "non_hg")
							<div class="col-sm-3">
							  <label>For Non Hg Doctor</label>
								<div class="">
									<label class="radio-inline ClassTopSection"><input type="checkbox" name="is_complete" value="1" /><span>Mark As Complete Profile, After show in claim list</span></label>
							  </div>
							</div>
							@endif
						
							
							
								<div class="col-md-3 form-group ">
									@if(!empty($doctor->profile_pic))
									<label>Exist profile image</label>
										<?php $image_url = getPath("public/doctor/ProfilePics/".$doctor->profile_pic);?>
										<p style="height:36px; margin:0px;"><img src="<?php echo $image_url;?>" class="img-responsive" alt="Blog Image" width="60" style="text-align:center;"/></p>
								
										@else
                                  
										<label>Exist profile image</label>
										
										<p style="height:36px; margin:0px;"><img src="{{ URL::asset('images/no-image.jpg') }}" class="img-responsive" alt="Blog Image" width="60" style="text-align:center;"/></p>
								

									@endif
						

								</div>
								<div class="col-md-3 form-group DoctorImage">
									<label>Doctor image</label><br>
									<input value="{{@$doctor->profile_pic}}"  type="hidden" name="old_profile_pic" class="form-control"/>
									<input type="file" class="form-control" id="profile_pic" name="profile_pic" placeholder="profile_pic" />
									<span class="help-block"></span>
								</div>
								<div class="col-md-3 form-group DoctorImage">
							<label>Clinic Image</label><br>
							<input value="{{@$doctor->clinic_image}}" type="hidden" name="old_clinic_image" class="form-control"/>
							<input type="file" id="clinic_image" class="form-control" name="clinic_image" placeholder="clinic_image" />
							<span class="help-block"></span>
						  </div>

						 
							<div class="col-md-3 form-group DoctorImage exist-clinic-image">
							@if(!empty($doctor->clinic_image))
							<label>Exist clinic image</label>
								<?php $clinic_image = getPath("public/doctor/".$doctor->clinic_image);?>
								<p><img src="<?php echo $clinic_image;?>" class="img-responsive" alt="Blog Image" height="50" width="100" style="text-align:center;"/></p>
							@endif
							</div>
						  

							

						

							
							
							<div class="col-md-3 form-group DoctorImage">
							@if(!empty($doctorInfo->doctor_sign))
							<label>Doctor Signature</label>
								<?php $doctor_signature = getPath("public/doctor/signature/".$doctorInfo->doctor_sign);?>
								<p><img src="<?php echo $doctor_signature;?>" class="img-responsive" alt="Blog Image" height="50" width="100" style="text-align:center;"/></p>
							@endif
							</div>
						  <div class="col-md-3 form-group DoctorImage">
							<div class="signatureDivoho">
								<input name="sign_view" id="sign_view" type="checkbox" value="1" @if(!empty($doctorInfo->doctor_sign)) {{ $doctorInfo->sign_view == '1' ? 'checked' : '' }} @else disabled @endif>
                                <label>Use this signature on clinical note</label>
								<input value="{{@$doctor->doctor_signature}}" type="hidden" name="old_signature_image" class="form-control"/>
								<input type="file" id="image-file" class="form-control" name="doctor_signature" placeholder="signature_image" />
								<span class="help-block"></span>
							  </div>
							 
							
						 

							</div>
							<div class="documents-section">
							  <label class="DocumentsSection">Documents Section</label>
							 <div class="uploadSection">
								<div class="col-md-12">
								  <ul class="documets-type documets-type123" id="documetTypes">
								  @foreach (getDoctorDocumentType() as $key => $type)
									@if($key != 1 && $key != 2)
									<li class="@if($key == 3) active @endif" data-id="{{$key}}">{{$type}}</li>
									@endif
								  @endforeach
								  </ul>
								  <span class="help-block"></span>
								</div>
								<div class="FileSizeClass col-md-12"><span>Upload Section</span></div>
								<div class="col-md-12">
								  <div class="dropzone">
									<div class="dz-message needsclick">
									  <strong>Drop /select Image.</strong>
									</div>
								  </div>
								</div>
							  </div>
							  </div>
							 <div class="col-md-12">
								<div class="userDocuments">
								  @if(count(@$doctor->DoctorDocuments) > 0)
								  @foreach (@$doctor->DoctorDocuments as $row)
								  <?php $extension = pathinfo($row->file_name, PATHINFO_EXTENSION);
									$img = url("/")."/public/doctorDocuments/".@$row->file_name;
									$path = url("/")."/public/doctorDocuments/".@$row->file_name;
									if ($extension == 'pdf') {
									  $img = URL::asset('img/pdf.png');
									}
								  ?>
									<div class="image-block {{getDocTypeClass($row->type)}}">
									  <span class="removeImage" data-id="{{$row->id}}"><i class="fa fa-times" aria-hidden="true"></i></span>
									  <img src="{{$img}}" alt="" class="openFile" filename="{{$path}}" width="100"/>
									  <span class="typeName">{{getDoctorDocumentType($row->type)}}</span>
									  @if($row->type == 1)
									  <!--<div class="profile_marked">
										<label class="container-checkbox"><input type="checkbox" class="profileMark" name="profile_marked[]" data-id="{{$row->id}}" value="1" @if($row->profile_marked == 1) checked disabled @endif/>{{__('page.reg_make_profile')}}<span class="checkmark"></span></label>
									  </div>-->
									  @endif
									</div>
								  @endforeach
								  @endif
								</div>

						  
			  <div class="opd_timing">
				<?php
					$appt_durations = getAppoimentDurations($doctor->practice_id);
					$increment = 900;
					$day_in_increments = range( 0, (86400 - $increment), $increment);
				 ?>
				<div id="opd_timing_tab" class="opd_timing_tab tab-pane txt-center">
					<div class="registration-wrap doc-register">
			  <div class="checkbox-div complete-str">
				<h3 class="checkbox-divOpd">Opd Timings Schedule</h3>
				<div class="opd-timings-slot">
				  <label>Appointment Duration</label>
				  <select name="slot_duration" class="slots-data">
					<option value="">Select</option>
					@foreach($appt_durations as $index => $duration)
						<option value="{{$duration->time}}" @if($doctor->slot_duration == $duration->time) selected="selected" @endif>{{$duration->title}}</option>
					@endforeach
					<!--<option value="3" @if($doctor->slot_duration == 3) selected="selected" @endif>3 minutes</option>
					<option value="6" @if($doctor->slot_duration == 6) selected="selected" @endif>6 minutes</option>
					<option value="5" @if($doctor->slot_duration == 5) selected="selected" @endif>5 minutes</option>
					<option value="10" @if($doctor->slot_duration == 10) selected="selected" @endif>10 minutes</option>
					<option value="15" @if($doctor->slot_duration == 15) selected="selected" @endif>15 minutes</option>
					<option value="20" @if($doctor->slot_duration == 20) selected="selected" @endif>20 minutes</option>
					<option value="30" @if($doctor->slot_duration == 30) selected="selected" @endif>30 minutes</option>
					<option value="45" @if($doctor->slot_duration == 45) selected="selected" @endif>45 minutes</option>
					<option value="60" @if($doctor->slot_duration == 60) selected="selected" @endif>1 Hours</option>
					<option value="90" @if($doctor->slot_duration == 90) selected="selected" @endif>1 Hour 30 minutes</option>
					<option value="120" @if($doctor->slot_duration == 120) selected="selected" @endif>2 Hours</option>-->
				  </select>
				  <span class="help-block"></span>
				</div>
				@if(!empty($doctor->opd_timings))
				  <?php $schdules = json_decode($doctor->opd_timings);
					$row = 1;
				  ?>
				@foreach($schdules as $index => $schdule)
				<div class="main-div-schedule">
							<div class="check-wrapper checkbox-div">
				  <label class="chck-container">
					<input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" @if(in_array('1',$schdule->days)) checked="checked" @endif  value="1">Monday
					<span class="checkmark"></span>
				  </label>
				  <label class="chck-container">
					<input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" @if(in_array('2',$schdule->days)) checked="checked" @endif  value="2">Tuesday
					<span class="checkmark"></span>
				  </label>

				  <label class="chck-container">
					<input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" @if(in_array('3',$schdule->days)) checked="checked" @endif  value="3">Wednesday
					<span class="checkmark"></span>
				  </label>

				  <label class="chck-container">
					<input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" @if(in_array('4',$schdule->days)) checked="checked" @endif  value="4">Thursday
					<span class="checkmark"></span>
				  </label>

				  <label class="chck-container">
					<input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" @if(in_array('5',$schdule->days)) checked="checked" @endif  value="5">Friday
					<span class="checkmark"></span>
				  </label>

				   <label class="chck-container">
					<input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" @if(in_array('6',$schdule->days)) checked="checked" @endif  value="6">Saturday
					<span class="checkmark"></span>
				  </label>

				   <label class="chck-container">
					<input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" @if(in_array('0',$schdule->days)) checked="checked" @endif  value="0">Sunday
					<span class="checkmark"></span>
				  </label>
							</div>

							<div class="pop-up-detail">
				 <?php $timecnt = 1; ?>
							 @foreach($schdule->timings as $rowss => $timeval)
				 <div class="sessions-div" scheduleCnt="{{$index}}">
								<label>Session {{$timecnt}}:</label>
				   <div class="teleconsult_section" @if(in_array(1,$types)) style="display:block;" @else style="display:none;" @endif>
					  <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1" @if(isset($timeval->teleconsultation) && !empty($timeval->teleconsultation)) checked @endif>Tel- Consultation</label>
					  <input type="hidden" class="teleconsult" name="schedule[{{$index}}][timings][{{$timecnt}}][teleconsultation]" value="@if(isset($timeval->teleconsultation) && !empty($timeval->teleconsultation)) 1 @else 0 @endif">
					</div>
					<div class="teleconsult_duration set_error" style="display:@if(isset($timeval->teleconsultation) && $timeval->teleconsultation == '1') block @else none @endif;">
						<select name="schedule[{{$index}}][timings][{{$timecnt}}][tele_appt_duration]" class="slots">
							<option value="">Select</option>
						  @foreach($appt_durations as $idx => $dur)
							<option value="{{$dur->time}}" @if(isset($timeval->tele_appt_duration) && $timeval->tele_appt_duration == $dur->time) selected="selected" @endif>{{$dur->title}}</option>
						   @endforeach
						</select>
					</div>
				  <div class="set_error">
					<select name="schedule[{{$index}}][timings][{{$timecnt}}][start_time]" class="session_time_up given_time">
						 <option value="">Start Time</option>
						 @foreach($day_in_increments as $time)
						 <option value="{{date( 'H:i', $time )}}" @if($timeval->start_time == date( 'H:i', $time )) selected="selected" @endif >{{date( 'g:i A', $time )}}</option>
						 @endforeach
					</select>
				  </div>
				  <div class="set_error">
					 <select name="schedule[{{$index}}][timings][{{$timecnt}}][end_time]" class="session_time_down given_time">
						<option value="">End Time</option>
						@foreach($day_in_increments as $time)
						  <option value="{{date( 'H:i', $time )}}"  @if($timeval->end_time == date( 'H:i', $time )) selected="selected" @endif >{{date( 'g:i A', $time )}}</option>
						@endforeach
					</select>
				  </div>
				   @if($timecnt > 1)
					<button class="btn btn-default removeSess"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
				   @endif
				  <?php $timecnt++; ?>
				   </div>
								@endforeach

							</div>
				 <div class="add-more-session"><a class="addSession" href="javascript:void(0);">Add More Session</a></div>
				 <div id="msg" class="success-data alert alert-danger" style="display: none;"></div>
				@if($row > 1)
				 <div class="opd-timings-schedule"><button class="btn btn-default remove" type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div>
				@endif
				 </div>
				  <?php $row++; ?>
				@endforeach
				@else
				  <div class="main-div-schedule">
					<div class="check-wrapper checkbox-div">
					<label class="chck-container">
					  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="1">Monday
					  <span class="checkmark"></span>
					</label>
					<label class="chck-container">
					  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="2">Tuesday
					  <span class="checkmark"></span>
					</label>

					<label class="chck-container">
					  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="3">Wednesday
					  <span class="checkmark"></span>
					</label>

					<label class="chck-container">
					  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="4">Thursday
					  <span class="checkmark"></span>
					</label>

					<label class="chck-container">
					  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="5">Friday
					  <span class="checkmark"></span>
					</label>

					 <label class="chck-container">
					  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="6">Saturday
					  <span class="checkmark"></span>
					</label>

					 <label class="chck-container">
					  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="0">Sunday
					  <span class="checkmark"></span>
					</label>
				  </div>

				  <div class="pop-up-detail">
				   <div class="sessions-div" scheduleCnt="1">
					 <div class="schedulingTop">
					<label style="float:left; width:100%;">Session 1:</label>
					<div class="teleconsult_section">
					  <label style="padding-left:0px; padding-bottom:10px;"class="checkbox-inline"><input  style="height:auto !important; float:left !important; margin:4px 5px 0 0;" type="checkbox" class="teleconsult_check"  value="1">Tel- Consultation</label>
					  <input type="hidden" class="teleconsult" name="schedule[1][timings][1][teleconsultation]" value="0">
					</div>
					<div class="teleconsult_duration set_error" style="display:none;">
						<select name="schedule[1][timings][1][tele_appt_duration]" class="slots">
							<option value="">Tele Appointment Duration</option>
						  @foreach($appt_durations as $index => $dur)
							<option value="{{$dur->time}}">{{$dur->title}}</option>
						   @endforeach
						</select>
					</div>
					<div class="set_error">
					<select name="schedule[1][timings][1][start_time]" class="session_time_up given_time">
					   <option value="">Start Time</option>
					   @foreach($day_in_increments as $time)
					   <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option>
					   @endforeach
					</select>
					</div>
					<div class="set_error">
					 <select name="schedule[1][timings][1][end_time]" class="session_time_down given_time">
					  <option value="">End Time</option>
					  @foreach($day_in_increments as $time)
						<option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option>
					  @endforeach
					</select>
					</div>
				  </div>
				   </div>
				  </div>
				  <div class="add-more-session"><a class="addSession" href="javascript:void(0);">Add More Session</a></div>
				  
				  <div id="msg" class="success-data alert alert-danger" style="display: none;"></div>

				  <div style="margin-left:10px;" class="add-more-session schedule"><a href="javascript:void(0);" class="addMoreSchedule">Add More Schedule</a></div> 
				
				</div>
				@endif
						</div>
			  
				<!-- <div class="form-fields send-button doc-profile final-form-submit">
				  <button type="submit" class="formSubmit">Save</button>
				</div> -->
			</div>
				</div>
			  </div>

						 <!-- <div class="col-sm-6 form-check">
							<label>Status</label><br>
							<label class="radio-inline"><input type="radio" name="status" value="1" @if(@$doctor->status == '1') checked="checked" @endif>Active</label>
							<label class="radio-inline"><input type="radio" name="status" value="0"  @if(@$doctor->status == '0') checked="checked" @endif>Inctive</label>
						  </div>  -->
						  <div class="reset-button">
							<!--<button type="reset" class="btn btn-warning">Reset</button>-->
							<button type="submit" class="btn btn-success submit">Save</button>
						 </div></div>
					{!! Form::close() !!}
				 </div>
			 </div>
		 </div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="documentViewModel" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close closeImageModal">&times;</button>
      </div>
      <div class="modal-body">
          <iframe src="" style="width: 100%; height:570px;" frameborder="0" id="DocumentView"></iframe>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-select.min.js') }}"></script>

<script src="{{ URL::asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/dropzone.js') }}"></script>
<script type="text/javascript">

	$('.btn-default').click(function() {
		$('.modal').modal('hide');
	});

	$('.close').click(function() {
		$('.modal').modal('hide');
	});

	$(document).ready(function(){
		$(".teleconsult_check").each(function(){
			if($(this).is(':checked')){
				$(this).closest('.sessions-div').find('.set_error').addClass('CommonWidth');
			}
		});
	});

	jQuery.validator.addMethod("letterswithspace", function(value, element) {
		return this.optional(element) || /^[a-z][a-z\s]*$/i.test(value);
	}, "This feild should be in alphabets only.");
 	jQuery(document).on("click change", ".oncall_status", function () {
        if($(this).val() == '1' && $(this).prop("checked") == true) {
          $(".oncall_fee").show();
          $(".teleconsult_section").show();
        }
		else if($(this).val() == '2' && $(this).prop("checked") == true) {~
			$(".inclinic_fees").show();
        }
		else if($(this).val() == '1'){
			$(".oncall_fee").hide();
			$(".teleconsult_section").hide();
			$(".teleconsult_section").each(function(){
				$(this).find(".teleconsult_check").prop("checked",false);
			});

        }

        else if($(this).val() == '2'){
			$(".inclinic_fees").hide();
        }
	});

			jQuery(document).ready(function(){
        $(".multiSelect").select2();
				jQuery("#updateDoctor").validate({
					rules: {
						clinic_name:  {required:true,maxlength:255},
						name: {required:true,maxlength:50},
						first_name: {required:true,maxlength:30},
						last_name: {required:true,maxlength:30},
						mobile_no:{required:true,minlength:10,maxlength:10,number: true},
						clinic_mobile:{minlength:10,maxlength:10,number: true},
						email: {required: true,email: true,maxlength:100},
						clinic_email: {email: true,maxlength:100},
						consultation_fees: {required:true,maxlength:6,number: true},
						convenience_fee: {maxlength:4,number: true},
						plan_consult_fee: {maxlength:4,number: true},
						// consultation_discount: {maxlength:6,number: true},
						address_1: {required:true,maxlength:255},
						qualification: {required: true,maxlength:250},
						speciality: "required",
						clinic_speciality: "required",
						experience: {required: true,maxlength:2},
						reg_no: {required: true,maxlength:50},
						reg_year: {required: true,maxlength:4},
						reg_council: {required: true,maxlength:200},
						oncall_fee: {required: true,maxlength:6,number: true},
						last_obtained_degree: {maxlength:200},
						degree_year: {maxlength:4},
						university: {maxlength:200},
						note: {maxlength:250},
						country_id: "required",
						"oncall_status[]": "required",
						state_id: "required",
						city_id: "required",
						followup_count: {number: true},
						zipcode: {minlength:6,maxlength:6,number: true},
						slot_duration: "required",
						acc_no: {
							  required: function(element) {
								  if ($('input[name=ifsc_no]').val() != "" ||  $('input[name=bank_name]').val() != "") {
									  return true;
								  } else {
									  return false;
								  }
							  },
							  maxlength:16,
							  number: true
						  },
						   acc_name: {
							  required: function(element) {
								  if ($('input[name=acc_no]').val() != "" ||  $('input[name=bank_name]').val() != "") {
									  return true;
								  } else {
									  return false;
								  }
							  },
							  maxlength:50,
						  },
						  ifsc_no: {
							  required: function(element) {
								  if ($('input[name=acc_no]').val() != ""  || $('input[name=bank_name]').val() != "") {
									  return true;
								  } else {
									  return false;
								  }
							  },
							  minlength:11,
							  maxlength:11
						  },
						  paytm_no: {
							  minlength:10,
							  maxlength:10,
							  number: true
						  },
						  bank_name: {
							  required: function(element) {
								  if ($('input[name=acc_no]').val() != "" || $('input[name=ifsc_no]').val() != "" ) {
									  return true;
								  } else {
									  return false;
								  }
							  }
						  },
                    },
					messages:{
					},
					errorPlacement: function(error, element){
						if (element.attr("name") == 'oncall_status[]') {
							$("#oncall_status").html(error);
						}
                    	else {
							error.appendTo(element.next());
                    	}
					},ignore: ":hidden",
					submitHandler: function(form) {
						var flag = true;
						 $('.main-div-schedule').each(function() {
                            $(this).find(".success-data").html('');
							$(this).find("#msg").hide();
                            if($(this).find('.day_check:checked').length < 1){
                                $(this).find(".success-data").append('<p>Please select at least one Schedule.</p>');
                                $(this).find(".success-data").slideDown();
                                flag = false;
                            }
                        });
						$('.given_time').each(function () {
							if($(this).val()==''){
								$(this).next(".help-block").remove();
							    $(this).after('<span style="width:100%" class="help-block">This field is required</span>');
							   	flag = false;
							}
							else{
								$(this).next(".help-block").remove();
							}
						});
						$('.AlternateAddress .form-group .form-control').each(function () {
							if($(this).val()==''){
								$(this).parent().find('.help-block .error').remove();
							    $(this).parent().find('.help-block').append('<label for="alternate_address[]" generated="true" class="error" style="display: inline-block;">This field is required.</label>');
							   	flag = false;
							}
							else{
								$(this).parent().find('.help-block .error').remove();
							}
						});
						 $('.teleconsult_section .teleconsult_check').each(function (){
			               var teleconsult_duration = $(this).closest('.sessions-div').find('.teleconsult_duration .slots').val();
			              if($(this).is(':checked') && teleconsult_duration == ""){
			                $(this).closest('.sessions-div').find('.teleconsult_duration .slots').next(".help-block").remove();
			                $(this).closest('.sessions-div').find('.teleconsult_duration .slots').after('<span style="width:100%" class="help-block">This field is required</span>');
			                  flag = false;
			                 //return false;
			              }
			              else{
			                $(this).closest('.sessions-div').find('.teleconsult_duration .slots').next(".help-block").remove();
			              }
			            });
						if(flag == true) {
							$(form).find('.submit').attr('disabled',true);
							 jQuery('.loading-all').show();
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
						else{
							return false;
						}
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

			jQuery(document).on("change", ".city_id", function (e) {
			  var cid = this.value;
			  var $el = jQuery('.locality_id');
			  $el.empty();
			  jQuery.ajax({
				  url: "{!! route('getLocalityList') !!}",
				  // type : "POST",
				  dataType : "JSON",
				  data:{'id':cid},
				success: function(result){
				  jQuery("#updateDoctor").find("select[name='locality_id']").html('<option value="">Select Locality</option>');
				  jQuery.each(result,function(index, element) {
					  $el.append(jQuery('<option>', {
						 value: element.id,
						 text : element.name
					  }));
				  });
				},
				error: function(error) {
					if(error.status == 401){
						alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						//alert("Oops Something goes Wrong.");
					}
				}
				}
			  );
			});
      jQuery(document).on("click", ".addSession", function () {
             var cnt = jQuery(this).parents(".main-div-schedule").find('.pop-up-detail .sessions-div').length+1;
             var scheduleCnt = jQuery(this).parents(".main-div-schedule").find('.pop-up-detail .sessions-div').attr('scheduleCnt');
             //alert(scheduleCnt);
             if(cnt <= 8){
				var row =  '<div class="sessions-div" scheduleCnt="1"> <div class="schedulingTop"><label>Session '+cnt+':</label> <div class="teleconsult_section"> <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1">Tel- Consultation</label><input type="hidden" class="teleconsult" name="schedule['+scheduleCnt+'][timings]['+cnt+'][teleconsultation]" value="0"> </div><div class="teleconsult_duration set_error" style="display:none;"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][tele_appt_duration]" class="slots"> <option value="">Tele Appointment Duration</option> @foreach($appt_durations as $index => $dur) <option value="{{$dur->time}}">{{$dur->title}}</option> @endforeach </select> </div> <div class="set_error"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][start_time]" class="session_time_up given_time"> <option value="">Start Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div> <div class="set_error"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][end_time]" class="session_time_down given_time"> <option value="">End Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div></div>';
				row += '<button class="btn btn-default removeSess"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div>';
				jQuery(this).parents(".main-div-schedule").find('.pop-up-detail').append(row);
				$('.oncall_status').each(function () {
					if($(this).val() == '1' && $(this).prop("checked") == true) {
						$(".teleconsult_section").show();
					}
					else if($(this).val() == '1'){
						$(".teleconsult_section").hide();
						$(".teleconsult_section").each(function() {
							$(this).find(".teleconsult_check").prop("checked",false);
						});
					}
				});
            }else{
				alert("You can not add more than 8 sessions.");
            }
      });

      jQuery(document).on("click", ".removeSess", function () {
           jQuery(this).parents(".main-div-schedule .pop-up-detail  .sessions-div").remove();
      });


      jQuery(".addMoreSchedule").click(function(){
		  var cnt = jQuery('.main-div-schedule').length+1;
		  if(cnt <= 7){
			   var row = '<div class="main-div-schedule"> <div class="check-wrapper checkbox-div"> <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="1">Monday <span class="checkmark"></span> </label> <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="2">Tuesday <span class="checkmark"></span> </label>  <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="3">Wednesday <span class="checkmark"></span> </label>  <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="4">Thursday <span class="checkmark"></span> </label>  <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="5">Friday <span class="checkmark"></span> </label>  <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="6">Saturday <span class="checkmark"></span> </label>  <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="0">Sunday <span class="checkmark"></span> </label> </div> <div class="pop-up-detail"> <div class="sessions-div" scheduleCnt="1"> <label>Session 1:</label><div class="teleconsult_section"> <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1">Tel- Consultation</label> <input type="hidden" class="teleconsult" name="schedule['+cnt+'][timings][1][teleconsultation]" value="0"></div><div class="teleconsult_duration set_error" style="display:none;"> <select name="schedule['+cnt+'][timings][1][tele_appt_duration]" class="slots"> <option value="">Tele Appointment Duration</option> @foreach($appt_durations as $index => $dur) <option value="{{$dur->time}}">{{$dur->title}}</option> @endforeach </select> </div><div class="set_error"> <select name="schedule['+cnt+'][timings][1][start_time]" class="session_time_up given_time"> <option value="">Start Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div> <div class="set_error"> <select name="schedule['+cnt+'][timings][1][end_time]" class="session_time_down given_time"> <option value="">End Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div></div> </div> <div class="add-more-session"><a class="addSession" href="javascript:void(0);">Add More Session</a></div><div id="msg" class="success-data alert alert-danger" style="display: none;"></div> <div class="opd-timings-schedule"><button class="btn btn-default remove" type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div></div>';
				jQuery('.complete-str').append(row);
				$('.oncall_status').each(function () {
					if($(this).val() == '1' && $(this).prop("checked") == true) {
						$(".teleconsult_section").show();
					}
					else if($(this).val() == '1'){
						$(".teleconsult_section").hide();
						$(".teleconsult_section").each(function() {
							$(this).find(".teleconsult_check").prop("checked",false);
						});
					}
				});
        }
        else{
                    alert("Doctor Can not Schedule More Than 7 Days.");
              }
      });
      jQuery(document).on("click", ".remove", function () {
         jQuery(this).parents(".main-div-schedule").remove();
      });

      jQuery(document).on("change", ".day_check", function () {
        var th = $(this), val = th.prop('value');
        if(th.is(':checked')) {
          $("#opd_timing_tab").find(':checkbox[value="'+val+'"]').not($(this)).prop('checked',false);
          $(this).closest(".main-div-schedule").each(function() {
          $(this).find(".success-data").html('');
          $(this).find("#msg").hide();
            if($(this).find('.day_check:checked').length < 1) {
              $(this).find(".success-data").html('');
              $(this).find(".success-data").find("#msg").hide();
            }
          });
        }
        else{
          $(this).closest(".main-div-schedule").find(".success-data").html('');
          $(this).closest(".main-div-schedule").find("#msg").hide();
          $(this).closest(".main-div-schedule").each(function() {
          $(this).find(".success-data").html('');
          $(this).find("#msg").hide();
            if($(this).find('.day_check:checked').length < 1) {
              $(this).find(".success-data").append('<p>Please select at least one Schedule.</p>');
              $(this).find(".success-data").slideDown();
            }
          });
        }
      });
      jQuery(document).on("change", ".session_time_up", function (){
              var currevent = this;
              var apostart_time = $(currevent).val();
              var practimeslot = 15;
              var updatedEndTime =  moment(apostart_time, "HH:mm:ss").add(practimeslot, 'minutes');
              selectedvar = moment(updatedEndTime).format('HH:mm:ss');
              console.log(selectedvar);
              var stDatetimestamp = moment(selectedvar, "HH:mm:ss").format('X');
              arrsloatEnd = [];
              $(this).find('option').each(function(){
                  if($(this).val() != ''){
                      var endTimestamp = moment($(this).val(), "HH:mm:ss").format('X');
                      if(endTimestamp >= stDatetimestamp){
                          arrsloatEnd.push($(this).val());
                      }
                  }
              });
              var row = '';
              $.each( arrsloatEnd, function( key, value ) {
                  var str = value;
                  var time = new moment(str, 'HH:mm:ss');
                  row += '<option value="'+value+'" >'+moment(time).format('hh:mm A')+'</option>';
              });
              $(currevent).parents('.sessions-div').find('.session_time_down').empty();
              $(currevent).parents('.sessions-div').find('.session_time_down').html(row);
      });
      jQuery(document).on("change", ".teleconsult_check", function (){
		 if($(this).is(':checked')){
		    $(this).closest('.teleconsult_section').find('.teleconsult').val(1);
		    $(this).closest('.sessions-div').find('.teleconsult_duration').show();
		    var slot_duration = '{{$doctor->slot_duration}}';
		    $(this).closest('.sessions-div').find('.teleconsult_duration .slots').val(slot_duration).trigger('change');
		    $(this).closest('.sessions-div').find('.set_error').addClass('CommonWidth');

		  }
		  else {
			 $(this).closest('.teleconsult_section').find('.teleconsult').val(0);
		     $(this).closest('.sessions-div').find('.teleconsult_duration').hide();
		     $(this).closest('.sessions-div').find('.teleconsult_duration .slots').prop('selectedIndex',0);
		      $(this).closest('.sessions-div').find('.set_error').removeClass('CommonWidth');
		  }
		});

		/*jQuery(document).on("change", ".slots", function (){
			if($(this).val() > 0){
				var currevent = this;
				var increment = $(this).val() * 60;
				var result = range(0, (86400-increment),increment);
				var row = '';
				$.each(result, function( key, value ) {
					var time = moment.unix(value).utc().format('hh:mm A');
					var tvalue = moment.unix(value).utc().format('HH:mm');
					row += '<option value="'+tvalue+'" >'+time+'</option>';
				});
				$(currevent).parents('.sessions-div').find('.session_time_down').empty();
				$(currevent).parents('.sessions-div').find('.session_time_up').empty();
				$(currevent).parents('.sessions-div').find('.session_time_up').html(row);
				$(currevent).parents('.sessions-div').find('.session_time_down').html(row);
			}
		});*/

		function range(start, end, step = 1) {
		  const len = Math.floor((end - start) / step) + 1
		  return Array(len).fill().map((_, idx) => start + (idx * step))
		}

	jQuery(document).on("keyup", ".verifyDocData", function () {
			var docInfo  = $(this).val();
			var v_type  = $(this).attr('v_type');
			var doc_id  = $(this).attr('doc_id');
			if(v_type == 1){
				if(docInfo.length > 6) {
					verifyDocDetail(docInfo,v_type,doc_id);
				}
			}
			else{
				verifyDocDetail(docInfo,v_type,doc_id);
			}
		});
		function verifyDocDetail(docInfo,v_type,doc_id) {
			jQuery.ajax({
				type: "POST",
				dataType : "HTML",
				url: "{!! route('verifyDocDetailsEdit')!!}",
				data:{'docInfo':docInfo,'v_type':v_type,'doc_id':doc_id},
				success: function(data) {
					if(data == 1){
						if(v_type == 1){
							alert('This mobile number is already registered with another person.');
							jQuery("#updateDoctor").find('input[name="mobile_no"]').val('');
						}
						else{
							alert('This Email is already registered with another person.');
							jQuery("#updateDoctor").find('input[name="email"]').val('');
						}
					}
				  jQuery('.loading-all').hide();
				},
				error: function(error) {
					if(error.status == 401){
						//alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						//alert("Oops Something goes Wrong.");
					}
					jQuery('.loading-all').hide();
				}
			});
		}

jQuery(document).ready(function() {
//Disabling autoDiscover
Dropzone.autoDiscover = false;
$(function() {
var myDropzone = new Dropzone(".dropzone", {
url: "{{route('admin.uploadDoctorDocuments', ['_token' => csrf_token() ])}}",
addRemoveLinks: true,
paramName: "file",
maxFilesize: 0.5,
maxFiles: 5,
acceptedFiles: ".jpeg,.jpg,.png,.pdf",
init: function(){
  let thisDropzone = this; // Closure
  this.on("error", function(file, message) {
	var msg = '<label for="type" generated="true" class="error">'+message+'</label>';
	$('#documetTypes').parent().find('.help-block label').remove();
	$('#documetTypes').parent().find('.help-block').append(msg);
	this.removeFile(file);
  });
  this.on('sending', function(file, xhr, formData){
	var type = $('#documetTypes .active').data('id');
	var id = jQuery("#updateDoctor").find('input[name="user_id"]').val();
	var doc_id = jQuery("#updateDoctor").find('input[name="id"]').val();
	formData.append('doc_id', doc_id);
	formData.append('user_id', id);
	formData.append('type', type);
  });
  this.on("success", function(file, response) {
	var ext = response.file_name.split('.').pop().toLowerCase();
	var img = response.file_name;
	if (ext == 'pdf') {
	  var img = "{{URL::asset('img/pdf.png')}}";
	}
	var typeClass = {"1":"profilePic", "2":"clinicPic", "3":"regCet", "4":"degree", "5":"addPrf"};
	var cls = typeClass[response.type];
	var img = '<div class="image-block '+cls+'"><span class="removeImage" data-id="'+response.id+'"><i class="fa fa-times" aria-hidden="true"></i></span><img src="'+img+'" filename="'+response.file_name+'" class="openFile" alt="" width="100"><span class="typeName">'+response.type_name+'</span></div>';
	$('.userDocuments').append(img);
	this.removeFile(file);
});
}
});
});
});
jQuery(document).on("click", ".openFile", function (e) {
  var file = $(this).attr('filename');
      jQuery('.loading').hide();
      jQuery('#documentViewModel').modal('show');
      jQuery('#documentViewModel').show();
      jQuery('#DocumentView').attr('src', file);
      setTimeout(function(){
       jQuery("#DocumentView").contents().find("img").css({'width': '100%'});
      }, 300);
      $('#DocumentView').load(function() {
          $(this).contents().find('img').css({'width': '100%'});
      })
});
jQuery(document).on("click", ".closeImageModal", function (e) {
	$("#documentViewModel").hide();
	// jQuery('#documentViewModel').modal('hide');
});
jQuery(document).on("click", ".editModalClose", function (e) {
	location.reload();
});
jQuery(document).on("click", "#documetTypes li", function (e) {
  $('#documetTypes li').removeClass('active');
  $(this).addClass('active');
});
jQuery(document).on("click", ".removeImage", function (e) {
  var id = $(this).data('id');
  var current = $(this);
  if (confirm("Are you sure want to delete this file?")) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.deleteFile') !!}",
    data:{'id':id},
    success: function(data){
      jQuery('.loading-all').hide();
      $(current).closest('.image-block').remove();
    },
    error: function(error){
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
  }
  else{
  return false;
  }
});
jQuery(document).on("click", "#addAddress", function (e) {
	var div = '<div class="col-md-4 form-group"><label>Alternate Address</label><br><textarea class="form-control" rows="2" name="alternate_address[]" value=""></textarea><span class="help-block"></span><div class="closeAddress"><i class="fa fa-times" aria-hidden="true"></i></div></div>';
	$('.AlternateAddress').append(div);

});
jQuery(document).on("click", ".closeAddress", function (e) {
	$(this).parent().remove();
});
$('#image-file').on('change', function() {
var fileSize= this.files[0].size / 1024;
if(fileSize>1024){
	alert("File size should not be greater than 1 mb");
	$('#image-file').val('');
	return false;
}

});

$('#clinic_image').on('change', function() {
var fileSize= this.files[0].size / 1024;
if(fileSize>1024){
	alert("File size should not be greater than 1 mb");
	$('#clinic_image').val('');
	return false;
}

});

$('#profile_pic').on('change', function() {
var fileSize= this.files[0].size / 1024;
if(fileSize>1024){
	alert("File size should not be greater than 1 mb");
	$('#profile_pic').val('');
	return false;
}

});

var clinicSearchRequest;
jQuery(document).on("keyup paste", ".clinic_nameBySearech", function () {
var currSearch = this;
if(jQuery(this).val().length <= 0) {
	$('#updateDoctor').find('input[name="clinic_id"]').val('');
	 jQuery(".container").find(".suggesstion-box").hide();
	 jQuery(".container").find(".suggesstion-box ul").remove();
}
if(clinicSearchRequest) {
	clinicSearchRequest.abort();
}
if(jQuery(this).val().length >= 3) {
  clinicSearchRequest = jQuery.ajax({
  type: "POST",
  url: "{!! route('getClinics') !!}",
  data: {'searchText':jQuery(this).val()},
  beforeSend: function(){
	jQuery(currSearch).css("background","#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
  },
  success: function(data){
  console.log(data);
	  var liToAppend = "";
		if(data.length > 0){
		  jQuery.each(data,function(k,v) {
			 var city_id = null;
			 var locality_id = null;
			 var city = '';
			 var clinic_name = '';
			 var practice_type = null;
			 var locality = '';
			 var clinic_speciality = null;
			 var pic_width  = 15;
			 var pic_height = 12;
			 var search_pic = '{{ URL::asset("img/search-dd.png") }}';
			 if(v.locality_id){
				locality = v.locality_id.name;
				locality_id = v.locality_id.id;
			 }
			 if(v.clinic_speciality){
				clinic_speciality = v.clinic_speciality;
			 }
			 if(v.city_id){
				city = v.city_id.name;
				city_id = v.city_id.id;
			 }
			 if(v.clinic_name){
				clinic_name = v.clinic_name;
			 }
	 if(v.practice_type){
				practice_type = v.practice_type;
			 }
			if(v.clinic_image_url != null){
				search_pic = v.clinic_image_url;
				pic_width  = 30;
				pic_height  = 30;
			}
			liToAppend += '<li value="'+v.id+'" p_id="'+v.practice_id+'" clinic_name="'+v.clinic_name+'" practice_type="'+practice_type+'" city_id="'+city_id+'" locality_id="'+locality_id+'" clinic_speciality="'+clinic_speciality+'" clinic_image="'+v.clinic_image+'"  clinic_image_url="'+v.clinic_image_url+'" clinic_mobile="'+v.clinic_mobile+'" clinic_email="'+v.clinic_email+'" website="'+v.website+'" address_1="'+v.address_1+'" country_id="'+v.country_id+'" state_id="'+v.state_id+'" zipcode="'+v.zipcode+'" class="dataListClinics"><i class="icon-clinicImage-pic"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail-clinic"><span class="txt">'+v.clinic_name+'</span><span class="spec">' +locality+' '+city+'</span></div></li>';
		  });
		}else{
			//liToAppend += '<li value="0">"'+jQuery(currSearch).val()+'" Clinic Not Found.</li>';
	  }
	  jQuery(currSearch).closest(".form-group").find(".suggesstion-box").show();
	  jQuery(currSearch).closest(".form-group").find(".suggesstion-box").html('<ul>'+liToAppend+'</ul>');
  }
  });
}


jQuery(document).on("click", ".dataListClinics", function (e) {
	e.stopImmediatePropagation();
    e.preventDefault();
alert('Your profiledd has been created as a Visiting doctor under this "'+jQuery(this).find('.txt').text()+'"');
//alert(jQuery(this).attr("p_id"));
$('#updateDoctor').find('input[name="clinic_id"]').val(jQuery(this).attr("p_id"));
jQuery(this).closest(".form-group").find(".clinic_nameBySearech").val(jQuery(this).find('.txt').text());
jQuery(this).closest(".form-group").find(".clinic_nameBySearech").attr('readonly',true);

if(jQuery(this).attr("practice_type") != "null") {
practice_type = jQuery(this).attr("practice_type");
if (practice_type == 2) {
  $('#updateDoctor').find('#hospital').prop('checked',true);
  $('#updateDoctor').find('.clinicRadio').hide();

}
else {
  $('#updateDoctor').find('#clinic ').prop('checked',true);
  $('#updateDoctor').find('.hospitalRadio').hide();
}
}


if(jQuery(this).attr("country_id") != "null") {
country_data = jQuery(this).attr("country_id");

$('#updateDoctor').find('.country_id option[value="'+country_data+'"]').prop('selected',true);
	var countryField = $('#updateDoctor').find('.country_id');
addDupHiddenField(countryField, 1);
}
else {
var countryField = $('#updateDoctor').find('.country_id');
addDupHiddenField(countryField, 1);
}
if(jQuery(this).attr("state_id") != "null") {
  var state_data = jQuery(this).attr("state_id");
  $('#updateDoctor').find('.state_id').val(state_data).trigger('change');
  var stateField = $('#updateDoctor').find('.state_id');
  addDupHiddenField(stateField, 1);
  }
  else {
	var stateField = $('#updateDoctor').find('.state_id');
	addDupHiddenField(stateField, 1);
  }
if(jQuery(this).attr("city_id") != "null") {
var city_data = jQuery(this).attr("city_id");
setTimeout(function(){
$('#updateDoctor').find('.city_id').val(city_data).trigger('change');
var cityField = $('#updateDoctor').find('.city_id');
addDupHiddenField(cityField, 1);
},1000);
}
  else {
	var cityField = $('#updateDoctor').find('.city_id');
	addDupHiddenField(cityField, 1);
  }

if(jQuery(this).attr("locality_id") != "null") {
var locality_data = jQuery(this).attr("locality_id");
//alert(locality_data);
setTimeout(function(){
$('#updateDoctor').find('.locality_id').val(locality_data).trigger('change');
var localityField = $('#updateDoctor').find('.locality_id');
addDupHiddenField(localityField, 1);
},1500);
}
else {
	var localityField = $('#updateDoctor').find('.locality_id');
	addDupHiddenField(localityField, 1);
}

if(jQuery(this).attr("clinic_speciality") != "null") {
  var clinic_speciality = jQuery(this).attr("clinic_speciality");
  //alert(clinic_speciality);
  setTimeout(function(){
	$('#updateDoctor').find('.clinic_speciality').val(clinic_speciality).trigger('change');
	var clinicSpeField = $('#updateDoctor').find('.clinic_speciality');
	addDupHiddenField(clinicSpeField, 1);
  },1500);
}
else {
var clinicSpeField = $('#updateDoctor').find('.clinic_speciality');
addDupHiddenField(clinicSpeField, 1);
}
if(jQuery(this).attr("clinic_mobile") != "null") {
clinic_m=	$('#updateDoctor').find('input[name="clinic_mobile"]').val(jQuery(this).attr("clinic_mobile"));

}
if(jQuery(this).attr("clinic_email") != "null") {
	$('#updateDoctor').find('input[name="clinic_email"]').val(jQuery(this).attr("clinic_email"));
}
if(jQuery(this).attr("website") != "null") {
	$('#updateDoctor').find('input[name="website"]').val(jQuery(this).attr("website"));
}
if(jQuery(this).attr("address_1") != "null") {
	$('#updateDoctor').find('input[name="address_1"]').val(jQuery(this).attr("address_1"));
	$('#updateDoctor').find('input[name="address_1"]').attr('readonly',true);
}
if(jQuery(this).attr("zipcode") != "null") {
	$('#updateDoctor').find('input[name="zipcode"]').val(jQuery(this).attr("zipcode"));
}
if(jQuery(this).attr("clinic_image") != "null" && jQuery(this).attr("clinic_image_url") != "null") {
	$('#updateDoctor').find('#docClinicImage').attr('src',jQuery(this).attr("clinic_image_url"));
	$('#updateDoctor').find('.clinicFIleDIv').hide();
}
else{
	$('#updateDoctor').find('#docClinicImage').attr('src',"{{ URL::asset('img/camera-icon.jpg') }}");
	$('#updateDoctor').find('.clinicFIleDIv').show();
}
$('#updateDoctor').find('input[name="clinic_mobile"]').attr('readonly',true);
$('#updateDoctor').find('input[name="clinic_email"]').attr('readonly',true);
$('#updateDoctor').find('input[name="website"]').attr('readonly',true);
$('#updateDoctor').find('input[name="zipcode"]').attr('readonly',true);

$('#updateDoctor').find(".btn-reset-clinic").show();
jQuery(this).closest(".suggesstion-box").hide();
jQuery(this).closest(".suggesstion-box ul").remove();

});

jQuery(document).on("click", ".btn-reset-clinic", function () {
jQuery(".clinic_nameBySearech").attr('readonly',false);
$('#addDoctor').find('input[name="clinic_id"]').val('');
jQuery(".clinic_nameBySearech").val('');


var countryField = $('#updateDoctor').find('.country_id');
addDupHiddenField(countryField, 2);

var stateField = $('#updateDoctor').find('.state_id');
addDupHiddenField(stateField, 2);

var cityField = $('#updateDoctor').find('.city_id');
addDupHiddenField(cityField, 2);

var localityField = $('#updateDoctor').find('.locality_id');
addDupHiddenField(localityField, 2);

var clinicSpeField = $('#updateDoctor').find('.clinic_speciality');
addDupHiddenField(clinicSpeField, 2);

$('#updateDoctor').find('.clinicRadio').show();
$('#updateDoctor').find('.hospitalRadio').show();
$('#updateDoctor').find('#clinic').prop('checked', false);
$('#updateDoctor').find('#hospital').prop('checked', false);



$('#updateDoctor').find('.country_id').val('101').trigger('change');
$('#updateDoctor').find('.state_id').val('33').trigger('change');
$('#updateDoctor').find('.city_id').val('').trigger('change');
$('#updateDoctor').find('.locality_id').val('').trigger('change');
$('#updateDoctor').find('.clinic_speciality').val('').trigger('change');

$('#updateDoctor').find('input[name="clinic_mobile"]').val('');
$('#updateDoctor').find('input[name="clinic_email"]').val('');
$('#updateDoctor').find('input[name="website"]').val('');
$('#updateDoctor').find('input[name="address_1"]').val('');
$('#updateDoctor').find('input[name="address_1"]').attr('readonly',false);
$('#updateDoctor').find('input[name="zipcode"]').val('');
$('#updateDoctor').find('#docClinicImage').attr('src','{{ URL::asset('img/camera-icon.jpg') }}');
$('#updateDoctor').find('.clinicFIleDIv').show();

$('#updateDoctor').find('input[name="clinic_mobile"]').attr('readonly',false);
$('#updateDoctor').find('input[name="clinic_email"]').attr('readonly',false);
$('#updateDoctor').find('input[name="website"]').attr('readonly',false);
$('#updateDoctor').find('input[name="zipcode"]').attr('readonly',false);
$('#updateDoctor').find('input[name="city_id"]').attr('disabled',false);
$('#updateDoctor').find('input[name="locality_id"]').attr('disabled',false);
$('#updateDoctor').find('input[name="clinic_speciality"]').attr('disabled',false);
$('#updateDoctorp').find(".btn-reset-clinic").hide();
});


function addDupHiddenField(field, type){
var name = field.prop('name');
var parent = field.parent();
if (type == 1) {
if (field.prop('disabled')) {
var name = field.attr('original-name');
parent.find('input[type="hidden"][name='+name+']').val(field.val());
}
else {
field.attr('original-name', name);
var $hiddenInput = $('<input/>',{ type  : 'hidden',
name  : name,
value : field.val()
});
parent.append( $hiddenInput );
field.prop({ name : name + "_1",disabled : true });
}
}
else if (type == 2 ) {
if(field.prop('disabled')){
var name = field.attr('original-name');
parent.find('input[type="hidden"][name='+name+']').remove();
field.prop({name : name,disabled : false});
field.removeAttr('original-name');
}
}
}

});



</script>
