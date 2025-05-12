@extends('layouts.admin.Masters.Master') 
@section('title', 'Add Blog') 
@section('content')


<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />



<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
        <div class="container-fluid flex-grow-1 container-p-y">
        <!-- <h4 class="font-weight-bold py-3 mb-0">Blogs List</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Admin</a></li>
                                <li class="breadcrumb-item active"><a href="#!">Blogs List</a></li>
                            </ol>
                        </div> -->
            <div class="layout-content card-body card" >
              <div class="panel-body">

                        {!! Form::open(array('route' => 'admin.addBlog', 'id' => 'addBlog','enctype' => 'multipart/form-data' , 'class' => 'col-sm-12')) !!}
                        <div class="row">
                        <div class="form-group col-md-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control blogTitle" placeholder="Enter Title">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Url</label>
                            <input type="text" name="slug" class="form-control blogSlug" placeholder="Enter Slug">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Keyword</label>
                            <input type="text" name="keyword" class="form-control" placeholder="Enter Keyword">
                            <span class="help-block"></span>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Description</label>
                            <input type="text" name="blog_desc" class="form-control" placeholder="Blog Description For Seo">
                            <span class="help-block"></span>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label>Description</label>
                            <textarea class="form-control" name="description" id="exampleblogEditor" rows="5"></textarea>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Blog Image</label>
                            <input type="file" name="image" class="form-control" placeholder="Enter Disease Name">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group col-md-3">
						
							<label>Blog Video</label>
								<input type="text" name="video" class="form-control" placeholder="" />
								 <span class="help-block"></span>
							
						</div>
                        <div class="form-group col-md-3">
                            <label>Publish Date</label>
                            <input type="text" class="form-control datepicker" placeholder="dd-mm-YYYY" value="{{date('d-m-Y')}}" name="publish_date" autocomplete="off" readonly />
                            <span class="help-block"></span>
                        </div>
						<div class="form-group col-md-3">
                            <label>Top Show By Date</label>
                            <input type="text" class="form-control datepicker" placeholder="dd-mm-YYYY" value="{{date('d-m-Y')}}" name="show_date" autocomplete="off" readonly />
                            <span class="help-block"></span>
                        </div>
                        <div class="form-check col-md-3">
                            <label>Type</label>
                            <br>
                            <label class="radio-inline">
                                <input type="checkbox" name="type[]" value="2" checked="checked">Web</label>
                            <label class="radio-inline">
                                <input type="checkbox" name="type[]" value="1" checked>App</label>
                        </div>
                        <div class="form-check col-md-3">
                            <label>Status</label>
                            <br>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="1" checked="checked">Active</label>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="0">Inctive</label>
                        </div>
                        <div class="reset-button col-md-12">
                            <button type="reset" class="btn btn-warning">Reset</button>
                            <button type="submit" class="btn btn-success submit">Save</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    </div>

            </div>
        </div>
   </div>
</div>
</div>


    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script type="text/javascript">
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
    jQuery(document).ready(function() {
        jQuery( ".datepicker" ).datepicker({
          dateFormat: 'dd-mm-yy',
          changeMonth: true,
          changeYear: true,
        });
        jQuery("#addBlog").validate({
            rules: {
                title: "required",
                slug: "required",
            },
            messages: {},
            errorPlacement: function(error, element) {
                error.appendTo(element.next());
            },
            ignore: ":hidden",
            submitHandler: function(form) {
				jQuery('.loading-all').show();
              	var flag = true;
				if (flag == true) {
                  $(form).find('.submit').attr('disabled', true);
                  jQuery.ajax({
                      type: "POST",
                      dataType: "JSON",
                      url: "{!! route('admin.addBlog')!!}",
                      data: new FormData(form),
                      contentType: false,
                      cache: false,
                      processData: false,
                      success: function(data){
                        //  alert(data)
                        //  console.log("data" ,  data)
                          if (data == 1) {

                              jQuery('.loading-all').hide();
                              $(form).find('.submit').attr('disabled', false);
                              document.location.href = '{!! route("admin.blogMaster")!!}';
                          } else {
                              jQuery('.loading-all').hide();
                              $(form).find('.submit').attr('disabled', false);
                              alert("Oops Something Problem");
                          }
                      }
                  });
                }

            }
        });
    });
    jQuery(document).on("keyup", ".blogTitle", function() {
        var str = this.value;
        str = str.replace(/[^a-zA-Z0-9\s]/g, "");
        str = str.toLowerCase();
        str = str.replace(/\s/g, '-');
        $('.blogSlug').val(str);
    });
</script>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
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
@endsection
