@section('title', 'Lab Order Complete')
<style>
.login_sucess{width: 60%; margin: 0 auto; text-align: center;}
.login_sucess_ico i {border: 3px solid #14bef0; border-radius: 50%; color: #14bef0; display: inline-block; font-size: 96px; height: 120px; padding: 28px;    width: 120px;}
.login_sucess_text {font-size: 31px;letter-spacing: 1px; font-weight:600}
.login_sucess_text span {color: #189ad4;}

.login_sucess a {background:none; border: 1px solid #14bef0;border-radius: 5px;color: #14bef0;font-size: 13px;padding: 8px 28px; font-weight:600;-webkit-transition: all .5s ease-in-out;-moz-transition: all .5s ease-in-out;-ms-transition: all .5s ease-in-out;-o-transition: all .5s ease-in-out;
transition:all .5s ease-in-out; text-decoration:none;}

.login_sucess a:hover {background:#fff; color:#189ad4; border:1px solid #14bef0;-webkit-transition: all .5s ease-in-out;-moz-transition: all .5s ease-in-out;-ms-transition: all .5s ease-in-out;-o-transition: all .5s ease-in-out;transition: all .5s ease-in-out;}

.login_sucess_ico { width: 150px;
    margin: 20px auto;
    border: 2px solid #14BEEF;
    height: 150px;
    border-radius: 150px;}
.backcolor{ background:none;}

.login_sucess_text {font-family: raleway;}
.login_sucess_inner a{font-family: raleway;}
.login_sucess_inner a:hover{background:#14bef0; color:#fff;}
.login_sucess_inner p{ font-size:15px; color:#222; font-family: raleway; font-weight:500; width:100%; float:left; line-height:25px;}
.login_sucess_inner p strong{ font-size:14px; color:#222; font-weight:600; margin:30px 0 0 0;}
.login_sucess_ico img{ margin:18px 0 0 0;}

#my-tab-content .login_sucess_inner h1 {margin: 0px; padding: 0px;font-size: 35px; font-family: 'Karla', sans-serif !important;}
#my-tab-content .login_sucess_inner .regards{ width: 100%;float: left;padding:25px 0; font-family: raleway; font-weight: 600; font-size: 14px;}

.login_sucess_text{margin: 0px; padding: 0px; font-size: 35px;font-family: 'Karla', sans-serif !important; letter-spacing:0px;}
#my-tab-content .login_sucess_inner {background: #efef; padding: 50px 0  100px 0; float: left; margin-top:3%;}


@media only screen and (max-width: 639px) {
#my-tab-content .login_sucess_inner{ margin-top:0px;}	

.login_sucess {  width: 80%; margin: auto; text-align: center;}
.login_sucess_ico {
    width: 150px;
    margin: 20px auto;
    border: 2px solid #14BEEF;
    height: 150px;
    border-radius: 150px;
}
.login_sucess_ico img {
    width: 50%;
}
.login_sucess_text {
font-size: 20px;
    letter-spacing: 0;
    font-weight: 600;
}
.login_sucess_inner p {
    font-size: 13px;
    letter-spacing: -1px;
}

}

@media only screen and (min-width: 640px) and (max-width: 767px) {
	.login_sucess {  width: 80%;
    margin: auto;
    text-align: center;
    display: table;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;}
.login_sucess_ico {
    width: 150px;
    margin: 20px auto;
    border: 2px solid #14BEEF;
    height: 150px;
    border-radius: 150px;
}
.login_sucess_ico img {
    width: 50%;
}
.login_sucess_text {
    font-size: 23px;
    letter-spacing: 1px;
}
.login_sucess_inner p {
    font-size: 14px;}

}

</style>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="login_sucess">
                <div id="my-tab-content" class="tab-content">
						<div class="tab-pane active">
							<div class="login_sucess_inner text-center">
								<div class="login_sucess_ico"><img src="../img/empty-cart-icon.png"></div>
								<div class="login_sucess_text">Your Transaction Is Not Completed.</div>
                                <p>Thank you for your interest. Your order is not completed. <br />If you have any query, please do not hesitate to call us on  <strong style=" font-family:Arial, Helvetica, sans-serif;">{{getSetting("helpline_number")[0]}}</strong></p>
								<div class="regards">
									Thanks,<br />
									Health Gennie Team.
								</div>
								<a href="{{route('home')}}">Return to home page</a>
							</div>
						</div>
					</div>
            </div>
        </div>
    </div>
</div>
 <script src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript">
// $(document).ready(function(){
  // setTimeout(function() {
  // window.location.href = "{!! route('drive') !!}";
  // }, 2000);
// });
</script>
