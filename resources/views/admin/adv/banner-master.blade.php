@extends('layouts.admin.Masters.Master')
@section('title', 'Banner Master')
@section('content')
<!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">

                <div class="row form-top-row">
               
                        <div class="btn-group">
                            <a class="btn btn-success" href="{{ route('admin.addAdBanner') }}"> <i
                                    class="fa fa-plus"></i>Add Banner</a>
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-success" href="javascript:void();">{{$banners->total()}}</a>
                        </div>
                   
						<div class="btn-group head-search">
						<div class="">
                                {!! Form::open(array('route' => 'admin.adBannerMaster', 'id' => 'chnagePagination',
                                'method'=>'POST')) !!}
                                <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                    <!-- <option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option> -->
                                    <option value="25" @if(isset($_GET['page_no']))
                                        @if(base64_decode($_GET['page_no'])=='25' ) selected @endif @endif>25</option>
                                    <option value="50" @if(isset($_GET['page_no']))
                                        @if(base64_decode($_GET['page_no'])=='50' ) selected @endif @endif>50</option>
                                    <option value="100" @if(isset($_GET['page_no']))
                                        @if(base64_decode($_GET['page_no'])=='100' ) selected @endif @endif>100</option>
                                </select>
                            </div>
							<div class="TOPMENU head-small mar-l5" >
                            <a href="javascript:void(0);" class="btn btn-defaultp excel-btn" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
                            </div>

							
                                <div class="mar-r5 custom-search-form">
                                    <input name="search" type="text" class="form-control capitalizee"
                                        placeholder="Search By Title" value="{{ old('search') }}" />
                                </div>
                            
							
                                <div class="custom-search-form">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit">
                                            SEARCH
                                        </button>
                                    </span>
                               <!-- /input-group -->
                                {!! Form::close() !!}
                            </div>

						</div>
                </div>

                <div class="layout-content">

                    <div class="table-responsive plan-master">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Link URL</th>
                                    <th>Type</th>
                                    <th>Area</th>
                                    <th>Expire Date</th>
                                    <th>status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($banners->count() > 0)
                                @foreach($banners as $index => $blg)
                                <tr>
                                    <td>
                                        <label>{{$index+($banners->currentpage()-1)*$banners->perpage()+1}}.</label>
                                    </td>
                                    <td>
                                        <img src="<?php
												if(!empty($blg->image)){
													echo url("/")."/public/adBannerFiles/".$blg->image;
												}
												else { echo url("/")."/img/camera-icon.jpg"; }
											?>" class="img-circle" alt="User Image" height="50" width="50">
                                    </td>
                                    <td>{{$blg->title}}</td>
                                    <td>{{$blg->link_url}}</td>
                                    <td>@if(@$blg->type == '1') English @else Hindi @endif</td>
                                    <td>@if(@$blg->area == '1') pop-up @elseif(@$blg->area == '2') Middle
                                        @elseif(@$blg->area == '4') Top @else Bottom @endif</td>
                                    <td>@if(!empty($blg->expiry_date)) {{date("d-m-Y",strtotime($blg->expiry_date))}}
                                        @endif</td>
                                    <!--<td>{{$blg->description}}</td>-->
                                    <td><span
                                            class="label-default label @if($blg->status == '1') label-success @else label-danger @endif">@if($blg->status
                                            == '1') Active @else Inactive @endif</span></td>
                                    <td>
                                        <button onclick="editAdBanner({{$blg->id}});" class="btn btn-info btn-sm"
                                            data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></button>
                                        <button onclick="deleteAdBanner({{$blg->id}});" class="btn btn-danger btn-sm"
                                            data-toggle="tooltip" data-placement="right" title="Delete "><i
                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6">No Record Found </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="page-nation text-right">
                        <ul class="pagination pagination-large">
                            {{ $banners->appends($_GET)->links() }}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bannerEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script>

<!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->
<!-- /.content-wrapper -->

<script>
function editAdBanner(id) {

    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.editAdBanner')!!}",
        data: {
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#bannerEditModal").html(data);
            jQuery('#bannerEditModal').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

function deleteAdBanner(id) {
    if (confirm('Are you sure want to delete?') == true) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.deleteAdBanner')!!}",
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