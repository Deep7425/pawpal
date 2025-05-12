@extends('layouts.admin.Masters.Master')
@section('title', 'Edit Medicine')
@section('content')
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y page">


                <div class="row mb-2 form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-primary" href="{{ route('admin.medicineMaster') }}"> <i
                                class="fa fa-list"></i> Medicine List</a>
                    </div>

                </div>


                <div class="layout-content layout-content card">

                    {!! Form::open(array('route' => 'admin.createMedicine', 'id' => 'addMedicine','enctype'
                    => 'multipart/form-data' , 'class' => 'ptm-order')) !!}
                    <div class="row">
                    <input type=hidden name="row_id" value="{{base64_encode($row->id)}}" />

                    <div class="form-group row ml-2 mr-2" style="margin-top:15px;">

                        <div class="col-sm-3 form-group">
                            <label>Company Name<span>*</span></label>
                            <?php /*
                            <select class="form-control company_id" name="manufacturer">
                                <option value="">Please Select</option>
                                <option value="0">Add New Company</option>
                                @foreach ($manufacturers as $key => $manu)
                                  <option value="{{$manu->manufacturer}}" {{$manu->manufacturer == $row->manufacturer ? 'selected' : ""}}>{{$manu->manufacturer}}</option>
                                @endforeach

                            </select>
                            <div class="new-company" style="display:none">
                              <input type="text" class="form-control" id="new_company" name="new_company" placeholder="Enter New Company Name" value=""/>
                              <div class="closse closeInputBox"><i class="fa fa-times" aria-hidden="true"></i></div>
                            </div> */ ?>
                            <div class="treatment-section manufacturerSearchDiv">
                                <input type="text" id="drug_name" placeholder="Manufacturer"
                                    class="form-control manufacturerSearch" autocomplete="off" name="manufacturer"
                                    value="{{$row->manufacturer}}" />
                                    <span class="help-block"></span>
                                <i class="fa fa-search" aria-hidden="true"></i>
                                <div class="suggesstion-box" style="display:none;"></div>
                            </div>
                          
                        </div>
                      
                        <div class="col-sm-3 form-group">
                            <label>Medicine Category<span>*</span></label>
                            <select class="form-control" name="medicine_type">
                                <option value="">Please Select</option>
                                @foreach($medicine_categories as $category)
                                <option value="{{$category->medicine_type}}"
                                    {{$category->medicine_type == $row->medicine_type ? 'selected' : ""}}>
                                    {{$category->medicine_type}}</option>
                                @endforeach
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Medicine Type<span>*</span></label>
                            <select class="form-control" name="pack_in">
                                <option value="">Please Select</option>
                                @foreach($pack_ins as $pack)
                                <option value="{{$pack->pack_in}}"
                                    {{$pack->pack_in == $row->pack_in ? 'selected' : ""}}>{{$pack->pack_in}}
                                </option>
                                @endforeach
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Type<span>*</span></label>
                            <select class="form-control" name="type">
                                <option value="">Please Select</option>
                                <option value="allopathy" {{$row->type == 'allopathy' ? 'selected' : ""}}>
                                    allopathy</option>
                                <option value="ayurvedic" {{$row->type == 'ayurvedic' ? 'selected' : ""}}>
                                    ayurvedic</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Medicine Name<span>*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="Product Name"
                                value="{{$row->name}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Composition Name</label>
                            <input type="text" class="form-control" name="composition_name"
                                placeholder="Composition Name" value="{{$row->composition_name}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Packing Label</label>
                            <input type="text" class="form-control" name="packing_label" placeholder="Packing Label"
                                value="{{$row->packing_label}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Unit</label>
                            <select class="form-control" name="unit">
                                <option selected="" value="">Please Select</option>
                                @foreach(getUnits() as $key => $unit)
                                <option value="{{$unit}}" {{$unit == $row->unit ? 'selected' : ""}}>{{$unit}}
                                </option>
                                @endforeach
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Pack Unit</label>
                            <input type="text" class="form-control" name="pack_unit" placeholder="Pack Unit"
                                value="{{$row->pack_unit}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Weight</label>
                            <input type="text" class="form-control" name="weight" placeholder="Weight"
                                value="{{$row->weight}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Price</label>
                            <input type="text" class="form-control" name="price" placeholder="Price"
                                value="{{$row->price}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>RX Required<span>*</span></label>
                            <select class="form-control" name="rx_req">
                                <option selected="" value="">Please Select</option>
                                <option value="1" {{$row->rx_req == '1' ? 'selected' : ""}}>Yes</option>
                                <option value="0" {{$row->rx_req == '0' ? 'selected' : ""}}>No</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>is Banned<span>*</span></label>
                            <select class="form-control" name="banned">
                                <option selected="" value="">Please Select</option>
                                <option value="1" {{$row->banned == '1' ? 'selected' : ""}}>Yes</option>
                                <option value="0" {{$row->banned == '0' ? 'selected' : ""}}>No</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-12 form-group">
                            <div class="reset-button">
                                <button type="reset" class="btn btn-warning">Reset</button>
                                <button type="submit" class="btn btn-success submit">Save</button>
                            </div>
                        </div>
                    </div></div>
                    {!! Form::close() !!}

                </div>

            </div>
        </div>
    </div>
