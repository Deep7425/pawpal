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
									 {!! Form::open(array('route' => 'admin.depositReq', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
										<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
											<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>
											<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
											<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
											<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
										</select>
									{!! Form::close() !!}
								  </div>
                                </div>
                                <div class="panel-body">
                          <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
										<th style="width:70px;">S.No.</th>
										<th>Deposit Amount</th>
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
											<td>{{$raw->amount}}</td>
											<td><a href='{{getPath("public/instant-subs-slip/".$raw->slip)}}' target="_blank"><img src='{{getPath("public/instant-subs-slip/".$raw->slip)}}' width="50" height="50"/></td>
											<td>
											<button class="btn @if($raw->status == '0') btn-warning @elseif($raw->status == '1') btn-success @else btn-danger @endif @if($raw->status == 0) changeStatus @endif">@if($raw->status == '0') Pending @elseif($raw->status == '1') Success @else Failed @endif</button>
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
function chnagePagination(e) {
	$("#chnagePagination").submit();
}
</script>
@endsection