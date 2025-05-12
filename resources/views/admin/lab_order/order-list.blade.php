@extends('layouts.admin.Masters.Master')
@section('title', 'Lab Orders')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet"
    href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />




<div class="layout-wrapper layout-2">
    <?php $userModule = checkAdminUserModulePermission(52); ?>
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">

            <div class="container-fluid flex-grow-1 container-p-y">

                <div class="row mb-2 ml-1 form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$orders->total()}}</a>
                    </div>
                    <div class="btn-group">
                        <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img
                                src='{{ url("/img/excel-icon.png") }}' /></a>
                    </div>
                    <div class="row-right">
                        <div class="btn-group btn-upload">
                            <form method="post" class="UserDataExcelImports mt-2 mb-3 ml-1"
                                enctype="multipart/form-data" action="{{ route('admin.labOrderImport') }}">
                                {{ csrf_field() }}


                                <div class="form-group">

                                    <table class="table">
                                        <tr>
                                            @if($userModule)
                                            <td style="width: 100px;">
                                                <input type="file" class="file-input" name="laborder_file" required>
                                            </td>

                                            <td>
                                                <input type="submit" name="upload" class="btn btn-primary"
                                                    value="Upload" />
                                            </td>
                                            @endif
                                            <td>

                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </form>
                        </div>
                        <a href="{{ url("/public/lab_order_sample_file.xls") }}" class="btn btn-success btn-icon-split"
                            download="">
                            <span class="icon text-white-50">
                                <i class="fa fa-download"></i>
                            </span>
                            <span class="text">Sample Download</span>
                        </a>
                    </div>
                </div>

                <div class="layout-content card body-edit">
                    <?php $companies = getAllLabCompanies();
						$labCompanies = getLabComGrp();?>
                    <div class="row mb-2 ml-1 mr-1 mt-2">
                        <div class="col-sm-1">
                            <div class=" ">
                                <label>Page</label>
                                {!! Form::open(array('route' => 'admin.labOrders', 'id' => 'chnagePagination',
                                'method'=>'POST')) !!}
                                <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}" />
                                <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                    <!--<option value="5" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '5') selected @endif @endif>5</option>
												<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                                    <option value="25" @if(isset($_GET['page_no']))
                                        @if(base64_decode($_GET['page_no'])=='25' ) selected @endif @endif>25</option>
                                    <option value="50" @if(isset($_GET['page_no']))
                                        @if(base64_decode($_GET['page_no'])=='50' ) selected @endif @endif>50</option>
                                    <option value="100" @if(isset($_GET['page_no']))
                                        @if(base64_decode($_GET['page_no'])=='100' ) selected @endif @endif>100</option>
                                    <option value="500" @if(isset($_GET['page_no']))
                                        @if(base64_decode($_GET['page_no'])=='500' ) selected @endif @endif>500</option>
                                    <option value="1000" @if(isset($_GET['page_no']))
                                        @if(base64_decode($_GET['page_no'])=='1000' ) selected @endif @endif>1000
                                    </option>
                                    <option value="2000" @if(isset($_GET['page_no']))
                                        @if(base64_decode($_GET['page_no'])=='2000' ) selected @endif @endif>2000
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class=" ">
                                <label>Filter By</label>
                                <select class="form-control" name="filter_by">
                                    <option value="">All</option>
                                    <option value="0" @if((app('request')->input('filter_by'))!='')
                                        @if(base64_decode(app('request')->input('filter_by')) == 0) selected @endif
                                        @endif >Created Date</option>
                                    <option value="1" @if((app('request')->input('filter_by'))!='')
                                        @if(base64_decode(app('request')->input('filter_by')) == 1) selected @endif
                                        @endif >Scheduled Date</option>
                                    <option value="2" @if((app('request')->input('filter_by'))!='')
                                        @if(base64_decode(app('request')->input('filter_by')) == 2) selected @endif
                                        @endif >Transaction Date</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="dataTables_length">
                                <label>From</label>
                                <div class="input-group date">
                                    <input type="text" autocomplete="off" class="form-control fromStartDate"
                                        name="start_date"
                                        value="@if((app('request')->input('start_date'))!=''){{ base64_decode(app('request')->input('start_date')) }}@endif" />
                                    <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar"
                                            aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="dataTables_length">
                                <label>To</label>
                                <div class="input-group date">
                                    <input type="text" autocomplete="off" class="form-control toStartDate"
                                        name="end_date"
                                        value="@if((app('request')->input('end_date'))!=''){{ base64_decode(app('request')->input('end_date')) }}@endif" />
                                    <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar"
                                            aria-hidden="true"></i> </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class=" ">
                                <label>Order From</label>
                                <select class="form-control" name="order_type">
                                    <option value="">All</option>
                                    @foreach($companies as $raw)
                                    <?php $rawId = $raw->id == '2' ? 0 : $raw->id; ?>
                                    <option value="{{$rawId}}" @if((app('request')->input('order_type'))!='')
                                        @if(base64_decode(app('request')->input('order_type')) == $rawId) selected
                                        @endif @endif >{{$raw->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="">
                                <label>Orders</label>
                                <select class="form-control" name="filter">
                                    <option value="0">All</option>
                                    <option value="2" @if((app('request')->input('filter'))!='')
                                        @if(base64_decode(app('request')->input('filter')) == '2') selected @endif
                                        @endif >Upcoming</option>
                                    <option value="3" @if((app('request')->input('filter'))!='')
                                        @if(base64_decode(app('request')->input('filter')) == '3') selected @endif
                                        @endif>Completed</option>
                                    <option value="4" @if((app('request')->input('filter'))!='')
                                        @if(base64_decode(app('request')->input('filter')) == '4') selected @endif
                                        @endif>Cancelled</option>
                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="row mb-2 mt-2 ml-1 mr-1">
                        <div class="col-sm-2">
                            <div class=" ">
                                <label>Payment Status</label>
                                <select class="form-control" name="status">
                                    <option value="">All</option>
                                    <option value="0" @if((app('request')->input('status'))!='')
                                        @if(base64_decode(app('request')->input('status')) == 0) selected @endif @endif
                                        >Pending</option>
                                    <option value="1" @if((app('request')->input('status'))!='')
                                        @if(base64_decode(app('request')->input('status')) == 1) selected @endif @endif
                                        >Completed</option>
                                    <option value="2" @if((app('request')->input('status'))!='')
                                        @if(base64_decode(app('request')->input('status')) == 2) selected @endif @endif
                                        >Cancelled</option>
                                    <option value="3" @if((app('request')->input('status'))!='')
                                        @if(base64_decode(app('request')->input('status')) == 3) selected @endif @endif
                                        >Failure Transaction</option>
                                </select>
                            </div>
                        </div>
                        <?php $orderStatus =  array("YET TO ASSIGN","YET TO CONFIRM","ASSIGNED","CANCELLED","DONE","ARRIVED","SERVICED","RELEASED","STARTED","ACCEPTED","COLLECTED","RESCHEDULED","Cancllation request","REQUEST TO RELEASE","NON SERVICEABLE",
									"LEAD","DISPATCHED","CALLBACK","REPORTED","LAB","HUB","FIX APPOINTMENT","PERSUASION","COMPLAINT","REQUEST TO RELEASE"); ?>
                        <div class="col-sm-2">
                            <div class=" ">
                                <label>Order Status</label>
                                <select class="form-control" name="order_status">
                                    <option value="0">All</option>
                                    @foreach($orderStatus as $status)
                                    <option value="{{$status}}" @if((app('request')->input('order_status'))!='')
                                        @if(base64_decode(app('request')->input('order_status')) == $status) selected
                                        @endif @endif >{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class=" ">
                                <label>Order No.</label>
                                <input type="text" placeholder="Hg Order Number" class="form-control" name="ref_orderId"
                                    value="@if((app('request')->input('ref_orderId'))!=''){{ base64_decode(app('request')->input('ref_orderId'))}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class=" ">
                                <label>Order By</label>
                                <input type="text" placeholder="Order By (Name)/(Mobile) " class="form-control"
                                    name="order_by"
                                    value="@if((app('request')->input('order_by'))!=''){{base64_decode(app('request')->input('order_by'))}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class=" ">
                                <label>Pay Type</label>
                                <select class="form-control" name="pay_type">
                                    <option value="0">All</option>
                                    <option value="Postpaid" @if((app('request')->input('pay_type'))!='')
                                        @if(base64_decode(app('request')->input('pay_type')) == 'Postpaid') selected
                                        @endif @endif >Postpaid</option>
                                    <option value="Prepaid" @if((app('request')->input('pay_type'))!='')
                                        @if(base64_decode(app('request')->input('pay_type')) == 'Prepaid') selected
                                        @endif @endif>Prepaid</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class=" ">
                                <label>Payment Mode</label>
                                <select class="form-control" name="payment_mode">
                                    <option value="">All</option>
                                    <option value="1" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '1') selected @endif
                                        @endif >Online Payment</option>
                                    <option value="2" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '2') selected @endif
                                        @endif >Cheque</option>
                                    <option value="3" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '3') selected @endif
                                        @endif >Cash</option>
                                    <option value="4" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '4') selected @endif
                                        @endif >Admin Online</option>
                                    <option value="6" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '6') selected @endif
                                        @endif >Payment Link Online</option>
                                    <option value="7" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '7') selected @endif
                                        @endif >Bank/NEFT/RTGS/IMPS</option>
                                    <option value="8" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '8') selected @endif
                                        @endif >Credit to CCL</option>
                                    <option value="9" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '9') selected @endif
                                        @endif >Credit to RELIABLE</option>
                                    <option value="10" @if((app('request')->input('payment_mode'))!='')
                                        @if(base64_decode(app('request')->input('payment_mode')) == '10') selected
                                        @endif @endif >Credit to LIVING ROOT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class=" ">
                                <label>Organization</label>
                                <select class="form-control" name="organization">
                                    <option value="">All</option>
                                    @if($organization)
                                    @foreach($organization as $res)
                                    <option value="{{$res->id}}" @if((app('request')->
                                        input('organization'))==base64_encode($res->id)) selected @endif
                                        >{{$res->title}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class=" ">
                                <label>&nbsp </label>
                                <div class="input-group custom-search-form">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary form-control" type="submit">
                                            SEARCH
                                        </button>

                                    </span>
                                </div>
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>




                </div>

                <div class="table-responsive labOrdersList">
                    <table class="table table-bordered table-hover LabOrderList123">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Order From</th>
                                <th>Assigned Company</th>
                                <th>OrderNo</th>
                                <!--<th>Order Number</th>-->
                                <th>Product</th>
                                <th>Actual Price</th>
                                <th>Schedule</th>
                                <!--<th>Pay Type</th>-->
                                <th>Payment Mode</th>
                                <th>Payable Amount </th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                                <th>Order By</th>
                                <th>Sale By</th>
                                <th>Organization</th>
                                <th>Transaction Id</th>
                                <th>Trans Date</th>
                                <th>Remark</th>
                                <th>Created At</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($orders->count() > 0)
                            @foreach($orders as $index => $element)
                            <?php
										$orderTime =  strtotime($element->created_at);
										$currentTime =  time();
										$diff =  $currentTime - $orderTime;
										$minutes = floor($diff / 60);
										$meta_data = json_decode($element->meta_data);
										?>
                            
                            <tr class="tr-appointment">
                                <td>
                                    <label>{{$index+($orders->currentpage()-1)*$orders->perpage()+1}}.</label>
                                </td>

                                <td>@if($element->type == 0) Thyrocare technologies Ltd. @else
                                    {{@$labCompanies[$element->type]}}@endif</td>
                                <td>
                                    @if($element->order_status != 'DONE')
                                    <select class="form-control company_type" name="page_no" style="width:150px">
                                        <option>Company Assign</option>
                                        @foreach($companies as $raw)
                                        <option value="{{@$raw->id}}_{{$element->id}}" @if($raw->id ==
                                            $element->company_id) selected @endif>
                                            {{@$raw->title}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    {{@$element->LabCompany->title}}
                                    @endif
                                </td>
                                <td>{{$element->orderId}}</td>
                                <!--<td>{{$element->ref_orderId}}</td>-->
                                <td>{{$element->product}}</td>
                                <td id="actual_cost{{$index+($orders->currentpage()-1)*$orders->perpage()+1}}"></td>

                                <td> @if(!empty($element->appt_date)) {{date('d M Y',$element->appt_date)}} <br>
                                    {{date('h:i A',$element->appt_date)}} :
                                    {{date('h:i A',$element->appt_date+3600 )}}@endif</td>
                                <!--<td>{{$element->pay_type}}</td>-->
                                <td>@if($element->payment_mode_type == "1") Online Payment
                                    @elseif($element->payment_mode_type == "2") Cheque
                                    @elseif($element->payment_mode_type == "3") Cash @elseif($element->payment_mode_type
                                    == "4") Admin Online @elseif($element->payment_mode_type == "5") Free
                                    @elseif($element->payment_mode_type == "6") Payment Link
                                    @elseif($element->payment_mode_type == "7") Bank/NEFT/RTGS/IMPS
                                    @elseif($element->payment_mode_type == "8") Credit to CCL
                                    @elseif($element->payment_mode_type == "9") Credit to RELIABLE
                                    @elseif($element->payment_mode_type == "10") Credit to LIVING ROOT @endif</td>
                                <td>{{$element->payable_amt}}</td>
                                <td>
                                    <div class="LabOrderListTop123">
                                        <select class="form-control lab_type" name="page_no">
                                            @if($element->status == 1)
                                            <option value="1_{{$element->id}}" @if($element->status == 1) selected
                                                disabled @endif >Completed</option>
                                            @else
                                            <option value="0_{{$element->id}}" @if($element->status == 0) selected
                                                @endif >Pending</option>
                                            <option value="1_{{$element->id}}" @if($element->status == 1) selected
                                                disabled @endif >Completed</option>
                                            <option value="2_{{$element->id}}" @if($element->status == 2) selected
                                                @endif>Cancelled</option>
                                            <option value="3_{{$element->id}}" @if($element->status == 3) selected
                                                @endif>Failure Transaction</option>
                                            @endif

                                        </select>

                                        @if($element->lab_type==0)<span class="cancel-btn  label">Free</span>@endif
                                    </div>
                                </td>

                                <td>
                                    @if(!empty($element->order_status))
                                    <span
                                        class="cancel-btn label-default label @if($element->order_status == 'CANCELLED') label-danger @elseif($element->order_status != 'CANCELLED') label-success @else label-warning @endif">{{ $element->order_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{@$element->order_by}} {{@$meta_data->mobile}}
                                </td>
                                <td>
                                    @if($element->added_by == 0) User @else
                                    {{getNameByLoginId($element->added_by)}}@endif
                                </td>
                                <td>
                                    {{@$element->user->OrganizationMaster->title}}
                                </td>
                                <td>@if(@$element->LabOrderTxn->tracking_id!='null'){{@$element->LabOrderTxn->tracking_id}}@endif
                                </td>
                                <td>{{@$element->LabOrderTxn->trans_date}}</td>
                                <td>{{@$element->remark}}</td>
                                <td>{{$element->created_at}}</td>
                                <td>{{@$element->admin->name}}</td>

                                {{--<td>
											<span  class="label-default label label-success" onclick="viewOrderDetails('{{$element->orderId}}');">View
                                Order</span>
                                <span class="label-default label label-danger"
                                    onclick="deleteOrder('{{$element->orderId}}');" title="Delete">Delete</span>
                                @if(!empty($element->LabReports))
                                @if($element->LabReports->company_id == 2)
                                <a target="_blank" href="{{$element->LabReports->report_pdf_name}}"><span
                                        class="label-default label label-primary" title="Download Report">Download
                                        Report</span></a>
                                @else
                                <a target="_blank"
                                    href="<?=url("/")."/public/others/lab-reports/".$element->LabReports->report_pdf_name?>"><span
                                        class="label-default label label-primary" title="Download Report">Download
                                        Report</span></a>
                                @endif
                                @endif
                                <span class="label-default label label-succes"><a
                                        href="{{route('downloadLabBill',base64_encode($element->id))}}">Download
                                        Receipt</a></span>
                                <button class="SMsBtn"
                                    onclick="smsView({{$element}},'{{date('d M Y h:i A',$element->appt_date)}}')"><i
                                        class="fa fa-commenting-o" aria-hidden="true"></i>SMS</button>

                                </td>--}}
                                <td>
                                    <div class="viewSubscription123 pptHere">
                                        <ul style="margin: 5px;">
                                            <li>
                                                <a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
                                                <ul style="width: 200px;">
                                                    <li>
                                                        <span class="label-default label"
                                                            onclick="viewOrderDetails('{{$element->orderId}}');">View
                                                            Order</span>
                                                    </li>
                                                    <li>
                                                        <span class="label-default label label-danger"
                                                            onclick="deleteOrder('{{$element->orderId}}');"
                                                            title="Delete">
                                                          Delete
                                                        </span>
                                                    </li>
                                                    @if(!empty($element->LabReports))
                                                    @if($element->LabReports->company_id == 2)
                                                    <li>
                                                        <a target="_blank"
                                                            href="{{$element->LabReports->report_pdf_name}}">
                                                            <span class="label-default label label-primary"
                                                                title="Download Report">Download Report</span>
                                                        </a>
                                                    </li>
                                                    @else
                                                    <li>
                                                        <a target="_blank"
                                                            href="<?=url("/")."/public/others/lab-reports/".$element->LabReports->report_pdf_name?>">
                                                            <span class="label-default label label-primary"
                                                                title="Download Report">Download Report</span>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @endif
                                                    <li>
                                                        <span class="label-default label ">
                                                            <a
                                                                href="{{route('downloadLabBill',base64_encode($element->id))}}">Download
                                                                Receipt</a>
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <button class="SMsBtn"
                                                            onclick="smsView({{$element}},'{{date('d M Y h:i A',$element->appt_date)}}')">
                                                            <i class="fa fa-commenting-o" aria-hidden="true"></i>SMS
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button><a href="javascript:void(0);" id="addLabtNote"
                                                                labId="{{base64_encode($element->id)}}"
                                                                labNote="{{$element->remark}}" title="Add Note"><i
                                                                    class="fa fa-sticky-note-o" aria-hidden="true"></i>
                                                                Note</a></button>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </td>


                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="11">No Record Found </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
                    <ul class="pagination pagination-large">
                        {{ $orders->appends($_GET)->links() }}
                    </ul>
                </div>

            </div>
        </div>


    </div>
</div>
<div class="modal fade" id="viewOrder" role="dialog" data-backdrop="static" data-keyboard="false">
</div>
<div class="modal fade" id="cancelOrderModel" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog ">
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Order Cancel</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-bd lobidrag">

                    <div class="panel-body">
                        {!! Form::open(array('id' => 'cancelOrder','name'=>'updateLocality', 'enctype' =>
                        'multipart/form-data')) !!}
                        <input type=hidden value="" id="orderId" name="orderId" />
                        <div class="form-group">
                            <label>Cancel Reason</label>
                            <textarea name="cancel_reason" rows="5" cols="40" class="form-control" maxlength="200"
                                placeholder="Write Cancel Reason..."></textarea>
                            <span class="help-block"></span>
                        </div>
                        <div class="reset-button">
                            <button type="submit" class="btn btn-success submit">Cancel Now</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Note</h4>
            </div>
            <div class="modal-body feedback">
                {!! Form::open(array('id' => 'addNote','name'=>'addNote')) !!}
                <!-- <input type="hidden" name="note_type" value="2"> -->
                <input type="hidden" name="id" id="lab_id" value="">
                <div class="form-group">
                    <label>Note:</label>
                    <textarea type="text" name="note" rows="5" class="form-control" id="lab_note"
                        placeholder="Write Note..."></textarea>
                    <span class="help-block"></span>
                </div>

                <div class="reset-button">
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-success addnote">Save</button>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>



<script>
jQuery(document).on("click", "#addLabtNote", function() {
    $('#lab_id').val('');
    $('#lab_note').val('');
    var id = $(this).attr('labId');
    var note = $(this).attr('labNote');
    $('#lab_id').val(id);
    $('#lab_note').val(note);
    $('#AddModal').modal('show');
});

$(document.body).on('click', '.addnote', function() {

    console.log("ww")
    jQuery("form[name='addNote']").validate({
        rules: {
            note: "required"
        },
        messages: {},
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find('.help-block'));
        },
        ignore: ":hidden",
        submitHandler: function(form) {
            $(form).find('.submit').attr('disabled', true);
            jQuery('.loading-all').show();
            jQuery.ajax({
                type: "POST",
                dataType: "JSON",
                url: "{!! route('admin.updateNote') !!}",
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data == 1) {
                        console.log(data)
                        jQuery('.loading-all').hide();
                        $(form).find('.submit').attr('disabled', false);
                        location.reload();
                    } else {
                        jQuery('.loading-all').hide();
                        $(form).find('.submit').attr('disabled', false);
                        alert("Oops Something Problem");
                    }
                },
                error: function(error) {
                    jQuery('.loading-all').hide();
                    alert("Oops Something goes Wrong.");
                }
            });
        }
    });
});

