<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="loading" style="display:none;"><img src="{{ URL::asset('img/turningArrow.gif') }}" /></div>
    <div class="modal-header" style="padding:0 15px; color:#189ad4; margin-bottom:0;">
      <h2 class="popup-heading">Plan Details</h2>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
    <div class="right-section">
      <div class="right-block">
       <div class="right-box">
       <div class="table-billing-top">
         <h2>All Amount in ( INR )</h2>
       </div>
      <div class="table-billing">
      <table class="table table-inventory-section">
      <thead>
      <tr>
        <th style="width:200px">Plan Name</th>
        <th style="text-align: left; width: 135px;">Plan Start Date</th>
        <th style="text-align: left; width: 135px;">Plan End Date</th>
        <th style="text-align: left; width: 135px;">Duration</th>
        <th style="text-align: left; width: 135px;">SMS Limit</th>
        <th style="width:120px; text-align:right !important">Plan Amount</th>
      </tr>
      </thead>
      <tbody>
        @if($PracticesSubscriptions->count() > 0)
       @foreach($PracticesSubscriptions->SubscribedPlans as $SubscribedPlan)
       <tr>
          <td style="width:200px">{{$SubscribedPlan->Plans->plan_title}}</td>
          <td style="width: 135px;">{{date('d-m-Y', strtotime($SubscribedPlan->ManageTrailPeriods->start_trail))}}</td>
          <td style="width: 135px;">{{date('d-m-Y', strtotime($SubscribedPlan->ManageTrailPeriods->end_trail))}}</td>
          <td style="width: 135px;">{{$SubscribedPlan->plan_duration}}@if($SubscribedPlan->plan_duration_type == "d") Day @elseif($SubscribedPlan->plan_duration_type == "m") Month @elseif($SubscribedPlan->plan_duration_type == "y") Year @endif</td>
          <td style="width: 135px;">{{$SubscribedPlan->promotional_sms_limit}}</td>
          <td style="text-align:right;">{{$SubscribedPlan->plan_price-$SubscribedPlan->discount_price}}</td>
        </tr>
        @endforeach
        <tr>
          <td style="text-align:right;" colspan="5">CGST(9%)</td>
          <td style="text-align:right;">{{$PracticesSubscriptions->tax/2}}</td>
        </tr>
        <tr>
          <td style="text-align:right;" colspan="5">SGST(9%)</td>
          <td style="text-align:right;">{{$PracticesSubscriptions->tax/2}}</td>
        </tr>
        <tr>
          <td style="text-align:right;" colspan="5">Total</td>
          <td style="text-align:right;">{{$PracticesSubscriptions->order_total+$PracticesSubscriptions->coupon_discount}}</td>
        </tr>
        @if($PracticesSubscriptions->coupon_discount != '')
        <tr>
          <td style="text-align:right;" colspan="5">Coupon Discount</td>
          <td style="text-align:right;">{{$PracticesSubscriptions->coupon_discount}}</td>
        </tr>
        @endif
        <tr>
          <td style="text-align:right;" colspan="5"><strong>@if($PracticesSubscriptions->order_status==1) Paid @else Payable @endif Total</stron></td>
          <!-- below line shows error on local because online trancsaction not available on local instace.due to this reason row not found in subscription_txn tbl -->
          <td style="text-align:right;"><strong>{{$PracticesSubscriptions->SubscriptionsTxn->payed_amount}}</strong></td>
        </tr>
        @else
           <tr><td>No Record Found </td></tr>
        @endif

      </tbody>
      </table>
      </div>
      </div>
      </div>
 </div>
  </div>
</div>
</div>
