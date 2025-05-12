@extends('layouts.admin.Masters.Master')
@section('title', 'Dashboard')
@section('content')
<script type="text/javascript">
var UserId = "{{Session::get('id')}}";
var url = $('.sidebar-menu li:first').find('.treeview-menu li:first').find('a').attr('href');
console.log(url);
if (UserId != '1') {
    if (url) {
        window.location.href = url;
    }
}
</script>

<style>
.layout-container {
    padiing-top: 0px;
}

body {
    padding: 10px 35px;
    font-family: Verdana, Arial, sans-serif;
}

.row {
    display: flex;
}

.cell {
    padding: 0px 35px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<div class="page-loader">
    <div class="bg-primary"></div>
</div>

<div class="layout-wrapper layout-2">
    <div class="layout-inner" style="min-height: 86vh;">


        <div class="layout-container">
            <div class="container-fluid flex-grow-1 container-p-y">
                <div class="layout-content">


                    <div class="row">
                        <!-- customar project  start -->

                        <div class="col-xl-3 col-md-6 first">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center m-l-0">
                                        <div class="col-auto icon-block ">
                                            <i class="fas fa-user-injured f-36 text-danger"></i>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="text-muted m-b-10">Total Users</h6>
                                            <h2 class="m-b-0">{{getAllPatients()}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 second">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center m-l-0">
                                        <div class="col-auto icon-block">
                                            <i class="fas fa-user-md f-36 text-info"></i>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="text-muted m-b-10">HG Doctor</h6>
                                            <h2 class="m-b-0">{{getAllHgDoctors()}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 three">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center m-l-0">
                                        <div class="col-auto icon-block">
                                            <i class="fas fa-user-md f-36 text-info"></i>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="text-muted m-b-10">Non HG Doctor</h6>
                                            <h2 class="m-b-0">{{getAllNonHgDoctors()}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 fourth">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center m-l-0">
                                        <div class="col-auto icon-block">
                                            <i class="fas fa-prescription-bottle-alt f-36 text-primary"></i>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="text-muted m-b-10">Active Practices</h6>
                                            <h2 class="m-b-0">{{getAllHgActiveDoctors()}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 fifth">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center m-l-0">
                                        <div class="col-auto icon-block">
                                            <i class="fas fa-flask f-36 text-warning"></i>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="text-muted m-b-10">Total Appointment</h6>
                                            <h2 class="m-b-0">{{getTotalAppointment()}}</h2>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 fifth">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center m-l-0">
                                        <div class="col-auto icon-block">
                                            <i class="fas fa-flask f-36 text-warning"></i>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="text-muted m-b-10">Total Subscription</h6>
                                            <h2 class="m-b-0">{{getSubscriptionData()}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

          


                <div class="row" style="margin-top:20px; padding: 50px 0 50px 0; background-color:white;">
                    <div class="head-search ml-5">
                        <select id="yearSelect" name = "year">
                            <!-- Populate with years -->
                            @for ($year = date('Y'); $year >= 2017; $year--)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div style="width: 80%; margin: auto; margin-top:60px;">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <link href="https://unpkg.com/singledivui/dist/singledivui.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/singledivui/dist/singledivui.min.js"></script>


    <script>
    var ctx = document.getElementById('barChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($data['labels']),
            datasets: [{
                label: 'Data',
                data: @json($data['data']),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>


    <script>
    const {
        Chart
    } = SingleDivUI;

    const options = {
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            series: {
                points: [15, 9, 25, 18, 31, 25]
            }
        },
        height: 200,
        width: 400
    };

    new Chart('#chart1', {
        type: 'line',
        ...options
    });

    new Chart('#chart2', {
        type: 'bar',
        ...options
    });

    var ctx = document.getElementById('barChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($data['labels']),
            datasets: [{
                label: 'Data',
                data: @json($data['data']),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


    document.getElementById('yearSelect').addEventListener('change', function() {
        var selectedYear = this.value; // Get the selected year
        fetchChartData(selectedYear); // Call a function to fetch chart data with the selected year
    });

    // Function to fetch chart data using AJAX
    function fetchChartData(selectedYear) {
        // Make an AJAX request to your backend function
        $.ajax({
            url: "{{ route('admin.home') }}", // Replace 'your.route.name' with the actual route name for your backend function
            type: 'GET',
            data: { year: selectedYear }, // Pass the selected year as a parameter
            success: function(data) {
                // Update the chart with the fetched data
                updateChart(data);
            },
            error: function(xhr, status, error) {
                // Handle errors if any
                console.error(error);
            }
        });
    }

    // Function to update the chart with fetched data
    function updateChart(data) {
        // Your chart update logic here
        // For example, you can update chart data and labels
        var ctx = document.getElementById('barChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Data',
                    data: data.data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }


    </script>


    @endsection