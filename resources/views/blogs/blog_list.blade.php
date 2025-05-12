@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content')

<div class="container blog-inner">
  <div class="container-inner">
    <div class="container blog-wrapper-section">
      <div class="blog-crasuseal" id="LoadBlogs">
      	<div id="BlogLists">
	  @if(count($blogs))
		  @foreach($blogs as $blog)
			<div class="blog-list" lastId="{{ date("Y-m-d", strtotime($blog->show_date))}}">  
				<a href="{{route('blogInfo',['slug'=>$blog->slug])}}"><img src="@if(!empty($blog->image)) <?php echo url("/")."/public/newsFeedFiles/".$blog->image;?> @else @endif" />
			  <h6>{{@$blog->keyword}}</h6>
			  <div class="date-post">@if(!empty($blog->publish_date)) {{date('F j , Y',strtotime($blog->publish_date))}} @endif</div>
			  <h2>{{@$blog->title}}</h2>
			  <span>Health Gennie</span></a>
			</div>
		@endforeach
		@endif
		</div>
		<div class="blankBlogDivLoader" style="display: none;">
			<div class="blog-list blank-1">  
				<div class="blank-img blank-2"> </div>
				<div class="New-blank">
			  <h6 class="blank-4"></h6>
			  </div>
			  <div class="date-post blank-5"></div>
			  <div class="New-blank">
			  <h2 class="blank-6"></h2>
			  </div>
			  
			  <span class="blank-7"></span></a>
			</div>
			<div class="blog-list blank-1">  
				<div class="blank-img blank-2"> </div>
				<div class="New-blank">
			  <h6 class="blank-4"></h6>
			  </div>
			  <div class="date-post blank-5"></div>
			  <div class="New-blank">
			  <h2 class="blank-6"></h2>
			  </div>
			  <span class="blank-7"></span></a>
			</div>
			
		</div>
      </div>


      <div class="continer-right">
             <div class="blog-search" style="padding-bottom:10px;">
            	<h3>Search Health Gennie Blog</h3>
                <input class="blog_search_by" type="text" placeholder="Search Your Keyword.." />
				<div class="dd-wrapper blogSearchByInput" style="display:none;"></div>
            </div>
        	<div class="similar-post">
            	<h3>Similar Blogs</h3>
                 <div class="similar-blog-list-wrapper blog-listed-by-search blogAppendList">
				 @if(count($blogs_sidebar))
					@foreach($blogs_sidebar as $blog)
						<div class="similar-blog-list">
							  <img src="@if(!empty($blog->image)) <?php echo url("/")."/public/newsFeedFiles/".$blog->image;?> @else @endif" />
							  <div class="content">
							  <h4>{{@$blog->title}}</h4>
							  <a data-id="{{$blog->id}}" class="blog_info_click" href="{{route('blogInfo',['slug'=>$blog->slug])}}">Read More</a>
							  </div>
						</div>
					@endforeach
				@endif
                </div>
            </div>

            <div class="subscription">
            	<h3>Subscribe Health Gennie Blog</h3>
                <p>Our e-mail updates will keep you informed on our company, new products, stories from the millions of people we help live healthier longer lives.</p>
                <div class="Get_company_search">
					<input class="email_subcription" type="email" placeholder="Enter Your Email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"  placeholder="Email" />
					<button type="button" class="email_subcription_btn">Subscribe</button>
				</div>
            </div>
        </div>

	</div>
	
  </div>
  
</div>
<script>
	  $(document).ready(function(){
     var action = 'inactive';
    // // alert($(window).scrollTop());
    // alert($(window).height());
    // // alert($(window).scrollTop() + $(window).height());
    // alert($("#LoadLabOrders").height());

  // var action = 'inactive';
  function LoadBlogs(lastId) {
    $('.blankBlogDivLoader').show();
    // var filter = $('.filters').find('.active').attr('filter')
    action = 'active';
    var token = $('meta[name="csrf-token"]').attr('content');
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('blogList') !!}",
    data: {'_token':token,'lastId':lastId},
    success:function(data)
    {

       $('.blankBlogDivLoader').hide();
     $('#BlogLists').append(data);
     if(data == '')
     {
     	 $('.blankBlogDivLoader').hide();
      clearInterval();
     }
     else
     {
     	 $('.blankBlogDivLoader').hide();
      action = "inactive";
     }
   },
      error: function(error)
      {
        if(error.status == 401)
        {
            alert("Session Expired,Please logged in..");
            location.reload();
        }
        else
        {
          jQuery('.loading-all').hide();
          alert("Oops Something goes Wrong.");
          jQuery('#saveAddress').attr('disabled',false);
        }
      }
   });
  }

