@extends('amp.layouts.Masters.Master')
@section('title', 'Cart')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<style media="screen">

.wizard,
.tabcontrol
{
    display: block;
    width: 100%;
    overflow: hidden;
}

.wizard a,
.tabcontrol a
{
    outline: 0;
}

.wizard ul,
.tabcontrol ul
{
    list-style: none !important;
    padding: 0;
    margin: 0;
}

.wizard ul > li,
.tabcontrol ul > li
{
    display: block;
    padding: 0;
}

/* Accessibility */
.wizard > .steps .current-info,
.tabcontrol > .steps .current-info
{
    position: absolute;
    left: -999em;
}

.wizard > .content > .title,
.tabcontrol > .content > .title
{
    position: absolute;
    left: -999em;
}



/*
    Wizard
*/

.wizard > .steps
{
    position: relative;
    display: block;
    width: 100%;
}

.wizard.vertical > .steps
{
    display: inline;
    float: left;
    width: 30%;
}

.wizard > .steps .number
{
    font-size: 1.429em;
}

.wizard > .steps > ul > li
{
    width: 25%;
}

.wizard > .steps > ul > li,
.wizard > .actions > ul > li
{
    float: left;
}

.wizard.vertical > .steps > ul > li
{
    float: none;
    width: 100%;
}

.wizard > .steps a,
.wizard > .steps a:hover,
.wizard > .steps a:active
{
    display: block;
    width: auto;
    margin: 0 0.5em 0.5em;
    padding: 1em 1em;
    text-decoration: none;

    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.wizard > .steps .disabled a,
.wizard > .steps .disabled a:hover,
.wizard > .steps .disabled a:active
{
    background: #eee;
    color: #aaa;
    cursor: default;
}

.wizard > .steps .current a,
.wizard > .steps .current a:hover,
.wizard > .steps .current a:active
{
    background: #fcfcfc;
    color: #111;
    cursor: default;
}

.wizard > .steps .done a,
.wizard > .steps .done a:hover,
.wizard > .steps .done a:active
{
    background: #4CAF50;
    color: #fff;
}

.wizard > .steps .error a,
.wizard > .steps .error a:hover,
.wizard > .steps .error a:active
{
    background: #ff3111;
    color: #fff;
}

.wizard > .content
{
    background: #eee;
    display: block;
    margin: 0.5em;
    min-height: 35em;
    overflow: hidden;
    position: relative;
    width: auto;

    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.wizard.vertical > .content
{
    display: inline;
    float: left;
    margin: 0 2.5% 0.5em 2.5%;
    width: 65%;
}

.wizard > .content > .body
{
    float: left;
    position: absolute;
    width: 95%;
    height: 95%;
    padding: 2.5%;
}

.wizard > .content > .body ul
{
    list-style: disc !important;
}

.wizard > .content > .body ul > li
{
    display: list-item;
}

.wizard > .content > .body > iframe
{
    border: 0 none;
    width: 100%;
    height: 100%;
}

.wizard > .content > .body input
{
    display: block;
    border: 1px solid #ccc;
}

.wizard > .content > .body input[type="checkbox"]
{
    display: inline-block;
}

.wizard > .content > .body input.error
{
    background: rgb(251, 227, 228);
    border: 1px solid #fbc2c4;
    color: #8a1f11;
}

.wizard > .content > .body label
{
    display: inline-block;
    margin-bottom: 0.5em;
}

.wizard > .content > .body label.error
{
    color: #8a1f11;
    display: inline-block;
    margin-left: 1.5em;
}

.wizard > .actions
{
    position: relative;
    display: block;
    text-align: right;
    width: 100%;
}

.wizard.vertical > .actions
{
    display: inline;
    float: right;
    margin: 0 2.5%;
    width: 95%;
}

.wizard > .actions > ul
{
    display: inline-block;
    text-align: right;
}

.wizard > .actions > ul > li
{
    margin: 0 0.5em;
}

.wizard.vertical > .actions > ul > li
{
    margin: 0 0 0 1em;
}

.wizard > .actions a,
.wizard > .actions a:hover,
.wizard > .actions a:active
{
    background: #2184be;
    color: #fff;
    display: block;
    padding: 0.5em 1em;
    text-decoration: none;

    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.wizard > .actions .disabled a,
.wizard > .actions .disabled a:hover,
.wizard > .actions .disabled a:active
{
    background: #eee;
    color: #aaa;
}

.wizard > .loading
{
}

.wizard > .loading .spinner
{
}



/*
    Tabcontrol
*/

.tabcontrol > .steps
{
    position: relative;
    display: block;
    width: 100%;
}

.tabcontrol > .steps > ul
{
    position: relative;
    margin: 6px 0 0 0;
    top: 1px;
    z-index: 1;
}

.tabcontrol > .steps > ul > li
{
    float: left;
    margin: 5px 2px 0 0;
    padding: 1px;

    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}

.tabcontrol > .steps > ul > li:hover
{
    background: #edecec;
    border: 1px solid #bbb;
    padding: 0;
}

.tabcontrol > .steps > ul > li.current
{
    background: #fff;
    border: 1px solid #bbb;
    border-bottom: 0 none;
    padding: 0 0 1px 0;
    margin-top: 0;
}

.tabcontrol > .steps > ul > li > a
{
    color: #5f5f5f;
    display: inline-block;
    border: 0 none;
    margin: 0;
    padding: 10px 30px;
    text-decoration: none;
}

.tabcontrol > .steps > ul > li > a:hover
{
    text-decoration: none;
}

.tabcontrol > .steps > ul > li.current > a
{
    padding: 15px 30px 10px 30px;
}

.tabcontrol > .content
{
    position: relative;
    display: inline-block;
    width: 100%;
    height: 35em;
    overflow: hidden;
    border-top: 1px solid #bbb;
    padding-top: 20px;
}

.tabcontrol > .content > .body
{
    float: left;
    position: absolute;
    width: 95%;
    height: 95%;
    padding: 2.5%;
}

.tabcontrol > .content > .body ul
{
    list-style: disc !important;
}

.tabcontrol > .content > .body ul > li
{
    display: list-item;
}

#MyCartPage input[type="text"],
#MyCartPage input[type="email"],
#MyCartPage input[type="tel"],
#MyCartPage input[type="url"],
#MyCartPage textarea,
#MyCartPage button[type="submit"] {
  font: 400 12px/16px "Titillium Web", Helvetica, Arial, sans-serif;
}

#MyCartPage {
  background: #F9F9F9;
  padding: 25px;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}

#MyCartPage h3 {
  display: block;
  font-size: 30px;
  font-weight: 300;
  margin-bottom: 10px;
}