function smsView(data, date) {
    let meta_data = JSON.parse(data.meta_data);
    console.log(data);
    console.log(meta_data);
    let patname = data.order_by;
    let age = meta_data.age + ' Ys';
    let gender = meta_data.Gender;
    let mobile = meta_data.mobile;
    let address = meta_data.address;
    // var doc = "Dr. "+data.user.doctor_info.first_name.charAt(0).toUpperCase() + data.user.doctor_info.first_name.slice(1)+" "+data.user.doctor_info.last_name;
    var paymentMode = '';
    if (data.payment_mode_type == "1") {
        paymentMode = 'Online Payment';
    } else if (data.payment_mode_type == "2") {
        paymentMode = 'Cheque';
    } else if (data.payment_mode_type == "3") {
        paymentMode = 'Cash';
    } else if (data.payment_mode_type == "4") {
        paymentMode = 'Admin Online';
    } else if (data.payment_mode_type == "5") {
        paymentMode = 'Free';
    } else if (data.payment_mode_type == "6") {
        paymentMode = 'Payment Link'
    } else if (data.payment_mode_type == "7") {
        paymentMode = 'Bank/NEFT/RTGS/IMPS'
    } else if (data.payment_mode_type == "8") {
        paymentMode = 'Credit to CCL'
    } else if (data.payment_mode_type == "9") {
        paymentMode = 'Credit to RELIABLE'
    } else if (data.payment_mode_type == "10") {
        paymentMode = 'Credit to LIVING ROOT'
    }

    var smsText = 'Test : ' + data.product + ' \nDate : ' + date + ' \nName : ' + patname + ' \nAge/Gender : ' + age +
        ' / ' + gender + ' \nMobile no : ' + mobile + ' \nAddress :  ' + address + ' \npayment : ' + paymentMode;
    // alert(smsText);
    // prompt(smsText);
    prompt("Copy to clipboard: Ctrl+C", smsText);
    copyText(smsText);
}

