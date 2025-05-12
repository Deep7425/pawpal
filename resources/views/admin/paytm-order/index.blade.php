@extends('layouts.admin.Masters.Master')
@section('title', 'Paytm Order List')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"/>


<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<style>
.displyNone{
  display: none;
}
</style>
	 <!-- =============================================== -->
            <!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y ptm-order">
            
                <div class="row form-top-row">

		
				   @if(session()->get('message'))
					<div class="alert alert-success">
					<strong>Success!</strong> {{ session()->get('message') }}
					</div>
				  	@endif
							       <?php
									$start_date = "";
									$end_date = "";
									$type = "";
									$page_no = 25;
									if((app('request')->input('start_date'))!='')
									{
										$start_date = base64_decode(app('request')->input('start_date'));
									}
									if((app('request')->input('end_date'))!=''){
										$end_date = base64_decode(app('request')->input('end_date'));
									}
									if(isset($_GET['page_no']) && base64_decode($_GET['page_no'])) {
										$page_no = base64_decode($_GET['page_no']);
									}
									if(isset($_GET['type']) && base64_decode($_GET['type'])) {
										$type = base64_decode($_GET['type']);
									}
							     	?>
                                    
                                     <!-- <div class="btn-group">
                                      <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
                                    </div> -->
                                  
									

                            

							
									<div class="btn-group">
                                        <a class="btn btn-success" href="javascript:void();">{{$totalOrders}}</a>
                                    </div>
									<div class="btn-group TOPMENU" id="example_length">
									    {!! Form::open(array('route' => 'admin.paytmOrders', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
										<input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>
									</div>
									<div class=" btn-group TOPMENU" id="example_length">
									    {!! Form::open(array('route' => 'admin.paytmOrders', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
										<input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>
									</div>

									<div class="btn-group head-search">
									<div class="col-sm-5">
										
										<div class="input-group date">
											<input type="text" autocomplete="off" class="form-control fromStartDate" name="start_date" value="{{$start_date}}"/>
											<span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i>
											</span>
										</div>
                                    </div>

									<div class="col-sm-4">
										
										<select class="form-control" name="type">
											<option value="">Select</option>
											<option value="SUCCESS" @if($type == 'SUCCESS') selected @endif>SUCCESS</option>
											<option value="FAILURE" @if($type == 'FAILURE') selected @endif>FAILURE</option>
											<option value="PENDING" @if($type == 'PENDING') selected @endif>PENDING</option>
										</select>
									</div>
 
										<div class="col-sm-3">
                                              
												<div class="custom-search-form">
													<span class="input-group-btn">
                                                      <button class="btn btn-primary" type="submit">
                                                          SEARCH
                                                      </button>
                                                  </span>
												</div> </div>
											  {!! Form::close() !!}
                                          </div>

							
				</div>
			


			       <div class="layout-content">

                

                 <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="success">
                                        <tr>
                                            <th>S.No.</th>
                                            <th class="tab-appointment">Order Id</th>
                                            <th>Txn Id</th>
                                            <th>Payment Status</th>
                                            <th>Payment Mode</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                      									@if(count($orders) > 0)
                                        <?php $i = 1 ?>
                      										@foreach($orders as $index => $order)

                                          @if(count($order) > 0)
                        										@foreach($order as $key => $raw)
                                                              <tr class="tbrow page @if($index != '1') displyNone @endif" page="{{$index}}">
                      											<td>
                                                                     <label>{{$i++}}.</label>
                                                                  </td>
                      											<td class="tab-appointment12">{{@$raw['merchantOrderId']}}</td>
                      											<td class="tab-appointment12">{{@$raw['txnId']}}</td>
                      											<td class="tab-appointment12">{{@$raw['orderSearchStatus']}}</td>
                      											<td class="tab-appointment12">{{@$raw['payMode']}}</td>
                      											<td class="tab-appointment12">{{@$raw['amount']}}</td>
                      											<td class="tab-appointment12">@if(isset($raw['orderCreatedTime'])){{date('d-m-Y h:i A',strtotime($raw['orderCreatedTime']))}} @endif</td>
                      										</tr>
                                          @endforeach
                                          @endif
                      									@endforeach
                      									@else
                      										<tr><td colspan="19">No Record Found </td></tr>
                      									@endif
                    				        </tbody>
								</table>
								</div>
                @if(count($pageNos) > 1)
                <div class="page-nation text-right d-flex justify-content-end mb-2 mr-2">
                    <ul class="pagination pagination-large">
                        <ul class="pagination" role="navigation">

        										@foreach($pageNos as $index => $raw)
                              <li class="page-item @if($raw == 1) active @endif " aria-current="page" data-page="{{$raw}}"><span class="page-link">{{$raw}}</span></li>
                            @endforeach

                        </ul>
                    </ul>
                </div>
                  @endif
							

			   </div>
			
		    </div>
        </div>
   </div>
</div>

  
<script src="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>

    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>

<!-- /.content-wrapper -->
<script type="text/javascript">
jQuery(document).ready(function(){
	$(".fromStartDate").datepicker({
                format: 'yyyy-mm-dd',
                onSelect: function (selected) {
                    var dt = new Date(selected);
                    dt.setDate(dt.getDate());
                    // Your logic here based on the selected date
                }
            }).on('changeDate', function () {
                $(this).datepicker('hide');
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
		}
	});
	jQuery('.toStartDate_cal').click(function () {
		jQuery('.toStartDate').datepicker('show');
	});

	var dt = new Date($(".fromStartDate").val());
	dt.setDate(dt.getDate());
	var edt = new Date($(".fromStartDate").val());
	edt.setDate(edt.getDate() + 1);
	$(".toStartDate").datepicker("option", "minDate", dt);
	$(".toStartDate").datepicker("option", "maxDate", edt);
});

function chnagePagination(e) {
	$("#chnagePagination").submit();
}
jQuery(document).on("click", ".page-item", function () {
  var page = $(this).data('page');
  $('.page-item').removeClass('active');
  $(this).addClass('active');
  $('table > tbody  > tr').each(function(index, tr) {
     if ($(tr).attr('page') != page) {
       $(tr).addClass('displyNone');
     }else{
       $(tr).removeClass('displyNone');
     }
  });
  $("html, body").animate({ scrollTop: 0 }, "slow");
});
</script>
@endsection
