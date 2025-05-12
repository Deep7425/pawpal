<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Lab Package</h4>
		</div>
		<div class="modal-body AddLaboratory">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.labPackageMaster') }}"> <i class="fa fa-list"></i>  Lab Package List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'UpdateLabPackage','name'=>'UpdateLabPackage', 'enctype' => 'multipart/form-data')) !!}
          <input type=hidden value="{{$row->id}}" name="id"/>
					<input type=hidden value="edit" name="action"/>
					<div class="form-group col-sm-6">
						<label>Type<i class="required_star">*</i></label>
						<select class="form-control" name="type">
							<option value="">Select Type</option>
							<option value="1" @if($row->type == '1') selected @endif>Normal</option>
							<option value="2" @if($row->type == '2') selected @endif>Group By Diseases</option>
						</select>
						 <span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label></span>
					</div>
          <div class="form-group col-sm-6">
            <label>Vendor Master<i class="required_star">*</i></label>
            <select class="form-control searchDropDown vendor_id" name="vendor_id">
              <option value="">Select Vendor</option>
              @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}" @if($row->vendor_id == $vendor->id) selected @endif>{{ $vendor->title }}</option>
              @endforeach
            </select>
             <span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label></span>
          </div>
          <div class="form-group col-sm-6">
            <label>Package Name<i class="required_star">*</i></label>
            <input value="{{$row->title}}" type="text" name="title" class="form-control" placeholder="Enter Package Name">
            <span class="help-block"></span>
          </div>
          <div class="form-group col-sm-12">
            <label>Select Lab test <i class="required_star">*</i></label>
            <div class="itempickerSection">
            <select class="itempicker lab_ids2" name="lab_ids[]" id="lab_ids2" multiple="multiple">
                @foreach(getLabByVendor($row->vendor_id) as $lab)
                <option value="{{$lab->LabMaster->id}}" price="{{$lab->price}}" @if(in_array($lab->LabMaster->id, explode(',',$row->lab_id))) selected @endif> {{$lab->LabMaster->title}}</option>
                @endforeach
            </select>
            <span class="help-block"></span>
            </div>
          </div>

          <div class="form-group col-sm-6">
            <label>Price<i class="required_star">*</i></label>
            <input value="{{$row->price}}" type="number" min="0" name="price" class="form-control packPrice" placeholder="Enter Price">
            <span class="help-block"></span>
          </div>
          <div class="form-group col-sm-6">
            <label>Discount</label>
            <input value="{{$row->discount}}" type="number" min="0" name="discount" class="form-control" placeholder="Enter Discount">
            <span class="help-block"></span>
          </div>
		<div class="form-group row">
			<div class="col-md-6">
			<label>Image</label>
			<input value="{{@$row->image}}" type="hidden" name="old_image" class="form-control"/>
			<input type="file" name="image" class="form-control"/>
			<span class="help-block"></span>
			</div>
			<div class="col-md-6">
				@if(!empty($row->image))
				<label>Old Image</label>
				<img src="<?php echo url("/")."/public/labFiles/".$row->image;?>" class="img-responsive" alt="Image" height="50" width="100" style="text-align:center;">
				@endif
			</div>
		</div>
          <div class="col-sm-12">
  					<div class="reset-button">
  					   <button type="reset" class="btn btn-warning">Reset</button>
  					   <button type="submit" class="btn btn-success update" id="upload-btn">Update</button>
  					</div>
          </div>
				 {!! Form::close() !!}

				</div>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>

	</div>
<script type="text/javascript">
var select = document.getElementById("lab_ids2");
multi(select, {
    non_selected_header: "Select Test",
    selected_header: "Selected Test"
});
$(document.body).on('click', '.update', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='UpdateLabPackage']").validate({
			rules: {
        vendor_id: "required",
        title: "required",
        lab_id: "required",
        price: "required",
        "lab_ids[]": "required",

			 },
			messages:{
			},
			errorPlacement: function(error, element){
        $(element).closest('.form-group').find('.help-block').append(error);
			},ignore: ":hidden",
			submitHandler: function(form) {
				$(form).find('.update').attr('disabled',true);
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.modifyLabPackage')!!}",
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
		});
	});
  jQuery('.vendor_id').on('change', function() {
    var cid = this.value;
    var $el = $(this).closest('form').find('.lab_ids2');
    var select = $(this).closest('form').find('.lab_ids2')[0];
    $el.empty();
    jQuery.ajax({
      url: "{!! route('admin.getLabByVendor') !!}",
     type : "POST",
      dataType : "JSON",
      data:{'id':cid},
      success: function(result){
      jQuery.each(result,function(index, element) {
         $el.append(jQuery('<option>', {
           value: element.lab_master.id,
           text : element.lab_master.title,
           price : element.price
        }));
      });
      $('.multi-wrapper').remove();
      $("#lab_ids2").removeAttr("data-multijs");
      $("#lab_ids2").removeAttr("style");
      var select = document.getElementById("lab_ids2");
      multi(select, {
          non_selected_header: "Select Test",
          selected_header: "Selected Test"
      });
    }}
    );
  });
  $(function() {
      $('#lab_ids2').change(function(e) {
        var select = $(this).closest('form').find('#lab_ids2');
        var packPrice = 0;
        $('option:selected', select).each(function(){
            var value = $(this).attr('price');
            packPrice += parseFloat(value);
        });
        $(this).closest('form').find('.packPrice').val(packPrice.toFixed(2));
      });
  });

</script>
