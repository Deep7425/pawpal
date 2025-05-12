@extends('layouts.admin.Masters.Master')
@section('title', 'Locality Master')
@section('content')

<style>

    .label-success{
        cursor:pointer;
        position:relative;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y page">

                <div class="row form-top-row localities">

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$localities->total()}}</a>
                    </div>

                

                    <div class="btn-group row-right head-search">

                    <div class="mar-r5">
                            <select class="form-control" name="top_status">
                                <option value="">All</option>
                                <option value="1" @if((app('request')->input('top_status'))!='')
                                    @if(base64_decode(app('request')->input('top_status')) == '1') selected @endif
                                    @endif >Top</option>
                                <option value="0" @if((app('request')->input('top_status'))!='')
                                    @if(base64_decode(app('request')->input('top_status')) == '0') selected @endif
                                    @endif>Other</option>
                            </select>
                        </div>

                        <div class="mar-r5">
                            {!! Form::open(array('route' => 'admin.localityMaster', 'id' => 'chnagePagination',
                            'method'=>'POST')) !!}
                            <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                                <option value="25" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='25' ) selected @endif @endif>25</option>
                                <option value="50" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='50' ) selected @endif @endif>50</option>
                                <option value="100" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='100' ) selected @endif @endif>100</option>
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
                                    placeholder="search by title" value="{{ old('search') }}" />
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
                        
                    </div>
                    {!! Form::close() !!}
                </div>
                
            


            <div class="layout-content">

                <div class="table-responsive plan-master">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Locality name</th>
                                <th>Slug</th>
                                <th>City name</th>
                                <th>State name</th>
                                <th>Country name</th>
                                <th>Top Most</th>
                                <th>Status</th>
                                <th width="115">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($localities->count() > 0)
                            @foreach($localities as $index => $local)
                            <tr>
                                <td>
                                    <label>{{$index+($localities->currentpage()-1)*$localities->perpage()+1}}.</label>
                                </td>
                                <td>{{$local->name}}</td>
                                <td>{{$local->slug}}</td>
                                <td>{{@$local->City->name}}</td>
                                <td>{{@$local->State->name}}</td>
                                <td>{{@$local->Country->name}}</td>
                                <td><span data-id="{{$local->id}}" data-status="{{$local->top_status}}"
                                        city-id="{{$local->city_id}}"
                                        class="label-default label changeLocalityMostStatus @if($local->top_status != '0') label-success @else label-danger @endif">@if($local->top_status
                                        != '0') Yes @else No @endif</span></td>

                                    <td>
                                  <span  class="label-default label @if($local->status == '1') label-success @else label-danger @endif">@if($local->status
                                        == '1') Active @else Inactive @endif</span></td>
                                     <td width="115">
                                    <button onclick="editLocality({{$local->id}});" class="btn btn-info btn-sm"
                                        data-toggle="tooltip" data-placement="left" title="Update"><i
                                            class="fa fa-pencil" aria-hidden="true"></i></button>
                                    <button onclick="deleteLocality({{$local->id}});" class="btn btn-danger btn-sm"
                                        data-toggle="tooltip" data-placement="right" title="Delete "><i
                                            class="fa fa-trash" aria-hidden="true"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8">No Record Found </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
             </div>
        
             
				<div class="page-nation text-right d-flex justify-content-end mt-2 mb-2">
                    <ul class="pagination pagination-large">
                        {{ $localities->appends($_GET)->links() }}
                    </ul>
                </div>


            </div>
        </div>
    </div>

    <div class="modal fade" id="localityEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div>


<!-- <script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> -->



<script>
function editLocality(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.editLocality')!!}",
        data: {
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#localityEditModal").html(data);
            jQuery('#localityEditModal').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

function deleteLocality(id) {
    if (confirm('Are you sure want to delete?') == true) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.deleteLocality')!!}",
            data: {
                'id': id
            },
            success: function(data) {
                if (data == 1) {
                    location.reload();
                } else {
                    alert("Oops Something Problem");
                }
                jQuery('.loading-all').hide();
            },
            error: function(error) {
                jQuery('.loading-all').hide();
                alert("Oops Something goes Wrong.");
            }
        });
    }
}

function chnagePagination(e) {
    $("#chnagePagination").submit();
}
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

jQuery(document).on("click", ".changeLocalityMostStatus", function(e) {
    var id = $(this).attr('data-id');
    var sts = $(this).attr('data-status');
    var city_id = $(this).attr('city-id');
    var curr = this;
    jQuery('.loading-all').show();
    jQuery.ajax({
        url: "{!! route('admin.updateLocalityStatusTop') !!}",
        type: "POST",
        dataType: "JSON",
        data: {
            'id': id,
            'top_status': sts,
            'city_id': city_id
        },
        success: function(result) {
            jQuery('.loading-all').hide();
            if (sts != '0') {
                $(curr).closest('td .changeLocalityMostStatus').addClass('label-danger');
                $(curr).closest('td .changeLocalityMostStatus').removeClass('label-success');
                $(curr).closest('td .changeLocalityMostStatus').html('No');
                $(curr).closest('td .changeLocalityMostStatus').attr('data-status', result);
            } else {
                $(curr).closest('td .changeLocalityMostStatus').addClass('label-success');
                $(curr).closest('td .changeLocalityMostStatus').removeClass('label-danger');
                $(curr).closest('td .changeLocalityMostStatus').html('Yes');
                $(curr).closest('td .changeLocalityMostStatus').attr('data-status', result);
            }
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            if (error.status == 401 || error.status == 419) {
                alert("Session Expired,Please logged in..");
                location.reload();
            } else {
                alert("Oops Something goes Wrong.");
            }
        }
    });
});
</script>
@endsection