@extends('layouts.admin.Masters.Master')
@section('title', 'Add Banner')
@section('content')


<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y add-offers-banner">

                <div class="row form-top-row">
                    <div class="col-sm-3">
                        <div class="btn-group">
                            <a class="btn btn-primary" href="{{ route('admin.offersBannerMaster') }}"> <i
                                    class="fa fa-list"></i> Banners List</a>
                        </div>
                    </div>
                </div>


                <div class="layout-content card">

                    {!! Form::open(array('route' => 'admin.addOffersBanner', 'id' => 'addOffersBanner','enctype' =>
                    'multipart/form-data' , 'class' => 'col-sm-12')) !!}
                    <div class="row">

                        <div class="form-group col-sm-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter Title Name">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group col-sm-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" placeholder="Select Image">
                            <span class="help-block"></span>
                        </div>

                        <div class="form-group col-sm-3">
                            <label>Type</label>
                            <select class="form-control valid" name="type">
                                <option value="">Select Type</option>
                                <option value="1">Home - en</option>
                                <option value="2">Lab - en</option>
                                <option value="3">Home - hi</option>
                                <option value="4">Lab - hi</option>
                                <option value="5">Med - en</option>
                                <option value="6">Med - hi</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group col-sm-3">
                            <label> Banner Type</label>
                            <select class="form-control valid" name="banner_type">
                                <option value="">Select Type</option>

                                <option value="0"> App</option>
                                <option value="1"> Web</option>

                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group col-sm-3">
                            <label>Link URL</label>
                            <input type="text" name="link_url" class="form-control" value=""
                                placeholder="Enter Link URL">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group col-sm-3 PackageTypeNew">
                            <label> Package Type</label>
                            <select class="form-control valid" name="package_id" id="">
                                <option value="">Select Package Type</option>
                                @foreach($labPackageid as $data)
                                <option value="{{$data->id}}"> {{$data->title}}</option>
                                @endforeach

                            </select>
                            <span class="help-block"></span>
                        </div>

                        <div class="form-check col-sm-3">
                            <label>Status</label><br>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="1" checked="checked">Active</label>
                            <label class="radio-inline"><input type="radio" name="status" value="0">Inctive</label>
                        </div>
                        <div class="reset-button col-sm-12">
                            <button type="reset" class="btn btn-warning">Reset</button>
                            <button type="submit" class="btn btn-success submit">Save</button>
                        </div>

                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>












<script src="{{ URL::asset('js/jquery-printme.js') }}"></script>
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>

<script src="{{ URL::asset('js/bootstrap.js') }}"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script type="text/javascript">
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
    jQuery("#addOffersBanner").validate({
        rules: {
            title: "required",
            type: "required",
        },
        messages: {},
        errorPlacement: function(error, element) {
            error.appendTo(element.next());
        },
        ignore: ":hidden",
        submitHandler: function(form) {
            $(form).find('.submit').attr('disabled', true);
            jQuery.ajax({
                type: "POST",
                dataType: "JSON",
                url: "{!! route('admin.addOffersBanner')!!}",
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data == 1) {
                        jQuery('.loading-all').hide();
                        $(form).find('.submit').attr('disabled', false);
                        document.location.href =
                            '{!! route("admin.offersBannerMaster")!!}';
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
</script>
@endsection