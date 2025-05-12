@extends('layouts.admin.Masters.Master')
@section('title', 'Add Subscription Plans')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y slider-master">
			
			<div class="row form-top-row ">
		                	<div class="btn-group instant-subs ">
								<a class="btn btn-success" href="javascript:void();">Online : {{$onlineSubs}}</a>
															</div>
															<div class="btn-group">

									<a class="btn btn-success" href="javascript:void();">Cash : {{$cashSubs}}</a>
								<input type="hidden" value="{{ route('admin.instantSubs') }}" id="subURL"/>
															</div>

							{!! Form::open(array('route' => 'admin.instantSubs', 'id' => 'addSubs', 'class' => 'col-sm-12')) !!}
								<div class="form-group col-sm-6 head-search mt-sm-2">
									<label>Organization:</label>
									<select class="form-control" name="organization" id="organizationSelect">
										<option value="">Select organization any one time</option>
										@if(count($OrganizationList))
											@foreach($OrganizationList as $raw)
												<option value="{{$raw->id}}" @if(@Session::get("admin_org_id") == $raw->id) selected @endif>{{$raw->title}}</option>
											@endforeach
										@endif
									</select>
									<span class="help-block"></span>
								</div>
								<div class="form-group col-sm-6 head-search mt-sm-2">
									<label>Referral Code:</label>
									<select class="form-control" name="ref_code" id="referralCodeSelect">
										<option value="">Select referral code any one time</option>
										@foreach($refCodeData as $raw)
											<option value="{{$raw->id}}" @if(@Session::get("admin_ref_code") == $raw->id) selected @endif >{{$raw->code}}</option>
										@endforeach
									</select>
									<span class="help-block"></span>
								</div>
								<div class="form-group col-sm-3 head-search mt-sm-2">
									<label>User Name:</label>
									<input type="text" class="form-control planTitle" placeholder="Enter User Name" name="user_name">
									<span class="help-block"></span>
								</div>
								<div class="form-group col-sm-3 head-search mt-sm-2">
									<label>Mobile:</label>
									<input type="text" class="form-control planSlug" placeholder="Enter Mobile Number" name="mobile_no"/>
									<span class="help-block"></span>
								</div>
								<div class="form-group col-sm-3 ml-2 mt-sm-2" >
									<label>Payment Type:</label>
									<p class="payment_type">
									<label><input type="radio" name="payment_type" value="cash"/><span class="help-block"></span>Cash</label>
									<label><input type="radio" name="payment_type" value="online"/>Online</label>
									</p>
								</div>
								<div class="form-group raw">
								<input type="hidden" id="orderId" value=""/> 
								</div>
								<div class="form-group col-sm-12 reset-button">
								   <button type="reset" class="btn btn-warning">Reset</button>
								   <button type="submit" class="btn btn-success submit">Submit</button>
								</div>
							 {!! Form::close() !!}


		    </div>






						
				
					   
					   <div class="panel-body panel-body_lab qrCodeImg" style="display:none;">
					   	<div class="ptm-pos-r ptm-subheading ptm-min-height ptm-w100-ib xs-ptm-hidden">
					   	<div class="ptm-merchnt-dtl">
					    <div class="ptm-view-details ptm-header-color">
					    	<img src="/img/homeIconPaytm.png" class="ptm-centralized" alt="mer-logo">
					    	</div>
					   	<span class="ptm-name-txt ptm-header-color ptm-ellipsis">Health Gennie</span>
					   	
					   	</div>
					   	<div class="ptm-logo-name ptm-pos-a ptm-fixheader-logo">
					   	<div class="ptm-logobox ptm-pos-r">
					   	<img src="/img/paytmlogoImg.png" class="ptm-centralized" alt="mer-logo">
					   	</div>
					   	</div>
					   	<div class="PaytmAmmount">
					   		<!--<h2>Payment Gateway</h2>-->
					   		<h2><strong><span class="udet"></span></strong></h2>
					   	</div>
					   	</div>
					   	<div class="qrCodeImgPath123">
					   	<div class="qrCodeImgPathNew">
						<img id="qrCodeImgPath" src=""/>
						</div>
						<div class="ScanPaytm123">
							<div class="ScanPaytm">
								<h2>Scan QR Code</h2>
								<p>Pay with Paytm Wallet or UPI</p>
							</div>
							<div class="UPIappLogo">
								<p>or Scan with any UPI app</p>
								<span><img src="/img/UPILogoImage.png" /></span>
							</div>
						</div>
					   </div>
					   </div>
					  
			</div>
			</div>
			</div>
			</div> 

