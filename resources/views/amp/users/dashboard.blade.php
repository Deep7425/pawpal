@extends('amp.layouts.Masters.Master')
@section('title', 'Elite | Health Gennie')
@section('description', "Welcome to HEALTH GENNIE ELITE where we offer a complete healthcare plan for you & your family. Choose one that suits your needs and enjoy the benefits.")
@section('content')
<div class="dashboard-wrapper dashboard-plan-wrapper container">
      @if(session()->get('message'))
	  <div class="alert alert-success">
		<strong>Success!</strong> {{ session()->get('message') }}
	  </div>
	  @endif
      <div class="HG_plan_Section">
        <div class="HG_plan_Block">
            <img src="img/HG-club-health-gennie-banner.jpg" />
        </div>
	  </div>
		
	 <div class="HG_plan">
      	<h2>HEALTH GENNIE ELITE</h2>
        <p>HEALTH GENNIE ELITE offers different plans. Choose one that suits your needs.</p>
        <div class="hg-plan-wrapper">
			@if(count($plans) > 0)
				@foreach($plans as $i => $plan)
				<div class="healthcare_plan plan-section{{$i+1}}">
					<div class="title-bg">
						<h2>{{$plan->plan_title}}</h2>
                        <div class="actual-price-wrapper"><strike><strong>₹{{$plan->price}}</strong></strike> <strong>₹{{$plan->price - $plan->discount_price}}</strong></div>
						
					</div>
					<h3></h3>
					<div class="plan-content">
						 <div class="view-icon">
                            <span class="fa fa-arrow-right viewinfoicon"></span>
                            <div id="lightbox-tooltip" class="data-pack-lab">
                            	 <div class="lab-test Lab_Test_Details single-lab-detail">
									<div class="package-box_lab">
									   <?php
										$groups = array();
										if(!empty($plan->pkg_data) && count($plan->pkg_data->childs) > 0){
											foreach($plan->pkg_data->childs as $element) {
												if($element->group_name != "SUBSET") {
													$groups[$element->group_name][] = $element;
												  }
											}
										}
										?>
										@if(count($groups) > 0)
											@foreach($groups as $group => $tests)
											<div class="toggle-wrapper">
											<button onclick="myFunction(this)">{{$group}}({{count($tests)}})</button>	
											  <div class="toggle-hg">
                                              @foreach($tests as $child)
											  
                                              <div class="toggle-wrapper-content" style="display:none;">
												<div class="package-box">
													<div class="lab-test-block-img">
														<img src="{{ URL::asset('img/lab2-icon.png') }}" />
													</div>
													<div class="lab-test-block">
														<h3>{{$child->name}}</h3>
													</div>
												</div>
											  </div>
											  @endforeach
											  </div>
                                              </div>
											@endforeach
										 @endif	
									</div>
								</div>         
                            </div>
                       </div>
					{!!$plan->content!!}</div>
					<a class="btn" href='{{route("checkOutUserPlan",["id" => base64_encode($plan->id)])}}'>Buy Plan</a>
				</div>
				@endforeach
			@endif
        </div>
      </div>
      
      <div class="HG-terms">
      	<h3>Terms & Conditions</h3>
        <ol>
				<li>Free Dr consultation of max upto Rs 300 allowed.</li>
				<li>Free Dr consultation can be used for any family member. </li>
				<li>Once booked, free consultation cannot be cancelled.</li>
				<li>One free body checkup can be availed in this membership within a year of the start date.</li>
            </ol>
      </div>
      
      <div class="hg-club">
      	<h3>HEALTH GENNIE ELITE</h3>
        <p>Health Gennie is the leading healthcare technology company in India.</p>
        <div class="HG-details">
			<div class="details-blog">
            	<img src="img/doctor-appointment.png" />
                <div class="content-blog-details">
                    <strong>1500+</strong>
                    <p>Wide Network Empaneled Doctors</p>
                </div>
            </div>
        	<div class="details-blog">
            	<img  src="img/patient.png" />
            	<div class="content-blog-details">
                	<strong>250000+</strong>
					<p>Patients Registered</p>
                </div>
            </div>

            
                        
            <div class="details-blog">
            	<img src="img/health-records.png" />
                <div class="content-blog-details">
                    <strong>300000+</strong>
                    <p>Health Records</p>
                </div>
            </div>
            
            <div class="details-blog">
            <img src="img/appointment-banner.png" />
            <div class="content-blog-details">
            	<strong>350000+</strong>
				<p>Appointments</p></div>
            </div>
            
        </div>
      </div>	
</div>
<script>
function myFunction(current) {
	$('.toggle-wrapper').removeClass("chooseEle");
	$(current).closest('.toggle-wrapper').addClass("chooseEle");
	$('.toggle-wrapper').each(function(){
		if(!$(this).hasClass('chooseEle')) {
			$(this).find('.toggle-wrapper-content').slideUp();
		}
	});
	$(current).closest('.toggle-wrapper').find('.toggle-wrapper-content').slideToggle();
}
</script>
@endsection