<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Hospital</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag feedback">
				<div class="panel-heading">
				</div>
				<div class="panel-body form-groupTtalNew">
					{!! Form::open(array('id' => 'updateHospitalContent','name'=>'updateHospitalContent', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
					<input type=hidden value="{{$hospital->id}}" name="id"/>
					<input type="hidden" name="doctors" id="doctors_vall">
					<div class="col-md-6">
					<div class="form-group">
						<label>Hospital Name</label>
						<input value="{{@$hospital->name}}" type="text" name="name" class="form-control" placeholder="Enter Hospital Name" >
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label>Hospital Helpline Number</label>
						<input value="{{@$hospital->help_line}}" type="text" name="help_line" class="form-control" placeholder="Enter Hospital Helpline Number" />
						<span class="help-block"></span>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group">
						<label>Nodal Officer</label>
						<input value="{{@$hospital->nodal_officer}}" type="text" name="nodal_officer" class="form-control" placeholder="Enter Nodal Officer" />
						<span class="help-block"></span>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group">
						<label>Asst. Nodal Officer</label>
						<input value="{{@$hospital->asst_nodal_officer}}" type="text" name="asst_nodal_officer" class="form-control" placeholder="Enter Asst. Nodal Officer" />
						<span class="help-block"></span>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group">
						<label>Website Url</label>
						<input value="{{@$hospital->url}}" type="text" name="url" class="form-control" placeholder="Enter Website Url" />
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-sm-6">
                    <div class="form-group">
					 <label>State</label>
						 <select class="form-control state_id multiSelect" name="state">
						  <option value="">Select State</option>
							@foreach (getStateList(101) as $state)
								<option value="{{ $state->id }}" @if($hospital->state == $state->id) selected @endif >{{ $state->name }}</option>
							@endforeach
						</select>
						<span class="help-block"></span>
					 </div>
                     </div>
					 <div class="col-md-6">
                     <div class="form-group">
					  <label>City</label><br>
						<select class="form-control city_id multiSelect" name="city">
							<option value="">Select City</option>
							@if(!empty($hospital->state))
							@foreach (getCityList($hospital->state) as $city)
								<option value="{{ $city->id }}" @if($hospital->city == $city->id) selected @endif >{{ $city->name }}</option>
							@endforeach
							@endif
						</select>
						<span class="help-block"></span>
                          </div>
					  </div>
					  
					   <div class="col-md-6">
                     <div class="form-group">
					  <label>Status</label><br>
						<select class="form-control" name="status">
							<option value="">Select Status</option>
							<option value="1" @if($hospital->status == '1') selected @endif >Active</option>
							<option value="0" @if($hospital->status == '0') selected @endif >Deactive</option>
						</select>
						<span class="help-block"></span>
                          </div>
					  </div>
					  
				
						<label class="main">General Beds</label>
						<div class="col-md-6">
						<div class="form-group">
							<label>Total</label>
							<input value="{{@$hospital->total_general_beds}}" type="text" name="total_general_beds" class="form-control NumericFeild " placeholder="Enter Total General Beds" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Occupied</label>
							<input value="{{@$hospital->o_gen_beds}}" type="text" name="o_gen_beds" class="form-control NumericFeild " placeholder="Enter Occupied General Beds" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Available</label>
							<input value="{{@$hospital->a_gen_beds}}" type="text" name="a_gen_beds" class="form-control NumericFeild " placeholder="Enter Available General Beds" readonly/>
							<span class="help-block"></span>
						</div>
						</div>
					
					
					
						<label class="main">Oxygen Beds</label>
						<div class="col-md-6">
						<div class="form-group">
							<label>Total</label>
							<input value="{{@$hospital->total_oxygen_beds}}" type="text" name="total_oxygen_beds" class="form-control NumericFeild " placeholder="Enter Total Oxygen Beds" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Occupied</label>
							<input value="{{@$hospital->o_oxy_beds}}" type="text" name="o_oxy_beds" class="form-control NumericFeild " placeholder="Enter Occupied Oxygen Beds" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Available</label>
							<input value="{{@$hospital->a_oxy_beds}}" type="text" name="a_oxy_beds" class="form-control NumericFeild " placeholder="Enter Available Oxygen Beds" readonly/>
							<span class="help-block"></span>
						</div>
						</div>
					
					
					
						<label class="main">ICU Beds without Ventilator</label>
						<div class="col-md-6">
						<div class="form-group">
							<label>Total</label>
							<input value="{{@$hospital->total_icu_beds_w_v}}" type="text" name="total_icu_beds_w_v" class="form-control NumericFeild " placeholder="Enter Total ICU Beds without Ventilator" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Occupied</label>
							<input value="{{@$hospital->o_icu_beds_w_v}}" type="text" name="o_icu_beds_w_v" class="form-control NumericFeild " placeholder="Enter Occupied ICU Beds without Ventilator" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Available</label>
							<input value="{{@$hospital->a_icu_beds_w_v}}" type="text" name="a_icu_beds_w_v" class="form-control NumericFeild " placeholder="Enter Available ICU Beds without Ventilator" readonly/>
							<span class="help-block"></span>
						</div>
						</div>
					
					
					
						<label class="main">ICU Beds with Ventilator</label>
						<div class="col-md-6">
						<div class="form-group">
							<label>Total</label>
							<input value="{{@$hospital->total_icu_beds_v}}" type="text" name="total_icu_beds_v" class="form-control NumericFeild " placeholder="Enter Total ICU Beds with Ventilator" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Occupied</label>
							<input value="{{@$hospital->o_icu_beds_v}}" type="text" name="o_icu_beds_v" class="form-control NumericFeild " placeholder="Enter Occupied ICU Beds with Ventilator" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Available</label>
							<input value="{{@$hospital->a_icu_beds_v}}" type="text" name="a_icu_beds_v" class="form-control NumericFeild " placeholder="Enter Available ICU Beds with Ventilator" readonly/>
							<span class="help-block"></span>
						</div>
						</div>
					
					<div class="col-md-6">
						<div class="form-group form-group123">
							<label>doctors</label> 
							 <button type="button" data="@if(count($hospital->CovidHospitalDoctors)>0){{$hospital->CovidHospitalDoctors}}@endif" class="btn btn-info btn-xs form-control edit_tagss" data-toggle="modal">Doctor Add</button>
						</div>
					</div>
					<div class="reset-button">
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
<div class="modal fade" id="doctorEditModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="closeDoctor">×</button>
			<h4 class="modal-title">Add Doctor</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group"> 
					</div>
				</div>
				<div class="panel-body">
					<div id="addedRows">
						<div class="form-group">
							<label>Doctor Name</label>
							<input type="text" class="form-control tag_names" placeholder="Enter Doctor Name" name="doctors[]"/>
						</div>
					</div>
					<div class="form-group">
						<button type="button" class="btn btn-default form-control" onclick="addMoreRows();">Add More Rows</button>
					</div>
					<div class="reset button">
						<button type="button" class="btn btn-primary addTags">Save</button>
						<button type="button" class="btn btn-default closeDoctor">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</div>   
  </div>   
</div> 


<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script type="text/javascript">
jQuery(document).on("change", ".state_id", function (e) {
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
jQuery(document).ready(function(){
$(".multiSelect").select2();
$(document.body).on('click', '.submit', function(){
 jQuery("form[name='updateHospitalContent']").validate({
	rules: {
		name:   {required:true,maxlength:255},
		total_general_beds: {required:false,number: true},
		o_gen_beds: {required:false,number: true},
		a_gen_beds: {required:false,number: true},
		total_oxygen_beds: {required:false,number: true},
		o_oxy_beds: {required:false,number: true},
		a_oxy_beds: {required:false,number: true},
		total_icu_beds_w_v: {required:false,number: true},
		o_icu_beds_w_v: {required:false,number: true},
		a_icu_beds_w_v: {required:false,number: true},
		total_icu_beds_v: {required:false,number: true},
		o_icu_beds_v: {required:false,number: true},
		a_icu_beds_v: {required:false,number: true},
		// help_line: {required:true,minlength:10,maxlength:15,number: true},
		// url: "required",
		// nodal_officer: "required",
		// asst_nodal_officer: "required",
		status: "required",
		city: "required",
		state: "required",
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
			url: "{!! route('admin.updateHospitalContent')!!}",
			data:  new FormData(form),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data) {
				 if(data==1){
				  jQuery('.loading-all').hide();
				  $(form).find('.submit').attr('disabled',false);
					location.reload();
				 }
				 else{
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

var rowCount = 1;
function addMoreRows() {
	rowCount++;
	var recRow = '<div class="form-group" id="rowCount'+rowCount+'"><input type="text" class="form-control tag_names" placeholder="Enter Doctor Name" name="doctors[]"><a class="close-button" href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="fa fa-times" aria-hidden="true"></i></a></div>';
	$("#doctorEditModal").find('#addedRows').append(recRow);
}
function removeRow(removeNum) {
	jQuery('#rowCount'+removeNum).remove();
}

jQuery(document).on("click", ".edit_tagss", function (e) {
	if($(this).attr('data')){
		var arr = JSON.parse($(this).attr('data'));
		if(arr.length > 0){
			var recRow = '';
			$("#doctorEditModal").find('#addedRows').html('');
			var i = 1;
			$.each(arr, function( key, value ) {
				recRow += '<div class="form-group" id="rowCount'+i+'"><input type="text" class="form-control tag_names" placeholder="Enter Doctor Name" name="doctors[]" value="'+value.name+'">';
				if(key > 0){ 
					recRow += '<a class="close-button" href="javascript:void(0);" onclick="removeRow('+i+');"><i class="fa fa-times" aria-hidden="true"></i></a>';
				}
				recRow += '</div>';
				i++;
			});
			$("#doctorEditModal").find('#addedRows').append(recRow);
			$("#doctorEditModal").modal('show');
		}
	}
	else{;
		$("#doctorEditModal").modal('show');
	}
});
jQuery(document).on("click", ".closeDoctor", function (e) {
	$("#doctorEditModal").modal('hide');
});
jQuery(document).on("click", ".addTags", function (e) {
	var arr = new Array();
	 $("#doctorEditModal").find(".tag_names").each(function(){
		if($(this).val()) {
			arr.push($(this).val());
		}
	 });
	if(arr.length > 0){
		$("#doctors_vall").val(JSON.stringify(arr));
	}
	$("#doctorEditModal").modal('hide');
});
$("input[name=o_gen_beds]").on("change paste keyup", function() {
    var av = $("#hospitalEditModal input[name=total_general_beds]").val() - $(this).val();
	$("#hospitalEditModal input[name=a_gen_beds]").val(av);
 });
 $("input[name=o_oxy_beds]").on("change paste keyup", function() {
    var av = $("#hospitalEditModal input[name=total_oxygen_beds]").val() - $(this).val();
	$("#hospitalEditModal input[name=a_oxy_beds]").val(av);
 });
 $("input[name=o_icu_beds_w_v]").on("change paste keyup", function() {
    var av = $("#hospitalEditModal input[name=total_icu_beds_w_v]").val() - $(this).val();
	$("#hospitalEditModal input[name=a_icu_beds_w_v]").val(av);
 });
 $("input[name=o_icu_beds_v]").on("change paste keyup", function() {
    var av = $("#hospitalEditModal input[name=total_icu_beds_v]").val() - $(this).val();
	$("#hospitalEditModal input[name=a_icu_beds_v]").val(av);
 });


</script>