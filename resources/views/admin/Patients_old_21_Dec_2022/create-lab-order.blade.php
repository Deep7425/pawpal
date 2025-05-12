@extends('layouts.admin.Masters.Master')
@section('title', 'Add Subscription Plans')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
 <div class="content-wrapper add-symptom-master CreateLabOrder123">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<div class="header-icon">
				<i class="pe-7s-note2"></i>
			</div>
			<div class="header-title">
				<form action="#" method="get" class="sidebar-form search-box pull-right hidden-md hidden-lg hidden-sm">
					<div class="input-group">
						<input type="text" name="q" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
							<button type="submit" name="search" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</form>
				<h1>Add Lab Order</h1>packages
				<ol class="breadcrumb hidden-xs">
					<li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
					<li class="active">Plans</li>
				</ol> 
			</div>
		</section>
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<!-- Form controls -->
				<div class="col-sm-12">
					<div class="panel panel-bd lobidrag">
						<!--<div class="panel-heading">
							<div class="btn-group">
								<a class="btn btn-primary" href="{{ route('plans.planMaster') }}"> <i class="fa fa-list"></i>Plans List</a>
							</div>
						</div>-->
						<div class="panel-body panel-body_lab">
							<form name="myForm">

								
                                <div class="form-group col-sm-4">
									<label>Health Checker Package:</label>
									<select class="form-control lab_order" name="lab_order">
										<option value="">Select Duration Type</option>
										@forelse(getLabCompanies() as $group)
										<option value="{{$group->id}}">{{$group->title}}</option>
										@endforeach
									</select>
									<span class="help-block errorLaborder">
									</span>
								</div>
								
                                   <div class="col-md-4 form-group showselect1234" id="showselect"  style="display:none">
									
									
								</div>
								
								<div class="form-group col-sm-4">
									<label>Name:</label>
									<input type="text" class="form-control username" value="{{$user->first_name}}" placeholder="Full Name" name="name">
									<span class="help-block errorName">
									</span>
								</div>
								
								<!--<input type="hidden" class="" id="reportHardCopy" name="hard_copy" value="">-->
						
								<div class="form-group col-sm-4">
									<label>Age:</label>
									<input type="text" class="form-control planSlug userage" value="{{$user->age}}" placeholder="Age" name="age"/>
									<span class="help-block userageError">
									</span>
								</div>
                               
								
								<div class="form-group col-sm-4 GenderMaleFemale123">
									<label>Gender:</label>
									<input type="checkbox" name="gender" value="M" @if($user->gender=='Male') checked @endif> Male

									<input type="checkbox" name="gender" value="F" @if($user->gender=='Female') checked @endif> Female
								
										<span class="help-block user_genderError"></span>
								</div>
								<div class="form-group col-sm-4">
									<label>Mobile no:</label>
									<input type="text" class="form-control usermobile" readonly value="{{$user->mobile_no}}" placeholder="Mobile" name="mobile">
									<span class="help-block usermobileError"></span>
								</div>
								<div class="form-group col-sm-4">
									<label>E-Mail:</label>
									<input type="text" class="form-control useremail" name="email" value="{{$user->email}}" placeholder="Email" >
									<span class="help-block useremailError"></span>
								</div>

								
								
								
								<input type="hidden" id="finalProducts" name="final_products" value=""/>
								<input type="hidden" name="coupon_id" id="coupanId" value="">
								<input type="hidden" name="coupon_amt" id="coupon_amt" value="">
								<input type="hidden" name="coupon_code" id="couponCode" value="">
								 <input type="hidden" name="coupon_discount_type" id="coupon_discount_type" value="">
								 <input type="hidden" name="lab_amount" id="lab_amount" value="">
								<input type="hidden" id="couponDiscountType" value="">
								<!--<input type="hidden" id="coupanDiscountAmount" value="">-->
								<input type="hidden" name="total_amount" id="totalAmount" value="">
								<input type="hidden" name="user_id" id="user_id" value="{{base64_decode(request()->get('id'))}}">
								<input type="hidden" name="payable_amt" id="totalfinalAmount" value="">
								<input type="hidden" name="report_type" id="reporttype" value="">
								<input type="hidden" name="company_ids" id="company_ids" value="">
								<!--<input type="hidden" name="report_type" id="reporttype" value="">-->
								<input type="hidden" class="" id="priceDiscount" name="discount_amt" value="">
								{{-- <input type="hidden" class="" id="coupanDiscountAmount" name="coupon_amt" value=""> --}}
								<input type="hidden" name="address_id" value="@isset($addresses->id) {{$addresses->id}} @endisset" >
								<input type="hidden" name="Margin" class="tMargin" id="tMargin" value="">
								<input type="hidden" name="status" value="0">
								<input type="hidden" name="order_status" value="YET TO ASSIGN">
								<input type="hidden" name="service_charge" class="serviceCharge" id="serviceCharge" value="">
								
								<div class="form-group col-sm-4">
									<label>Coupon Code:</label>
									<strong class="CouponAvailableMsg" style="display:none;"></strong>
									<div class="labapplycoupon">
									<input type="text" placeholder="Enter Coupon" class="form-control couponInput" id="couponInputCode" value="" />
							
									
									<button id="coupanApply" type="button" class="btn-add-address">Apply</button>
									</div>
								  </div>
								
								  
								 
									

								   	
								<div class="form-group col-sm-4">
									<label>Payment Mode:</label>
									<select class="form-control payment_mode_type" name="pay_type">
										<option value="">Select Payment Mode</option>
										<option value="3">Cash</option>
										<option value="4">Online</option>
										<option value="2">Cheque</option>
                                        <option value="5">Free</option>
										<option value="6">Payment Link</option>
									</select>
									<span class="help-block payment_mode_typeError">
									</span>
								</div>

								<div class="form-group col-sm-4 trackBlock" style="display:none;">
									<label>Txn Id:</label>
									<input type="text" class="form-control" placeholder="Txn Id received from paytm payments" name="tracking_id">
									<span class="help-block">
									</span>
								</div>

								<div class="form-group row payment_mode_cheque" style="display:none;">
									<div class="form-group col-sm-4">
										<label>Cheque Number:</label>
										<input type="text" class="form-control" placeholder="Cheque Number" name="cheque_no">
										<span class="help-block">
										</span>
									</div>
									<div class="form-group col-sm-4">
										<label>Cheque Payee Name:</label>
										<input type="text" class="form-control" placeholder="Cheque Payee Name" name="cheque_payee_name">
										<span class="help-block">
										</span>
									</div>
									<div class="form-group col-sm-4">
										<label>Cheque Bank Name:</label>
										<input type="text" class="form-control" placeholder="Cheque Bank Name" name="cheque_bank_name">
										<span class="help-block">
										</span>
									</div>
									<div class="form-group col-sm-4" >
										<label>Cheque Date:</label>
										<input type="text" class="form-control datepickerss" name="cheque_date" placeholder="dd-mm-YYYY" autocomplete="off">
										<span class="help-block">
										</span>
									</div>
									</div>

								    <div class="form-group col-sm-4">
									<label>Pincode<i class="required_star">*</i></label>
									<input type="text" placeholder="Pincode" class="form-control NumericFeild inputvalidation pincodes" id="checkPincode" name="pincode" maxlength="6" value="" />
									
									<span class="help-block pincodeError">
									</span>
									<div class="inputBoxLoader showmessage" style="display:none"><p>Service Is Available</p>
										<i class="loader"></i>
									  </div>
									</div>
									
									 <span class="help-block"></span>


									 <div class="form-group col-sm-4 form-groupDate">
										<label>Date<i class="required_star">*</i></label>
										<div class="date-formet-section showdatebox">
									
										</div>
										<span class="help-block scheduleDateError">
										</span>
									  </div>

									  <div class="form-group col-sm-4">
										<label>Time<i class="required_star">*</i></label>
										<select name="appt_time" id="scheduleTime" class="form-control">
										<option value="">Select Schedule Time</option>
										</select>
										
										<span class="help-block"><label for="speciality" generated="true" class="error" style="display:none;"></label></span>
										<span class="help-block scheduleTimeError">
										</span>
									  </div>
									  
								<div class="form-group col-sm-12">
									<label>Address:</label>
									<textarea  class="form-control address"  placeholder="Address" name="address">@isset($addresses->address){{$addresses->address}}@endisset @isset($addresses->locality){{$addresses->locality}} @endisset @isset($addresses->locality) {{$addresses->locality}} @endisset @isset($addresses->landmark) {{$addresses->landmark}} @endisset @isset($addresses->locality) {{$addresses->locality}} @endisset @isset($addresses->pincode) {{$addresses->pincode}} @endisset</textarea>
									
									<span class="help-block addressError">
									</span>
								</div>
									@if(Session::get("lab_company_type") == 0)
								  <div class="right-block save-block last waitingTime ReportTypeshow col-sm-12" style="display:none;">
									<h3>Report Type</h3>
									<div class="coupan-list ReportType">
									  <div class="list ">
										<!--<input type="radio" class="report_type" name="report_type waiting_time" value="N" cost="0">-->
										<p class="coupon-wrapper">
											<input type="radio" class="report_type" id="report_type_1" name="report_type" value="no"  checked>
											<label for="report_type_1">Soft Copy <span class="">₹0</span></label>
										</p>
										</div>
										<div class="list">
										<p class="coupon-wrapper">
										<input type="radio" class="report_type" id="report_type_2" value="yes" name="report_type" />
										<label for="report_type_2">Hard Copy + Soft Copy<span class="">₹75</span></label>
										</p>
									  </div>
									</div>
								  </div>
								  @endif  
								  <div class="form-group col-sm-12 calculation showselect1234" >
	
										
								   </div>
								</div>
							
							

								<div class="form-group col-sm-12 reset-button">
								   <button type="reset" class="btn btn-warning">Reset</button>
								  
								   <button id="checkPackage" type="button" class="btn btn-success shwopplybutton" style="display:none">Apply</button>
								
								   <button type="submit" id="createOrder" class="btn btn-success submit hidesavebutton">Save</button>
								</div>
							</form>
					   </div>
				   </div>
			   </div>
		   </div>
    </section> <!-- /.content -->
  </div>
  <script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
  <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script type="text/javascript">
	
