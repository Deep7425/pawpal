@extends('layouts.admin.Masters.Master')
@section('title', 'Referral Code Master')
@section('content')
    <!-- Content Wrapper. Contains page content -->




<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y scroll"> 
             
                 <div class="row form-top-row">
                               <div class="btn-group mr-1">
                                        <a class="btn btn-success" href="{{ route('admin.addReferral') }}"> <i class="fa fa-plus"></i>  Add Referral Code</a>
                                    </div>
									                  <div class="btn-group">
                                        <a class="btn btn-success" href="javascript:void();">{{$referrals->total()}}</a>
                                    </div>  

                <div class="btn-group head-search">
                   <div class="">
											{!! Form::open(array('route' => 'admin.referralMaster', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
											<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												</select>
                     </div>
                     
                     <div class="mar-r5 mar-l5">
                              <label class="btn-primary btn">Organization :</label>
                          </div>
                          <div class="">
                              <select class="form-control" name="organization_id">
                                  <option value="">Select</option>
                                  @foreach(getOrganizations() as $raw)
                                      <option value="{{$raw->id}}" @if((app('request')->input('organization_id'))!='') @if(base64_decode(app('request')->input('organization_id')) == $raw->id) selected @endif @endif>{{$raw->title}}</option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="mar-r5 mar-l5">
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="Search By Title" value="{{ old('search') }}"/>
												</div>
											</div>

                      <div class="">
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

                <div class=" table-responsive plan-master">
                 <table class="table table-bordered table-hover">
                                <thead>
                                  <tr>
                    	            <th style="width:70px;">S.No.</th>
                    	            <th>Title</th>
                                      <th>Organization</th>
                                    <th>Discount Type</th>
									<th>Discount</th>
									<th>Referral Code</th>

									<th>Expire Date</th>
									<th>Note</th>
									<th>Status</th>
                  <th>Created By</th>
                  <th style="width: 115px;">Action</th>
                    	         </tr>
                                </thead>
                                <tbody>
                                 @if($referrals->count() > 0)
                                 @foreach($referrals as $index => $row)
                                   <tr>
                                   <td>{{$index+($referrals->currentpage()-1)*$referrals->perpage()+1}}.</td>
                                   <td>{{$row->title}}</td>
                                       <td>@if($row->org_id == 0) User @else {{getOrganizationIdByName($row->org_id)}}@endif</td>
                                   <td>@if($row->referral_discount_type == "1") ₹ @elseif($row->referral_discount_type == "2") % @endif</td>
                                   <td>{{$row->referral_discount}}</td>
                                   <td>{{$row->code}}</td>
                                   <td>{{date('d-m-Y', strtotime($row->code_last_date))}}</td>
                                   <td>{!!$row->other_text!!}</td>
                                   <td>
                                     <button class="btn btn-default update_status" id="{{$row->id}}" status="{{$row->status}}" type="button">@if($row->status == 0) Inactive @else Active @endif </button>
                                   </td>
                              <td>{{@$row->admin->name}}</td>

                                   <td style="width: 115px;">
                      	<a href="{{route('admin.editReferral', ['id' => base64_encode($row->id)])}}" title="Edit Referral code Details" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a href="{{route('admin.deleteReferralMaster', ['id' => base64_encode($row->id)])}}" title="Delete Referral code" onclick="if(confirm('Are You Sure?')){return true;}else{return false;}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash" aria-hidden="true"></i></a>
                          	</td>
                                 </tr>
                                 @endforeach
                                 @else
                                      <tr><td colspan="10">No Record Found </td></tr>
                                 @endif
                               </tbody>
							</table>
              </div>
		
                </div>
                <div class="page-nation text-right d-flex justify-content-end mt-2 mb-2 ">
				<ul class="pagination pagination-large">
					{{ $referrals->appends($_GET)->links() }}
				</ul>
			</div>
            
            </div>
       </div>
    </div>
</div>


 <!-- /.content-wrapper -->
<script type="text/javascript">
jQuery(document).ready(function () {
  jQuery(".update_status").on('click',function(){
      if(confirm('Are you sure want to change status?')){
        jQuery('.loading-all').show();
        jQuery(this).attr('disabled',true);
        var id =  $(this).attr('id');
        var status =  $(this).attr('status');
        var btn = this;
        jQuery.ajax({
          type: "POST",
          url: "{!! route('admin.updateReferralStatus')!!}",
          data: {"_token":"{{ csrf_token() }}",'id':id,'status':status},
          success: function(data){
            jQuery(btn).attr('disabled',false);
            jQuery('.loading-all').hide();
             if(data==1){
               jQuery(btn).text("Active");
               $(btn).attr('status','1');
             }
             else if(data==2){
               jQuery(btn).text("Inactive");
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
      }
  });
});
function chnagePagination(e) {
	$("#chnagePagination").submit();
}
</script>
@endsection
