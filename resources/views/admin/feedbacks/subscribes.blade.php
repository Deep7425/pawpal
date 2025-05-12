@extends('layouts.admin.Masters.Master')
@section('title', 'Subscribe List')
@section('content')
<!-- Content Wrapper. Contains page content -->



<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">

                <div class="row ml-1 form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$subscribes->total()}}</a>
                    </div>

                    {!! Form::open(array('route' => 'admin.subcribedAll', 'id' => 'chnagePagination', 'method'=>'POST'))
                    !!}

                    <div class="btn-group head-search">

                        <div class="ml-sm-2">
                            <!-- <label>Paginate By </label> -->

                            <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                                <option value="25" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='25' ) selected @endif @endif>25</option>
                                <option value="50" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='50' ) selected @endif @endif>50</option>
                                <option value="100" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='100' ) selected @endif @endif>100</option>
                                <option value="300" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='300' ) selected @endif @endif>300</option>
                            </select>
                        </div>

                        <div class=" ml-sm-2">
                     
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


                <div class="layout-content ">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>E-Mail</th>
                                    <th>Date</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if($subscribes->count() > 0)
                                @foreach($subscribes as $index => $element)
                                <tr>
                                    <td>
                                        <label>{{$index+($subscribes->currentpage()-1)*$subscribes->perpage()+1}}.</label>
                                    </td>

                                    <td>{{$element->email}}</td>
                                    <td>{{date('d M Y', strtotime($element->created_at))}}</td>

                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8">No Record Found </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
                    <ul class="pagination pagination-large">
                        {{ $subscribes->appends($_GET)->links() }}
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal md-effect-1 md-show" id="viewModal" role="dialog" data-backdrop="static" data-keyboard="false">
    </div>


</div>







<script>
function chnagePagination(e) {
    $("#chnagePagination").submit();
}

function viewFeedback(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.viewFeedback')!!}",
        data: {
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#viewModal").html(data);
            jQuery('#viewModal').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
}
</script>
@endsection