#MyCartPage h4 {
  margin: 5px 0 15px;
  display: block;
  font-size: 13px;
  font-weight: 400;
}

#MyCartPage input[type="text"],
#MyCartPage input[type="email"],
#MyCartPage input[type="tel"],
#MyCartPage input[type="url"],
#MyCartPage textarea {
  width: 100%;
  border: 1px solid #ccc;
  background: #FFF;
  margin: 0 0 5px;
  padding: 10px;
}

#MyCartPage input[type="text"]:hover,
#MyCartPage input[type="email"]:hover,
#MyCartPage input[type="tel"]:hover,
#MyCartPage input[type="url"]:hover,
#MyCartPage textarea:hover {
  -webkit-transition: border-color 0.3s ease-in-out;
  -moz-transition: border-color 0.3s ease-in-out;
  transition: border-color 0.3s ease-in-out;
  border: 1px solid #aaa;
}

#MyCartPage textarea {
  height: 100px;
  max-width: 100%;
  resize: none;
}

#MyCartPage button[type="submit"] {
  cursor: pointer;
  width: 100%;
  border: none;
  background: #4CAF50;
  color: #FFF;
  margin: 0 0 5px;
  padding: 10px;
  font-size: 15px;
}

#MyCartPage button[type="submit"]:hover {
  background: #43A047;
  -webkit-transition: background 0.3s ease-in-out;
  -moz-transition: background 0.3s ease-in-out;
  transition: background-color 0.3s ease-in-out;
}

#MyCartPage button[type="submit"]:active {
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
}



#MyCartPage input:focus,
#MyCartPage textarea:focus {
  outline: 0;
  border: 1px solid #aaa;
}

.steps > ul > li > a,
.actions li a {
    padding: 10px;
    text-decoration: none;
    margin: 1px;
    display: block;
    color: #777;
}
.steps > ul > li,
.actions li {
    list-style:none;
}

</style>
<?php //pr($code);
    $LabCart = getSubscriptionLabData($code);
