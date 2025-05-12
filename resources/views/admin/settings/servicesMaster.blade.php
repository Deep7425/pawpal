@extends('layouts.admin.Masters.Master')
@section('title', 'Services Master')
@section('content')
 
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
        <div class="container-fluid flex-grow-1 container-p-y">
        <!-- <h4 class="font-weight-bold py-3 mb-0">Service Master</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Admin</a></li>
                                <li class="breadcrumb-item active"><a href="#!">Service Master</a></li>
                            </ol>
                        </div> -->
        <div class="row mb-2 form-top-row">
         
              <div class="btn-group">
                 <a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i>  Add Service </a>
               </div>
					  <div class="btn-group">
               <a class="btn btn-success" href="javascript:void();">{{$services->total()}}</a>
            </div>

        
            <div class="btn-group head-search">
              <div class="col-sm-3">
                  {!! Form::open(array('route' => 'admin.servicesMaster', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                  <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                      <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                      <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
                      <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
                      <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
                    </select>
               </div>

               <div class="col-sm-5">
                  <select class="form-control" name="search">
                    <option value="">Select Key</option>
                      @if($services2->count() > 0)
                        @foreach($services2 as $index => $row)
                        <option value="{{$row->id}}"  @if(isset($_GET['search']))@if(base64_decode($_GET['search']) == $row->id) selected @endif @endif>{{$row->key}}</option>
                        @endforeach
                      @endif
                  </select>
              </div>

              <div class="col-sm-2"> 
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
              <div class="layout-content">
                    <div class="table-responsive plan-master">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Key</th>
                                        <th>Value</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if(count($services) > 0)
								@foreach($services as $index => $row)
                                    <tr>
										<td>
											<label>{{$index+($services->currentpage()-1)*$services->perpage()+1}}.</label>
										</td>
                    <td>{{$row->key}}</td>
										<td>
                      
                      @foreach(explode(",",$row->value) as $index => $val)
                      <code>{{$val}}</code>
                      @endforeach
                    </td>
										<td>
											<button onclick="editService({{$row->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
										</td>
									</tr>
								@endforeach
								@else
									<tr><td colspan="3">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
            </div>
            <div class="page-nation text-right d-flex justify-content-end mt-2 mb-2">
                <ul class="pagination pagination-large">
                  {{ $services->appends($_GET)->links() }}
              </ul>
            </div>
        </div>
      </div>

      <div class="modal fade add-service" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Add Service</h4>
      </div>
      <div class="modal-body feedback">
        {!! Form::open(array('id' => 'addServicesMaster','name'=>'addServicesMaster', 'enctype' => 'multipart/form-data')) !!}
        <div class="row">
        <input type="hidden" name="type" value="1">
        <div class="form-group col-md-6">
          <label>Key</label>
          <select class="form-control serviceKey" name="key">
            <option value="">Select Key</option>
              @if($services->count() > 0)
                @foreach($services as $index => $row)
                <option value="{{$row->id}}">{{$row->key}}</option>
                @endforeach
              @endif
            <option value="addNew">Add New Key</option>
          </select>
          <input value="" type="text" name="new_key" class="form-control addNewKey" placeholder="Enter New Key" style="display: none">
          <span class="help-block"></span>
        </div>
         <div class="form-group col-md-6">
          <label>Value</label>
          <input value="" type="text" name="value" class="form-control" placeholder="Enter Value">
          <span class="help-block"></span>
        </div>
        <div class="form-group col-md-12">
          <div class="reset-button">
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-success submit" id="submit-btn">Submit</button>
          </div></div>
          {!! Form::close() !!}
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<div class="modal fade" id="EditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>






<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script>

  // $('#AddModal').on('hidden.bs.modal', function () {
   // location.reload();
  // });

$(document.body).on('change', '.serviceKey', function(){
  if (this.value == 'addNew') {
    $('.addNewKey').show();
    $('.serviceKey').hide();
  }
  else {
     $('.serviceKey').show();
     $('.addNewKey').hide();
  }
});
$(document.body).on('click', '.submit', function(){
		// jQuery("#modifySubAdmin").validate({
		 jQuery("form[name='addServicesMaster']").validate({
			rules: {
        key: "required",
        new_key: "required",
        value: "required",
			 },
			messages:{
			},
			errorPlacement: function(error, element){

				error.appendTo(element.parent().find('.help-block'));
			},ignore: ":hidden",
			submitHandler: function(form) {
				$(form).find('.submit').attr('disabled',true);
        jQuery('.loading-all').show();
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.addServicesMaster')!!}",
					data:  new FormData(form),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data) {
						 if(data==1)
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
							location.reload();
						 }
             else if (data==0) {
               alert("key already exists");
               jQuery('.loading-all').hide();
                $(form).find('.submit').attr('disabled',false);
             }
						 else
						 {
						  jQuery('.loading-all').hide();
						  // $(form).find('.submit').attr('disabled',false);
						  alert("Oops Something Problem");
						 }
					},
          error: function(error)
          {
              jQuery('.loading-all').hide();
              alert("Oops Something goes Wrong.");
          }
				});
			}
		});
	});
function editService(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editServices')!!}",
    data:{'id':id},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#EditModal").html(data);
      jQuery('#EditModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
}


function chnagePagination(e) {
	$("#chnagePagination").submit();
}



</script>
@endsection
