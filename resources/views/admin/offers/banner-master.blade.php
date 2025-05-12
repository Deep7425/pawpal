@extends('layouts.admin.Masters.Master')
@section('title', 'Banner Master')
@section('content')
<!-- Content Wrapper. Contains page content -->

<style>

    .label-success{
        cursor:pointer;
        position:relative;
    }
</style>

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">

            <div class="container-fluid flex-grow-1 container-p-y offers-banner">

                <div class="row form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="{{ route('admin.addOffersBanner') }}"> <i
                                class="fa fa-plus"></i> Add Banner</a>
                    </div>

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$banners->total()}}</a>
                    </div>
                    <!-- <div class="btn-group">
                        <a class="btn btn btn-success buttons-excel buttons-html5 " style="color:white;"
                            tabindex="0"><span>Excel</span></a>
                    </div> -->
                    <div class="btn-group head-search">
                        <div class="">
                            {!! Form::open(array('route' => 'admin.offersBannerMaster', 'id' => 'chnagePagination',
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

                        <div class="">
                            <div class="custom-search-form">
                                <input name="search" type="text" class="form-control capitalizee"
                                    placeholder="Search By Title" value="{{ old('search') }}" />
                            </div>
                        </div>

                        <div class="">
                            <div class="custom-search-form">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        SEARCH
                                    </button>
                                </span>
                            </div><!-- /input-group -->
                            {!! Form::close() !!}
                        </div>

                    </div>

                </div>

                <div class="layout-content ">

                    <div class="table-responsive plan-master">

                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Link URL</th>
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
													echo url("/")."/public/offerBannerFiles/".$blg->image;
												}
												else { echo url("/")."/img/camera-icon.jpg"; }
											?>" class="img-circle" alt="User Image" height="50" width="50">
                                    </td>
                                    <td>{{$blg->title}}</td>
                                    <td>{{$blg->link_url}}</td>
                                    <!--<td>{{$blg->description}}</td>-->
                                    <td><span
                                            class="label-default label @if($blg->status == '1') label-success @else label-danger @endif">@if($blg->status
                                            == '1') Active @else Inactive @endif</span></td>
                                    <td>
                                        <button onclick="editOffersBanner({{$blg->id}});" class="btn btn-info btn-sm"
                                            data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></button>
                                        <button onclick="deleteOffersBanner({{$blg->id}});"
                                            class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right"
                                            title="Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                  <td colspan="6">No Record Found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                
                </div>

			     	<div class="page-nation text-right">
                        <ul class="pagination pagination-large">
                            {{ $banners->appends($_GET)->links() }}
                        </ul>
                    </div>

            </div>
        </div>

      
    </div>

    <div class="modal fade" id="bannerEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>

    <script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script>

    <!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->


    <!-- /.content-wrapper -->
    <script src="{{ URL::asset('js/jquery-printme.js') }}"></script>
    <script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
    <script>
    jQuery(document).ready(function() {
        $('#multipleSelect1').multiselect({
            nonSelectedText: 'Select Department',
            includeSelectAllOption: true,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
        });
        $('#multipleSelect2').multiselect({
            nonSelectedText: 'Select User',
            includeSelectAllOption: true,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
        });
    });

    function editOffersBanner(id) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "HTML",
            url: "{!! route('admin.editOffersBanner')!!}",
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

    function deleteOffersBanner(id) {
        if (confirm('Are you sure want to delete?') == true) {
            jQuery('.loading-all').show();
            jQuery.ajax({
                type: "POST",
                dataType: "JSON",
                url: "{!! route('admin.deleteOffersBanner')!!}",
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