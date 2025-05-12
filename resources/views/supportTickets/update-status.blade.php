<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content updateModel">
        <div class="modal-header">
            <button type="button" class="close" id="closeEditModelX" data-dismiss="modal">×</button>
            <h4 class="modal-title">Reply Message</h4>
        </div>
        <div class="modal-body">
            <div class="comments-section">
                @foreach($ticket->comments as $index => $comment)
                    <div class="comment">
                        <p>{{ $comment->comments }}</p>
                        @if(isset($comment->ticketReply))
                            @foreach($comment->ticketReply as $reply)
                                <div class="reply">
                                    <p>{{ $reply->message }}</p>
                                </div>
                            @endforeach
                        @endif
                        {{-- Display reply form only for the last comment --}}
                        @if($loop->last)
                            <form method="post" action="{{ route('comment.reply.store', ['comment' => $comment->id]) }}">
                                @csrf
                                <div class="form-group">
                                    <label for="reply_message">Reply Message</label>
                                    <textarea class="form-control" id="reply_message" name="reply_message"></textarea>
                                </div>
                                <input type="hidden" name="ticket_id" value="{{ $comment->ticket->id }}">
                                <button type="submit" class="btn btn-primary btn-submit">Submit Reply</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>

        </div>
    </div>
</div>

<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
<script>
    // $(document).ready(function() {
    //     $('#closeEditModelX').click(function() {
    //         location.reload();
    //     });
    // });
    $('#updateDepartmentSelect').change(function() {
        var departmentId = $(this).val();
        console.log('gj', departmentId);

        $.ajax({
            url: '{{ route("fetch-users") }}',
            type: 'POST',
            data: { department_id: departmentId },
            success: function(response) {
                $('#assignUserSelect').empty();

                // Check if response is not empty
                if (response.length > 0) {
                    console.log('response', response)
                    // Loop through each user object in the response
                    $.each(response, function(index, user) {
                        console.log('user', user)
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
</script>
