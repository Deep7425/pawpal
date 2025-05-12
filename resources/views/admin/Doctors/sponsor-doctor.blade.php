@extends('layouts.admin.Masters.Master')
@section('title', 'Doctor Sponsorship')
@section('content')
<!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container">
		<div class="container-fluid flex-grow-1 container-p-y data-list">

		<div class="row form-top-row">	
		
			            <div class=" btn-group  ">
							<a class="btn btn-primary" href="{{ route('admin.sponsoredDoctor') }}"> <i class="fa fa-list"></i>  Sponsorship List </a>
						</div>
					</div>

		     <div class="layout-content mt-2 card pad user-data-form">
			 @if(base64_decode(Request::route('action')) == 'add' ||   base64_decode(Request::route('action')) == 'edit')
		      {!! Form::open(array('route' => 'admin.doctorSponsorship', 'id' => 'doctorSponsorship', 'class' => 'col-sm-12')) !!}
						<input type="hidden" name="id" value="@if(isset($sponsor->user_id)){{@$sponsor->user_id}}@endif">
						<input type="hidden" name="sponsorship_id" value="@if(isset($sponsor->id)){{@$sponsor->id}}@endif">
		            <div class="row">
							<div class="col-sm-3 form-group">
	 							  <label>Clinic Name<i class="required_star">*</i></label>
									<select class="form-control multiSelect" name="clinic_name" id="clinic_name">
									<option value="">Select Clinic</option>
									@foreach(getClinics() as $clinic)
										<option value="{{$clinic->practice_id}}" state-id="{{$clinic->city_id}}" city-id="{{$clinic->state_id}}"  @if(isset($sponsor->user_id))  @if(@$clinic->practice_id == $sponsor->user_id) selected @endif @endif >{{$clinic->clinic_name}} @if(!empty($clinic->city_id)), {{@getCityName($clinic->city_id)}} @endif @if(!empty($clinic->state_id)), {{@getStateName($clinic->state_id)}} @endif</option>
									@endforeach
									</select>
									<span class="help-block"></span>
							</div>
							<div class="col-sm-3 form-group">
							 <label>Package Type</label>
								 <select class="form-control multiSelect" name="package_id">
									<option value="">Package Type</option>
									<option value="1" @if(isset($sponsor->id) && $sponsor->package_id == 1) selected @endif>Package 1</option>
									<option value="2" @if(isset($sponsor->id) && $sponsor->package_id == 2) selected @endif>Package 2</option>
									<option value="3" @if(isset($sponsor->id) && $sponsor->package_id == 3) selected @endif>Package 3</option>
									<option value="4" @if(isset($sponsor->id) && $sponsor->package_id == 4) selected @endif>Package 4</option>
									<option value="5" @if(isset($sponsor->id) && $sponsor->package_id == 5) selected @endif>Package 5</option>
									<option value="6" @if(isset($sponsor->id) && $sponsor->package_id == 6) selected @endif>Package 6</option>
								</select>
								<span class="help-block"></span>
							 </div>

							<div class="col-sm-3 form-group">
								<div class="dataTables_length">
									<label>From <i class="required_star">*</i></label>
									<div class="input-group date">
									<input type="text" autocomplete="off" placeholder="Start Date" class="form-control fromStartDate" name="start_date" value="@if(isset($sponsor->start_date)) {{@$sponsor->start_date}} @endif" readonly/>
									<span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i></span>
									</div>
									<span class="help-block"></span>
								</div>
							</div>
							<div class="col-sm-3 form-group">
								<div class="dataTables_length">
									<label>To <i class="required_star">*</i></label>
									<div class="input-group date">
									<input type="text" autocomplete="off" placeholder="End Date" class="form-control toStartDate" name="end_date" value="@if(isset($sponsor->end_date)) {{@$sponsor->end_date}} @endif" readonly/>
									<span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
									</div>
									<span class="help-block"></span>
								</div>
							</div>
						
						<div class="col-sm-3 form-group">

@if(@$sponsor->state_ids != "")
		@php $satesCount = explode(",", @$sponsor->state_ids); @endphp
@endif
<label>State Selection</label> <span class="count stateCount">@if(!empty($satesCount)) {{count($satesCount)}} @else 0 @endif</span>

<div class="selection-section ">
 <ul class="section-field StateSelection">
	 @if(isset($sponsor->state_ids) && !empty($sponsor->state_ids))
	 @php $sates = explode(",", $sponsor->state_ids); @endphp
	 @foreach($sates as $value)
		 <li class="selectionChoice" data-id="{{$value}}">{{getStateName($value)}} <span class="stateChoiceRemove">×</span></li>
	 @endforeach
	 @else
		 <li class="selectionChoice noSelection"> No State Selected</li>
	 @endif

 </ul>