function copyText(text) {
    var input = document.body.appendChild(document.createElement("input"));
    input.value = text;
    input.select();
    document.execCommand('copy');
    input.parentNode.removeChild(input);
}
jQuery(document).ready(function() {

    // $(".fromStartDate").datepicker({
    // 	  changeMonth: true,
    // 	  changeYear: true,
    // 	  dateFormat: 'yy-mm-dd',
    // 	onSelect: function (selected) {
    // 		var dt = new Date(selected);
    // 		dt.setDate(dt.getDate());
    // 		$(".toDOB").datepicker("option", "minDate", dt);
    // 	}
    // });
    // jQuery('.fromStartDate_cal').click(function () {
    // 	jQuery('.fromStartDate').datepicker('show');
    // });

    $(".fromStartDate").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayHighlight: true
}).datepicker('setDate', new Date()).on('changeDate', function () {
                $(this).datepicker('hide');
            });

    // $(".toStartDate").datepicker({
    // 	  changeMonth: true,
    // 	  changeYear: true,
    // 	  dateFormat: 'yy-mm-dd',
    // 	onSelect: function (selected) {
    // 		var dt = new Date(selected);
    // 		dt.setDate(dt.getDate());
    // 		$(".toDOB").datepicker("option", "minDate", dt);
    // 	}
    // });
    // jQuery('.toStartDate_cal').click(function () {
    // 	jQuery('.toStartDate').datepicker('show');
    // });
    
    $(".toStartDate").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayHighlight: true
}).datepicker('setDate', new Date()).on('changeDate', function () {
                $(this).datepicker('hide');
            });

});

