<link href="https://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<?php ini_set('memory_limit', '-1');
	$useragent=$_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
			$_COOKIE['in_mobile'] = '1';
		}
		else{
			$_COOKIE['in_mobile'] = '0';
		}
 ?>
<style>
@import url(https://fonts.googleapis.com/css?family=Roboto);
a:hover { transition: all 200ms ease-in-out;}

.page-container { min-width: auto; width: auto; float:left; height: 100%;}
.content { max-width: 800px; min-width: 600px; display: block; padding: 50px; margin: 50px auto; box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);  background-color: #ffffff; overflow: hidden;}
.dashboard-wrapper.dashboard-plan-wrapper .user-detail-sidebar{ display:block;}
.dashboard-wrapper.dashboard-plan-wrapper.sideband-menu-bar .user-detail-sidebar{ display:block;}
.page-container.sidebar-collapsed {transition: all 100ms linear;}
.page-container.sidebar-collapsed-back { transition: all 100ms linear;}
.page-container.sidebar-collapsed .sidebar-menu { width: 65px; transition: all .4s ease 0s;}
.page-container.sidebar-collapsed-back .sidebar-menu { width: 100%; transition: all .4s ease 0s;}
.page-container.sidebar-collapsed .sidebar-icon { transform: rotate(90deg); transition: all 300ms ease-in-out;}
.page-container.sidebar-collapsed-back .sidebar-icon { transform: rotate(0deg); transition: all 300ms ease-in-out;}
.page-container.sidebar-collapsed .logo { padding: 21px; height: 85px; box-sizing: border-box; transition: all 100ms ease-in-out; position:relative;}
.page-container.sidebar-collapsed-back .logo {width: 100%;
    background: #fff;
    padding: 0px 0px 20px 10px !important;
    height: 50px;
    box-sizing: border-box;
	margin-bottom:0px;
    transition: all 100ms ease-in-out;
    position: relative;}
.user-detail-sidebar{ float:left; width:177px; position:absolute;}
.username-side{ float:left; margin:2px 0 0 10px; width:115px;}
.user-detail-sidebar h3{    
	margin: 0px;
    padding: 0px;
    font-size: 14px;
    color: #222;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 600;} 
.user-detail-sidebar span{font-size: 12px; line-height:12px; color:#888;}
.user-detail-sidebar img{ float:left; width:40px; height:40px; border-radius:40px;}
.page-container.sidebar-collapsed #logo {opacity: 0; transition: all 200ms ease-in-out;}
.page-container.sidebar-collapsed-back #logo { opacity: 1; transition: all 200ms ease-in-out; transition-delay: 300ms;}
.page-container.sidebar-collapsed #menu span { opacity: 0; transition: all 50ms linear;}
.page-container.sidebar-collapsed-back #menu span { opacity: 1; transition: all 200ms linear; transition-delay: 300ms;}
.sidebar-collapsed-back .sidebar-menu {float: left; width: 280px; transition: all 0.5s ease; left: 0; bottom: 0; background-color: #fff; color: #aaabae; font-family: "Segoe UI"; box-shadow: 0px 0px 0px; z-index: 999999999; border-right:1px solid #ebebeb}
.sidebar-menu {  float: left; width: 280px;top: 133px;left: 0;	height:100%; background-color: #fff; color: #aaabae;font-family: "Segoe UI";
box-shadow: 0 0 0; z-index: 999999999;}
.dashboard-wrapper.dashboard-plan-wrapper.sticky-section .sidebar-menu { top:0px;}
#menu { list-style: none; margin: 0; padding: 0; margin-bottom: 20px;}
#menu li {position: relative; margin: 0; font-size: 12px;border-bottom: 1px solid #efefef; padding: 0;}
#menu li:hover 

#menu li {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

#menu li .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: #7c92a7;
    color: #fff;
    opacity: 0.9 !important;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 9999999999;
    left: 70px;
    top: 10px;
	font-size:12px;
}
.sideband-menu-bar #menu li .tooltiptext { left:240px; top:10px; position:absolute !important;}

.tooltip-right::after {
	content: "";
    position: absolute;
    top: 50%;
    right: 100%;
    margin-top: -5px;
    border-width: 5px;
    border-style: solid;
    color: #7c92a7;
    border-color: transparent #7c92a7 transparent transparent;
}
#menu li:hover .tooltiptext {
  visibility: visible;
}
 
#menu li ul {opacity: 0; height: 0px;}
#menu li.active a::after{ content:""; position:absolute; right:0px; top:0px; height:100%; width:3px; background:#14bef0;}
#menu li a {
    font-style: normal;
    font-weight: 500;
    position: relative;
    display: block;
    padding: 15px 20px;
    color: #7c92a7;
    font-size: 13px;
    white-space: nowrap;
    z-index: 2;
}

#menu li a:hover {
  color: #ffffff;
  background-color: #14bef0;
  transition: color 250ms ease-in-out, background-color 250ms ease-in-out; text-decoration:none;
}

