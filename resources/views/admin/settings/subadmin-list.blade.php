@extends('layouts.admin.Masters.Master') @section('title', 'Subadmin list') @section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="layout-content">
          <div class="container-fluid flex-grow-1 container-p-y">
            <div class="row form-top-row">
           
                   <div class="btn-group">
                                <a class="btn btn-success" href="{{ route('admin.addSubAdmin') }}"> <i class="fa fa-plus"></i> Add Subadmin</a>
                            </div>
                             <div class="btn-group">
                                <a class="btn btn-success" href="javascript:void();">{{$users->total()}}</a>
                            </div>
                            <!-- <div class="btn-group">
                                <a class="btn btn-success btn-md" tabindex="0" style = "color : white;"><span>CSV</span></a>
                            </div> -->
                        
                        
                         <div class="btn-group head-search ml-sm-2">
                            <div class="btn-group">
                            
                                {!! Form::open(array('route' => 'admin.subadminList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}

                                    <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                        <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                                        <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
                                        <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
                                        <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
                                    </select>
                           
                        </div>
                            <div class="btn-group mar-l5 mar-r5">
                                <div class="custom-search-form symptom-search-box">
                                    <input name="search" type="search" class="col-sm-5 form-control capitalizee" placeholder="Search By Title" value="{{ old('search') }}"/>
                                </div>
                            </div>                      
                    

                        <div class="btn-group mar-r5">
                             
                                <div class="input-group custom-search-form">
                                    <input name="search" type="text" class="form-control capitalizee" placeholder="Search By Name" value="{{ old('search') }}"/>
                                    
                                </div>
                           
                         </div>
                        <div class="btn-group">
                            
                                <div class="custom-search-form">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit">
                                            SEARCH
                                        </button>
                                    </span>
                                </div>
                                {!! Form::close() !!}
						
                  

                     </div>
            <!-- <h4 class="font-weight-bold py-3 mb-0">Subadmin List</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Admin</a></li>
                                <li class="breadcrumb-item active"><a href="#!">Subadmin List</a></li>
                            </ol>
                        </div> -->

                      @if(session()->get('successMsg'))
                      <!-- <div class="alert alert-success">
                        <strong>Success!</strong> {{ session()->get('successMsg') }}
                      </div> -->

                      <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session()->get('successMsg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>

                      @endif
                </div>

            </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>E-Mail</th>
                                    <th>Mobile No.</th>
                                    <th>status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($users->count() > 0) @foreach($users as $index => $user) @if($user->id != 1)
                                <tr>
                                    <td>
                                        <label>{{$index+($users->currentpage()-1)*$users->perpage()+1}}.</label>
                                    </td>

                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->mobile_no}}</td>
                                    <!-- <td><span class="label-default label @if($user->status == '1') label-success @else label-danger @endif">@if($user->status == '1') Active @else Inactive @endif</span></td> -->
                                    <td>
                                        <a class="btn @if($user->status == '0') btn-success @else btn-danger @endif changeStatus" status="{{$user->status}}" data-id="{{$user->id}}" href="javascript:void();">
                                            @if($user->status == '0') Active @else Inactive @endif
                                        </a>
                                    </td>

                                    <td>
                                        <button onclick="editSubAdmin({{$user->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                        @if(Session::get('userdata')->id == 1)
                                        <button onclick="changePassword({{$user->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Change Password"><i class="fa fa-key" aria-hidden="true"></i></button>
                                        @endif
                                        <!-- <button onclick="deleteSubAdmin({{$user->id}});" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></button> -->
                                    </td>
                                </tr>
                                @endif @endforeach @else
                                <tr>
                                    <td colspan="6">No Record Found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>  
                </div>
                <div class="page-nation text-right d-flex justify-content-end mt-2 mb-2 ">
                        <ul class="pagination pagination-large">
                          {{ $users->appends($_GET)->links() }} 
                        </ul>
                      </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="subAdminEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
 </div>
 <div class="modal fade" id="subAdminEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 

<script>
    function editSubAdmin(id) {
        jQuery(".loading-all").show();
        jQuery.ajax({
            type: "POST",
            dataType: "HTML",
            url: "{!! route('admin.editSubAdmin')!!}",
            data: { id: id },
            success: function (data) {
                jQuery(".loading-all").hide();
                jQuery("#subAdminEditModal").html(data);
                jQuery("#subAdminEditModal").modal("show");
            },
            error: function (error) {
                jQuery(".loading-all").hide();
                console.log("error-1")
                alert("Oops Something goes Wrong.");
            },
        });
    }

    function deleteSubAdmin(id) {
        if (confirm("Are you sure want to delete?") == true) {
            jQuery(".loading-all").show();
            jQuery.ajax({
                type: "POST",
                dataType: "JSON",
                url: "{!! route('admin.modifySubAdmin')!!}",
                data: { action: "delete", id: id },
                success: function (data) {
                    if (data == 1) {
                        location.reload();
                    } else {
                        alert("Oops Something Problem");
                    }
                    jQuery(".loading-all").hide();
                },
                error: function (error) {
                    jQuery(".loading-all").hide();
                    console.log("error-2")
                    alert("Oops Something goes Wrong.");
                },
            });
        }
    }
    function changePassword(id) {
        jQuery(".loading-all").show();
        jQuery.ajax({
            type: "POST",
            dataType: "HTML",
            url: "{!! route('admin.modifySubAdmin')!!}",
            data: { id: id, action: "openChangePassModal" },
            success: function (data) {
                jQuery(".loading-all").hide();
                jQuery("#subAdminEditModal").html(data);
                jQuery("#subAdminEditModal").modal("show");
            },
            error: function (error) {
                // location.reload(true);
                jQuery(".loading-all").hide();
                console.log("error-3")
                alert("Oops Something goes Wrong.");
            },
        });
    }

    jQuery(".changeStatus").on("click", function () {
        var id = $(this).attr("data-id");
        var status = $(this).attr("status");
        if (status == 1) {
            var text = "Are you sure to Inactive User ?";
        } else {
            var text = "Are you sure to Active User ?";
        }
        if (confirm(text)) {
            jQuery.ajax({
                url: "{!! route('admin.modifySubAdmin') !!}",
                type: "POST",
                dataType: "JSON",
                data: { action: "statusChange", id: id, status: status },
                success: function (result) {
                    location.reload();
                },
            });
        } else {
            return false;
        }
    });
    function chnagePagination(e) {
        $("#chnagePagination").submit();
    }
</script>
@endsection
