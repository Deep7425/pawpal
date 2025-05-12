@extends('amp.layouts.Masters.Master')
@if(Session::get('loginFrom') == '1')
@section('title', 'Book Diagnostic Tests from Home at Best Prices | Health Gennie')
@elseif (Session::get('loginFrom') == '2')
@section('title', 'Buy Medicines | Health Gennie')
@else
@section('title', 'Health Gennie | Book Doctor Appointments, Order Medicine, Diagnostic Tests')
@endif
@section('content')

<div class="container-inner login-wrapper">
  <div class='example'>
  @if (Session::has('message'))
	<div class="alert alert-success sessionMsg">{{ Session::get('message') }}</div>
	@endif
  {!! Form::open(array('route' => 'generateCouponCode','method' => 'POST', 'id' => 'generateCouponCode', 'enctype' => 'multipart/form-data')) !!}
    <div class='tabsholder1'>
	    <div class="tab-content generate-codeWidth123">
        <div class="coupon-generate generate-codeWidth">
        	<div class="heading-pay1234">
        		<h2>Register Here</h2>
        	</div>
        <div class="heading-pay222">
          <div class="form-group">
            <label>Company/Store Name<i class="required_star">*</i></label>
            <input name="name" type="text" class="form-control"/>
			<span class="help-block">
				@if($errors->has('content'))
				<label for="content" generated="true" class="error">
					 {{ $errors->first('content') }}
				</label>
				@endif
			</span>
          </div>
		  <div class="form-group">
            <label>Owner name<i class="required_star">*</i></label>
            <input name="owner_name" type="text" class="form-control"/>
            <span class="help-block">
				@if($errors->has('owner_name'))
				<label for="owner_name" generated="true" class="error">
					 {{ $errors->first('owner_name') }}
				</label>
				@endif
			</span>
          </div>
		  
		  <div class="form-group">
            <label>Mobile No.<i class="required_star">*</i></label>
            <input name="mobile" type="text" class="form-control"/>
            <span class="help-block">
				@if($errors->has('mobile'))
				<label for="mobile" generated="true" class="error">
					 {{ $errors->first('mobile') }}
				</label>
				@endif
			</span>
          </div>
		  
		  <div class="form-group">
            <label>Id Proof</label>
            <input name="document" type="file" class="form-control"/>
            <span class="help-block"></span>
          </div>
          <div class="form-group">
            <label>Generate By<i class="required_star">*</i></label>
            <select name="interest_in" class="form-control">
              <option selected="selected" value="">Please Select</option>
              @foreach (getSalesTeam() as $key => $user)
                <option value="{{$user->id}}">{{$user->name}}</option>
              @endforeach
            </select>
            <span class="help-block">
				@if($errors->has('mobile'))
				<label for="mobile" generated="true" class="error">
					 {{ $errors->first('mobile') }}
				</label>
				@endif
			</span>
          </div>
		  <div class="form-group">
            <label>Address<i class="required_star">*</i></label>
            <textarea name="address" class="form-control"></textarea>
            <span class="help-block"></span>
          </div>
		  
		  <div class="form-group">
			  <label>Country<i class="required_star">*</i></label>
			 <select class="country_id searchDropDown" name="country_id">
				<option value="">Select country</option>
				@foreach(getCountriesList() as $country)
					<option value="{{$country->id}}" @if($country->id == '101') selected @endif >{{$country->name}}</option>
				@endforeach
			 </select>
				<span class="help-block"><label for="country_id" generated="true" class="error" style="display:none;"></label>
				@if($errors->has('country_id'))
				<label for="country_id" generated="true" class="error">
					 {{ $errors->first('country_id') }}
				</label>
				@endif
				</span>
			</div>
			<div class="form-group">
			  <label>State<i class="required_star">*</i></label>
				<select class="state_id searchDropDown" name="state_id">
				 <option value="">Select State</option>
					@foreach (getStateList(101) as $state)
						<option value="{{ $state->id }}" @if($state->id == '33') selected @endif >{{ $state->name }}</option>
					@endforeach
				</select>
				 <span class="help-block"><label for="state_id" generated="true" class="error" style="display:none;"></label>
					@if($errors->has('state_id'))
					<label for="state_id" generated="true" class="error">
						 {{ $errors->first('state_id') }}
					</label>
					@endif
				 </span>
			</div>
			<div class="form-group">
			  <label>City<i class="required_star">*</i></label>
			 <select class="city_id searchDropDown" name="city_id">
				<option value="">Select City</option>
				@foreach (getCityList(33) as $city)
					<option value="{{ $city->id }}" @if($city->id == '3378') selected @endif >{{ $city->name }}</option>
				@endforeach
			 </select>
			  <span class="help-block"><label for="city_id" generated="true" class="error" style="display:none;"></label>
				@if($errors->has('city_id'))
				<label for="city_id" generated="true" class="error">
					 {{ $errors->first('city_id') }}
				</label>
				@endif
			  </span>
			</div>
          </div>
          
          <div class="generate-codeWidth1234">
          <div class="heading-pay">
          	<h2>Bank Account Details</h2>
          </div>
          <div class="heading-pay222">
          <div class="form-group">
            <label>Bank Name</label>
            <input name="bank_name" type="text" class="form-control"/>
            <span class="help-block"></span>
          </div>
          <div class="form-group">
            <label>Account Name</label>
            <input name="acc_name" type="text" class="form-control"/>
            <span class="help-block"></span>
          </div>
		  <div class="form-group">
            <label>Account Number</label>
            <input name="acc_no" type="text" class="form-control"/>
            <span class="help-block"></span>
          </div>
		  <div class="form-group">
            <label>IFSC Code</label>
            <input name="ifsc_no" type="text" class="form-control"/>
            <span class="help-block"></span>
          </div>
		  <div class="form-group">
            <label>Paytm Number</label>
            <input name="paytm_no" type="text" class="form-control"/>
            <span class="help-block"></span>
          </div>
		  </div>
		  </div>
         
        </div>
        <div class="generateNowBtn">
         <button type="submit" class="btn btn-default generateNow">Generate Now</button>
         </div>
      </div>
    </div>
	{!! Form::close() !!}
  </div>
