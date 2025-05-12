
<aside>

<?php $userModules = getAdminUserPermissionModule();?>


    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] End -->

    <!-- [ Layout wrapper ] Start -->
    <div class="layout-wrapper layout-2" >
            <!-- [ Layout sidenav ] Start -->

            <!-- Side-bar -->
            <div  id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark" style = "z-index : 1;">
                <!-- Brand demo (see assets/css/demo/demo.css) -->
                <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                    	<img src="{{ URL::asset('css/assets/dist/img/logo.png') }}" style = "width : 20px; height : 10px;" class="img-circle" alt="User Image">
                    </span>
                    <a href="{{ route('admin.home') }}" class="app-brand-text demo sidenav-text font-weight-normal ml-2">Health Gennie</a>
                 
                </div>
                <div class="sidenav-divider mt-0"></div>

                <!-- Links -->
                <ul class="sidenav-inner py-1">

                    <!-- Dashboards -->

                    @if(in_array(23,$userModules))

                    <li class="sidenav-item open @if ($controller == "HomeController" && ($action == "Home"))  active  @endif">
                        <a href="{{ route('admin.home') }}" class="sidenav-link ">
                            <i class="sidenav-icon feather icon-home"></i>
                            <div>Home</div>
                        </a>  
                    </li>  
                    @endif

                    <li class="sidenav-item @if ($controller == "TicketController") active open @endif">
                        <a id = "support" href="javascript:void(0);" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fas fa-solid fa-ticket"></i>
                            <div>Support Ticket</div>
                        </a>

                        <ul class="sidenav-menu @if ($controller == "TicketController") menu-open  @endif">
                            <li class="sidenav-item" ><a  id = "supportlink"  class="sidenav-link" href="{{ route('view.tickets') }}" @if ($controller == "TicketController") style="color: #ff4a00;" active @endif>Dashboard</a></li>
                        </ul>
                    </li>

                  @if(in_array(10,$userModules))
                        <!-- Appointment -->
                    <li class="sidenav-item  @if ($controller == "AppointmentController" && ($action == "hgAppointments")) active open @endif">
                        <a id = "Appointment" href="javascript:void(0);" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-regular fa-calendar-check"></i>
                            <div>Appointment</div>
                        </a>
                        <ul class="sidenav-menu @if ($controller == "AppointmentController" && ($action == "hgAppointments")) menu-open @endif">
                        
                            <li class="sidenav-item">
                                <a id = "Appointmentlink" href="{{ route('admin.hgAppointments',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($action == "hgAppointments")) style="color: #ff4a00 !important;" @endif class="sidenav-link" >
                                    <div>Appointment List</div>
                                </a>
                            </li>
                       </ul>
                    </li>
                    @endif
                    <!-- Users  -->
                    @if(in_array(4,$userModules))
                    <li class="sidenav-item  @if ($controller == "HomeController" && ($action == "patientList" || $action == "addUser" || $action == "editUser")) active open @elseif ($controller == "SubscriptionController" && $action == "viewSubscription" ||  $action == "newSubscription" || $action == "corporateUsers")) active open  @endif">
                        <a href="javascript:void(0);" id = "user" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-regular fa-user"></i>
                            <div>Users</div>
                        </a>
                        <ul class="sidenav-menu  @if ($controller == "HomeController" && ($action == "patientList" || $action == "notificationMaster" || $action == "addUser" || $action == "editUser")) menu-open @elseif ($controller == "SubscriptionController" && $action == "viewSubscription"  || $action == "newSubscription" )) menu-open @endif">
                            <li class="sidenav-item">
                                <a id = "userlink1"  href="{{ route('admin.patientList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($controller == "HomeController" && ($action == "patientList" || $action == "addUser" || $action == "editUser")) style="color: #ff4a00 " @elseif ($controller == "SubscriptionController" && $action == "viewSubscription"  || $action == "newSubscription" )) style="color: #ff4a00;"   @endif  class="sidenav-link" >
                                    <div>User List</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a id = "userlink2"  href="{{ route('admin.corporateUsers',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($controller == "HomeController" && ($action == "corporateUsers")) style="color: #ff4a00 !important;" @elseif ($controller == "SubscriptionController" && $action == "viewSubscription"  || $action == "newSubscription" )) style="color: #ff4a00;" @endif   class="sidenav-link">
                                    <div>Corporate User List</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

  <!-- corporate user  -->   
                    
                 <!-- Notification -->
                 
                 @if(in_array(25,$userModules))
                    <li class="sidenav-item @if ($controller == "HomeController" && ($action == "notificationMaster")) active open @endif">
                        <a id = "notification" href="javascript:void(0);" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-regular fa-envelope"></i>
                            <div>Notifications</div>
                        </a>
                        <ul class="sidenav-menu @if ($controller == "HomeController" && ($action == "notificationMaster")) menu-open @endif">
                            <li class="sidenav-item">
                                <a  id = "notificationlink"  href="{{ route('admin.notificationMaster') }}" @if ($controller == "HomeController" && ($action == "notificationMaster"))  style="color: #ff4a00;"  @endif    class="sidenav-link">
                                    <div>Notifications</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <!-- Manage Symptoms -->
                    @if(in_array(5,$userModules))
                    <li class="sidenav-item @if ($controller == "SymptomController" && ($action == "SymptomsMaster" || $action == "addSymptoms" || $action == "updateSymptoms" || $action == "editSymptoms" || $action == "deleteSymptoms")) active open @endif">
                        <a href="javascript:void(0);" id = "symptoms" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-disease"></i>
                            
                            <div>Manage Symptoms</div>
                        </a>
                        <ul class="sidenav-menu @if ($controller == "SymptomController" && ($action == "SymptomsMaster" || $action == "addSymptoms" || $action == "updateSymptoms" || $action == "editSymptoms" || $action == "deleteSymptoms")) menu-open @endif">
                            <li class="sidenav-item">
                                <a  id = "symptomslink1"  class="sidenav-link" href="{{ route('symptoms.addSymptoms') }}" @if ($action == "addSymptoms")) style="color: #ff4a00;"  @endif>
                                    <div>Add Symptoms</div>
                                </a>
                            </li>
                            <li class="sidenav-item ">
                                <a  id = "symptomslink2"  href="{{ route('symptoms.SymptomsMaster') }}" @if ($action == "SymptomsMaster")) style="color: #ff4a00;" @endif   class="sidenav-link">
                                    <div>Symptoms List</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                  <!-- Plan Manager -->
                  @if(in_array(15,$userModules))
                   <li class="sidenav-item  @if ($controller == "SubscriptionController" && ($action == "planMaster" || $action == "planMasterAdd" || $action == "editPlans")) active open @endif">
                  <a href="javascript:void(0);" id = "planManager" class="sidenav-link sidenav-toggle">
                    <i class="sidenav-icon fa-regular fa-clipboard"></i>
                    <div>Plan Manager</div></a>
                
                  <ul class="sidenav-menu @if ($controller == "SubscriptionController" && ($action == "planMaster" ||  $action == "planMasterAdd" )) menu-open @endif" >
                       
                            <!-- <li class="sidenav-item">
                                <a href="{{ route('plans.planMasterAdd') }}" @if ($action == "planMasterAdd")) style="color: #ff4a00;"  @endif class="sidenav-link" >
                                    <div>Add Plan</div>
                                </a>
                            </li> -->
                            <li class="sidenav-item">
                                <a id = "planManagerlink" href="{{ route('plans.planMaster') }}"   @if ($action == "planMaster"))  style="color: #ff4a00;"  @endif class="sidenav-link" >
                                    <div>Plan List</div>
                                </a>
                            </li>
                         
                        </ul>
                    </li>
                    @endif
                    
                        <!-- Subscription Manager -->
 
                @if(in_array(16,$userModules))
				<li class="sidenav-item @if ($controller == "SubscriptionController" && ($action == "subscriptionMaster" ||  $action == "editSubscription")) active open @endif">
                    <a href="javascript:void(0);" id ="SubscriptionManager" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon feather icon-lock"></i>
                            <div>Subscription Manager </div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "SubscriptionController" && ($action == "subscriptionMaster")) menu-open @endif">
						<li  class="sidenav-item" ><a  id = "SubscriptionManagerlink" class="sidenav-link" href="{{ route('subscription.subscriptionMaster',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($action == "subscriptionMaster")) style="color: #ff4a00 !important;" @endif>Subscription</a></li>
					</ul>
				</li>
				@endif


               <!-- labs -->
                   
                   	@if(in_array(11,$userModules))
				<li class="sidenav-item @if (($controller == "LabController" || $controller == "LabMasterController" || $controller == "ThyrocarePackageController") && ($action == "labOrders" || $action == "defaultLab" || $action == "labCollection" || $action == "thyrocarePackageMaster" || $action == "addThyrocarePackage" || $action == "editThyrocarePackage" || $action == "updateThyrocarePackage" || $action == "deleteThyrocarePackage" || $action == "labRequests"  || $action == "labPackage" || $action == "getlabcompany" || $action == "getcomapnypin" || $action == "thyrocareLab" || $action == "makeLabOrder")) active open @endif">
                       <a href="javascript:void(0);" id = "lab" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-vial-virus"></i>
                            <div>Labs</div>
                        </a>
					<ul class="sidenav-menu @if (($controller == "LabController" || $controller == "LabMasterController" || $controller == "ThyrocarePackageController") && ($action == "labOrders" || $action == "defaultLab" || $action == "labCollection" || $action == "thyrocarePackageMaster" || $action == "addThyrocarePackage" || $action == "editThyrocarePackage" || $action == "updateThyrocarePackage" || $action == "deleteThyrocarePackage" || $action == "labRequests" || $action == "labPackage" || $action == "getlabcompany" ))  menu-open @endif">
						<li class="sidenav-item" ><a id = "lablink1" class="sidenav-link" href="{{ route('admin.labOrders') }}" @if ($action == "labOrders")) style="color: #ff4a00 !important;" @endif>Lab Orders</a></li>
						
                        <li class="sidenav-item" >
                            <a class="sidenav-link" id = "lablink2"  href="{{ route('admin.labRequests.index') }}" @if ($action == "labRequests" || $action == "makeLabOrder")) style="color: #ff4a00 !important;" @endif>Lab Request</a>
                        </li>

						<li class="sidenav-item" >
                        <a class="sidenav-link" id = "lablink3"  href="{{route('admin.defaultLab.index')}}" @if ($controller =="LabMasterController" &&  ($action == "defaultLab")) style="color: #ff4a00 !important;" @endif>Default Labs</a>
                      </li>
                      
						<li class="sidenav-item" ><a id = "lablink4"  class="sidenav-link" href="{{route('admin.labCollection.index')}}" @if ($controller == "LabMasterController" && ($action == "labCollection")) style="color: #ff4a00 !important;" @endif>Labs Data Collection</a></li>
                        
						<li class="sidenav-item" ><a id = "lablink5"  class="sidenav-link" href="{{ route('admin.thyrocarePackageMaster') }}" @if ($action == "thyrocarePackageMaster")) style="color: #ff4a00 !important;" @endif>Package Group list</a></li>

						<li class="sidenav-item" ><a id = "lablink6"  class="sidenav-link" href="{{ route('admin.labPackage.index') }}" @if ($action == "labPackage")) style="color: #ff4a00 !important;" @endif>Lab Package</a></li>

						<li class="sidenav-item" ><a id = "lablink7"  class="sidenav-link" href="{{ route('lab.company') }}" @if ($action == "getlabcompany")) style="color: #ff4a00 !important;" @endif>Lab Companies</a></li>

						<li class="sidenav-item" ><a id = "lablink8"  class="sidenav-link" href="{{route('lab.company.pin')}}" @if ($action == "getcomapnypin")) style="color: #ff4a00 !important;" @endif> Labs Pin Code</a></li>

						<li class="sidenav-item" ><a id = "lablink9"  class="sidenav-link" href="{{route('admin.thyrocareLab')}}"@if ($action == "thyrocareLab")) style="color: #ff4a00 !important;" @endif > Thyrocare Labs</a></li>
					</ul>
				</li>
				@endif
                       
              <!-- App Blog -->

                 @if(in_array(6,$userModules))
                    <li class="sidenav-item  @if ($controller == "BlogsController" && ($action == "blogMaster" || $action == "addBlog" || $action == "editBlog" || $action == "updateBlog" || $action == "deleteBlog" || $action == "blogComments" )) active open @endif" >
                        <a id = "AppBlog" href="javascript:void(0);" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-brands fa-rocketchat"></i>
                            <!-- <i class="fa-brands fa-rocketchat"></i> -->
                            <div>App Blogs</div>
                        </a>
                        <ul class="sidenav-menu @if ($controller == "BlogsController" && ($action == "blogMaster" || $action == "addBlog" || $action == "editBlog" || $action == "updateBlog" || $action == "deleteBlog")) menu-open @endif">
                       
                            <li class="sidenav-item">
                                <a id = "AppBloglink" href="{{ route('admin.addBlog') }}" @if ($action == "addBlog")) style="color: #ff4a00 !important;" @endif class="sidenav-link" >
                                    <div>Add Blog</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a id = "AppBloglink1" href="{{ route('admin.blogMaster') }}" @if ($action == "blogMaster")) style="color: #ff4a00 !important;"  @endif class="sidenav-link" >
                                    <div>Blog List</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a  id = "AppBloglink2" href="{{ route('admin.blogComments') }}" @if ($action == "blogComments")) style="color: #ff4a00 !important;" style="color: #ff4a00 !important;" @endif class="sidenav-link" >
                                    <div>Blog Comments</div>
                                </a>
                            </li>
                         
                        </ul>
                    </li>
                    @endif


                   <!-- City Locality -->

                   @if(in_array(7,$userModules))
				<li class="sidenav-item @if ($controller == "LocalityController" && ($action == "localityMaster" || $action == "addLocality" || $action == "editLocality" || $action == "updateLocality" || $action == "deleteLocality")) active open @endif @if ($controller == "HomeController" && ($action == "doctorsListForLocality" )) active open @endif">
                <a href="javascript:void(0);" id = "city" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-city"></i>
                         
                            <div>City Locality</div>
                        </a>

					<ul class="sidenav-menu  @if ($controller == "LocalityController" && ($action == "localityMaster" || $action == "addLocality" || $action == "editLocality" || $action == "updateLocality" || $action == "deleteLocality")) menu-open @endif @if ($controller == "HomeController" && ($action == "doctorsListForLocality" )) menu-open @endif">
						<li  class="sidenav-item">
                            <a  id = "cityLink" class="sidenav-link"  href="{{ route('admin.addLocality') }}" @if ($action == "addLocality"))  style="color: #ff4a00 !important;" @endif>
                            <div>Add Locality</div>
                            </a>
                      </li>
						<li  class="sidenav-item" ><a  class="sidenav-link"  id = "cityLink1"   href="{{ route('admin.localityMaster') }}" @if ($action == "localityMaster")) style="color: #ff4a00 !important;"  @endif><div>Locality list</div></a></li>
						<li class="sidenav-item"><a class="sidenav-link" id = "cityLink2"  href="{{ route('admin.doctorsListForLocality') }}" @if ($controller == "HomeController" && ($action == "doctorsListForLocality")) style="color: #ff4a00 !important;" @endif><div>Locality Manage</div></a></li>
					</ul>
				</li>
				@endif

                <!-- Offer Banners -->    

                 @if(in_array(8,$userModules))
				<li class="sidenav-item @if ($controller == "BannersController" && ($action == "offersBannerMaster" || $action == "addOffersBanner" || $action == "editOffersBanner" || $action == "updateOffersBanner" || $action == "deleteOffersBanner")) active open @endif">
                <a id = "Offer" href="javascript:void(0);" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-image"></i>

                            <div>Offer Banners </div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "BannersController" && ($action == "offersBannerMaster" || $action == "addOffersBanner" || $action == "editOffersBanner" || $action == "updateOffersBanner" || $action == "deleteOffersBanner")) menu-open @endif">
						<li class="sidenav-item" ><a id = "Offerlink" class="sidenav-link"  href="{{ route('admin.addOffersBanner') }}" @if ($action == "addOffersBanner"))  style="color: #ff4a00 !important;" @endif>Add Banner</a></li>
						<li class="sidenav-item" ><a id = "Offerlink1"  class="sidenav-link"  href="{{ route('admin.offersBannerMaster') }}" @if ($action == "offersBannerMaster")) style="color:#ff4a00 !important;"  @endif>Banners list</a></li>

					</ul>
				</li>
				@endif


                  <!-- Advertisement Banner  -->
                  @if(in_array(24,$userModules))
                  <li class="sidenav-item @if ($controller == "BannersController" && ($action == "adBannerMaster" || $action == "addAdBanner" || $action == "editAdBanner" || $action == "updateAdBanner" || $action == "deleteAdBanner")) active open @endif">
                <a id = "Advertisment"  href="javascript:void(0);" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-regular fa-image"></i>
                           
                            <div>Advertisement Banners </div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "BannersController" && ($action == "adBannerMaster" || $action == "addAdBanner" || $action == "editAdBanner" || $action == "updateAdBanner" || $action == "deleteAdBanner")) menu-open @endif">
						<li class="sidenav-item" ><a id = "Advertismentlink" class="sidenav-link"  href="{{ route('admin.addAdBanner') }}" @if ($action == "addAdBanner")) style="color: #ff4a00 !important;" @endif >Add Banner</a></li>
						<li class="sidenav-item" ><a id = "Advertismentlink1" class="sidenav-link"  href="{{ route('admin.adBannerMaster') }}" @if ($action == "adBannerMaster")) style="color: #ff4a00 !important;" @endif>Banners list</a></li>
					</ul>
				</li>
                @endif

                 <!-- Coupons managers  -->
                 
                 @if(in_array(13,$userModules))
                  <li class="sidenav-item @if ($controller == "CouponController" && ($action == "couponMaster" || $action == "couponMasterAdd" || $action == "editCoupons" )) active open @endif">
                       <a href="javascript:void(0);" id = "Coupon" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa fa-gift"></i>
                           
                            <div>Coupon Manager </div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "CouponController" && ($action == "couponMaster")) menu-open @endif" >
						<li class="sidenav-item" ><a id = "Couponlink1"  class="sidenav-link" href="{{ route('admin.couponMaster') }}" @if ($action == "couponMaster"))  style="color: #ff4a00 !important;" @endif>Coupon list</a></li>
						<li class="sidenav-item" ><a id = "Couponlink2" class="sidenav-link" href="{{ route('admin.couponMasterAdd') }}" @if ($action == "couponMasterAdd"))  style="color: #ff4a00 !important;" @endif >Add Coupan</a></li>
					</ul>
				</li>
                @endif

            
     
                 <!-- Camp Master -->
                 <!-- @if(in_array(17,$userModules))
				<li class="sidenav-item @if ($controller == "SettingsController" && ($action == "campMaster" )) active open @endif">
				
                    <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-house-flag"></i>
                            
                            <div>Camp Master</div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "SettingsController" && ($action == "campMaster")) menu-open @endif">
						<li class="sidenav-item"  ><a class="sidenav-link"  href="{{ route('admin.campMaster') }}" @if ($controller == "SettingsController" && ($action == "campMaster")) style="color: #ff4a00 !important;" @endif>Camp Master</a></li>
					</ul>
				</li>
				@endif -->

  
                 <!-- organization Master -->
                 @if(in_array(18,$userModules))
				<li class="sidenav-item @if ($controller == "SettingsController" && ($action == "organizationMaster" )) active open @endif">
                    <a href="javascript:void(0);" id = "Organization" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-sitemap"></i>
                            
                            <div>Organization Master</div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "SettingsController" && ($action == "organizationMaster")) menu-open @endif">
						<li class="sidenav-item" ><a id = "Organizationlink" class="sidenav-link" href="{{ route('admin.organizationMaster') }}" @if ($controller == "SettingsController" && ($action == "organizationMaster")) style="color: #ff4a00 !important;" @endif>Organization Master</a></li>
					</ul>
				</li>
				@endif

                <!-- Slide Master -->
                @if(in_array(19,$userModules))
				<li class="sidenav-item @if ($controller == "SettingsController" && ($action == "sliderMaster" )) open active @endif">
                    <a href="javascript:void(0);" id = "slideMaster" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-sliders"></i>
                            <div>Slider Master</div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "SettingsController" && ($action == "sliderMaster")) menu-open @endif">
						<li class="sidenav-item" ><a id = "slideMasterlink"  class="sidenav-link" href="{{ route('admin.sliderMaster') }}" @if ($controller == "SettingsController" && ($action == "sliderMaster")) style="color: #ff4a00 !important;" @endif>Slider Master</a></li>
					</ul>
				</li>
				@endif
                  
            <!-- Reference  code Master -->
             
            @if(in_array(20,$userModules))
				<li class="sidenav-item @if ($controller == "SettingsController" && ($action == "referralMaster" || $action == "addReferral"  )) open active @endif">
                  <a id = "referralCode" href="javascript:void(0);" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-ticket"></i>
                          
                            <div>Referral Code Master</div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "SettingsController" && ($action == "referralMaster")) menu-open @endif">
						<li class="sidenav-item" ><a id = "referralCodelink" class="sidenav-link"  href="{{ route('admin.referralMaster') }}" @if ($controller == "SettingsController" && ($action == "referralMaster" ||  $action == "addReferral"  )) style="color: #ff4a00 !important;" @endif>Referral Code Master</a></li>
					</ul>
				</li>
				@endif


                <!-- AU Marathon  -->
                  
                <!-- @if(in_array(21,$userModules))
				<li class="sidenav-item @if ($controller == "SettingsController" && ($action == "AuMarathonReg" )) open active @endif">
                    <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-regular fa-address-card"></i>
                            
                            <div>AU Marathon Registrations</div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "SettingsController" && ($action == "AuMarathonReg")) menu-open @endif">
						<li  class="sidenav-item"><a  class="sidenav-link" href="{{ route('admin.AuMarathonReg') }}" @if ($controller == "SettingsController" && ($action == "AuMarathonReg")) style="color: #ff4a00 !important;" @endif>AU Marathon Registrations</a></li>
					</ul>
				</li>
				@endif -->


                <!-- Dynamic Pages  -->

                @if(in_array(22,$userModules))
				<li class="sidenav-item @if ($controller == "SettingsController" && ($action == "PagesList" || $action == "editPageContent")) active open @endif">
                    <a href="javascript:void(0);" id = "Dynamicpages" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-regular fa-file"></i>
                          
                            <div>Dynamic Pages</div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "SettingsController" && ($action == "PagesList" || $action == "editPageContent")) menu-open @endif">
						<li class="sidenav-item"><a  id = "Dynamicpageslink"   class="sidenav-link" href="{{ route('admin.PagesList') }}" @if ($controller == "SettingsController" && ($action == "PagesList" || $action == "editPageContent")) style="color: #ff4a00 !important;" @endif>Pages List</a></li>


					</ul>
				</li>
				@endif

                <!-- Speciality Manager -->

                        @if(in_array(14,$userModules))
				<li class="sidenav-item @if ($controller == "SettingsController" && ($action == "specialityAll" || $action == "updateSpeciality" || $action == "specialityGroupMaster" || $action == "addGroupSpeciality" || $action == "addSpeciality" )) open active @endif">
                <a id = "Speciality" href="javascript:void(0);" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-user-nurse"></i>
                            <div>Speciality Manager </div>
                        </a>
                        <ul class="sidenav-menu @if ($controller == "SettingsController" && ($action == "specialityAll" || $action == "addSpeciality" || $action == "specialityGroupMaster" || $action == "addGroupSpeciality")) menu-open @endif">
                            
						<li  class="sidenav-item"><a id = "Specialitylink" class="sidenav-link" href="{{ route('admin.specialityGroupMaster') }}" @if ($action == "specialityGroupMaster")) style="color: #ff4a00 !important;" @endif>Speciality Group list</a></li>

						<li  class="sidenav-item"><a id = "Specialitylink1" class="sidenav-link" href="{{ route('admin.addGroupSpeciality') }}" @if ($action == "addGroupSpeciality")) style="color: #ff4a00 !important;" @endif>Add Group Speciality</a></li>

						<li  class="sidenav-item"><a id = "Specialitylink2" class="sidenav-link" href="{{ route('admin.specialityAll') }}" @if ($action == "specialityAll")) style="color: #ff4a00 !important;" @endif>Speciality list</a></li>

						<li  class="sidenav-item"><a id = "Specialitylink3" class="sidenav-link" href="{{ route('admin.addSpeciality') }}" @if ($action == "addSpeciality")) style="color: #ff4a00 !important;" @endif>Add Speciality</a></li>
					</ul>
				</li>
				@endif
                                  
                    <!-- Doctors -->
                    @if(in_array(2,$userModules))
                    <li class="sidenav-item  @if ($controller == "HomeController" && ($action == "doctorsList" ||  $action == "liveDoctorsList" || $action == "addDoctor" || $action == "nonHgDoctorsList" || $action == "claimDoctorsList" || $action == "nonClaimDoctorsList")) open active @endif"">
                        <a href="" id = "Doctors" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-user-doctor"></i>
                        
                            <div>Doctors</div>
                        </a>
                        <ul class="sidenav-menu @if ($controller == "HomeController" && ($action == "doctorsList" || $action == "liveDoctorsList" || $action == "addDoctor" || $action == "nonHgDoctorsList"  || $action == "claimDoctorsList" || $action == "nonClaimDoctorsList")) menu-open @endif" >
                       
                            <li class="sidenav-item">
                                <a id = "Doctorslink" href="{{ route('admin.liveDoctorsList') }}" @if ($controller == "HomeController" && ($action == "liveDoctorsList")) style="color: #ff4a00 !important;" @endif class="sidenav-link" >
                                    <div>Live Doctor</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a id = "Doctorslink1"  href="{{ route('admin.doctorsList') }}" @if ($controller == "HomeController" && ($action == "doctorsList"))  style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>HG Doctor</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a id = "Doctorslink2" href="{{ route('admin.nonHgDoctorsList') }}" @if ($controller == "HomeController" && ($action == "nonHgDoctorsList")) style="color: #ff4a00 !important;" @endif class="sidenav-link" >
                                    <div>NON HG Doctor</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a id = "Doctorslink3" href="{{ route('admin.claimDoctorsList') }}" @if ($controller == "HomeController" && ($action == "claimDoctorsList"))  style="color: #ff4a00 !important;"  @endif class="sidenav-link">
                                    <div>Claim Doctor</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif


                   <!-- Queries -->
                   @if(in_array(12,$userModules) || in_array(26,$userModules) || in_array(27,$userModules) || in_array(28,$userModules) || in_array(29,$userModules) || in_array(30,$userModules) || in_array(31,$userModules) || in_array(32,$userModules))
                   <li class="sidenav-item @if ($controller == "HomeController" && ($action == "feedbackPatAll" || $action == "enquiryQuery" || $action == "contactQuery" ||    $action == "subcribedAll" || $action == "supportPatAll" || $action == "supportPatAll" || $action == "otpList" || $action == "userOtpList"|| $action == "covidHelpList" || $action == "vaccinationDrive" || $action == "runnersLeads" || $action == "corporateLeads")) open active @endif">
                       
                       <a href="" id = "Queries" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-clipboard-question"></i>
                            <div>Queries</div>
                        </a>
                        <ul class="sidenav-menu @if($controller == "HomeController" && ($action == "feedbackPatAll" || $action == "enquiryQuery" || $action == "contactQuery" || $action == "subcribedAll" || $action == "supportPatAll" || $action == "otpList" || $action == "userOtpList"|| $action == "covidHelpList"  || $action == "vaccinationDrive" || $action == "runnersLeads" || $action == "corporateLeads")) menu-open @endif">


                           @if(in_array(26,$userModules))
                           <li class="sidenav-item">
                                <a id = "Querieslink1"  href="{{ route('admin.feedbackPatAll') }}" @if($action == "feedbackPatAll")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Feedback List</div>
                                </a>
                            </li>
                            @endif
                            @if(in_array(27,$userModules))
                            <li class="sidenav-item">
                                <a id = "Querieslink2" href="{{ route('admin.supportPatAll') }}" @if ($action == "supportPatAll")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Support List</div>
                                </a>
                            </li>
                            @endif
                            @if(in_array(28,$userModules))
                            <li class="sidenav-item">
                                <a id = "Querieslink3" href="{{ route('admin.subcribedAll') }}" @if ($action == "subcribedAll")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Subscribe List</div>
                                </a>
                            </li>
                            @endif
                            @if(in_array(29,$userModules))
                            <li class="sidenav-item">
                                <a  id = "Querieslink4" href="{{ route('admin.contactQuery') }}" @if ($action == "contactQuery")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Contact List</div>
                                </a>
                            </li>
                            @endif
                    
						@if(in_array(30,$userModules))
						<li><a  id = "Querieslink0" href="{{ route('admin.enquiryQuery',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($action == "enquiryQuery")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Enquiry Form List</div>
                                </a>
                            </li>
                            @endif
                            @if(in_array(31,$userModules))
                            <li class="sidenav-item">
                                <a  id = "Querieslink5" href="{{ route('admin.otpList') }}" @if ($action == "otpList")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Doctor OTPs</div>
                                </a>
                            </li>
                            @endif
                            @if(in_array(32,$userModules))
                            <li class="sidenav-item">
                        <a id = "Querieslink6" href="{{ route('admin.userOtpList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" 
						@if($action == "userOtpList")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Users OTPs</div>
                                </a>
                            </li>
                            @endif
                       
                            @if(in_array(43,$userModules))
                            <li class="sidenav-item">
                                <a id = "Querieslink7" href="{{ route('admin.runnersLeads',['from_date'=>base64_encode(date('Y-m-d')),'to_date'=>base64_encode(date('Y-m-d'))]) }}"
					        	@if($action == "runnersLeads")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Runners List</div>
                                </a>
                            </li>
                            @endif
                            @if(in_array(46,$userModules))
                            <li class="sidenav-item">
                                <a id = "Querieslink8" href="{{ route('admin.corporateLeads',['from_date'=>base64_encode(date('Y-m-d')),'to_date'=>base64_encode(date('Y-m-d'))]) }}"
						      @if($action == "corporateLeads")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Corporate Leads</div>
                                </a>
                            </li>
                            @endif

                                 <!-- @if(in_array(36,$userModules))
                            <li class="sidenav-item">
                                <a href="{{ route('admin.covidHelpList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" 
					          	@if($action == "covidHelpList")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Covid Help</div>
                                </a>
                            </li>
                            @endif -->
                            <!-- @if(in_array(40,$userModules))
                            <li class="sidenav-item">
                                <a href="{{ route('admin.vaccinationDrive') }}" @if($action == "vaccinationDrive")) style="color: #ff4a00 !important;" @endif class="sidenav-link">
                                    <div>Vaccination Drive</div>
                                </a>
                            </li>
                            @endif -->

                        </ul> 

                    </li>

                    @endif

                        <!-- Sponsor And Suggest Doctor -->
                        @if(in_array(3,$userModules))
                        <li class="sidenav-item @if ($controller == "HomeController" && ($action == "sponsoredDoctor" || $action == "sponsorDoc")) active open @endif">
                        <a href="" id = "SuggestDoctor" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-user-doctor"></i>
                            <div>Sponsor And Suggest Doctor</div>
                        </a>
                        <ul class="sidenav-menu @if ($controller == "HomeController" && ($action == "sponsoredDoctor" || $action == "sponsorDoc")) menu-open @endif">
                            <li class="sidenav-item">
                                <a id = "SuggestDoctorlink" href="{{ route('admin.sponsoredDoctor') }}" @if ($controller == "HomeController" && ($action == "sponsoredDoctor" || $action == "sponsorDoc")) style="color: #ff4a00 !important;"  @endif class="sidenav-link" >
                                    <div>Sponsor And Suggest Doctor</div>
                                </a>
                            </li>
                        </ul>
                     </li>
                     @endif


                       <!-- Hospital Bed List -->
                    
                       <!-- @if(in_array(37,$userModules))
				<li class="sidenav-item  @if ($controller == "SettingsController" && ($action == "HosBedList" || $action == "editHosBed")) open active @endif">
		
                    <a href="#" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-hospital"></i>
                            <div>Hospital Bed List</div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "SettingsController" && ($action == "HosBedList" || $action == "editHosBed")) menu-open @endif">
						<li  class="sidenav-item" ><a  class="sidenav-link"   href="{{ route('admin.HosBedList') }}" @if ($controller == "SettingsController" && ($action == "HosBedList" || $action == "editHosBed"))  style="color: #ff4a00 !important;" @endif>Hospital Bed List</a></li>
					</ul>
				</li>
				@endif -->

                      <!-- Health Question Master -->
  
                      @if(in_array(39,$userModules))
				    <li class="sidenav-item @if ($controller == "CommonController" && ($action == "hQMaster" )) active open @endif">
					 
                    <a href="" id = "healthQuestion" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-clipboard-question"></i>
                            <div>Health Question Master</div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "CommonController" && ($action == "hQMaster")) menu-open @endif">
						<li class="sidenav-item" ><a id = "healthQuestionlink" class="sidenav-link"  href="{{ route('admin.hQMaster') }}" @if ($controller == "CommonController" && ($action == "hQMaster")) style="color: #ff4a00 !important;" @endif>Health Question Master</a></li>
					</ul>
				</li>
				@endif       
               
                 <!-- Health Medicine Master -->
                 @if(in_array(42,$userModules))
				<li class="sidenav-item @if ($controller == "CommonController" && ($action == "medicineMaster" )) active open @endif">
                    <a href="" id = "healthMedicine" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-syringe"></i>
                            <div>Health Medicine Master</div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "CommonController" && ($action == "medicineMaster")) menu-open @endif">
						<li class="sidenav-item" ><a id = "healthMedicinelink" class="sidenav-link" href="{{ route('admin.medicineMaster') }}" @if ($controller == "CommonController" && ($action == "medicineMaster")) style="color: #ff4a00 !important;" @endif>Medicine Masters</a></li>
					</ul>
				</li>
				@endif

                <!-- Paytm order List -->
                   
                @if(in_array(44,$userModules))
				<li class="sidenav-item @if ($controller == "CommonController" && ($action == "paytmOrders" )) open active @endif">
			
                    <a href="" id = "paytmOrder" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-regular fa-credit-card"></i>
                            <div>Paytm Order List</div>
                        </a>

					<ul class="sidenav-menu @if ($controller == "CommonController" && ($action == "paytmOrders")) menu-open @endif">
						<li  class="sidenav-item" ><a  id = "paytmOrderlink" class="sidenav-link" href="{{ route('admin.paytmOrders',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($controller == "CommonController" && ($action == "paytmOrders")) style="color: #ff4a00 !important;" @endif>Paytm Orders</a></li>
					</ul>
				</li>
				@endif
                
                <!-- Medicine Manage -->

                @if(in_array(45,$userModules))
				<li class="sidenav-item  @if ($controller == "MedicineController" && ($action == "medicineOrder" )) active open @endif">
                    <a href="" id = "medicineManage"  class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-stethoscope"></i>
                            <div>Medicine Manage</div>
                        </a>
					<ul class="sidenav-menu @if ($controller == "MedicineController" && ($action == "medicineOrder")) menu-open @endif">
						<li  class="sidenav-item" ><a id = "medicineManagelink" class="sidenav-link"  href="{{ route('admin.medicineOrder',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}" @if ($controller == "MedicineController" && ($action == "medicineOrder")) style="color: #ff4a00 !important;" @endif>Order</a></li>
					</ul>
				</li>
				@endif

                <!-- User Online Data -->

                @if(in_array(47, $userModules))
                <li class="sidenav-item @if ($controller == "HomeController" && ($action == "userDataList")) open active @endif">
                    <a href="{{ route('admin.userDataList') }}#userOnlineData" id="userOnlineData" class="sidenav-link sidenav-toggle">
                        <i class="sidenav-icon fa-solid fa-database"></i>
                        <div>User Online Data</div>
                    </a>

                    <ul class="sidenav-menu @if ($controller == "HomeController" && ($action == "userDataList"))  menu-open @endif">
                        <li class="sidenav-item">
                            <a id="userOnlineDataLink" class="sidenav-link" href="{{ route('admin.userDataList') }}" @if ($controller == "HomeController" && ($action == "userDataList")) style="color: #ff4a00 !important;" @endif>users</a>
                        </li>
                    </ul>
                </li>
                @endif

          <li class="sidenav-item @if ($controller == "HomeController" && ($action == "HgMap")) active open @endif">
                    <a href="{{ route('admin.HgMap') }}" id = "HgMap" class="sidenav-link sidenav-toggle">
                    <i class="sidenav-icon fa-solid fa-map-location-dot"></i>
                            <div>Health Gennie Map</div>
                        </a>
		     <ul class="sidenav-menu @if($controller == "HomeController" && ($action == "HgMap")) menu-open  @endif">
				<li class="sidenav-item" >
                    <a id="hgMapLink" class="sidenav-link" href="{{ route('admin.HgMap') }}" @if($controller == "HomeController" && ($action == "HgMap")) style="color: #ff4a00 !important;" @endif>Map</a>
                </li>
			</ul>
		</li>

        <!-- instant Subscription -->
        <!-- @if(in_array(55,$userModules))
        <li class="sidenav-item @if ($controller == "SubscriptionController" && ($action == "instantSubs" || $action == "instantSubsReport" || $action == "depositReq")) active open @endif">
                    <a href="" id = "instantSub" class="sidenav-link sidenav-toggle">
                    <i class="sidenav-icon fa-solid fa-map-location-dot"></i>
                            <div>Instant Subscription</div>
                        </a>
		     <ul class="sidenav-menu @if($controller == "SubscriptionController" && ($action == "instantSubs" || $action == "instantSubsReport"|| $action == "depositReq")) menu-open  @endif">
				<li class="sidenav-item" >
                    <a id="instantSub1" class="sidenav-link" href="{{ route('admin.instantSubs') }}" @if($controller == "SubscriptionController" && ($action == "instantSubs")) style="color: #ff4a00;" @endif>Create Instant Subscription</a>
                </li>
                <li class="sidenav-item" >
                    <a id="instantSub2" class="sidenav-link" href="{{ route('admin.instantSubsReport') }}" @if($controller == "SubscriptionController" && ($action == "instantSubsReport")) style="color: #ff4a00;" @endif>Report</a>
                </li>
                <li class="sidenav-item" >
                    <a id="instantSub3" class="sidenav-link" href="{{ route('admin.depositReq') }}" @if($controller == "SubscriptionController" && ($action == "depositReq")) style="color: #ff4a00;" @endif>Credit Report</a>
                </li>
			</ul>
		</li>
        @endif -->


        <!-- Attandence Report -->

            
        @if(in_array(55,$userModules))
        <li class="sidenav-item @if ($controller == "AttendanceController"  && ($action == "attendance" || $action == "attendanceList" || $action == "leaveRequestList" || $action == "attendanceAdminList" || $action == "leaveRequestAdminList"))   active open @endif">
                    <a href="" id = "attandence" class="sidenav-link sidenav-toggle">
                    <i class="sidenav-icon fa-solid fa-clipboard-user"></i>
                            <div>Attandence Management</div>
                        </a>
                        
		     <ul class="sidenav-menu @if ($controller == "AttendanceController" && ($action == "attendance" || $action == "attendanceList")) menu-open @endif">
				
             <li class="sidenav-item" >
                    <a id="attandence1" class="sidenav-link" href="{{ route("admin.attendanceList") }}" @if($controller == "AttendanceController" && ($action == "attendanceList")) style="color: #ff4a00 !important;" @endif>
                    <div>User Attandence Sheet</div>
                </a>
                </li>
             <li class="sidenav-item" >
                    <a id="attandence2" class="sidenav-link" href="{{ route("admin.leaveRequestList") }}" @if($controller == "AttendanceController" && ($action == "leaveRequestList")) style="color: #ff4a00 !important;" @endif>
                    <div>User Leave Request</div>
                </a>
                </li>

                <li class="sidenav-item" >
                    <a id="attandence3" class="sidenav-link" href="{{ route("admin.attendanceAdminList") }}" @if($controller == "AttendanceController" && ($action == "attendanceAdminList")) style="color: #ff4a00 !important;" @endif>
                    <div>Attendance Sheet</div>
                </a>
                </li>
                <li class="sidenav-item" >
                    <a id="attandence4" class="sidenav-link" href="{{ route("admin.leaveRequestAdminList") }}" @if($controller == "AttendanceController" && ($action == "leaveRequestAdminList")) style="color: #ff4a00 !important;" @endif>
                    <div>Leave Request</div>
                </a>
                </li>
			</ul>
		</li>
        @endif

    

        <!-- @if(in_array(55,$userModules))
				<li class="treeview @if ($controller == "AttendanceController" && ($action == "attendance" || $action == "attendanceList" || $action == "leaveRequestList" || $action == "attendanceAdminList" || $action == "leaveRequestAdminList")) active @endif">
					<a href="#">
						<i><img width="18" src="/img/Subscription-Manager.png" /></i><span>Attendance Management</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu @if ($controller == "AttendanceController" && ($action == "attendance" || $action == "attendanceList")) menu-open @endif">

						<li><a href="{{ route("admin.attendanceList") }}" @if ($controller == "AttendanceController" && ($action == "attendanceList")) style="color: #009688;" @endif>User Attendance Sheet</a></li>

						<li><a href="{{ route("admin.leaveRequestList") }}" @if ($controller == "AttendanceController" && ($action == "leaveRequestList")) style="color: #009688;" @endif>User Leave Request</a></li>

						@if(in_array(56,$userModules))
						<li><a href="{{ route("admin.attendanceAdminList") }}" @if ($controller == "AttendanceController" && ($action == "attendanceAdminList")) style="color: #009688;" @endif>Attendance Sheet</a></li>

						<li><a href="{{ route("admin.leaveRequestAdminList") }}" @if ($controller == "AttendanceController" && ($action == "leaveRequestAdminList")) style="color: #009688;" @endif>Leave Request</a></li>

						@endif
					</ul>

				</li>
               @endif -->



                 <!-- Assessment MAster -->

                 <!-- <li class="sidenav-item @if ($controller == "CommonController" && ($action == "assesmentMaster" )) open active @endif">
                    <a href="#" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fas fa-book-reader"></i>
                            <div>Assesment Master</div>
                        </a>
		        <ul class="sidenav-menu @if ($controller == "CommonController" && ($action == "assesmentMaster")) menu-open  @endif">
				<li class="sidenav-item" ><a class="sidenav-link" href="{{ route('admin.assesmentMaster') }}" @if ($controller == "CommonController" && ($action == "assesmentMaster")) style="color: #ff4a00 !important;" @endif>Assesment Master</a>
                </li>
				</ul>
				</li> -->


                <!-- World Mental Health -->
                @if(in_array(54,$userModules))
                <li class="sidenav-item @if ($controller == "CommonController" && ($action == "wmhMaster" )) open active @endif">
                    <a href="#" class="sidenav-link sidenav-toggle">
                    <i class="sidenav-icon fa-solid fa-earth-americas"></i>
                            <div>World Mental Health</div>
                        </a>
		        <ul class="sidenav-menu @if ($controller == "CommonController" && ($action == "wmhMaster")) menu-open  @endif">
				<li class="sidenav-item" ><a class="sidenav-link" href="{{ route('admin.wmhMaster') }}" @if ($controller == "CommonController" && ($action == "wmhMaster")) style="color: #ff4a00 !important;" @endif>World Mental Health Chart</a>
                </li>
				</ul>
				</li>
                @endif


                 <!-- Setting -->

                     @if(in_array(1,$userModules) || in_array(35,$userModules))
                 <li class="sidenav-item @if ($controller == "SettingsController" && ($action == "UserPermission" || $action == "addSubAdmin" || $action == "subadminList" || $action == "servicesMaster")) active open @endif">
                        <a href="" id = "settings" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon fa-solid fa-gear"></i>
                           
                            <div>Settings</div>
                        </a>
                        <ul class="sidenav-menu  @if($controller == "SettingsController" && ($action == "UserPermission" || $action == "addSubAdmin" || $action == "subadminList")) menu-open @endif">
                        @if(in_array(1,$userModules))
                            <li class="sidenav-item">
                                <a id = "settingLink" href="{{ route('admin.UserPermission') }}" @if ($controller == "SettingsController" && ($action == "UserPermission"))   style="color: #ff4a00 !important;" active @endif class="sidenav-link" >
                                    <div>Subadmin User Permission</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a id = "settingLink2" href="{{ route('admin.addSubAdmin') }}" @if ($controller == "SettingsController" && ($action == "addSubAdmin"))  style="color: #ff4a00 !important;" active @endif class="sidenav-link" >
                                    <div>Add Subadmin</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a id = "settingLink3" href="{{ route('admin.subadminList') }}" @if ($controller == "SettingsController" && ($action == "subadminList")) style="color: #ff4a00 !important;" active  @endif class="sidenav-link" >
                                    <div>Subadmin List</div>
                                </a>
                            </li>
                            @endif	
					        @if(in_array(35,$userModules))
                            <li class="sidenav-item"> 
                                <a id = "settingLink4" href="{{ route('admin.servicesMaster') }}" @if ($controller == "SettingsController" && ($action == "servicesMaster")) style="color: #ff4a00 !important;" active @endif class="sidenav-link" >
                                    <div>Service Master</div>
                                </a>
                            </li>
                            @endif	
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
            </div>

        <!-- Core scripts -->
      
	    <script>
         

        //  instant Sub

    //    document.getElementById('instantSub1').addEventListener('click', function (event) {
    //          event.preventDefault();
    //         window.location.href = this.getAttribute('href') + '#instantSub';
    //     });
    //     document.getElementById('instantSub2').addEventListener('click', function (event) {
    //          event.preventDefault();
    //         window.location.href = this.getAttribute('href') + '#instantSub';
    //     });
    //     document.getElementById('instantSub3').addEventListener('click', function (event) {
    //          event.preventDefault();
    //         window.location.href = this.getAttribute('href') + '#instantSub';
    //     });

        // attendance

        document.getElementById('attandence1').addEventListener('click', function (event) {
                     event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#attandence';
                });

                document.getElementById('attandence2').addEventListener('click', function (event) {
                     event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#attandence';
                });

                document.getElementById('attandence3').addEventListener('click', function (event) {
                     event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#attandence';
                });

                document.getElementById('attandence4').addEventListener('click', function (event) {
                     event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#attandence';
                });

        //  support 

        document.getElementById('supportlink').addEventListener('click', function (event) {
                     event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#support';
                });


        //  Appointment 
           
        document.getElementById('Appointmentlink').addEventListener('click', function (event) {
                     event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Appointment';
                });
       
          //  User
         
           document.getElementById('userlink1').addEventListener('click', function (event) {
                     event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#user';
                });
                document.getElementById('userlink2').addEventListener('click', function (event) {
                     event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#user';
                });

                  // Notification

                document.getElementById('notificationlink').addEventListener('click', function (event) {
                     event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#notification';
                });

          // Symptoms
    
             document.getElementById('symptomslink1').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#symptoms';
                });

                document.getElementById('symptomslink2').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#symptoms';
                });


            // Plan manager 
           
            document.getElementById('planManagerlink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#planManager';
                });

           //  Subscription manager
           
           document.getElementById('SubscriptionManagerlink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#SubscriptionManager';
                });

            //  labs

            document.getElementById('lablink1').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#lab';
                });
                document.getElementById('lablink2').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#lab';
                });
                document.getElementById('lablink3').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#lab';
                });
                document.getElementById('lablink4').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#lab';
                });
                document.getElementById('lablink5').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#lab';
                });
                document.getElementById('lablink6').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#lab';
                });
                document.getElementById('lablink7').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#lab';
                });
                document.getElementById('lablink8').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#lab';
                });
                document.getElementById('lablink9').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#lab';
                });

            // App Blog
            document.getElementById('AppBloglink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#AppBlog';
                });

                document.getElementById('AppBloglink1').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#AppBlog';
                });
                document.getElementById('AppBloglink2').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#AppBlog';
                });
            // City Locality 

            document.getElementById('cityLink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#city';
                });
                document.getElementById('cityLink1').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#city';
                });
                document.getElementById('cityLink2').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#city';
                });

            // Offers

            document.getElementById('Offerlink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Offer';
                });
                document.getElementById('Offerlink1').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Offer';
                });

           //  Adv

              document.getElementById('Advertismentlink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Advertisment';
                });
                document.getElementById('Advertismentlink1').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Advertisment';
                });

         // Coupon Master
         
          document.getElementById('Couponlink1').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Coupon';
                });
         
                    
          document.getElementById('Couponlink2').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Coupon';
                });
        // Organization

        
        document.getElementById('Organizationlink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Organization';
                });


       //  Slide Master 

          document.getElementById('slideMasterlink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#slideMaster';
                });
         
        // Referral Code Master

        document.getElementById('referralCodelink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#referralCode';
                });


        //  Dynamic pages

        document.getElementById('Dynamicpageslink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Dynamicpages';
                });

          //  Specility Manager

             document.getElementById('Specialitylink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Speciality';
                });

                document.getElementById('Specialitylink1').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Speciality';
                });
                document.getElementById('Specialitylink2').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Speciality';
                });
                document.getElementById('Specialitylink3').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Speciality';
                });
           // Doctor
           
               document.getElementById('Doctorslink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Doctors';
                });
                document.getElementById('Doctorslink1').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Doctors';
                });
                document.getElementById('Doctorslink2').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Doctors';
                });
                document.getElementById('Doctorslink3').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Doctors';
                });
           
           // Queries
   
                document.getElementById('Querieslink1').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Queries';
                });
                document.getElementById('Querieslink2').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Queries';
                });
                document.getElementById('Querieslink3').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Queries';
                });
                document.getElementById('Querieslink4').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Queries';
                });
                document.getElementById('Querieslink5').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Queries';
                });
                document.getElementById('Querieslink6').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Queries';
                });
                document.getElementById('Querieslink7').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Queries';
                });
                document.getElementById('Querieslink8').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Queries';
                });
                document.getElementById('Querieslink0').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#Queries';
                });

        // Sponsor And Suggest Doctor 

          document.getElementById('SuggestDoctorlink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#SuggestDoctor';
                });
        // health Question Master

          document.getElementById('healthQuestionlink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#healthQuestion';
                });


      // Health Medicine Master 
      
      document.getElementById('healthMedicinelink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#healthMedicine';
                });
      

       // Paytm Order 

       document.getElementById('paytmOrderlink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#paytmOrder';
                });

       //  Medicine Manage

            document.getElementById('medicineManagelink').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.location.href = this.getAttribute('href') + '#medicineManage';
                });

      // User Online Data
         
       document.getElementById('userOnlineDataLink').addEventListener('click', function (event) {
            event.preventDefault();
            window.location.href = this.getAttribute('href') + '#userOnlineData';
        });

       // Map

        document.getElementById('hgMapLink').addEventListener('click', function (event) {
            event.preventDefault();
            window.location.href = this.getAttribute('href') + '#HgMap';
        });


        // Settings

        document.getElementById('settingLink').addEventListener('click', function (event) {
            event.preventDefault();
            window.location.href = this.getAttribute('href') + '#settings';
        });
        document.getElementById('settingLink2').addEventListener('click', function (event) {
            event.preventDefault();
            window.location.href = this.getAttribute('href') + '#settings';
        });
        document.getElementById('settingLink3').addEventListener('click', function (event) {
            event.preventDefault();
            window.location.href = this.getAttribute('href') + '#settings';
        });
        document.getElementById('settingLink4').addEventListener('click', function (event) {
            event.preventDefault();
            window.location.href = this.getAttribute('href') + '#settings';
        });

            $(document).ready(function () {
                // checkCookie();
                $('#exampleModalCenter').modal();
            });

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires=" + d.toGMTString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function getCookie(cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }

            function checkCookie() {
                var ticks = getCookie("modelopen");
                if (ticks != "") {
                    ticks++;
                    setCookie("modelopen", ticks, 1);
                    if (ticks == "2" || ticks == "1" || ticks == "0") {
                        $('#exampleModalCenter').modal();
                    }
                } else {
                    // user = prompt("Please enter your name:", "");
                    $('#exampleModalCenter').modal();
                    ticks = 1;
                    setCookie("modelopen", ticks, 1);
                }
            }


            



        </script>
        <!-- <script src="assets/js/pages/dashboards_index.js"></script> -->

        </aside>


