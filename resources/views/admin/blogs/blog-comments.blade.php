@extends('layouts.admin.Masters.Master')
@section('title', 'Blog Comments')
@section('content')
    
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="layout-content"  >
              <div class="container-fluid flex-grow-1 container-p-y page">

              <!-- <h4 class="font-weight-bold py-3 mb-0">Blog Comments</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Admin</a></li>
                                <li class="breadcrumb-item active"><a href="#!">Blog Comments</a></li>
                            </ol>
                        </div> -->

                        <div class="row  form-top-row">

                     
                              <div class="btn-group">
                                        <a class="btn btn-success" href="{{ route('admin.addBlog') }}"> <i class="fa fa-plus"></i>  Add Blog</a>
                                    </div>

									              <div class="btn-group">
                                        <a class="btn btn-success" href="javascript:void();">{{$comments->total()}}</a>
                                    </div>
                   

                        
                                        
										<div class="btn-group row-right" >

                   
                            <div class="head-select">
										   	  {!! Form::open(array('route' => 'admin.blogComments', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                             <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                              <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                              <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
                              <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
                              <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
                            </select>
                         </div>
                                    

											<div class="head-search-sm">
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="Search By Title" value="{{ old('search') }}"/>
													
												</div>
											</div>
									
										
											<div class="head-search-btn">
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

                       
                        <div class="table-responsive ">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Image</th>
                                        <th style="width:120px;">Blog Name</th>
                                        <th>User Name</th>
                                        <th>Comment</th>
                                        <th>Publish</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($comments->count() > 0)
								@foreach($comments as $index => $comment)
                                    <tr>
										<td>
											<label>{{$index+($comments->currentpage()-1)*$comments->perpage()+1}}.</label>
										</td>
										<td>
											<img src="<?php
												if(!empty(@$comment->Blog->image)){
													echo url("/")."/public/newsFeedFiles/".@$comment->Blog->image;
												}
												else { echo url("/")."/img/camera-icon.jpg"; }
											?>" class="img-circle" alt="User Image" height="50" width="50">
										</td>
										<td style="width:120px;">{{@$comment->Blog->title}}</td>
                    <td>{{@$comment->user->first_name}} {{@$comment->user->last_name}}</td>
                    <td>{{@$comment->comment}}</td>
                    <td><label class="toggle-btn"> <input type="checkbox" class="toggle-btn-radio" id="{{$comment->id}}" publish="{{$comment->publish}}" @if($comment->publish == 1) checked @endif> <span class="button-slider round"></span> </label></td>
									</tr>
								@endforeach
								@else
									<tr><td colspan="6">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>

              </div>
            </div>
        </div>
    </div>
</div>



<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>

<script>


jQuery(document).on("click", ".toggle-btn-radio", function (e) {
  var id = $(this).attr('id');
  var publish = $(this).attr('publish');
  var current = $(this);
  if (publish == 2) {
    var text = "Are you sure Publish Comment"
  }
  else {
    var text = "Are you sure Unpublish Comment";
  }
  if (confirm(text)) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.blogCommentPublish') !!}",
    data:{'id':id, 'publish':publish},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      if (publish == 2) {
        $(current).attr('publish','1');
      }
      else {
        $(current).attr('publish','2');
      }
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
  }
  else{
  return false;
  }

});


function chnagePagination(e) {
	$("#chnagePagination").submit();
}

</script>
@endsection
