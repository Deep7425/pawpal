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

			<!-- sidebar menu -->
			<ul class="sidebar-menu">
			@if(checkAdminUserModulePermission(23))
				<li class="@if ($controller == "HomeController" && ($action == "Home")) active @endif">
					<a href="{{ route('admin.home') }}"><i class="fa fa-hospital-o"></i><span>Dashboard</span>
					</a>
				</li>
				@endif
				@if(checkAdminUserModulePermission(10))
				<li class="treeview @if ($controller == "AppointmentController" && ($action == "hgAppointments")) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Appointment</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "AppointmentController" && ($action == "hgAppointments")) menu-open @endif">
						<li><a href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($action == "hgAppointments")) style="color: #009688;" @endif>Appointment list</a></li>
					</ul>
				</li>
				@endif
				@if(checkAdminUserModulePermission(1))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "UserPermission" || $action == "addSubAdmin" || $action == "subadminList" || $action == "servicesMaster")) active @endif">
					<a href="#">
						<i class="fa fa-user-md"></i><span>Settings</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "UserPermission" || $action == "addSubAdmin" || $action == "subadminList")) menu-open @endif">
						<li><a href="{{ route('admin.UserPermission') }}" @if ($controller == "SettingsController" && ($action == "UserPermission")) style="color: #009688;" @endif>Subadmin User Permission</a></li>
						<li><a href="{{ route('admin.addSubAdmin') }}" @if ($controller == "SettingsController" && ($action == "addSubAdmin")) style="color: #009688;" @endif>Add Subadmin</a></li>
						<li><a href="{{ route('admin.subadminList') }}" @if ($controller == "SettingsController" && ($action == "subadminList")) style="color: #009688;" @endif>Subadmin List</a></li>
						<li><a href="{{ route('admin.servicesMaster') }}" @if ($controller == "SettingsController" && ($action == "servicesMaster")) style="color: #009688;" @endif>Services Master</a></li>
					</ul>
				</li>
				@endif
				@if(checkAdminUserModulePermission(2))
				<li class="treeview @if ($controller == "HomeController" && ($action == "doctorsList" || $action == "addDoctor" || $action == "nonHgDoctorsList" || $action == "claimDoctorsList" || $action == "nonClaimDoctorsList")) active @endif">
					<a href="#">
						<i class="fa fa-user-md"></i><span>Doctor</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "HomeController" && ($action == "doctorsList" || $action == "addDoctor" || $action == "nonHgDoctorsList"  || $action == "claimDoctorsList" || $action == "nonClaimDoctorsList")) menu-open @endif">
						<li><a href="{{ route('admin.doctorsList') }}" @if ($controller == "HomeController" && ($action == "doctorsList")) style="color: #009688;" @endif>Hg Doctor</a></li>
						<li><a href="{{ route('admin.nonHgDoctorsList') }}" @if ($controller == "HomeController" && ($action == "nonHgDoctorsList")) style="color: #009688;" @endif>Non Hg Doctor</a></li>
						<li><a href="{{ route('admin.claimDoctorsList') }}" @if ($controller == "HomeController" && ($action == "claimDoctorsList")) style="color: #009688;" @endif>Claim Doctor</a></li>
					</ul>
				</li>
				@endif
				
				@if(checkAdminUserModulePermission(12) || checkAdminUserModulePermission(26) || checkAdminUserModulePermission(27) || checkAdminUserModulePermission(27) || checkAdminUserModulePermission(28) || checkAdminUserModulePermission(29) || checkAdminUserModulePermission(30) || checkAdminUserModulePermission(31) || checkAdminUserModulePermission(32))
				<li class="treeview @if ($controller == "HomeController" && ($action == "feedbackPatAll" || $action == "enquiryQuery" || $action == "contactQuery" || $action == "subcribedAll" || $action == "supportPatAll" || $action == "supportPatAll" || $action == "otpList" || $action == "userOtpList")) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Queries</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if($controller == "HomeController" && ($action == "feedbackPatAll" || $action == "enquiryQuery" || $action == "contactQuery" || $action == "subcribedAll" || $action == "supportPatAll" || $action == "otpList" || $action == "userOtpList")) menu-open @endif">
						@if(checkAdminUserModulePermission(26))
						<li><a href="{{ route('admin.feedbackPatAll') }}" @if ($action == "feedbackPatAll")) style="color: #009688;" @endif>Feedback list</a></li>
						@endif
						@if(checkAdminUserModulePermission(27))
						<li><a href="{{ route('admin.supportPatAll') }}" @if ($action == "supportPatAll")) style="color: #009688;" @endif>Support list</a></li>
						@endif
						@if(checkAdminUserModulePermission(28))
						<li><a href="{{ route('admin.subcribedAll') }}" @if ($action == "subcribedAll")) style="color: #009688;" @endif>Subscribe list</a></li>
						@endif
						@if(checkAdminUserModulePermission(29))
						<li><a href="{{ route('admin.contactQuery') }}" @if ($action == "contactQuery")) style="color: #009688;" @endif>Contact list</a></li>
						@endif
						@if(checkAdminUserModulePermission(30))
						<li><a href="{{ route('admin.enquiryQuery') }}" @if ($action == "enquiryQuery")) style="color: #009688;" @endif>Enquiry Form List</a></li>
						@endif
						@if(checkAdminUserModulePermission(31))
						<li><a href="{{ route('admin.otpList') }}" @if ($action == "otpList")) style="color: #009688;" @endif>Doctor OTPs</a></li>
						@endif
						@if(checkAdminUserModulePermission(32))
						<li><a href="{{ route('admin.userOtpList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" 
						@if($action == "userOtpList")) style="color: #009688;" @endif>Users OTPs</a></li>
						@endif
					</ul>
				</li>
				@endif
				
				@if(checkAdminUserModulePermission(3))
				<li class="treeview @if ($controller == "HomeController" && ($action == "sponsoredDoctor" || $action == "sponsorDoc")) active @endif">
					<a href="#">
						<i class="fa fa-sitemap"></i><span>Sponsor & Suggest Doctors</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "HomeController" && ($action == "sponsoredDoctor" || $action == "sponsorDoc")) menu-open @endif">
						<li><a href="{{ route('admin.sponsoredDoctor') }}" @if ($controller == "HomeController" && ($action == "sponsoredDoctor" || $action == "sponsorDoc")) style="color: #009688;" @endif>Sponsor & Suggest Doctors</a></li>
					</ul>
				</li>
				@endif
				@if(checkAdminUserModulePermission(4))
				<li class="treeview @if ($controller == "HomeController" && ($action == "patientList" || $action == "addPatients")) active @elseif ($controller == "SubscriptionController" && $action == "viewSubscription" || $action == "newSubscription" )) active @endif">
					<a href="#">
						<i class="fa fa-user"></i><span>Users</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "HomeController" && ($action == "patientList" || $action == "addPatients"|| $action == "notificationMaster")) menu-open @elseif ($controller == "SubscriptionController" && $action == "viewSubscription"  || $action == "newSubscription" )) menu-open @endif" >
						<li><a href="{{ route('admin.patientList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($controller == "HomeController" && ($action == "patientList")) style="color: #009688;" @elseif ($controller == "SubscriptionController" && $action == "viewSubscription"  || $action == "newSubscription" )) style="color: #009688;" @endif >Users list</a></li>
					</ul>
				</li>
				@endif
				
				@if(checkAdminUserModulePermission(25))
				<li class="treeview @if ($controller == "HomeController" && ($action == "notificationMaster")) active @endif">
					<a href="#">
						<i class="fa fa-user"></i><span>Notifications</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "HomeController" && ($action == "notificationMaster")) menu-open @endif" >
						<li><a href="{{ route('admin.notificationMaster') }}" @if ($controller == "HomeController" && ($action == "notificationMaster")) style="color: #009688;" @endif >Notifications</a></li>
					</ul>
				</li>
				@endif
				
				@if(checkAdminUserModulePermission(5))
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
				@if(checkAdminUserModulePermission(6))
				<li class="treeview @if ($controller == "BlogsController" && ($action == "blogMaster" || $action == "addBlog" || $action == "editBlog" || $action == "updateBlog" || $action == "deleteBlog")) active @endif">
					<a href="#">
						<i class="fa fa-list-alt"></i> <span>App Blogs</span>
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
				@if(checkAdminUserModulePermission(7))
				<li class="treeview @if ($controller == "LocalityController" && ($action == "localityMaster" || $action == "addLocality" || $action == "editLocality" || $action == "updateLocality" || $action == "deleteLocality")) active @endif @if ($controller == "HomeController" && ($action == "doctorsListForLocality" )) active @endif">
					<a href="#">
						<i class="fa fa-list-alt"></i> <span>City Locality</span>
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
				@if(checkAdminUserModulePermission(8))
				<li class="treeview @if ($controller == "BannersController" && ($action == "offersBannerMaster" || $action == "addOffersBanner" || $action == "editOffersBanner" || $action == "updateOffersBanner" || $action == "deleteOffersBanner")) active @endif">
					<a href="#">
						<i class="fa fa-list-alt"></i> <span>Offer Banners</span>
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
				
				@if(checkAdminUserModulePermission(24))
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

				@if(checkAdminUserModulePermission(9))
				<li class="treeview @if ($controller == "ThyrocarePackageController" && ($action == "thyrocarePackageMaster" || $action == "addThyrocarePackage" || $action == "editThyrocarePackage" || $action == "updateThyrocarePackage" || $action == "deleteThyrocarePackage")) active @endif">
					<a href="#">
						<i class="fa fa-list-alt"></i> <span>Thyrocare Package Group</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "ThyrocarePackageController" && ($action == "thyrocarePackageMaster" || $action == "addThyrocarePackage" || $action == "editThyrocarePackage" || $action == "updateThyrocarePackage" || $action == "deleteThyrocarePackage")) menu-open @endif">
						<li><a href="{{ route('admin.addThyrocarePackage') }}" @if ($action == "addThyrocarePackage")) style="color: #009688;" @endif>Add Package Group</a></li>
						<li><a href="{{ route('admin.thyrocarePackageMaster') }}" @if ($action == "thyrocarePackageMaster")) style="color: #009688;" @endif>Package Group list</a></li>

					</ul>
				</li>
				@endif


				@if(checkAdminUserModulePermission(11))
				<li class="treeview @if ($controller == "LabController" && ($action == "labOrders")) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Lab</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "LabController" && ($action == "labOrders")) menu-open @endif">
						<li><a href="{{ route('admin.labOrders') }}" @if ($action == "labOrders")) style="color: #009688;" @endif>Lab Orders</a></li>
					</ul>
				</li>
				@endif

				@if(checkAdminUserModulePermission(13))
				<li class="treeview @if ($controller == "CouponController" && ($action == "couponMaster" || $action == "couponMasterAdd" )) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Coupan Manager</span>
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

				@if(checkAdminUserModulePermission(14))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "specialityAll" || $action == "updateSpeciality" || $action == "specialityGroupMaster" || $action == "addGroupSpeciality" || $action == "addSpeciality" )) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Speciality Manager</span>
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
				@if(checkAdminUserModulePermission(15))
				<li class="treeview @if ($controller == "SubscriptionController" && ($action == "planMaster" || $action == "planMasterAdd" || $action == "editPlans")) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Plan Manager</span>
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
				@if(checkAdminUserModulePermission(16))
				<li class="treeview @if ($controller == "SubscriptionController" && ($action == "subscriptionMaster" || $action == "newSubscription" || $action == "editSubscription")) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Subscription Manager</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SubscriptionController" && ($action == "subscriptionMaster" ||  $action == "newSubscription" )) menu-open @endif">
						<li><a href="{{ route('subscription.subscriptionMaster') }}" @if ($action == "subscriptionMaster")) style="color: #009688;" @endif>Subscription</a></li>
					</ul>
				</li>
				@endif
				@if(checkAdminUserModulePermission(17))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "campMaster" )) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Camp Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "campMaster")) menu-open @endif">
						<li><a href="{{ route('admin.campMaster') }}" @if ($controller == "SettingsController" && ($action == "campMaster")) style="color: #009688;" @endif>Camp Master</a></li>
					</ul>
				</li>
				@endif
				@if(checkAdminUserModulePermission(18))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "organizationMaster" )) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Organization Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "organizationMaster")) menu-open @endif">
						<li><a href="{{ route('admin.organizationMaster') }}" @if ($controller == "SettingsController" && ($action == "organizationMaster")) style="color: #009688;" @endif>Organization Master</a></li>
					</ul>
				</li>
				@endif
				@if(checkAdminUserModulePermission(19))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "sliderMaster" )) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Slider Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "sliderMaster")) menu-open @endif">
						<li><a href="{{ route('admin.sliderMaster') }}" @if ($controller == "SettingsController" && ($action == "sliderMaster")) style="color: #009688;" @endif>Slider Master</a></li>
					</ul>
				</li>
				@endif
				@if(checkAdminUserModulePermission(20))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "referralMaster" )) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Referral Code Master</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "referralMaster")) menu-open @endif">
						<li><a href="{{ route('admin.referralMaster') }}" @if ($controller == "SettingsController" && ($action == "referralMaster")) style="color: #009688;" @endif>Referral Code Master</a></li>
					</ul>
				</li>
				@endif
				@if(checkAdminUserModulePermission(21))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "AuMarathonReg" )) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>AU Marathon Registrations</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "AuMarathonReg")) menu-open @endif">
						<li><a href="{{ route('admin.AuMarathonReg') }}" @if ($controller == "SettingsController" && ($action == "AuMarathonReg")) style="color: #009688;" @endif>AU Marathon Registrations</a></li>
					</ul>
				</li>
				@endif
				@if(checkAdminUserModulePermission(22))
				<li class="treeview @if ($controller == "SettingsController" && ($action == "PagesList" || $action == "editPageContent")) active @endif">
					<a href="#">
						<i class="fa fa-check-square-o"></i><span>Dynamic Pages</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "SettingsController" && ($action == "PagesList" || $action == "editPageContent")) menu-open @endif">
						<li><a href="{{ route('admin.PagesList') }}" @if ($controller == "SettingsController" && ($action == "PagesList" || $action == "editPageContent")) style="color: #009688;" @endif>Pages List</a></li>


					</ul>
				</li>
				@endif
		</ul>
	</div> <!-- /.sidebar -->
</aside>
