@extends('layouts.Masters.Master')
@section('title', 'Conditions | Health Gennie')
@section('description', "This is the official Terms of Use for Health Gennie website, by accessing our website you agree to be bound by the terms and conditions of this user agreement.")
@section('content') 

	<div class="container">
        <div class="container-inner">
        	<div class="company-policy">
          {!!$page->description !!}
          </div>
         <!-- <button class="button" onClick="showModalPopUp();">Register HealthId</button>-->
        </div>
	</div>
    
	<div class="container-fluid">
		<div class="container"> </div>
    </div>
    <script type="text/javascript">
      var popUpObj;
    function showModalPopUp()
    {
popUpObj=window.open("http://healthidsbx.ndhm.gov.in/facility?requestId=1234567899&customCode=s3depp",
      "HidModalPopUp",
      "toolbar=no," +
      "scrollbars=yes," +
      "location=no," +
      "statusbar=no," +
      "menubar=no," +
      "directories=no," +
      "resizable=no," +
      "width=600," +
      "height=800," +
      "left=100," +
      "top=100,"+
      "copyhistory=no"
    );
    popUpObj.focus();
    }
    </script>
@endsection