<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content TodayAttendance">
        <div class="modal-header">
            <button type="button" class="close" id="closeEditModelX" data-dismiss="modal">×</button>
            <h4 class="modal-title">Update Attendance</h4>
        </div>
        <div class="modal-body">
            <div class="panel panel-bd lobidrag">
                <div class="panel-heading">
                    <!-- Add any heading content here if needed -->
                </div>
                <div class="panel-body">
                    {{--                    <h3>Shift Time {{$user}}</h3>--}}
                    {!! Form::open(['id' => 'editAttendance', 'name' => 'editAttendance', 'enctype' => 'multipart/form-data']) !!}
                    <div class="row">
                    <input type="hidden" name="id" value="{{ $attendance->id }}">

                    <div class="form-group col-md-6">
                        <label>Shift End</label>
                        <input type="time" id="end_time" name="end_time" class="form-control"  placeholder="Enter End Time">
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Shift End Selfie</label>
                        <input type="file" name="end_pic" class="form-control" placeholder="End Pic">
                        <span class="help-block"></span>
                    </div>


                    <div class="reset-button col-sm-12">
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button type="submit" class="btn btn-success submit">Save</button>
                    </div></div>    
                    {!! Form::close() !!}
                </div>

            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" id="closeEditModel" data-dismiss="modal">Close</button>
        </div>
    </div>

</div>
{{--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrzKrcKQqGvZQjuMZtDQy3MHOpNjPmjnU&libraries=places"></script>--}}
<script src="{{ URL::asset('js/jquery-printme.js') }}"></script>
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('#closeEditModel').click(function() {
            location.reload();
        });
    });
    $(document).ready(function() {
        $('#closeEditModelX').click(function() {
            location.reload();
        });
    });
    var now = new Date();
    var hours = now.getHours().toString().padStart(2, '0');
    var minutes = now.getMinutes().toString().padStart(2, '0');
    var time = hours + ':' + minutes;

    // Set the value of the end_time input field to the current time
    document.getElementById('end_time').value = time;


    $(document).ready(function () {
        jQuery("form[name='editAttendance']").validate({

            rules: {
                end_pic: {required:true},
                end_time: {required:true},

            },
            messages: {
                end_pic: "Please Upload Image"
            },

            submitHandler: function (form) {
                console.log(form);

                jQuery('.loading-all').show();
                var formData = new FormData(form);
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: "{!! route('admin.editAttendance')!!}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSubmit: function () {
                        jQuery('.loading-all').show();
                    },
                    success: function (data) {
                        if (data) {
                            jQuery('.loading-all').hide();
                            location.reload();
                            alert('Attendance Updated Successfully')
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        jQuery('.loading-all').hide();
                        alert('Error occurred while adding the attendance.');
                    }
                });
            }
        });
    });
</script>