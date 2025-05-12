<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Update Camp Data</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag camp-data">
				<div class="">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.campMaster') }}"> <i class="fa fa-list"></i>Camp List</a>
					</div>
				</div>
				<div class="panel-body" style="padding-top:10px;"> 
					{!! Form::open(array('name'=>'updateCamp')) !!}
					
					<div class="row">
						<input type=hidden value="{{@$camp->id}}" name="id"/>
					<input type=hidden value="{{@$camp->user->id}}" name="user_id"/>
					<div class="form-group col-sm-6">
  						<label>First Name</label>
  						<input type="text" name="first_name" class="form-control" placeholder="Enter First Name" value="{{@$camp->user->first_name}}">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>Last Name</label>
  						<input type="text" name="last_name" class="form-control" placeholder="Enter Last Name" value="{{@$camp->user->last_name}}">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>Email</label>
  						<input type="text" name="email" class="form-control" placeholder="Enter Email"  value="{{@$camp->user->email}}">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>Mobile No</label>
  						<input type="text" name="mobile_no" class="form-control" placeholder="Enter Mobile No" value="{{@$camp->user->mobile_no}}">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>ThyroCare Order Number</label>
  						<input type="text" name="thy_ref_order_no" class="form-control" placeholder="Enter ThyroCare Order Number" value="{{@$camp->thy_ref_order_no}}">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>ThyroCare Lead Id</label>
  						<input type="text" name="thy_lead_id" class="form-control" placeholder="ThyroCare Lead Id" value="{{@$camp->thy_lead_id}}">
  						<span class="help-block"></span>
  					</div>
					<div class="form-group col-sm-6">
  						<label>Camp Title</label>
						<select class="form-control camp_idF" name="camp_id">
							<option value="">Select Camp Title</option>
							@if(count(getCampTitleMaster())>0)
								@foreach(getCampTitleMaster() as $val)
									<option value="{{$val->id}}" @if($camp->camp_id == $val->id) selected @endif>{{$val->title}}</option>
								@endforeach	
							@endif	
							<option value="0">Other</option>
						</select>
  						<span class="help-block"></span>
  					</div>
					<div class="form-group other_titleF col-sm-6" style="display:none;">
  						<label>Other Name of Camp Title</label>
  						<input type="text" name="other_title" class="form-control" placeholder="Name of Camp Title">
  						<span class="help-block"></span>
  					</div>
					
					<div class="reset-button col-sm-12">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success update">Update</button>
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
<script type="text/javascript">

$(document.body).on('click', '.update', function(){
		 jQuery("form[name='updateCamp']").validate({
			rules: {
				 first_name: "required",
				 last_name: "required",
				 email: "required",
				 mobile_no: "required",
				 thy_ref_order_no: "required",
				 thy_lead_id: "required",
				 camp_id: "required",
				 other_title: "required",
			 },
			messages:{
			},
			errorPlacement: function(error, element){

				error.appendTo(element.parent().find('.help-block'));
			},ignore: ":hidden",
			submitHandler: function(form) {
				$(form).find('.update').attr('disabled',true);
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.updateCamp')!!}",
					data:  new FormData(form),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data) {
						 if(data==1)
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.update').attr('disabled',false);
							location.reload();
						 }
						 else
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.update').attr('disabled',false);
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
$(document.body).on('change', '.camp_idF', function(){
	if($(this).val() == '0'){
		$(".other_titleF").show();
	}
	else{
		$(".other_titleF").hide();
	}
});

$('.btn-default').click(function() {
    $('.modal').modal('hide');
});

$('.close').click(function() {
    $('.modal').modal('hide');
});
</script>
