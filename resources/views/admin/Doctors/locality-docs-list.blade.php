@extends('layouts.admin.Masters.Master')
@section('title', 'Locality Doctor list')
@section('content')
<!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">

            <div class="container-fluid flex-grow-1 container-p-y localities">
                <div class="row mb-2 form-top-row">
                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$doctors->total()}}</a>
                    </div>
                    <div class="btn-group head-search">
                        <div class="mar-r5">
                            {!! Form::open(array('route' => 'admin.doctorsListForLocality', 'id' => 'chnagePagination',
                            'method'=>'POST')) !!}
                            <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                                <option value="25" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='25' ) selected @endif @endif>25</option>
                                <option value="50" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='50' ) selected @endif @endif>50</option>
                                <option value="100" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='100' ) selected @endif @endif>100</option>
                                <option value="200" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='200' ) selected @endif @endif>200</option>
                            </select>
                        </div>
                        <div class="mar-r5">
                            <select class="form-control" name="type">
                                <option value="">By</option>
                                <option value="1" @if((app('request')->input('type'))!='')
                                    @if(base64_decode(app('request')->input('type')) == '1') selected @endif @endif
                                    >Address</option>
                                <option value="2" @if((app('request')->input('type'))!='')
                                    @if(base64_decode(app('request')->input('type')) == '2') selected @endif
                                    @endif>Locality</option>
                            </select>
                        </div>
                        <div class="mar-r5">
                            <select class="form-control state_id" name="state_id">
                                <option value="">Select State</option>
                                @foreach (getStateList(101) as $state)
                                <option value="{{ $state->id }}" @if(old('state_id')==$state->id) selected @endif
                                    >{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mar-r5">
                            <select class="form-control city_id" name="city_id">
                                <option value="">Select City</option>
                                @if(!empty(old('state_id')))
                                @foreach (getCityList(old('state_id')) as $city)
                                <option value="{{ $city->id }}" @if(old('city_id')==$city->id) selected @endif
                                    >{{ $city->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mar-r5">
                            <div class="input-group custom-search-form">
                                <input name="search" type="text" class="form-control capitalizee"
                                    placeholder="Search By Name" value="{{ old('search') }}" />
                            </div>
                        </div>
                        <div class="mar-r5">
                            <div class="input-group custom-search-form">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        SEARCH
                                    </button>
                                </span>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>



                <div class="layout-content ">
                 

                    <div class="table-responsive plan-master">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <th>state</th>
                                    <th>city</th>
                                    <th>locality</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($doctors->count() > 0)
                                @foreach($doctors as $index => $doc)
                                <tr>
                                    <td>
                                        <label>{{$index+($doctors->currentpage()-1)*$doctors->perpage()+1}}.</label>
                                    </td>
                                    <td>{{$doc->first_name}} {{$doc->last_name}}</td>
                                    <td>{{$doc->mobile_no}}</td>
                                    <td>@if(!empty($doc->address_1)){{$doc->address_1}} @else <button
                                            doc_id="{{$doc->id}}" class="btn btn-info updateAddressOfDoc">Update
                                            Address</button> @endif</td>
                                    <td>{{getStateName($doc->state_id)}}</td>
                                    <td>{{getCityName($doc->city_id)}}</td>
                                    <td>
                                        @if(!empty($doc->address_1))
                                        <select class="form-control selectLocalityDiv" multiple doc_id="{{$doc->id}}">
                                            <option value="">Select Locality</option>
                                            @foreach (getLocalityByCityId($doc->city_id) as $loc)
                                            <option value="{{ $loc->id }}" @if($doc->locality_id == $loc->id) selected
                                                @endif >{{ $loc->name }}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        Address Not Vailable
                                        @endif
                                    </td>
                                   
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="10">No Record Found </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
                    <ul class="pagination pagination-large">
                        {{ $doctors->appends($_GET)->links() }}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade notification-broadcast" id="docEditAddress" role="dialog" data-backdrop="static" data-keyboard="false">
	  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="reset" class="close" data-bs-dismiss="modal">×</button>
				<h4 class="modal-title">Update Address</h4>
			</div>
			{!! Form::open(array('id' => 'updateDocAddress','name'=>'updateDocAddress')) !!}
			<input type=hidden value="" name="doc_id"/>
			<div class="modal-body">
				<div class="panel panel-bd lobidrag">
					
					<div class="panel-body">
						<div class="form-group">
							<label>Address</label>
							<textarea value="" class="form-control" name="address_1" rows="5"></textarea>
							<span class="help-block"></span>
						</div>
						<div class="row">

                        <div class="form-group ">
						<label>State</label>
							<select class="form-control state_id_doc_update" name="state_id">
							  <option value="">Select State</option>
								@foreach (getStateList(101) as $state)
									<option value="{{ $state->id }}"  @if($state->id == '33') selected @endif >{{ $state->name }}</option>
								@endforeach
							</select>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-6 doc_address_city">
						<label>City</label>
							<select class="form-control city_id" name="city_id">
							  <option value="">Select City</option>
							  @foreach (getCityList(33) as $city)
								<option value="{{ $city->id }}" @if($city->id == '3378') selected @endif >{{ $city->name }}</option>
								@endforeach
							</select>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-6 doc_address_locality">
						<label>Locality</label>
							<select class="form-control locality_id" name="locality_id">
							  <option value="">Select Locality</option>
							@foreach (getLocalityByCityId(3378) as $loc)
								<option value="{{ $loc->id }}" >{{ $loc->name }}</option>
							@endforeach
							</select>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-6">
						<label>Zipcode</label>
							<input type="text"  class="form-control" name="zipcode"/>
							<span class="help-block"></span>
						</div>		
                        </div>
						<div class="reset button">
							<button type="submit" class="btn btn-primary submit">Save</button>
							<button type="reset" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>
			</div>
			{!! Form::close() !!}
		</div>   
	  </div>   
	</div> 
</div>


<script type="text/javascript">

// jQuery(".btn-default").on('click' , function(){
//     jQuery('.modal').hide();
//     jQuery('.modal-backdrop').hide();
// });
// jQuery(".close").on('click' , function(){
//     jQuery('.modal').hide();
//     jQuery('.modal-backdrop').hide();
// });

function editDoc(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.editDoctor')!!}",
        data: {
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#doctorEditModal").html(data);
            jQuery('#doctorEditModal').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

function chnagePagination(e) {
    $("#chnagePagination").submit();
}
jQuery(document).on("change", ".state_id_doc_update", function(e) {
    var cid = this.value;
    var $el = jQuery(".doc_address_city").find('.city_id');
    $el.empty();
    jQuery.ajax({
        url: "{!! route('getCityList') !!}",
        // type : "POST",
        dataType: "JSON",
        data: {
            'id': cid
        },
        success: function(result) {
            jQuery(".doc_address_city").find("select[name='city_id']").html(
                '<option value="">Select City</option>');
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
    var lid = this.value;
    var $el = jQuery(".doc_address_locality").find('.locality_id');
    $el.empty();
    jQuery.ajax({
        url: "{!! route('getLocalityList') !!}",
        // type : "POST",
        dataType: "JSON",
        data: {
            'id': lid
        },
        success: function(result) {
            jQuery("#updateDocAddress").find("select[name='locality_id']").html(
                '<option value="">Select Locality</option>');
            jQuery.each(result, function(index, element) {
                $el.append(jQuery('<option>', {
                    value: element.id,
                    text: element.name
                }));
            });
        },
        error: function(error) {
            if (error.status == 401) {
                alert("Session Expired,Please logged in..");
                location.reload();
            } else {
                alert("Oops Something goes Wrong.");
            }
        }
    });
});

jQuery(document).on("change", ".state_id", function(e) {
    //jQuery('.state_id').on('change', function() {
    var cid = this.value;
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
            jQuery(".panel-header").find("select[name='city_id']").html(
                '<option value="">Select City</option>');
            jQuery.each(result, function(index, element) {
                $el.append(jQuery('<option>', {
                    value: element.id,
                    text: element.name
                }));
            });
        }
    });
});


</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>

<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>


<script type="text/javascript">


$(document).ready(function() {
    $('.selectLocalityDiv').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });
    jQuery(document).on("click", ".updateAddressOfDoc", function(e) {
        var doc_id = $(this).attr("doc_id");
        $("#docEditAddress").trigger('reset');
        $("#docEditAddress").modal('show');
        $("#docEditAddress").find('input[name="doc_id"]').val(doc_id);

    });
    jQuery(document).on("change", ".selectLocalityDiv", function(e) {
        var locality_id = $(this).val();
        var doc_id = $(this).attr("doc_id");
        jQuery('.loading-all').hide();
        jQuery.ajax({
            url: "{!! route('admin.localityAssign') !!}",
            type: "POST",
            dataType: "JSON",
            data: {
                'locality_id': locality_id,
                "doc_id": doc_id
            },
            success: function(result) {
                if (result == 1) {
                    alert("Locality Assign Successfully");
                } else if (result == 2) {
                    alert("Locality Already Assign");
                } else {
                    alert("Oops");
                }
                jQuery('.loading-all').hide();

            },
            error: function(error) {
                if (error.status == 401 || error.status == 419) {
                    alert("Session Expired,Please logged in..");
                    location.reload();
                } else {
                    alert("Oops Something goes Wrong.");
                }
            }
        });
    });
});


jQuery("form[name='updateDocAddress']").validate({
    rules: {
        'address_1': {
            required: true
        },
        state_id: "required",
        city_id: "required",
        locality_id: "required",
    },
    messages: {},
    errorPlacement: function(error, element) {
        error.appendTo(element.next());
    },
    ignore: ":hidden",
    submitHandler: function(form) {
        $(form).find('.submit').attr('disabled', true);
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.updateDocAddress')!!}",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    alert("Address updated Successfully");
                    $("#docEditAddress").modal('hide');
                } else {
                    alert("Oops Something Problem");
                }
                jQuery('.loading-all').hide();
                $(form).find('.submit').attr('disabled', false);
                $(form).trigger('reset');
            },
            error: function(error) {
                if (error.status == 401 || error.status == 419) {
                    alert("Session Expired,Please logged in..");
                    location.reload();
                } else {
                    alert("Oops Something goes Wrong.");
                }
            }
        });
    }
});


</script>
@endsection