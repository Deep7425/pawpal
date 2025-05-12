@extends('layouts.admin.Masters.Master') 
@section('title', 'World Mental Health Master') 
@section('content')



<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />


<!-- Bootstrap Datepicker CSS -->

<style>

</style>

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y appoint-list">

           
            <div class="row mb-2 ml-1 form-top-row pad">

             

                    {!! Form::open(array('route' => 'admin.wmhMaster',  'id' => 'chnagePagination', 'method'=>'POST' , 'style' => 'float: left;')) !!}
                <div class="btn-group form-head">
                    <div class="col-md-5">
                    
                    <div class=""> 
                      
                        <div class="input-group date">
                            <input style = "border-radius:1px; border:1px solid #ddd; padding :1px" type="text" autocomplete="off" class="form-control fromStartDate" name="start_date" value="@if((app('request')->input('start_date'))!=''){{ base64_decode(app('request')->input('start_date')) }}@endif" />
                            <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>

                        </div>
                    </div>
                    </div>

                <div class="col-md-5">
                    <div class="">
                       
                        <div class="input-group date">
                            <input style = "border-radius:1px; border:1px solid #ddd; padding :1px" type="text" autocomplete="off" class="form-control toStartDate" name="end_date" value="@if((app('request')->input('end_date'))!=''){{ base64_decode(app('request')->input('end_date')) }}@endif" />
                            <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
                        </div>
                    </div>
                </div>

                    <div class="col-md-3">

                        <div class="">
                        
                        <span class="input-group-btn">
                            <button class="btn btn-primary form-control" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </span>
                        </div>

                        </div>

                    </div> 
           
  
            {!! Form::close() !!}
                

            </div>

            <div class="layout-content card appointment-master">
                            
                <div id="am-xy-5"  style="height: 400px; margin-top:100px;"></div>

                <input type="hidden" value="" id="love" />
                <input type="hidden" value="" id="joy" />
                <input type="hidden" value="" id="surprise" />
                <input type="hidden" value="" id="sadness" />
                <input type="hidden" value="" id="anger" />
                <input type="hidden" value="" id="fear" />
                </div>
            </div>
         </div>
        </div>
    </div>
<!-- Bootstrap CSS -->


    <script>


$(".fromStartDate").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayHighlight: true
}).datepicker('setDate', new Date()).on('changeDate', function () {
                $(this).datepicker('hide');
            });
	jQuery('.fromStartDate_cal').click(function () {
		jQuery('.fromStartDate').datepicker('show');
	});


    $(".toStartDate").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayHighlight: true
}).datepicker('setDate', new Date()).on('changeDate', function () {
                $(this).datepicker('hide');
            });
	jQuery('.toStartDate_cal').click(function () {
		jQuery('.toStartDate').datepicker('show');
	});

        var myArray ={!! $viewer !!};

        // Loop through the array and set values for each input field
        for (var i = 0; i < myArray.length; i++) {
            var inputId = ''; // Initialize an empty string to store the input field ID
            switch (i) {
                case 0:
                    inputId = 'love';
                    break;
                case 1:
                    inputId = 'joy';
                    break;
                case 2:
                    inputId = 'surprise';
                    break;
                case 3:
                    inputId = 'sadness';
                    break;
                case 4:
                    inputId = 'anger';
                    break;
                case 5:
                    inputId = 'fear';
                    break;
                default:
                    // Handle additional cases if needed
                    break;
            }

            // Set the value of the hidden input field
            document.getElementById(inputId).value = myArray[i];
        }

      

        $(function() {
        	let love = $("#love").val();
            // console.log('love' , love)
        	let joy = $("#joy").val();
            // console.log('joy' , joy)
        	let surprise = $("#surprise").val();
            // console.log('surprise' , surprise)
        	let sadness = $("#sadness").val();
        	let anger = $("#anger").val();
        	let fear = $("#fear").val();
        	am4core.useTheme(am4themes_animated);

        	var chart = am4core.create("am-xy-5", am4charts.XYChart3D);
        	chart.data = [{
        		"title": "love",
        		"count": love,
        		// "units": 450
        	}, {
        		"title": "joy",
        		"count": joy,
        		// "units": 222
        	}, {
        		"title": "surprise",
        		"count": surprise,
        		// "units": 300
        	},
            {
        		"title": "sadness",
        		"count": sadness,
        		// "units": 300
        	},
            {
        		"title": "anger",
        		"count": anger,
        		// "units": 300
        	},
            {
        		"title": "fear",
        		"count": fear,
        		// "units": 300
        	}

         ];


        	var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        	console.log(categoryAxis);
        	categoryAxis.dataFields.category = "title";
        	// categoryAxis.title.text = "Countries";
        	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        	valueAxis.min = 0;
        	var series = chart.series.push(new am4charts.ColumnSeries3D());

        	series.dataFields.valueY = "count";
        	series.dataFields.categoryX = "title";
        	series.name = "Total";
        	series.columns.template.fill = am4core.color("#fb7d5b");
        	series.tooltipText = "{name}: [bold]{valueY}[/]";
        	chart.cursor = new am4charts.XYCursor();
         });
          </script>




         @endsection

      
         
         <script src="{{ asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
		 <script src="{{ asset('assets/libs/chart-am4/core.js') }}"></script>
		 <script src="{{ asset('assets/libs/chart-am4/charts.js') }}"></script>
		 <script src="{{ asset('assets/libs/chart-am4/animated.js') }}"></script> 
		<script src="{{ asset('assets/js/analytics.js') }}"></script>
		<script src="{{ asset('assets/js/pages/charts_am.js') }}"></script>

        

<script src="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ URL::asset('assets/libs/moment/moment.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
</div>