</div>
</div>

<div class="col-sm-3 form-group">
@if(@$sponsor->city_ids != "")
		@php $citiesCount = explode(",", @$sponsor->city_ids); @endphp
@endif


<label>City Selection</label> <span class="count cityCount">@if(!empty($citiesCount)) {{count($citiesCount)}} @else 0 @endif</span>
<div class="selection-section ">
	<ul class="section-field CitySelection">
		@if(isset($sponsor->city_ids) && !empty($sponsor->city_ids))
		@php $cities = explode(",", $sponsor->city_ids); @endphp

		@foreach($cities as $value)
		 <li class="selectionChoice" state-id="{{getStateId($value)}}" data-id="{{$value}}">{{getCityName($value)}} <span class="cityChoiceRemove">×</span></li>
		@endforeach
		@else
		 <li class="selectionChoice noSelection"> No City	 Selected</li>
		@endif
	</ul>
</div>
</div>
<div class="col-sm-3 form-group">
										<label>Country</label>
										<select class="form-control country_id multiSelect" name="country_id[]" id="country_id">
										<option value="">Select country</option>
										@foreach(getCountriesList() as $country)
											<option value="{{$country->id}}"  @if(@$sponsor->Doctors->country_id == $country->id) selected @elseif($country->id == '101') selected @endif >{{$country->name}}</option>
										@endforeach
										</select>
										<span class="help-block"></span>
									</div>
									<div class="col-sm-3 form-group">
									 <label>State <i class="required_star">*</i></label>
									 <input type="hidden" name="state_id" value="@if(isset($sponsor->id)) {{@$sponsor->state_ids}} @endif" id="stateValues">
										 <select class="form-control state_id multiSelect" name="state_id_1[]">
											<option value="">Select State</option>
											@if(!empty($sponsor->Doctors->country_id))
											@foreach (getStateList($sponsor->Doctors->country_id) as $state)
												<option value="{{ $state->id }}" @if(@$sponsor->Doctors->state_id == $state->id) selected @endif >{{ $state->name }}</option>
											@endforeach

											@else
											@foreach (getStateList(101) as $state)
												<option value="{{ $state->id }}" @if(@$sponsor->Doctors->state_id == $state->id) selected @endif >{{ $state->name }}</option>
											@endforeach
											@endif
										</select>
										<span class="help-block"></span>
									 </div>

									 <div class="col-sm-3 form-group">
										<label>City <i class="required_star">*</i></label><br>
										 <input type="hidden" name="city_id" value="@if(isset($sponsor->id)) {{@$sponsor->city_ids}} @endif" id="cityValues">
										<select class="form-control city_id multiSelect" name="city_id_1[]">
											<option value="">Select City</option>
											@if(!empty($sponsor->Doctors->state_id))
											<option value="All">All</option>
											@foreach (getCityList($sponsor->Doctors->state_id) as $city)
												<option value="{{ $city->id }}" @if($sponsor->Doctors->city_id == $city->id) selected @endif data="{{$sponsor->Doctors->city_id}} = {{$city->id}}">{{ $city->name }}</option>
											@endforeach
											@endif
										</select>
										<span class="help-block"></span>
										</div>

										<div class="col-sm-12 reset-button">
										<button type="submit" class="btn btn-success submit">Save</button>
								 </div></div>
							{!! Form::close() !!}
		        </div>
		     	 @elseif(base64_decode(Request::route('action')) == 'view')

			 <div class="row">
							 <div class="col-sm-6 form-group">
								 <div class="dataTables_length">
									 <label>From</label>
									<h4>@if(isset($sponsor->start_date)) {{ date('d-M-Y', strtotime($sponsor->start_date))}} @endif</h4>
								 </div>
							 </div>
							 <div class="col-sm-6 form-group">
								 <div class="dataTables_length">
									 <label>To</label>
									 <h4>@if(isset($sponsor->end_date)) {{ date('d-M-Y', strtotime($sponsor->end_date))}} @endif</h4>

								 </div>
							 </div>
						 </div>
							 <div class="row-section">
								 <div class="row">

									 <div class="col-sm-6 form-group">

										 @if(@$sponsor->state_ids != "")
												 @php $satesCount = explode(",", @$sponsor->state_ids); @endphp
										 @endif
										<label>State Selection</label> <span class="count stateCount">@if(!empty($satesCount)) {{count($satesCount)}} @else 0 @endif</span>

										<div class="selection-section ">
											<ul class="section-field StateSelection">
												@if(isset($sponsor->state_ids))
												@php $sates = explode(",", $sponsor->state_ids); @endphp
												@foreach($sates as $value)
												 <li class="selectionChoice" data-id="{{$value}}">{{getStateName($value)}} </li>
												@endforeach
												@else
												 <li class="selectionChoice noSelection"> No State Selected</li>
												@endif

											</ul>
										</div>
										</div>

										<div class="col-sm-6 form-group">
										 @if(@$sponsor->city_ids != "")
												 @php $citiesCount = explode(",", @$sponsor->city_ids); @endphp
										 @endif

										 <label>City Selection</label> <span class="count cityCount">@if(!empty($citiesCount)) {{count($citiesCount)}} @else 0 @endif</span>
										 <div class="selection-section ">
											 <ul class="section-field CitySelection">
												 @if(isset($sponsor->city_ids))
												 @php $cities = explode(",", $sponsor->city_ids); @endphp

												 @foreach($cities as $value)
													<li class="selectionChoice" state-id="{{getStateId($value)}}" data-id="{{$value}}">{{getCityName($value)}}</li>
												 @endforeach
												 @else
													<li class="selectionChoice noSelection"> No City Selected</li>
												 @endif
											 </ul>
										 </div>
										 </div>
								 </div>
							 </div>
							 @endif

		 </div>
		</div>
	</div>
