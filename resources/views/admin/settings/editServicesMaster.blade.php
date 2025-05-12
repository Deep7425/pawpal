  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content form-group-Value123">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Edit Service</h4>
      </div>
      <div class="modal-body">
        {!! Form::open(array('id' => 'UpdateServicesMaster','name'=>'UpdateServicesMaster', 'enctype' => 'multipart/form-data')) !!}
        <div class="row"> 
        <input type="hidden" name="id" value="{{$service->id}}">
        <input type="hidden" name="type" value="2">
        <div class="form-group col-sm-6">
          <label>Key</label>
          <input readonly type="text" name="key" class="form-control" value="{{$service->key}}" placeholder="Enter Key">
          <span class="help-block"></span>
        </div>
         <div class="form-group col-sm-6">
          <label class="col-sm-12">Value</label>
		  <div class="form-group-Value">
          <?php $values = explode(",",$service->value);?>
          @if(count($values) > 0)
          <?php $i =1;?>
            @foreach($values as $index => $value)
			<div class="">
            <div class="InputRow"><input value="{{$value}}" type="text" name="value[]" class="form-control" placeholder="Enter Value">
              @if(count($values) != '1')<span class="removeRow"><i class="fa fa-times" aria-hidden="true"></i></span> @endif</div></div>
              <?php $i++; ?>
            @endforeach
          @endif
          <span class="help-block"></span>
		  </div>
        </div>
        <div class="form-group col-sm-12">
          <div class="reset-button">
             <button type="reset" class="btn btn-warning">Reset</button>
             <button type="submit" class="btn btn-success update" id="upload-btn">Update</button>
          </div></div>
          {!! Form::close() !!}
          
        </div>
		<div class="modal-footer col-sm-12">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
  </div>


  <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>

  <script>
        $(document.body).on('click', '.removeRow', function(){
          $(this).parent().remove();
          if ($('.InputRow').length == '1') {
            $('.removeRow').hide();
          }
        });
    $(document.body).on('click', '.update', function(){
    // jQuery("#modifySubAdmin").validate({
     jQuery("form[name='UpdateServicesMaster']").validate({
     rules: {
        key: "required",
        'value[]': "required",

       },
      messages:{
      },
      errorPlacement: function(error, element){

        error.appendTo(element.parent().find('.help-block'));
      },ignore: ":hidden",
      submitHandler: function(form) {
        jQuery('.loading-all').show();
        $(form).find('.update').attr('disabled',true);
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
  </script>