// When the browser is ready...
$(document).ready(function(){

// $('.scheduleDate').datepicker('setDate', 'today');

$(document).on('change','.lab_order',function(){

   var cmp_id= btoa(this.value);
   var cmp_idcheck= this.value;
   if(cmp_id==''){
	return false;
   }

	// var wrapper  = "";
	jQuery('.loading-all').show();
	$('.shwopplybutton').hide();
	 $('.pincodes').val('');

    jQuery.ajax({
	type: "GET",
	url: "{!! route('admin.getLabsPackage')!!}/"+cmp_id,	
	success: function(data){
	
		$('#company_ids').val(data.cmp_id);
	      if(data.cmp_id=='2'){

			$('#showselect').html('<label>lab</label><select class="form-control packagesAppend lab_test" id="lab_test'+data.cmp_id+'"  name="lab[]" size="1" multiple></select><span class="help-block lab_testError errorLabordertest"></span>');
			$('#showselect').show();
			$('.showdatebox').html('<input type="text" class="scheduleDate form-control " name="appt_date"  placeholder="yyyy-mm-dd" readonly /><i class="fa fa-calendar" aria-hidden="true"></i>');
				$( ".scheduleDate" ).datepicker({
				dateFormat: 'dd-MM-yy',
				changeMonth: true,
				minDate:0,
				changeYear: true,
			});
		  }else{

			$('#showselect').html('<label>lab</label><select class="form-control packagesAppend lab_test" id="lab_test'+data.cmp_id+'"  name="lab[]" size="1" multiple></select><span class="help-block lab_testError errorLabordertest"></span>');
			 $('.showdatebox').html('<input type="text" class="scheduleDate form-control " name="appt_date"  placeholder="yyyy-mm-dd" readonly /><i class="fa fa-calendar" aria-hidden="true"></i>');
			$('#showselect').show();
			$( ".scheduleDate" ).datepicker({
			dateFormat: 'dd-MM-yy',
			changeMonth: true,
			changeYear: true,
	    	 });

		  }
              
		        if(data.cmp_id!='2'){
					$('.hidesavebutton').show();
			if(data.package.length>0){
				var lab_types="labpackage";
				data.package.forEach(function(package) {
					$('.packagesAppend').append('<option value='+package.discount_price+'_'+package.id+'_'+lab_types+'>'+package.title+'('+package.discount_price+')</option>');
                  
				});

	       }
                if(data.labcollection.length>0){
                    var lab_types="labcollection";
					// $('#lab_type').val(lab_types);
			
					data.labcollection.forEach(function(labcollection) {
                     if(labcollection.offer_rate){
						$('.packagesAppend').append('<option value='+labcollection.offer_rate+'_'+labcollection.id+'_'+lab_types+'>'+labcollection.default_labs.title+'('+labcollection.offer_rate+')</option>');
					 }else{
						$('.packagesAppend').append('<option value='+labcollection.cost+'_'+labcollection.id+'_'+lab_types+'>'+labcollection.default_labs.title+'('+labcollection.cost+')</option>');
					 }
					
					})

					$('#lab_test'+data.cmp_id+'').multiselect({
						includeSelectAllOption: true,
						enableFiltering: true,
						placeholder: 'Select Languages',
						enableCaseInsensitiveFiltering: true,
					});

				}
				

		 }else{

			$('.hidesavebutton').hide();
				data.package.forEach(function(package) {

					$('.packagesAppend').append('<option value='+package.rate.offerRate+'_'+package.id+'>'+package.name+'('+package.rate.offerRate+')</option>');

				})	

		$('#lab_test'+data.cmp_id+'').multiselect({
			includeSelectAllOption: true,
			enableFiltering: true,
			numberDisplayed: 0, 
			placeholder: 'Select Languages',
			enableCaseInsensitiveFiltering: true,
		});
		

		 }
		 jQuery('.loading-all').hide();

	},
	error: function(error){
		jQuery('.loading-all').hide();
		alert("Oops Something goes Wrong.");
	}
	});

});


$(document).on('change','.packagesAppend',function(){
	// alert(222);
	let lab_order=$('.lab_order').val();

	$('.calculation').html('<table class="table calculationrow"></table>');
	$('.shwopplybutton').hide();
  var values = 0.00;
  {
    $('input[type="checkbox"]:checked').each(function() {
		// alert(2211);
      //if(values.indexOf($(this).val()) === -1){
      
		var value=$(this).val();
	    if(value!='M'){
			if(value!='F'){
		if(value!='undefined'){
		var trainindIdArray = value.split('_');

		console.log("=============",trainindIdArray);
		
		values=values+parseInt(trainindIdArray[0]);
		}
		if(lab_order=='2'){
			$('.shwopplybutton').show();
		}
	}
	   }
	 
      // }
    });

	var coupon_rate='';
	var coupon_code='';
	paymentCalculate(values, coupon_rate, coupon_code ,2);
	// else{
	// 	$('.calculationrow').append('<tr><td>Total</td><td>'+values+'</td></tr>');
	// }
	
  }
});




jQuery(document).on("click", "#coupanApply", function () {

	var couponCode = $('#couponInputCode').val();
	if (couponCode != "") {
		ApplyCoupon(couponCode);
	}
	else{
		$('.CouponAvailableMsg').text('please enter Coupon Code');
		$('.CouponAvailableMsg').css("color", "red");
		$('.CouponAvailableMsg').slideDown();
	}
});


function ApplyCoupon(couponcode) {
  jQuery('#coupanApply').attr('disabled',true);
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('ApplyCoupon') !!}",
  data: {'couponcode':couponcode,'isDirect':'1'},
  success: function(data){
	
		if (data.status == '1') {
		  $('#coupanDiscount').val(btoa(data.coupon_rate));
		  $('#couponCode').val(data.coupon_code);
		  $('#coupon_discount_type').val(data.coupon_discount_type);
		  $('#couponDiscountType').val(data.coupon_discount_type);
		  var package_amount='';
		  paymentCalculate(package_amount,data.coupon_rate,data.coupon_code,1);
			$('.coupanApplyedBox').find('.applyCouponCode').text(data.coupon_code);
			if(data.other_text != null) {
				$('.coupanApplyedBox').find('.applyCouponText').text(data.other_text);
			}
			$('.divForHide').slideUp();
			$('.coupanApplyedBox').slideDown();
		}
		else{
			$('.CouponAvailableMsg').text('Invalid Or Expired Coupan Code');
			$('.CouponAvailableMsg').css("color", "red");
			$('.CouponAvailableMsg').slideDown();
		}
      jQuery('#coupanApply').attr('disabled',false);
    },
    error: function(error)
    {
      if(error.status == 401)
      {
        //  alert("Session Expired,Please logged in..");
          location.reload();
      }
      else
      {
        jQuery('.loading-all').hide();
       // alert("Oops Something goes Wrong.");
        jQuery('#coupanApply').attr('disabled',false);
      }
    }
  });
}

