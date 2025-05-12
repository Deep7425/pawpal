@extends('layouts.admin.Masters.Master') @section('title', 'View Blog') @section('content')
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y view-blog">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <div class="layout-content card">
        <div class="row mt-2 ml-2 mr-2">
          <div class="col-sm-12">
            
                  <div class="panel-heading mb-2">
                      <div class="btn-group">
                          <a class="btn btn-primary" href="{{ route('admin.blogMaster') }}"> <i class="fa fa-list"></i> Blogs List</a>
                      </div>
                  </div>
                  <div class="panel-body">
                    @if(isset($blog) && !empty($blog))
                    <div class="top-header-blog">
                        <p>{{@$blog->keyword}}</p>
                        <p>@if(!empty($blog->created_at)) {{date('F j , Y',strtotime($blog->created_at))}} @endif</p>
                    </div>
                    <h1>{{@$blog->title}}</h1>
                    <img src="@if(!empty($blog->image)) <?php echo url("/")."/public/newsFeedFiles/".$blog->image;?> @else @endif" />
                    <div class="blog-description-class">{!!@$blog->description!!}</div>
                    @endif
                  </div>
              </div>
    



        </div>
        </div>


    <!-- /.content -->
</div>
</div>
</div>
</div>

@endsection
