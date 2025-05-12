@extends('layouts.admin.Masters.Master')
@section('title', 'Edit Coupon')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
          
               <div class="container-fluid flex-grow-1 container-p-y data-list">
                       <div class=" form-top-row">
                                        <a class="btn btn-primary" href="{{ route('admin.couponMaster') }}"> <i class="fa fa-list"></i> Coupon List</a>
                                    </div>
               <div class="layout-content card appointment-master user-data-form" >
               {!! Form::open(array('route' => 'admin.updateCouponsMaster', 'id' => 'editCoupon','enctype' => 'multipart/form-data' , 'class' => 'col-sm-12')) !!}
               <div class="row"> 
               <input type=hidden value="{{$coupon->id}}" name="id"/>
                <input type=hidden value="{{$coupon->term_conditions}}" name="term_conditions" id="otherText"/>
          
              
                <div class="col-sm-3 form-group">
                      <label>Type<span>*</span></label>
                      <select class="form-control cType" name="type">
                      <option value="">Type</option>
                      <option value="1" @if($coupon->type == "1") selected @endif>Lab</option>
                      <option value="2" @if($coupon->type == "2") selected @endif>Appointment</option>
                      <option value="3" @if($coupon->type == "3") selected @endif>Medicine</option>
                      </select>
                      <span class="help-block"></span>
                    </div>
                    <div class="col-sm-3 form-group subcat" @if($coupon->type == "1") style="display:none;" @endif >
                      <label>Coupon sub Category</label>
                      <select class="form-control subcatField" name="coupon_sub_type">
                      <option value="">Type</option>
                      <option value="1" @if($coupon->coupon_sub_type == "1") selected @endif>Tele</option>
                      <option value="2" @if($coupon->coupon_sub_type == "2") selected @endif>Inclinic</option>
                      </select>
                      <span class="help-block"></span>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Coupon Title<span>*</span></label>
                      <input type="text" class="form-control" name="coupon_title" placeholder="Coupon Title" value="{{$coupon->coupon_title}}">
                      <span class="help-block"></span>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Coupon Discount Type<span>*</span></label>
                      <select class="form-control" name="coupon_discount_type">
                      <option value="">Type</option>
                      <option value="1" @if($coupon->coupon_discount_type == "1") selected @endif>₹</option>
                      <option value="2" @if($coupon->coupon_discount_type == "2") selected @endif>%</option>
                      </select>
                      <span class="help-block"></span>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Coupon Discount<span>*</span></label>
                      <input type="text" class="form-control" name="coupon_discount" placeholder="Discount Amount" value="{{$coupon->coupon_discount}}">
                      <span class="help-block"></span>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Coupon Code<span>*</span></label>
                      <input type="text" class="form-control" name="coupon_code" placeholder="Coupon Code" value="{{$coupon->coupon_code}}">
                      <span class="help-block"></span>
                    </div>
                    <div class="col-sm-3 form-group" style="display:none;">
                      <label>Coupon Duration Type<span>*</span></label>
                      <select class="form-control" name="coupon_duration_type">
  											<option value="">select duration type</option>
                        <option value="d" @if($coupon->coupon_duration_type == "d") selected @endif>Day</option>
  											<option value="m" @if($coupon->coupon_duration_type == "m") selected @endif>Month</option>
  											<option value="y" @if($coupon->coupon_duration_type == "y") selected @endif>Year</option>
  										</select>
                      <span class="help-block"></span>
                    </div>
                    <div class="col-sm-3 form-group" style="display:none;">
                      <label>Coupon Duration<span>*</span></label>
                      <input type="text" class="form-control" name="coupon_duration" placeholder="Coupon Duration" value="{{$coupon->coupon_duration}}">
                      <span class="help-block"></span>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Coupon Exipre Date:<span>*</span></label>
                      <input type="text" class="form-control datepicker" placeholder="dd-mm-YYYY" name="coupon_last_date" value="{{$coupon->coupon_last_date}}" autocomplete="off"/>
                      <span class="help-block"></span>
                    </div>
					             <div class="col-sm-3 form-group">
                      <label>max Uses Per Person</label>
                      <input type="text" class="form-control" name="max_uses" placeholder="max Uses Per Person" value="{{$coupon->max_uses}}">
                      <span class="help-block"></span>
                    </div>
                    <div class="col-sm-3 form-group cappOn">
                      <label>Coupon Show In List</label>
                      <select class="form-control applyT" name="is_show">
                        <option value="">Type</option>
                        <option value="1" @if($coupon->is_show == "1") selected @endif>Yes</option>
                        <option value="0" @if($coupon->is_show == "0") selected @endif>No</option>
                      </select>
                      <span class="help-block"></span>
                    </div>
                    
                    <div class="col-sm-12 form-group">
                      <label>Special Note</label>
                      <textarea rows="5" cols="5" class="form-control" placeholder="Special Note" name="other_text" value="{{$coupon->other_text}}">{{$coupon->other_text}}</textarea>
                      <span class="help-block"></span>
                    </div>
					           <div class="col-sm-12 form-group">
                      <label>Terms & Conditions</label>
                      <textarea rows="5" cols="5" class="form-control" id="couponText" placeholder="Terms & Conditions" name="other_text_" value="{{$coupon->term_conditions}}">{{$coupon->term_conditions}}</textarea>
                      <span class="help-block"></span>
                    </div>
                    <div class="col-sm-3 form-group cappOn" @if($coupon->type == "1") style="display:none;" @endif>
                      <label>Coupon Applied On<span>*</span></label>
                      <select class="form-control applyT" name="apply_type" id="apply_type">
                        <option value="">Type</option>
                        <option value="1" @if($coupon->apply_type == "1") selected @endif>Doctor Fees</option>
                        <option value="2" @if($coupon->apply_type == "2") selected @endif>Convienence Fees</option>
                      </select>
                      <span class="help-block"></span>
                    </div>
					         
                    <div class="col-sm-12 form-group">
                      <div class="reset-button">
                          <button type="reset" class="btn btn-warning">Reset</button>
                          <button type="submit" class="btn btn-success submit">Save</button>
                      </div>
                    </div>

                  
									 {!! Form::close() !!}


               </div>

               </div>
          </div>
       </div>
  </div>
