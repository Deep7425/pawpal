@extends('layouts.admin.Masters.Master')
@section('title', 'Organization Master')
@section('content')
    <!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">

            <div class="row  form-top-row">
			   <div class="btn-group mr-1">
                   <a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i>  Add Organization </a>
                </div>
				<div class="btn-group">
                   <a class="btn btn-success" href="javascript:void();">{{$organizations->total()}}</a>
                </div>
				<div class="btn-group head-search">
				<div class="" >
											{!! Form::open(array('route' => 'admin.organizationMaster', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
											<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												</select>
                                            </div>
											<div class="mar-r5 mar-l5">
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="Search By Title" value="{{ old('search') }}"/>
												</div>
											</div>
											<div class="">
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


				 <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>ID</th>
                                        <th>slug</th>
                                        <th>Title</th>
                                        <th>Logo</th>
                                        <th>Password</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($organizations->count() > 0)
								@foreach($organizations as $index => $row)
                                    <tr>
										<td>
											<label>{{$index+($organizations->currentpage()-1)*$organizations->perpage()+1}}.</label>
										</td>
										<td>{{$row->id}}</td>
										<td>{{$row->slug}}</td>
										<td>{{$row->title}}</td>
										<td>@if(!empty($row->logo)) <img src="<?php echo url("/")."/public/organization_logo/".$row->logo;?>" alt="" width="80"> @endif</td>
										<?php $oidUrl =  url("/")."/assessment-admin/".$row->slug;
											$oidFormUrl =  url("/")."/assessment-registration/".$row->slug;
										?>
										<td><code>{{$row->pwd}}</code></td>
										<td>
											<a href="javascript::void();" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Copy Health Assessment Form Link" onclick="copyText('{{$oidFormUrl}}')"><i class="fa fa-copy" aria-hidden="true"></i></a>
											
											<a href="javascript::void();" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Copy Health Assessment Admin Link" onclick="copyText('{{$oidUrl}}')"><i class="fa fa-copy" aria-hidden="true"></i></a>
											<a href="{{ route('admin.viewOrgPay',['id'=>base64_encode($row->id)]) }}" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a>
											<button onclick="editOrg({{$row->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
											<button onclick="deleteOrg({{$row->id}});" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>
										</td>
									</tr>
								@endforeach
								@else
									<tr><td colspan="3">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
		           	<div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
				       <ul class="pagination pagination-large">
					      {{ $organizations->appends($_GET)->links() }}
				      </ul>
			    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content ">
  		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal">×</button>
  			<h4 class="modal-title">Add Organization</h4>
  		</div>
  		<div class="modal-body">
  			<div class="panel panel-bd lobidrag add-organization">
  				
  				<div class="panel-body">
  					{!! Form::open(array('id' => 'addOrganization','name'=>'addOrganization', 'enctype' => 'multipart/form-data')) !!}
  					<div class="row">
					<div class="col-md-6 form-group">
  						<label>Title</label>
  						<input value="" type="text" name="title" class="form-control" placeholder="Enter Title">
  						<span class="help-block"></span>
  					</div>
					<div class="col-md-6 form-group">
  						<label>Url Slug</label>
  						<input value="" type="text" name="slug" class="form-control organizationSlug" placeholder="Enter Slug">
  						<span class="help-block"></span>
  					</div>
					<div class="col-md-6 form-group">
						<label>Logo</label>
					  <input type="file" name="logo" class="form-control" onchange='openFile(event)' id="upload-file-selector"/ placeholder="">
					  <span id="fileselector"></span>
  					</div>
					<div class="col-md-6 form-group">
  					    <img src="" id="blah" alt="" width="100" style="display:none;">
  					</div>
					  <div class="col-md-12">
						<div class="reset-button">
						<button type="reset" class="btn btn-warning">Reset</button>
						<button type="submit" class="btn btn-success submit" id="submit-btn">Submit</button>
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
</div>
<div class="modal fade" id="EditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<!-- /.content-wrapper -->


<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->
<script>
$(document.body).on('click', '.submit', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='addOrganization']").validate({
			rules: {
        title: {
          required: true,
          minlength: 1,
          maxlength: 100,
        },
		slug: {
          required: true
        },
			 },
			messages:{
			},
			errorPlacement: function(error, element){

				error.appendTo(element.parent().find('.help-block'));
			},ignore: ":hidden",
			submitHandler: function(form) {
				$(form).find('.submit').attr('disabled',true);
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.addOrganization')!!}",
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
						 }
						 else if(data ==2){
							jQuery('.loading-all').hide();
								  $(form).find('.submit').attr('disabled',false);
								  alert('Title already exists');
						 }
						 else
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
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
function editOrg(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editOrganization')!!}",
    data:{'id':id},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#EditModal").html(data);
      jQuery('#EditModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
}

function deleteOrg(id) {
	if(confirm('Are you sure want to delete?') == true){
		jQuery('.loading-all').show();
		jQuery.ajax({
		type: "POST",
		dataType : "JSON",
		url: "{!! route('admin.modifyOrganization')!!}",
		data:{'action':'delete','id':id},
		success: function(data)
		{
		 if(data==1)
		 {
		  location.reload();
		 }
		 else
		 {
		  alert("Oops Something Problem");
		 }
		jQuery('.loading-all').hide();
		},
		error: function(error)
		{
			jQuery('.loading-all').hide();
			alert("Oops Something goes Wrong.");
		}
	  });
	}
}
function changePassword(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.modifySubAdmin')!!}",
    data:{'id':id,'action':'openChangePassModal'},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#subAdminEditModal").html(data);
      jQuery('#subAdminEditModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
}

jQuery('.changeStatus').on('click', function() {
  var id = $(this).attr('data-id');
  var status = $(this).attr('status');
  if (status == 1) {
    var text = 'Are you sure to Inactive User ?';
  }
  else {
    var text = 'Are you sure to Active User ?';
  }
  if (confirm(text)) {
    jQuery.ajax({
      url: "{!! route('admin.modifySubAdmin') !!}",
      type: "POST",
      dataType : "JSON",
      data:{'action':'statusChange', 'id':id, 'status':status},
      success: function(result){
        location.reload();

    }}
    );
  }
  else {
    return false;
  }
})
function chnagePagination(e) {
	$("#chnagePagination").submit();
}
function openFile(event) {
  $("#submit-btn").attr('disabled',false);
    var input = event.target;
    var FileSize = input.files[0].size / 1024 /1024; // 10in MB
    var type = input.files[0].type;
    var fileName = input.files[0].name;
    var ext = input.files[0].name.split('.').pop().toLowerCase();
    var reader = new FileReader();
    if(FileSize>3){
    $('#blah').hide();
    $('#fileselector').next(".help-block").remove();
    $('#fileselector').after(' <span class="help-block"><label for="title" generated="true" class="error">Allowed file size exceeded. (Max. 3 MB)</label></span>');

	}
	else if($.inArray(ext, ['png','jpg','jpeg']) >=0){
    $("#submit-btn").attr('disabled',false);
			reader.addEventListener("load", function (){
				if($.inArray(ext, ['png','jpg','jpeg']) >=0){

          $('#blah').attr('src',reader.result);
					$('#blah').show();
					$('#fileselector').next(".help-block").remove();
					$('#fileselector').after(' <span class="help-block" style="color:green;">('+fileName+')File Browsed Successfully.</span>');
				}
				else{
					$('#fileselector').next(".help-block").remove();
          $('#fileselector').after(' <span class="help-block" style="color:green;">('+fileName+')File Browsed Successfully.</span>');
				}
			});
			reader.readAsDataURL(input.files[0]);
			//alert(reader.result);
	    }
        else{
          $("#submit-btn").attr('disabled',true);
          $('#blah').hide();
          $('#fileselector').next(".help-block").remove();
          $('#fileselector').after(' <span class="help-block"><label for="title" generated="true" class="error">Only formats are allowed : (jpeg,jpg,png)</label></span>');
			}
		}
	function copyText(text) {
	  var input = document.body.appendChild(document.createElement("input"));
	  input.value = text;
	  input.select();
	  document.execCommand('copy');
	  input.parentNode.removeChild(input);
	  alert('Copied');
	}	
	jQuery(document).on("keyup", ".organizationTitle", function () {
        var str = this.value;
        str = str.replace(/[^a-zA-Z0-9\s]/g,"");
        str = str.toLowerCase();
        str = str.replace(/\s/g,'-');
        $('.organizationSlug').val(str);
    });
</script>
@endsection
