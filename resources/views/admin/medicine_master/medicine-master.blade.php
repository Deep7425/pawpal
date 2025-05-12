@extends('layouts.admin.Masters.Master')
@section('title', 'Medicine Master')
@section('content')
<!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y medicine-master">

                <div class="row form-top-row">

            <div class="btn-group mr-1">
                   <a class="btn btn-success" href="{{route('admin.addMedicine')}}"> <i class="fa fa-plus"></i> Add
                            Medicine</a>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$object->total()}}</a>
                    </div>

                    <div class="btn-group head-search ml-sm-2">
                        <div class="">
                            {!! Form::open(array('route' => 'admin.medicineMaster', 'id' => 'chnagePagination',
                            'method'=>'POST')) !!}
                            <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                <option value="25" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='25' ) selected @endif @endif>25</option>
                                <option value="50" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='50' ) selected @endif @endif>50</option>
                                <option value="100" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='100' ) selected @endif @endif>100</option>
                                <option value="1000" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='1000' ) selected @endif @endif>1000</option>
                            </select>
                        </div>


                        <div class="ml-sm-2">
                            <div class="input-group custom-search-form">
                                <input name="search" type="text" class="form-control capitalizee" placeholder="search"
                                    value="{{ old('search') }}" />
                            </div>
                        </div>

                        <div class=" ml-sm-2">
                            <div class="input-group custom-search-form">
                                <div class="treatment-section manufacturerSearchDiv">
                                    <input type="text" placeholder="Manufacturer"
                                        class="form-control manufacturerSearch" autocomplete="off" name="manufacturer"
                                        value="{{base64_decode(app('request')->input('manufacturer'))}}" />
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    <div class="suggesstion-box" style="display:none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="layout-content card appointment-master">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class=" ml-sm-2">
                                <div class="input-group custom-search-form">
                                    <select name="pack_in" class="form-control">
                                        <option value="">Pack In</option>
                                        @foreach ($pack_ins as $key => $row)
                                        <option value="{{$row->pack_in}}" @if((app('request')->input('pack_in'))!='')
                                            @if(base64_decode(app('request')->input('pack_in')) == $row->pack_in)
                                            selected @endif @endif>{{$row->pack_in}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="ml-sm-2">
                                <div class="input-group custom-search-form">
                                    <select name="rx_req" class="form-control">
                                        <option value="">RX Required</option>
                                        <option value="1" @if((app('request')->input('rx_req'))!='')
                                            @if(base64_decode(app('request')->input('rx_req')) == '1') selected @endif
                                            @endif>Yes</option>
                                        <option value="2" @if((app('request')->input('rx_req'))!='')
                                            @if(base64_decode(app('request')->input('rx_req')) == '2') selected @endif
                                            @endif>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="ml-sm-2">
                                <div class="input-group custom-search-form">
                                    <select name="medicine_type" class="form-control">
                                        <option value="">Medicine Type</option>
                                        @foreach ($medicine_types as $key => $row)
                                        <option value="{{$row->medicine_type}}" @if((app('request')->
                                            input('medicine_type'))!='')
                                            @if(base64_decode(app('request')->input('medicine_type')) ==
                                            $row->medicine_type) selected @endif @endif>{{$row->medicine_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="ml-sm-2">
                                <div class="input-group custom-search-form">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit">
                                            SEARCH
                                        </button>
                                    </span>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="layout-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Medicine Category</th>
                                    <th>Icon</th>
                                    <th>Medicine Name</th>
                                    <th>Composition Name</th>
                                    <th>Manufacturer</th>
                                    <th>Medicine Type</th>
                                    <th>Pack/Unit</th>
                                    <th>Weight</th>
                                    <th>Strength/Unit</th>
                                    <th>Price(MRP)</th>
                                    <th>RX Required</th>
                                    <th>Banned</th>
                                    <th width="115" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($object->count() > 0)
                                @foreach($object as $index => $row)
                                <tr>
                                    <td>
                                        <label>{{$index+($object->currentpage()-1)*$object->perpage()+1}}.</label>
                                    </td>
                                    <td>{{$row->medicine_type}} / {{$row->type}}</td>
                                    <td><img @if(!empty($row->images)) src="{{json_decode($row->images)}}" @else
                                        src="https://onemg.gumlet.io/image/upload/a_ignore,w_380,h_380,c_fit,q_auto,f_auto/v1625228213/hx2gxivwmeoxxxsc1hix.png"
                                        @endif width="50" height="50"/></td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->composition_name}}</td>
                                    <td>{{$row->manufacturer}}</td>
                                    <td>{{$row->pack_in}}</td>
                                    <td>{{$row->pack_unit}}</td>
                                    <td>{{$row->weight}}</td>
                                    <td>{{$row->packing_label}}</td>
                                    <td>@if(!empty($row->price)){{number_format($row->price,2)}}@endif</td>
                                    <td>@if($row->rx_req == '1') Yes @else No @endif</td>
                                    <td>@if($row->banned == '1') Yes @else No @endif</td>
                                    <td width="115">
                                        <a href="{{route('admin.editMedicine',base64_encode($row->id))}}"
                                            class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                            title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <button onclick="deleteOrg({{$row->id}});" class="btn btn-danger btn-sm"
                                            data-toggle="tooltip" data-placement="right" title="Delete "><i
                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="14">No Record Found </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                   
                </div>
				<div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
                        <ul class="pagination pagination-large">
                            {{ $object->appends($_GET)->links() }}
                        </ul>
                    </div>

            </div>
        </div>
    </div>
</div>
<script>
function deleteOrg(id) {
    if (confirm('Are you sure want to delete?') == true) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('admin.modifyMedicine')!!}",
            data: {
                'id': id,
                'action': 'delete'
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
jQuery(document).on("keyup click", ".manufacturerSearch", function() {
    var currSearch = this;
    jQuery.ajax({
        type: "POST",
        url: "{!! route('admin.modifyMedicine') !!}",
        data: {
            'searchText': jQuery(this).val(),
            'action': 'manufacturerSearch'
        },
        beforeSend: function() {
            jQuery(currSearch).css("background", "#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
        },
        success: function(data) {
            var liToAppend = "";
            if (data.length > 0) {
                jQuery.each(data, function(k, v) {
                    liToAppend += '<li  value="' + v.name + '" class="dataList">' + v.name +
                        '</li>';
                });
            } else {
                liToAppend += '<li value="0">"' + jQuery(currSearch).val() +
                    '" Manufacturer Not Found.</li>';
                liToAppend += '<li><a href="javascript::void(0)" class="dataList"> Add "' + jQuery(
                    currSearch).val() + '" as new Manufacturer.</a></li>';
            }
            jQuery(currSearch).closest(".manufacturerSearchDiv").find(".suggesstion-box").show();
            jQuery(currSearch).closest(".manufacturerSearchDiv").find(".suggesstion-box").html(
                '<ul>' + liToAppend + '</ul>');
        }
    });
});
// $(".manufacturerSearch").blur(function(){
//   if ($(".manufacturerSearch").val() == "") {
//     $(".suggesstion-box").hide();
//   }
// });
jQuery(document).on("click", ".dataList", function() {
    $(".manufacturerSearch").val($(this).attr("value"));
    jQuery(this).closest(".suggesstion-box").hide();
    jQuery(this).closest(".suggesstion-box ul").remove();
});
jQuery(document).on("click", ".wrapper", function() {
    jQuery(this).find(".suggesstion-box").hide();
    jQuery(this).find(".suggesstion-box ul").remove();
});

function chnagePagination(e) {
    $("#chnagePagination").submit();
}
</script>
@endsection