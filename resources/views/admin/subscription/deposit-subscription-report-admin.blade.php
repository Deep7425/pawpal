@extends('layouts.admin.Masters.Master')
@section('title', 'Deposit Subscription Reports')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<div class="content-wrapper">
             @if (Session::has('message'))
				 <div class="alert alert-info">{{ Session::get('message') }}</div>
			 @endif
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
                        <h1>Deposit Reports</h1>
                        <small>Deposit Reports list</small>
                        <ol class="breadcrumb hidden-xs">
                            <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                            <li class="active">Deposit Amount</li>
                        </ol>
                    </div>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-bd lobidrag">
                                <div class="panel-heading">
									<div class="btn-group">
                                        <a class="btn btn-default" href="javascript:void();">{{$deposits->total()}}</a>
                                    </div>
									<div class="btn-group">
										 <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
									</div>
                                </div>
                                <div class="panel-body">
								<div class="row">
                                     <div class="panel-header panel-header345">
									 {!! Form::open(array('route' => 'admin.depositReqAdmin', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
										<input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>
                                        <div class="col-sm-1">
                                            <div class="dataTables_length">
											<label>Page</label>
												<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
													<option value="500" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '500') selected @endif @endif>500</option>
													<option value="1000" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '1000') selected @endif @endif>1000</option>
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
												<label>City</label>
												<select class="form-control sts" name="city">
													<option value="">All</option>
													@foreach(getCities() as $raw)
														<option value="{{$raw->city}}" @if((app('request')->input('city'))!='')  @if(base64_decode(app('request')->input('city')) == $raw->city) selected @endif @endif >{{$raw->city}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="dataTables_length">
												<label>Status</label>
												<select class="form-control sts" name="status">
													<option value="">All</option>
													<option value="1" @if((app('request')->input('status'))!='') @if(base64_decode(app('request')->input('status')) == '1') selected @endif @endif >Success</option>
													<option value="0" @if((app('request')->input('status'))!='') @if(base64_decode(app('request')->input('status')) == '0') selected @endif @endif >Pending</option>
													<option value="2" @if((app('request')->input('status'))!='') @if(base64_decode(app('request')->input('status')) == '2') selected @endif @endif >Invalid</option>

												</select>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="dataTables_length">
												<label>Submit By</label>
												<select class="form-control sts" name="added_by">
													<option value="">All</option>
													@foreach(getAdmins() as $raw)
														<option value="{{$raw->id}}" @if((app('request')->input('added_by'))!='')  @if(base64_decode(app('request')->input('added_by')) == $raw->id) selected @endif @endif >{{$raw->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-sm-1">
                                            <div class="dataTables_length">
												<label>Filter</label>
												<span class="input-group-btn">
												<button class="btn btn-primary form-control" type="submit">
												  <span class="glyphicon glyphicon-search"></span>
												</button>
												</span>
                                           </div>
										</div>
									{!! Form::close() !!}
                              </div>

                          </div>
                          <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
										<th style="width:70px;">S.No.</th>
										<th>Name</th>
										<th>City</th>
										<th>Deposit Amount</th>
										<th>Pending Amount</th>
										<th>Slip / File</th>
										<th>Status</th>
										<th>Date</th>
									</tr>
                                </thead>
                                <tbody>
								@if($deposits->count() > 0)
									@foreach($deposits as $index => $raw)
										<tr>
											<th>{{$index+($deposits->currentpage()-1)*$deposits->perpage()+1}}.</th>
											<td>{{$raw->Admin->name}}</td>
											<td>{{$raw->Admin->city}}</td>
											<td>{{$raw->amount}}</td>
											<td>{{$raw->Admin->subs_amount}}</td>
											<td><a href='{{getPath("public/instant-subs-slip/".$raw->slip)}}' target="_blank"><img src='{{getPath("public/instant-subs-slip/".$raw->slip)}}' width="50" height="50"/></td>
											<td>
											<a class="btn @if($raw->status == '0') btn-warning @elseif($raw->status == '1') btn-success @else btn-danger @endif @if($raw->status == 0) changeStatus @endif" status="{{$raw->status}}" data-id="{{$raw->id}}" href="javascript:void();">@if($raw->status == '0') Pending @elseif($raw->status == '1') Success @else Invalid @endif</a>
											</td>
											<td>{{date('d-m-Y H:i A',strtotime($raw->created_at))}}</td>
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
					{{ $deposits->appends($_GET)->links() }}
				</ul>
			</div>
			</div>
		</div>
		</div>
	</div>
</section>
</div>
<script type="text/javascript">
jQuery('.changeStatus').on('click', function() {
  var id = $(this).attr('data-id');
  var status = $(this).attr('status');
  $.alert({
	title: 'Alert !',
	content: 'Select Status.',
	draggable: false,
	type: 'green',
	typeAnimated: true,
	buttons: {
		Cancel: function(){
			 // $.alert('Canceled!');
		},
		Confirm: function(){
			updateDepStatus(1,id);
		},
		Invalid : function(){
			updateDepStatus(2,id);
		},
	}
  });
})

function updateDepStatus(val,id){
    jQuery.ajax({
      url: "{!! route('admin.updateDepositReqSts') !!}",
      type: "POST",
      dataType : "JSON",
      data:{'id':id, 'status':val},
      success: function(result){
		$.alert('Status Changed!');
        location.reload();
	  }}
    );
}

jQuery(document).ready(function(){
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
});

function ForExcel() {
	jQuery("#file_type").val("excel");
	$("#chnagePagination").submit();
	jQuery("#file_type").val("");
}
function chnagePagination(e) {
	$("#chnagePagination").submit();
}
</script>
@endsection