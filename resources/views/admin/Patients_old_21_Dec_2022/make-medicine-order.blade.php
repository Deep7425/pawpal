<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Medicine Order</h4>
		</div>
    {!! Form::open(array('id' => 'createMedOrder','name'=>'createMedOrder', 'enctype' => 'multipart/form-data')) !!}
    <input type="hidden" name="user_id" value="{{$user->id}}">
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
				</div>
				<div class="panel-body">

          <input type=hidden value="" name="id"/>



          <table class="table table-bordered">

            <thead>
              <tr>
                <th>#</th>
                <th style="width:500px;">Medicine Name</th>
                <th style="text-align: center;">Qty.</th>
                <th style="text-align: center;">Price</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="addRow">
              <tr class="trRow">
                <td>1</td>
                <td style="width:400px;">
                  <div class="treatment-section medicineSearchDiv">
                    <input type="text" id="drug_name" placeholder="Search Medicines" class="form-control drugSearchNew" autocomplete="off" name="cart[1][medicine_name]" />
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <div class="suggesstion-box" style="display:none;"></div>
                  </div>
                  <input type="hidden" name="cart[1][medicine_id]" class="medicine_id" value="">
                </td>
                <td>
                  <input type="number" name="cart[1][qty]" min="1" class="form-control qty" placeholder="Enter Qty." value="1">
                </td>
                <td>
                  <input type="text" class="form-control medPrice medPriceInput" value="0" readonly>
                  <input type="hidden" name="cart[1][price]" class="medPrice actualPrice" value="0">
                </td>
                <td>
                  <p class="removeRow" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></p>
                </td>
              </tr>
              <tr><td colspan="5"><button type="button" class="btn btn-success addMoreMedicine" id="addMoreMedicine">Add Medicine</button></td></tr>
            </tbody>
            <tbody class="PriceRow">
              <tr>
                <td colspan="3"><strong>Order Sub Total</strong></td>
                <td>
                  <input type="text" name="order_subtotal" class="form-control order_subtotal" value="0" readonly>
                </td>
                <td></td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: right;"><strong>Coupon Discount</strong></td>
                <td>
                  <div class="form-group">
                      <select class="form-control" name="coupon_id" id="coupon_id">
                          <option value="">Select Coupon</option>
                          @foreach(getCouponCodes(3) as $coupon)
                          <option value="{{@$coupon['coupon_id']}}" type="{{@$coupon['coupon_discount_type']}}" coupon_rate="{{@$coupon['coupon_rate']}}">{{@$coupon['coupon_code']}}</option>
                          @endforeach
                      </select>
                      <span class="help-block"></span>
                  </div>
                </td>
                <td>
                  <input type="text" name="coupon_discount" class="form-control coupon_discount" value="0" readonly>
                </td>
                <td></td>
              </tr>
              <tr>
                <td colspan="3"><strong>Delivery Charge</strong></td>
                <td>
                  <input type="text" name="delivery_charge" class="form-control delivery_charge" value="{{getSetting('delivery_charge')[0]}}" readonly>
                </td>
                <td></td>
              </tr>
              <tr>
                <td colspan="3"><strong>Order Total</strong></td>
                <td>
                  <input type="text" name="order_total" class="form-control order_total" value="0" readonly>
                </td>
                <td></td>
              </tr>
            </tbody>

          </table>
          <div class="hideDiv" style="display: none;">
            <table class="customTable">
              <tr class="trRow">
                <td>__number__</td>
                <td>
                  <div class="treatment-section medicineSearchDiv">
                    <input type="text" id="drug_name" placeholder="Search Drug" class="form-control drugSearchNew" autocomplete="off" __name__="cart[__number__][medicine_name]" />
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <div class="suggesstion-box" style="display:none;"></div>
                  </div>
                  <input type="hidden" __name__="cart[__number__][medicine_id]" class="medicine_id" value="">
                </td>
                <td>
                  <input type="number" __name__="cart[__number__][qty]" min="1" class="form-control qty" placeholder="Enter Qty." value="1">
                </td>
                <td>
                  <input type="text" class="form-control medPrice medPriceInput" value="0" readonly>
                  <input type="hidden" __name__="cart[__number__][price]" class="medPrice actualPrice" value="">

                </td>
                <td><p class="removeRow"><i class="fa fa-times" aria-hidden="true"></i></p></td>
              </tr>
            </table>
          </div>
          <div class="row">
            <div class="col-sm-6 form-group">
                <label>Address</label>
                <div class="address-wrapper AddressBox waitingTime">
                    @if(count($addresses) > 0)
                      @php $i = 1; @endphp
                      @foreach($addresses as $address)
                        <div class="address-box @if($i == 1) active @endif" lable-type="{{$address->label_type}}">
                          <?php if ($address->label_type == 1) { $label_name = "Home"; } elseif ($address->label_type == 2) { $label_name = "Office"; } else { $label_name = $address->label_name; } ?>
                            <div class="coupon-wrapper">
                                <label for="address_radio_{{$address->id}}"><input type="radio" id="address_radio_{{$address->id}}" value="{{$address->id}}" name="address_id" class="selectAddress" code="{{$address->pincode}}" @if($i == 1) checked @endif />
                                <span class="address-area"><strong>{{$label_name}}</strong> <br>{{$address->address}}, {{$address->locality}}, {{$address->landmark}}, {{$address->pincode}}</span>

                            </label>
                            </div>

                        </div>
                        @php $i++; @endphp
                      @endforeach
                    @endif

                    <div class="emptyAddress" style="display: @if (count($addresses) > 0) none @else block @endif;">
                        <h3>No Address Available !</h3>
                        <strong class="addressEmptyMsg" style="display: none;"></strong>
                    </div>
                </div>
                <div class="btn-add-address addModal"><a href="javascript:void(0)">Add New Address</a></div>
            </div>

            <div class="col-sm-6">
              <div class="row">
                <div class="col-sm-12 form-group">
                  <label>Sellar Details</label>
                  <textarea class="form-control" rows="3" name="seller_detail" placeholder="Enter Sellar Details" value=""></textarea>
                  <span class="help-block"></span>
                </div>
                <div class="col-sm-12 form-group">
                  <label>Prescription Upload</label>
                  <div class="form-check">
                      <label class="radio-inline"> <input type="radio" name="pres_type" value="2" checked="checked" />No</label>
                      <label class="radio-inline"> <input type="radio" name="pres_type" value="1" />Yes</label>
                  </div>
                  <div class="prescriptionUpload" style="display:none;">
                    <input type="file" name="document[]" class="form-control" onchange='openFile(event)' id="upload-file-selector"/ placeholder="" multiple>
                    <span id="fileselector"></span>
                  </div>
                </div>
              </div>
  					</div>
            <!-- <div class="form-group">
  					    <img src="" id="blah" alt="" width="100" style="display:none;">
  					</div> -->
          </div>




				</div>
			</div>

		</div>
		<div class="modal-footer">
			<div class="reset-button">
					   <button type="submit" class="btn btn-success update" id="upload-btn">Save</button>
					</div>

		</div>
		{!! Form::close() !!}
	</div>

	</div>