</div>
<div class="container-fluid">
  <div class="container">
  </div>
</div>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script>
	// jQuery(document).on("click", ".generateNow", function () {
    // var id = $('#SalesTeam').val();
    // if (id == "") {
      // $('#SalesTeam').next().find('label').remove();
      // $('#SalesTeam').next().append('<label for="interest_in" generated="true" class="error">please select user.</label>');
      // return false;
    // }
		// jQuery('.loading-all').show();
		// jQuery.ajax({
			// type: "POST",
			// dataType : "JSON",
			// url: "{!! route('generateCouponCode')!!}",
			// data:{'id':id},
			// success: function(data) {
				// console.log(data.status);
				// jQuery('.loading-all').hide();
				// if(data.status == 1) {
					// alert('Coupon generated successfully: '+data.coupon);
					// location.reload();
				// }
			// },
			// error: function(error) {
				// if(error.status == 401) {
					// alert("Session Expired,Please logged in..");
					// location.reload();
				// }
				// else{
					// alert("Oops Something goes Wrong.");
				// }
			// }
		// });
	// });
	jQuery(document).ready(function () {
		$(".searchDropDown").select2();
	
	jQuery('.country_id').on('change', function() {
		  var cid = this.value;
		  var $el = $('.state_id');
		  $el.empty();
		  //jQuery('.loading-all').show();
		   jQuery("#generateCouponCode").find(".state_id").prepend($('<option></option>').html('Loading...'));
		  jQuery.ajax({
			  url: "{!! route('getStateList') !!}",
			 // type : "POST",
			dataType : "JSON",
			data:{'id':cid},
			success: function(result) {
				jQuery("#generateCouponCode").find(".city_id").html('<option value="">Select City</option>');
				jQuery("#generateCouponCode").find(".state_id").html('<option value="">Select State</option>');
				 jQuery.each(result,function(index, element) {
					   $el.append(jQuery('<option>', {
						   value: element.id,
						   text : element.name
					  }));
				  });
			  //jQuery('.loading-all').hide();
			},
			error: function(error) {
				if(error.status == 401){
					//alert("Session Expired,Please logged in..");
					location.reload();
				}
				else{
					//alert("Oops Something goes Wrong.");
				}
				//jQuery('.loading-all').hide();
			}
			}
		  );
		})
		jQuery(document).on("change", ".state_id", function (e) {
		  var cid = this.value;
		  var $el = jQuery('.city_id');
		  $el.empty();
		  //jQuery('.loading-all').show();
		  jQuery("#generateCouponCode").find(".city_id").prepend($('<option></option>').html('Loading...'));
		  jQuery.ajax({
			  url: "{!! route('getCityList') !!}",
			  // type : "POST",
			  dataType : "JSON",
			  data:{'id':cid},
			success: function(result){
			  jQuery("#generateCouponCode").find(".city_id").html('<option value="">Select City</option>');
			  jQuery.each(result,function(index, element) {
				  $el.append(jQuery('<option>', {
					 value: element.id,
					 text : element.name
				  }));
			  });
			  //jQuery('.loading-all').hide();
			},
			error: function(error) {
				if(error.status == 401){
					//alert("Session Expired,Please logged in..");
					location.reload();
				}
				else{
					//alert("Oops Something goes Wrong.");
				}
				//jQuery('.loading-all').hide();
			}
			}
		  );
		});
	});
jQuery("#generateCouponCode").validate({
	rules: {
		name: {required:true,maxlength:50},
		owner_name: {required:true,maxlength:50},
		mobile:{required:true,minlength:10,maxlength:10,number: true},
		address: {required:true,maxlength:200},
		interest_in: "required",
		country_id: "required",
		state_id: "required",
		city_id: "required",
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
		  minlength:6,
		  maxlength:14
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
	messages: {
	},
	errorPlacement: function(error, element) {
		 error.appendTo(element.next());
	},ignore: ":hidden",
	submitHandler: function(form) {
		jQuery('.loading-all').show();
		form.submit();
	}
});
</script>
@endsection