// function coupanDiscountFun(coupanDiscount, type) {
// 	console.log(coupanDiscount);
//   var disType = $('#couponDiscountType').val();
//   var paidAmount = atob($('#paidAmount2').val());
//   var priceDiscount = atob($('#priceDiscount').val());
//   var coupanDiscountAmount = 0;
//   if(disType == 1){
// 	coupanDiscountAmount = coupanDiscount;
//   }
//   else{
// 	  coupanDiscountAmount = paidAmount * coupanDiscount / 100;
//   }

//   var totalSaving = atob($('#totalSaving').val());
//   var reportHardCopy = atob($('#reportHardCopy').val());
//   if (type == 1) {
//     if (coupanDiscountAmount != "") {
//       $('#coupanDiscountAmount').val(btoa(coupanDiscountAmount));
//       $('.coupanDiscountAmount').text(coupanDiscountAmount);

//     }
//     else {
//       $('.coupanDiscountAmount').text('0.00');
//     }
//     paidAmount = paidAmount - coupanDiscountAmount;
//     // if (reportHardCopy != "") {
//     //   paidAmount = parseInt(paidAmount) + parseInt(reportHardCopy);
//     // }
//     if (priceDiscount != "" && priceDiscount > "0") {
//       $('#totalSaving').val(btoa(parseInt(totalSaving) + parseInt(coupanDiscountAmount)));
//       $('.totalSaving').text(parseInt(totalSaving) + parseInt(coupanDiscountAmount));
//     }
//     else{
//       $('#totalSaving').val(btoa(coupanDiscountAmount));
//       $('.totalSaving').text(coupanDiscountAmount);
//     }
//     $('.coupanApplyedBox').find('.save-icon').find('.applyCouponAmount').text(coupanDiscountAmount);
//   }
//   if (type == 2) {
//       paidAmount = parseInt(paidAmount);
//       // if (reportHardCopy != "") {
//       //   paidAmount = parseInt(paidAmount) + parseInt(reportHardCopy);
//       // }
//       $('#totalSaving').val(btoa(parseInt(totalSaving) - parseInt(coupanDiscountAmount)));
//       $('.totalSaving').text(parseInt(totalSaving) - parseInt(coupanDiscountAmount));
//   }
//   return paidAmount;
// }

