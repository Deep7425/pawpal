<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Page</h4>
		</div>
		<div class="modal-body">
			<div class="update-page">
				<div class="panel-heading">
				</div>
				<div class="panel-body form-groupTtalNew">
					{!! Form::open(array('id' => 'updatePageContent','name'=>'updatePageContent', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
					<input type=hidden value="{{$page->id}}" name="id"/>
					<div class="col-md-6">
					<div class="form-group">
						<label>Title</label>
						<input value="{{@$page->title}}" type="text" name="title" class="form-control blogTitle" placeholder="Enter Title" readonly="">
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" class="form-control blogSlug" placeholder="Slug" value="{{@$page->slug}}" readonly>
                        <span class="help-block"></span>
                    </div>
					</div>
					<div class="col-md-6">
                    <div class="form-group">
                        <label>Language</label>
                        <select class="form-control" name="lng">
                            <option value="">Select Languages</option>
                            <option value="hi" @if($page->lng == 'hi') selected @endif>Hindi</option>
                            <option value="en" @if($page->lng == 'en') selected @endif>English</option>
                        </select>
                        <span class="help-block"></span>
                    </div>
					</div>
					<div class="col-md-12">
					<div class="form-group">
						<label>Description</label>
						<textarea value="{{@$page->description}}" class="form-control" name="description" id="exampleblogEditor" rows="5">{{@$page->description}}</textarea>
					</div>
                    </div>


					<div class="reset-button">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success submit">Update</button>
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

<script type="text/javascript">

$(document.body).on('click', '.submit', function(){
		// jQuery("#updatePageContent").validate({
		 jQuery("form[name='updatePageContent']").validate({
			rules: {
				title: "required",
        slug: "required",
				lng: "required",
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
					url: "{!! route('admin.updatePageContent')!!}",
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
</script>

<script>
	CKEDITOR.config.removePlugins = 'maximize';
	CKEDITOR.config.allowedContent = true;
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

	$('.btn-default').click(function() {
    $('.modal').modal('hide');
});

$('.close').click(function() {
    $('.modal').modal('hide');
});
</script>
