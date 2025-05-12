<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Slider</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag update-slider">
				
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.sliderMaster') }}"> <i class="fa fa-list"></i>  sliders List</a>
					</div>
				
				<div class="panel-body" style="padding-top:15px;">
					{!! Form::open(array('id' => 'UpdateSlider','name'=>'UpdateSlider', 'enctype' => 'multipart/form-data')) !!}
          			<div class="row">
					<input type=hidden value="{{$slider->id}}" name="id"/>
					<input type=hidden value="edit" name="action"/>
					<div class="col-md-6">
						<label>Title</label>
						<input value="{{@$slider->title}}" type="text" name="title" class="form-control" placeholder="Enter Title">
						<span class="help-block"></span>
					</div>
          <div class="col-md-6 slide-img">
            <label>Slider Image</label>
            <input type="file" name="image" class="form-control" onchange='openFile(event)' id="upload-file-selector"/ placeholder="">
            <input type="hidden" name="old_image" value="{{@$slider->image}}">
            <span class="help-block"></span>
            <span id="fileselector2"></span>
			<img src="<?php echo url("/")."/public/slidersImages/".$slider->image;?>" id="blah2" alt="" width="100" style="display:@if(!empty($slider->image)) block @else none @endif ;">
          </div>
          
          <div class="col-md-12">
            <label>Description</label>
            <textarea value="" class="form-control" name="description" id="sliderDescription" rows="5">{{$slider->description}}</textarea>
            <span class="help-block"></span>
         </div>
				 <div class="col-md-12">
					<div class="reset-button">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success update" id="upload-btn">Update</button>
					</div></div>
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

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script type="text/javascript">

$(document.body).on('click', '.update', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='UpdateSlider']").validate({
			rules: {
        title: {
          required: true,
          minlength: 1,
          maxlength: 100,
        },
        image: "required",

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
					url: "{!! route('admin.modifySliderMaster')!!}",
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


		  $('.btn-default').click(function() {
    $('.modal').modal('hide');
});

$('.close').click(function() {
    $('.modal').modal('hide');
});
</script>
