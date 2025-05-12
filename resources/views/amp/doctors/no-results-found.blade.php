@extends('amp.layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content') 
	<div class="searching-keyword">
		<div class="container">
			<h1>SEARCH RESULTS FOR: <strong>"{{ Session::get('search_from_search_bar') }}"</strong></h1>
			<div class="searhc-result"> 0 matches found for:<strong>{{ Session::get('search_from_search_bar') }} In {{ Session::get('search_from_city_name') }}</strong> </div>
		</div>
    </div>
	<div class="container">
		  <div class="container-inner">
				<div class="filer-bar">
			 
					  <div class="breadcrume">
					<ul>
						  <li><a href="#">Home</a> /</li>
						  <li><a href="#">{{ Session::get('search_from_city_name') }}</a> /</li>
						  <li>Search result for <strong>“{{ Session::get('search_from_search_bar') }}”</strong></li>
						</ul>
				  </div>
					  <div class="sorting">
					<label>Showing all 4 results</label>
					<div class="select">
						  <select>
						<option>SORT BY LATEST</option>
					  </select>
						</div>
				  </div>
					</div>
					
				<div class="left-content">
					  <h2>LOCALITY</h2>
					  <label class="chck-container">All Places
					<input type="checkbox">
					<span class="checkmark"></span> </label>
					  <label class="chck-container">Vaishali Nagar
					<input type="checkbox">
					<span class="checkmark"></span> </label>
					  <label class="chck-container">Mansarovar
					<input type="checkbox">
					<span class="checkmark"></span> </label>
					  <label class="chck-container">Vidhyadhar Nagar
					<input type="checkbox">
					<span class="checkmark"></span> </label>
					  <label class="chck-container">Malviya Nagar
					<input type="checkbox">
					<span class="checkmark"></span> </label>
					  <label class="chck-container">Shastri Nagar
					<input type="checkbox">
					<span class="checkmark"></span> </label>
					  <label class="chck-container">Gopalpura
					<input type="checkbox">
					<span class="checkmark"></span> </label>
					  <label class="chck-container">Pratap Nagar
					<input type="checkbox">
					<span class="checkmark"></span> </label>
					  <label class="chck-container">Adarsh Nagar
					<input type="checkbox">
					<span class="checkmark"></span> </label>
					  <h2>GENDER</h2>
					  <div class="filter-wrap">
					<label class="chck-container">All
						  <input type="checkbox">
						  <span class="checkmark"></span> </label>
				  </div>
					  <div class="filter-wrap">
					<label class="chck-container">Male
						  <input type="checkbox">
						  <span class="checkmark"></span> </label>
				  </div>
					  <div class="filter-wrap">
					<label class="chck-container">Female
						  <input type="checkbox">
						  <span class="checkmark"></span> </label>
				  </div>
					  <h2>EXPERIENCE</h2>
					  <div class="filter-wrap">
					<label class="chck-container">1-5 Years</label>
				  </div>
					  <div class="filter-wrap">
					<label class="chck-container">5-10 Years</label>
				  </div>
					  <div class="filter-wrap">
					<label class="chck-container">10-15 Years</label>
				  </div>
					  <div class="filter-wrap">
					<label class="chck-container">15-20 Years</label>
				  </div>
					  <h2>Consultation Fees</h2>
					  <div class="price-range-block">
				<div id="slider-range" class="price-filter-range" name="rangeInput"></div>
				<div style="float:left; width:100%;">
				  <input type="number" min=0 max="9900" oninput="validity.valid||(value='0');" id="min_price" class="price-range-field" />
				  <input type="number" min=0 max="10000" oninput="validity.valid||(value='10000');" id="max_price" class="price-range-field" />
				</div>

				<button class="price-range-search" id="price-range-submit">Search</button>

				<div id="searchResults" class="search-results-block"></div>

			</div>
				</div>

		</div>
			
		<div class="right-content no-result-found">
           <img src="img/search-result.png" alt="icon"  />
           <h2><strong>We're Sorry!</strong><br /> We couldn't find what you were looking for!</h2>
           <p>Go back to <a href="#">home</a>.</p>
		</div>
	</div>
	<div class="container-fluid">
		<div class="container"> </div>
    </div>
	<script>
		jQuery(document).on("click", ".show_doctor_info", function (e) {
			var search_type = $(this).attr('search_type');
			var info_type = $(this).attr('info_type');
			var data_info_id = $(this).attr('data_id');
			$("#searchDocInfo").find("input[name='search_type']").val(search_type);
			$("#searchDocInfo").find("input[name='info_type']").val(info_type);
			$("#searchDocInfo").find("input[name='id']").val(data_info_id);
			setTimeout(function(){
				$("#searchDocInfo").submit();
			}, 500);
		});
	</script>
@endsection