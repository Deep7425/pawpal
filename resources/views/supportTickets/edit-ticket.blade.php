<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content updateModel">
        <div class="modal-header">
            <button type="button" class="close" id="closeEditModelX" data-dismiss="modal">×</button>
            <h4 class="modal-title">Update Tickets</h4>
        </div>
        <div class="modal-body">
            <div class="panel panel-bd lobidrag">
                <div class="panel-body">
                    <form id="updateTicketDetail" name="updateTicketDetail" enctype="multipart/form-data" method="post" action="{{ route('update.ticket') }}">
                        @csrf
                        <input type=hidden value="{{ $ticket->id }}" name="id"/>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <!-- Use a dropdown for status -->
                            <select class="form-control" id="status" name="status">
                                <option value="Pending" {{ $ticket->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In-Progress" {{ $ticket->status == 'In-Progress' ? 'selected' : '' }}>In-Progress</option>
                                <option value="Cancelled" {{ $ticket->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="Complete" {{ $ticket->status == 'Complete' ? 'selected' : '' }}>Complete</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <!-- Dropdown for selecting department -->
                            <label for="department">Department</label>
                            <select class="form-control" id="updateDepartmentSelect" name="department" required>
                                    <option value="">Select Department</option>
                                    @foreach(getDepartmentName() as $department)
                                        <option value="{{ $department->id }}" @if ($ticket->assignByUser && $ticket->assignByUser->department_id == $department->id)   selected @endif>{{ $department->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="assign_by">Assign By</label>
{{--                            <select class="form-control dynamic" id="updateAssignUserSelect" name="assign_by" required>--}}
{{--                                <option value="">Select User</option>--}}
{{--                            </select>--}}
                            <select class="form-control dynamic" id="updateAssignUserSelect" name="assign_by" required>
                                <option value="">Select User</option>
{{--                                @foreach($users as $user) <!-- Assuming $users contains the list of users for selection -->--}}
                                <option value="{{ $ticket->assignByUser->id }}" @if ($ticket->assign_by == $ticket->assignByUser->id) selected @endif>{{  $ticket->assignByUser->name }}</option>
{{--                                @endforeach--}}
                            </select>
                        </div>
                        <!-- Hidden fields for ticket ID and department ID -->
{{--                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">--}}
{{--                        <input type="hidden" name="department_id" value="{{ $ticket->assign_by }}">--}}
                        <div class="reset-button">
                            <button type="submit" class="btn btn-success submit">Save</button>
                        </div>
                    </form>

                </div>
            </div>
{{--            <button type="button" class="btn btn-default" data-dismiss="modal" id="closeEditModel">Close</button>--}}
        </div>
    </div>
</div>
<script>$(document).ready(function() {
        $(document).ready(function() {
            $('#closeEditModelX').click(function() {
                location.reload();
            });
        });
        $('#updateDepartmentSelect').change(function() {
            var departmentId = $(this).val();

            $.ajax({
                url: '{{ route("fetch-users") }}',
                type: 'POST',
                data: { department_id: departmentId },
                success: function(response) {
                    $('#updateAssignUserSelect').empty();

                    // Check if response is not empty
                    if (response.length > 0) {
                        // Loop through each user object in the response
                        $.each(response, function(index, user) {
                            // Append an option element to the dropdown for each user
                            $('#updateAssignUserSelect').append('<option value="' + user.id + '">' + user.name + '</option>');
                        });
                    } else {
                        // If no users are returned, display a default option
                        $('#updateAssignUserSelect').append('<option value="">No users found</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>