@extends('layouts.admin.Masters.Master')
@section('title', 'Tickets Overview')
@section('content')
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />
    <body>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <div class="layout-container appointment-master" style="padding-top: 0px !important;">
                <div class="container-fluid flex-grow-1 container-p-y">
                <div class="row mb-2 ml-1 form-top-row">
                    
                        <div class="header-sc ticket">
                            <h3>Overall Tickets: {{ $totalTickets??0 }}</h3>
                        </div>
                        <div class="head-btn">
                        <div class="orange-btn">
                            <a href="{{ route('admin.assignTicketList') }}" class="btn btn-primary">Assign Ticket List</a>
                        </div>
                        <div class="orange-btn">
                            <a href="{{ route('admin.unassignTicketList') }}" class="btn btn-primary">Unassign Ticket List</a>
                        </div>
                        <div class="select-head">
                            <select id="yearSelect">
                                <!-- Populate with years -->
                                @for ($year = date('Y'); $year >= 2017; $year--)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="graph">
                                <canvas id="statusPieChart" width="100" height="100"></canvas>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="graph">
                                <canvas id="monthBarGraph" width="100" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--           <p>No of tickets in a year: {{dd($statusWiseCountsGrouped)}}</p>--}}
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   <!-- Status-wise pie chart -->

    <script>
        var statusData = {!! json_encode($statusWiseCountsGrouped) !!};
        console.log('statusData', statusData)

        // Function to update the charts based on the selected year
        // Function to update the charts based on the selected year
        function updateChart(selectedYear) {
            // Filter data for the selected year
            var filteredData = {};
            Object.keys(statusData).forEach(status => {
                filteredData[status] = statusData[status].filter(item => new Date(item.created_at).getFullYear() === selectedYear);
            });

            // Update the bar graph
            updateBarGraph(filteredData);

            // Update the pie chart
            updatePieChart(filteredData);
        }


        // Function to update the bar graph
        function updateBarGraph(data) {
            // Extract unique months from the filtered data
            var uniqueMonths = [...new Set(Object.values(data).flatMap(status => status.map(item => item.month)))];

            // Generate month-wise data for the bar graph
            var monthData = uniqueMonths.map(month => {
                var totalTickets = Object.values(data).flatMap(status => status)
                    .filter(item => item.month === month)
                    .reduce((acc, curr) => acc + curr.count, 0);
                console.log('totalTickets', totalTickets)

                return {
                    month: monthNames[month - 1], // Subtracting 1 as JavaScript arrays are 0-indexed
                    totalTickets: totalTickets,
                    counts: Object.values(data).map(status => status.find(item => item.month === month)?.count || 0)
                };
            });
            var statusColors = {
         
                "Cancelled": '#55a3f4'
            };

            // Update the datasets for the bar graph
            monthBarChart.data.labels = monthData.map(item => item.month);
            monthBarChart.data.datasets = [{
                label: 'Total Tickets',
                data: monthData.map(item => item.totalTickets),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }].concat(Object.keys(data).map((status, index) => ({
                label: status,
                data: monthData.map(item => item.counts[index]),
                backgroundColor: statusColors[status],
                borderColor: 'rgba(34, 43, 64, 1)',
                borderWidth: 1
            })));

            // Update the chart data
            monthBarChart.update();
        }
        function updatePieChart(data) {
            var statusColors = {
                "In-Progress": '#f4ab55',
                "Pending": '#ff4961',
                "Complete": '#62d493',
                "Cancelled": '#55a3f4'
            };
            // Update the datasets for the pie chart
            statusPieChart.data.labels = Object.keys(data);
            statusPieChart.data.datasets[0].data = Object.values(data).map(status => status.reduce((acc, curr) => acc + curr.count, 0));
            statusPieChart.data.datasets[0].backgroundColor = Object.keys(data).map(status => statusColors[status]);
            // Update the chart data
            statusPieChart.update();
        }


        // Initialize the charts with default data
        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var monthBarCtx = document.getElementById('monthBarGraph').getContext('2d');
        var monthBarChart = new Chart(monthBarCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: []
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var statusPieCtx = document.getElementById('statusPieChart').getContext('2d');
        var statusPieChart = new Chart(statusPieCtx, {
            type: 'pie',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        '#f4ab55', '#FF4961', 'yellow', 'green'
                    ]
                }]
            }
        });

        // Get the current year
        var currentYear = new Date().getFullYear();

        // Update the charts with default data
        updateChart(currentYear);

        // Event listener for the year select dropdown
        document.getElementById('yearSelect').addEventListener('change', function(event) {
            var selectedYear = parseInt(event.target.value);
            updateChart(selectedYear);
        });
    </script>



    </body>
@endsection
