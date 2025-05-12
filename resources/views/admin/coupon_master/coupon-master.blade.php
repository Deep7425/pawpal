@extends('layouts.admin.Masters.Master')
@section('title', 'Coupon List')
@section('content')
<!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top:0px !important;">


            <div class="container-fluid flex-grow-1 container-p-y">
                <div class="row form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="{{ route('admin.couponMasterAdd') }}"> <i
                                class="fa fa-plus"></i> Add Coupon</a>
                    </div>

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$coupons->total()}}</a>
                    </div>

                    <div class="btn-group head-search">

                    <div class="">
                                {!! Form::open(array('route' => 'admin.couponMaster', 'id' => 'chnagePagination',
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

                           
                                <div class="custom-search-form mar-l5 mar-r5">
                                    <input name="search" type="text" class="form-control capitalizee"
                                        placeholder="Search By Title" value="{{ old('search') }}" />
                                </div>
                           

                        
                                <div class="custom-search-form">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit">
                                            SEARCH
                                        </button>
                                    </span>
                                </div>
                                {!! Form::close() !!}
                           

                    </div>


                </div>

                <div class="layout-content">

                    <div class="table-responsive plan-master">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width:70px;">S.No.</th>
                                    <th>Type</th>
                                    <th>Coupon Title</th>
                                    <th>Coupon Discount Type</th>
                                    <th>Coupon Discount</th>
                                    <th>Coupon Code</th>
                                    <!--<th>Coupon Duration</th>-->
                                    <th>Coupon Expire Date</th>
                                    <th>Note</th>
                                    <!--<th>Medical Store Details</th>-->
                                    <th>Status</th>
                                    <th>User Name</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($coupons->count() > 0)
                                @foreach($coupons as $index => $coupon)
                                <tr>
                                    <th>{{$index+($coupons->currentpage()-1)*$coupons->perpage()+1}}.</th>
                                    <td>@if($coupon->type == "1") Lab @elseif($coupon->type == "2") Appointment
                                        @elseif($coupon->type == "3") Medicine @endif</td>
                                    <td>{{$coupon->coupon_title}}</td>
                                    <td>@if($coupon->coupon_discount_type == "1") ₹
                                        @elseif($coupon->coupon_discount_type == "2") % @endif</td>
                                    <td>{{$coupon->coupon_discount}}</td>
                                    <td>{{$coupon->coupon_code}}</td>
                                    <!--<td>{{$coupon->coupon_duration}} @if($coupon->coupon_duration_type == "d") Day @elseif($coupon->coupon_duration_type == "m") Month @elseif($coupon->coupon_duration_type == "y") Year @endif</td>-->
                                    <td>{{date('d-m-Y', strtotime($coupon->coupon_last_date))}}</td>
                                    <td>{!!$coupon->other_text!!}</td>
                                    <!--<td>
										@if(!empty($coupon->MedicalStoreDetails))	{{$coupon->MedicalStoreDetails->name}},<br>{{$coupon->MedicalStoreDetails->mobile}},<br>{{getCityName($coupon->MedicalStoreDetails->city_id)}}
										@endif
									</td>-->
                                    <td>
                                        <button class="btn btn-default update_status" id="{{$coupon->id}}"
                                            status="{{$coupon->status}}" type="button">@if($coupon->status == 0)
                                            Inactive @else Active @endif </button>
                                    </td>
                                    <td>{{@$coupon->admin->name}}</td>

                                    <td>
                                        <a href="{{route('admin.editCoupons', ['id' => base64_encode($coupon->id)])}}"
                                            title="Edit Coupon Details" class="btn btn-info btn-sm"
                                            data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <a href="{{route('admin.deleteCouponMaster', ['id' => base64_encode($coupon->id)])}}"
                                            title="Delete Coupon Master"
                                            onclick="if(confirm('Are You Sure?')){return true;}else{return false;}"
                                            class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right"
                                            title="Delete "><i class="fa fa-trash" aria-hidden="true"></i></a>
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
                    <div class="page-nation text-right d-flex justify-content-end mb-3 mt-3">
                        <ul class="pagination pagination-large">
                            {{ $coupons->appends($_GET)->links() }}
                            <!--<li class="disabled"><span>«</span></li>
					<li class="active"><span>1</span></li>
					<li><a href="#">2</a></li>
					<li class="disabled"><span>...</span></li><li>
					<li><a rel="next" href="#">Next</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="packageEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
    </div>



    <!-- /.content-wrapper -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

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
                    url: "{!! route('admin.updateCouponStatus')!!}",
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

    function chnagePagination(e) {
        $("#chnagePagination").submit();
    }
    </script>
    @endsection