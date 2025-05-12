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
						<a class="btn btn-primary" href="{{ route('admin.supportPatAll') }}"> <i class="fa fa-list"></i> Support List</a>
					</div>
				</div>
				<div class="panel-body">


          <table class="table table-bordered table-hover">
              <tr>
                <th>User Name</th>
                 <td>{{@$support->User->first_name.' '.@$support->User->last_name }} </td>

              </tr>
              <tr>
                <th>Name</th>
                <td>{{$support->name}} </td>
              </tr>
              <tr>
                <th>Mobile No.</th>
                <td>{{$support->mobile}}</td>
              </tr>
              <tr>
                <th>E-Mail</th>
                <td>{{$support->email}} </td>
              </tr>
              <tr>
                <th>Subject</th>
                <td>{{$support->subject}} </td>
              </tr>
              <tr>
                <th>Messages</th>
                <td>{{$support->message}} </td>
              </tr>
              <tr>
                <th>Create Date</th>
                <td>{{date('d M Y', strtotime($support->created_at))}} </td>
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
  <script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 

<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>