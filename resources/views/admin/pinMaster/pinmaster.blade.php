@extends('layouts.admin.Masters.Master')
@section('title', ' Pin Code')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">


                <div class="row mb-2 ml-1 form-top-row">
                    <div class="btn-group">
                        <a class="btn btn-success" href="" data-toggle="modal" data-target="#AddModal"> <i
                                class="fa fa-plus"></i> Add Pin Code</a>
                    </div>
                </div>

                <div class="layout-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width:70px;">S.No.</th>
                                    <th>Company Name</th>
                                    <th>Company Id </th>
                                    <th>Pin Code </th>
                                    <th>User Name</th>
                                    <th style="width:115px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($getlabcompanypin as $key =>$data)
                                <tr>

                                    <td>{{$key+1}}</td>
                                    <td>{{@$data->LabCompany->title}}</td>
                                    <td>({{$data->company_id}})</td>
                                    <td>{{$data->pincode}}</td>
                                    <td>{{@$data->admin->name}}</td>
                                    <td style="width:115px;">
                                        <button onclick="editLab('{{$data->id}}');" class="btn btn-info btn-sm"
                                            data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></button>
                                        <button onclick="deleteLab({{$data->id}});" class="btn btn-danger btn-sm"
                                            data-toggle="tooltip" data-placement="right" title="Delete "><i
                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="page-nation text-right d-flex justify-content-end mt-2 mb-2 ml-1">
                    <ul class="pagination pagination-large">
                        {{ $getlabcompanypin->appends($_GET)->links() }}
                    </ul>
                </div>
            </div>
        </div>
    </div>

 


                <div class="modal fade" id="editLabModal" role="dialog" data-backdrop="static" data-keyboard="false">
                </div>
            </div>

			<div class="modal fade modal-dialog1234" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Add Pin Code</h4>
                </div>
                <div class="modal-body add-pin">
                  
                      <div class="panel-heading"></div>
                        <div class="panel-body form-groupTtalNew">
                            {!! Form::open(array('name' => 'addLab', 'method' => 'POST')) !!}
                                <div class="row">
                            <div class="col-md-6 pad-left0">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <select name="company_id" class="form-control ">
                                        @forelse(getLabCompaniesAdmin() as $raw)
                                        <option value="{{$raw->id}}">{{$raw->title}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group labDropDown">
                                    <label>Pin </label>
                                    <input name="pincode" type="number" class="form-control"
                                        placeholder="Enter Discount" />
                                    <span class="help-block"></span>
                                </div>
                            </div>
							
                            <div class="reset-button">
                                <button type="reset" class="btn btn-warning">Reset</button>
                                <button type="submit" class="btn btn-success submitLab">Submit</button>
                            </div></div>

                            {!! Form::close() !!}
                        </div>
                 
                </div>
                </div>
                </div>
                </div>


            <script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"> </script>

			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
            <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
            <script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
            <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
            <script type="text/javascript">
            $(document).ready(function() {
                setValue();
            });

            function setValue() {
                $('#exampleSelect1').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
            }

            function editLab(id) {
                jQuery('.loading-all').show();
                jQuery.ajax({
                    type: "POST",
                    dataType: "HTML",
                    url: "{!! route('admin.LabCompanypin.edit')!!}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'id': id
                    },
                    success: function(data) {
                        jQuery('.loading-all').hide();
                        jQuery("#editLabModal").html(data);
                        jQuery('#editLabModal').modal('show');
                    },
                    error: function(error) {
                        jQuery('.loading-all').hide();
                        alert("Oops Something goes Wrong.");
                    }
                });
            }

            function deleteLab(id) {
                if (confirm('Are you sure want to delete?') == true) {
                    jQuery('.loading-all').show();
                    jQuery.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: "{!! route('admin.LabCompany.delete.pin')!!}",
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
            jQuery(document).ready(function() {
                jQuery("form[name='addLab']").validate({
                    rules: {
                        company_id: {
                            required: true
                        },
                        pincode: {
                            required: true,
                            rangelength: [6, 6],
                            number: true
                        },

                    },
                    messages: {},
                    errorPlacement: function(error, element) {
                        error.appendTo(element.next());
                    },
                    ignore: ":hidden",
                    submitHandler: function(form) {
						console.log("22")
                        $(form).find('.submit').attr('disabled', true);

                        jQuery.ajax({
                            type: "POST",
                            dataType: "JSON",
                            url: "{!! route('create.company.pin')!!}",
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
                            }
                        });
                    }
                });
            });


            // jQuery(document).on("click", ".dataLabList", function () {
            // $('input[name="lab_id"]').val(jQuery(this).attr('value'));
            // jQuery(this).closest(".form-group").find(".labSearch").val(jQuery(this).find('.txt').text());
            // jQuery(this).closest(".form-group").find(".labSearch").attr('readonly',true);
            // jQuery(this).closest(".suggesstion-box").hide();
            // jQuery(this).closest(".suggesstion-box ul").remove();
            // });
            </script>

            @endsection