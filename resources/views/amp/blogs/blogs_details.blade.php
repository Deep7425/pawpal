@extends('amp.layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content')

<?php $image_url = url("/").'/img/avatar_2x.png'; ?>
	<div class="container blog-detail">
		<div class="container-inner">
		@if(isset($blog) && !empty($blog))
			<?php $blog_url = url("/")."/blog/".$blog->slug; ?>
            <div class="top-header-blog">
            	<p>{{@$blog->keyword}}</p>
                <p>@if(!empty($blog->publish_date)) {{date('F j , Y',strtotime($blog->publish_date))}} @endif</p>
            </div>
			<h1>{{@$blog->title}}</h1>
            
			@if(!empty($blog->video) && $blog->video_publish == '1')
				<div class="blog-video-section hideforPaytm"> 
					<iframe class="videoSectionIfrm" width="100%" height="100%" src="{{$blog->video}}" allowfullscreen >
					</iframe>
				</div>
			@else
				<img src="@if(!empty($blog->image)) <?php echo url("/")."/public/newsFeedFiles/".$blog->image; ?> @else @endif" />
			@endif
            <div class="blog-description-class">{!!@$blog->description!!}</div>

			<div id="action-bar" class="hideforPaytm" style="display: inline-block">
			  <a href="#" class="btn btn-default disabled" title="Views"><i class="fa fa-eye"></i>{{thousandsCurrencyFormat($blog->blog_count)}}</a>
			  <a  @if(Auth::user() != null) href="javascript:void(0);" @else href="{{ route('clickComment',['blog_url'=>$blog_url]) }}" @endif class="btn btn-default fa fa-comment @if(Auth::user() != null) blogComment @endif" title="Comment" data-original-title="View comments"><i class="icon-comment"></i>&nbsp;</a>
				<a href="javascript:void(0);" class="btn btn-default blog_like blogLike @if($like != null) blogLiked @else blogUnLike @endif" status="@if($like != null) {{$like->id}} @else 0 @endif" data-id="{{$blog->id}}" title="Like"><span class="fa fa-thumbs-up"></span> <span class="likeCount">{{$likeCount}}</span></a>
				<div class="share-button">
						<a href="javascript:void(0);" class="btn btn-default social-toggle" title="Share" ><span class="fa fa-share-alt"></span></a>
				    <div class="social-networks">
						<ul>
							<li class="social-facebook">
									<a href="javascript:void(0);" id="share-fb" class="sharer button" title="Facebook"><i class="fa fa-3x fa-facebook-square"></i></a>
							</li>
				            <li class="social-twitter">
				                <a href="javascript:void(0);" id="share-tw" class="sharer button" title="twitter"><i class="fa fa-3x fa-twitter-square"></i></a>
				            </li>
							<li class="social-linkedin">
								 <a href="javascript:void(0);" id="share-li" class="sharer button" title="Linkedin"><i class="fa fa-3x fa-linkedin-square"></i></a>
							</li>
				        </ul>
				    </div>
				</div>

			</div>

			<div class="comments-box" style="display:none;">
				<div class="section">
					<textarea name="message" class="comment" id="CommentTextbox" rows="5" cols="128" placeholder="Enter Your Comment (max : 255 character)"></textarea>
					<span class="help-block commentError" style="display:none;"><label generated="true" class="error"></label></span>
				</div>
				<div class="section div-post-comment">
					<button type="button" class="form-control postComment" data-id="{{$blog->id}}">Post</button>
				</div>
			</div>
			<div class="success-msg" style="display:none"><i class="fa fa-check-circle" aria-hidden="true"></i> <span class="cmt-msg"></span></div>
			<div class="comment-section">
			@if(count($comments))
				<div class="container">
					<div class="row">
						<div class="col-sm-12">
						<h3>Comments..</h3>
						</div>
					</div>
					<div id="commentList">
						<input type="hidden" id="bId" value="{{base64_encode($blog->id)}}">
	 				@foreach($comments as $comment)
						<div class="row">
							<div class="col-sm-1">
								<div class="thumbnail">
								<?php
									if(!empty($comment->user->image)) {
										$image_urls = getEhrUrl()."/public/patients_pics/".$comment->user->image;
										if(does_url_exists($image_urls)) {
											$image_url = $image_urls;
										}
									}
								?>
								<img class="img-responsive user-photo" src='{{$image_url}}'>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="panel panel-default">
								<div class="panel-heading">
								<strong>{{$comment->user->first_name}} {{$comment->user->last_name}}</strong> <span class="text-muted">{{getTimeElapsedString($comment->created_at)}}</span>
								</div>
								<div class="panel-body comment-body">
									@if(Auth::user() != null && Auth::user()->id == $comment->user_id)
									  <span class="editComment" title="Edit">edit</span>
										<span class="save saveComment" title="Save" data-id="{{base64_encode($comment->id)}}" style="display:none;">save</span>
								  @endif

									<div class="comment-box" placeholder="Type comments...">
											{{$comment->comment}}
									</div>
								</div>
								<!-- <div class="comment-edit editComment"><i class="fa fa-pencil" aria-hidden="true"></i></div> -->
								</div>
							</div>
						</div>
					@endforeach
				
					</div>
				</div>
				@endif
			</div>
		@endif
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
				 @if(count($blogs))
					@foreach($blogs as $blog)
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
	<script src="https://cdn.jsdelivr.net/sharer.js/latest/sharer.min.js"></script>
