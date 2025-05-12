<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Lab</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.labMaster') }}"> <i class="fa fa-list"></i>  Lab List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'UpdateLab','name'=>'UpdateLab', 'enctype' => 'multipart/form-data')) !!}
          <input type=hidden value="{{$row->id}}" name="id"/>
					<input type=hidden value="edit" name="action"/>
					<div class="form-group">
						<label>Title<i class="required_star">*</i></label>
						<input value="{{@$row->title}}" type="text" name="title" class="form-control" placeholder="Enter Title">
						<span class="help-block"></span>
					</div>
          <div class="form-group">
						<label>Short Name</label>
						<input value="{{@$row->title}}" type="text" name="short_name" class="form-control" placeholder="Enter Short Name">
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

$(document.body).on('click', '.update', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='UpdateLab']").validate({
			rules: {
        title: {
          required: true,
          minlength: 1,
          maxlength: 100,
        },

			 },
			messages:{
			},
			errorPlacement: function(error, element){

				error.appendTo(element.parent().find('.help-block'));
			},ignore: ":hidden",
			submitHandler: function(form) {
        jQuery('.loading-all').show();
				$(form).find('.update').attr('disabled',true);
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.modifyLab')!!}",
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
