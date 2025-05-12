@extends('layouts.admin.Masters.Master')
@section('title', 'Organization Payment Master')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">
             
			@if(session()->get('successMsg'))
			  <div class="alert alert-success">
				<strong>Success!</strong> {{ session()->get('successMsg') }}
			  </div>
			  @endif

             <div class="row">

			 <div class="col-3">
			 <div class="btn-group">
								<a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i>Add New payment</a>
							</div>
							<div class="btn-group">
								<a class="btn btn-success" href="javascript:void();">{{count($records)}}</a>
							</div>
			 </div>

			 </div>

			<div class="layout-content card mt-2">
			<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>S.No.</th>
									<th>Org Name</th>
									<th>Amount</th>
									<th>Remaining Amount</th>
									<th>Created At</th>
								</tr>
							</thead>
							<tbody>
							@if($records->count() > 0)
							@foreach($records as $index => $row)
								<tr>
									<td>
										<label>{{$index+1}}.</label>
									</td>
									<td>{{$row->OrganizationMaster->title}}</td>
									<td>{{$row->amount}}</td>
									<td>{{$row->remaining_amount}}</td>
									<td>{{date('d-m-Y h:i A',strtotime($row->created_at))}}</td>
								</tr>
							@endforeach
							@else
								<tr><td colspan="6">No Record Found </td></tr>
							@endif
							</tbody>
						</table>
					</div>
		 
		    </div>

			<div class="modal fade" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content ">
  		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal">×</button>
  			<h4 class="modal-title">Add New Payment</h4>
  		</div>
	
  		<div class="modal-body">
		  <div class="">
  				
  				<div class="panel-body">
  					{!! Form::open(array('id' => 'addNewPay','name'=>'addNewPay')) !!}
					<input value="{{$organization_id}}" type="hidden" name="organization_id" class="form-control"/>
  					<div class="form-group">
  						<label>Total Amount</label>
  						<input value="" type="text" name="amount" class="form-control" placeholder="Enter Amount"/>
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
		   </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
		

<script>
$(document.body).on('click', '.submit', function(){
 jQuery("form[name='addNewPay']").validate({
	rules: {
		amount: {
		  required: true,
		  number:true,
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
			url: "{!! route('admin.addNewPay')!!}",
			data:  new FormData(form),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data) {
				 if(data==1){
				  jQuery('.loading-all').hide();
				  $(form).find('.submit').attr('disabled',false);
				  location.reload();
				 }
				 else{
				  jQuery('.loading-all').hide();
				  $(form).find('.submit').attr('disabled',false);
				  alert("Oops Something Problem");
				 }
			},
		  error: function(error){
			  jQuery('.loading-all').hide();
			  alert("Oops Something goes Wrong.");
		  }
		});
	}
});
});
$(document.body).on('click', '.editRec', function(){
  var payId = $(this).attr("payId");
  $(".newPayId").val(payId);
});

</script>
@endsection