@extends('layouts.admin.Masters.Master')
@section('title', 'Instant Subscription Reports')
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
                        <h1>Instant Subscription Reports</h1>
                        <small>Instant Subscription Reports list</small>
                        <ol class="breadcrumb hidden-xs">
                            <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                            <li class="active">Instant Subscription</li>
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
                                        <a class="btn btn-danger" href="javascript:void();">Account Balance ({{$adminData->subs_amount}})</a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-warning" href="javascript::void(0)" data-toggle="modal" data-target="#AddNewAmtModal">Daily Report</a>
									</div>
									<div class="btn-group">
                                        <a class="btn btn-success" title="Amount deposit to the company" href="javascript::void(0)" data-toggle="modal" data-target="#depositAmountModal">Deposit</a>
									</div>
									<div class="btn-group">
                                        <a class="btn btn-default" href="javascript:void();">{{$instReport->total()}}</a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                     <div class="panel-header panel-headerTop123">
									 {!! Form::open(array('route' => 'admin.instantSubsReport', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                                        <div class="col-sm-3">
                                            <div class="dataTables_length">
												<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												</select>
                                            </div>
                                        </div>
										<!--<div class="col-sm-3">
											<div class="dataTables_length">
												<div class="input-group custom-search-form">
													<span class="input-group-btn">
													  <button class="btn btn-primary" type="submit">
														  SEARCH
													  </button>
													</span>

												</div>
											</div>
										</div>-->
									{!! Form::close() !!}
                              </div>
                          </div>
                          <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
										<th style="width:70px;">S.No.</th>
										<th>Total Visited Students</th>
										<th>Online Plan</th>
										<th>Cash Plan</th>
										<th>Amount</th>
										<th>Date</th>
									</tr>
                                </thead>
                                <tbody>
								@if($instReport->count() > 0)
									@foreach($instReport as $index => $raw)
										<tr>
											<th>{{$index+($instReport->currentpage()-1)*$instReport->perpage()+1}}.</th>
											<td>{{$raw->total_students}}</td>
											<td>{{$raw->plan_online}}</td>
											<td>{{$raw->plan_cash}}</td>
											<td>{{$raw->amount}}</td>
											<td>{{date('d-m-Y h:i A',strtotime($raw->created_at))}}</td>
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
					{{ $instReport->appends($_GET)->links() }}
				</ul>
			</div>
			</div>
		</div>
		</div>
	</div>
</section>
<div class="modal fade" id="AddNewAmtModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content ">
  		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal">×</button>
  			<h4 class="modal-title">Add Today Report</h4>
  		</div>
  		<div class="modal-body">
  			<div class="panel panel-bd lobidrag">
  				<div class="panel-heading">

  				</div>
  				<div class="panel-body">
  					{!! Form::open(array('name'=>'insertSubsAmount')) !!}
					
  					<div class="form-group">
  						<input type="checkbox" value="1" name="off_today" id="isOff" />
						<label>Day Off Today</label>
  						<span class="help-block"></span>
  					</div>
					<div class="form-group">
  						<label>Total Visited Students</label>
  						<input type="text" name="total_students" class="form-control" placeholder="Enter Total Visited Students"/>
  						<span class="help-block"></span>
  					</div>
					<div class="form-group">
  						<label>Online Plan</label>
  						<input type="text" name="plan_online" class="form-control" placeholder="Enter Total Online Plan"/>
  						<span class="help-block"></span>
  					</div>
					<div class="form-group">
  						<label>Cash Plan</label>
  						<input type="text" name="plan_cash" class="form-control cashPlan" placeholder="Enter Total Cash Plan"/>
  						<span class="help-block"></span>
  					</div>
					<div class="form-group">
  						<label>Amount</label>
  						<input type="text" name="amount" readonly class="form-control cashAmt" placeholder="Enter Total Cash Amount"/>
  						<span class="help-block"></span>
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

<div class="modal fade" id="depositAmountModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content ">
  		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal">×</button>
  			<h4 class="modal-title">Deposit Amount Request To The Company</h4>
  		</div>
  		<div class="modal-body">
  			<div class="panel panel-bd lobidrag">
  				<div class="panel-heading">
					<button class="btn btn-danger" href="javascript:void();">Account Balance ({{$adminData->subs_amount}})</button>
  				</div>
				@if($adminData->subs_amount >= 500)
  				<div class="panel-body">
  					{!! Form::open(array('name'=>'depositAmt', 'enctype' => 'multipart/form-data')) !!}
  					<div class="form-group">
  						<label>Amount</label>
  						<input type="text" name="amount" class="form-control depAmt" placeholder="Enter Deposit Amount">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group">
						<label>Slip / File</label>
					  <input type="file" name="slip" class="form-control">
					  <span class="help-block"></span>
  					</div>
  					<div class="reset-button">
  					   <button type="reset" class="btn btn-warning">Reset</button>
  					   <button type="submit" class="btn btn-success submit" id="submit-btn">Submit</button>
  					</div>
  				 {!! Form::close() !!}
  				</div>
				@else
					<div class="panel-body">
						<h2>Deposit are accepted for amounts greater than 499 rupees</h2>
					</div>
				@endif	
  			</div>
  		</div>
  		<div class="modal-footer">
  			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  		</div>
  	</div>
  	</div>
</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function () {
	$(document.body).on('click', '.submit', function(){
	 jQuery("form[name='insertSubsAmount']").validate({
		rules: {
			total_students: {
			  required: true,
			  number : true
			},
			plan_cash: {
			  required: true,
			  number : true
			},
			amount: {
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
			jQuery('.loading-all').hide();
			jQuery.ajax({
				type: "POST",
				dataType : "JSON",
				url: "{!! route('admin.insertSubsAmount')!!}",
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
jQuery(document).on("keyup keypress", ".cashPlan", function (e) {
    if($(this).val()=="."){
      $(this).css('border','1px solid red');
      alert('enter correct value');
      $(this).val("");
      return false;
    }
    else if(e.which < 48 || e.which > 57) {
      $(this).css('border','');
	  setVal($(this).val());
      return false;
    }
    else if($(this).val()!=""){
	 setVal($(this).val());
      $(this).css('border','');
    }
    else
    $(this).css('border','1px solid red');
});

jQuery(document).on("keyup keypress", ".depAmt", function (e) {
    if($(this).val()=="."){
      $(this).css('border','1px solid red');
      alert('enter correct value');
      $(this).val("");
      return false;
    }
    else if(e.which < 48 || e.which > 57) {
      $(this).css('border','');
      return false;
    }
    else if($(this).val()!=""){
      $(this).css('border','');
    }
    else
    $(this).css('border','1px solid red');
});

function setVal(cc){
	$(".cashAmt").val(cc * 500);
}
$("#isOff").on('change', function() {
	 if($(this).is(':checked')) {
		 $("input[name=total_students]").val(0);
		 $("input[name=plan_online]").val(0);
		 $("input[name=plan_cash]").val(0);
		 $("input[name=amount]").val(0);
	 }
	 else{
		 $("input[name=total_students]").val("");
		 $("input[name=plan_online]").val("");
		 $("input[name=plan_cash]").val("");
		 $("input[name=amount]").val("");
	 }
});
$(document.body).on('click', '.submit', function(){
	 jQuery("form[name='depositAmt']").validate({
		rules: {
			amount: {
			  required: true,
			  min:2500,
			},
			slip: {
			  required: true
			},
		},
		errorPlacement: function(error, element){
			error.appendTo(element.parent().find('.help-block'));
		},ignore: ":hidden",
		submitHandler: function(form) {
			let depAmount = $(".depAmt").val();
			  $.alert({
				title: 'Alert !',
				content: 'Would you like to submit this request with an amount of Rs. '+depAmount+'?',
				draggable: false,
				type: 'red',
				typeAnimated: true,
				buttons: {
					Cancel: function(){
						 // $.alert('Canceled!');
					},
					Confirm: function(){
						jQuery('.loading-all').hide();
						$(form).find('.submit').attr('disabled',true);
						jQuery.ajax({
							type: "POST",
							dataType : "JSON",
							url: "{!! route('admin.depositAmt')!!}",
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
						
					},
					
				}
			  });
		}
	});
});
});
function chnagePagination(e) {
	$("#chnagePagination").submit();
}
</script>
@endsection