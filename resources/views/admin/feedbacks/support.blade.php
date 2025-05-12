@extends('layouts.admin.Masters.Master')
@section('title', 'Support List')
@section('content')
	<!-- Content Wrapper. Contains page content -->

	
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
          <div class="container-fluid flex-grow-1 container-p-y">


		  <div class="row ml-1 form-top-row">

		  <div class="btn-group">
			 <a class="btn btn-success" href="javascript:void();">{{$supports->total()}}</a>
		  </div>

		  <div class="btn-group head-search">
		         <div class="ml-sm-2">
	
							{!! Form::open(array('route' => 'admin.supportPatAll', 'id' => 'chnagePagination', 'method'=>'POST')) !!}

							<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
								<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
								<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
								<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
								<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
								<option value="300" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '300') selected @endif @endif>300</option>
							</select>
						</div>

						<div class="ml-sm-2">
					
							<input name="search" placeholder="Enter Name Here" type="text" class="form-control capitalizee"  value="@if(isset($_GET['search'])) {{base64_decode($_GET['search'])}}  @endif">
						</div>

						<div class="">
							
											<div class="input-group custom-search-form">
												<span class="input-group-btn">
												<button class="btn btn-primary form-control" type="submit">
												  SEARCH
												</button>
												</span>
											</div>
										</div>

										{!! Form::close() !!}

		    </div>
		  </div>


		  <div class="layout-content">

		  <div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
									<tr>
										<th>S.No.</th>
										<th>Name</th>
										<th>Mobile</th>
										<th style="width:180px;">E-Mail</th>
										<th style="width: 210px;">Messages</th>
										<th>Note</th>
										<th>Type</th>
										<th style="width:90px;">Created Date</th>
										<th style="width: 150px;">Action</th>
									</tr>
									</thead>
									<tbody>
									@if($supports->count() > 0)
										@foreach($supports as $index => $element)
											<tr>
												<td>
													<label>{{$index+($supports->currentpage()-1)*$supports->perpage()+1}}.</label>
												</td>
												<td>{{@$element->User->first_name.' '.@$element->User->last_name }} </td>
												<td>{{@$element->User->mobile_no}}</td>
												<td style="width:180px;">{{@$element->User->email}}</td>
												<td style="width: 210px;">{{$element->message}}</td>
												<td>{{$element->note}}</td>
												<td><span class="label-default label label-success">@if($element->type == '1') Web @else App @endif</span></td>
												<td>{{date('d M Y h:i A', strtotime($element->created_at))}} </td>
												<td> 
													<div style="width: 150px;"><a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="viewFeedback({{$element->id}});"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;
														<a href="javascript:void(0);" user_mobile="{{@$element->User->mobile_no}}" user_id="{{@$element->User->id}}" class="btn btn-info btn-sm sendMsgModal" ><i class="fa fa-paper-plane" aria-hidden="true"></i></a>
														
														<a href="javascript:void(0);" sid="{{base64_encode(@$element->id)}}" note="{{$element->note}}" class="btn btn-info btn-sm addSupportNote" ><i class="fa fa-edit" aria-hidden="true"></i></a>

														<a style="float:none;" href="javascript:void(0);" pkey="{{base64_encode(@$element->id)}}" r_from="1" class="btn btn-info btn-sm manageSprt" title="Manage Leads"><img src='{{ url("/img/customer-care-icon.png") }}'/></a>

													</div>
												</td>

											</tr>
										@endforeach
									@else
										<tr><td colspan="8">No Record Found </td></tr>
									@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
							<ul class="pagination pagination-large">
								{{ $supports->appends($_GET)->links() }}
							</ul>
						</div>

					 </div>
	 			</div>
 			</div>

		<div class="modal md-effect-1 md-show" id="sendMsgModal" role="dialog" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content ">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">×</button>
						<h4 class="modal-title">Send Reply</h4>
					</div>
					<div class="modal-body">
						<div class="panel panel-bd lobidrag feedback">
							<div style="padding-bottom:15px;">
								<div class="btn-group">
									<a class="btn btn-primary" href="{{ route('admin.supportPatAll') }}"> <i class="fa fa-list"></i> Support List</a>
								</div>
							</div>
							<div class="panel-body">
								{!! Form::open(array('id' => 'sendSupportReply','name'=>'sendSupportReply', 'enctype' => 'multipart/form-data')) !!}
								<input name="mobile_no" value="" id="mobile_no" type="hidden"/>
								<input name="user_id" value="" id="user_id" type="hidden"/>
								<div class="form-group">
									<label>Text Message (Max:255 Character Allowed)</label>
									<textarea value="" type="text" name="msg" class="form-control msg" placeholder="Text Message"></textarea>
									<span class="help-block"></span>
								</div>
								<div class="reset-button">
									<button type="reset" class="btn btn-warning">Reset</button>
									<button type="submit" class="btn btn-success send" id="upload-btn">Send</button>
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

		<div class="modal fade" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content ">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">×</button>
						<h4 class="modal-title">Add Note</h4>
					</div>
					<div class="modal-body feedback">
						{!! Form::open(array('id' => 'addNote','name'=>'addNote')) !!}
						<input type="hidden" name="id" id="sId" value="">
						<input type="hidden" name="note_type" value="4">
						<div class="form-group">
							<label>Note:</label>
							<textarea type="text" name="note" rows="5" class="form-control" id="note" placeholder="Write Note..."></textarea>
							<span class="help-block"></span>
						</div>

						<div class="reset-button">
							<button type="reset" class="btn btn-warning">Reset</button>
							<button type="submit" class="btn btn-success submit">Save</button>
						</div>
						{!! Form::close() !!}
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>

			</div>
		</div>

		<div class="modal md-effect-1 md-show" id="viewModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
		</div>


	<script>