function chnagePagination(e) {
    $("#chnagePagination").submit();
}

function ForExcel() {
    jQuery("#file_type").val("excel");
    $("#chnagePagination").submit();
    jQuery("#file_type").val("");
}

function deleteOrder(orderId) {
    if (confirm('Are you sure want to delete?') == true) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.deleteOrder')!!}",
            data: {
                'orderId': orderId
            },
            success: function(data) {
                if (data == 1) {
                    location.reload();
                } else {
                    alert("Oops Something Problem");
                }
                jQuery('.loading-all').hide();
            },
            error: function(error) {
                jQuery('.loading-all').hide();
                alert("Oops Something goes Wrong.");
            }
        });
    }
}

function viewOrderDetails(orderId) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.viewOrderDetails')!!}",
        data: {
            'orderId': orderId
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#viewOrder").html(data);
            jQuery('#viewOrder').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

jQuery(document).on("change", ".lab_type", function(e) {
    jQuery('.loading-all').show();
    var id = this.value;
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.chnagepayStatus')!!}",
        data: {
            'orderId': id
        },
        success: function(data) {
            if (data == '1') {
                location.reload();
            }

        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });



});

jQuery(document).ready(function() {
    $('.cancelOrder').on('click', function() {
        $('#orderId').val($(this).attr('orderId'));
        var orderTime = $(this).attr('orderTime');
        if (orderTime < 10) {
            alert('Please Cancel Order After 10 Minutes');
            return false;
        } else {
            $('#cancelOrderModel').modal('toggle');
        }

    });

    jQuery(document).on("change", ".changeSts", function(e) {
        var status = $(this).val();
        var id = $(this).attr('orderId');
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.changeOrderStatus')!!}",
            data: {
                'id': id,
                'status': status
            },
            success: function(data) {
                jQuery('.loading-all').hide();
                alert("Status Changed Successfully");
                location.reload();
            },
            error: function(error) {
                jQuery('.loading-all').hide();
                alert("Oops Something goes Wrong.");
            }
        });
    });

    // jQuery(document).on("change", ".payment_mode_type", function (e) {
    //     var status = $(this).val();
    // 	console.log("status = ", status);
    // 	var id = $(this).attr('orderId');
    // 	console.log("id =" , id)
    //     jQuery('.loading-all').show();
    // 		jQuery.ajax({
    // 		type: "POST",
    // 		dataType : "JSON",
    // 		url: "{!! route('admin.changePaymentMode')!!}",
    // 		data:{
    // 			 'id':id,
    // 			'payment_mode_type':status,
    // 			'tracking_id': null,
    // 			'trans_date':null

    // 		},
    // 		success: function(data){

    // 		  jQuery('.loading-all').hide();

    // 		//   alert("Payment Mode Changed Successfully");
    // 		//   location.reload();
    // 		},
    // 		error: function(error){
    // 			jQuery('.loading-all').hide();
    // 			alert("Oops Something goes Wrong.");
    // 		}
    // 	  });
    // });



    jQuery(document).on("change", ".payment_mode_type", function(e) {
        var status = $(this).val();
        var id = $(this).attr('orderId');

        // Define your payment type value here

        if (status == 4 || status == 7) {

            jQuery(document).on("click", ".submit", function(e) {
                e.preventDefault(); // Prevent the default form submission

                var status = $('.payment_mode_type').val(); // Get the payment mode status
                var id = $('.payment_mode_type').attr('orderId'); // Get the order ID

                console.log("status = ", status)
                console.log("id = ", id)

                if (status == 4 || status == 7) {
                    tracking_id = $('#tracking_id').val(); // Get the tracking ID value
                    trans_date = $('#trans_date').val(); // Get the transaction date value

                    if ((tracking_id === '' || trans_date === '')) {
                        alert("Please provide both Tracking ID and Transaction Date.");
                        return;
                    }
                    console.log("tracking_id =", tracking_id)
                    console.log("trans_date =", trans_date)
                }

                $('.loading-all').show(); // Show loading indicator

                // Perform AJAX submission
                jQuery.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "{!! route('admin.changePaymentMode') !!}",
                    data: {
                        'id': id,
                        'payment_mode_type': status,
                        'tracking_id': tracking_id,
                        'trans_date': trans_date
                    },
                    success: function(data) {


                        $('.loading-all').hide(); // Hide loading indicator
                        // Handle success response
                        location.reload();

                    },
                    error: function(error) {
                        $('.loading-all').hide(); // Hide loading indicator
                        alert("Oops, 2 something went wrong.");
                    }
                });
            });

        } else {
            var tracking_id = null;
            var trans_date = null;
        }

        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.changePaymentMode')!!}",
            data: {
                'id': id,
                'payment_mode_type': status,
                'tracking_id': tracking_id,
                'trans_date': trans_date

            },
            success: function(data) {
                jQuery('.loading-all').hide();
                // Handle success response
            },
            error: function(error) {
                jQuery('.loading-all').hide();
                // alert("Oops 1 Something goes Wrong.");
            }
        });
    });




    // Attach a click event listener to the submit button with class 'submit'
    jQuery(document).on("click", ".submit", function(e) {
        e.preventDefault(); // Prevent the default form submission

        var status = $('.payment_mode_type').val(); // Get the payment mode status
        var id = $('.payment_mode_type').attr('orderId'); // Get the order ID

        var tracking_id = null;
        var trans_date = null;

        if (status == 4 || status == 7) {
            tracking_id = $('#tracking_id').val(); // Get the tracking ID value
            trans_date = $('#trans_date').val(); // Get the transaction date value
        }

        $('.loading-all').show(); // Show loading indicator

        // Perform AJAX submission
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.changePaymentMode') !!}",
            data: {
                'id': id,
                'payment_mode_type': status,
                'tracking_id': tracking_id,
                'trans_date': trans_date
            },
            success: function(data) {
                $('.loading-all').hide(); // Hide loading indicator
                // Handle success response
            },
            error: function(error) {
                $('.loading-all').hide(); // Hide loading indicator
                alert("Oops, something went wrong.");
            }
        });
    });

    // ------------------------------------------------------------------------------------------------------------------------------

    // Attach a click event listener to the submit button with class 'submit'








    jQuery(document).on("change", ".company_type", function(e) {
        var company_type = $(this).val();

        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.changeCompanyStatus')!!}",
            data: {
                'company_type': company_type
            },
            success: function(data) {
                jQuery('.loading-all').hide();

                if (data == 1) {
                    alert("Company Assigned Successfully");
                }

                location.reload();
            },
            error: function(error) {
                jQuery('.loading-all').hide();
                alert("Oops Something goes Wrong.");
            }
        });
    });
    jQuery("#cancelOrder").validate({
        rules: {
            cancel_reason: {
                required: true,
                maxlength: 200
            },
        },
        messages: {},
        errorPlacement: function(error, element) {
            error.appendTo(element.next());
        },
        ignore: ":hidden",
        submitHandler: function(form) {
            jQuery('.loading-all').show();
            $(form).find('.submit').attr('disabled', true);
            jQuery.ajax({
                type: "POST",
                dataType: "JSON",
                url: "{!! route('cancelOrder')!!}",
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    jQuery('.loading-all').hide();
                    console.log(data.output.RESPONSE);
                    if (data.status == 1) {
                        alert("Order Cancel Successfull !");
                        $(form).find('.submit').attr('disabled', false);
                        location.reload();
                    } else {
                        alert("Oops Something Problem To Cancel Order");
                        $(form).find('.submit').attr('disabled', false);
                        location.reload();
                    }


                },
                error: function(error) {
                    if (error.status == 401) {
                        alert("Session Expired,Please logged in..");
                        location.reload();
                    } else {
                        jQuery('.loading-all').hide();
                        alert("Oops Something goes Wrong.");
                        jQuery('#saveAddress').attr('disabled', false);
                    }
                }
            });
        }
    });
});