function paymentCalculate(package_amount, coupon_rate, coupon_code, type) {
//   $('#coupanDiscountAmount').val('');

   let lab_order=$('.lab_order').val();
   if(lab_order=='2'){
	$('.ReportTypeshow').show();
   }else{
	$('.ReportTypeshow').hide();
   }
   
    if(type=='3'){
		
	
		// console.log("package_amount=======",coupon_rate);
		$('.calculationrow1').remove();
		$('.calculation').html('<table class="table calculationrow"></table>');
		$('.calculationrow').append('<tr><td> Amount</td><td>'+package_amount+'</td></tr>');
		var totalfinalAmount=$('#totalfinalAmount').val();
		      $('#totalfinalAmount').val('');
		var  coupanDiscount =  $('#coupanDiscountAmount').val();
              $('#coupanDiscountAmount').val('');
		var package_amount=parseFloat(totalfinalAmount)+parseFloat(coupanDiscount);
	 
		if(package_amount<500){
	
			package_amount=package_amount+parseInt(200);
		$('.calculationrow').append('<tr><td>Include Service Charge<br>(Service Charge Applicable On Order value Below ₹ 500)</td><td>200</td></tr>');
		$('.calculationrow').append('<tr><td>Total</td><td>'+Math.abs(package_amount)+'</td></tr>');
		$('#totalAmount').val(Math.abs(package_amount));
		}else{
			// alert(333);
		
			
			$('.calculationrow').append('<tr><td>Payble Amount</td><td>'+Math.abs(package_amount)+'</td></tr>');	
			$('#totalAmount').val(Math.abs(package_amount));
		}

		return false;
		
	}

	if(type=='4'){
		var totalAmount=$('#totalAmount').val();
	
		// console.log("package_amount=======",coupon_rate);
		$('.calculationrow').remove();
		$('.calculation').html('<table class="table calculationrow"></table>');
		$('.calculationrow').append('<tr><td> Amount</td><td>'+totalAmount+'</td></tr>');
		
	
		      $('#totalfinalAmount').val('');
		var  coupanDiscount =  $('#coupanDiscountAmount').val();
		var reporttype=$('#reporttype').val();
	
              $('#coupanDiscountAmount').val('');
			  if(reporttype!=''){
				var package_amount=parseInt(totalAmount)+parseInt(reporttype);
					
			$('.calculationrow').append('<tr><td>Include Hard Copy Charge</td><td>+'+reporttype+'</td></tr>');
			  }else{
				var package_amount=parseInt(totalAmount);
			  }

	 
		if(package_amount<500){
	
			package_amount=package_amount+parseInt(200);
		$('.calculationrow').append('<tr><td>Include Service Charge<br>(Service Charge Applicable On Order value Below ₹ 500)</td><td>200</td></tr>');
		$('.calculationrow').append('<tr><td>Total</td><td>'+Math.abs(package_amount)+'</td></tr>');
		$('#totalAmount').val(Math.abs(package_amount));
		}else{
			
			$('.calculationrow').append('<tr><td>Payble Amount</td><td>'+Math.abs(package_amount)+'</td></tr>');	
			// $('#totalAmount').val(Math.abs(package_amount));
		}

		return false;
		
	}

	if(package_amount){
		$('#lab_amount').val(package_amount);
		$('.calculationrow').append('<tr><td> Amount</td><td>'+Math.abs(package_amount)+'</td></tr>');
  
		if(package_amount<500){
			package_amount=package_amount+parseInt(200);
		$('.calculationrow').append('<tr><td>Include Service Charge<br>(Service Charge Applicable On Order value Below ₹ 500)</td><td>200</td></tr>');
		$('.calculationrow').append('<tr><td>Total</td><td>'+Math.abs(package_amount)+'</td></tr>');
		$('#totalAmount').val(Math.abs(package_amount));
		}else{
			$('.calculationrow').append('<tr><td>Payble Amount</td><td>'+Math.abs(package_amount)+'</td></tr>');	
			$('#totalAmount').val(Math.abs(package_amount));
		}
		
	}


	if(coupon_rate){

	
	    coupanDiscountAmount = coupon_rate;
 
		var totalAmount=$('#totalAmount').val();
		var totalfinalAmount=$('#totalfinalAmount').val();
		var lab_amount=$('#lab_amount').val();
	    var coupon_discount_type=$('#coupon_discount_type').val();
		$('.calculationrow').remove();
		$('.calculation').html('<table class="table calculationrow1 calculationrowTop3"></table>');
		$('.calculationrow1').append('<tr class="calculationrowTop1"><td>Package Amount</td><td>'+lab_amount+'</td></tr>');

		if(lab_amount<500){
		
			package_amount=totalAmount+parseInt(200);
			if(coupon_discount_type=='1'){
				var coupanDiscount=coupanDiscountAmount;
				coupanDiscountAmount = totalAmount - coupanDiscountAmount;
			}else{
				coupanDiscountAmount = totalAmount * coupanDiscountAmount / 100;
				var coupanDiscount=coupanDiscountAmount;
				var coupanDiscountAmount=totalAmount-coupanDiscountAmount;
				
			}
	        $('#coupon_amt').val(coupanDiscount);
			
		// alert(Math.abs(package_amount));
		$('#totalfinalAmount').val(coupanDiscountAmount);
		$('#coupanDiscountAmount').val(Math.abs(coupanDiscountAmount.toFixed(2)));
		$('#coupon_discount').text(Math.abs(coupanDiscountAmount));
		
		$('.calculationrow1').append('<tr><td>Include Service Charge<br>(Service Charge Applicable On Order value Below ₹ 500)</td><td>200</td></tr>');
		// $('.calculationrow1').append('<tr class="calculationrowTop1"><td>Package Amount</td><td>'+Math.abs(totalAmount)+'</td></tr>');
		$('.calculationrow1').append('<tr><td> Coupon Discount</td><td>-'+coupanDiscount+'</td></tr>');
		$('.calculationrow1').append('<tr class="calculationrowTop2"><td>Sub Total</td><td>'+coupanDiscountAmount.toFixed(2)+'</td></tr>');
	
		}else{

			if(coupon_discount_type=='1'){
				var coupanDiscount=coupanDiscountAmount;
				coupanDiscountAmount = totalAmount - coupanDiscountAmount;
			}else{
				coupanDiscountAmount = totalAmount * coupanDiscountAmount / 100;
				var coupanDiscount=coupanDiscountAmount;
				var coupanDiscountAmount=totalAmount-coupanDiscountAmount;
				
			}

		
			$('#coupanDiscountAmount').val(Math.abs(coupanDiscountAmount.toFixed(2)));
		
			$('#coupon_discount').text(Math.abs(coupanDiscountAmount.toFixed(2)));
			$('#coupon_amt').val(Math.abs(coupanDiscount));
			$('#totalfinalAmount').val(Math.abs(coupanDiscountAmount).toFixed(2));
			// $('.calculationrow1').append('<tr class="calculationrowTop1"><td>Package Amount</td><td>'+Math.abs(totalAmount)+'</td></tr>');
			$('.calculationrow1').append('<tr><td> Coupon Discount</td><td>-'+Math.abs(coupanDiscount).toFixed(2)+'</td></tr>');
			$('.calculationrow1').append('<tr><td>Payble Amount</td><td>'+Math.abs(coupanDiscountAmount).toFixed(2)+'</td></tr>');	
		}


	}





}

