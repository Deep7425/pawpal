@extends('layouts.admin.Masters.Master')
@section('title', 'Users List')
@section('content')


<body>

    <!-- [ Layout wrapper ] Start -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
        
          
            <div class="layout-container">
           
   
                <div class="layout-content">
                
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Users</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#!">Admin</a></li>
                                <li class="breadcrumb-item active"><a href="#!">User</a></li>
                            </ol>
                        </div>
						<section class="content-header">
                            <div class="header-icon">
                                <i class="pe-7s-box1"></i>
                            </div>
                            <div class="header-title">
                         
                                <h1>Users</h1>
                                <small>Users list</small>
                                <ol class="breadcrumb hidden-xs">
                                    <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                                    <li class="active">Users</li>
                                </ol>
                            </div>
                        </section>

									<div class="layout-content">
										<div class="card mb-4">
											<!-- header start -->
											<div class="row" >
												{{app('request')->input('facility')}}
												<div class="col-sm-1 mt-2 ml-2">
													<a class="btn btn-success" href="javascript:void();">{{$patients->total()}}</a>
												</div>
												<div class="col-sm-5 mt-2 ml-2">
													<a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
												 <form method="post" class="UserDataExcelImports" enctype="multipart/form-data" action="{{ route('admin.userExcelImport') }}">
													 {{ csrf_field() }}
													 <div class="form-group">
														<table class="table">
															<tr>
																<td style="width: 100px;">
																<input type="file" class="file-input" name="select_file" required>
																</td>
																<td>
																<input type="submit" name="upload"  class="btn btn-primary" value="Upload"/>
																</td>
																<td>
																<a href="{{asset('public/users-sample.xls')}}" class="btn btn-success btn-icon-split" download>
																<span class="icon text-white-50">
																<i class="fa fa-download"></i>
																</span>
																<span class="text">Sample Download</span>
																</a>
																</td>
															</tr>
														</table>
													 </div>
												 </form>
												</div>
												<!-- Add User -->
												<div class="col-sm-2 mt-2">
                                                 <a class="btn btn-primary" href="{{route('admin.addUser')}}"><i class="fa fa-user"></i> Add User</a>
                                                </div>
                                                <!-- Pagination --> 
											<div class="TOPMENU col-sm-3  mt-2 ml-5 d-flex justify-content-end" id="example_length ">
											{!! Form::open(array('route' => 'admin.patientList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
											<input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>
											<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
												<!-- <option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option> -->
												<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
												<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
												<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												<option value="500" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '500') selected @endif @endif>500</option>
												<option value="1000" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '1000') selected @endif @endif>1000</option>
												<option value="2000" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '2000') selected @endif @endif>2000</option>
											</select>
										    </div>
											</div>
                                          


                                     <!-- Search section -->
									 <div class="row mt-2 ml-2 mr-2">
                     
                            <div class="col-sm-3">
                           <div class="dataTables_length">
                              <label>State</label>
                              <select class="form-control state_id searchDropDown" name="state_id">
                                 <option value="">Select State</option>
                                 @foreach (getStateList(101) as $state)
                                 <option value="{{ $state->id }}" @if(isset($_GET['state_id'])) @if(base64_decode($_GET['state_id']) == $state->id) selected @endif @endif>{{ $state->name }}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-3">
                           <div class="dataTables_length">
                              <label>City</label>
                              <select class="form-control city_id searchDropDown" name="city_id">
                                 <option value="">Select City</option>
                                 @if(!empty(old('state_id')))
                                 @foreach (getCityList(old('state_id')) as $city)
                                 <option value="{{ $city->id }}" @if(isset($_GET['city_id'])) @if(base64_decode($_GET['city_id']) == $city->id) selected @endif @endif >{{ $city->name }}</option>
                                 @endforeach
                                 @endif
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-2">
                           <div class="dataTables_length">
                              <label>From</label>
                              <div class="input-group date">
                                 <input type="text" autocomplete="off" class="form-control fromStartDate" name="start_date" value="@if((app('request')->input('start_date'))!=''){{ base64_decode(app('request')->input('start_date')) }}@endif"/>
                                 <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-2">
                           <div class="dataTables_length">
                              <label>To</label>
                              <div class="input-group date">
                                 <input type="text" autocomplete="off" class="form-control toStartDate" name="end_date" value="@if((app('request')->input('end_date'))!=''){{ base64_decode(app('request')->input('end_date')) }}@endif"/>
                                 <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-2">
                           <div class="dataTables_length">
                              <label>Organization</label>
                              <select class="form-control" name="organization_type">
                                 <option value="">Select</option>
                                 @if(count($OrganizationList))
                                 <option value="blank" @if((app('request')->input('organization_type'))!='') @if(base64_decode(app('request')->input('organization_type')) == 'blank') selected @endif @endif>Without Organization</option>
                                 @foreach($OrganizationList as $raw)
                                 <option value="{{$raw->id}}" @if(isset($_GET['organization_type'])) @if(base64_decode($_GET['organization_type']) == $raw->id) selected @endif @endif>{{$raw->title}}</option>
                                 @endforeach
                                 @endif
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-3">
                           <div class="dataTables_length">
                              <label>Type</label>
                              <select class="form-control userTypeFilter" name="user_type">
                                 <option value="">User Type</option>
                                 <option value="2" @if(isset($_GET['user_type'])) @if(base64_decode($_GET['user_type']) == 2) selected @endif @endif>Registered (HealthGennie)</option>
                                 <option value="3" @if(isset($_GET['user_type'])) @if(base64_decode($_GET['user_type']) == 3) selected @endif @endif>Registered (Doctor Portal)</option>
                                 <!--<option value="4" @if(old('user_type') == 4) selected @endif>Registered (Appointment Not Done)</option>-->
                                 <option value="5" @if(isset($_GET['user_type'])) @if(base64_decode($_GET['user_type']) == 5) selected @endif @endif>Registered (HealthGennie) (Appointment Done)</option>
                                 <option value="1" @if(isset($_GET['user_type'])) @if(base64_decode($_GET['user_type']) == 1) selected @endif @endif>Subscribed</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-3 registeredFromDiv" @if(isset($_GET['user_type']) && base64_decode($_GET['user_type']) == "2") style="display:block;" @else style="display:none;" @endif>
                        <div class="dataTables_length">
                           <label>Registered From</label>
                           <select class="form-control registeredFromSelect" name="reg_type">
                              <option value="">Select..</option>
                              <option value="1" @if(isset($_GET['reg_type'])) @if(base64_decode($_GET['reg_type']) == 1) selected @endif @endif>Android</option>
                              <option value="2" @if(isset($_GET['reg_type'])) @if(base64_decode($_GET['reg_type']) == 2) selected @endif @endif>IOS</option>
                              <option value="3" @if(isset($_GET['reg_type'])) @if(base64_decode($_GET['reg_type']) == 3) selected @endif @endif>Web</option>
                              <option value="4" @if(isset($_GET['reg_type'])) @if(base64_decode($_GET['reg_type']) == 4) selected @endif @endif>Paytm</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-3">
                        <div class="dataTables_length">
                           <label>Leads Type</label>
                           <select class="form-control" name="lead_type">
                              <option value="">Type</option>
                              <option value="6" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 6) selected @endif @endif>Pending</option>
                              <option value="2" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 2) selected @endif @endif>Call Established</option>
                              <option value="3" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 3) selected @endif @endif>Not Reachable/Switched Off</option>
                              <option value="4" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 4) selected @endif @endif>Follow Up</option>
                              <option value="5" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 5) selected @endif @endif>Lost</option>
                              <option value="1" @if(isset($_GET['lead_type'])) @if(base64_decode($_GET['lead_type']) == 1) selected @endif @endif>Won</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="dataTables_length">
                           <label>Name</label>
                           <div class="input-group custom-search-form">
                              <input name="search" type="text" class="form-control capitalizee" placeholder="search by name and mobile" value="@if(isset($_GET['search'])){{ base64_decode($_GET['search']) }}@endif"/>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-2">
                        <div class="dataTables_length">
                           <label>Filter</label>
                           <div class="input-group custom-search-form">
                              <span class="input-group-btn">
                              <button class="btn btn-primary" type="submit">
                              SEARCH
                              </button>
                              </span>
                           </div>
                           <!-- /input-group -->
                           {!! Form::close() !!}
                        </div>
                     </div>
                     <div class="col-sm-12">
                        <div class="dataTables_length">
                           <label>Send Campaign</label>
                           <a href="javascript:void(0);" class="btn btn-info btn-sm sendMsgModal" ><i class="fa fa-paper-plane" aria-hidden="true">Campaign</i></a>
                        </div>
                     </div>
                 

										</div>
									</div>
                               


                        <div class="row">
                            <!-- subscribe start -->
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>User List </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center m-l-0">
                                            <div class="col-sm-6">
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                <button class="btn btn-success btn-sm mb-3 btn-round" data-toggle="modal" data-target="#modal-report"><i class="feather icon-plus"></i> Add Student</button>
                                            </div>
                                        </div>
                                        
                                        <div class="table-responsive">
                                            <table id="report-table" class="table table-bordered table-striped mb-0">
                                                <thead>
                                                    <tr>
													<th><input type="checkbox" class="selecAll" /></th>
													<th>S.No.</th>
													<th class="tab-appointment" title="Appointment">Appt</th>
													<th>Organization</th>
													<th>Reg. Type</th>
													<th>Name / Gender(PID)</th>
													<th>Mobile No</th>
													<th>Other Mobile</th>
													<th>Address</th>
													<th>city</th>
													<th>state</th>
													<th>Location</th>
													<th>Manage Leads</th>
													<th style="width:80px;">Date</th>
													<th style="width:40px; text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                             
												<tbody>
									 @if($patients->count() > 0)
										@foreach($patients as $index => $pat)
                                        <tr class="tbrow">
											<th><input type="checkbox" value="{{$pat->id}}" class="sub_chk" /></th>
                                            <td>
                                               <label>{{$index+($patients->currentpage()-1)*$patients->perpage()+1}}.</label>
                                            </td>
											<td class="tab-appointment12">@if(!empty($pat->pId)){{@$pat->tot_appointment}} @else 0 @endif</td>
											<td>{{@$pat->OrganizationMaster->title}}</td>
                                            <td>
                                            	@if($pat->device_type == '1') Android
                                            	@elseif($pat->device_type == '2') IOS
                                                @else  @if($pat->login_type == '3') Paytm @else Web @endif  @endif
                                            </td>
											<td>{{$pat->first_name}} {{$pat->last_name}} @if(!empty($pat->gender))/ {{$pat->gender}}@endif @if(!empty($pat->pId))({{$pat->pId}})@endif</td>
											<td>{{$pat->mobile_no}}</td>
											<td>{{@$pat->other_mobile_no}}</td>
											<td>{{$pat->address}}</td>
											<td>{{@$pat->getCityName->name}}</td>
											<td>{{@$pat->State->name}}</td>
											<td>
												@if(!empty($pat->location_meta))
													@php $location_meta = json_decode($pat->location_meta); @endphp	@if(!empty($location_meta->locality)){{@$location_meta->locality}}, @endif @if(!empty($location_meta->subAdministrativeArea)){{@$location_meta->subAdministrativeArea}}, @endif @if(!empty($location_meta->administrativeArea)){{@$location_meta->administrativeArea}}@endif
												@endif
											</td>
											<td><a href="javascript:void(0);" pkey="{{base64_encode(@$pat->id)}}" r_from="2" class="btn btn-info btn-sm manageSprt" title="Manage Leads"><img src='{{ url("/img/customer-care-icon.png") }}'/>Manage Leads</a></td>
											<td><div class="viewSubscription12">{{date('d-m-Y g:i A',strtotime($pat->created_at))}}</div></td>
											<td>
											<div class="viewSubscription123">
												<ul>
													<li>
														<a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
															<ul>
																@if(!empty($pat->pId))
																<li><a href="{{ route('admin.hgAppointments',['id'=>base64_encode($pat->pId)]) }}" title="view Subscription"><i class="fa fa-phone-square" aria-hidden="true"></i> Go To Appointments</a></li>
																@endif
																<li><a href="{{ route('subscription.viewSubscription',['id'=>base64_encode($pat->id)]) }}" title="view Subscription"><span class="fa fa-eye"></span> view Subscription</a></li>
																@if(!empty($pat->pId))<li><button title="View All child users of this patient" class="showChildUser" pid="{{base64_encode($pat->pId)}}"><i class="fa fa-eye" aria-hidden="true"></i>Show Child Users</button></li>@endif
																<li><a href="{{route('admin.editUser', base64_encode($pat->id))}}" title="edit user"><span class="fa fa-user"></span>  Edit User</a></li>
																<li><a href="javascript:void(0);" id="makeMedOrder" ppid="{{$pat->id}}" pId="{{base64_encode($pat->id)}}" title="Make Medicine Order"><img width="18" src="/img/Medicine-Manage-1.png"> Make Medicine Order</a></li>
																<li><a href="javascript:void(0);" id="addAppt" pId="{{base64_encode($pat->id)}}" title="Create Payment Link For Appointment"><i class="fa fa-calendar-check-o"></i>Paid Appointment</a></li>
																<li><button id="addFreeAppt" pId="{{base64_encode($pat->id)}}" title="Create an Free Appointment"><i class="fa fa-calendar-check-o"></i>Free Appointment</button></li>
																<li><button title="Show All Payment Links" class="showLinks" userId="{{base64_encode($pat->id)}}"><i class="fa fa-link" aria-hidden="true"></i> Payment Links</button></li>
																<li><button title="Create Lab Order" class="btn btn-default createNewOrder" type="button" pname="{{base64_encode($pat->first_name." ".$pat->last_name)}}" address="{{base64_encode(@$pat->address)}}" age="{{base64_encode(@$pat->age)}}" gender="{{base64_encode(@$pat->gender)}}" mobile_no="{{base64_encode(@$pat->mobile_no)}}"  email="{{base64_encode(@$pat->email)}}" id="{{base64_encode(@$pat->id)}}"><i class="fa fa-flask" aria-hidden="true"></i>Create Lab Order</button></li>
																<!--<li><a href="{{ route('admin.newLabOrderFromAdmin',['id'=>base64_encode($pat->id)]) }}" title="view Subscription"><span class="fa fa-flask"></span>Create Default Lab order</a></li>-->
															</ul>
													</li>
												</ul>
											</div>
											</td>
										</tr>
									 @endforeach
									 @else
										<tr><td colspan="19">No Record Found </td></tr>
									 @endif
                    				 </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- subscribe end -->
                        </div>
                    </div>
           
                   
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <!-- [ Layout wrapper] End -->
    <div class="modal fade" id="modal-report" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-12">
                                <h5>Personal Information</h5>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="floating-label" for="Name">Name</label>
                                    <input type="text" class="form-control" id="Name" placeholder="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group fill">
                                    <label class="floating-label" for="Email">Email</label>
                                    <input type="email" class="form-control" id="Email" placeholder="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group fill">
                                    <label class="floating-label" for="Password">Password</label>
                                    <input type="password" class="form-control" id="Password" placeholder="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="floating-label" for="Rollno">Roll number</label>
                                    <input type="text" class="form-control" id="Rollno" placeholder="">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="floating-label" for="Address">Address</label>
                                    <textarea class="form-control" id="Address" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <h5>Other Information</h5>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="floating-label" for="Sex">Select Sex</label>
                                    <select class="form-control" id="Sex">
                                        <option value=""></option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group fill">
                                    <label class="floating-label" for="Icon">Profie Image</label>
                                    <input type="file" class="form-control" id="Icon" placeholder="sdf">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group fill">
                                    <label class="floating-label" for="Birth">Birth Date</label>
                                    <input type="date" class="form-control" id="Birth" placeholder="123">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="floating-label" for="Age">Age</label>
                                    <input type="text" class="form-control" id="Age" placeholder="">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="floating-label" for="Blood">Select Blood Group</label>
                                    <select class="form-control" id="Blood" placeholder="">
                                        <option value=""></option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button class="btn btn-primary">Submit</button>
                                <button class="btn btn-danger">Clear</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Core scripts -->
    <script src="assets/js/pace.js"></script>
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/libs/popper/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/sidenav.js"></script>
    <script src="assets/js/layout-helpers.js"></script>
    <script src="assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/libs/datatables/datatables.js"></script>
    <!-- Demo -->
    <script src="assets/js/demo.js"></script>
    <script>
        // DataTable start
        $('#report-table').DataTable();
        // DataTable end
    </script>
</body>


@endsection