<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Blogs</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag edit-blog">
				<div class="">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.blogMaster') }}"> <i class="fa fa-list"></i>  Blogs List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'updateBlog','name'=>'updateBlog', 'enctype' => 'multipart/form-data')) !!}
					
					<div class="row">
						<input type=hidden value="{{$blog->id}}" name="id"/>
					<div class="col-md-6">
						<label>Title</label>
						<input value="{{@$blog->title}}" type="text" name="title" class="form-control blogTitle" placeholder="Enter Title">
						<span class="help-block"></span>
					</div>
					<div class="col-md-6">
						<label>Url</label>
						<input value="{{@$blog->slug}}" type="text" name="slug" class="form-control blogSlug" placeholder="Enter Slug">
						<span class="help-block"></span>
					</div>
					<div class="col-md-6">
						<label>Keyword</label>
						<input value="{{@$blog->keyword}}" type="text" name="keyword" class="form-control" placeholder="Enter Keyword">
						<span class="help-block"></span>
					</div>

                         <div class="col-md-6">
						<label>Description</label>
						<input value="{{@$blog->blog_desc}}" type="text" name="blog_desc" class="form-control" placeholder="Blog Description For Seo">
						<span class="help-block"></span>
					</div>

					<div class="col-md-12">
						<label>Description</label>
						<textarea value="{{@$blog->description}}" class="form-control" name="description" id="exampleblogEditor" rows="5">{{@$blog->description}}</textarea>
					</div>
					
						<div class="col-md-6">
							<label>Image</label>
							<input value="{{@$blog->image}}" type="hidden" name="old_image" class="form-control"/>
							<input type="file" name="image" class="form-control"/>
							<span class="help-block"></span>
						</div>
						<div class="col-md-6">
							@if(!empty($blog->image))
							<label style="width:100%; float:left;">Old Image</label>
							<img src="<?php echo url("/")."/public/newsFeedFiles/".$blog->image;?>" class="img-responsive" alt="Blog Image" height="50" width="100" style="text-align:center;">
							@endif
						</div>
					
					
						<div class="col-md-6">
							<label>Video</label>
							<input value="{{@$blog->video}}" type="text" name="video" class="form-control"/>
							 <span class="help-block"></span>
						</div>
					
				  <div class="col-md-6">
					  <label>Publish Date</label>
					  <input type="text" class="form-control datepicker" placeholder="dd-mm-YYYY" name="publish_date" value="@if(!empty($blog->publish_date)) {{date('d-m-Y', strtotime($blog->publish_date))}} @endif" autocomplete="off" readonly />
					  <span class="help-block"></span>
				  </div>
					<div class="col-md-6">
						  <label>Top Show By Date</label>
						  <input type="text" class="form-control datepicker" placeholder="dd-mm-YYYY" name="show_date" value="@if(!empty($blog->show_date)) {{date('d-m-Y', strtotime($blog->show_date))}} @endif" autocomplete="off" readonly />
						  <span class="help-block"></span>
					  </div>
					 <div class="col-md-6 form-check">
					  <label>Type</label><br>
					  <label class="radio-inline">
					  <?php $types = []; if(!empty($blog->type)){ $types = explode(',',$blog->type); } ?>
						  <input type="checkbox" name="type[]" value="2" @if(in_array(2,$types)) checked="checked" @endif>Web</label>
						  <label class="radio-inline"><input type="checkbox" name="type[]" value="1" @if(in_array(1,$types)) checked @endif>App</label>
					</div>
					<div class="col-md-6 form-check">
					  <label>Status</label><br>
					  <label class="radio-inline">
						  <input type="radio" name="status" value="1" 	@if(@$blog->status == '1') checked="checked" @endif>Active</label>
						  <label class="radio-inline"><input type="radio" name="status" value="0"  @if(@$blog->status == '0') checked="checked" @endif>Inctive</label>
					</div>
					<div class="col-md-12">
						<div class="reset-button">
						<button type="reset" class="btn btn-warning">Reset</button>
						<button type="submit" class="btn btn-success submit">Update</button>
						</div>
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery( ".datepicker" ).datepicker({
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
    });
  });
  function validateVideoFileExtension(fld_value) {
  	if(!/(\.flv|\.avi|\.mov|\.mpg|\.wmv|\.m4v|\.mp4|\.wma|\.3gp)$/i.test(fld_value)) {
      $('#blogVideo').closest('.form-group').find('.help-block').show();
      $('#viewVideo').hide();
  		return false;
  	}
    $('#blogVideo').closest('.form-group').find('.help-block').hide();
    var input = $('#blogVideo')[0];
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              $('#viewVideo').attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]);
      }
      $('#viewVideo').show();
  	return true;
  }

$(document.body).on('click', '.submit', function(){
		// jQuery("#updateBlog").validate({
		 jQuery("form[name='updateBlog']").validate({
			rules: {
				title: "required",
				slug: "required",
			 },
			messages:{
			},
			errorPlacement: function(error, element){
				error.appendTo(element.next());
			},ignore: ":hidden",
			submitHandler: function(form) {
				$(form).find('.submit').attr('disabled',true);
				jQuery('.loading-all').show();
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.updateBlog')!!}",
					data:  new FormData(form),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data) {
						 if(data==1)
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
						location.reload();
						// document.location.href='{!! route("admin.blogMaster")!!}';
						 }
						 else
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
						  alert("Oops Something Problem");
						 }
					}
				});
			}
		});
	});
  jQuery(document).on("keyup", ".blogTitle", function () {
    var str = this.value;
    str = str.replace(/[^a-zA-Z0-9\s]/g,"");
    str = str.toLowerCase();
    str = str.replace(/\s/g,'-');
    $('.blogSlug').val(str);
  });
</script>

<script>
	CKEDITOR.config.removePlugins = 'maximize';
	CKEDITOR.replace('exampleblogEditor');
	CKEDITOR.on('instanceReady', function () {
	$.each(CKEDITOR.instances, function (instance) {
			CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
			CKEDITOR.instances[instance].document.on("paste", CK_jQ);
			CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
			CKEDITOR.instances[instance].document.on("blur", CK_jQ);
			CKEDITOR.instances[instance].document.on("change", CK_jQ);
		});
	});

	function CK_jQ() {
		for (instance in CKEDITOR.instances) {
			CKEDITOR.instances[instance].updateElement();
		}
	}
</script>
