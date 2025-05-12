<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Laboratory</h4>
		</div>
		<div class="modal-body AddLaboratory">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.laboratoryMaster') }}"> <i class="fa fa-list"></i>  Laboratory List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'UpdateLaboratory','name'=>'UpdateLaboratory', 'enctype' => 'multipart/form-data')) !!}
          <input type=hidden value="{{$row->id}}" name="id"/>
					<input type=hidden value="edit" name="action"/>
          <div class="form-group col-sm-6">
            <label>Vendor Master<i class="required_star">*</i></label>
            <select class="form-control searchDropDown" name="vendor_id">
              <option value="">Select Vendor</option>
              @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}" @if($row->vendor_id == $vendor->id) selected @endif>{{ $vendor->title }}</option>
              @endforeach
            </select>
             <span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label></span>
          </div>
          <div class="form-group col-sm-6">
            <label>Lab Master<i class="required_star">*</i></label>
            <select class="form-control searchDropDown" name="lab_id">
              <option value="">Select Lab</option>
              @foreach($labs as $lab)
                <option value="{{ $lab->id }}"
                @if($row->lab_id == $lab->id) selected @endif >{{ $lab->title }}</option>
              @endforeach
            </select>
             <span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label></span>
          </div>
          <div class="form-group col-sm-6">
            <label>Price<i class="required_star">*</i></label>
            <input value="{{$row->price}}" type="number" min="0" name="price" class="form-control" placeholder="Enter Price">
            <span class="help-block"></span>
          </div>
          <div class="form-group col-sm-6">
            <label>Discount</label>
            <input value="{{$row->discount}}" type="number" min="0" name="discount" class="form-control" placeholder="Enter Discount">
            <span class="help-block"></span>
          </div>

					<div class="reset-button">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success update" id="upload-btn">Update</button>
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
$(".searchDropDown").select2();
jQuery('.searchDropDown').on('change', function() {
  if (this.value != "") {
  $(this).parent('.form-group').find('.help-block .error').hide();
  }
});
$(document.body).on('click', '.update', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='UpdateLaboratory']").validate({
			rules: {
        vendor_id: "required",
        lab_id: "required",
        price: "required",

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
					url: "{!! route('admin.modifyLaboratory')!!}",
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

  function openFile(event) {
    $("#upload-btn").attr('disabled',false);
      var input = event.target;
      var FileSize = input.files[0].size / 1024 /1024; // 10in MB
      var type = input.files[0].type;
      var fileName = input.files[0].name;
      var ext = input.files[0].name.split('.').pop().toLowerCase();
      var reader = new FileReader();
      if(FileSize>3){
      $('#blah2').hide();
      $('#fileselector2').next(".help-block").remove();
      $('#fileselector2').after(' <span class="help-block"><label for="title" generated="true" class="error">Allowed file size exceeded. (Max. 3 MB)</label></span>');

  	}
  	else if($.inArray(ext, ['png','jpg','jpeg']) >=0){
      $("#upload-btn").attr('disabled',false);
  			reader.addEventListener("load", function (){
  				if($.inArray(ext, ['png','jpg','jpeg']) >=0){

            $('#blah2').attr('src',reader.result);
  					$('#blah2').show();
  					$('#fileselector2').next(".help-block").remove();
  					$('#fileselector2').after(' <span class="help-block" style="color:green;">('+fileName+')File Browsed Successfully.</span>');
  				}
  				else{
  					$('#fileselector2').next(".help-block").remove();
            $('#fileselector2').after(' <span class="help-block" style="color:green;">('+fileName+')File Browsed Successfully.</span>');
  				}
  			});
  			reader.readAsDataURL(input.files[0]);
  			//alert(reader.result);
  	    }
          else{
            $("#upload-btn").attr('disabled',true);
            $('#blah2').hide();
            $('#fileselector2').next(".help-block").remove();
            $('#fileselector2').after(' <span class="help-block"><label for="title" generated="true" class="error">Only formats are allowed : (jpeg,jpg,png)</label></span>');
  			}
  		}

</script>