// if(action == 'inactive')
// {
//  action = 'active';
//  var lastId = null;
//  LoadBlogs(lastId);
// }
var search =  '{{app("request")->input("search")}}';
if (search == "") {
	$(window).scroll(function(){
	  if($(window).scrollTop() + $(window).height() > $("#LoadBlogs").height() && action == 'inactive')
	  {
	   action = 'active';
	   var lastId = $("#BlogLists .blog-list").last().attr('lastId');
	   setTimeout(function(){
	    LoadBlogs(lastId);
	   }, 200);
	  }
	});
}


});
jQuery(document).on("keyup paste click", ".blog_search_by", function (e) {
	  var currSearch = this;
		var blogSearchByInputDiv = jQuery('.blogSearchByInput').find('.search-data div').length;
		$(".blogSearchByInput").hide();
		$(".blogSearchByInput .search-data").remove();
		if(e.originalEvent.detail == 1) {
			if(jQuery(this).val().length < 3){
				if(blogSearchByInputDiv <= 0){
					getFavBlogs(this);
				}
			}
			else{
				if(jQuery(this).val().length >= 1) {
					var currSearch = jQuery(this).val();
					if(blogSearchByInputDiv <= 0){
						searchBlog(currSearch);
					}
					$(".search_close_lab").show();
				}
			}
		}
		else if (e.originalEvent.key == 'Backspace') {
			if (this.value == "") {
					currSearch = null;
					searchBlog(currSearch);
			}
			else {
				searchBlog(this.value);
			}
		}
		else {
			if(jQuery(this).val().length >= 1) {
				searchBlog(currSearch);
				$(".search_close_lab").show();
			}
			else{
				getFavBlogs(this);
			}
		}
});
var currentBlogRequest ;
function searchBlog(currSearch) {
	if(currentBlogRequest){
					currentBlogRequest.abort();
			}
	currentBlogRequest = jQuery.ajax({
		type: "POST",
		url: "{!! route('blogSearch') !!}",
		data: {'search_key':$(currSearch).val()},
		beforeSend: function() {
			jQuery(currSearch).css("background","#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
		},
		success: function(data){
			console.log(data);
			var rowToAppend = "";
			if(data.keywords.length > 0) {
			jQuery.each(data.keywords,function(k,v) {
			    var keyword = v.keyword.replace(/\s+/g, '-').toLowerCase();;
				var url = "{{url("/")}}/blog?search="+keyword;
				rowToAppend += '<div class="dd viewBlogs"> <a href="'+url+'" class="keywords"><i class="icon-ic_gps_system"></i><div class="detail"><span class="text">'+v.keyword+'</span></div> </a></div>';
				jQuery('.blogSearchByInput').css('display','block');
				jQuery('.blogSearchByInput').html('<div class="search-data">'+rowToAppend+'</div>');
			});
		}

			var liToAppend = "";
		  if(data.blogs.length > 0) {
			jQuery.each(data.blogs,function(k,v){
					var url = '{{ route("blogInfo", ":slug") }}';
					    url = url.replace(':slug', v.slug);
				  liToAppend +='<div class="similar-blog-list"> <img src="'+v.image_url+'" /> <div class="content"> <h4>'+v.title+'</h4> <a  data-id="'+v.id+'" class="blog_info_click" href="'+url+'">Read More</a> </div> </div>';
			  });
			}
		  else {
			liToAppend += '<div class="similar-blog-list blog-not-found"> <img class="blog-search-icon" src="{{ URL::asset("img/search-dd.png") }}" /> <div class="content"><a href="javascript:void(0);">"'+jQuery(currSearch).val()+'" Blog Not Found.</a> </div> </div>';
		  }
		 $(".blogAppendList").html(liToAppend);
		},
		error: function(error) {
			if(error.status == 401 || error.status == 419){
				location.reload();
			}
		}
	});
}

var currentFavBlogRequest ;
function getFavBlogs(current) {
	if(currentFavBlogRequest){
					currentFavBlogRequest.abort();
			}
	currentFavBlogRequest = jQuery.ajax({
		type: "POST",
		url: "{!! route('blogSearch') !!}",
		data: {'getFavBlogs':'getFavBlogs'},
		beforeSend: function() {
			jQuery(current).css("background","#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
		},
		success: function(data){
			var rowToAppend = "";
			if(data.keywords.length > 0) {
				jQuery.each(data.keywords,function(k,v) {
						// var url = '{{ route("blogList", ":search") }}';
					 //    url = url.replace(':search', v.keyword);
					   var keyword = v.keyword.replace(/\s+/g, '-').toLowerCase();;
				var url = "{{url("/")}}/blog?search="+keyword;
					rowToAppend += '<div class="dd viewBlogs"><a href="'+url+'" class="keywords"><i class="icon-ic_gps_system"></i><div class="detail"><span class="text">'+v.keyword+'</span></div></a></div>';
				});
			}
			jQuery('.blogSearchByInput').css('display','block');
			jQuery('.blogSearchByInput').html('<div class="search-data">'+rowToAppend+'</div>');

		},
		error: function(error) {
			if(error.status == 401 || error.status == 419){
				location.reload();
			}
		}
	});
}
</script>
@endsection