?>
<div class="lab-test lab-test-profile">
  <div class="container-fluid">
    <div class="container">
      <div class="tab-cart-wrapper">
        @if(!empty($LabCart))
        <div class="tabs-cart">
          <form id="createLabOrder" method="post" action="{{ route('createLabOrder')}}">
              <div class="order-overview">
                <div class="left">
                  <div id="MyCartPage">
                      <div>
                          <h3>My Avail Plan</h3>
                          <section>
                            <table cellpadding="0" cellspacing="0">
                              <thead>
                                <tr>
                                  <td></td>
                                  <td><strong>Product</strong></td>
                                  <!--<td><strong>Price</strong></td>
                                  <td></td>-->
                                </tr>
                              </thead>
                              <tbody id="cartItems">
                                <input type="hidden" id="finalProducts" name="final_products" value="">
                                <?php  $totalAmount = 0; $totalDiscount = 0; $offerAmount=0; ?>
                              @if(!empty($LabCart))
                              @foreach($LabCart as $package)

                              <tr>
                                <?php $totalAmount += $package['rate']['b2c'];
                                    $main = $package['rate']['b2c'];
									if ($package['rate']['offer_rate'] != "null") {
									  $offerAmount += $package['rate']['offer_rate'];
									  $totalDiscount += $main-$package['rate']['offer_rate'];
									}
									else {
									  $offerAmount += $package['rate']['b2c'];
									  $totalDiscount += "0.00";
									}
									$plan_data = "";
									$meta_data = @$plan->UserSubscribedPlans->meta_data;
									if(!empty($meta_data)){
										$plan_data = json_decode($meta_data); 
									}
                                ?>
                                <td align="center"><img src="img/OurStore-icon.png" /></td>
                                <td> <a href="javascript:void(0);" class="cartProduct" product="{{$package['name']}}">{{@$plan_data->lab_pkg_title}} </a> </td>
                                <!--<td>₹   @if($package['rate']['offer_rate'] != 'null') {{$package['rate']['offer_rate']}} @else {{$package['rate']['b2c']}}  @endif</td>-->
                              </tr>

                              @endforeach
                              @endif
                                </tbody>
                            </table>
                          </section>
                          <h3>Personal Info</h3>
                          <section>
                            <div class="patient-details">
                              <input type="hidden" name="status" value="0">
                              <input type="hidden" name="order_status" value="0">
                              <div class="input-wrapper">
                                <label>Full Name<i class="required_star">*</i></label>
                              <input type="text" placeholder="Name" name="name" value="{{$user->first_name.' '.$user->last_name}}" />
                              <span class="help-block"></span>
                              </div>
                              <?php
                                //date in mm/dd/yyyy format; or it can be in other formats as well
                                $birthDate = date('m/d/Y', $user->dob);
                                //explode the date to get month, day and year
                                $birthDate = explode("/", $birthDate);
                                //get age from date or birthdate
                                $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
                                  ? ((date("Y") - $birthDate[2]) - 1)
                                  : (date("Y") - $birthDate[2]));
                              ?>
							  <div class="input-wrapper">
								  <label>Age (Year)<i class="required_star">*</i></label>
								  <input type="text" placeholder="Age" name="age" class="nummber NumericFeild"  value="{{$age}}" />
								  <span class="help-block"></span>
							  </div>

							  <div class="radio-wrapper waitingTime top">
								  <label>Gender<i class="required_star">*</i></label>
								  <p><input id="gender1" type="radio" name="gender" id="test8" value="M" @if($user->gender == "Male") checked @endif><label for="gender1">Male</label><span class="help-block"></span></p>
								  <p><input id="gender2" type="radio" name="gender" id="test9" value="F" @if($user->gender == "Female") checked @endif ><label for="gender2">Female</label></p>
								  <span class="help-block"></span>
							  </div>
                              <div class="input-wrapper">
                                <label>E-Mail<i class="required_star">*</i></label>
                              <input type="text" placeholder="E-mail" name="email" value="{{$user->email}}" />
                              <span class="help-block"></span>
                              </div>
                              <div class="input-wrapper">
                                <label>Mobile Number<i class="required_star">*</i></label>
                                <input type="text" placeholder="Mobile Number" name="mobile" class="NumericFeild" name="email" value="{{$user->mobile_no}}" />
                                <span class="help-block"></span>
                              </div>
                            </div>
                          </section>
                          <h3>Address</h3>
                          <section>
                            <div class="address-wrapper AddressBox waitingTime">
                              @if(count($addresses) > 0)
                              @php $i = 1; @endphp
                                @foreach($addresses as $address)
                                <div class="address-box @if($i == 1) active @endif" lable-type="{{$address->label_type}}">
								  <p class="coupon-wrapper">
									  <input type="radio" id="address_radio_{{$address->id}}" value="{{$address->id}}" name="address_id" class="selectAddress" code="{{$address->pincode}}" @if($i == 1) checked @endif />
									  <label for="address_radio_{{$address->id}}"></label>
								  </p>
                                 <div class="delete-address">
                                   <a href="javascript:void(0)" title="Delete Address" data-id="{{$address->id}}" class="addressDeleteNow" click-delete="0"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                 </div>
                                  <div class="float">
                                    <?php
                                      if ($address->label_type == 1) {
                                        $label_name = "Home";
                                      }
                                      elseif ($address->label_type == 2) {
                                        $label_name = "Office";
                                      }
                                      else {
                                        $label_name = $address->label_name;
                                      }
                                     ?>
                                    <div class="label-name">
                                        {{$label_name}}
                                    </div>
                                    <div class="address-area">{{$address->address}}, {{$address->locality}}, {{$address->landmark}}, {{$address->pincode}}</div>
                                  </div>
                                </div>
                                @php $i++; @endphp
                                @endforeach
                              @endif
                              <div class="emptyAddress" style="display: @if(count($addresses) > 0) none @else block @endif">
                                <h3>No Address Available !</h3>
                                <strong class="addressEmptyMsg" style="display:none;"></strong>
                              </div>
                            </div>
                            <div class="btn-add-address"><a href="javascript:void(0)" class="addNewAddress">Add New Address</a></div>
							<div class="addAddressDiv waitingTime" style="@if(count($addresses) <= 0) display:block; @else display:none; @endif">
                              <div id="addAddressForm" class="form-address-details">
                                <div class="input-wrapper">
                                  <label>Address<i class="required_star">*</i></label>
                                  <input type="text" placeholder="Address" class="inputvalidation" name="address" maxlength="100"  value="{{$user->address}}" />
                                  <span class="help-block"></span>
                                </div>
                                <div class="input-wrapper">
                                  <label>Pincode<i class="required_star">*</i></label>
                                  <input type="text" placeholder="Pincode" class="NumericFeild inputvalidation" id="checkPincode" name="pincode" maxlength="6" value="" />
                                  <div class="icon-container inputBoxLoader" style="display:none">
                                     <i class="loader"></i>
                                   </div>
                                  <span class="help-block"></span>
                                </div>
                                <div class="input-wrapper">
                                  <label>Locality<i class="required_star">*</i></label>
                                  <input type="text" placeholder="Locality" name="locality" class="inputvalidation" maxlength="50" value="{{@$user->CityLocalities->name}}" />
                                  <span class="help-block"></span>
                                </div>
                                <div class="input-wrapper">
                                  <label>Landmark<i class="required_star">*</i></label>
                                  <input type="text" placeholder="Landmark" name="landmark" class="inputvalidation" maxlength="44" value="{{@$user->CityLocalities->name}}" />
                                  <span class="help-block"></span>
                                </div>
                                <div class="input-wrapper labelName" style="display:none;">
                                  <label>Name<i class="required_star">*</i></label>
                                  <input type="text" placeholder="Name" name="label_name" value="" />
                                  <span class="help-block"></span>
                                </div>
                                <div class="radio-wrapper bottom">

                                  <p><input id="label_type_1" type="radio" name="label_type" class="labelType" value="1" checked><label for="label_type_1">Home</label></p>

                                  <p><input id="label_type_2" type="radio" name="label_type" class="labelType" value="2"><label for="label_type_2">Office</label></p>

                                  <p><input id="label_type_3" type="radio" name="label_type" class="labelType" value="3"><label for="label_type_3">Other</label></p>
                                  <span class="help-block"></span>
                                </div>
                                <button id="saveAddress" type="button"  class="formSubmit btn-add-address">Save</button>
                              </div>
                            </div>
                          </section>
                          <h3>Schedule</h3>
                          <section>
                            <div class="address-wrapper">
                              <div class="input-wrapper">
                                <label>Date<i class="required_star">*</i></label>
                                <div class="date-formet-section">
								<input type="text" class="scheduleDate" name="appt_date"  placeholder="yyyy-mm-dd" readonly />
								<i class="fa fa-calendar" aria-hidden="true"></i>
							  </div>
                              </div>
                              <div class="input-wrapper">
                                <label>Time<i class="required_star">*</i></label>
                                <select name="appt_time" id="scheduleTime" class="form-control">
									<option value="">Select Schedule Time</option>
								 </select>
								  <span class="help-block"><label for="speciality" generated="true" class="error" style="display:none;"></label></span>
                              </div>
                            </div>
                          </section>
                          <h3>Payment</h3>
                          <section>
                            <div class="paymentSection">
							<div class="form-group">
								<h4 class="text-success">Elite membership lab charges free.This lab test will not be cancelled.</h4>
							</div>
							<!--
                              <div class="form-group" data-toggle="buttons">
                                  <label class="btn btn-default btn-lg toggle-checkbox primary payMode">
                                      <i class="fa fa-fw"></i>
                                      <input id="one" autocomplete="off" class="pay_type" name="pay_type" value="Postpaid" type="checkbox" />
                                      Pay Later
                                  </label>
                              </div>

                             <div class="form-group" data-toggle="buttons">
                                  <label class="btn btn-default btn-lg toggle-checkbox primary payMode">
                                      <i class="fa fa-fw"></i>
                                      <input id="two" autocomplete="off" class="pay_type" name="pay_type" value="Prepaid" type="checkbox" />
                                      Online
                                  </label>
                              </div> -->
                              <span class="help-block"></label><label class="error payModeError" style="display:none; color:red;">Please Choose Payment Mode</label></span>
                            </div>
                          </section>
                      </div>
                  </div>
                </div>
                <div class="right">
                  <!--<div class="right-block save-block last waitingTime">
                    <h3>Report Type</h3>
                    <div class="coupan-list ReportType">
                      <div class="list ">
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
                  <div class="right-block save-block">
                    <div class="form-address-details CouponBox divForHide">
                      <div class="input-box">
                        <input type="text" placeholder="Enter Coupon" class="couponInput" id="couponInputCode" value="" />
                        <input type="hidden" name="coupon_id" id="coupanId" value="">
                        
                      </div>
                      <button id="coupanApply" type="button" class="btn-add-address">Apply</button>
                      <strong class="CouponAvailableMsg" style="display:none;"></strong>
                    </div>
                    <div class="coupanApplyedBox" style="display:none;">
                      <div class="save-icon"><img width="13" height="14" src="{{asset('img/right-icon.png')}}" /> You saved <strong>₹</strong><strong class="applyCouponAmount"></strong></div>
                      <p><strong class="applyCouponCode">DIWALIGIFT:</strong> <span class="applyCouponText"></span> </p>
                      <div class="remove-icon"><a href="javascript:void(0)" class="removeCoupan">Remove</a> </div>
                    </div>
                  </div>-->

                  <div class="right-block">
				   <input type="hidden" name="prod_code" value="{{$code}}" >
				   <input type="hidden" name="plan_id" value="{{$plan_id}}" >
				   <input type="hidden" name="report_type" value="no" >
				   <input type="hidden" name="coupon_id" id="coupanId" value="">
				    <input name="pay_type" value="Prepaid" type="hidden" />
				    <input type="hidden" name="coupon_code" value="HGSUBSCRIBED"> 
                    <input type="hidden" class="" id="totalAmount" name="total_amount" value="{{base64_encode($offerAmount)}}">
                    <input type="hidden" class="" id="priceDiscount" name="discount_amt" value="">
                    <input type="hidden" class="" id="paidAmount2" name="paid_amount" value="{{base64_encode($offerAmount)}}">
                    <input type="hidden" class="" id="coupanDiscount" name="coupan_discount" value="">
                    <input type="hidden" class="" id="coupanDiscountAmount" name="coupon_amt" value="">
                    <input type="hidden" class="" id="reportHardCopy" name="hard_copy" value="">
                    <input type="hidden" class="" id="paidAmount" name="payable_amt" value="{{base64_encode($offerAmount)}}">
                    <input type="hidden" class="" id="totalSaving" name="total_saving" value="{{base64_encode($totalDiscount)}}">

                    <ul>
                      <li>Total  <div class="price-tag-wrap"><span>₹</span><span class="totalAmount">0.00</span></div></li>
                      <!-- <li>Price Discount <div class="price-tag-wrap"><span>- ₹</span><span class="priceDiscount">0.00</span></div></li>
                     <li>Coupon Discount <div class="price-tag-wrap"><span>- ₹</span><span class="coupanDiscountAmount">0.00</span></div></li>-->
                      <li class="total-bill">Pay Amount <div class="price-tag-wrap"> <span>₹</span><span class="paidAmount">0.00</span></div></li>
                    </ul>
                   <!-- <div class="total-save" style="display:none;">Total Savings <div class="total-price"><strong>₹</strong><strong class="totalSaving">{{$totalDiscount}}</strong></div></div>-->
                  </div>
                  <!--<div class="right-block last-box alreadyAdded" style="display:none;">
                    <div class="include-messages">
                      <p>" <span class="IncludedTest"></span> Included In <span class="IncludedInTest"></span> "</p>
                    </div>
                  </div>-->
                </div>
              </div>
          </form>
        </div>
        @else
        <div class="cartEmpty">
            <div class="cart-empty-msg">
            	<img src="img/empty-cart.png" />
              <h3>Lab Cart is Empty!</h3>
              <p>Looks like you have no lab test in your cart yet.</p>
              <div class="lab-dashboard"><a href="{{route('LabDashboard')}}" class="lab-dashboard-button"><strong> < Book Lab Test</strong></a></div>
            </div>
        </div>
        @endif
      </div>


    </div>
  </div>
