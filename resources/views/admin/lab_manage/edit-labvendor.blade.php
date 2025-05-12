<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Lab Vendor</h4>
		</div>
		<div class="modal-body AddLaboratory">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.labVendorMaster') }}"> <i class="fa fa-list"></i>  Lab Vendor List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'UpdateLabVendor','name'=>'UpdateLabVendor', 'enctype' => 'multipart/form-data')) !!}
          <input type=hidden value="{{$row->id}}" name="id"/>
					<input type=hidden value="edit" name="action"/>
					<div class="form-group">
						<label>Title<i class="required_star">*</i></label>
						<input value="{{@$row->title}}" type="text" name="title" class="form-control" placeholder="Enter Title">
						<span class="help-block"></span>
					</div>
          <div class="form-group">
            <label>Address</label>
            <textarea class="form-control" rows="3" name="address">{{@$row->address}}</textarea>
            <span class="help-block"></span>
          </div>
          <?php
            $days = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
            $rowDays = !empty($row->days) ? json_decode($row->days, true) : array();
          ?>
          <div class="form-group">
            <label>Day<i class="required_star">*</i></label>
            <div class="check-wrapper checkbox-div">
              @foreach($days as $key => $day)
              <label class="chck-container">
                <input type="checkbox" class="day_check" name="days[]" value="{{$key}}" @if(in_array($key,$rowDays)) checked @endif>{{$day}}
                <span class="checkmark"></span>
              </label>
              @endforeach

            </div>
            <span class="help-block"></span>
          </div>
          <div class="row">
            <div class="form-group col-sm-6">
              <label>Open Time<i class="required_star">*</i></label>
              <input type="time" name="open_time" class="form-control" value="{{$row->open_time}}">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label>Close Time<i class="required_star">*</i></label>
              <input type="time" name="close_time" class="form-control" value="{{$row->close_time}}">
              <span class="help-block"></span>
            </div>
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

$(document.body).on('click', '.update', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='UpdateLabVendor']").validate({
			rules: {
        title: {
          required: true,
          minlength: 1,
          maxlength: 100,
        },
        'days[]': "required",
        open_time: "required",
        close_time: "required",

			 },
			messages:{
			},
			errorPlacement: function(error, element){

				$(element).closest('.form-group').find('.help-block').append(error);
			},ignore: ":hidden",
			submitHandler: function(form) {
        jQuery('.loading-all').show();
				$(form).find('.update').attr('disabled',true);
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.modifyLabVendor')!!}",
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