#menu li.active > a {
  background-color: #f5f5f5;
  color: #14bef0;
}
.dashboard-wrapper.dashboard-plan-wrapper.sticky-section .page-container{ top: 0px; z-index:9999999;}
#menu ul li { background-color: #2b303a; }

#menu ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

#menu li ul {
  position: absolute;
  visibility: hidden;
  right: 100%;
  top: -1px;
  background-color: #2b303a;
  box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5);
  opacity: 0;
  transition: opacity 0.1s linear;
  border-top: 1px solid rgba(69, 74, 84, 0.7);
}

#menu li:hover > ul {
  visibility: visible;
  opacity: 1;
}

#menu li li ul {
  right: 100%;
  visibility: hidden;
  top: -1px;
  opacity: 0;
  transition: opacity 0.1s linear;
}

#menu li li:hover ul {
  visibility: visible;
  opacity: 1;
}

#menu .fa { margin-right: 5px; }

.logo {
  width: 100%;
  padding: 21px;
  box-sizing: border-box;
}

.sidebar-icon {
  position: relative;
  float: right;
  border: 0px; 
  text-align: center;
  z-index:9999999999;
  line-height: 1;
  font-size: 28px;
  padding: 2px;
  border-radius: 3px;
  color: #888;
  background-clip: padding-box;
  text-shadow: 0px 0px 0px;
}

/*#logo
{
    width: 150px;
    height: 64px;
    vertical-align: middle;
    -webkit-filter: drop-shadow(0px 0px 10px rgba(0,0,0,0.5));
}*/


