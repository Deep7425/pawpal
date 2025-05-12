@extends('layouts.admin.Masters.Master')
@section('title', 'Add Referral')
@section('content')
<div class="content-wrapper"> 

  <!-- Main content -->
  <div class="layout-wrapper layout-2 referral">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important; padding-left:0px !important;">
            
               <div class="container-fluid flex-grow-1 container-p-y add-offers-banner">
  
    <div class="row"> 
      <div class="form-top-row">
            <div class="panel-heading">
              <div class="btn-group"> <a class="btn btn-primary" href="{{ route('admin.referralMaster') }}"> <i class="fa fa-list"></i> Referral List</a> </div>
            </div>
          </div>
          
      <!-- Form controls -->
      <div class="layout-content card">
       
          
           {!! Form::open(array('route' => 'admin.addReferral', 'id' => 'addReferral','enctype' => 'multipart/form-data' , 'class' => '')) !!} 
            <!-- <input type=hidden name="term_conditions" id="otherText"/> -->
            <div class="row">
            <div class="col-sm-3 form-group">
              <label>Title<span>*</span></label>
              <input type="text" class="form-control" name="title" placeholder="Title" value="">
              <span class="help-block"></span> </div>
            <div class="col-sm-3 form-group" >
              <label>Discount Type<span>*</span></label>
              <select class="form-control" name="referral_discount_type">
                <option value="">select</option>
                <option value="1">₹</option>
                <option value="2">%</option>
              </select>
              <span class="help-block"></span> </div>
            <div class="col-sm-3 form-group">
              <label>Discount<span>*</span></label>
              <input type="text" class="form-control" name="referral_discount" placeholder="Discount Amount" value="">
              <span class="help-block"></span> </div>
            <div class="col-sm-3 form-group">
              <label>Code<span>*</span></label>
              <input type="text" class="form-control" name="code" placeholder="Code" value="">
              <span class="help-block"></span> </div>
            <div class="col-sm-3 form-group" style="display:none;">
              <label>Coupon Duration Type<span>*</span></label>
              <select class="form-control" name="referral_duration_type">
                <option value="">select duration type</option>
                <option value="d">Day</option>
                <option value="m">Month</option>
                <option value="y">Year</option>
              </select>
              <span class="help-block"></span> </div>

            <div class="col-sm-3 form-group" style="display:none;">
              <label>Referral Duration<span>*</span></label>
              <input type="text" class="form-control" name="referral_duration" placeholder="Referral Duration" value="">
              <span class="help-block"></span> </div>

                        <div class="col-sm-3 form-group">
                                        <label>Organization</label>
                                        <select class="form-control" name="organization_id">
                                            <option value="">Select</option>
                                            @foreach(getOrganizations() as $raw)
                                                <option value="{{$raw->id}}">{{$raw->title}}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>



            <div class="col-sm-3 form-group">
              <label>Expire Date:<span>*</span></label>
              <input type="text" class="form-control datepicker" placeholder="dd-mm-YYYY" name="code_last_date" autocomplete="off"/>
              <span class="help-block"></span> </div>
            <div class="col-sm-3 form-group">
              <label>max Uses Per Person</label>
              <input type="text" class="form-control" name="max_uses" placeholder="max Uses Per Person" value="">
              <span class="help-block"></span> </div>

              <div class="col-md-3">
              <div class="form-group SelectPlan">
                <label>Plan</label>
                <select class="form-control" id="exampleSelect1" name="plan_ids[]" size="1" multiple >
                  
							@foreach(getGenniePlan() as $index => $raw)
								
                  <option value="{{$raw->slug}}">{{$raw->plan_title}} - ({{number_format($raw->price - $raw->discount_price,2)}})</option>
                  
							@endforeach
						
                </select>
                <span class="help-block"></span> </div>
            </div>
            <!--<div class="col-sm-12 form-group">
                      <label>Terms & Conditions</label>
                      <textarea rows="5" cols="5" class="form-control" id="couponText" placeholder="Terms & Conditions" name="other_text_" value=""></textarea>
                      <span class="help-block"></span>
                    </div>-->
            <div class="col-sm-3 form-group cappOn">
              <label>Coupon Show In List</label>
              <select class="form-control applyT" name="is_show">
                <option value="">Type</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
              <span class="help-block"></span> </div>

            <div class="col-sm-12 form-group">
              <label>Special Note</label>
              <textarea rows="5" cols="5" class="form-control" id="couponText" placeholder="Special Note" name="other_text"></textarea>
              <span class="help-block"></span> </div>
            <div class="col-sm-12 form-group">
              <label>Terms & Conditions</label>
              <textarea rows="5" cols="5" class="form-control" id="editor" placeholder="Terms & Conditions" name="term_conditions" value=""></textarea>
              <span class="help-block"></span> </div>
            
            <div class="col-sm-12 form-group">
              <div class="reset-button">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-success submit">Save</button>
              </div>
            </div></div>
            {!! Form::close() !!} 
        
      </div>
    </div>
  
  </div>  </div>  </div>  </div>
  <!-- /.content --> 
</div>
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script> 
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
	$('#exampleSelect1').multiselect({
	includeSelectAllOption: true,
	enableFiltering: true,
	enableCaseInsensitiveFiltering: true,
});
});
</script> 
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery( ".datepicker" ).datepicker({
dateFormat: 'dd-mm-yy'
});
});
// When the browser is ready...
jQuery(document).ready(function () {
jQuery("#addReferral").validate({
rules: {
	type: "required",
	title: "required",
	code: "required",
	referral_discount_type: "required",
	referral_discount: {required: true, number: true, range: [0, 1000]},
	code_last_date: "required",

},
// Specify the validation error messages
messages: {
	title: "Please enter title",
	code: "Please enter code",
	referral_discount: {required: "Please enter coupon discount", number: "Please Enter Valid Number", range: "Please Enter Range(0-100) Value"},
	code_last_date: "Please enter expire date",
},
	errorPlacement: function(error, element) {
		 error.appendTo(element.next());
	  },ignore: ":hidden",
	submitHandler: function(form) {
		// $("#otherText").val(editor.getData());
	  jQuery('.loading-all').show();
	  jQuery('.submit').attr('disabled',true);
		  jQuery.ajax({
			  type: "POST",
			  url: "{!! route('admin.addReferral')!!}",
			  data:  new FormData(form),
			  contentType: false,
			  cache: false,
			  processData:false,
			  success: function(data) {
				jQuery('.submit').attr('disabled',false);
				 if(data==1) {
				  document.location.href = "{!! route('admin.referralMaster')!!}";
				 }
				else if(data==2) {
				  alert("Entered code already exist");
				 }
				 else {
				  alert("System Problem");
				 }
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
$(function() {
//$('#row_dim').hide();
$('.cType').change(function(){ //console.log($('.cType').val());
if($('.cType').val() == 2) {
$('.subcatField').val(1);
$('.subcat').show();
$('.cappOn').show();
 $('#apply_type').rules('add','required');
} else {
  $('.subcat').hide();
  $('.subcatField').val('');
  $('.cappOn').hide();
  //$('#apply_type').removeAttr('required');​​​​​
	$('#apply_type').rules('remove');
}
});
});
</script> 
<script src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script> 
<script>
// var editor = CKEDITOR.replace('couponText', { height: 200 });

// jQuery(document).ready(function() {
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
  CKEDITOR.config.removePlugins = 'maximize';
  var editor = CKEDITOR.replace('editor');


</script> 
@endsection 