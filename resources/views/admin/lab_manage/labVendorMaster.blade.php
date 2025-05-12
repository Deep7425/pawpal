@extends('layouts.admin.Masters.Master')
@section('title', 'Lab Vendor Master')
@section('content')
<?php $days = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="header-icon">
                        <i class="pe-7s-box1"></i>
                    </div>
                    <div class="header-title">
                        <form action="#" method="get" class="sidebar-form search-box pull-right hidden-md hidden-lg hidden-sm">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button type="submit" name="search" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                        <h1>Lab Vendor Master</h1>
                        <small>Lab Vendor List</small>
                        <ol class="breadcrumb hidden-xs">
                            <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                            <li class="active">Lab Vendor Master</li>
                        </ol>
                    </div>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                      @if(session()->get('successMsg'))
                      <div class="alert alert-success">
                        <strong>Success!</strong> {{ session()->get('successMsg') }}
                      </div>
                      @endif
                        <div class="col-sm-12">
                            <div class="panel panel-bd lobidrag">
                                <div class="panel-heading">
                                    <div class="btn-group">
                                        <a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i>  Add Lab Vendor </a>
                                    </div>
									<div class="btn-group">

                                        <a class="btn btn-success" href="javascript:void();">{{$rows->total()}}</a>

                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                     <div class="panel-header panel-headerTop123">
                                        <div class="col-sm-3">
                                            <div class="dataTables_length">
											{!! Form::open(array('route' => 'admin.organizationMaster', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
											<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												</select>
                                            </div>
                                        </div>

										<div class="col-sm-3">
											<div class="dataTables_length">
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="search by title" value="{{ old('search') }}"/>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="dataTables_length">
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

                          </div>
                          <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Title</th>
                                        <th>Address</th>
                                        <th>Timing</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($rows->count() > 0)
								@foreach($rows as $index => $row)
                                    <tr>
										<td>
											<label>{{$index+($rows->currentpage()-1)*$rows->perpage()+1}}.</label>
										</td>
                    <td>{{$row->title}}</td>
                    <td>{{$row->address}}</td>
										<td>
                      <div class="timing">
                        <p><strong>
                        @if(!empty($row->days))
          								@foreach(json_decode($row->days, true) as $key => $day)
                            {{$days[$day]}},
                          @endforeach
                        @endif
                        </strong></p>
                        <p> <strong>{{!empty($row->open_time) ? date('h:i A', strtotime($row->open_time)) : '00:00' }} - {{ !empty($row->close_time) ? date('h:i A', strtotime($row->close_time)) : '00:00' }}</strong> </p>
                      </div>

                    </td>
										<td>
											<button onclick="editOrg({{$row->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
											<button onclick="deleteOrg({{$row->id}});" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></button>
										</td>
									</tr>
								@endforeach
								@else
									<tr><td colspan="5">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
			<div class="page-nation text-right">
				<ul class="pagination pagination-large">
					{{ $rows->appends($_GET)->links() }}
				</ul>
			</div>
			</div>
		</div>
		</div>
	</div>
</section> <!-- /.content -->
<div class="modal fade" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content ">
  		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal">×</button>
  			<h4 class="modal-title">Add Lab Vendor</h4>
  		</div>
  		<div class="modal-body">
  			<div class="panel panel-bd lobidrag">
  				<div class="panel-heading">

  				</div>
  				<div class="panel-body">
  					{!! Form::open(array('id' => 'addLabVendor','name'=>'addLabVendor', 'enctype' => 'multipart/form-data')) !!}
  					<div class="form-group">
  						<label>Title<i class="required_star">*</i></label>
  						<input value="" type="text" name="title" class="form-control" placeholder="Enter Title">
  						<span class="help-block"></span>
  					</div>
            <div class="form-group">
  						<label>Address</label>
  					  <textarea class="form-control" rows="3" name="address"></textarea>
  						<span class="help-block"></span>
  					</div>

            <div class="form-group">
              <label>Day<i class="required_star">*</i></label>
              <div class="check-wrapper checkbox-div">
                @foreach($days as $key => $day)
                <label class="chck-container">
                  <input type="checkbox" class="day_check" name="days[]" value="{{$key  }}">{{$day}}
                  <span class="checkmark"></span>
                </label>
  							@endforeach

              </div>
              <span class="help-block"></span>
            </div>
            <div class="row">
              <div class="form-group col-sm-6">
                <label>Open Time<i class="required_star">*</i></label>
    						<input type="time" name="open_time" class="form-control" value="">
    						<span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label>Close Time<i class="required_star">*</i></label>
    						<input type="time" name="close_time" class="form-control" value="">
    						<span class="help-block"></span>
              </div>
            </div>


  					<div class="reset-button">
  					   <button type="reset" class="btn btn-warning">Reset</button>
  					   <button type="submit" class="btn btn-success submit" id="submit-btn">Submit</button>
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
</div> <!-- /.content-wrapper -->

<script>
$(document.body).on('click', '.submit', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='addLabVendor']").validate({
			rules: {
        title: {
          required: true,
          minlength: 1,
          maxlength: 100,
        },
        "days[]": "required",
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
				$(form).find('.submit').attr('disabled',true);
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.addLabVendor')!!}",
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
    url: "{!! route('admin.editLabVendor')!!}",
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
		url: "{!! route('admin.modifyLabVendor')!!}",
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
      url: "{!! route('admin.modifyLabVendor') !!}",
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
</script>
@endsection