jQuery(document).on("click", ".removeCoupan", function () {
	
  var  coupanDiscount =  $('#coupanDiscountAmount').val();
  var totalAmount=$('#totalAmount').val();
 
 var coupon_code= $('#couponCode').val();

  paymentCalculate(totalAmount, coupanDiscount, coupon_code, 3);
  $('#couponInputCode').val('');
  $('#coupanDiscount').val('');
//   $('#coupanDiscountAmount').val('');
  $('#coupanId').val('')
  $('.coupanDiscountAmount').text('0.00')

})

$("#checkPincode").on("keyup paste", function(){
	
        var pincode = $(this).val();
		
        var current = this;
        // if (pincode.length != 6) {
		// 	$(current).parent().find('.help-block').find('label').hide();
        // }

        if (pincode.length == 6) {
			var company_id=$('.lab_order').val();
			
		 jQuery.ajax({
		  type: "POST",
		  dataType: 'json',
		  url: "{!! route('admin.checkPincodeAvailability') !!}",
		  data: {'pincode':pincode,'company_id':company_id},
		  success: function(data) {
			
			  if(data==1) {
				// jQuery('.loading-all').hide();
				// jQuery('#saveAddress').attr('disabled',false);
				// $(current).parent().find('.help-block').find('label').hide();
			
				$(".showmessage").show("slow", function(){
            // Code to be executed
			$('.showmessage').html('<label  style="display:block; color:green;">Service is Available</label>');
          
               });
				// $('.inputBoxLoader').hide();
			  }
			  else {
				$(".showmessage").show("slow", function(){
				// jQuery('#saveAddress').attr('disabled',true);
				// $(current).parent().find('.help-block').find('label').hide();
				$('.showmessage').html('<label style="display:block; color:red;">Service Not Available</label>');
				// $(current).val('');
				// $('.inputBoxLoader').hide(); 
			    });
			  }
			}
		  });
        }
        else{
        //   $(current).parent().find('.help-block').append('<label class="error" style="display:block; color:red;">This field is required</label>');
        }
    });