</div>
    <script src=" {{ URL::asset('js/jquery.steps.js') }}"></script>
<script type="text/javascript">
var mainDiv = $("#MyCartPage");
var form = $("#createLabOrder");
  form.validate({

    rules: {

      name	: "required",
      gender: "required",
      age: {
        required: true,
        min: 1,
        max: 100,
      },
      email: {
        required: true,
        minlength: 10,
        maxlength: 50,
      },
      address_id: "required",
      appt_time: "required",
      mobile: {
        required: true,
        minlength: 10,
        maxlength: 10,
      },
    },
    // Specify the validation error messages
    messages: {
     //   company_id: "Please Select a Company.",
        // item_type: "Please Select Product Type",
        // unit: "Please Select Unit",
        // strength:{"required": "Please enter Strength","number": "Please enter Strength in Numeric."},
        // item_name: "Please enter Drug Name",
        // hsn: "Please enter HSN Code",
        // gst: "Please Select GST.",
        //standards: "Please Select Standards"
    },
    errorPlacement: function(error, element) {
       // $(element).css({"color": "red", "border": "1px solid red"});
      error.appendTo($(element).parent().find('.help-block'));




    },
    submitHandler: function(form_data) {


            jQuery('.loading-all').show();
            jQuery('#saveAddress').attr('disabled',true);
            jQuery.ajax({
            type: "POST",
            url: $(form_data).attr('action'),
            data:  new FormData(form_data),
			contentType: false,
            processData:false,
            success: function(data) {
					if(data.status == '0') {
						jQuery('.loading-all').hide();
						console.log(data.output.RESPONSE);
						 $.alert({
							title: 'Alert!',
							draggable: false,
							content: data.output.RESPONSE,
						});
					}
					else if(data.status == '1') {
						 console.log(data);
						 var url = '{!! url("/labCheckoutOrder?tid='+data.tid+'&order_id='+data.order_id+'&amount='+data.amount+'&merchant_param1='+data.merchant_param1+'&merchant_param2='+data.merchant_param2+'&merchant_param3='+data.merchant_param3+'&merchant_param4='+data.merchant_param4+'") !!}';
						 //window.location.href = '{{route("labCheckoutOrder",'+data+')}}'; //using a named route
						 window.location = url; //using a named route
					}
					else{
					  console.log(data);
					  window.location.href = '{{route("orderSuccess")}}';
					}
               },
                error: function(error)
                {
                  if(error.status == 401)
                  {
                     // alert("Session Expired,Please logged in..");
                      location.reload();
                  }
                  else
                  {
                    jQuery('.loading-all').hide();
                    //alert("Oops Something goes Wrong.");
                    jQuery('#saveAddress').attr('disabled',false);
                  }
                }
             });
    }
});
mainDiv.children("div").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    onStepChanging: function (event, currentIndex, newIndex)
    {
      // alert(currentIndex);

      if (currentIndex > newIndex)
       {
           return true;
       }
      if (currentIndex == 2) {
        if ($('.AddressBox .address-box').length == 0) {

            $('.addressEmptyMsg').show();
            $('.addressEmptyMsg').text("Please Add Address First");
            $('.addressEmptyMsg').css("color", 'red');
            return false;
        }
        else {
          return true;
        }
      }
      if (newIndex == 4) {
        $(".actions ul li").each(function(){
            if ($(this).find('a').text() == "Finish") {

              $(this).find('a').text("Place Order");

            }
        });
      }
      form.validate().settings.ignore = ":disabled,:hidden";
      return form.valid();


    },
    onFinishing: function (event, currentIndex)
    {
      var pay_type = $('.pay_type:checked').length;
	  return true;
       // if(!pay_type){
         // $('.payModeError').show();
           // return false;
       // }else {
         // return true;
       // }

    },
    onFinished: function (event, currentIndex)
    {
        $("#createLabOrder").submit();
    }
});


