@extends('layouts.admin.Masters.Master')
@section('title', 'Manage Health Manager')
@section('content')
	 <!-- =============================================== -->
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
                        <h1>Users</h1>
                        <small>Users list</small>
                        <ol class="breadcrumb hidden-xs">
                            <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                            <li class="active">Users</li>
                        </ol>
                    </div>
                </section>
                <!-- Main content -->
                <section class="content">


                    <div class="row">
					@if(session()->get('message'))
					<div class="alert alert-success">
					<strong>Success!</strong> {{ session()->get('message') }}
					</div>
					@endif
                        <div class="col-sm-12">
                            <div class="panel panel-bd lobidrag">
                                <div class="panel-heading">
                                    <div class="btn-group">
                                        <a class="btn btn-success" href="javascript:void();">{{$patients->total()}}</a>
                                    </div>
                                     <div class="btn-group">
                                      <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="panel-header panel-headerTop123">
                                            {!! Form::open(array('route' => 'admin.ManageHealthManager', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                                            <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>
                                            <div class="col-sm-2">
                                                <div class="dataTables_length" id="example_length">
													<label>Page</label>
													<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
														<!-- <option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option> -->
														<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
														<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
														<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
														<option value="500" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '500') selected @endif @endif>500</option>
														<option value="1000" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '1000') selected @endif @endif>1000</option>
														<option value="2000" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '2000') selected @endif @endif>2000</option>
													</select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="dataTables_length">
                                                    <label>From</label>
                                                    <div class="input-group date">
                                                       <input type="text" autocomplete="off" class="form-control fromStartDate" name="start_date" value="@if((app('request')->input('start_date'))!=''){{ base64_decode(app('request')->input('start_date')) }}@endif"/>
                                                       <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i>
                                                       </span>
                                                    </div>
                                               </div>
                                           </div>
                                           <div class="col-sm-2">
                                                <div class="dataTables_length">
                                                    <label>To</label>
                                                    <div class="input-group date">
                                                       <input type="text" autocomplete="off" class="form-control toStartDate" name="end_date" value="@if((app('request')->input('end_date'))!=''){{ base64_decode(app('request')->input('end_date')) }}@endif"/>
                                                       <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
                                                    </div>
                                               </div>
                                           </div>
                                           <div class="col-sm-2">
                                                <div class="dataTables_length">
                                                    <label>Filter</label>
                                                    <div class="input-group custom-search-form">
                                                        <span class="input-group-btn">
                                                          <button class="btn btn-primary" type="submit">
                                                              SEARCH
                                                          </button>
                                                      </span>
                                                    </div><!-- /input-group -->
                                              </div>
                                          </div>
                                          {!! Form::close() !!}
                                  		</div>
                              		</div>
                              <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="success">
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Name / Gender</th>
                                            <th>Mobile No</th>
                                            <th style="width:150px;">Date</th>
                                            <th style="width:150px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									@if($patients->count() > 0)
										@foreach($patients as $index => $pat)
										<?php $pat = (Object)$pat; $getUpdateHealthManage = getUpdateHealthManage(@$pat->id) ?>
                                        <tr class="tbrow">
                                            <td>
                                               <label>{{$index+(@$patients->currentpage()-1)*$patients->perpage()+1}}.</label>
                                            </td>
											<td>{{@$pat->first_name}} {{@$pat->last_name}} @if(!empty(@$pat->gender))/ {{@$pat->gender}}@endif </td>
											<td>{{@$pat->mobile_no}}</td>
											<td><div class="viewSubscription12">@if(count($getUpdateHealthManage) > 0) Updated @endif</div></td>
											<td>
												<button onclick="viewUpdate({{@$pat->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="View Update"><i class="fa fa-eye" aria-hidden="true"></i></button>
									
											</td>
										</tr>
									@endforeach
									@else
										<tr><td colspan="19">No Record Found </td></tr>
									@endif
                    				</tbody>
								</table>
							</div>
						<div class="page-nation text-right">
							<ul class="pagination pagination-large">
							{{ $patients->appends($_GET)->links() }}
							  <!--  <li class="disabled"><span>«</span></li>
								<li class="active"><span>1</span></li>
								<li><a href="#">2</a></li>
								<li class="disabled"><span>...</span></li><li>
								<li><a rel="next" href="#">Next</a></li> -->
							</ul>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section> <!-- /.content -->
	<div class="modal fade" id="EditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div> <!-- /.content-wrapper -->
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery(document).on("click", ".selecAll", function (e) {
        var ids = [];
        if(!this.checked) {
            $('.sub_chk').prop('checked', false);
        $("#sendUserBulkSms").find("#ids").val('');
        }else{
        $('.sub_chk').prop('checked', true);
        $(".sub_chk").each(function(i){
            if(this.checked){
                ids.push(this.value)
            }
        });
        $("#sendUserBulkSms").find("#ids").val(JSON.stringify(ids));
        }
    });
    $('.sub_chk').click(function(e) {
        var flag = 0;
        var ids = [];
        $(".sub_chk").each(function(i){
            if(this.checked){
                ids.push(this.value);
            }
            else{
                flag = 1;
            }
        });
        if(flag == 1){
            $('.selecAll').prop('checked', false);
        }
        else if(flag == 0) {
            $('.selecAll').prop('checked', true);
        }
        $("#sendUserBulkSms").find("#ids").val(JSON.stringify(ids));
        console.log(ids);
    });
    $(".fromStartDate").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'yy-mm-dd',
        //minDate: new Date(),
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate());
            $(".toDOB").datepicker("option", "minDate", dt);
        }
    });
    jQuery('.fromStartDate_cal').click(function () {
        jQuery('.fromStartDate').datepicker('show');
    });
    
    $(".fromFollowupDate").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd-mm-yy',
          minDate: new Date(),
          onSelect: function (selected) {
                var dt = new Date(selected);
                updateCallStatus($(this).attr("user_id"),selected);
            }
    });
    jQuery('.fromfollowup_cal').click(function () {
        jQuery('.fromFollowupDate').datepicker('show');
    });
});

$(".toStartDate").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
    //minDate: new Date(),
    onSelect: function (selected) {
        var dt = new Date(selected);
        dt.setDate(dt.getDate());
        $(".toDOB").datepicker("option", "minDate", dt);
    }
});
jQuery('.toStartDate_cal').click(function () {
    jQuery('.toStartDate').datepicker('show');
});
function chnagePagination(e) {
	$("#chnagePagination").submit();
}


function viewUpdate(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.ViewHealthManager')!!}",
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

</script>
@endsection