.fa-html5 {color: #fff; margin-left: 50px;}
.sidebar-collapsed #menu li a { font-size:0px;}
.sidebar-collapsed #menu li a i{ font-size:19px;}
.sidebar-menu .logo a {color:#14bef0;     margin-right: 15px;}
.prime-member-gennie{    position: absolute;
    font-size: 10px;
    top: 0px;
	color:#ff6100;
}
#aswift_0_expand{ display:none;}
header.logo subscribed-member{ position:relative;}
header.logo.subscribed-member:after{ position: absolute;
    top: 0px;
    right: 0px;
    content: "";
    width: 32px;
    height: 40px;
    background: url(https://www.healthgennie.com/img/star-premium.png) no-repeat;
    background-size: 100%;}

</style>

<div class="page-container sidebar-collapsed-back">
  <div class="sidebar-menuHight"></div>
  <div class="sidebar-menu"> <!--dashboard-left sidebar-->
    <header class="logo">
    	<?php $user = Auth::user(); ?>
		<?php
		if(!empty($user->image)) {
			$image_url = getEhrUrl()."/public/patients_pics/".$user->image;
			if(does_url_exists($image_url)) {
				$image_url = $image_url;
			}
			else{
				$image_url = null;
			}
		}
		else{
			$image_url = null;
		} ?>
     <a href="javascript:void(0);" class="sidebar-icon"> <span class="fa fa-bars open-side-menu"></span> </a><div class="user-detail-sidebar">
			
			<a href="{{ route('profile',['id'=>base64_encode(Auth::id())]) }}">
        	<img  @if($image_url != null) src="{{$image_url}}" @else src="{{ URL::asset('img/user-icon-sidebar.png') }}" @endif />
            <div class="username-side"><h3>@if(!empty($user->first_name)) {{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}} @else {{@$user->mobile_no}} @endif</h3> <span>@if(!empty($user->first_name)) {{ucfirst($user->mobile_no)}} @endif</span></div>
            
            
			</a>
        </div>
		@if(Auth::id() != null && checkUserSubcriptionStatus(Auth::id()))
		<div class="elite-member">
        	<div class="bg">&nbsp;</div>
            <div class="text">Elite</div>
        </div>
		@endif
		</header>
     
    <!-- <div style="border-top:1px solid #ddd; position:relative; z-index:99;"></div>-->
    <div class="menu">
      <ul id="menu">
		<li class='my-plan @if($controller == "SubscriptionController" && ($action == "drive" || $action == "subscriptionPlans" || $action == "checkOutUserPlan" )  ) active @endif ' ><a href="{{ route('drive') }}"><i class="fa fa-desktop "></i>HG Elite<span class="tooltiptext tooltip-right">HG Elite</span>
        <div class="sidebar_new">NEW</div></a>
        </li>
		<li class='my-plan'><a href="javascript:void();" class="bookFreeAppt" apptDate="{{base64_encode(date('d-m-Y'))}}" apptTime="{{base64_encode(strtotime(date('h:i A')))}}" doc_id='{{base64_encode(getSetting("direct_appt_doc_id")[0])}}'><i class="fa fa-calendar"></i>Book Instant Appointment<span class="tooltiptext tooltip-right">Book Instant Appointment</span></a>
        </li>
		<li class='my-profile @if($controller == "UserController"&&($action == "profile")) active @endif ' ><a href="{{ route('profile',['id'=>base64_encode(Auth::id())]) }}"><i class="fa fa-user"></i>My Profile</a><span class="tooltiptext tooltip-right">My Profile</span></li>

<!-- <li class='change-pass @if($controller == "UserController"&&($action == "changePassword")) active @endif ' ><a href="{{route('changePassword',['id'=>base64_encode(Auth::id())])}}"><i class="fa fa-unlock-alt"></i>Change Password</a><span class="tooltiptext tooltip-right">Change Password</span></li>-->
<li class='my-appointment @if($controller == "AppointmentController"&&($action == "userAppointment" || $action == "showAppointmentTxn" || $action == "appointmentDetails")) active @endif ' ><a href="{{ route('userAppointment') }}"><i class="fa fa-clock-o"></i>My Appointments</a><span class="tooltiptext tooltip-right">My Appointments</span></li>
<!-- <li class='my-appointment @if($controller == "UserController"&&($action == "myPriscription")) active @endif ' ><a href="{{ route('myPriscription') }}"><i class="fa fa-sticky-note-o"></i>My Prescriptions</a><span class="tooltiptext tooltip-right">My Priscriptions</span></li> -->

<li class='hideforPaytm my-orders @if($controller == "LabController"&&($action == "labOrders" || $action ==  "labOrderDetails"))active @endif '><a href="{{route('labOrders')}}"><i class="fa fa-flask"></i>My Lab Orders</a><span class="tooltiptext tooltip-right">My Lab Orders</span></li>

<li class='my-orders @if($controller == "SubscriptionController"&&($action == "mySubscriptions" || $action == "viewSubscription")) active @endif '><a href="{{route('mySubscriptions')}}"><i class="fa fa-credit-card-alt"></i>My Membership</a><span class="tooltiptext tooltip-right">My Membership</span></li>
<li class="articles"><a href="{{route('blogList')}}"><i class="fa fa-sticky-note-o"></i> Articles</a><span class="tooltiptext tooltip-right">Articles</span></li>


      </ul>
    </div>
  </div>
</div>
<script>
$(document).on("click", ".bookFreeAppt", function () {
	date = $(this).attr("apptDate");
	time = $(this).attr("apptTime");
	doc_id = $(this).attr("doc_id");
	var type = btoa('1');
	var url = '{!! url("/doctor/appointment-book?doc='+doc_id+'&date='+date+'&time='+time+'&conType='+type+'&isDirect=1") !!}';
	window.location = url;
});
var toggle = true;
if($.cookie('in_mobile') == '1') {
	$(".sidebar-icon").click(function() {
	  if (!toggle) {
			$(".dashboard-wrapper").addClass("sideband-menu-bar");
			$(".page-container").addClass("sidebar-collapsed-back");
			$(".page-container").removeClass("sidebar-collapsed");
			setTimeout(function() {
			  $("#menu span").css({"position":"relative"});
			}, 400);
			$(".user-detail-sidebar").show();
	  }
	  else {
		  $(".page-container").addClass("sidebar-collapsed");
			$(".page-container").removeClass("sidebar-collapsed-back");
			$(".dashboard-wrapper").removeClass("sideband-menu-bar");
			$("#menu span").css({"position":"absolute"});
			$(".user-detail-sidebar").hide();
	  }
	  toggle = !toggle;
	 });
}
else{
	$(".sidebar-icon").click(function() {
	  if (!toggle) {
			$(".dashboard-wrapper").addClass("sideband-menu-bar");
			$(".page-container").addClass("sidebar-collapsed-back");
			$(".page-container").removeClass("sidebar-collapsed");
			setTimeout(function() {
			  $("#menu span").css({"position":"relative"});
			}, 400);
	  }
	  else {
		  $(".page-container").addClass("sidebar-collapsed");
			$(".page-container").removeClass("sidebar-collapsed-back");
			$(".dashboard-wrapper").removeClass("sideband-menu-bar");
			$("#menu span").css({"position":"absolute"});
	  }
	  toggle = !toggle;
	 });
}
 $(document).ready(function() {
	$(window).scroll(function() {
		if($("body").find(".top-navbaar").hasClass("sticky")) {
			$(".dashboard-wrapper").addClass('sticky-section');
		}
		else{
			$(".dashboard-wrapper").removeClass('sticky-section');
		}
	});
});

</script>
