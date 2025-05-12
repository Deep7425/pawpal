<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">View Medicine Order</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<input name="orderId" type="hidden" value="{{@$order->id}}"/>
					<div class="toppOrderSection">
					<a class="btn btn-primary" href="javascript:void(0);">Order No. : <strong>{{@$order->order_id}}</strong> </a>
					<a class="btn btn-primary status" href="javascript:void(0);">Order Date : <strong>{{date('F d Y g:i A',strtotime($order->created_at))}}</strong></a>
					</div>
					<!--<a class="btn btn-primary status" href="javascript:void(0);">Order Tracking Status : <strong>@if($order->order_status == "0") pending @elseif($order->order_status == "1") completed @elseif($order->order_status == "2") Cancelled @elseif($order->order_status == "3") Failure Transaction @endif</strong></a>-->
					<div class="OrderTrackingStatus">
					<div class="form-group col-sm-6">
					<label>Order Tracking Status</label>
					<select class="form-control orderSts" @if($order->order_status == "3") style="display:none;" @endif>
						<option value="">Select Tracking Status</option>
						<option value="0" @if($order->order_status == "0") selected @endif>Pending Assign</option>
						<option value="1" @if($order->order_status == "1") selected @endif>Assigned</option>
						<option value="2" @if($order->order_status == "2") selected @endif>On the way</option>
						<option value="3" @if($order->order_status == "3") selected @endif>DONE</option>
						<option value="4" @if($order->order_status == "4") selected @endif>CANCELLED</option>
					</select>
					<a class="btn btn-primary stsDone" href="javascript:void(0);" @if($order->order_status == "3") style="display:show;" @else style="display:none;" @endif><strong>DONE</strong></a>
					</div>
					<div class="form-group col-sm-6">
					<div class="dataTables_length">
						<label>Delivery Date</label>
						<div class="ClassDateTracking">
						   <input @if($order->order_status == "3") disabled @endif placeholder="Delivery Date" readonly type="text" autocomplete="off" class="form-control delivery_date" value="{{$order->delivery_date}}"/>
						   <span class="input-group-addon delivery_date_cal"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						</div>
				   </div>
				   </div>
				</div>
				</div>
				<div class="panel-body">
			  <h3>Order Items</h3>
			  <div class="SubscriptionSe">
			<div class="SubscriptionSe22">
			  <table class="table table-bordered table-hover">
				  <tr>
				    <th>#</th>
				    <th>ITEM NAME</th>
					<th>MFR/MKT</th>
					<th>HSN</th>
					<th>BATCH</th>
					<th>EXP</th>
					<th>MRP</th>
					<th>DIS</th>
					<th>UNIT PRICE</th>
					<th>QTY</th>
					<th>AMOUNT</th>
				  </tr>
				  <?php
					$meta_data = json_decode($order->meta_data,true);
				  ?>
				  @if(count($meta_data['meds']) > 0)
					@foreach($meta_data['meds'] as $index => $raw)
					<tr>
						<td>{{$index+1}}</td>
						<td>{{$raw['medicine_product_details']['name']}}</td>
						<td>{{$raw['medicine_product_details']['manufacturer']}}</td>
						<td></td>
						<td></td>
						<td></td>
						<td>{{number_format($raw['medicine_product_details']['price'],2)}}</td>
						<td></td>
						<td>{{number_format($raw['medicine_product_details']['price'],2)}}</td>
						<td>{{$raw['qty']}}</td>
						<td>{{number_format($raw['medicine_product_details']['price'],2)}}</td>
					</tr>
					@endforeach
					@else
						<tr><td colspan="9">No Record Found</td></tr>
					@endif
			  </table>
			  </div>
			  </div>
			  <h3>User Details</h3>
			  <div class="SubscriptionSe">
			  <div class="SubscriptionSe22">
			  <table class="table table-bordered table-hover">
				  <tr>
					<th>Name</th>
					<th>Gender</th>
					<th>E-Mail</th>
					<th>Mobile No.</th>
				  </tr>
				  <tr>
					<td>{{@$order->User->first_name}} {{@$order->User->last_name}}</td>
					<td>{{@$order->User->gender}}</td>
					<td>{{@$order->User->email}}</td>
					<td>{{@$order->User->mobile_no}}</td>
				  </tr>
			  </table>
			  </div>
			  </div>
		  </div>
        <div class="panel-heading PriceDiv123">
        	<div class="SubscriptionSe1233">
			<div class="SubscriptionSe2211">
          <table class="table table-bordered table-hover">
          <tbody>
          	<tr>
          		<td style="text-align: right;">Date</td>
          		<td style="text-align: right; width: 30px;">:</td>
          		<td style="text-align: right; width: 200px;"><strong> {{date('jS M, Y',strtotime($order->created_at))}}</strong></td>
          	</tr>
          	<tr>
          		<td style="text-align: right;">Order Type</td>
          		<td style="text-align: right;width: 30px;">:</td>
          		<td style="text-align: right; width: 200px;"><strong> @if($order->type == "0") Pending @elseif($order->type == "1") Free @elseif($order->type == "2") Paid @endif</strong></td>
          	</tr>
          	<tr>
          		<td style="text-align: right;">Total Amount</td>
          		<td style="text-align: right;width: 30px;">:</td>
          		<td style="text-align: right; width: 200px;"><strong> ₹ {{$order->order_subtotal}}</strong></td>
          	</tr>
			<tr>
          		<td style="text-align: right;">Coupon Discount</td>
          		<td style="text-align: right;width: 30px;">:</td>
          		<td style="text-align: right; width: 200px;"><strong> ₹ @if(!empty($order->coupon_discount)) {{$order->coupon_discount}} @else 0.00  @endif</strong></td>
          	</tr>
          	<tr>
          		<td style="text-align: right;">Tax</td>
          		<td style="text-align: right;width: 30px;">:</td>
          		<td style="text-align: right; width: 200px;"><strong>@if(!empty($order->tax)) ₹ {{$order->tax}} @else ₹ 0.00 @endif</strong></td>
          	</tr>
			<tr>
          		<td style="text-align: right;">Delivery Charge</td>
          		<td style="text-align: right;width: 30px;">:</td>
          		<td style="text-align: right; width: 200px;"><strong>@if(!empty($order->delivery_charge)) ₹ {{$order->delivery_charge}}@endif</strong></td>
          	</tr>
          	<tr class="PaidTotal">
          		<td style="text-align: right;">Paid Total</td>
          		<td style="text-align: right;width: 30px;">:</td>
          		<td style="text-align: right; width: 200px;"><strong> ₹ {{$order->order_total}}</strong></td>
          	</tr>
          </tbody>
          </table>
          </div>
          </div>
		</div>
		@if($order->pres_type == "1")
			<?php 
				$recd = [];	
				$records = getPresRecord($order->presIds); 
				if(count($records) == 0) {
					$recd = getPresRecordByUser($order->user_id); 
				}
			?>
			<div>
			<button class="presBtn">Prescription</button>
			<div class="recordsDiv" style="display:none;">
			@if(count($records) > 0 )
				@foreach($records as $raw)
					<p><img src="{{$raw->presUrl}}" class="showPres"/></p>
				@endforeach
			@elseif(count($recd)>0)
				@foreach($recd as $raw)
					<p><img src="{{$raw->presUrl}}" class="showPres"/></p>
				@endforeach
			@else
				<p class="notFound">No Prescription Found</p>
			@endif
			</div>
			</div>
		@endif	
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
	</div>