<script type="text/javascript">

$(document.body).on('click', '.update', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='createMedOrder']").validate({
			rules: {

			 },
			messages:{
			},
			errorPlacement: function(error, element){
				error.appendTo(element.parent().find('.help-block'));
			},ignore: ":hidden",
			submitHandler: function(form) {
        var flag = true;
        $("table .addRow .trRow .medicine_id").each(function(index) {
          if ($(this).val() > 0) {
            $(this).closest("td").find('.drugSearchNew').css({"border": ""});
          }else {
            $(this).closest("td").find('.drugSearchNew').css({"border": "1px solid red"});
            flag = false;
          }
        });
        if (flag == true) {
         jQuery('.loading-all').show();
         $(form).find('.update').attr('disabled',true);
         jQuery.ajax({
           type: "POST",
           dataType : "JSON",
           url: "{!! route('admin.makeMedOrder')!!}",
           data:  new FormData(form),
           contentType: false,
           cache: false,
           processData:false,
           success: function(data) {
              if(data==1)
              {
               jQuery('.loading-all').hide();
               $(form).find('.update').attr('disabled',false);
               location.reload();
              }
              else
              {
               jQuery('.loading-all').hide();
               $(form).find('.update').attr('disabled',false);
               alert("Oops Something Problem");
              }
           },
           error: function(error)
           {
               jQuery('.loading-all').hide();
               alert("Oops Something goes Wrong.");
           }
         });
        }

			}
		});
	});
  jQuery(document).on("keyup", ".drugSearchNew", function () {
      var currSearch = this;
      jQuery.ajax({
      type: "POST",
      url: "{!! route('admin.searchMedicine') !!}",
      data: {'searchText':jQuery(this).val()},
      beforeSend: function(){
        jQuery(currSearch).css("background","#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
      },
      success: function(data){
          var liToAppend = "";
          if(data.length > 0){
          jQuery.each(data,function(k,v){
            liToAppend += '<li  value="'+v.id+'" name="'+v.name+'" price="'+v.price+'" class="dataList">'+v.name+'</li>';
          });
        }else{
            liToAppend += '<li value="0">"'+jQuery(currSearch).val()+'" Drug Not Found.</li>';
            //liToAppend += '<li><a href="javascript::void(0)" data-toggle="modal" data-event="this" data-target="#addDrugModel"> Add "'+jQuery(currSearch).val()+'" Drug as new Drug.</a></li>';
          }
          jQuery(currSearch).closest(".medicineSearchDiv").find(".suggesstion-box").show();
          jQuery(currSearch).closest(".medicineSearchDiv").find(".suggesstion-box").html('<ul>'+liToAppend+'</ul>');
        //  jQuery(currSearch).css("background","#FFF");
      }
      });
    });
    jQuery(document).off('click').on("click", "#addMoreMedicine", function () {
      $('table .addRow tr:first .removeRow').show();
      var rowCount = 1;
      if($(this).closest('form').find("table .addRow .trRow").length > 0){
        rowCount = $(this).closest('form').find("table .addRow .trRow").length+1;
      }

      var row = $(this).closest('.panel-body').find(".hideDiv .customTable tr").html();
      row = row.replace(/__number__/gi, rowCount);
      row = row.replace(/__name__/gi, 'name');
      // console.log(recRowHtml);
      // $(this).closest('form').find('table .addRow').append("<tr>"+row+"</tr>");
      $('table .addRow .trRow').last().after("<tr class='trRow'>"+row+"</tr>");
      $("table .addRow .trRow").each(function(index) {
          var prefix = "cart[" + index + "]";
          $(this).find("input").each(function() {
             this.name = this.name.replace(/cart\[\d+\]/, prefix);
          });
      });
    });
  jQuery(document).on("click", ".removeRow", function () {

    $(this).closest('tr').remove();
    $("table .addRow tr").each(function(index) {
        var prefix = "cart[" + index + "]";
        $(this).find("input").each(function() {
           this.name = this.name.replace(/cart\[\d+\]/, prefix);
        });
    });
    priceCalculation();
      var trRow = $('table .addRow .trRow').length;

      if (trRow == '1') {
        $('table .addRow .trRow .removeRow').hide();
      }
  });

  jQuery(document).on("click", ".wrapper", function () {
       jQuery(this).find(".suggesstion-box").hide();
       jQuery(this).find(".suggesstion-box ul").remove();
  });
  function priceCalculation() {
    var price = 0;
    jQuery(".actualPrice").each(function () {
      var qty = $(this).closest('tr').find('.qty').val();
      var itemPrice = parseFloat($(this).val()*qty);
        price += Number(itemPrice);
    });
    $('.order_subtotal').val(price.toFixed(2));
    var couponSelect = $('#coupon_id');
    if ($(couponSelect).val() != "" || $('.coupon_discount').val() > 0) {
      var disType = $('option:selected', couponSelect).attr('type');
      var discount = $('option:selected', couponSelect).attr('coupon_rate')||0;
      if (disType == '2') {
        var discount = parseFloat((price*discount)/100);
      }
      if (price >= discount || $('.coupon_discount').val() > 0) {
        $('.coupon_discount').val(discount);
        price = parseFloat(price-discount);
      }
    }
    var delivery_charge = $('.delivery_charge').val()||0;
    price = parseFloat(price)+parseFloat(delivery_charge);
    $('.order_total').val(price.toFixed(2));
  }
    // $(".manufacturerSearch").blur(function(){
    //   $(".suggesstion-box").hide();
    // });
  jQuery(document).on("click", ".dataList", function () {
    jQuery(this).closest("tr").find('td .qty').val(1);
    jQuery(this).closest("tr").find('td .medPrice').val($(this).attr("price"));
      jQuery(this).closest("td").find('.drugSearchNew').val($(this).attr("name"));
       jQuery(this).closest("td").find('.medicine_id').val($(this).attr("value"));

       $(this).closest("td").find('.drugSearchNew').css({"border": ""});

       jQuery(this).closest(".suggesstion-box").hide();
       jQuery(this).closest(".suggesstion-box ul").remove();
       priceCalculation();
  });
  jQuery(document).on("change", ".qty", function () {
    var qty = $(this).val();
    var actualPrice = $(this).closest('tr').find('.actualPrice').val();
    var itemPrice = parseFloat(actualPrice*qty);
    $(this).closest('tr').find('.medPriceInput').val(itemPrice.toFixed(2));
    priceCalculation();
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

  $(document.body).on('click', '.saveAddress', function(){
    		// jQuery("#modifySubAdmin").validate({
    		 jQuery("form[name='addUserAddress']").validate({
    			rules: {
            address	: "required",
            pincode	: "required",
            locality	: "required",
            landmark	: "required",
    			 },
    			messages:{
    			},
    			errorPlacement: function(error, element){

    				error.appendTo(element.parent().find('.help-block'));
    			},ignore: ":hidden",
    			submitHandler: function(form) {
    				$(form).find('.update').attr('disabled',true);
    				jQuery.ajax({
    					type: "POST",
    					dataType : "JSON",
    					url: "{!! route('admin.addUserAddress')!!}",
    					data:  new FormData(form),
    					contentType: false,
    					cache: false,
    					processData:false,
    					success: function(data) {
    						 if(data != "")
    						 {
                   if (data.label_type == 1) {
                     var labelName = "Home";
                   }
                   else if (data.label_type == 2) {
                     var labelName = "Office";
                   }
                   else{
                     var labelName = data.label_name;
                   }
                   addressDiv = '<div class="address-box active" lable-type="'+data.label_type+'"> <div class="coupon-wrapper"> <label for="address_radio__'+data.id+'"> <input type="radio" id="address_radio__'+data.id+'" value="'+data.id+'" name="address_id" class="selectAddress" code="'+data.pincode+'" checked="" /> <span class="address-area"> <strong>'+labelName+'</strong> <br />'+data.address+', '+data.locality+', '+data.landmark+', '+data.pincode+'</span> </label> </div> </div>';

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

                   $('#addUserAddress')[0].reset();
                   $('#addAddressModal').modal('hide');
                   jQuery('#saveAddress').attr('disabled',false);
    						 }
    						 else
    						 {
    						  jQuery('.loading-all').hide();
    						  $(form).find('.update').attr('disabled',false);
    						  alert("Oops Something Problem");
    						 }
    					},
              error: function(error)
              {
                  jQuery('.loading-all').hide();
                  alert("Oops Something goes Wrong.");
              }
    				});
    			}
    		});
    	});
$(document.body).on('change', 'input[name="pres_type"]', function(){
  if (this.value == '1') {
    $('.prescriptionUpload').show();
  }else {
    $('.prescriptionUpload').hide();
  }
});
$(document.body).on('change', 'select[name="coupon_id"]', function(){
  priceCalculation();
});
function openFile(event) {
    $("#submit-btn").attr("disabled", false);
    var input = event.target;
    var FileSize = input.files[0].size / 1024 / 1024; // 10in MB
    var type = input.files[0].type;
    var fileName = input.files[0].name;
    var ext = input.files[0].name.split(".").pop().toLowerCase();
    var reader = new FileReader();
    if (FileSize > 3) {
        $("#blah").hide();
        $("#fileselector").next(".help-block").remove();
        $("#fileselector").after(' <span class="help-block"><label for="title" generated="true" class="error">Allowed file size exceeded. (Max. 3 MB)</label></span>');
    } else if ($.inArray(ext, ["png", "jpg", "jpeg"]) >= 0) {
        $("#submit-btn").attr("disabled", false);
        reader.addEventListener("load", function () {
            if ($.inArray(ext, ["png", "jpg", "jpeg"]) >= 0) {
                $("#blah").attr("src", reader.result);
                $("#blah").show();
                $("#fileselector").next(".help-block").remove();
                $("#fileselector").after(' <span class="help-block" style="color:green;">(' + fileName + ")File Browsed Successfully.</span>");
            } else {
                $("#fileselector").next(".help-block").remove();
                $("#fileselector").after(' <span class="help-block" style="color:green;">(' + fileName + ")File Browsed Successfully.</span>");
            }
        });
        reader.readAsDataURL(input.files[0]);
        //alert(reader.result);
    } else {
        $("#submit-btn").attr("disabled", true);
        $("#blah").hide();
        $("#fileselector").next(".help-block").remove();
        $("#fileselector").after(' <span class="help-block"><label for="title" generated="true" class="error">Only formats are allowed : (jpeg,jpg,png)</label></span>');
    }
}

$(document).on('click','.addModal', function () {
	$('#addAddressModal').modal('show');
});
$(document).on('hidden.bs.modal','#addAddressModal', function () {
$('body').addClass('modal-open');
});

</script>
