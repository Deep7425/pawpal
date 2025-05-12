@extends('layouts.admin.Masters.Master')
@section('title', 'Sponsor & Suggest Doctors')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">

                <div class="row form-top-row">
                  
                    <div class="btn-group">
                        <a class="btn btn-success"
                            href="{{ route('admin.sponsorDoc', ['action' => base64_encode('add')]) }}"> <i
                                class="fa fa-plus"></i> Add Sponsor</a>
                    </div>

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$doctors->total()}}</a>
                    </div>


                    {!! Form::open(array('route' => 'admin.sponsoredDoctor', 'id' => 'chnagePagination',
                        'method'=>'POST')) !!}
                    <div class="btn-group head-search">
            

                        <div class="">
                            <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                                <option value="25" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='25' ) selected @endif @endif>25</option>
                                <option value="50" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='50' ) selected @endif @endif>50</option>
                                <option value="100" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='100' ) selected @endif @endif>100</option>
                            </select>
                        </div>

                        <div class=" ml-sm-2">
                            <select class="searchDropDown form-control" name="package_id">
                                <option value="">Select Package</option>
                                <option value="1" @if((app('request')->input('package_id'))!='')
                                    @if(base64_decode(app('request')->input('package_id')) == '1') selected @endif
                                    @endif>Package 1</option>
                                <option value="2" @if((app('request')->input('package_id'))!='')
                                    @if(base64_decode(app('request')->input('package_id')) == '2') selected @endif
                                    @endif>Package 2</option>
                                <option value="3" @if((app('request')->input('package_id'))!='')
                                    @if(base64_decode(app('request')->input('package_id')) == '3') selected @endif
                                    @endif>Package 3</option>
                                <option value="4" @if((app('request')->input('package_id'))!='')
                                    @if(base64_decode(app('request')->input('package_id')) == '4') selected @endif
                                    @endif>Package 4</option>
                                <option value="5" @if((app('request')->input('package_id'))!='')
                                    @if(base64_decode(app('request')->input('package_id')) == '5') selected @endif
                                    @endif>Package 5</option>
                                <option value="6" @if((app('request')->input('package_id'))!='')
                                    @if(base64_decode(app('request')->input('package_id')) == '6') selected @endif
                                    @endif>Package 6</option>

                            </select>
                        </div>

                        <div class="ml-sm-2">
                            <select class="form-control" name="status">
                                <option value="">Select Status</option>
                                <option value="1" @if((app('request')->input('status'))!='')
                                    @if(base64_decode(app('request')->input('status')) == '1') selected @endif
                                    @endif>Active</option>
                                <option value="0" @if((app('request')->input('status'))!='')
                                    @if(base64_decode(app('request')->input('status')) == '0') selected @endif
                                    @endif>Deactive</option>
                            </select>
                        </div>

                        <div class="ml-sm-2">
                            <div class="input-group custom-search-form">
                                <input name="search" type="text" class="form-control capitalizee"
                                    placeholder="Clinic Name" value="{{ old('search') }}" />
                            </div>
                        </div>

                        <div class="ml-sm-2">
                            <div class="input-group custom-search-form">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        SEARCH
                                    </button>
                                </span>
                            </div>
                        </div>

                    </div>


                </div>

                {!! Form::close() !!}
                <div class="layout-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Clinic Name</th>
                                    <th>Package</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($doctors->count() > 0)
                                @foreach($doctors as $index => $doc)
                                <tr>
                                    <td>
                                        <label>{{$index+($doctors->currentpage()-1)*$doctors->perpage()+1}}.</label>
                                    </td>

                                    <td>{{@$doc->Doctors->clinic_name}}</td>
                                    <td>{{$doc->package_id}}</td>
                                    <td>{{ date('d-M-Y', strtotime($doc->start_date))}}</td>
                                    <td>{{ date('d-M-Y', strtotime($doc->end_date))}}</td>

                                    <td>
                                        <a class="btn @if($doc->status == '1') btn-success @else btn-danger @endif changeStatus"
                                            status="{{$doc->status}}" data-id="{{$doc->id}}"
                                            href="javascript:void();">@if($doc->status == '1') Active @else Inactive
                                            @endif</a>
                                        <!-- <span class="label-default label @if($doc->status == '1') label-success @else label-danger @endif">@if($doc->status == '1') Active @else Inactive @endif</span> -->

                                    </td>
                                    <td>
                                        <a href="{{ route('admin.sponsorDoc', ['action' => base64_encode('edit'), 'id' => base64_encode($doc->id)]) }}"
                                            class="btn btn-info btn-sm"><i class="fa fa-pencil"
                                                aria-hidden="true"></i></a>
                                        <a href="{{ route('admin.sponsorDoc', ['action' => base64_encode('view'), 'id' => base64_encode($doc->id)]) }}"
                                            class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7">No Record Found </td>
                                </tr>
                                @endif
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('js/bootstrap.js') }}"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <script type="text/javascript">

jQuery('.changeStatus').on('click', function() {
        var id = $(this).attr('data-id');
        var status = $(this).attr('status');
        if (confirm('Are you sure ?')) {
            jQuery.ajax({
                url: "{!! route('admin.changeSponsorStatus') !!}",
                // type : "POST",
                dataType: "JSON",
                data: {
                    'id': id,
                    'status': status
                },
                success: function(result) {
                    location.reload();

                }
            });
        } else {
            return false;
        }


    })


    function ForExcel() {
        jQuery("#file_type").val("excel");
        jQuery("#chnagePagination").submit();
        jQuery("#file_type").val("");
    }

    function chnagePagination(e) {
        $("#chnagePagination").submit();
    }
    $(".fromStartDate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        //minDate: new Date(),
        onSelect: function(selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate());
        }
    });

    $(".toStartDate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        //minDate: new Date(),
        onSelect: function(selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate());
        }
    });


    </script>
    @endsection