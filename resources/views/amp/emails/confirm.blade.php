@extends('auth.layouts.loginMaster')
@section('title', 'Login')
@section('content')
<style>

.login_sucess_ico i {
    border: 4px solid #d46f18;
    border-radius: 50%;
    color: #ffffff;
    display: inline-block;
    font-size: 75px;
    height: 120px;
    padding: 18px;
    width: 120px;
    box-shadow: 0px 4px 7px 0px #62a8f4;
}
.login_sucess_text {letter-spacing: 1px; margin-bottom:35px; font-size: 24px !important;color: #fff;    width: 100%;
    float: left;}
.login_sucess_text span {color: #189ad4;}
.login_sucess a {
    background: #189ad4;
    border: 0px solid #fff;
    border-radius: 4px;
    color: #fff;
    font-size: 22px;
    letter-spacing: 1px;
    padding: 10px 34px;
    font-weight: bold;
    -webkit-transition: all .5s ease-in-out;
    -moz-transition: all .5s ease-in-out;
    -ms-transition: all .5s ease-in-out;
    -o-transition: all .5s ease-in-out;
    transition: all .5s ease-in-out;
}
.login_sucess a:hover {background:#fff; color:#189ad4; border:2px solid #189ad4;-webkit-transition: all .5s ease-in-out;-moz-transition: all .5s ease-in-out;-ms-transition: all .5s ease-in-out;-o-transition: all .5s ease-in-out;transition: all .5s ease-in-out;}
.login_sucess_ico {margin: 20px 0;width: 100%;
    float: left;
    padding: 0px 0px 0px 0px;}
.backcolor{ background:none;}
.login_sucess_inner {
    margin-top: 10%;
    width: 100%;
    float: left;
    padding: 0px 0px 0px 0px;
}
.login_sucess_confirm {
    background: url(../img/splash-23.png) repeat scroll 0px 0px;
    position: fixed;
    width: 100%;
    bottom: 0px;
    top: 0px;
    height: 100%;
    background-size: cover;
}
.login_sucess {
    width: 100%;
    float: left;
    padding: 0px;
}
.login_sucess .tab-content {
    width: 100%;
    float: left;
    padding: 0px 0px 0px 0px;
}
.tab-pane.active {
    width: 100%;
    float: left;
    padding: 0px 0px 0px;
}
.login_sucess_btn { width: 100%; float:left; padding:0px 0px 0px; text-align: center;}
.login_sucess_btn a { width: auto; margin: 0px auto; display: table;}
.login_sucess_btn a.ios-classs { background:transparent; padding:0px; width: 100px;}
.login_sucess_btn a.ios-classs img { width: 180px;}
@media only screen and (min-width:320px) and (max-width: 520px) { 
.login_sucess_text {
    letter-spacing: 1px;
    margin-bottom: 35px;
    font-size: 16px !important;
    color: #fff;
}
.loginsection footer .copyright {
    text-align: center;
    color: #323232;
    letter-spacing: .5px;
    margin: 10px 0;
    font-size: 13px;
}
.login_sucess_inner {
    margin-top: 13%;
}
}
</style>
<div class="login_sucess_confirm">
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="login_sucess">
                <div id="my-tab-content" class="tab-content">
						<div class="tab-pane active">
                    <div class="login_sucess_inner text-center">
                    <div class="login_sucess_ico"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></div>
                    <div class="login_sucess_text">Your Health Gennie account has been activated successfully.</div>
                   <div class="login_sucess_btn">
                    @if(base64_decode( Request::get('device') ) == 'iOS')
                        <a class="ios-classs" href="https://itunes.apple.com/in/app/health-gennie/id1424931124?mt=8" class="hpdownloadapp" target="_blank"><img src="{{ URL::asset('img/web/appstore_btn.png') }}" /></a>
                    @elseif(base64_decode( Request::get('device') ) == 'Android')
                        <a class="ios-classs" href="https://play.google.com/store/apps/details?id=com.healthgennie" class="hpdownloadapp appstoredown" target="_blank"><img src="{{ URL::asset('img/web/google_btn.png') }}" /></a>
                    @else
                        <a href="{{route('login')}}">Login Here</a>
                    @endif
                    </div>
              </div>
						</div>
					</div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
