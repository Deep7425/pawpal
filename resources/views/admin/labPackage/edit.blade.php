<div class="modal-dialog modal-dialog111">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title"> Labs Package</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="" style="padding-bottom:15px;">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.labPackage.index') }}"> <i class="fa fa-list"></i>   Package Labs List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'editLab','name'=>'admin.labCollection.edit')) !!}
					<div class="row">
					<input type=hidden value="{{$lab->id}}" name="id"/>
					<div class="col-md-6 pad-left0">
					<div class="form-group">
						<label>Company Name</label>
						<select name="company_id" class="form-control company_id">
							<option value="">Select Company</option>
							@forelse(getLabCompanies() as $raw)
							<option value="{{$raw->id}}" @if($raw->id == $lab->company_id) selected @endif>{{$raw->title}}</option>
							@empty
							@endforelse
						</select>
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group labDropDown">
						<?php $labIds = explode(",",$lab->lab_id);?>
						<label>Lab</label>
						<select class="form-control labSearch selectpicker itempicker" id="exampleSelect2" name="lab_id[]" data-show-subtext="true" data-live-search="true" multiple>
						@forelse($labs as $raw)
							<option value="{{$raw->id}}" @if(in_array($raw->id,$labIds)) selected @endif>{{$raw->DefaultLabs->title}}</option>
						@empty
						@endforelse
						</select>
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label>Title</label>
						<input name="title" value="{{$lab->title}}"  type="title" class="form-control labSearch" placeholder="Enter Title"/>
						<span class="help-block"></span>
						<div class="suggesstion-box" style="display:none;"></div>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label>Price</label>
						<input value="{{$lab->price}}" type="text" name="price" class="form-control" placeholder="Enter Price" />
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label>Discounted Price</label>
						<input value="{{$lab->discount_price}}" type="text" name="discount_price" class="form-control" placeholder="Enter Discounted Price" />
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label>Image</label>
						<input type="file" name="image" class="form-control" />
						<input type="hidden" name="old_image" class="form-control" value="{{$lab->image}}"/>
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label style="width:100%;">Existing Image</label>
						<img src="{{url("/")}}/public/lab-package-icon/{{$lab->image}}" height="50" width="50"/>
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-12">
						<div class="reset-button">
						   <button type="reset" class="btn btn-warning">Reset</button>
						   <button type="submit" class="btn btn-success submit">Update</button>
						</div>
					</div></div>
				 {!! Form::close() !!}
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>


<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {
setValue();
});

function setValue(){
	$('#exampleSelect2').multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
	});
}


// When the browser is ready...
jQuery(document).ready(function () {
$(document.body).on('click', '.submit', function(){
		jQuery("#editLab").validate({
		rules: {
			company_id: {required:true},
			title: {required:true},
			price: {required:true,number:true},
			discount_price: {required:false,number:true},
		},
		// Specify the validation error messages
		messages: {
		},
		errorPlacement: function(error, element) {
		  error.appendTo(element.next());
		},
		submitHandler: function(form) {
			jQuery('.loading-all').show();
			jQuery('.submit').attr('disabled',true);
			jQuery.ajax({
				type: "POST",
				dataType : "JSON",
				url: "{!! route('admin.labPackage.update')!!}",
				data:  new FormData(form),
				contentType: false,
				cache: false,
				processData:false,
				success: function(data) {
					 if(data==1) {
						jQuery('.submit').attr('disabled',false);
						location.reload();
					 }
					 else {
						alert("System Problem");
					 }
					 jQuery('.submit').attr('disabled',false);
					 jQuery('.loading-all').hide();
				 },
				 error: function(error){
					 jQuery('.submit').attr('disabled',false);
					 jQuery('.loading-all').hide();
					 alert("Oops Something goes Wrong.");
				 }
			});
		}
	});
  });
});
jQuery(document).on("change", ".company_id", function () {
	var company_id = $(this).val();
	console.log(company_id);
	getLabsByCompany(company_id);
});
function getLabsByCompany(company_id) {
var company_id = jQuery(".company_id").val();
  clinicSearchRequest = jQuery.ajax({
  type: "POST",
  url: "{!! route('getLabByCompany') !!}",
  data: {'company_id':company_id},
  success: function(response){
	  var liToAppend = "";
		if(response.length > 0){
		  jQuery.each(response,function(k,v) {
			 var title = null;
			 var short_name = null;
			 if(v.default_labs.title){
				title = v.default_labs.title;
			 }
			 if(v.default_labs.short_name){
				short_name = v.default_labs.short_name;
			 }
			liToAppend += '<option value="'+v.id+'" class="dataLabList">'+title+' '+short_name+'</option>';
		  });
		}else{
			liToAppend += '<option value="0">Lab Not Found.</option>';
	  }
	  $(".labDropDown").find(".selectpicker:first").html('');
	  $(".labDropDown").find(".selectpicker:first").html(liToAppend);
	  $("#exampleSelect2").multiselect('destroy');
	  setValue();
  }
  });
}
</script>
