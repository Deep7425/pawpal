@extends('layouts.admin.Masters.Master')
@section('title', 'Spaciality Master')
@section('content')

<style>
.label-success{
    position:relative;

}

</style>

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y doctor-speciality">

                <div class="row form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="{{ route('admin.addSpeciality') }}"> <i class="fa fa-plus"></i>
                            Add Speciality</a>
                    </div>

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$specialities->total()}}</a>
                    </div>

                    <div class="btn-group head-search">
                        <div class="ml-sm-2">
                            {!! Form::open(array('route' => 'admin.specialityAll', 'id' => 'chnagePagination',
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
                        <div class="ml-sm-2">
                            <select class="form-control searchDropDown" name="grp_speciality">
                                <option value="">Speciality Group</option>
                                @foreach(getSpecialityGroupList() as $spc)
                                <option value="{{ $spc->id }}" @if(old('grp_speciality')==$spc->id) selected @endif
                                    >{{ $spc->group_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ml-sm-2">
                            <div class=" custom-search-form">
                                <input name="search" type="text" class="form-control capitalizee"
                                    placeholder="Search by Specialities" value="{{ old('search') }}" />

                            </div>
                        </div>

                        <div class="ml-sm-2">
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
                </div>

                <div class="layout-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Icon</th>
                                    <th>Image</th>
                                    <th>Order No.</th>
                                    <th>Specialities</th>
                                    <th>Specialty Slug</th>
                                    <th>Specialty (Hindi)</th>
                                    <th>Description</th>
                                    <th>Specialist</th>
                                    <th>Specialist Text</th>
                                    <th>Description(Hindi)</th>
                                    <th>Group</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($specialities->count() > 0)
                                @foreach($specialities as $index => $blg)
                                <tr>
                                    <td>
                                        <label>{{$index+($specialities->currentpage()-1)*$specialities->perpage()+1}}.</label>
                                    </td>
                                    <td>
                                        <img src="<?php
												if(!empty($blg->speciality_icon)){
													echo url("/")."/public/speciality-icon/".$blg->speciality_icon; 
												}
												else { echo url("/")."/img/camera-icon.jpg"; }
											?>" class="img-circle" alt="Speciality Image" height="50" width="50">
                                    </td>
                                    <td>
                                        <img src="<?php
												if(!empty($blg->speciality_image)){
													echo url("/")."/public/speciality-images/".$blg->speciality_image; 
												}
												else { echo url("/")."/img/camera-icon.jpg"; }
											?>" class="img-circle" alt="Speciality Image" height="50" width="50">
                                    </td>
                                    <td>{{@$blg->order_no}}</td>
                                    <td>{{$blg->specialities}}</td>
                                    <td>{{$blg->slug}}</td>
                                    <td>{{$blg->spaciality_hindi}}</td>
                                    <td>{{$blg->spec_desc}}</td>
                                    <td>{{$blg->spaciality}}</td>
                                    <td>{{$blg->speciality_text}}</td>
                                    <td>{{$blg->spec_desc_hindi}}</td>
                                    <td>{{@$blg->SpecialityGroup->group_name}}</td>
                                    
                                    <td><span
                                            class="label-default label @if($blg->status == '1') label-success @else label-danger @endif">@if($blg->status
                                            == '1') Active @else Inactive @endif</span></td>
                                    <td>

                                        <button onclick="editSpeciality({{$blg->id}});" class="btn btn-info btn-sm"
                                            data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></button>
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
                 
                </div>
                <div class="page-nation text-right d-flex justify-content-end mt-2 mb-2">
                        <ul class="pagination pagination-large">
                            {{ $specialities->appends($_GET)->links() }}
                        </ul>
                    </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="spacialityEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->


<!-- /.content-wrapper -->

<script>
$(".searchDropDown").select2();

function editSpeciality(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.editSpeciality')!!}",
        data: {
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#spacialityEditModal").html(data);
            jQuery('#spacialityEditModal').modal('show');
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
</script>
@endsection