</div>



<script src="{{ URL::asset('js/select2.min.js') }}"></script>

<!-- <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script> -->
<!-- <script src="{{ URL::asset('js/bootstrap-select.min.js') }}"></script> -->
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>

<script src="{{ URL::asset('js/bootstrap.js') }}"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script type="text/javascript">
function StateIds() {
    var values = [];
    var i = 0;
    $('.StateSelection li').each(function() {
        values.push($(this).attr('data-id'));
        i++;
    });
    $('#stateValues').val(values);
    $('.stateCount').text(i);
}

function CityIds() {
    var values = [];
    var i = 0;
    $('.CitySelection li').each(function() {
        values.push($(this).attr('data-id'));
        i++;
    });
    $('#cityValues').val(values);
    $('.cityCount').text(i);
}
$(".fromStartDate").datepicker({
                format: 'yyyy-mm-dd',
                onSelect: function (selected) {
                    var dt = new Date(selected);
                    dt.setDate(dt.getDate());
                    // Your logic here based on the selected date
                }
            }).on('changeDate', function () {
                $(this).datepicker('hide');
            });

            $(".toStartDate").datepicker({
                format: 'yyyy-mm-dd',
                onSelect: function (selected) {
                    var dt = new Date(selected);
                    dt.setDate(dt.getDate());
                    // Your logic here based on the selected date
                }
            }).on('changeDate', function () {
                $(this).datepicker('hide');
            });


jQuery('#clinic_name').on('change', function() {
    var state_id = $(this).find('option:selected').attr("city-id");
    var city_id = $(this).find('option:selected').attr("state-id");

    if (this.value != "") {
        jQuery("#doctorSponsorship").find("input[name='id']").val(this.value);

        if (state_id != "") {
            jQuery("#doctorSponsorship").find(".state_id").val(state_id).trigger('change');
        }
        if (city_id != "") {
            setTimeout(function() {
                jQuery("#doctorSponsorship").find(".city_id").val(city_id).trigger('change');
            }, 500);
        }
    } else {
        jQuery("#doctorSponsorship").find("input[name='id']").val('');
        jQuery("#doctorSponsorship").find(".state_id").val('33').trigger('change');
        setTimeout(function() {
            jQuery("#doctorSponsorship").find(".city_id").val('3378').trigger('change');
        }, 500);

    }
});



jQuery(document).ready(function() {
    $(".multiSelect").select2();
    jQuery("#doctorSponsorship").validate({
        rules: {
            clinic_name: "required",
            start_date: "required",
            end_date: "required",
            country_id: "required",
            state_id: "required",
            city_id: "required"
        },
        messages: {},
        errorPlacement: function(error, element) {
            element.closest('.form-group').find('.help-block').append(error);
        },
        submitHandler: function(form) {
            $(form).find('.submit').attr('disabled', true);
            jQuery.ajax({
                type: "POST",
                dataType: "JSON",
                url: "{!! route('admin.doctorSponsorship')!!}",
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data == 1) {
                        jQuery('.loading-all').hide();
                        $(form).find('.submit').attr('disabled', false);
                        location.reload();
                        window.location = '{!! route("admin.sponsoredDoctor")!!}';
                    } else {
                        jQuery('.loading-all').hide();
                        $(form).find('.submit').attr('disabled', false);
                        alert("Oops Something Problem");
                    }
                }
            });
        }
    });
});


