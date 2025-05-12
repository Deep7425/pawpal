	<aside class="main-sidebar">
			<!-- sidebar -->
			<div class="sidebar">
			<!-- Sidebar user panel -->
			<div class="user-panel">
				<div class="image pull-left">
					<img src="{{ URL::asset('css/assets/dist/img/avatar5.png') }}" class="img-circle" alt="User Image">
				</div>
				<div class="info">
					<h4>Welcome</h4>
					<p>{{@Session::get('userdata')->name}}</p>
				</div>
			</div>
			<?php $userModules = getAdminUserPermissionModule();?>
			<!-- sidebar menu -->
			<ul class="sidebar-menu">
				@if(in_array(23,$userModules))
				<li class="@if ($controller == "HomeController" && ($action == "Home")) active @endif">
					<a href="{{ route('admin.home') }}"><i class="fa fa-hospital-o"></i><span>Dashboard</span>
					</a>
				</li>
				@endif
				@if(in_array(10,$userModules))
				<li class="treeview @if ($controller == "AppointmentController" && ($action == "hgAppointments")) active @endif">
					<a href="#">
						<i class="fa fa-calendar" aria-hidden="true"></i><span>Appointment</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "AppointmentController" && ($action == "hgAppointments")) menu-open @endif">
						<li><a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($action == "hgAppointments")) style="color: #009688;" @endif>Appointment list</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(1,$userModules) || in_array(35,$userModules))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "UserPermission" || $action == "addSubAdmin" || $action == "subadminList" || $action == "servicesMaster")) active @endif">
					<a href="#">
						<i class="fa fa-cog" aria-hidden="true"></i><span>Settings</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "UserPermission" || $action == "addSubAdmin" || $action == "subadminList")) menu-open @endif">
					@if(in_array(1,$userModules))
						<li><a href="{{ route('admin.UserPermission') }}" @if ($controller == "SettingsController" && ($action == "UserPermission")) style="color: #009688;" @endif>Subadmin User Permission</a></li>
					
						<li><a href="{{ route('admin.addSubAdmin') }}" @if ($controller == "SettingsController" && ($action == "addSubAdmin")) style="color: #009688;" @endif>Add Subadmin</a></li>
						<li><a href="{{ route('admin.subadminList') }}" @if ($controller == "SettingsController" && ($action == "subadminList")) style="color: #009688;" @endif>Subadmin List</a></li>
					@endif	
					@if(in_array(35,$userModules))
						<li><a href="{{ route('admin.servicesMaster') }}" @if ($controller == "SettingsController" && ($action == "servicesMaster")) style="color: #009688;" @endif>Services Master</a></li>
					@endif
					</ul>
				</li>
				@endif
				@if(in_array(2,$userModules))
				<li class="treeview @if ($controller == "HomeController" && ($action == "doctorsList" ||  $action == "liveDoctorsList" || $action == "addDoctor" || $action == "nonHgDoctorsList" || $action == "claimDoctorsList" || $action == "nonClaimDoctorsList")) active @endif">
					<a href="#">
						<i class="fa fa-user-md"></i><span>Doctor</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "HomeController" && ($action == "doctorsList" || $action == "liveDoctorsList" || $action == "addDoctor" || $action == "nonHgDoctorsList"  || $action == "claimDoctorsList" || $action == "nonClaimDoctorsList")) menu-open @endif">
						<li><a href="{{ route('admin.liveDoctorsList') }}" @if ($controller == "HomeController" && ($action == "liveDoctorsList")) style="color: #009688;" @endif>LIVE Doctor</a></li>
						<li><a href="{{ route('admin.doctorsList') }}" @if ($controller == "HomeController" && ($action == "doctorsList")) style="color: #009688;" @endif>Hg Doctor</a></li>
						<li><a href="{{ route('admin.nonHgDoctorsList') }}" @if ($controller == "HomeController" && ($action == "nonHgDoctorsList")) style="color: #009688;" @endif>Non Hg Doctor</a></li>
						<li><a href="{{ route('admin.claimDoctorsList') }}" @if ($controller == "HomeController" && ($action == "claimDoctorsList")) style="color: #009688;" @endif>Claim Doctor</a></li>
					</ul>
				</li>
				@endif
				
				@if(in_array(12,$userModules) || in_array(26,$userModules) || in_array(27,$userModules) || in_array(28,$userModules) || in_array(29,$userModules) || in_array(30,$userModules) || in_array(31,$userModules) || in_array(32,$userModules))
				<li class="treeview @if ($controller == "HomeController" && ($action == "feedbackPatAll" || $action == "enquiryQuery" || $action == "contactQuery" || $action == "subcribedAll" || $action == "supportPatAll" || $action == "supportPatAll" || $action == "otpList" || $action == "userOtpList"|| $action == "covidHelpList" || $action == "vaccinationDrive" || $action == "runnersLeads" || $action == "corporateLeads")) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Queries</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if($controller == "HomeController" && ($action == "feedbackPatAll" || $action == "enquiryQuery" || $action == "contactQuery" || $action == "subcribedAll" || $action == "supportPatAll" || $action == "otpList" || $action == "userOtpList"|| $action == "covidHelpList"  || $action == "vaccinationDrive" || $action == "runnersLeads" || $action == "corporateLeads")) menu-open @endif">
						@if(in_array(26,$userModules))
						<li><a href="{{ route('admin.feedbackPatAll') }}" @if ($action == "feedbackPatAll")) style="color: #009688;" @endif>Feedback list</a></li>
						@endif
						@if(in_array(27,$userModules))
						<li><a href="{{ route('admin.supportPatAll') }}" @if ($action == "supportPatAll")) style="color: #009688;" @endif>Support list</a></li>
						@endif
						@if(in_array(28,$userModules))
						<li><a href="{{ route('admin.subcribedAll') }}" @if ($action == "subcribedAll")) style="color: #009688;" @endif>Subscribe list</a></li>
						@endif
						@if(in_array(29,$userModules))
						<li><a href="{{ route('admin.contactQuery') }}" @if ($action == "contactQuery")) style="color: #009688;" @endif>Contact list</a></li>
						@endif
						@if(in_array(30,$userModules))
						<li><a href="{{ route('admin.enquiryQuery',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($action == "enquiryQuery")) style="color: #009688;" @endif>Enquiry Form List</a></li>
						@endif
						@if(in_array(31,$userModules))
						<li><a href="{{ route('admin.otpList') }}" @if ($action == "otpList")) style="color: #009688;" @endif>Doctor OTPs</a></li>
						@endif
						@if(in_array(32,$userModules))
						<li><a href="{{ route('admin.userOtpList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" 
						@if($action == "userOtpList")) style="color: #009688;" @endif>Users OTPs</a></li>
						@endif
						@if(in_array(36,$userModules))
						<li><a href="{{ route('admin.covidHelpList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" 
						@if($action == "covidHelpList")) style="color: #009688;" @endif>Covid Help</a></li>
						@endif
						@if(in_array(40,$userModules))
						<li><a href="{{ route('admin.vaccinationDrive') }}"
						@if($action == "vaccinationDrive")) style="color: #009688;" @endif>Vaccination Drive</a></li>
						@endif
						@if(in_array(43,$userModules))
						<li><a href="{{ route('admin.runnersLeads',['from_date'=>base64_encode(date('Y-m-d')),'to_date'=>base64_encode(date('Y-m-d'))]) }}"
						@if($action == "runnersLeads")) style="color: #009688;" @endif>Runners Lead</a></li>
						@endif
						@if(in_array(46,$userModules))
						<li><a href="{{ route('admin.corporateLeads',['from_date'=>base64_encode(date('Y-m-d')),'to_date'=>base64_encode(date('Y-m-d'))]) }}"
						@if($action == "corporateLeads")) style="color: #009688;" @endif>Corporate Leads</a></li>
						@endif
					</ul>
				</li>
				@endif
				
				@if(in_array(3,$userModules))
				<li class="treeview @if ($controller == "HomeController" && ($action == "sponsoredDoctor" || $action == "sponsorDoc")) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Sponsor-Suggest-Doctors-icon.png" /></i><span>Sponsor & Suggest Doctors</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "HomeController" && ($action == "sponsoredDoctor" || $action == "sponsorDoc")) menu-open @endif">
						<li><a href="{{ route('admin.sponsoredDoctor') }}" @if ($controller == "HomeController" && ($action == "sponsoredDoctor" || $action == "sponsorDoc")) style="color: #009688;" @endif>Sponsor & Suggest Doctors</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(4,$userModules))
				<li class="treeview @if ($controller == "HomeController" && ($action == "patientList" || $action == "addUser" || $action == "editUser")) active @elseif ($controller == "SubscriptionController" && $action == "viewSubscription" || $action == "newSubscription" )) active @endif">
					<a href="#">
						<i class="fa fa-user"></i><span>Users</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "HomeController" && ($action == "patientList" || $action == "notificationMaster" || $action == "addUser" || $action == "editUser")) menu-open @elseif ($controller == "SubscriptionController" && $action == "viewSubscription"  || $action == "newSubscription" )) menu-open @endif" >
						<li><a href="{{ route('admin.patientList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($controller == "HomeController" && ($action == "patientList" || $action == "addUser" || $action == "editUser")) style="color: #009688;" @elseif ($controller == "SubscriptionController" && $action == "viewSubscription"  || $action == "newSubscription" )) style="color: #009688;" @endif >Users list</a></li>
					</ul>
				</li>
				@endif
				
				@if(in_array(33,$userModules))
				<li class="treeview @if ($controller == "HomeController" && ($action == "corporateUsers" || $action == "addPatients")) active @elseif ($controller == "SubscriptionController" && $action == "viewSubscription" || $action == "newSubscription" )) active @endif">
					<a href="#">
						<i class="fa fa-users" aria-hidden="true"></i><span>Corporate Users</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "HomeController" && ($action == "corporateUsers" || $action == "addPatients"|| $action == "notificationMaster")) menu-open @elseif ($controller == "SubscriptionController" && $action == "viewSubscription"  || $action == "newSubscription" )) menu-open @endif" >
						<li><a href="{{ route('admin.corporateUsers',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($controller == "HomeController" && ($action == "corporateUsers")) style="color: #009688;" @elseif ($controller == "SubscriptionController" && $action == "viewSubscription"  || $action == "newSubscription" )) style="color: #009688;" @endif >Users list</a></li>
					</ul>
				</li>
				@endif
				
				@if(in_array(25,$userModules))
				<li class="treeview @if ($controller == "HomeController" && ($action == "notificationMaster")) active @endif">
					<a href="#">
						<i class="fa fa-bell-o" aria-hidden="true"></i><span>Notifications</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "HomeController" && ($action == "notificationMaster")) menu-open @endif" >
						<li><a href="{{ route('admin.notificationMaster') }}" @if ($controller == "HomeController" && ($action == "notificationMaster")) style="color: #009688;" @endif >Notifications</a></li>
					</ul>
				</li>
				@endif
				
				@if(in_array(5,$userModules))
				<li class="treeview @if ($controller == "SymptomController" && ($action == "SymptomsMaster" || $action == "addSymptoms" || $action == "updateSymptoms" || $action == "editSymptoms" || $action == "deleteSymptoms")) active @endif">
					<a href="#">
						<i class="fa fa-sitemap"></i><span>Manage Symptoms</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SymptomController" && ($action == "SymptomsMaster" || $action == "addSymptoms" || $action == "updateSymptoms" || $action == "editSymptoms" || $action == "deleteSymptoms")) menu-open @endif">
						<li><a href="{{ route('symptoms.addSymptoms') }}" @if ($action == "addSymptoms")) style="color: #009688;" @endif>Add Symptoms</a></li>
						<li><a href="{{ route('symptoms.SymptomsMaster') }}" @if ($action == "SymptomsMaster")) style="color: #009688;" @endif>Symptoms list</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(6,$userModules))
				<li class="treeview @if ($controller == "BlogsController" && ($action == "blogMaster" || $action == "addBlog" || $action == "editBlog" || $action == "updateBlog" || $action == "deleteBlog")) active @endif">
					<a href="#">
						<i class="fa fa-rss" aria-hidden="true"></i> <span>App Blogs</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "BlogsController" && ($action == "blogMaster" || $action == "addBlog" || $action == "editBlog" || $action == "updateBlog" || $action == "deleteBlog")) menu-open @endif">
						<li><a href="{{ route('admin.addBlog') }}" @if ($action == "addBlog")) style="color: #009688;" @endif>Add Blog</a></li>
						<li><a href="{{ route('admin.blogMaster') }}" @if ($action == "blogMaster")) style="color: #009688;" @endif>Blog list</a></li>
						<li><a href="{{ route('admin.blogComments') }}" @if ($action == "blogComments")) style="color: #009688;" @endif>Blog Comments</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(7,$userModules))
				<li class="treeview @if ($controller == "LocalityController" && ($action == "localityMaster" || $action == "addLocality" || $action == "editLocality" || $action == "updateLocality" || $action == "deleteLocality")) active @endif @if ($controller == "HomeController" && ($action == "doctorsListForLocality" )) active @endif">
					<a href="#">
						<i class="fa fa-map-marker" aria-hidden="true"></i> <span>City Locality</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "LocalityController" && ($action == "localityMaster" || $action == "addLocality" || $action == "editLocality" || $action == "updateLocality" || $action == "deleteLocality")) menu-open @endif @if ($controller == "HomeController" && ($action == "doctorsListForLocality" )) menu-open @endif">
						<li><a href="{{ route('admin.addLocality') }}" @if ($action == "addLocality")) style="color: #009688;" @endif>Add Locality</a></li>
						<li><a href="{{ route('admin.localityMaster') }}" @if ($action == "localityMaster")) style="color: #009688;" @endif>Locality list</a></li>
						<li><a href="{{ route('admin.doctorsListForLocality') }}" @if ($controller == "HomeController" && ($action == "doctorsListForLocality")) style="color: #009688;" @endif>Locality Manage</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(8,$userModules))
				<li class="treeview @if ($controller == "BannersController" && ($action == "offersBannerMaster" || $action == "addOffersBanner" || $action == "editOffersBanner" || $action == "updateOffersBanner" || $action == "deleteOffersBanner")) active @endif">
					<a href="#">
						<i class="fa fa-file-image-o" aria-hidden="true"></i><span>Offer Banners</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "BannersController" && ($action == "offersBannerMaster" || $action == "addOffersBanner" || $action == "editOffersBanner" || $action == "updateOffersBanner" || $action == "deleteOffersBanner")) menu-open @endif">
						<li><a href="{{ route('admin.addOffersBanner') }}" @if ($action == "addOffersBanner")) style="color: #009688;" @endif>Add Banner</a></li>
						<li><a href="{{ route('admin.offersBannerMaster') }}" @if ($action == "offersBannerMaster")) style="color: #009688;" @endif>Banners list</a></li>

					</ul>
				</li>
				@endif
				
				@if(in_array(24,$userModules))
				<li class="treeview @if ($controller == "BannersController" && ($action == "adBannerMaster" || $action == "addAdBanner" || $action == "editAdBanner" || $action == "updateAdBanner" || $action == "deleteAdBanner")) active @endif">
					<a href="#">
						<i class="fa fa-list-alt"></i> <span>Advertisement  Banners</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "BannersController" && ($action == "adBannerMaster" || $action == "addAdBanner" || $action == "editAdBanner" || $action == "updateAdBanner" || $action == "deleteAdBanner")) menu-open @endif">
						<li><a href="{{ route('admin.addAdBanner') }}" @if ($action == "addAdBanner")) style="color: #009688;" @endif>Add Banner</a></li>
						<li><a href="{{ route('admin.adBannerMaster') }}" @if ($action == "adBannerMaster")) style="color: #009688;" @endif>Banners list</a></li>

					</ul>
				</li>
				@endif
				
				@if(in_array(13,$userModules))
				<li class="treeview @if ($controller == "CouponController" && ($action == "couponMaster" || $action == "couponMasterAdd" )) active @endif">
					<a href="#">
						<i class="fa fa-building-o" aria-hidden="true"></i><span>Coupan Manager</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "CouponController" && ($action == "couponMaster")) menu-open @endif">
						<li><a href="{{ route('admin.couponMaster') }}" @if ($action == "couponMaster")) style="color: #009688;" @endif>Coupan list</a></li>
						<li><a href="{{ route('admin.couponMasterAdd') }}" @if ($action == "couponMasterAdd")) style="color: #009688;" @endif>Add Coupan</a></li>
					</ul>
				</li>
				@endif

				@if(in_array(14,$userModules))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "specialityAll" || $action == "updateSpeciality" || $action == "specialityGroupMaster" || $action == "addGroupSpeciality" || $action == "addSpeciality" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Speciality-Manager-icon.png" /></i><span>Speciality Manager</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "specialityAll" || $action == "addSpeciality" || $action == "specialityGroupMaster" || $action == "addGroupSpeciality")) menu-open @endif">
						<li><a href="{{ route('admin.specialityGroupMaster') }}" @if ($action == "specialityGroupMaster")) style="color: #009688;" @endif>Speciality Group list</a></li>
						<li><a href="{{ route('admin.addGroupSpeciality') }}" @if ($action == "addGroupSpeciality")) style="color: #009688;" @endif>Add Group Speciality</a></li>

						<li><a href="{{ route('admin.specialityAll') }}" @if ($action == "specialityAll")) style="color: #009688;" @endif>Speciality list</a></li>
						<li><a href="{{ route('admin.addSpeciality') }}" @if ($action == "addSpeciality")) style="color: #009688;" @endif>Add Speciality</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(15,$userModules))
				<li class="treeview @if ($controller == "SubscriptionController" && ($action == "planMaster" || $action == "planMasterAdd" || $action == "editPlans")) active @endif">
					<a href="#">
						<i><img width="18" src="/img/plan-icon.png" /></i><span>Plan Manager</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SubscriptionController" && ($action == "planMaster" ||  $action == "planMasterAdd" )) menu-open @endif">
						<li><a href="{{ route('plans.planMasterAdd') }}" @if ($action == "planMasterAdd")) style="color: #009688;" @endif>Add Plan</a></li>
						<li><a href="{{ route('plans.planMaster') }}" @if ($action == "planMaster")) style="color: #009688;" @endif>Plan list</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(16,$userModules))
				<li class="treeview @if ($controller == "SubscriptionController" && ($action == "subscriptionMaster" ||  $action == "editSubscription")) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Subscription-Manager.png" /></i><span>Subscription Manager</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SubscriptionController" && ($action == "subscriptionMaster")) menu-open @endif">
						<li><a href="{{ route('subscription.subscriptionMaster',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($action == "subscriptionMaster")) style="color: #009688;" @endif>Subscription</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(17,$userModules))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "campMaster" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Camp-Master.png" /></i><span>Camp Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "campMaster")) menu-open @endif">
						<li><a href="{{ route('admin.campMaster') }}" @if ($controller == "SettingsController" && ($action == "campMaster")) style="color: #009688;" @endif>Camp Master</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(18,$userModules))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "organizationMaster" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Organization-icon.png" /></i><span>Organization Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "organizationMaster")) menu-open @endif">
						<li><a href="{{ route('admin.organizationMaster') }}" @if ($controller == "SettingsController" && ($action == "organizationMaster")) style="color: #009688;" @endif>Organization Master</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(19,$userModules))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "sliderMaster" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Slider-Master-icon.png" /></i><span>Slider Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "sliderMaster")) menu-open @endif">
						<li><a href="{{ route('admin.sliderMaster') }}" @if ($controller == "SettingsController" && ($action == "sliderMaster")) style="color: #009688;" @endif>Slider Master</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(20,$userModules))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "referralMaster" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Referral-Code-icon.png" /></i><span>Referral Code Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "referralMaster")) menu-open @endif">
						<li><a href="{{ route('admin.referralMaster') }}" @if ($controller == "SettingsController" && ($action == "referralMaster")) style="color: #009688;" @endif>Referral Code Master</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(21,$userModules))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "AuMarathonReg" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/AU-Marathon-Registrations.png" /></i><span>AU Marathon Registrations</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "AuMarathonReg")) menu-open @endif">
						<li><a href="{{ route('admin.AuMarathonReg') }}" @if ($controller == "SettingsController" && ($action == "AuMarathonReg")) style="color: #009688;" @endif>AU Marathon Registrations</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(22,$userModules))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "PagesList" || $action == "editPageContent")) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Dynamic-Pages.png" /></i><span>Dynamic Pages</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "PagesList" || $action == "editPageContent")) menu-open @endif">
						<li><a href="{{ route('admin.PagesList') }}" @if ($controller == "SettingsController" && ($action == "PagesList" || $action == "editPageContent")) style="color: #009688;" @endif>Pages List</a></li>


					</ul>
				</li>
				@endif
				
				@if(in_array(37,$userModules))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "HosBedList" || $action == "editHosBed")) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Hospital-Bed-List.png" /></i><span>Hospital Bed List</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "HosBedList" || $action == "editHosBed")) menu-open @endif">
						<li><a href="{{ route('admin.HosBedList') }}" @if ($controller == "SettingsController" && ($action == "HosBedList" || $action == "editHosBed")) style="color: #009688;" @endif>Hospital Bed List</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(39,$userModules))
				<li class="treeview @if ($controller == "CommonController" && ($action == "hQMaster" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Health-Question-Master.png" /></i><span>Health Question Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "CommonController" && ($action == "hQMaster")) menu-open @endif">
						<li><a href="{{ route('admin.hQMaster') }}" @if ($controller == "CommonController" && ($action == "hQMaster")) style="color: #009688;" @endif>Health Question Master</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(42,$userModules))
				<li class="treeview @if ($controller == "CommonController" && ($action == "medicineMaster" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/HealthMedicineMaster.png" /></i><span>Health Medicine Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "CommonController" && ($action == "medicineMaster")) menu-open @endif">
						<li><a href="{{ route('admin.medicineMaster') }}" @if ($controller == "CommonController" && ($action == "medicineMaster")) style="color: #009688;" @endif>Medicine Masters</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(44,$userModules))
				<li class="treeview @if ($controller == "CommonController" && ($action == "paytmOrders" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/PaytmOrderList.png" /></i><span>Paytm Order List</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "CommonController" && ($action == "paytmOrders")) menu-open @endif">
						<li><a href="{{ route('admin.paytmOrders',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($controller == "CommonController" && ($action == "paytmOrders")) style="color: #009688;" @endif>Paytm Orders</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(45,$userModules))
				<li class="treeview @if ($controller == "MedicineController" && ($action == "medicineOrder" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Medicine-Manage.png" /></i><span>Medicine Manage</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "MedicineController" && ($action == "medicineOrder")) menu-open @endif">
						<li><a href="{{ route('admin.medicineOrder',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($controller == "MedicineController" && ($action == "medicineOrder")) style="color: #009688;" @endif>Order</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(47,$userModules))
				<li class="treeview @if ($controller == "HomeController" && ($action == "userDataList" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Medicine-Manage.png" /></i><span>User Online Data</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "HomeController" && ($action == "userDataList")) menu-open @endif">
						<li><a href="{{route('admin.userDataList')}}" @if ($controller == "HomeController" && ($action == "userDataList")) style="color: #009688;" @endif>users</a></li>
					</ul>
				</li>
				@endif
				@if(in_array(11,$userModules))
				<li class="treeview @if (($controller == "LabController" || $controller == "LabMasterController" || $controller == "ThyrocarePackageController") && ($action == "labOrders" || $action == "defaultLab" || $action == "labCollection" || $action == "thyrocarePackageMaster" || $action == "addThyrocarePackage" || $action == "editThyrocarePackage" || $action == "updateThyrocarePackage" || $action == "deleteThyrocarePackage" || $action == "labRequests"  || $action == "labPackage" || $action == "getlabcompany")) active @endif">
					<a href="#">
						<i class="fa fa-flask" aria-hidden="true"></i><span>Labs</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if (($controller == "LabController" || $controller == "LabMasterController" || $controller == "ThyrocarePackageController") && ($action == "labOrders" || $action == "defaultLab" || $action == "labCollection" || $action == "thyrocarePackageMaster" || $action == "addThyrocarePackage" || $action == "editThyrocarePackage" || $action == "updateThyrocarePackage" || $action == "deleteThyrocarePackage" || $action == "labRequests" || $action == "labPackage" || $action == "getlabcompany")) menu-open @endif">
						<li><a href="{{ route('admin.labOrders') }}" @if ($action == "labOrders")) style="color: #009688;" @endif>Lab Orders</a></li>
						<li><a href="{{ route('admin.labOrders') }}" @if ($action == "labOrders")) style="color: #009688;" @endif>Lab Postpaid Orders</a></li>
						<li><a href="{{ route('admin.labRequests.index') }}" @if ($action == "labRequests")) style="color: #009688;" @endif>Lab Request</a></li>
						<li><a href="{{route('admin.defaultLab.index')}}" @if ($controller == "LabMasterController" && ($action == "defaultLab")) style="color: #009688;" @endif>Default Labs</a></li>
						<li><a href="{{route('admin.labCollection.index')}}" @if ($controller == "LabMasterController" && ($action == "labCollection")) style="color: #009688;" @endif>Labs Data Collection</a></li>
						<li><a href="{{ route('admin.thyrocarePackageMaster') }}" @if ($action == "thyrocarePackageMaster")) style="color: #009688;" @endif>Package Group list</a></li>
						<li><a href="{{ route('admin.labPackage.index') }}" @if ($action == "labPackage")) style="color: #009688;" @endif>Lab Package</a></li>
						<li><a href="{{ route('lab.company') }}" @if ($action == "getlabcompany")) style="color: #009688;" @endif>Lab Companies</a></li>
						<li><a href="{{route('lab.company.pin')}}" @if ($action == "getcomapnypin")) style="color: #009688;" @endif> Labs Pin Code</a></li>
						<li><a href="{{route('admin.thyrocareLab')}}"@if ($action == "thyrocareLab")) style="color: #009688;" @endif > Thyrocare Labs</a></li>
					</ul>
				</li>
				@endif
				<li class="treeview @if ($controller == "CommonController" && ($action == "assesmentMaster" )) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Health-Question-Master.png" /></i><span>Assesment Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "CommonController" && ($action == "assesmentMaster")) menu-open  @endif">
						<li><a href="{{ route('admin.assesmentMaster') }}" @if ($controller == "CommonController" && ($action == "hQMaster")) style="color: #009688;" @endif>Assesment Master</a></li>
					</ul>
				</li>
		</ul>
	</div> <!-- /.sidebar -->
</aside>