</div>


<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>

		   <script src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script>

       <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
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
				  var editor = CKEDITOR.replace('other_text_');

				// CKEDITOR.config.removePlugins = 'maximize';
				// CKEDITOR.replace('couponText');
				// CKEDITOR.config.allowedContent = true;


				// });

			  </script>
           <script type="text/javascript">
           jQuery(document).ready(function() {
           	jQuery( ".datepicker" ).datepicker({
           	  dateFormat: 'dd-mm-yy'
           	});
           });
           // When the browser is ready...
           jQuery(document).ready(function () {
             jQuery("#editCoupon").validate({
               rules: {
                   coupon_title: "required",
                   coupon_code: "required",
                   coupon_discount_type: "required",
                   apply_type: "required",
                   coupon_discount: {required: true, number: true, range: [0, 1000]},
                   // coupon_duration_type: "required",
                   // coupon_duration: "required",
                   coupon_last_date: "required",
               },
               // Specify the validation error messages
               messages: {
                   coupon_title: "Please enter coupon title",
                   coupon_code: "Please enter coupon code",
                   coupon_discount: {required: "Please enter coupon amount", number: "Please Enter Valid Number", range: "Please Enter Range(0-100) Value"},
                   coupon_duration_type: "Please select coupon duration type",
                   coupon_duration: "Please enter coupon duration",
                   coupon_last_date: "Please enter expire date",
               },
                 errorPlacement: function(error, element) {
                      error.appendTo(element.next());
                   },ignore: ":hidden",
                 submitHandler: function(form) {
                   jQuery('.loading-all').show();
				   // alert();
					$("#otherText").val(editor.getData());
                   jQuery('.submit').attr('disabled',true);
                     jQuery.ajax({
                       type: "POST",
                       url: "{!! route('admin.updateCouponsMaster')!!}",
                       data:  new FormData(form),
                       contentType: false,
                       cache: false,
                       processData:false,
                       success: function(data) {
                         jQuery('.submit').attr('disabled',false);
                          if(data==1) {
                           document.location.href = "{!! route('admin.couponMaster')!!}";
                          }
                          else if(data==2) {
                           alert("Entered coupon code already exist");
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
                  // $('.applyT').attr("disabled", false);
                  $('#apply_type').rules('remove');
                }
              });
             /*$('.subcatField').change(function(){
                if($(this).val() == 2) {
                $('.applyT').val(2);
                $('.applyT').attr("disabled", true);
                //$('.subcat').show();
                }else{
                 $('.applyT').attr("disabled", false);
                }
              }); */
            });

            $('.btn-default').click(function() {
    $('.modal').modal('hide');
});

$('.close').click(function() {
    $('.modal').modal('hide');
});
           	</script>
			<!--<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>-->



@endsection