jQuery(document).on("change", ".scheduleDate", function () {
  var pincode = $('.pincodes').val();
  if(pincode==''){
    
	alert("Please provide pincode");
	return false;

  }
  
  var schedule_date = $(this).val();

    GetAppointmentSlots(pincode, schedule_date);
 
});

function GetAppointmentSlots(pincode, schedule_date) {
  var scheduleTime = $('#scheduleTime');
  var company_id=$('.lab_order').val();
  scheduleTime.empty();
  jQuery("#scheduleTime").prepend($('<option value=""></option>').html('Loading...'));
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('admin.GetAppointmentSlots') !!}",
  data: {'pincode':pincode, schedule_date:schedule_date,'company_id':company_id},
  success: function(data){
      if (data.lSlotDataRes > '0') {
        jQuery("#scheduleTime").html('<option value="">Select Schedule Time</option>');
        jQuery.each(data.lSlotDataRes,function(index, element) {
			   let slot_time = element.slot.split("-");
			scheduleTime.append(jQuery('<option>', {
             value: slot_time[0],
             text : element.slot,
             data_id : JSON.stringify(element)
          }));
        });
      }
      else{
		// alert(data.response);
		jQuery("#scheduleTime").html('<option value="">All Time Slots have been booked for the day</option>');
      }
    },
    error: function(error){
      if(error.status == 401) {
         // alert("Session Expired,Please logged in..");
          location.reload();
      }
      else {
        jQuery('.loading-all').hide();
       // alert("Oops Something goes Wrong.");
        jQuery('#saveAddress').attr('disabled',false);
      }
    }
  });
}