// function cancelOrder(orderId, cancel_reason) {
//   jQuery('.loading-all').show();
//   jQuery.ajax({
//   type: "POST",
//   dataType : "JSON",
//   url: "{!! route('cancelOrder') !!}",
//   data: {'orderId':orderId, 'cancel_reason':cancel_reason},
//   success: function(data){
//     jQuery('.loading-all').hide();
//       if(data.status == '0') {
// 					jQuery('.loading-all').hide();
// 					console.log(data.output.RESPONSE);
// 				}
//       $.alert({
//         title: 'Order Cancel Successfull !',
//         content: 'Your order has been canceled',
//         type: 'green',
//         typeAnimated: true,
//         buttons: {
//             ok: function(){
//             location.reload();
//             },
//         }
//       });
//
//     },
//     error: function(error)
//     {
//       if(error.status == 401)
//       {
//           alert("Session Expired,Please logged in..");
//           location.reload();
//       }
//       else
//       {
//         jQuery('.loading-all').hide();
//         alert("Oops Something goes Wrong.");
//         jQuery('#saveAddress').attr('disabled',false);
//       }
//     }
//   });
// }

$('#image-file').on('change', function() {
    var fileSize = this.files[0].size / 1024;
    if (fileSize > 10) {
        alert("File size should not be greater than 1 mb");
        $('#image-file').val('');
        return false;
    }
});


$('.btn-default').click(function() {
    $('.modal').modal('hide');
});

$('.close').click(function() {
    $('.modal').modal('hide');
});
</script>
@endsection