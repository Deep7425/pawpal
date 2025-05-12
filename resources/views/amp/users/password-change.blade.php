@extends('amp.layouts.Masters.Master')
@section('title', 'Change Password | Health Gennie')
@section('description', "Please enter a new password and then click on the submit button. Once you reset or change your password then you will be login.")
@section('content')
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
@include('users.sidebar')
<div class="dashboard-right">
    <div class="container-inner change-pwd-wrapper">
        <div class='user-profile-detail'>
		  {!! Form::open(array('route' => 'changePassword','method' => 'POST', 'id' => 'reset-form')) !!}
		  <input type="hidden" name="id" value="{{@$id}}"/>
			   <div class="tab-content user-claim-profile">
						<div class="registration-wrap user-info profile-exam">
                        @if (Session::has('message'))
							<div class="alert alert-info sessionMsg">{{ Session::get('message') }}</div>
						@endif
					<div class="user-update-data">
						<div class="form-title"><h2>Change Password</h2>
						</div>
						<div class="form-fields pad-r0">
						  <label>New Password<i class="required_star">*</i></label>
						  <input type="password" name="password" placeholder="New Password" autocomplete="off" id="password" />
						  <span class="help-block"></span>
						</div>
						<div class="form-fields">
						  <label>Confirm Password<i class="required_star">*</i></label>
							<input type="password" name="c_pass" placeholder="Confirm Password" autocomplete="off"/>
						  <span class="help-block"></span>
						</div>

						<div class="form-fields send-button doc-profile">
						  <button type="submit" class="form-control">Submit</button>
						</div>
					</div>
          {!! Form::close() !!}
		  </div>
		</div>
    </div>
    </div>
    </div>
 </div>
<script>

		jQuery("#reset-form").validate({
			rules: {
				  password : {
                      required: true,
                      minlength : 6,
                      maxlength : 20
                   },
					c_pass : {
                      required: true,
                      minlength : 6,
                      maxlength : 20,
                      equalTo : "#password"
                   }
			},
			messages: {
			},
			errorPlacement: function(error, element) {
				 error.appendTo(element.next());
			},ignore: ":hidden",
			submitHandler: function(form) {
				jQuery('.loading-all').hide();
				form.submit();
			}
		});
</script>
@endsection
