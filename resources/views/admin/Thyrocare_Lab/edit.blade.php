<div class="modal-dialog modal-dialog111">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title"> Lab Thyrocare</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.thyrocareLab') }}"> <i class="fa fa-list"></i>   Thyrocare Labs List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('id' => 'editLab','name'=>'admin.ThyrocareLab.edit')) !!}
					<input type=hidden value="{{$lab->id}}" name="id"/>
					<div class="row">
					<div class="form-group col-md-6">
						<label> Name</label>

						<input name="name" value="{{$lab->name}}"  type="title" class="form-control labSearch" >
						<span class="help-block"></span>
					</div>
					<div class="form-group col-md-6">
						<label>Common Name</label>

						<input name="common_name" value="{{$lab->common_name}}"  type="title" class="form-control labSearch" />
						<span class="help-block"></span>
					</div>

					<div class="col-md-12">
						<div class="reset-button">
						   <button type="reset" class="btn btn-warning">Reset</button>
						   <button type="submit" class="btn btn-success submit">Update</button>
						</div>
					</div>
					</div>
					
					
				 {!! Form::close() !!}
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>


<!-- <script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script>  -->
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->

<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>


<script type="text/javascript">


// When the browser is ready...
jQuery(document).ready(function () {
$(document.body).on('click', '.submit', function(){
		jQuery("#editLab").validate({
		rules: {
			name: {required:true},
			common_name: {required:true},

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
				url: "{!! route('admin.ThyrocareLab.update')!!}",
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
			liToAppend += '<option value="0">'+jQuery(currSearch).val()+'Lab Not Found.</option>';
	  }
	  $(".labDropDown").find(".selectpicker:first").html('');
	  $(".labDropDown").find(".selectpicker:first").html(liToAppend);
	  $("#exampleSelect2").multiselect('destroy');
	  setValue();
  }
  });
}
</script>