function viewCart(reportType) {
  var codess = '{{$code}}' ;
  jQuery('.loading-all').show();
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('AvailViewCartAPI') !!}",
  data: {'report_type':reportType,'code':codess},
  success: function(data)
      {
        console.log(data);
        var products = data.PRODUCT;
        $('#finalProducts').val(data.PRODUCT);
        var alreadyAdded = false;
        var Includedtest = [];
          $(".cartProduct").each(function(){
             currentAttr = $(this).attr('product');
              if(jQuery.inArray(currentAttr, products) == -1){
                Includedtest.push(currentAttr);
                alreadyAdded = true;
              }
          });
          if (alreadyAdded == true) {
            $('.alreadyAdded').show();
          }

          Includedtest = Includedtest.toString();

        $('.IncludedTest').text(Includedtest.replace(/,/g, ", "));
        $('.IncludedInTest').text(data.PRODUCT);

        $('#paidAmount').val(btoa(data.PAYABLE));
        $('#paidAmount2').val(btoa(data.PAYABLE));

        var totalAmount = atob($('#totalAmount').val()); 
        var reportHardCopy = $(".report_type:checked").val();
        if (reportHardCopy != "yes") {
			//$('.totalAmount').text(totalAmount);
			$('#priceDiscount').val(btoa(totalAmount - data.PAYABLE));
			//$('.priceDiscount').text(totalAmount - data.PAYABLE);
        }
		else{
			//$('.totalAmount').text(parseInt(totalAmount) + 75);
		}


        $('#paidAmount2').val(btoa(data.PAYABLE));
        $('.paidAmount').text();
        jQuery('.loading-all').hide();
        paymentCalculate(4);
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
}



jQuery(document).ready(function () {
  $(window).load(function(){
    if ($('#cartItems tr').length > '0') {
      viewCart('no');
    }
  });
});

jQuery(document).ready(function () {
  jQuery(document).on("click", ".payMode", function () {
    $('.payMode').removeClass('active');
    $('.payMode').parent().parent().find('.pay_type').prop('checked', false);
    $(this).parent().find('.pay_type').prop('checked', true);
    $('.payModeError').hide();
  });

    $("#wizard").steps({
        headerTag: "h2",
        bodyTag: "section",
        transitionEffect: "none",
        enableFinishButton: false,
        enablePagination: false,
        enableAllSteps: true,
        titleTemplate: "#title#",
        cssClass: "tabcontrol"
    });

	jQuery(document).on("keyup paste", "#checkPincode", function () {
        var pincode = $(this).val(); alert(pincode);
        var current = this;
        if (pincode.length != 6) {
          $(current).parent().find('.help-block').find('label').hide();
        }
        if (pincode.length == 6) {
          $('.inputBoxLoader').show();
          jQuery('#saveAddress').attr('disabled',true);
          jQuery.ajax({
          type: "POST",
          url: "{!! route('checkPincodeAvailability') !!}",
          data: {'pincode':pincode},
          success: function(data){
              if(data==1)
              {
                jQuery('#saveAddress').attr('disabled',false);
                $(current).parent().find('.help-block').find('label').hide();
                $(current).parent().find('.help-block').append('<label class="error" style="display:block; color:green;">Service Available</label>');
                $('.inputBoxLoader').hide();
              }
              else{
                jQuery('#saveAddress').attr('disabled',true);
                $(current).parent().find('.help-block').find('label').hide();
                $(current).parent().find('.help-block').append('<label class="error" style="display:block; color:red;">Service Not Available</label>');
								$(current).val('');
                $('.inputBoxLoader').hide();

              }
            }
          });
        }
        else{
          $(current).parent().find('.help-block').append('<label class="error" style="display:block; color:red;">This field is required</label>');
        }

    });

});


