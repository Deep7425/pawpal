<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Child Users</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-body">
			  <div class="SubscriptionSe">
			<div class="SubscriptionSe22">
			  <table class="table table-bordered table-hover">
				  <tr>
				    <th>Name</th>
				    <th>Mobile No</th>
					<th>Action</th>
				  </tr>
				  @if($users->count() > 0)
					@foreach($users as $index => $raw)
					  <tr>
						<td>{{@$raw->first_name}} {{@$raw->last_name}}</td>
						<td>{{@$raw->mobile_no}}</td>
						<td><a href="{{route('admin.editUser', base64_encode($raw->id))}}" title="edit user"><span class="fa fa-edit"></span>Edit</a></td>
					</tr>
					@endforeach
					@else
						<tr><td colspan="3">No Record Found </td></tr>
					@endif
			  </table>
			  </div>
			  </div>
		  </div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
	</div>
</div>