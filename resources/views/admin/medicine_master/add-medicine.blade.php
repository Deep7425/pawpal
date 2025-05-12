@extends('layouts.admin.Masters.Master')
@section('title', 'Add Medicine')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y medicine-master">


                <div class="row form-top-row">
                    <div class="btn-group">
                        <a class="btn btn-primary" href="{{ route('admin.medicineMaster') }}"> <i
                                class="fa fa-list"></i> Medicine List</a>
                    </div>
                </div>

                <div class="row form-group">
                    <!-- Form controls -->
                    <div class="layout-content card">


                        <div class="panel-body" style="padding-top:15px">
                            {!! Form::open(array('route' => 'admin.createMedicine', 'id' => 'addMedicine','enctype'
                            => 'multipart/form-data' , 'class' => '')) !!}
                            <div class="row">
                                <?PHP /*
                        <div class="col-sm-12 form-group">
                            <label>Search Medicine<span>*</span></label>
                            <div class="treatment-section drugSearchNewDiv">
                              <input type="text" id="drug_name" placeholder="Search Drug" class="form-control drugSearchNew" autocomplete="off" name="medi_name" />
                              <i class="fa fa-search" aria-hidden="true"></i>
                              <div class="suggesstion-box" style="display:none;"></div>
                            </div>
                        </div> */ ?>
                                <!-- <input type=hidden name="term_conditions" id="otherText"/> -->
                                <div class="col-sm-3 form-group">
                                    <label>Company Name<span>*</span></label>
                                    <?php /*
                            <select class="form-control" name="manufacturer">
                                <option value="">Please Select</option>
                                <option value="0">Add New Company</option>
                                @foreach ($manufacturers as $key => $manu)
                                  <option value="{{$manu->manufacturer}}">{{$manu->manufacturer}}</option>
                                @endforeach

                            </select> */ ?>
                                    <div class="treatment-section manufacturerSearchDiv">
                                        <input type="text" id="drug_name" placeholder="Manufacturer"
                                            class="form-control manufacturerSearch" autocomplete="off"
                                            name="manufacturer" />
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        <div class="suggesstion-box" style="display:none;"></div>
                                    </div>

                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Medicine Category<span>*</span></label>
                                    <select class="form-control" name="medicine_type">
                                        <option value="">Please Select</option>
                                        @foreach($medicine_categories as $category)
                                        <option value="{{$category->medicine_type}}">{{$category->medicine_type}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Medicine Type<span>*</span></label>
                                    <select class="form-control" name="pack_in">
                                        <option value="">Please Select</option>
                                        @foreach($pack_ins as $row)
                                        <option value="{{$row->pack_in}}">{{$row->pack_in}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Type<span>*</span></label>
                                    <select class="form-control" name="type">
                                        <option value="">Please Select</option>
                                        <option value="allopathy">allopathy</option>
                                        <option value="ayurvedic">ayurvedic</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Medicine Name<span>*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="Product Name"
                                        value="" />
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Composition Name</label>
                                    <input type="text" class="form-control" name="composition_name"
                                        placeholder="Composition Name" value="" />
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Packing Label</label>
                                    <input type="text" class="form-control" name="packing_label"
                                        placeholder="Packing Label" value="" />
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Unit</label>
                                    <select class="form-control" name="unit">
                                        <option selected="" value="">Please Select</option>
                                        @foreach(getUnits() as $key => $unit)
                                        <option value="{{$unit}}">{{$unit}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Pack Unit</label>
                                    <input type="text" class="form-control" name="pack_unit" placeholder="Pack Unit"
                                        value="" />
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Weight</label>
                                    <input type="text" class="form-control" name="weight" placeholder="Weight"
                                        value="" />
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control" name="price" placeholder="Price" value="" />
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>RX Required<span>*</span></label>
                                    <select class="form-control" name="rx_req">
                                        <option selected="" value="">Please Select</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>is Banned<span>*</span></label>
                                    <select class="form-control" name="banned">
                                        <option selected="" value="">Please Select</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-sm-12 form-group">
                                    <div class="reset-button">
                                        <button type="reset" class="btn btn-warning">Reset</button>
                                        <button type="submit" class="btn btn-success submit">Save</button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>

    <script src="{{ URL::asset('js/bootstrap.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
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
    // $(document).on("click", "[name='manufacturer']", function () {
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
        $("[name='manufacturer']").prop('selectedIndex', 0);
        $("[name='manufacturer']").show();
    });
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
                pack_in: "required",
                rx_req: "required",
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
                element.closest('.form-group').find('.help-block').append(error);
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
    jQuery(document).on("keyup", ".drugSearchNew", function() {
        var currSearch = this;
        jQuery.ajax({
            type: "POST",
            url: "{!! route('admin.searchMedicine') !!}",
            data: {
                'searchText': jQuery(this).val()
            },
            beforeSend: function() {
                jQuery(currSearch).css("background",
                    "#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
            },
            success: function(data) {
                var liToAppend = "";
                if (data.length > 0) {
                    jQuery.each(data, function(k, v) {
                        liToAppend += '<li  value="' + v.id + '" class="dataListtreat">' + v
                            .name + '<img src="' + v.img + '" width="40" alt=""></li>';
                    });
                } else {
                    liToAppend += '<li value="0">"' + jQuery(currSearch).val() +
                        '" Drug Not Found.</li>';
                    liToAppend +=
                        '<li><a href="javascript::void(0)" data-toggle="modal" data-event="this" data-target="#addDrugModel"> Add "' +
                        jQuery(currSearch).val() + '" Drug as new Drug.</a></li>';
                }
                jQuery(currSearch).closest(".manufacturerSearchDiv").find(".suggesstion-box")
                    .show();
                jQuery(currSearch).closest(".manufacturerSearchDiv").find(".suggesstion-box").html(
                    '<ul>' + liToAppend + '</ul>');
                //  jQuery(currSearch).css("background","#FFF");
            }
        });
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
                        liToAppend += '<li  value="' + v.name + '" class="dataList">' + v
                            .name + '</li>';
                    });
                } else {
                    liToAppend += '<li value="0">"' + jQuery(currSearch).val() +
                        '" Manufacturer Not Found.</li>';
                    liToAppend += '<li><a href="javascript::void(0)" class="dataList"> Add "' +
                        jQuery(currSearch).val() + '" as new Manufacturer.</a></li>';
                }
                jQuery(currSearch).closest(".manufacturerSearchDiv").find(".suggesstion-box")
                    .show();
                jQuery(currSearch).closest(".manufacturerSearchDiv").find(".suggesstion-box").html(
                    '<ul>' + liToAppend + '</ul>');
            }
        });
    });
    jQuery(document).on("click", ".wrapper", function() {
        jQuery(this).find(".suggesstion-box").hide();
        jQuery(this).find(".suggesstion-box ul").remove();
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