jQuery(document).ready(function () {

$( ".scheduleDate" ).datepicker({
	dateFormat: 'dd-MM-yy',
	minDate: 0,
	changeMonth: true,
    changeYear: true,
 });
$('.scheduleDate').datepicker('setDate', 'today');
});

function cuteHide(el) {
  el.animate({opacity: '0'}, 150, function(){
    el.animate({height: '0px'}, 150, function(){
      el.remove();
    });
  });
}
function switchButton() {
  var tab1 = $('#wizard-t-0').parent().attr('aria-selected');
  var tab2 = $('#wizard-t-1').parent().attr('aria-selected');
  var tab3 = $('#wizard-t-2').parent().attr('aria-selected');
  if (tab1 == 'true') {
    $('.scheduleButton').find('strong').text('Schedule Order')
    $('.scheduleButton').removeClass('payNow')
    $('#payment_tab').val(0);
  }
  else if (tab2 == 'true') {
    $('.scheduleButton').find('strong').text('Proceed Payment')
    $('.scheduleButton').addClass('payNow')
    $('#payment_tab').val(0);
  }

  if (tab3 == 'true') {
    $('#payment_tab').val(1);
  }
}
function billCalculate(currentRow) {

}
function coupanDiscountFun(coupanDiscount, type) {
  var paidAmount = atob($('#paidAmount2').val());
  var priceDiscount = atob($('#priceDiscount').val());
  var coupanDiscountAmount = paidAmount * coupanDiscount / 100;
  var totalSaving = atob($('#totalSaving').val());
  var reportHardCopy = atob($('#reportHardCopy').val());
  if (type == 1) {
    if (coupanDiscountAmount != "") {
      $('#coupanDiscountAmount').val(btoa(coupanDiscountAmount));
      $('.coupanDiscountAmount').text(coupanDiscountAmount);

    }
    else {
      $('.coupanDiscountAmount').text('0.00');
    }

    paidAmount = paidAmount - coupanDiscountAmount;

    if (priceDiscount != "" && priceDiscount > "0") {
      $('#totalSaving').val(btoa(parseInt(totalSaving) + parseInt(coupanDiscountAmount)));
      $('.totalSaving').text(parseInt(totalSaving) + parseInt(coupanDiscountAmount));
    }
    else{
      $('#totalSaving').val(btoa(coupanDiscountAmount));
      $('.totalSaving').text(coupanDiscountAmount);
    }
    $('.coupanApplyedBox').find('.save-icon').find('.applyCouponAmount').text(coupanDiscountAmount);
  }
  if (type == 2) {
      paidAmount = parseInt(paidAmount);
      $('#totalSaving').val(btoa(parseInt(totalSaving) - parseInt(coupanDiscountAmount)));
      $('.totalSaving').text(parseInt(totalSaving) - parseInt(coupanDiscountAmount));
  }

  return paidAmount;
}

function paymentCalculate(type, currentRow) {
  var reportHardCopy = $(".report_type:checked").val();
  var totalAmount = atob($('#totalAmount').val());
  var priceDiscount = atob($('#priceDiscount').val());
  var paidAmount = atob($('#paidAmount').val());
  var paidAmount2 = atob($('#paidAmount2').val());
  var coupanDiscount = atob($('#coupanDiscount').val());
  var coupanDiscountAmount = atob($('#coupanDiscountAmount').val());
  var totalSaving = atob($('#totalSaving').val());

  if (type == 1) {
    var thisOfferPrice = $(currentRow).parent().find('.offerPrice').val();
    var thisPackagePrice = $(currentRow).parent().find('.packagePrice').val();
    if (thisOfferPrice > 0 || thisOfferPrice != "" ) {
      var thistotalAmount = thisPackagePrice - thisOfferPrice;
    }
    else {
      var thistotalAmount = thisPackagePrice
    }

    $('#totalAmount').val(btoa(totalAmount-thistotalAmount));

    paidAmount = parseInt(paidAmount2) - (parseInt(thisPackagePrice) - parseInt(thisOfferPrice));

    $('#paidAmount2').val(btoa(paidAmount));

    $('#totalSaving').val(btoa((totalSaving) -(thisOfferPrice)));
    //$('.totalAmount').text(totalAmount-thistotalAmount);
    //$('.paidAmount').text(paidAmount);
    $('.totalSaving').text((totalSaving) -(thisOfferPrice));
    if (coupanDiscount != "") {
        paidAmount = coupanDiscountFun(coupanDiscount, 1);
    }

  }

if (type == 2) {
  paidAmount = coupanDiscountFun(coupanDiscount, 1);
}
if (type == 3) {
  paidAmount = coupanDiscountFun(coupanDiscount, 2);
}

if (type == 4) {
  if (reportHardCopy == 'yes') {
    saveAmount = (paidAmount) * (coupanDiscount) / 100;
    paidAmount = (paidAmount) - (saveAmount);
    coupanDiscountFun(coupanDiscount, 1)
  }
  else {
    saveAmount = (paidAmount) * (coupanDiscount) / 100;
    paidAmount = (paidAmount) - (saveAmount);

    coupanDiscountFun(coupanDiscount, 1)
  }
}

$('#paidAmount').val(btoa(paidAmount));
//$('.paidAmount').text(paidAmount);

}



