@extends('layouts.admin.Masters.Master')
@section('title', 'Health Gennie Camp Master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <style>
   #AddModal .panel-bd > .panel-heading {
    color: #14bef0;
    background-color: #f4f9f3;
    border-color: #dedada;
    position: relative;
    padding: 10px;
    font-size: 20px;
    font-weight: 700;
    display: none;
}
#EditModal .panel-bd > .panel-heading {
    color: #14bef0;
    background-color: #f4f9f3;
    border-color: #dedada;
    position: relative;
    padding: 10px;
    font-size: 20px;
    font-weight: 700;
    display: none;
}
    </style>
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
                        <h1>Health Gennie Camp Master</h1>
                        <small>Camp List</small>
                        <ol class="breadcrumb hidden-xs">
                            <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                            <li class="active">Health Gennie Camp Master</li>
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
                                        <a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i>New</a>
                                    </div>
									<div class="btn-group">

                                        <a class="btn btn-success" href="javascript:void();"></a>

                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                     <div class="panel-header panel-headerTop123">
                                        <div class="col-sm-3">
                                            <div class="dataTables_length">
											{!! Form::open(array('route' => 'admin.campMaster', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
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
												<select class="form-control" name="camp_id">
													<option value="">Select Camp Title</option>
												
												</select>
                                            </div>
                                       </div>
										<div class="col-sm-3">
											<div class="dataTables_length">
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="Search By ThyroCare Order Number" value="{{ old('search') }}"/>
												</div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="dataTables_length">
												<div class="input-group custom-search-form">
													<span class="input-group-btn">
													  <button class="btn btn-primary" type="submit">
														  SEARCH
													  </button>
													</span>
												</div><!-- /input-group -->
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
                                        <th>Camp Title</th>
                                        <th>ThyroCare Order Number</th>
                                        <th>ThyroCare Lead Id</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
							
								</tbody>
							</table>
						</div>
			<div class="page-nation text-right">
				<ul class="pagination pagination-large">
					
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
  			<h4 class="modal-title">Add Camp Data</h4>
  		</div>
  		<div class="modal-body">
  			<div class="panel panel-bd lobidrag">
  				<div class="panel-heading">
  				
  				</div>
  				<div class="panel-body">
  					{!! Form::open(array('id' => 'addCamp','name'=>'addCamp')) !!}
  					<div class="form-group col-sm-6">
  						<label>First Name</label>
  						<input value="" type="text" name="first_name" class="form-control" placeholder="Enter First Name">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>Last Name</label>
  						<input value="" type="text" name="last_name" class="form-control" placeholder="Enter Last Name">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>Email</label>
  						<input value="" type="text" name="email" class="form-control" placeholder="Enter Email">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>Mobile No</label>
  						<input value="" type="text" name="mobile_no" class="form-control" placeholder="Enter Mobile No">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>ThyroCare Order Number</label>
  						<input value="" type="text" name="thy_ref_order_no" class="form-control" placeholder="Enter ThyroCare Order Number">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>ThyroCare Lead Id</label>
  						<input value="" type="text" name="thy_lead_id" class="form-control" placeholder="ThyroCare Lead Id">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>Camp Title</label>
						<select class="form-control camp_idF" name="camp_id">
							<option value="">Select Camp Title</option>
							@if(count(getCampTitleMaster())>0)
								@foreach(getCampTitleMaster() as $val)
									<option value="{{$val->id}}">{{$val->title}}</option>
								@endforeach	
							@endif	
							<option value="0">Other</option>
						</select>
  						<span class="help-block"></span>
  					</div>
					<div class="form-group other_titleF col-sm-6" style="display:none;">
  						<label>Other Name of Camp Title</label>
  						<input value="" type="text" name="other_title" class="form-control" placeholder="Name of Camp Title">
  						<span class="help-block"></span>
  					</div>

  					<div class="reset-button col-sm-12">
  					   <button type="reset" class="btn btn-warning">Reset</button>
  					   <button type="submit" class="btn btn-success submit">Submit</button>
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
$(document.body).on('change', '.camp_idF', function(){
	if($(this).val() == '0'){
		$(".other_titleF").show();
	}
	else{
		$(".other_titleF").hide();
	}
});
$(document.body).on('click', '.submit', function(){
		 jQuery("form[name='addCamp']").validate({
			rules: {
				 first_name: "required",
				 last_name: "required",
				 email: "required",
				 mobile_no: "required",
				 thy_ref_order_no: "required",
				 thy_lead_id: "required",
				 camp_id: "required",
				 other_title: "required",
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
				url: "{!! route('admin.addCamp')!!}",
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
function editCamp(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editCamp')!!}",
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
function chnagePagination(e) {
	$("#chnagePagination").submit();
}

</script>
@endsection
