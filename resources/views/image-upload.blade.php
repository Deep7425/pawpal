<!DOCTYPE html>
<html>
<head>
	<title>Laravel 5.3 Amazon S3 Image Upload with Validation example</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>


<div class="container">
  <div class="panel panel-primary">
    <div class="panel-heading"><h2>Laravel 5.3 Amazon S3 Image Upload with Validation example</h2></div>


    <div class="panel-body">


      @if (count($errors) > 0)
	 <div class="alert alert-danger">
	    <strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
		  @foreach ($errors->all() as $error)
		    <li>{{ $error }}</li>
		  @endforeach
		 </ul>
	    </div>
      @endif


	  @if ($message = Session::get('success'))
		<div class="alert alert-success alert-block">
			<button type="button" class="close" data-dismiss="alert">×</button>
		        <strong>{{ $message }}</strong>
		</div>
		<!-- <img src="{{ Session::get('path') }}"> -->
	  @endif

		<button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal">Upload File</button>
		<button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal2">Create Folder</button>
	  <h2>Amezone S3 Buket</h2>

		<ul class="breadcrumb">
			@if(!empty($currentUrl) > 0)
			<li><a href="{{route('Home')}}">Root</a></li>
			<?php $breadcrumb = explode("/",$currentUrl); $appendUrl = ""; ?>
			@foreach($breadcrumb as $index => $url)
			<?php $appendUrl .= $url; ?>
		  	<li><a href="{{route('enterDirectory',['prefix'=>$appendUrl])}}">{{$url}}</a></li>
				<?php $appendUrl .= '/'; ?>
			@endforeach
			@else
			<li><a href="{{route('Home')}}">Root</a></li>
			@endif
		</ul>
	  <table class="table table-condensed">
	    <thead>
	      <tr>
	        <th>SR.</th>
	        <th>File</th>
	        <th>Action</th>
	      </tr>
	    </thead>
	    <tbody>
				<?php $i = 1;  ?>
				@if(count($files) > 0)
				@foreach($files as $index => $file)
		      <tr>
		        <td>{{$i++}}</td>
		        <td><img src="{{$file}}" width="50"></td>
		        <td><a href="{{route('deleteFile',['prefix'=>$S3AllFiles[$index]])}}" class="btn btn-info btn-md">Delete</a></td>
		      </tr>
				 @endforeach
				 @endif
				 @if(count($directories) > 0)
 				@foreach($directories as $index => $direct)
					<?php
					$lastUrl =  substr(strrchr(rtrim($direct, '/'), '/'), 1);
					if (empty($lastUrl)) {
						$lastUrl = $direct;
					}
					?>

 		      <tr>
 		        <td>{{$i++}}</td>
 		        <td><a href="{{route('enterDirectory',['prefix'=>$direct])}}">{{$lastUrl}}</a></td>
 		        <td>
							<?php /* <a href="{{route('deleteFile',$S3AllFiles[$index])}}" class="btn btn-info btn-md">Delete</a> */ ?>
							</td>
 		      </tr>
 				 @endforeach
 				 @endif

	    </tbody>
	  </table>


	  <!-- <form action="{{ url('s3-image-upload') }}" enctype="multipart/form-data" method="POST">
		{{ csrf_field() }}
		<div class="row">
			<div class="col-md-12">
				<input type="file" name="image" />
			</div>
			<div class="col-md-12">
				<button type="submit" class="btn btn-success">Upload</button>
			</div>
		</div>
	  </form> -->
    </div>


  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload File</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('imageUploadPost')}}" enctype="multipart/form-data" method="POST">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-6">
						<input type="file" name="image" />
						<input type="hidden" name="directory" value="{{$currentUrl}}" />
					</div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-success">Upload</button>
					</div>
				</div>
			  </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal2" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Create Folder</h4>
			</div>
			<div class="modal-body">
				<form action="{{ route('createFolder') }}" enctype="multipart/form-data" method="POST">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-6 form-group">
						<input type="text" class="form-control" name="folder_name" value="">
						<input type="hidden" name="directory" value="{{$currentUrl}}" />
					</div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-success">Create</button>
					</div>
				</div>
			  </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>


</body>
</html>