function GetAppointmentSlots(pincode, schedule_date) {
  var scheduleTime = $('#scheduleTime');
  scheduleTime.empty();
  jQuery("#scheduleTime").prepend($('<option value=""></option>').html('Loading...'));
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('GetAppointmentSlots') !!}",
  data: {'pincode':pincode, schedule_date:schedule_date},
  success: function(data){
      console.log(data.LSlotDataRes.length);
      if (data.LSlotDataRes > '0') {
        jQuery("#scheduleTime").html('<option value="">Select Schedule Time</option>');
        jQuery.each(data.LSlotDataRes,function(index, element) {
			   let slot_time = element.Slot.split("-");
			scheduleTime.append(jQuery('<option>', {
             value: slot_time[0],
             text : element.Slot,
             data_id : JSON.stringify(element)
          }));
        });
      }
      else{
        jQuery("#scheduleTime").html('<option value="">All Time Slots have been booked for the day</option>');
      }

    },
    error: function(error)
    {
      if(error.status == 401)
      {
          location.reload();
      }
      else
      {
        jQuery('.loading-all').hide();
        jQuery('#saveAddress').attr('disabled',false);
      }
    }
  });
}
function LabCart(current, product_array, action_type) {
  jQuery('.loading-all').show();
  jQuery.ajax({
  type: "POST",
  url: "{!! route('CartUpdate') !!}",
  data: {'product_array':product_array,'action_type':action_type},
  success: function(data){

      if(data==2)
      {
        jQuery('.loading-all').hide();
        $(current).closest('tr').remove();
        if ($('#cartItems tr').length != '0') {
          var reportHardCopy = $(".report_type:checked").val();
          viewCart(reportHardCopy);
        }
        if ($('#cartItems tr').length == '0') {
          setTimeout(function(){
            window.location = "{{ route('LabCart') }}";
          }, 300);
        }
      }
      else
      { jQuery('.loading-all').hide();
        alert("Problem into Cart");
      }
    }
  });
}

