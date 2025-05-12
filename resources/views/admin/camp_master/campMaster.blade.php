@extends('layouts.admin.Masters.Master')
@section('title', 'Health Gennie Camp Master')
@section('content')
<!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">

                @if(session()->get('successMsg'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ session()->get('successMsg') }}
                </div>
                @endif
                <div class="row  form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript::void(0)" data-toggle="modal"
                            data-target="#AddModal"> <i class="fa fa-plus"></i> New</a>
                    </div>

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$camps->total()}}</a>

                    </div>

                   
                    <div class="btn-group head-search">

                        <div class="">
                            {!! Form::open(array('route' => 'admin.campMaster', 'id' => 'chnagePagination',
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

                        <div class="mar-r5 mar-l5" >
                            <select class="form-control" name="camp_id" style = "border: 1px solid #ddd; border-radius: 4px !important;">
                                <option value="">Select Camp Title</option>
                                @if(count(getCampTitleMaster())>0)
                                @foreach(getCampTitleMaster() as $val)
                                <option value="{{$val->id}}" @if(isset($_GET['camp_id']))
                                    @if(base64_decode($_GET['camp_id'])==$val->id) selected @endif
                                    @endif>{{$val->title}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mar-r5">
                            <div class="custom-search-form">
                                <input name="search" type="text" class="form-control capitalizee"
                                    placeholder="Search By ThyroCare Order Number" value="{{ old('search') }}" />
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
            

                <div class="layout-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Camp Title</th>
                                    <th>ThyroCare Order Number</th>
                                    <th>ThyroCare Lead Id</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($camps->count() > 0)
                                @foreach($camps as $index => $row)
                                <tr>
                                    <td>
                                        <label>{{$index+($camps->currentpage()-1)*$camps->perpage()+1}}.</label>
                                    </td>
                                    <td>{{$row->CampTitleMaster->title}}</td>
                                    <td>{{$row->thy_ref_order_no}}</td>
                                    <td>{{$row->thy_lead_id}}</td>
                                    <td>{{@$row->user->first_name}}</td>
                                    <td>{{@$row->user->last_name}}</td>
                                    <td>{{@$row->user->mobile_no}}</td>
                                    <td>{{@$row->user->email}}</td>
                                    <td>
                                        <button onclick="editCamp({{$row->id}});" class="btn btn-info btn-sm"
                                            data-toggle="tooltip" data-placement="left" title="Edit"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></button>
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

				<div class="page-nation text-right">
                        <ul class="pagination pagination-large">
                            {{ $camps->appends($_GET)->links() }}
                        </ul>
                    </div>

            </div>
        </div>

        <div class="modal fade" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">Add Camp Data</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel panel-bd lobidrag">
                            <div class="panel-heading">

                            </div>
                            <div class="panel-body">
                                {!! Form::open(array('id' => 'addCamp','name'=>'addCamp')) !!}
                                <div class="form-group col-sm-6">
                                    <label>First Name</label>
                                    <input value="" type="text" name="first_name" class="form-control"
                                        placeholder="Enter First Name">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Last Name</label>
                                    <input value="" type="text" name="last_name" class="form-control"
                                        placeholder="Enter Last Name">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Email</label>
                                    <input value="" type="text" name="email" class="form-control"
                                        placeholder="Enter Email">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Mobile No</label>
                                    <input value="" type="text" name="mobile_no" class="form-control"
                                        placeholder="Enter Mobile No">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>ThyroCare Order Number</label>
                                    <input value="" type="text" name="thy_ref_order_no" class="form-control"
                                        placeholder="Enter ThyroCare Order Number">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>ThyroCare Lead Id</label>
                                    <input value="" type="text" name="thy_lead_id" class="form-control"
                                        placeholder="ThyroCare Lead Id">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Camp Title</label>
                                    <select class="form-control camp_idF" name="camp_id">
                                        <option value="">Select Camp Title</option>
                                        @if(count(getCampTitleMaster())>0)
                                        @foreach(getCampTitleMaster() as $val)
                                        <option value="{{$val->id}}">{{$val->title}}</option>
                                        @endforeach
                                        @endif
                                        <option value="0">Other</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group other_titleF col-sm-6" style="display:none;">
                                    <label>Other Name of Camp Title</label>
                                    <input value="" type="text" name="other_title" class="form-control"
                                        placeholder="Name of Camp Title">
                                    <span class="help-block"></span>
                                </div>

                                <div class="reset-button col-sm-12">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success submit">Submit</button>
                                </div>
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

    </div>
    <div class="modal fade" id="EditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>



<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script>

<!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->
<!-- /.content-wrapper -->
<script>
$(document.body).on('change', '.camp_idF', function() {
    if ($(this).val() == '0') {
        $(".other_titleF").show();
    } else {
        $(".other_titleF").hide();
    }
});
$(document.body).on('click', '.submit', function() {
    jQuery("form[name='addCamp']").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            email: "required",
            mobile_no: "required",
            thy_ref_order_no: "required",
            thy_lead_id: "required",
            camp_id: "required",
            other_title: "required",
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
                url: "{!! route('admin.addCamp')!!}",
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

function editCamp(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.editCamp')!!}",
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



function chnagePagination(e) {
    $("#chnagePagination").submit();
}
</script>
@endsection