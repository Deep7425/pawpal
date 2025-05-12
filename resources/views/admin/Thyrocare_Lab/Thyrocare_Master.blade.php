@extends('layouts.admin.Masters.Master')
@section('title', 'Thyrocare Labs')
@section('content')


<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">

            <div class="container-fluid flex-grow-1 container-p-y">
			<div class="row mb-2 ml-1 form-top-row">
			<form class="" action="{{route('admin.thyrocareLab')}}" method="post">

@csrf
  <meta name="csrf-token" content="{{ csrf_token() }}">
<div class="head-search-sm mar-0 col-md-3">

		<div class="custom-search-form symptom-search-box">
		  <input name="search" type="search" class="col-sm-5 form-control capitalizee" placeholder="Search By Title" />
		</div>
	</div>
	<div class="head-search-btn mar-l0">

		<div class="custom-search-form">
		  <span class="input-group-btn">
			<button class="btn btn-primary" type="submit">
			  SEARCH
			</button>
		  </span>
	  </div>
	</div>

</form>	</div>
		
		     	<div class="layout-content ">       


				 <div class="table-responsive table-container">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th style="width:70px;">S.No.</th>
								<th>Name</th>
								<th>Common Name</th>
                <th>Image</th>
								<th>Code</th>
								<th>Type</th>
								<th>Rate</th>
								<th>Test Count</th>
								<th>Pay-Type</th>
                <th>Margin</th>
                <th>Test Name</th>

								<th style="width:85px;">Action</th>
							</tr>
						</thead>
						<tbody>
              @foreach ($labs as $key => $data)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->common_name}} </td>
                    <?php $imgLocations = @$data->imageMaster[0]['imgLocations']; ?>
                    <?php $rate = @$data->rate['offerRate']; ?>

                    <td>
                    @if(isset($data->imageMaster))
      <img src="{{$imgLocations}}" alt="" width="50" height="50"/>
   @elseif(isset($data->imgLocations))
    <img src="{{$data->imgLocations}}" alt="" width="50" height="50"/>
   @else
       <img src="{{asset('img/empty.jpg')}}" alt="" width="50" height="50"/>
   @endif
 </td>
                    <td>{{$data->code}}</td>
                    <td>{{$data->type}}</td>
                    <td>{{$rate}}</td>
                    <td>{{$data->testCount}}</td>
                    <td>{{$data->payType}}</td>
                    <td>{{$data->margin}}</td>
                    <td>{{$data->testNames}} </td>
                    <td>	<button onclick="editLab('{{$data->id}}');" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
    									<!-- <button onclick="deleteLab({{$data->id}});" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></button></td> -->
                  </tr>
               @endforeach
						</tbody>
					</table>
				</div>
			

			    </div>
				<div class="page-nation text-right d-flex justify-content-end mt-2 mb-2 mr-1">
				<ul class="pagination pagination-large">
        	{{ $labs->appends($_GET)->links() }}
				</ul>
			</div>
		   </div>
        </div>
     </div>
</div>

<div class="modal fade" id="editLabModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->

<!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->


<script type="text/javascript">


function editLab(id) {
	jQuery('.loading-all').show();
	jQuery.ajax({
	type: "POST",
	dataType : "HTML",
	url: "{!! route('admin.ThyrocareLab.edit')!!}",
	data: {"_token": "{{ csrf_token() }}", 'id': id},
	success: function(data) {
	  jQuery('.loading-all').hide();
	  jQuery("#editLabModal").html(data);
	  jQuery('#editLabModal').modal('show');
		console.log(data);
	},
	error: function(error) {
		location.reload();
		jQuery('.loading-all').hide();
		alert("Oops Something goes Wrong.");
	}
  });
}

function deleteLab(id) {
if(confirm('Are you sure want to delete?') == true){
	jQuery('.loading-all').show();
	jQuery.ajax({
	type: "POST",
	dataType : "JSON",
	url: "{!! route('admin.ThyrocareLab.delete')!!}",
	data:{'id':id},
	success: function(data) {
	 if(data==1){
	  location.reload();
	 }
	 else {
	  alert("Oops Something Problem");
	 }
	jQuery('.loading-all').hide();
	},
	error: function(error)
	{
		jQuery('.loading-all').hide();
		alert("Oops Something goes Wrong.");
	}
  });
}
}
jQuery(document).ready(function(){
jQuery("form[name='addLab']").validate({
	rules: {
		title: {required:true,maxlength:255},
		short_name: {required:true,maxlength:255},
	 },
	messages:{
	},
	errorPlacement: function(error, element){
		error.appendTo(element.next());
	},ignore: ":hidden",
	submitHandler: function(form) {
		$(form).find('.submit').attr('disabled',true);
		jQuery('.loading-all').show();
		jQuery.ajax({
			type: "POST",
			dataType : "JSON",
			url: "{!! route('admin.defLab.create')!!}",
			data:  new FormData(form),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data) {
				 if(data==1){
					jQuery('.loading-all').hide();
					$(form).find('.submit').attr('disabled',false);
					// location.reload();
				 }
				 else {
				  jQuery('.loading-all').hide();
				  $(form).find('.submit').attr('disabled',false);
				  alert("Oops Something Problem");
				 }
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
