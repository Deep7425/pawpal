<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-bs-dismiss="modal">×</button>
			<h4 class="modal-title">Update Offer Banner</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag  offer-banner">
				<div class="">
					<div class="btn-group">
						<a class="btn btn-primary" href="{{ route('admin.offersBannerMaster') }}"> <i class="fa fa-list"></i>OfferBanners List</a>
					</div>
				</div>
				<div class="panel-body" style="padding-top:15px;">
					{!! Form::open(array('id' => 'updateOffersBanner','name'=>'updateOffersBanner', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
					<input type=hidden value="{{$banner->id}}" name="id"/>
					<div class="col-md-6 form-group">
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
							<img src="<?php echo url("/")."/public/offerBannerFiles/".$banner->image;?>" class="img-responsive" alt="Banner Image" height="50" width="100" style="text-align:center;">
							@endif
						</div>
					
          <div class="form-group col-md-6">
            <label>Type</label>
              <select class="form-control valid" name="type">
                <option value="">Select Type</option>
                <option value="1" @if(@$banner->type == '1') selected @endif>Home - en</option>
                <option value="2" @if(@$banner->type == '2') selected @endif>Lab - en</option>
                <option value="3" @if(@$banner->type == '3') selected @endif>Home - hi</option>
                <option value="4" @if(@$banner->type == '4') selected @endif>Lab - hi</option>
                <option value="5" @if(@$banner->type == '5') selected @endif>Med - en</option>
                <option value="6" @if(@$banner->type == '6') selected @endif>Med - hi</option>
              </select>
            <span class="help-block"></span>
          </div>
          <div class="form-group col-md-6">
            <label> Banner Type</label>
              <select class="form-control valid" name="banner_type">
                <option value="">Select Type</option>

                <option value="0"> App</option>
                <option value="1"> Web</option>

              </select>
            <span class="help-block"></span>
          </div>
          <div class="col-md-6 form-group">
              <label>Link URL</label>
              <input type="text" name="link_url" class="form-control" value="{{@$banner->link_url}}" placeholder="Enter Link URL">
              <span class="help-block"></span>
          </div>
        
          <div class="form-group col-sm-6 PackageTypeNew">
            <label> Package Type</label>
              <select class="form-control valid" name="package_id" id="multipleSelect1" >
                <option value="">Select Package Type</option>
                @foreach($labPackageid as $data)
                <option value="{{$data->id}}" @if(@$banner->package_id == $data->id) selected @endif> {{$data->title}}</option>
                @endforeach

              </select>
            <span class="help-block"></span>
          </div>
					<div class="form-check col-md-6">
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

  <script src="{{ URL::asset('js/jquery-printme.js') }}"></script>
  <script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
  
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script type="text/javascript">

$(document.body).on('click', '.submit', function(){

		// jQuery("#updateOffersBanner").validate({
		 jQuery("form[name='updateOffersBanner']").validate({
			rules: {
				title: "required",
        type: "required",
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
					url: "{!! route('admin.updateOffersBanner')!!}",
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
  jQuery(document).ready(function(){
    $('#multipleSelect1').multiselect({
       nonSelectedText: 'Select Department',
      includeSelectAllOption: true,
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
    });
    $('#multipleSelect2').multiselect({
       nonSelectedText: 'Select User',
      includeSelectAllOption: true,
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
    });
    });

</script>
