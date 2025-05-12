<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content ">
		<div class="modal-header" >
			<button type="button" class="close" data-bs-dismiss="modal">×</button>
			<h4 class="modal-title">Update Advertisement Banner</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag adv">
				<div class="">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.adBannerMaster') }}"> <i class="fa fa-list"></i>Advertisement Banner List</a>
					</div>
				</div>
				<div class="panel-body" style="padding-top:10px;">
					{!! Form::open(array('id' => 'updateAdBanner','name'=>'updateAdBanner', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
					<input type=hidden value="{{$banner->id}}" name="id"/>
					<div class="col-md-6">
						<label>Title</label>
						<input value="{{@$banner->title}}" type="text" name="title" class="form-control" placeholder="Enter Title Name"/>
						<span class="help-block"></span>
					</div>
					
						<div class="col-md-6">
						<label>Image</label>
						<input value="{{@$banner->image}}" type="hidden" name="old_image" class="form-control"/>
						<input type="file" name="image" class="form-control"/>
						<span class="help-block"></span>
						</div>

						<div class="col-md-6">
							@if(!empty($banner->image))
							<label style="width:100%; float:left;">Old Image</label>
							<img src="<?php echo url("/")."/public/adBannerFiles/".$banner->image;?>" class="img-responsive" alt="Banner Image" height="50" width="100" style="text-align:center;">
							@endif
						</div>
					
          <div class="col-md-6">
            <label>Type</label>
              <select class="form-control valid" name="type">
                <option value="">Select Type</option>
                <option value="1" @if(@$banner->type == '1') selected @endif>English</option>
                <option value="2" @if(@$banner->type == '2') selected @endif>Hindi</option>
              </select>
            <span class="help-block"></span>
          </div>
		   <div class="col-md-6">
            <label>Area</label>
              <select class="form-control valid" name="area">
                <option value="">Select Type</option>
                <option value="1" @if(@$banner->area == '1') selected @endif>pop-up</option>
                <option value="2" @if(@$banner->area == '2') selected @endif>Middle</option>
                <option value="3" @if(@$banner->area == '3') selected @endif>Bottom</option>
                <option value="4" @if(@$banner->area == '4') selected @endif>Top</option>
              </select>
            <span class="help-block"></span>
          </div>
          <div class="col-md-6">
              <label>Link URL</label>
              <input type="text" name="link_url" class="form-control" value="{{@$banner->link_url}}" placeholder="Enter Link URL">
              <span class="help-block"></span>
          </div>
					<div class="col-md-6">
					  <label>Exipre Date:</label>
					  <input type="text" class="form-control datepicker" readonly placeholder="dd-mm-YYYY" name="expiry_date" autocomplete="off" value='{{date("d-m-Y",strtotime($banner->expiry_date))}}' />
					  <span class="help-block"></span>
					</div>
					<div class="col-md-6 form-check">
					  <label>Status</label><br>
					  <label class="radio-inline">
						  <input type="radio" name="status" value="1" @if(@$banner->status == '1') checked="checked" @endif>Active</label>
						  <label class="radio-inline"><input type="radio" name="status" value="0"  @if(@$banner->status == '0') checked="checked" @endif>Inctive</label>
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
			<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
		</div>
	</div>

	</div>


	<!-- <script src="{{ URL::asset('js/bootstrap.js') }}"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
	
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery( ".datepicker" ).datepicker({
	  dateFormat: 'dd-mm-yy',
	  minDate: new Date(),
	});
});
$(document.body).on('click', '.submit', function(){
		// jQuery("#updateAdBanner").validate({
		 jQuery("form[name='updateAdBanner']").validate({
			rules: {
				title: "required",
				type: "required",
				link_url: "required",
				area: "required",
				expiry_date: "required",
			 },
			messages:{
			},
			errorPlacement: function(error, element){
				error.appendTo(element.next());
			},ignore: ":hidden",
			submitHandler: function(form) {
				$(form).find('.submit').attr('disabled',true);
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('admin.updateAdBanner')!!}",
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
						// document.location.href='{!! route("admin.blogMaster")!!}';
						 }
						 else
						 {
						  jQuery('.loading-all').hide();
						  $(form).find('.submit').attr('disabled',false);
						  alert("Oops Something Problem");
						 }
					}
				});
			}
		});
	});
</script>
