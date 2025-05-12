@extends('layouts.admin.Masters.Master')
@section('title', 'AU Marathon Registrations')
@section('content')
    <!-- Content Wrapper. Contains page content -->

  <div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">
              
            <div class="row form-top-row">
               <div class="btn-group">
                    <a class="btn btn-success" href="javascript:void();">{{$registrations->total()}}</a>
               </div>

               <div class="btn-group head-search">
               <div class="">
											{!! Form::open(array('route' => 'admin.AuMarathonReg', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
												<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												</select>
                     </div>

                     <div class="ml-sm-2">
										<select  class="form-control" name="t_status">
										<option value="">Type</option>
											<option value="0" @if(isset($_GET['t_status'])) @if(base64_decode($_GET['t_status']) == '0') selected @endif @endif>Pending</option>
											<option value="1" @if(isset($_GET['t_status'])) @if(base64_decode($_GET['t_status']) == '1') selected @endif @endif>Done</option>
										</select>
										</div>

                    <div class=" ml-sm-2">
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="search by title" value="{{ old('search') }}"/>
													
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
										{!! Form::close() !!}

               </div>
            </div>

               <div class="layout-conten">

               <div class="table-responsive plan-master">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>E-Mail</th>
                                        <th>Mobile No.</th>
                                        <th>Date of Birth*</th>
                                        <th>Gender</th>
                                        <th>T-shirt Size</th>
										                    <th>T-shirt Status</th>
                                        <th>Registration Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @if($registrations->count() > 0)
                                  @foreach($registrations as $index => $user)
                                    <tr>
                                    <td>
                                      <label>{{$index+($registrations->currentpage()-1)*$registrations->perpage()+1}}.</label>
                                    </td>
                                    <td>{{$user->name}}</td>
                                    <td>{{@$user->email}}</td>
                                    <td>{{@$user->mobile_no}}</td>
                                    <td>{{date('d-M-Y', strtotime($user->dob))}}</td>
                                    <td>{{@$user->gender}}</td>
                                    <td>{{@$user->t_shirt_size}}</td>
                                    <td>
                                    <button class="btn btn-primary update_status" id="{{$user->id}}" status="{{$user->t_status}}" type="button">@if($user->t_status == 0) Pending @else Done @endif </button>
                                    </td>
                                    <td>{{date('d-M-Y', strtotime($user->created_at))}}</td>
									   </tr>
							    	@endforeach
						     		@else
									<tr><td colspan="8">No Record Found </td></tr>
								@endif
								</tbody>
							</table>

						</div>

		
               </div>
             	<div class="page-nation text-right d-flex  justify-content-end mb-2 mt-2">
				<ul class="pagination pagination-large">
					{{ $registrations->appends($_GET)->links() }}
				</ul>
			</div>
            </div>
        </div>
     </div>
  </div>


<!-- /.content-wrapper -->

<script>
function chnagePagination(e) {
	$("#chnagePagination").submit();
}

jQuery(document).ready(function () {
  jQuery(".update_status").on('click',function(){
      // if(confirm('Are you sure want to change status?')){
        jQuery('.loading-all').show();
        jQuery(this).attr('disabled',true);
        var id =  $(this).attr('id');
        var status =  $(this).attr('status');
        var btn = this;
        jQuery.ajax({
          type: "POST",
          url: "{!! route('admin.updateTshirtStatus')!!}",
          data: {"_token":"{{ csrf_token() }}",'id':id,'status':status},
          success: function(data){
            jQuery(btn).attr('disabled',false);
            jQuery('.loading-all').hide();
             if(data==1){
               jQuery(btn).text("Done");
               $(btn).attr('status','1');
             }
             else if(data==2){
               jQuery(btn).text("Pending");
               $(btn).attr('status','0');
             }
             else{
                 alert("System Problem");
             }
           },
           error: function(error){
             jQuery(btn).attr('disabled',false);
             jQuery('.loading-all').hide();
             alert("Oops Something goes Wrong.");
           }
        });
      // }
  });
});
</script>
@endsection
