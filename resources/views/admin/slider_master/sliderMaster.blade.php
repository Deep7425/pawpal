@extends('layouts.admin.Masters.Master')
@section('title', 'Slider Master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y slider-master">
                @if(session()->get('successMsg'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ session()->get('successMsg') }}
                </div>
                @endif
                <div class="row form-top-row">

                    <div class="btn-group mr-1">
                        <a class="btn btn-success" href="javascript::void(0)" data-toggle="modal"
                            data-target="#AddModal"> <i class="fa fa-plus"></i> Add Slider </a>
                    </div>

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$sliders->total()}}</a>
                    </div>

					<div class="btn-group head-search">
				      <div class="">
                                {!! Form::open(array('route' => 'admin.sliderMaster', 'id' => 'chnagePagination',
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

							<div class="mar-r5 mar-l5">
                                <div class="input-group custom-search-form ">
                                    <input name="search" type="text" class="form-control capitalizee"
                                        placeholder="Search By Title" value="{{ old('search') }}" />
                                </div>
                            </div>

							<div class="">
                                <div class="input-group custom-search-form">
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
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($sliders->count() > 0)
                                @foreach($sliders as $index => $row)
                                <tr>
                                    <td>
                                        <label>{{$index+($sliders->currentpage()-1)*$sliders->perpage()+1}}.</label>
                                    </td>
                                    <td>{{$row->title}}</td>
                                    <td>@if(!empty($row->image)) <img
                                            src="<?php echo url("/")."/public/slidersImages/".$row->image;?>" alt=""
                                            width="80"> @endif</td>
                                    <td>{{$row->description}}</td>
                                    <td>
                                        <button onclick="editFun({{$row->id}});" class="btn btn-info btn-sm"
                                            data-toggle="tooltip" data-placement="left" title="Edit"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></button>
                                        <button onclick="deleteFun({{$row->id}});" class="btn btn-danger btn-sm"
                                            data-toggle="tooltip" data-placement="right" title="Delete "><i
                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5">No Record Found </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="page-nation text-right">
                        <ul class="pagination pagination-large">
                            {{ $sliders->appends($_GET)->links() }}
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade add-slide-modal" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Add Slider</h4>
                </div>
                <div class="modal-body ">
                    <div class="">
                   
                        <div class="panel-body">
                            {!! Form::open(array('id' => 'addSlider','name'=>'addSlider', 'enctype' =>
                            'multipart/form-data')) !!}
                           <div class="row">
                            <div class="col-md-6">
                                <label>Title</label>
                                <input value="" type="text" name="title" class="form-control" placeholder="Enter Title">
                                <span class="help-block"></span>
                            </div>
                            <div class="col-md-6">
                                <label>Slider Image</label>
                                <input type="file" name="image" class="form-control" onchange='openFile(event)'
                                    id="upload-file-selector" / placeholder="">
                                <span class="help-block"></span>
                                <span id="fileselector"></span>
                            </div>
                            <div class="col-md-6">
                                <img src="" id="blah" alt="" width="100" style="display:none;">
                            </div>

                            <div class="col-md-12">
                                <label>Description</label>
                                <textarea value="" class="form-control" name="description" id="sliderDescription"
                                    rows="5"></textarea>
                                <span class="help-block"></span>
                            </div>

                            <div class="col-md-12">
                                <div class="reset-button">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success submit" id="submit-btn">Submit</button>
                                </div>
                            </div> </div>
                            {!! Form::close() !!}

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="EditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>


</div>
<!-- /.content-wrapper -->

<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->
<script>
$(document.body).on('click', '.submit', function() {
    // jQuery("#modifySubAdmin").validate({
    jQuery("form[name='addSlider']").validate({
        rules: {
            title: {
                required: true,
                minlength: 1,
                maxlength: 100,
            },
            image: "required",
        },
        messages: {},
        errorPlacement: function(error, element) {

            error.appendTo(element.parent().find('.help-block'));
        },
        ignore: ":hidden",
        submitHandler: function(form) {
            $(form).find('.submit').attr('disabled', true);
            jQuery.ajax({
                type: "POST",
                dataType: "JSON",
                url: "{!! route('admin.addSlider')!!}",
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data == 1) {
                        jQuery('.loading-all').hide();
                        $(form).find('.submit').attr('disabled', false);
                        location.reload();
                    } else {
                        jQuery('.loading-all').hide();
                        $(form).find('.submit').attr('disabled', false);
                        alert("Oops Something Problem");
                    }
                },
                error: function(error) {
                    jQuery('.loading-all').hide();
                    alert("Oops Something goes Wrong.");
                }
            });
        }
    });
});

function editFun(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.editSlider')!!}",
        data: {
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#EditModal").html(data);
            jQuery('#EditModal').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

function deleteFun(id) {
    if (confirm('Are you sure want to delete?') == true) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.modifySliderMaster')!!}",
            data: {
                'action': 'delete',
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

function changePassword(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.modifySubAdmin')!!}",
        data: {
            'id': id,
            'action': 'openChangePassModal'
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#subAdminEditModal").html(data);
            jQuery('#subAdminEditModal').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

jQuery('.changeStatus').on('click', function() {
    var id = $(this).attr('data-id');
    var status = $(this).attr('status');
    if (status == 1) {
        var text = 'Are you sure to Inactive User ?';
    } else {
        var text = 'Are you sure to Active User ?';
    }
    if (confirm(text)) {
        jQuery.ajax({
            url: "{!! route('admin.modifySubAdmin') !!}",
            type: "POST",
            dataType: "JSON",
            data: {
                'action': 'statusChange',
                'id': id,
                'status': status
            },
            success: function(result) {
                location.reload();

            }
        });
    } else {
        return false;
    }
})

function chnagePagination(e) {
    $("#chnagePagination").submit();
}

function openFile(event) {
    $("#submit-btn").attr('disabled', false);
    var input = event.target;
    var FileSize = input.files[0].size / 1024 / 1024; // 10in MB
    var type = input.files[0].type;
    var fileName = input.files[0].name;
    var ext = input.files[0].name.split('.').pop().toLowerCase();
    var reader = new FileReader();
    if (FileSize > 3) {
        $('#blah').hide();
        $('#fileselector').next(".help-block").remove();
        $('#fileselector').after(
            ' <span class="help-block"><label for="title" generated="true" class="error">Allowed file size exceeded. (Max. 3 MB)</label></span>'
            );

    } else if ($.inArray(ext, ['png', 'jpg', 'jpeg']) >= 0) {
        $("#submit-btn").attr('disabled', false);
        reader.addEventListener("load", function() {
            if ($.inArray(ext, ['png', 'jpg', 'jpeg']) >= 0) {

                $('#blah').attr('src', reader.result);
                $('#blah').show();
                $('#fileselector').next(".help-block").remove();
                $('#fileselector').after(' <span class="help-block" style="color:green;">(' + fileName +
                    ')File Browsed Successfully.</span>');
            } else {
                $('#fileselector').next(".help-block").remove();
                $('#fileselector').after(' <span class="help-block" style="color:green;">(' + fileName +
                    ')File Browsed Successfully.</span>');
            }
        });
        reader.readAsDataURL(input.files[0]);
        //alert(reader.result);
    } else {
        $("#submit-btn").attr('disabled', true);
        $('#blah').hide();
        $('#fileselector').next(".help-block").remove();
        $('#fileselector').after(
            ' <span class="help-block"><label for="title" generated="true" class="error">Only formats are allowed : (jpeg,jpg,png)</label></span>'
            );
    }
}
</script>
@endsection