$('.btn-default').click(function() {
    $('.modal').modal('hide');
});

$('.close').click(function() {
    $('.modal').modal('hide');
});

           jQuery(document).on("click", ".addSupportNote", function () {
			$('#sid').val('');
			$('#note').val('');
			var sid = $(this).attr('sid');
			var note = $(this).attr('note');
			$('#sId').val(sid);
			$('#note').val(note);
			$('#AddModal').modal('show');
		});

		function chnagePagination(e) {
			$("#chnagePagination").submit();
		}
		function viewFeedback(id) {
			jQuery('.loading-all').show();
			jQuery.ajax({
				type: "POST",
				dataType : "HTML",
				url: "{!! route('admin.viewSupport')!!}",
				data:{'id':id},
				success: function(data)
				{
					jQuery('.loading-all').hide();
					jQuery("#viewModal").html(data);
					jQuery('#viewModal').modal('show');
				},
				error: function(error)
				{
					jQuery('.loading-all').hide();
					alert("Oops Something goes Wrong.");
				}
			});
		}

		$(document.body).on('click', '.sendMsgModal', function(){
			$("#mobile_no").val($(this).attr("user_mobile"));
			$("#user_id").val($(this).attr("user_id"));
			$(".msg").val("");
			$("#sendMsgModal").modal("show");
		});
		jQuery("#sendSupportReply").validate({
			// jQuery("form[name='sendSupportReply']").validate({
			rules: {
				msg: {
					required: true,
					minlength: 5,
					maxlength: 255,
				},
			},
			messages:{
			},
			errorPlacement: function(error, element){
				error.appendTo(element.parent().find('.help-block'));
			},ignore: ":hidden",
			submitHandler: function(form) {
				$(form).find('.send').attr('disabled',true);
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.sendSupportReply')!!}",
					data:  new FormData(form),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data) {
						if(data==1)
						{
							jQuery('.loading-all').hide();
							$(form).find('.send').attr('disabled',false);
							alert("Message Send Successfully..");
							//location.reload();
							$("#sendMsgModal").modal("hide");

						}
						else
						{
							jQuery('.loading-all').hide();
							$(form).find('.send').attr('disabled',false);
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


		$(document.body).on('click', '.submit', function(){
			jQuery("form[name='addNote']").validate({
				rules: {
					note: "required"
				},
				messages:{
				},
				errorPlacement: function(error, element){
					error.appendTo(element.parent().find('.help-block'));
				},ignore: ":hidden",
				submitHandler: function(form) {
					$(form).find('.submit').attr('disabled',true);
					jQuery('.loading-all').show();
					jQuery.ajax({
						type: "POST",
						dataType : "JSON",
						url: "{!! route('admin.addNote')!!}",
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
								$.alert("Oops Something Problem");
							}
						},
						error: function(error)
						{
							jQuery('.loading-all').hide();
							$.alert("Oops Something goes Wrong.");
						}
					});
				}
			});
		});
		// });
	</script>
@endsection
