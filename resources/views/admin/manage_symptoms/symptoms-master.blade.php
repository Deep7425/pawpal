@extends('layouts.admin.Masters.Master')
@section('title', 'Syamptoms Master')
@section('content')
<!-- Content Wrapper. Contains page content -->



<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y appointment-master notification symptoms-page">
                
                <div class="row mb-2 form-top-row">
                    <div class="btn-group">
                        <a class="btn btn-success" href="{{ route('symptoms.addSymptoms') }}"> <i
                                class="fa fa-plus"></i> Add Symptoms</a>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$symptoms->total()}}</a>
                    </div>

                   
                    <div class="btn-group head-search" style="width:50%;">
                        <div class="btn-group col-sm-2">

                            {!! Form::open(array('route' => 'symptoms.SymptomsMaster', 'id' => 'chnagePagination',
                            'method'=>'POST')) !!}
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


                        <div class="search-sec">
                            <div class="custom-search-form symptom-search-box">
                                <input style="width:45%;" name="search" type="search" class="col-sm-5 form-control capitalizee"
                                    placeholder="Search By Symptom" value="{{ old('search') }}" />
                                <select class="col-sm-5 form-control capitalizee" name="spaciality">
                                    <option value="">All</option>
                                    @foreach(getSpecialityList() as $index => $spaciality)
                                    <option value="{{$spaciality->id}}" @if($spaciality->id == old('spaciality'))
                                        selected @endif>{{$spaciality->specialities}}</option>
                                    @endforeach
                                </select>
                                <div class="search-btn">
                                    <div class="input-group custom-search-form">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="submit">
                                                SEARCH
                                            </button>
                                        </span>

                                    </div><!-- /input-group -->
                                    {!! Form::close() !!}
                                </div>
                            </div>


                        </div>

                    </div>
                </div>

                <div class="layout-content ">

                    <div class="table-responsive table-container">
                        <table class="table table-bordered table-hover">
                            <thead>
                                    <tr>
                                    <th>S.No.</th>
                                    <th>Spaciality</th>
                                    <th>Symptoms Name</th>
                                    <th>Disease</th>
                                    <th>status</th>
                                    <th>Action</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @if($symptoms->count() > 0)
                                @foreach($symptoms as $index => $symp)
                                <tr>
                                    <td>
                                        <label>{{$index+($symptoms->currentpage()-1)*$symptoms->perpage()+1}}.</label>
                                    </td>
                                    <td>
                                        @if(count($symp->SymptomsSpeciality) >0 )
                                        @foreach($symp->SymptomsSpeciality as $spaciality)
                                        {{@$spaciality->Speciality->specialities}}</br>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>{{$symp->symptom}}</td>
                                    <td>{{$symp->disease}}</td>
                                    <td><span
                                            class="label-default label @if($symp->status == '1') label-success @else label-danger @endif">@if($symp->status
                                            == '1') Active @else Inactive @endif</span></td>
                                    <td>
                                        <button onclick="editSymtoms({{$symp->id}});" class="btn btn-info btn-sm"
                                            data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></button>
                                        <button onclick="deleteSymtoms({{$symp->id}});" class="btn btn-danger btn-sm"
                                            data-toggle="tooltip" data-placement="right" title="Delete "><i
                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6">No Record Found </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="page-nation">
                    <ul class="pagination pagination-large">
                        {{ $symptoms->appends($_GET)->links() }}
                        <!--<li class="disabled"><span>«</span></li>
					<li class="active"><span>1</span></li>
					<li><a href="#">2</a></li>
					<li class="disabled"><span>...</span></li><li>
					<li><a rel="next" href="#">Next</a></li> -->
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="symtomsEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- /.content-wrapper -->
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script>
jQuery(document.body).ready(function() {


});

function editSymtoms(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('symptoms.editSymptoms')!!}",
        data: {
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#symtomsEditModal").html(data);
            jQuery('#symtomsEditModal').modal('show');
            setTimeout(function() {
                $('#exampleSelect1').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
            }, 100);
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}

function deleteSymtoms(id) {
    if (confirm('Are you sure want to delete?') == true) {
        jQuery('.loading-all').show();
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{!! route('symptoms.deleteSymptoms')!!}",
            data: {
                'id': id
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

function chnagePagination(e) {
    $("#chnagePagination").submit();
}
</script>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>

@endsection