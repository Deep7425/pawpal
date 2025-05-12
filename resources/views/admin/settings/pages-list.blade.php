@extends('layouts.admin.Masters.Master')
@section('title', 'Dynamic Pages')
@section('content')

    <!-- Content Wrapper. Contains page content -->
 <div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">

            <div class="container-fluid flex-grow-1 container-p-y">

          <div class="row form-top-row">
               
                        <div class="btn-group mr-1">
                            <a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i>  Add Page</a>
                         </div>

						<div class="btn-group">
                             <a class="btn btn-success" href="javascript:void();">{{$pages->total()}}</a>
                        </div>


                        <div class="btn-group head-search">

                                         <div class="ml-sm-2">
											{!! Form::open(array('route' => 'admin.PagesList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
												<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												</select>
                                            </div>

                                            <div class=" ml-sm-2">
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="Search By Title" value="{{ old('search') }}"/>

												</div>
											</div>

                                            <div class="ml-sm-2">
												<div class="input-group custom-search-form">
													<span class="input-group-btn">
													  <button class="btn btn-primary" type="submit">
														  SEARCH
													  </button>
													</span>
												</div>
											{!! Form::close() !!}
											</div>
                        </div>

          </div>

            <div class="layout-content">

            <div class="table-responsive table-container">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Language</th>
                                        <th style="width:105px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($pages->count() > 0)
								@foreach($pages as $index => $page)
                                    <tr>
										<td>
											<label>{{$index+($pages->currentpage()-1)*$pages->perpage()+1}}.</label>
										</td>
                                        <td>{{$page->title}}</td>
                                        <td>{{$page->slug}}</td>
                                        <td>@if($page->lng == "hi") Hindi @else English @endif</td>
										<td style="width:105px;">
									    <div class="QR-Code-top12" style="width:105px; float:left;">
											<button onclick="editPage({{$page->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
										</div>
										</td>
									</tr>
								@endforeach
								@else
									<tr><td colspan="6">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
			<div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
				<ul class="pagination pagination-large">
					{{ $pages->appends($_GET)->links() }}
					<!--<li class="disabled"><span>«</span></li>
					<li class="active"><span>1</span></li>
					<li><a href="#">2</a></li>
					<li class="disabled"><span>...</span></li><li>
					<li><a rel="next" href="#">Next</a></li> -->
				</ul>
			</div>

            </div>

            </div> 
         </div> 

         <div class="modal fade modal-dialog1234" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog add-dynamic-page">
      <!-- Modal content-->
      <div class="modal-content ">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title">Add Dynamic Page</h4>
        </div>
        <div class="modal-body">
            <div class="panel panel-bd lobidrag">
                
                <div class="panel-body form-groupTtalNew">
                    {!! Form::open(array('id' => 'AddDynamicPage','name'=>'AddDynamicPage', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
                    <div class="col-md-6">
                    <div class="form-group">
                        <label>Title</label>
                        <input value="" type="text" name="title" class="form-control blogTitle" placeholder="Enter Title">
                        <span class="help-block"></span>
                    </div>
					</div>
					<div class="col-md-6">
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" class="form-control blogSlug" placeholder="Slug" readonly>
                        <span class="help-block"></span>
                    </div>
					</div>
					<div class="col-md-6">
                    <div class="form-group">
                        <label>Language</label>
                        <select class="form-control" name="lng">
                            <option value="">Select Languages</option>
                            <option value="hi">Hindi</option>
                            <option value="en">English</option>
                        </select>
                        <span class="help-block"></span>
                    </div>
					</div>
					<div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea value="" class="form-control" name="description" id="description" rows="5"></textarea>
                    </div>
                       </div>


                    <div class="reset-button">
                       <button type="reset" class="btn btn-warning">Reset</button>
                       <button type="submit" class="btn btn-success submit" id="submit-btn">Submit</button>
                    </div></div>
                 {!! Form::close() !!}
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
    </div>
</div>

     </div> 

     <div class="modal fade" id="pageEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>

 </div> 
 <!-- /.content -->


 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 <!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->


<!-- /.content-wrapper -->
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script>
    jQuery(document).on("keyup", ".blogTitle", function() {
        var str = this.value;
        str = str.replace(/[^a-zA-Z0-9\s]/g, "");
        str = str.toLowerCase();
        str = str.replace(/\s/g, '-');
        $('.blogSlug').val(str);
    });

    $(document.body).on('click', '.submit', function(){
        // jQuery("#updatePageContent").validate({
         jQuery("form[name='AddDynamicPage']").validate({
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
                    url: "{!! route('admin.AddDynamicPage')!!}",
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
function editPage(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editPageContent')!!}",
    data:{'id':id,'action':'1'},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#pageEditModal").html(data);
      jQuery('#pageEditModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
}

function chnagePagination(e) {
	$("#chnagePagination").submit();
}
</script>
<script>
    CKEDITOR.config.removePlugins = 'maximize';
	CKEDITOR.config.allowedContent = true;
    CKEDITOR.replace('description');
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
