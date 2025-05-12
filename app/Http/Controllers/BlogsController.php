<?php

namespace App\Http\Controllers;
  
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\NewsFeeds;
use App\Models\BlogLikes;
use App\Models\BlogComment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Mail\Mailer;
class BlogsController extends Controller
{


	public function blogList(Request $request, $keyword = null)
	{
		try {
			$search = '';
			if ($request->isMethod('post')) {
				$data = $request->all();
				if ($data['lastId'] != null) {
					$loadBlogs = NewsFeeds::where('status', 1)->whereRaw("find_in_set('2',news_feeds.type)")->whereDate('publish_date', '<=', date("Y-m-d"))->whereDate('show_date', '<', $data['lastId'])->orderBy('show_date', 'DESC')->limit(2)->get();
					return view($this->getView('blogs.loadMoreBlogs'), ['loadBlogs' => $loadBlogs]);
				}

				$params = array();
				if (!empty($request->input('search'))) {
					$params['search'] = $request->input('search');
				}
				if (!empty($request->input('page_no'))) {
					$params['page_no'] = base64_encode($request->input('page_no'));
				}
				return redirect()->route('blogList', $params)->withInput();
			} else {
				if (!empty($request->input('search'))) {
					$search = $request->input('search');
					$search = str_replace("-", " ", $search);
				}
				$query = NewsFeeds::where('status', 1)->whereRaw("find_in_set('2',news_feeds.type)")->whereDate('publish_date', '<=', date("Y-m-d"));
				$blogs_sidebar = NewsFeeds::where('status', 1)->whereRaw("find_in_set('2',news_feeds.type)")->whereDate('publish_date', '<=', date("Y-m-d"))->orderBy('show_date', 'desc')->limit(5)->get();
				if (!empty($search)) {
					$query->where('keyword', 'like', '%' . $search . '%');
				}
				$page = 10;
				if (!empty($request->input('page_no'))) {
					$page = base64_decode($request->input('page_no'));
				}
				$blogs = $query->orderBy('show_date', 'desc')->limit(4)->get();
			}
			return view($this->getView('blogs.blog_list'), ['blogs' => $blogs, 'blogs_sidebar' => $blogs_sidebar]);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function blogInfo(Request $request, $slug)
	{

		try {
			$blog = NewsFeeds::where('slug', $slug)->first();
			$user_id = (Auth::user() != null ? Auth::user()->id : "");
			$ip = $request->ip();
			if (!empty($blog)) {
				$like = BlogLikes::where(['ip' => $ip])->Where('blog_id', $blog->id)->first();
				$comments = BlogComment::with('user')->Where('blog_id', $blog->id)->Where('publish', 1)->orderBy('id', 'desc')->get();
				$likeCount = BlogLikes::Where('blog_id', $blog->id)->count();
				$blogs = NewsFeeds::where('status', 1)->whereRaw("find_in_set('2',news_feeds.type)")->orderBy('show_date', 'desc')->limit(5)->get();
				$ttl = $blog->blog_count + 1;
				NewsFeeds::where('slug', $slug)->update(array(
					'blog_count' => $ttl,
				));
				return view($this->getView('blogs.blogs_details'), ['blog' => $blog, 'blogs' => $blogs, 'like' => $like, 'likeCount' => $likeCount, 'comments' => $comments]);
			} else {
				return abort(404);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function blogSearch(Request $request)
	{

		try {

			$data = $request->all();
			if (isset($data['getFavBlogs']) && $data['getFavBlogs'] == 'getFavBlogs') {
				$keywords = NewsFeeds::select('keyword')->where('status', 1)->whereDate('publish_date', '<=', date("Y-m-d"))->groupBy('keyword')->WhereNotNull('keyword')->limit(5)->get();
				$blogs = null;
			} elseif (isset($data['search_key']) || empty($data['search_key'])) {
				$query1 = NewsFeeds::select('keyword')->where('status', 1)->whereDate('publish_date', '<=', date("Y-m-d"))->groupBy('keyword')->WhereNotNull('keyword');
				if (!empty($data['search_key'])) {
					$query1->where('keyword', 'like', '%' . $data['search_key'] . '%');
				} else {
					$query1->orderBy('id', 'desc')->limit(5);
				}
				$keywords = $query1->get();
				$query = NewsFeeds::where('status', 1)->whereRaw("find_in_set('2',news_feeds.type)")->whereDate('publish_date', '<=', date("Y-m-d"));
				if (!empty($data['search_key'])) {
					$query->where('keyword', 'like', '%' . $data['search_key'] . '%');
				}
				$blogs = $query->orderBy('id', 'desc')->limit(10)->get();
				foreach ($blogs as $blog) {
					$image_url = url("/") . "/public/newsFeedFiles/" . $blog['image'];
					if (!empty($image_url)) {
						if (does_url_exists($image_url)) {
							$blog['image_url'] = $image_url;
						} else {
							$blog['image_url'] = null;
						}
					} else {
						$blog['image_url'] = null;
					}
				}
			}
			return ["keywords" => $keywords, 'blogs' => $blogs];
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function blogLikeComment(Request $request)
	{

		try {
			if ($request->isMethod('post')) {
				$data = $request->all();
				$ip = $request->ip();
				if ($data['action'] == 'like') {
					if ($data['likeId'] != 0) {
						BlogLikes::Where('id', $data['likeId'])->delete();
						$id = 0;
					} else {
						$blog = BlogLikes::create([
							'user_id' => (Auth::user() != null ? Auth::user()->id : null),
							'blog_id' => $data['blogId'],
							'ip' => $ip,
						]);
						$id = $blog->id;
					}
					return $id;
				} elseif ($data['action'] == 'comment') {
					$comment = BlogComment::create([
						'user_id' => (Auth::user() != null ? Auth::user()->id : null),
						'blog_id' => $data['blogId'],
						'comment' => $data['comment'],
					]);
					return ['user_name' => $comment->user->first_name . ' ' . $comment->user->last_name, 'comment' => $comment->comment];
				} elseif ($data['action'] == 'reply') {
					BlogComment::where('blog_id', base64_decode($data['blogId']))->where('id', base64_decode($data['id']))->update([
						'comment' => $data['comment']
					]);
					return 1;
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function clickComment(Request $request)
	{

		try {

			if (Auth::user() == null) {
				$data = $request->all();
				Session::put('loginFrom', '4');
				Session::put('hgBlogData', $data['blog_url']);
				return redirect()->route('login');
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
}
