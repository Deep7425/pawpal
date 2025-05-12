@extends('layouts.admin.Masters.Master') @section('title', 'Subadmin User Permission') @section('content')
<!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y user-list">
                <div class="row form-top-row">
                    <div class="btn-group">
                        <a class="btn btn-success" href="{{ route('admin.addSubAdmin') }}"> <i class="fa fa-plus"></i> Add Sub Admin </a>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">@if(count($users) > 0) {{count($users)}} @endif</a>
                    </div>
                </div>
                <div class="layout-content card body-edit pad-2">
                    <div class="panel-body">
                        <div class="row ">
               
                                <div class="col-sm-3">
                                    <div class="">
                                        <select class="form-control" id="users" name="user">
                                            @if(count($users) > 0) @foreach($users as $user) @if($user->id != 1)
                                            <option value="{{$user->id}}">  {{$user->name}} </option>
                                            @endif @endforeach @endif
                                        </select>
                                    </div>
                                </div>
                            
                        </div>

                        <div class="row">
                            
                            <div class="LoadUserPermission" id="LoadUserPermission"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="doctorEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 
<script type="text/javascript">
    function LoadUserPermission(user_id) {
        jQuery(".loading-all").show();
            console.log("sdfg");
            var url = "{!! route('admin.LoadUserPermission')!!}";
           console.log("URL: " + url);

        jQuery.ajax({
            type: "POST",
            dataType: "HTML",
            url: "{!! route('admin.LoadUserPermission')!!}",
            data: { user_id: user_id },
            success: function (data) {
                jQuery(".loading-all").hide();
                console.log("data" , data)
                jQuery("#LoadUserPermission").html(data);
            },
            error: function (error) {
                jQuery(".loading-all").hide();
                alert("Oops Something goes Wrong.");
            },
        });
    }

    $(document).ready(function () {
        // console.log("354353")
        $(window).load(function () {
            var user_id = $("#users option:selected").val();
            console.log(user_id);
            LoadUserPermission(user_id);
        });
    });

    $("#users").on("change", function () {
        var user_id = this.value;
        console.log(user_id);
        LoadUserPermission(user_id);
    });

    jQuery(document).on("click", "#saveNow", function (e) {
        var modules_access = [];
        $.each($("input[name='module_id']:checked"), function () {
            modules_access.push($(this).val());
        });
        var user_id = $("#users option:selected").val();
        // alert($('.user_names').val());
        if (modules_access.length > 0) {
            $(".loading-all").show();
            $.ajax({
                type: "POST",
                dataType: "HTML",
                url: "{!! route('admin.LoadUserPermission') !!}",
                data: { save_permissions: "save_permissions", user_id: user_id, modules_access: modules_access },

                success: function (data) {
                    $(".loading-all").hide();
                    jQuery("#LoadUserPermission").html(data);
                    alert("Permissions saved successfully");
                },
                error: function (error) {
                    $(".loading-all").hide();
                    alert("Oops Something goes Wrong.");
                    //jQuery('.submit').attr('disabled',false);
                },
            });
        } else {
            alert("Please choose atleast a module.");
        }
    });
</script>
@endsection