function ApplyCoupon(couponcode) {
  jQuery('#coupanApply').attr('disabled',true);
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('ApplyCoupon') !!}",
  data: {'couponcode':couponcode},
  success: function(data){
		if (data != "") {
      $('#coupanDiscount').val(btoa(data.coupon_rate))
      $('#couponCode').val(data.coupon_code)

      paymentCalculate(2);
			
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
          location.reload();
      }
      else
      {
        jQuery('.loading-all').hide();
        jQuery('#coupanApply').attr('disabled',false);
      }
    }
  });
}
$("#couponInputCode").on("keyup", function(){
	$('.CouponAvailableMsg').slideUp();
	$('.CouponAvailableMsg').text('');
	$('.CouponAvailableMsg').css("color", "");
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

jQuery(document).on("click", ".removeCoupan", function () {
  var  coupanDiscount =  parseInt($('#coupanDiscountAmount').val());
  paymentCalculate(3);
  $('#couponInputCode').val('');
  $('#coupanDiscount').val('');
  $('#coupanDiscountAmount').val('');
  $('#coupanId').val('')
  $('.coupanDiscountAmount').text('0.00')

});

jQuery(document).on("click", ".scheduleButton", function () {
  var tab3 = $('#payment_tab').val();
  if (tab3 == 0) {
    $('#wizard-t-1').click();
    switchButton();
  }
});
jQuery(document).on("click", "#wizard .steps ul li", function () {

  switchButton();
});
jQuery(document).on("change", ".labelType", function () {
  if (this.value == 3) {
      $('.labelName').slideDown();
      $("input[name~='label_name']").addClass('inputvalidation')
  }
  else{
      $('.labelName').slideUp();
      $("input[name~='label_name']").removeClass('inputvalidation')
  }
});
jQuery(document).on("click", ".addNewAddress", function () {
  $('#label_type_1').prop('checked', true);
  $('.labelName').slideUp();
  $('.addAddressDiv').slideToggle();

});
jQuery(document).on("click", ".applyThisCoupan", function () {
  $('.divForHide').slideToggle();
  $('.coupanApplyedBox').slideToggle();
});
jQuery(document).on("click", ".removeCoupan", function () {
  $('.divForHide').slideToggle();
  $('.coupanApplyedBox').slideToggle();
  $('.applyThisCoupan').prop('checked', false);
});
jQuery(document).on("click", ".deleteFromCart", function () {

    var selectPackage = [];
    var pname = $(this).attr('Pname');
    var pcode = $(this).attr('Pcode');
    selectPackage.push({pname:pname,pcode:pcode});

    LabCart(this, selectPackage, 'remove_item');


    current = $(this);
    paymentCalculate(1, current);

    var el =   $(this).closest('tr');
    cuteHide(el);


    var cartTotal = jQuery('#cartTotal').text();
    jQuery('#cartTotal').text(parseFloat(cartTotal)-1);
    jQuery('.totalTest').text(parseFloat(cartTotal)-1);
    var data_name = $(this).attr('Pname');
    $("#miniCartList .list").each(function(){
      if ($(this).attr("data-name") == data_name) {
        $(this).remove();
      }
    });
    if ($("#miniCartList .list").length == '0') {
      $("#miniCart").css("display", "none");
    }


});
jQuery(document).on("change", ".scheduleDate", function () {
  var pincode = $(".selectAddress:checked").attr('code');
  var schedule_date = $(this).val();
  var addressCount = $('.AddressBox .address-box').length;



  if ($('.AddressBox .address-box').length > 0) {

    GetAppointmentSlots(pincode, schedule_date);
    $('.addressEmptyMsg').hide();
    $('.addressEmptyMsg').text('');
  }
  else{
      $('.addressEmptyMsg').show();
      $('.addressEmptyMsg').text("Please Add Address First");
    $('.addressEmptyMsg').css("color", 'red');
  }
});

jQuery(document).ready(function () {

  $(window).load(function(){
    var schedule_date = $('.scheduleDate').val();
    var pincode = $(".selectAddress:checked").attr('code');
    setTimeout(function(){
      if ($('.AddressBox .address-box').length > 0) {
          GetAppointmentSlots(pincode, schedule_date);
        }
    }, 3000);
  });


});

jQuery(document).on("click", ".address-box", function () {
  var clickDelete = $(this).find('.delete-address').find('.addressDeleteNow').attr('click-delete');
  if (clickDelete == "0") {
    $('.address-box').removeClass('active');
    $(this).addClass('active');
    $(this).find('.coupon-wrapper').find('.selectAddress').prop("checked", true);
    var pincode = $(this).find('.coupon-wrapper').find('.selectAddress').attr('code');
    var schedule_date = $('.scheduleDate').val();

    GetAppointmentSlots(pincode, schedule_date)
  }
});


jQuery(document).on("change", ".report_type", function () {
  var reportType = $(".report_type:checked").val();
    viewCart(reportType);
});

jQuery(document).ready(function () {
  $(".inputvalidation").on("keyup paste", function(){
    if (this.value != "") {
      $(this).parent().find('.help-block').find('label').remove();
    }
  });
});

jQuery(document).on("click", "#saveAddress", function () {
  var value = $('#addAddressForm').find('.inputvalidation').val();
  var flag = true;

    $(".inputvalidation").each(function(){
      var value = $(this).val();
      if (value == "" ) {
          $(this).parent().find('.help-block').find('label').remove();
          $(this).parent().find('.help-block').append('<label class="error" style="display:block">This field is required.</label>');
        flag = false;
      }
      if ($(this).attr('name') == "address" && value != "" && value.length < 10) {
          $(this).parent().find('.help-block').find('label').remove();
          $(this).parent().find('.help-block').append('<label class="error" style="display:block">Please enter at least 10 characters.</label>');
          flag = false;
      }
      if ($(this).attr('name') == "address" && value != "" && value.length > 100) {
          $(this).parent().find('.help-block').find('label').remove();
          $(this).parent().find('.help-block').append('<label class="error" style="display:block">Please enter no more than 100 characters.</label>');
          flag = false;
      }

      if ($(this).attr('name') == "locality" && value != "" && value.length > 50) {
          $(this).parent().find('.help-block').find('label').remove();
          $(this).parent().find('.help-block').append('<label class="error" style="display:block">Please enter no more than 50 characters.</label>');
          flag = false;
      }

      if ($(this).attr('name') == "landmark" && value != "" && value.length > 40) {
          $(this).parent().find('.help-block').find('label').remove();
          $(this).parent().find('.help-block').append('<label class="error" style="display:block">Please enter no more than 40 characters.</label>');
          flag = false;
      }


    });
    var form = $('#addAddressForm :input').serialize();

    console.log(form);

  if (flag == true) {
    jQuery('.loading-all').show();
    jQuery('#saveAddress').attr('disabled',true);
    jQuery.ajax({
    type: "POST",
    dataType : "JSON",
    url: "{!! route('createLaborderAddresses') !!}",
    data:  form,
    success: function(data)
        {
          console.log(data);
          if (data.label_type == 1) {
            var labelName = "Home";
          }
          else if (data.label_type == 2) {
            var labelName = "Office";
          }
          else{
            var labelName = data.label_name;
          }

          addressDiv = '<div class="address-box active" lable-type="'+data.label_type+'"><p class="coupon-wrapper"><input type="radio" id="address_radio_'+data.id+'" value="'+data.id+'" name="address_id" class="selectAddress" code="'+data.pincode+'" checked  /><label for="address_radio_'+data.id+'"></label></p><div class="delete-address"><a href="javascript:void(0)" title="Delete Address" data-id="'+data.id+'" class="addressDeleteNow"><i class="fa fa-trash" aria-hidden="true"></i></a></div><div class="float"><div class="label-name">'+labelName+'</div><div class="address-area">'+data.address+', '+data.locality+', '+data.landmark+', '+data.pincode+'</div></div></div>';

          var labeTypes  = [];
          $('.address-box').each(function(){
              labeTypes.push($(this).attr('lable-type'));
          });
          if ((labeTypes.includes(data.label_type.toString()) == true) && (data.label_type == 1 || data.label_type == 2)) {
            $('.address-box').each(function(){
                var label_type = $(this).attr('lable-type');
                if (label_type == data.label_type) {
                  $('.address-box').removeClass('active');
                    $(this).replaceWith(addressDiv);
                }
            });
          }
          else{
            $('.address-box').removeClass('active');
            $(".AddressBox").append(addressDiv);
          }

          $('.emptyAddress').hide();
          $('.addressEmptyMsg').hide();
          $('.addressEmptyMsg').text("");
         $('.addressEmptyMsg').css("color", '');
          $('.availableMsg').hide();
          $("#addAddressForm input:text").each(function(){
              $(this).parent().find('.help-block').find('label').hide();
              $(this).parent().find('.help-block').append('<label class="error" style="display:block"></label>');
          });
          jQuery('.loading-all').hide();
          $('.addAddressDiv').slideUp();
          $('.labelName').slideUp();
          $('#addAddressForm').find('input:text').val('');
          jQuery('#saveAddress').attr('disabled',false);
       },
        error: function(error)
        {
          if(error.status == 401)
          {
              location.reload();
          }
          else
          {
            jQuery('.loading-all').hide();
            jQuery('#saveAddress').attr('disabled',false);
          }
        }
     });
  }

});


jQuery(document).on("click", ".addressDeleteNow", function () {
  $(this).attr('click-delete','1');
  var id = $(this).attr('data-id');
  var current = $(this);
  jQuery.ajax({
  type: "POST",
  url: "{!! route('deletelaborderAddress') !!}",
  data: {'id':id},
  success: function(data){
      var el = $(current).closest('.address-box');
      cuteHide(el);
         $(current).closest('.address-box').remove();
         if ($('.AddressBox .address-box').length == 0) {
           $('.emptyAddress').css({"display": "block"});
         }

    },
    error: function(error)
    {
      if(error.status == 401)
      {
          location.reload();
      }
      else
      {
        jQuery('.loading-all').hide();
        jQuery('#saveAddress').attr('disabled',false);
      }
    }
  });

});

jQuery(document).ready(function () {
	setTimeout(location.reload.bind(location), 600000);
});

</script>
@endsection