</div>

<!-- /.content -->

<script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script>
$(document).ready(function() {
    $("#exampleSelect1").multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
    });
});
</script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery(".datepicker").datepicker({
        dateFormat: "dd-mm-yy",
    });
});
// When the browser is ready...
jQuery(document).ready(function() {
    jQuery("#addMedicine").validate({
        rules: {
            manufacturer: "required",
            medicine_category: "required",
            medicine_type: "required",
            name: "required",
            rx_req: "required",
            pack_in: "required",
            banned: "required",
            hsn: "required",
            gst: "required"
        },
        // Specify the validation error messages
        messages: {
            // title: "Please enter title",
            // code: "Please enter code",
            // referral_discount: { required: "Please enter coupon discount", number: "Please Enter Valid Number", range: "Please Enter Range(0-100) Value" },
            // code_last_date: "Please enter expire date",
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.next());
        },
        ignore: ":hidden",
        submitHandler: function(form) {
            // $("#otherText").val(editor.getData());
            jQuery(".loading-all").show();
            jQuery(".submit").attr("disabled", true);
            jQuery.ajax({
                type: "POST",
                url: "{!! route('admin.createMedicine')!!}",
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    jQuery(".submit").attr("disabled", false);
                    if (data == 1) {
                        // location.reload();
                        document.location.href =
                            "{!! route('admin.medicineMaster')!!}";
                    } else {
                        alert("System Problem");
                    }
                    jQuery(".loading-all").hide();
                },
                error: function(error) {
                    jQuery(".submit").attr("disabled", false);
                    jQuery(".loading-all").hide();
                    alert("Oops Something goes Wrong.");
                },
            });
        },
    });
});
$(function() {
    //$('#row_dim').hide();
    $(".cType").change(function() {
        //console.log($('.cType').val());
        if ($(".cType").val() == 2) {
            $(".subcatField").val(1);
            $(".subcat").show();
            $(".cappOn").show();
            $("#apply_type").rules("add", "required");
        } else {
            $(".subcat").hide();
            $(".subcatField").val("");
            $(".cappOn").hide();
            //$('#apply_type').removeAttr('required');​​​​​
            $("#apply_type").rules("remove");
        }
    });
});
</script>
<script src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script>
<script>
// $(document).on("click", "[name='company_id']", function () {
// 	if(this.value == 0){
//     jQuery(".new-company").show();
// 		jQuery(this).hide();
//   }
//   else{
//     jQuery(".new-company").hide();
// 	  jQuery(this).show();
// 	}
// });
$(document).on("click", ".closeInputBox", function() {
    jQuery(".new-company").hide();
    jQuery("[name='company_id']").show();
});
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
            jQuery(currSearch).css("background",
                "#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
        },
        success: function(data) {
            var liToAppend = "";
            if (data.length > 0) {
                jQuery.each(data, function(k, v) {
                    liToAppend += '<li  value="' + v.name +
                        '" class="dataList">' + v.name + '</li>';
                });
            } else {
                liToAppend += '<li value="0">"' + jQuery(currSearch).val() +
                    '" Manufacturer Not Found.</li>';
                liToAppend +=
                    '<li><a href="javascript::void(0)" class="dataList"> Add "' +
                    jQuery(currSearch).val() + '" as new Manufacturer.</a></li>';
            }
            jQuery(currSearch).closest(".manufacturerSearchDiv").find(
                ".suggesstion-box").show();
            jQuery(currSearch).closest(".manufacturerSearchDiv").find(
                ".suggesstion-box").html('<ul>' + liToAppend + '</ul>');
        }
    });
});
// $(".manufacturerSearch").blur(function(){
//   $(".suggesstion-box").hide();
// });
jQuery(document).on("click", ".dataList", function() {
    $(".manufacturerSearch").val($(this).attr("value"));
    jQuery(this).closest(".suggesstion-box").hide();
    jQuery(this).closest(".suggesstion-box ul").remove();
});
</script>
@endsection