
@if(count($loadBlogs))
	@foreach($loadBlogs as $blog)
		<div class="blog-list" lastId="{{ date("Y-m-d", strtotime($blog->show_date))}}">  
			<a href="{{route('blogInfo',['slug'=>$blog->slug])}}"><img src="@if(!empty($blog->image)) <?php echo url("/")."/public/newsFeedFiles/".$blog->image;?> @else @endif" />
		  <h6>{{@$blog->keyword}}</h6>
		  <div class="date-post">@if(!empty($blog->publish_date)) {{date('F j , Y',strtotime($blog->publish_date))}} @endif</div>
		  <h2>{{@$blog->title}}</h2>
		  <span>Health Gennie</span></a>
		</div>
	@endforeach
@endif