jQuery(document).on("click", "#checkPackage", function () {
	// alert(222);
//   jQuery('.loading-all').show();
var packageIds = new Array();

$('input[type="checkbox"]:checked').each(function() {
	        var value=($(this).val());
		
		    var ArrayD = value.split('_');
			if(ArrayD[1]!='undefined'){
			 packageIds.push(ArrayD[1]);
			}
		
    });


  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('admin.ViewCartAPI') !!}",
  data: {'packageIds':packageIds},
  success: function(data)
      {
		var coupon_rate='';
		var coupon_code='';
	
		$('#finalProducts').val(data.product);
        $('.hidesavebutton').show();
        var reportHardCopy = $(".report_type:checked").val();
		
        if(reportHardCopy != "yes") {
		
			if(data.chcCharges != 0) {
				$('#totalAmount').text(data.payable);
				$('#totalAmount').val(data.payable);
				$('#lab_amount').val(data.payable);
				// $('#totalAmount').val(data.payable);
				// $('#priceDiscount').val(btoa(totalAmount - data.rates));
				// $('.priceDiscount').text(totalAmount - data.rates);
			}
			else{
			     console.log("=================22111",data.hcrAmount);
				 console.log("=================222",data.payable);
				$('#totalAmount').val(data.payable);
				$('#lab_amount').val(data.payable);
		
			}
        }
		else{
			var paybleAmount=parseInt(data.payable) +data.hcrAmount;
			$('#reporttype').val(data.hcrAmount);
			$('#totalfinalAmount').val(paybleAmount);
			$('#totalAmount').val(data.payable);
			$('#lab_amount').val(data.payable);
			// $('.totalAmount').text(paybleAmount);
		}
		var coupanDiscount="";
		var coupon_code="";
		paymentCalculate(paybleAmount, coupanDiscount, coupon_code, 4);
		// alert(totalAmount);
		
        // $('#paidAmount2').val(btoa(data.payable));
        $('.tMargin').val(data.margin);
        $('#serviceCharge').val(data.chcCharges);
        // $('.serviceCharge').text(data.chcCharges);
        // $('.paidAmount').text();undefined
		
	
     },
      error: function(error)
      {
        if(error.status == 401)
        {
         //   alert("Session Expired,Please logged in..");
            location.reload();
        }
        else
        {
          jQuery('.loading-all').hide();
        //  alert("Oops Something goes Wrong.");
          jQuery('#saveAddress').attr('disabled',false);
        }
      }
   });
});