<script>
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
				// var currSearch = jQuery(this).val();
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
						var url = "{{url("/")}}/blog?search="+v.keyword;
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
$(".blog-description-class").find("p").first().addClass("first-para");

jQuery(document).on("click", ".blogComment", function (e) {
	$('.comments-box').slideToggle('slow');
	$('.commentError').hide();
	$('.commentError label').text('');
	$('#CommentTextbox').val('');
});
jQuery(document).on("click", ".postComment", function (e) {
	var comment = $('#CommentTextbox').val();
	 var blogId = $(this).attr('data-id');
	if (comment != "") {
		jQuery('.loading-all').show();
		jQuery.ajax({
			type: "POST",
			url: "{!! route('blogLikeComment') !!}",
			data: {'action':'comment','blogId':blogId,'comment':comment},
			success: function(data){
				$('#CommentTextbox').val('');
				$('.comments-box').slideToggle('slow');
				$('.success-msg').show();
				$('.success-msg .cmt-msg').text('Comment Sent Successfully & will be published after being verified by Admin.');
				setTimeout(function(){ 	$('.success-msg').slideUp(); $('.success-msg .cmt-msg').text(); }, 7500);
				// $('.success-msg').show();
				jQuery('.loading-all').hide();
			},
			error: function(error) {
				if(error.status == 401 || error.status == 419){
					location.reload();
				}
				else{
					alert("Oops Something goes Wrong.");
				}
			}
		});
	}
	else {
		$('.commentError').show();
		$('.commentError label').text('This field is required');
	}
});
var max = 255;
jQuery(document).on("keyup paste", "#CommentTextbox", function (e) {
	if (this.value != "") {
		$('.commentError').hide();
		$('.commentError label').text('');

		 if (this.value.length == max) {
			e.preventDefault();
		} else if (this.value.length > max) {
			this.value = this.value.substring(0, max);
		}
	}
	else {
		$('.commentError').show();
		$('.commentError label').text('This field is required');
	}
});
function FocusEndOfText(element) {
	var text = element.text();
	var setpos = document.createRange();
	var set = window.getSelection();
	setpos.setStart(element[0].childNodes[0], text.length);
	setpos.collapse(true);
	set.removeAllRanges();
	set.addRange(setpos);
	element.focus();
}
jQuery(document).on("click", ".editComment", function (e) {
	$('#commentList').find('.panel-body').removeClass('editable');
	$('#commentList').find('.panel-body .comment-box').removeAttr('contenteditable');
	$('#commentList').find('.saveComment').hide();
	$('#commentList').find('.editComment').show();

	var comment = $(this).closest('.panel-default').find('.panel-body .comment-box').text().trim();
	$(this).hide();
	$(this).parent().addClass('editable');;
	$(this).parent().find('.comment-box').attr('contenteditable', 'true');
	var text = $(this).parent().find('.comment-box').text();
	$(this).parent().find('.saveComment').show();

	FocusEndOfText($(this).parent().find('.comment-box'))

	// var text = $(this).closest('.panel-default').find('.panel-body .comment-box');
	// // $('#CommentTextbox').val(cmt);
	// // $('.comments-box').slideDown('slow');
	// 	input = $('<textarea name="message" class="comment" rows="5" cols="110" placeholder="Enter Your Comment (max : 255 character)"></textarea>')
	//
	// text.hide()
	// 	.after(input);
	// input.val(comment).show().focus()
	// 	.keypress(function(e) {
	// 		var key = e.which
	// 		if (key == 13) // enter key
	// 		{
	// 			input.hide();
	// 			alert(input.val());
	// 			text.html(input.val())
	// 				.show();
	// 			return false;
	// 		}
	// 	})
	// 	.focusout(function() {
	// 		input.hide();
	// 		text.show();
	// 	})
});
jQuery(document).on("click", ".saveComment", function (e) {
	// $(this).hide();
	// $(this).parent().find('.panel-default').removeClass('editable');
	// $(this).parent().find('.comment-box').removeAttr('contenteditable');
	// $(this).parent().find('.editComment').show();
	var comment = $(this).parent().find('.comment-box').text().trim();
	var currect = $(this);
	if (comment != "") {
		$(currect).parent().find('.comment-box').css('border','');
		var blogId = $('#bId').val();
		var id = $(this).attr('data-id').trim();
		jQuery.ajax({
			type: "POST",
			url: "{!! route('blogLikeComment') !!}",
			data: {'action':'reply','blogId':blogId,'id':id,'comment':comment},
			success: function(data){
				if (data == 1) {
					$(currect).hide();
					$(currect).parent().removeClass('editable');
					$(currect).parent().find('.comment-box').removeAttr('contenteditable');
					$(currect).parent().find('.editComment').show();
				}
			},
			error: function(error) {
				if(error.status == 401 || error.status == 419){
					location.reload();
				}
			}
		});
	}
	else {
		$(currect).parent().find('.comment-box').focus();
		$(currect).parent().find('.comment-box').css('border',' 2px solid red');
	}
});
var maxText = 255;
jQuery(document).on("keyup paste", ".comment-box", function (e) {
	var text = $(this).text().trim();
	if (text != "") {
		$(this).css('border','');
		// $('.commentError').hide();
		// $('.commentError label').text('');

		 if (text.length == maxText) {
			 	$(this).css('border','');
			e.preventDefault();
		} else if (text.length > maxText) {
			text = text.substring(0, maxText);
			 $(this).text(text);
			 	FocusEndOfText($(this))
			 (this).css('border',' 2px solid red');
		}
	}
	else {
		(this).css('border',' 2px solid red');
		// $('.commentError').show();
		// $('.commentError label').text('This field is required');
	}
});
jQuery(document).on("click", ".blogLike", function (e) {

	var blogId = $(this).attr('data-id');
	var likeId = $(this).attr('status').trim();
	var currect = $(this);
	currect.addClass('disabled');
	jQuery.ajax({
		type: "POST",
		url: "{!! route('blogLikeComment') !!}",
		data: {'action':'like','blogId':blogId,'likeId':likeId},
		success: function(data){
			currect.removeClass('disabled');
			if (likeId != 0) {
				$(currect).addClass('blogUnLike');
				$(currect).removeClass('blogLiked');
				$(currect).attr('status', data);
				var likeCount = + $('.likeCount').text().trim() - 1;
				$('.likeCount').text(likeCount);
			}
			else {
				$(currect).addClass('blogLiked');
				$(currect).removeClass('blogUnLike');
				$(currect).attr('status', data);
				var likeCount = + $('.likeCount').text().trim() + 1;
				$('.likeCount').text(likeCount);
			}
		},
		error: function(error) {
			if(error.status == 401 || error.status == 419){
				location.reload();
			}
		}
	});
});
/*jQuery(document).on("click", "#share-wa", function (e) {
	var url = window.location.href;
	var blog_url = $('#share-wa').attr('data-url', url);
	var shrUrl = 'https://api.whatsapp.com/send?phone=917691079774&text='+blog_url+'&source=&data=';
	window.location = shrUrl;
});*/
jQuery(document).on("click", ".social-toggle", function (e) {
	$(this).next().toggleClass('open-menu');
});


$(document).ready(function(){
   var url = window.location.href;
   var title = document.title;
   var subject = "Read this good article";
   var via = "yourTwitterUsername";
//facebook
$('#share-wa').attr('data-url', url).attr('data-title', title).attr('data-sharer', 'whatsapp');
//facebook
$('#share-fb').attr('data-url', url).attr('data-sharer', 'facebook');
//twitter
$('#share-tw').attr('data-url', url).attr('data-title', title).attr('data-via', via).attr('data-sharer', 'twitter');
//linkedin
$('#share-li').attr('data-url', url).attr('data-sharer', 'linkedin');
// google plus
$('#share-gp').attr('data-url', url).attr('data-title', title).attr('data-sharer', 'googleplus');
  // email
$('#share-em').attr('data-url', url).attr('data-title', title).attr('data-subject', subject).attr('data-sharer', 'email');
});

</script>
@endsection