</div>
<!-- The Modal -->
<div id="myModal" class="modal">
  <!-- The Close Button -->
  <span class="close12">&times;</span>
  <!-- Modal Content (The Image) -->
  <img class="modal-content12" id="img01">
</div>
<script type="text/javascript">
jQuery(".delivery_date").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
  //minDate: new Date(),
  onSelect: function (selected) {
    AssignDate(selected);
  }
});
jQuery('.delivery_date_cal').click(function () {
  jQuery('.delivery_date').datepicker('show');
});
function AssignDate(d_date) {
	jQuery('.loading-all').show();
	var orderId = jQuery("input[name='orderId']").val();
	jQuery.ajax({
	  type: "POST",
	  url: "{!! route('admin.changeorderDate')!!}",
	  data: {"_token":"{{ csrf_token() }}",'orderId':orderId,'d_date':d_date},
	  success: function(data){
		jQuery('.loading-all').hide();
		 if(data==1) {
			$.alert("Date updated successfully..");
		 }
	   },
	   error: function(error){
		 jQuery('.loading-all').hide();
		 $.alert("Oops Something goes Wrong.");
	   }
	});
}
jQuery(document).on("click", ".showPres", function () {
var modal = document.getElementById("myModal");
var img = $(this).attr("src");
var modalImg = document.getElementById("img01");
modal.style.display = "block";
modalImg.src = this.src;
});
jQuery(document).on("click", ".close12", function () {
	var modal = document.getElementById("myModal");
	 modal.style.display = "none";
});
</script>