<script type="text/javascript">
	$(document).ready(function () {
		$('#organizationSelect').change(function () {
			var organizationId = $(this).val();

			if (organizationId) {
				$.ajax({
					type: 'POST',
					url: '{{ route('admin.getReferralCodes') }}',
					data: {
						'organization_id': organizationId
					},
					success: function (data) {
						updateReferralCodeDropdown(data.referralCodes);
					},
					error: function (error) {
						console.error('Error fetching referral codes: ', error);
					}
				});
			} else {
				$('#referralCodeSelect').empty();
			}
		});

		function updateReferralCodeDropdown(referralCodes) {
			let referralCodeSelect = $('#referralCodeSelect');
			referralCodeSelect.empty();
			if(referralCodes.length > 0) {
				referralCodeSelect.append($('<option>', {
					value: '',
					text: 'Select referral code any one time'
				}));
				$.each(referralCodes, function (key, value) {
					referralCodeSelect.append($('<option>', {
						value: value.id,
						text: value.code
					}, {
						value: 'Select'
					}));
				});
			}
			else{
				referralCodeSelect.append($('<option>', {
					value: '',
					text: 'Select referral code any one time'
				}));
			}
		}
	});
   // When the browser is ready...
	jQuery(document).ready(function () {
	 setTimeout(function () {
		loadPaymentStatus();
	}, 5000);
	function loadPaymentStatus(){
		let orderId = jQuery('#orderId').val();
		if(orderId) {
			jQuery.ajax({
				type: "POST",
				data : "JSON",
				data: {'order_id':orderId},
				url: "{!! route('admin.checkPaymentStatusAdmin')!!}",
				success: function(data) {
					if(data.status == 1) {
						document.location.href = "{!! route('admin.instantPlanSuccess')!!}";
					}
					else {
						setTimeout(function() {
							loadPaymentStatus();
						}, 5000);
					}
				},
				error: function(error) {
					if(error.status == 401 || error.status == 419){
						alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						// alert("Oops Something goes Wrong.");
					}
				}
			});
		}
		else{
			setTimeout(function() {
				loadPaymentStatus();
			}, 5000);
		}
	}
    $(document.body).on('click', '.submit', function(){
		jQuery("#addSubs").validate({
	    rules: {
			organization: "required",
			ref_code: "required",
			user_name: "required",
			mobile_no:{required:true,minlength:10,maxlength:10,number: true},
			payment_type: "required"
		},
	    messages: {
	    },
		    errorPlacement: function(error, element) {
		         error.appendTo(element.next());
		    },
		    submitHandler: function(form) {
		  jQuery('.loading-all').show();
		  jQuery('.submit').attr('disabled',true);
			  jQuery.ajax({
				  type: "POST",
				  url: "{!! route('admin.instantSubs')!!}",
				  data:  new FormData(form),
				  contentType: false,
				  cache: false,
				  processData:false,
				  success: function(data) {
					 if(data.type == 1) {
					  jQuery('.submit').attr('disabled',false);
					  document.location.href = $("#subURL").val();
					 }
					 else if(data.type == 3) {
						 alert('User is Already Subscribed');
					 }
					 else if(data.type == 2) {
					  let uname = $('input[name="user_name"]').val();
					  let umob = $('input[name="mobile_no"]').val();
					  jQuery('.udet').text('('+uname+') '+umob);
					  jQuery('.submit').attr('disabled',false);
					  jQuery('.mainSection').hide();
					  jQuery('#orderId').val(data.orderId);
					  jQuery('#qrCodeImgPath').attr('src','data:image/png;base64,'+data.qr_code);
					  jQuery('.qrCodeImg').show();
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
@endsection