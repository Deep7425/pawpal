@extends('layouts.admin.Masters.Master') @section('title', 'Subscription Plans') @section('content')
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">




            <div class="container-fluid flex-grow-1 container-p-y">

                <div class="row form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="{{ route('plans.planMasterAdd') }}"> <i class="fa fa-plus"></i>
                            Add Plan</a>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$plans->total()}}</a>
                    </div>
                    <div class="head-search">
                        {!! Form::open(array('route' => 'plans.planMaster', 'id' => 'chnagePagination',
                        'method'=>'POST')) !!}
                        <div class="row">
                            <div class="col-sm-4">

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
                            <div class="col-sm-5">

                                <div class="symptom-search-box">
                                    <input name="search" type="search" class="col-sm-5 form-control capitalizee"
                                        placeholder="search by title" value="{{ old('search') }}" />

                                </div>

                            </div>
                            <div class="col-sm-3">

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



                </div>

                <div class="layout-content ">
                <div class="table-responsive plan-master">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width:70px;">S.No.</th>
                                <th>Type</th>
                                <th>Plan Titcxcle</th>
                                <th>Slug</th>
                                <th>Plan Price</th>
                                <th>Discount Price</th>
                                <th>Plan Duration</th>
                                <th>Appointments</th>
                                <th>Max Appointment Fee</th>
                                <th>Lab Package Title</th>
                                <th>Lab Package Code</th>
                                <th>Status</th>
                                <th style="width:85px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($plans->count() > 0)
                            @foreach($plans as $index => $plan)
                            <tr>
                                <td>{{$index+($plans->currentpage()-1)*$plans->perpage()+1}}.</td>
                                <td>@if($plan->type == '1') Normal - en
                                    @elseif($plan->type == '2') Instant Appointment Plan - en
                                    @elseif($plan->type == '3') Normal - hi
                                    @elseif($plan->type == '4') Instant Appointment Plan - hi
                                    @endif</td>
                                <td>{{$plan->plan_title}}</td>
                                <td>{{$plan->slug}}</td>
                                <td>{{$plan->price}}</td>
                                <td>{{$plan->discount_price}}</td>
                                <td>{{$plan->plan_duration}} @if($plan->plan_duration_type == "d") Day
                                    @elseif($plan->plan_duration_type == "m") Month @elseif($plan->plan_duration_type ==
                                    "y") Year @endif</td>
                                <td>{{$plan->appointment_cnt}}</td>
                                <td>{{$plan->max_appointment_fee}}</td>
                                <td>{{$plan->lab_pkg_title}}</td>
                                <td>{{$plan->lab_pkg}}</td>
                                <td>
                                    <button class="btn btn-default update_status" id="{{$plan->id}}"
                                        status="{{$plan->status}}" type="button">@if($plan->status == 0) Inactive @else
                                        Active @endif </button>
                                </td>
                                <td>
                                    <button onclick="editPlans({{$plan->id}});" class="btn btn-info btn-sm"
                                        data-toggle="tooltip" data-placement="left" title="Update"><i
                                            class="fa fa-pencil" aria-hidden="true"></i></button>
                                    <button onclick="deletePlans({{$plan->id}});" class="btn btn-danger btn-sm"
                                        data-toggle="tooltip" data-placement="right" title="Delete "><i
                                            class="fa fa-trash" aria-hidden="true"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9">No Record Found </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
</div>
              

				
                <div class="page-nation text-right d-flex justify-content-end mt-2 mb-2">
                    <ul class="pagination pagination-large">
                        {{ $plans->appends($_GET)->links() }}
                    </ul>
                </div>
            </div>
        </div>
        <div class="modal " id="planEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
    </div>
</div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery(".update_status").on('click', function() {
        if (confirm('Are you sure want to change status?')) {
            jQuery('.loading-all').show();
            jQuery(this).attr('disabled', true);
            var id = $(this).attr('id');
            var status = $(this).attr('status');
            var btn = this;
            jQuery.ajax({
                type: "POST",
                url: "{!! route('plans.updatePlanStatus')!!}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                    'status': status
                },
                success: function(data) {
                    jQuery(btn).attr('disabled', false);
                    jQuery('.loading-all').hide();
                    if (data == 1) {
                        jQuery(btn).text("Active");
                        $(btn).attr('status', '1');
                    } else if (data == 2) {
                        jQuery(btn).text("Inactive");
                        $(btn).attr('status', '0');
                    } else {
                        alert("System Problem");
                    }
                },
                error: function(error) {
                    jQuery(btn).attr('disabled', false);
                    jQuery('.loading-all').hide();
                    alert("Oops Something goes Wrong.");
                }
            });
        }
    });
});

function editPlans(id) {
    jQuery('.loading-all').show();
    // alert("Hello")
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('plans.editPlans')!!}",
        data: {
            "_token": "{{ csrf_token() }}",
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#planEditModal").html(data);
            jQuery('#planEditModal').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

function deletePlans(id) {
    if (confirm('Are you sure want to delete?') == true) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('plans.deletePlanMaster')!!}",
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
</script>
@endsection