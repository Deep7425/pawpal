<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content ">
        <div class="modal-header">
            <button type="button" class="close" data-bs-dismiss="modal">×</button>
            <h4 class="modal-title">View Feedback</h4>
        </div>
        <div class="modal-body">
            <div class="panel panel-bd lobidrag feedback">
                <div style="padding-bottom:15px">
                    <div class="btn-group">
                        <a class="btn btn-primary" href="{{ route('admin.feedbackPatAll') }}"> <i class="fa fa-list"></i> Feedback List</a>
                    </div>
                </div>
                <div class="panel-body">

                    <div class="scroller">
                        <div class="form-fields form-field-mid pad-r1 form-group">
                            <label>Would you recommend this professional?</label>

                            <div class="review-number">
                                {{$feedback->recommendation}}<span class="glyphicon glyphicon-star"></span>
                            </div>
                        </div>

                        <div class="form-fields form-field-mid pad-r1 form-group">
                            <label>How long was the wait time in the office before you were seen?</label>
                            <div class="review-number">
                                {{$feedback->waiting_time}}<span class="glyphicon glyphicon-star"></span>
                            </div>

                        </div>

                        <div class="form-fields form-field-mid pad-r1 form-group">
                            <label>Reason to visit?</label>
                            @if($feedback->visit_type == 1)<h4>Consultation</h4>@endif
                            @if($feedback->visit_type == 2)<h4>Procedure</h4>@endif
                            @if($feedback->visit_type == 3)<h4>Follow up</h4>@endif
                        </div>

                        <div class="form-fields form-field-mid pad-r1 form-group">
                            <label>Compliment</label>
                            @php $suggestions =  explode(",", $feedback->suggestions); @endphp
                            @if(!empty($feedback->suggestions))
                                @foreach ($suggestions as $value)
                                    <h4>{{getCompliments($value)}}</h4>
                                @endforeach
                            @else
                                <h4>Not Available</h4>
                            @endif


                        </div>

                        <div class="form-fields form-field-mid pad-r1 form-group">
                            <label>How would you rate this professional’s bedside manner?</label>
                            <div class="review-number">
                                {{$feedback->rating}}<span class="glyphicon glyphicon-star"></span>
                            </div>

                        </div>

                        <div class="form-fields form-field-mid pad-r1 form-group">
                            <label>What did you think about your visit?<i class="required_star">*</i></label>

                            <p>{{$feedback->experience}}</p>

                        </div>

                        <div class="form-fields form-field-mid pad-r1 form-group">
                            <div class="">
                                <label>Keep this experience publicly anonymous.</label>
                                <h3>@if($feedback->publish_status ==1) Yes @else No @endif  </h3>

                            </div>
                            <span class="help-block"></span>
                        </div>

                    </div>


                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
        </div>
    </div>

</div>

<!-- Use the Bootstrap bundle -->

<!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->

<script>


$('.btn-default').click(function() {
    $('.modal-dialog').modal('hide');
});

$('.close').click(function() {
    $('.modal-dialog').modal('hide');
});

</script>