$(function () {

$('form').on('submit', function (e) {

  e.preventDefault();


 

  var lab_order=$('.lab_order').val();


  if(lab_order==''){

	  $('.errorLaborder').html('<p style="color:red">Please select Package</p>');
	return false;

  }else{
	$('.errorLaborder').hide();
  }

  var names=$('.username').val();

  var userage=$('.userage').val();
  var user_gender=$('.user_gender').val();
  var useremail=$('.useremail').val();
  var usermobile=$('.usermobile').val();
  var pincode=$('.pincodes').val();

  var address=$('.address').val();
  var scheduleTime=$('#scheduleTime').val();
  var scheduleDate=$('.scheduleDate').val();
  var payment_mode_type=$('.payment_mode_type').val();
  



if(names==''){

$('.errorName').html('<p style="color:red">Please Enter Name</p>');
return false;

}else{
	$('.errorName').hide();
}

if(userage==''){

$('.userageError').html('<p style="color:red">Please Enter Age</p>');
return false;

}else{
	$('.userageError').hide();
}

// alert(user_gender);
var cbox = document.forms["myForm"]["gender"];
  if (
    cbox[0].checked == false &&
    cbox[1].checked == false
    
  ) {
	$('.user_genderError').html('<p style="color:red">Please Choose Gender</p>');
    return false;
  }else{
	$('.user_genderError').hide();	 
}

var cbox = document.forms["myForm"]["gender"];
  if (
    cbox[0].checked == false &&
    cbox[1].checked == false
    
  ) {
	$('.user_genderError').html('<p style="color:red">Please Choose Gender</p>');
    return false;
  }else{
	$('.user_genderError').hide();	 
}



if(payment_mode_type==''){
	// alert("blank");

$('.payment_mode_typeError').html('<p style="color:red">Please Select Payment Type</p>');
return false;

}else{
	$('.payment_mode_typeError').hide();	
}



if(useremail==''){
	

$('.useremailError').html('<p style="color:red">Please Provide Mail</p>');
return false;

}else{
	$('.useremailError').hide();
}

if(usermobile==''){

$('.usermobileError').html('<p style="color:red">Please Enter Mobile No</p>');
return false;

}else{
	$('.usermobileError').hide();
}

if(pincode==''){

$('.pincodeError').html('<p style="color:red">Please Enter Pincode </p>');
return false;

}else{
	$('.pincodeError').hide();
}
if(scheduleDate==''){
$('.scheduleDateError').html('<p style="color:red">Please select schedule date </p>');
return false;

}else{
	$('.scheduleDateError').hide();
}

if(scheduleTime==''){
$('.scheduleTimeError').html('<p style="color:red">Please select schedule time </p>');
return false;

}else{
	$('.scheduleTimeError').hide();
}
if(address==''){

$('.addressError').html('<p style="color:red">Please Provide address </p>');
return false;

}else{
	$('.addressError').hide();
}

if(address.length<22){



$('.addressError').html('<p style="color:red">Please Provide full address </p>');
return false;

}else{
	$('.addressError').hide();
}

  jQuery('.loading-all').show();



  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('admin.createLabOrder') !!}",
  data: $('form').serialize(),
  success: function(data){
	
      if (data.lSlotDataRes > '0') {

      }
      else{
	
		jQuery("#scheduleTime").html('<option value="">All Time Slots have been booked for the day</option>');
      }

	  if(data.status==true){
		jQuery('.loading-all').hide();
		             $.alert({
   						title: 'Success!',
   						content: 'Order Created Successfully.',
   						draggable: false,
   						type: 'green',
   						typeAnimated: true,
   					
   					  });

						 
		 window.location.href = '{{ route("admin.patientList",["start_date"=>base64_encode(date("Y-m-d")),"end_date"=>base64_encode(date("Y-m-d"))]) }}';
		 //response_code=591&response_description=Unsuccessful&reference_code=354d6728a9c1ef0&transaction_id=1535121422&trans_type=deposit

	   }
     if (data.type == 2) {
		jQuery('.loading-all').hide();
              $.alert({
   						title: 'Success!',
   						content: 'Link Create Successfully. Please click button to copy link.',
   						draggable: false,
   						type: 'green',
   						typeAnimated: true,
   						buttons: {
   							Copy: function(){
   								copyText(data.data.link);
                  location.reload();
   							},
   							Cancel : function(){
                  location.reload();

   							}
   						}
   					  });
            }

    },
	  error: function (reject) {
                if( reject.status === 400 ) {
                    var errors = $.parseJSON(reject.responseText);
                    $.each(errors, function (key, val) {
						if(val['total_amount']){
						
							$('.lab_testError').html('<p style="color:red; width: 100%;float:left">Please Select Lab name</p>');
						}

                      console.log("================1111",val['total_amount']);
                    });
                }
            }
  });

});

});


jQuery(document).on("change", ".payment_mode_type", function () {
		var type = $(this).find('option:selected').val();
		$(".trackBlock").hide();
		$(".payment_mode_cheque").hide();
		if(type == '2'){
			$(".payment_mode_cheque").show();
		}
		else if(type == '4') {
			$(".trackBlock").show();
		}
	});
	function copyText(text) {
  var input = document.body.appendChild(document.createElement("input"));
  input.value = text;
  input.select();
  document.execCommand('copy');
  input.parentNode.removeChild(input);
}

// jQuery(document).on("submit", "#createOrder", function (e) {
// 	e.preventDefault(e);

// 	var form = $("#createLabOrder");


// });

$('textarea').each(function(){
            $(this).val($(this).val().trim());
        }
    );

});





</script>
@endsection
