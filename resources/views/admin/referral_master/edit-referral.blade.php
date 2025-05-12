@extends('layouts.admin.Masters.Master') @section('title', 'Edit Referral') @section('content')


<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />


<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y data-list">
                <div class="btn-group  form-top-row">
                    <a class="btn btn-primary" href="{{ route('admin.referralMaster') }}"> <i class="fa fa-list"></i>Referral Code List</a>
                </div>

                <div class="layout-content card mt-2 uer-data-form">
                    {!! Form::open(array('route' => 'admin.updateReferralsMaster', 'id' => 'editReferral','enctype' => 'multipart/form-data' , 'class' => '')) !!}
                    <input type="hidden" value="{{$referral->id}}" name="id" />
                    <input type="hidden" value="{{$referral->term_conditions}}" name="term_conditions" id="otherText" />
                    <div class="row">
                        <div class="col-sm-3 form-group">
                            <label>Title<span>*</span></label>
                            <input type="text" class="form-control" name="title" placeholder="Title" value="{{$referral->title}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Referral Discount Type<span>*</span></label>
                            <select class="form-control" name="referral_discount_type">
                                <option value="">Type</option>
                                <option value="1" @if($referral->referral_discount_type == "1") selected @endif>₹</option>
                                <option value="2" @if($referral->referral_discount_type == "2") selected @endif>%</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Referral Discount<span>*</span></label>
                            <input type="text" class="form-control" name="referral_discount" placeholder="Discount Amount" value="{{$referral->referral_discount}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Referral Code<span>*</span></label>
                            <input type="text" class="form-control" name="code" placeholder="Referral Code" value="{{$referral->code}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group" style="display: none;">
                            <label>Referral Duration Type<span>*</span></label>
                            <select class="form-control" name="referral_duration_type">
                                <option value="">select duration type</option>
                                <option value="d" @if($referral->referral_duration_type == "d") selected @endif>Day</option>
                                <option value="m" @if($referral->referral_duration_type == "m") selected @endif>Month</option>
                                <option value="y" @if($referral->referral_duration_type == "y") selected @endif>Year</option>
                            </select>
                            <span class="help-block"></span>
                        </div>

                        <div class="col-sm-3 form-group">
                                        <label>Organization</label>
                                        <select class="form-control" name="organization_id">
                                            <option value="">Select</option>
                                            @foreach(getOrganizations() as $raw)
                                                <option value="{{$raw->id}}" @if($referral->org_id == $raw->id) selected @endif>{{$raw->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                        <div class="col-sm-3 form-group" style="display: none;">
                            <label>Duration<span>*</span></label>
                            <input type="text" class="form-control" name="referral_duration" placeholder="Referral Duration" value="{{$referral->referral_duration}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Referral Exipre Date:<span>*</span></label>
                            <input type="text" class="form-control datepicker" placeholder="dd-mm-YYYY" name="code_last_date" value="{{$referral->code_last_date}}" autocomplete="off" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>max Uses Per Person</label>
                            <input type="text" class="form-control" name="max_uses" placeholder="max Uses Per Person" value="{{$referral->max_uses}}" />
                            <span class="help-block"></span>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group SelectPlan">
                                <label>Plan</label>
                                <?php
						                    $planIds = [];
						                   if(!empty($referral->plan_ids)){ $planIds = explode(",",$referral->plan_ids); } ?>
                                <select class="form-control" id="exampleSelect1" name="plan_ids[]" size="1" multiple>
                                    @foreach(getGenniePlan() as $index => $raw)
                                    <option value="{{$raw->slug}}" @if(in_array($raw->id,$planIds)) selected @endif>{{$raw->plan_title}} - ({{number_format($raw->price - $raw->discount_price,2)}})</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-sm-3 form-group cappOn">
                      <label>Referral Show In List</label>
                      <select class="form-control applyT" name="is_show">
                        <option value="">Type</option>
                        <option value="1" @if($referral->is_show == "1") selected @endif>Yes</option>
                        <option value="0" @if($referral->is_show == "0") selected @endif>No</option>
                      </select>
                      <span class="help-block"></span>
                    </div>
                        <div class="col-sm-12 form-group">
                            <label>Terms & Conditions</label>
                            <textarea rows="5" cols="5" class="form-control" id="editor" placeholder="Terms & Conditions" name="term_conditions" value="">{{$referral->term_conditions}}</textarea>
                            <span class="help-block"></span>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label>Special Note</label>
                            <textarea rows="5" cols="5" class="form-control" placeholder="Special Note" name="other_text" value="{{$referral->other_text}}">{{$referral->other_text}}</textarea>
                            <span class="help-block"></span>
                        </div>
                        
                    <div class="col-sm-12 form-group">
                      <div class="reset-button">
                          <button type="reset" class="btn btn-warning">Reset</button>
                          <button type="submit" class="btn btn-success submit">Save</button>
                      </div>
                    </div>
					 {!! Form::close() !!}

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>



<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script>
    $(document).ready(function () {
        $("#exampleSelect1").multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".datepicker").datepicker({
            dateFormat: "dd-mm-yy",
        });
    });
    // When the browser is ready...
    jQuery(document).ready(function () {
        jQuery("#editReferral").validate({
            rules: {
                title: "required",
                code: "required",
                referral_duration_type: "required",
                referral_discount: { required: true, number: true, range: [0, 1000] },
                code_last_date: "required",
            },
            // Specify the validation error messages
            messages: {
                title: "Please enter title",
                code: "Please enter code",
                referral_discount: { required: "Please enter amount", number: "Please Enter Valid Number", range: "Please Enter Range(0-100) Value" },
                referral_duration_type: "Please select duration type",
                referral_duration: "Please enter duration",
                code_last_date: "Please enter expire date",
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.next());
            },
            ignore: ":hidden",
            submitHandler: function (form) {
                jQuery(".loading-all").show();
                // alert();
                // $("#otherText").val(editor.getData());
                jQuery(".submit").attr("disabled", true);
                jQuery.ajax({
                    type: "POST",
                    url: "{!! route('admin.updateReferralsMaster')!!}",
                    data: new FormData(form),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        jQuery(".submit").attr("disabled", false);
                        if (data == 1) {
                        alert(data)
                            document.location.href = "{!! route('admin.referralMaster')!!}";
                        } else if (data == 2) {
                            alert("Entered code already exist");
                        } else {
                            alert("System Problem");
                        }
                        jQuery(".loading-all").hide();
                    },
                    error: function (error) {
                        jQuery(".submit").attr("disabled", false);
                        jQuery(".loading-all").hide();
                        alert("Oops Something goes Wrong.");
                    },
                });
            },
        });
    });
    $(function () {
        //$('#row_dim').hide();
        $(".cType").change(function () {
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
                // $('.applyT').attr("disabled", false);
                $("#apply_type").rules("remove");
            }
        });
    });
</script>
<script src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script>
<script>
    // var editor = CKEDITOR.replace('couponText', { height: 200 });

    // jQuery(document).ready(function() {
    CKEDITOR.on("instanceReady", function () {
        $.each(CKEDITOR.instances, function (instance) {
            CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
            CKEDITOR.instances[instance].document.on("paste", CK_jQ);
            CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
            CKEDITOR.instances[instance].document.on("blur", CK_jQ);
            CKEDITOR.instances[instance].document.on("change", CK_jQ);
        });
    });

    function CK_jQ() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    }
    CKEDITOR.config.removePlugins = "maximize";
    var editor = CKEDITOR.replace("editor");
</script>
@endsection
