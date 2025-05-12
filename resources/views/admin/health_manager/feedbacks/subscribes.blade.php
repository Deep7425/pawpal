@extends('layouts.admin.Masters.Master')
@section('title', 'Subscribe List')
@section('content')


<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
        
     
              <div class="container-fluid flex-grow-1 container-p-y">
              <div class = "row mb-2">
            <div class="col-sm-3">
                <div class="btn-group">
                   <a class="btn btn-success" href="javascript:void();">{{$subscribes->total()}}</a>
               </div>
            </div>
        </div>
              <div class="layout-content card" >

              <div class="row mt-2 ml-1 ">
                <div class="col-sm-3">
                <div class="dataTables_length">
											   <label>Paginate By </label>
											   {!! Form::open(array('route' => 'admin.subcribedAll', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
												<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
													<option value="300" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '300') selected @endif @endif>300</option>
												</select>
                                            </div>
                         </div>

                                       <div class="col-sm-3">
                                              <div class="dataTables_length">
                                                <label>E-Mail </label>
                                                <input name="search" type="text" class="form-control capitalizee" placeholder="email" value=" @if(isset($_GET['search'])) {{base64_decode($_GET['search'])}}  @endif">
                                              </div>
                                            </div>

                                            <div class="col-sm-2">
                                            <div class="dataTables_length">
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
									<tr><td colspan="8">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
                        <div class="page-nation text-right d-flex justify-content-end mb-2">
				<ul class="pagination pagination-large">
					{{ $subscribes->appends($_GET)->links() }}
				</ul>
			</div>
              </div>
          </div>
        </div>
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
    dataType : "HTML",
    url: "{!! route('admin.viewFeedback')!!}",
    data:{'id':id},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#viewModal").html(data);
      jQuery('#viewModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
}



</script>
@endsection
