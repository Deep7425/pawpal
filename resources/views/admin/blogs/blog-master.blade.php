@extends('layouts.admin.Masters.Master')
@section('title', 'Blog Master')
@section('content')



<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
  
                <div class="container-fluid flex-grow-1 container-p-y page">


                    <div class="row mb-2  form-top-row">
                       
                       

                            <div class="btn-group">
                                <a class="btn btn-success" href="{{ route('admin.addBlog') }}"> <i
                                        class="fa fa-plus"></i> Add Blog</a>
                            </div>

                            <div class="btn-group">
                                <a class="btn btn-success" href="javascript:void();">{{$blogs->total()}}</a>
                            </div>

                      
{{--                            <div class="btn-group">--}}
{{--                                <a class="btn btn-success"--}}
{{--                                    tabindex="0"><span>CSV</span></a>--}}
{{--                             </div>--}}

                    
                        <div class=" row-right">


                        <div class="head-select">
                                {!! Form::open(array('route' => 'admin.blogMaster', 'id' => 'chnagePagination',
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

                          
                                <div class="head-search-sm custom-search-form">
                                    <input name="search" type="text" class="form-control capitalizee"
                                        placeholder="Search By Title" value="{{ old('search') }}" />

                                </div>
                          
                      

                       
                            <div class="head-search-btn">
                                <div class=" custom-search-form btn-search">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit">
                                            SARCH
                                        </button>
                                    </span>
                                </div>

                                {!! Form::close() !!}
                            </div>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Url</th>
                                    <th>Keyword</th>
                                    <!--<th>Description</th>-->
                                    <th>Type(Web,App)</th>
                                    <th style="width: 100px;">Publish Date</th>
                                    <th>Video Publish</th>
                                    <th>status</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($blogs->count() > 0)
                                @foreach($blogs as $index => $blg)
                                <tr>
                                    <td>
                                        <label>{{$index+($blogs->currentpage()-1)*$blogs->perpage()+1}}.</label>
                                    </td>
                                    <td>
                                        <img src="<?php
												if(!empty($blg->image)){
													echo url("/")."/public/newsFeedFiles/".$blg->image;
												}
												else { echo url("/")."/img/camera-icon.jpg"; }
											?>" class="img-circle" alt="User Image" height="50" width="50">
                                    </td>
                                    <td>{{$blg->title}}</td>
                                    <td>{{$blg->slug}}</td>
                                    <td>{{$blg->keyword}}</td>
                                    <?php $types = []; if(!empty($blg->type)){ $types = explode(',',$blg->type); } ?>
                                    <td>@if(count(array_intersect($types,array(1,2))) == count(array(1,2))) Both
                                        @elseif(in_array(1,$types)) App @elseif(in_array(2,$types)) Web @else No @endif
                                    </td>
                                    <td>@if(!empty($blg->publish_date)) {{date('d-M-Y', strtotime($blg->publish_date))}}
                                        @endif</td>
                                    <td>@if(!empty($blg->video)) <label class="toggle-btn"> <input type="checkbox"
                                                class="toggle-btn-radio" id="{{$blg->id}}"
                                                publish="{{$blg->video_publish}}" @if($blg->video_publish == 1) checked
                                            @endif> <span class="button-slider round"></span> </label> @else Video Not
                                        Available @endif</td>
                                    <td><span
                                            class="label-default label @if($blg->status == '1') label-success @else label-danger @endif">@if($blg->status
                                            == '1') Active @else Inactive @endif</span></td>



                                    <td>{{ @$blg->admin->name }}</td>

                                    <td class="action">
                                        <a href="{{route('admin.viewBlog',$blg->slug)}}" class="btn btn-info btn-sm"
                                            data-toggle="tooltip" data-placement="top" title="Blog View"><i
                                                class="fa fa-eye" aria-hidden="true"></i></a>
                                        <button onclick="editBlog({{$blg->id}});" class="btn btn-info btn-sm"
                                            data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></button>
                                        <button onclick="deleteBlog({{$blg->id}});" class="btn btn-danger btn-sm"
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

                </div>
            </div>
        </div>
        <div class="modal fade" id="blogEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>





<!-- <script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> -->




<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script>
jQuery(document).on("click", ".toggle-btn-radio", function(e) {
    var id = $(this).attr('id');
    var publish = $(this).attr('publish');
    var current = $(this);
    if (publish == 2) {
        var text = "Are you sure Publish Video"
    } else {
        var text = "Are you sure Unpublish Video";
    }
    if (confirm(text)) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "HTML",
            url: "{!! route('admin.editBlog') !!}",
            data: {
                'id': id,
                'publish': publish,
                'action': '2'
            },
            success: function(data) {
                jQuery('.loading-all').hide();
                if (publish == 2) {
                    $(current).attr('publish', '1');
                } else {
                    $(current).attr('publish', '2');
                }
            },
            error: function(error) {
                jQuery('.loading-all').hide();
                alert("Oops Something goes Wrong.");
            }
        });
    } else {
        return false;
    }

});



function editBlog(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.editBlog')!!}",
        data: {
            'id': id,
            'action': '1'
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#blogEditModal").html(data);
            jQuery('#blogEditModal').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

function deleteBlog(id) {
    if (confirm('Are you sure want to delete?') == true) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.deleteBlog')!!}",
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