jQuery('.country_id').on('change', function() {
    var cid = this.value;
    var $el = $('.state_id');
    $el.empty();
    jQuery.ajax({
        url: "{!! route('getStateList') !!}",
        // type : "POST",
        dataType: "JSON",
        data: {
            'id': cid
        },
        success: function(result) {
            jQuery("#doctorSponsorship").find("select[name='state_id']").html(
                '<option value="">Select State</option>');
            jQuery("#doctorSponsorship").find("select[name='city_id']").html(
                '<option value="">Select City</option>');
            jQuery.each(result, function(index, element) {
                $el.append(jQuery('<option>', {
                    value: element.id,
                    text: element.name
                }));
            });
        }
    });
})
jQuery(document).on("change", ".state_id", function(e) {
    //jQuery('.state_id').on('change', function() {
    var text = $('.state_id option:selected').text();
    var cid = this.value;
    var stateValues = $('#stateValues').val();
    var checkValue = stateValues.includes(this.value);
    if (this.value != "") {
        $('.StateSelection').find('.noSelection').remove();
    }
    if (this.value != "" && checkValue == false) {
        $('.selection-section').find('.StateSelection').append('<li class="selectionChoice" data-id="' + cid +
            '">' + text + '<span class="stateChoiceRemove">×</span></li>')
        StateIds();
    }

    var $el = jQuery('.city_id');

    $el.empty();
    jQuery.ajax({
        url: "{!! route('getCityList') !!}",
        // type : "POST",
        dataType: "JSON",
        data: {
            'id': cid
        },
        success: function(result) {
            jQuery("#doctorSponsorship").find("select[name='city_id']").html(
                '<option value="">Select City</option>');
            if (result != '') {
                $el.append(
                '<option value="">Select City</option> <option value="All">All</option>');
            }
            jQuery.each(result, function(index, element) {
                $el.append(jQuery('<option>', {
                    value: element.id,
                    text: element.name
                }));
            });
        }
    });
});

jQuery(document).on("change", ".city_id", function(e) {
    //jQuery('.state_id').on('change', function() {
    var text = $('.city_id option:selected').text();
    var state_id = $('.state_id option:selected').val();
    var cid = this.value;
    var cityValues = $('#cityValues').val();
    var checkValue = cityValues.includes(this.value);
    if (this.value != "") {
        $('.CitySelection').find('.noSelection').remove();
    }
    if (this.value == 'All') {
        $(".city_id option").each(function() {
            var checkValueLoop = cityValues.includes(this.value);
            if (this.value > 0 && checkValueLoop == false) {
                $('.selection-section').find('.CitySelection').append(
                    '<li class="selectionChoice" state-id="' + state_id + '"  data-id="' + this
                    .value + '">' + this.text + '<span class="cityChoiceRemove">×</span></li>')
                CityIds();
            }
        });

    } else {
        if (this.value != "" && checkValue == false) {
            $('.selection-section').find('.CitySelection').append('<li class="selectionChoice" state-id="' +
                state_id + '" data-id="' + cid + '">' + text +
                '<span class="cityChoiceRemove">×</span></li>')
            CityIds();
        }

    }

});

jQuery(document).on("click", ".stateChoiceRemove", function(e) {

    var state_id = $(this).parent().attr('data-id');
    $('.CitySelection li').each(function() {
        var selectState_id = $(this).attr('state-id');
        if (state_id == selectState_id) {
            $(this).remove();
        }
    });
    CityIds();
    var cityValues = $('#cityValues').val();
    if (cityValues == '' && $('.CitySelection li').length == 0) {
        $('.selection-section').find('.CitySelection').append(
            '<li class="selectionChoice noSelection"> No City Selected</li>')
    }
    $(this).parent().remove();
    StateIds();
    var stateValues = $('#stateValues').val();
    if (stateValues == '') {
        $('.selection-section').find('.StateSelection').append(
            '<li class="selectionChoice noSelection"> No State Selected</li>')
    }

});
jQuery(document).on("click", ".cityChoiceRemove", function(e) {
    $(this).parent().remove();
    CityIds();
    var cityValues = $('#cityValues').val();
    if (cityValues == '') {
        $('.selection-section').find('.CitySelection').append(
            '<li class="selectionChoice noSelection"> No City Selected</li>')
    }

});
</script>
@endsection