<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">View Feedback</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.contactQuery') }}"> <i class="fa fa-list"></i> Contact List</a>
					</div>
				</div>
				<div class="panel-body">


          <table class="table table-bordered table-hover">
              <tr>
                <th>Intrested In</th>
                <td>{{$contact->interest_in}} </td>

              </tr>
              <tr>
                <th>Name</th>
                <td>{{$contact->name}} </td>
              </tr>
              <tr>
                <th>Mobile No.</th>
                <td>{{$contact->mobile}}</td>
              </tr>
              <tr>
                <th>E-Mail</th>
                <td>{{$contact->email}} </td>
              </tr>
              <tr>
                <th>Subject</th>
                <td>{{$contact->subject}} </td>
              </tr>
              <tr>
                <th>Messages</th>
                <td>{{$contact->message}} </td>
              </tr>
              <tr>
                <th>Create Date</th>
                <td>{{date('d M Y', strtotime($contact->created_at))}} </td>
              </tr>
          </table>

				</div>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>

	</div>
