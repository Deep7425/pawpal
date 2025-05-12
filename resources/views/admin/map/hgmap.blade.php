@extends('layouts.admin.Masters.Master')
@section('title', 'Health Gennie Map')
@section('content')

<html>
<head>
    <title>Indian Map</title>
    <style>
      body{
        background-color: #092b59;
         
      }
        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }

        .blink {
            animation: blink-animation 1s steps(5, start) infinite;
        }

        .marker-label {
            font-size: 10px;
            fill: black;
            font-weight:bolder;
            text-anchor: middle;
            dominant-baseline: central;
            cursor: pointer;
        }

        .marker-description {
        font-size: 8px;
        fill: #fff;
        text-anchor: middle;
        dominant-baseline: central;
        cursor: pointer;
        background-color: transparent; /* Set the initial background color */
        transition: background-color 0.3s; /* Add a smooth transition */
    }

    .marker-container:hover .marker-description {
        background-color: green; /* Change the background color on hover */
    }

    .popup {
        position: absolute;
        background-color: rgba(255, 255, 255, 0.8);
        padding: 5px;
        border-radius: 5px;
        font-size: 22px;
        pointer-events: none;
        display: none;
    }  

        .marker-image {
            width: 16px;
            height: 16px;
        }

        .marker-container {
            position: relative;
            display: inline-block;
        }

        .marker-container:hover .popup {
            display: block;
      
        }
     
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="http://d3js.org/d3.v3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/topojson/3.0.2/topojson.js" integrity="sha256-OztcN49BsVSoupBpIZaUwczM6+GLsXuA8IJF+W9SsBU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datamaps/0.5.9/datamaps.ind.js" integrity="sha256-dIqkWXhup1J7R2OiniHh0/nH6hw0ELzNSKfBkO1WHlQ=" crossorigin="anonymous"></script>
    <script>

// 26.9430° N, 75.8410° E

        $(function () {
            var cities = [
                { name: 'Jaipur(HQ)', latitude: 26.9124, longitude: 75.7873, imageUrl: '{{URL::asset('map_images/preview.png') }}',  },

                { name: 'Kota', latitude: 25.2138, longitude: 75.8648, imageUrl: '{{URL::asset('map_images/location-pin.png') }}',  },
                { name: 'Patna', latitude: 25.5941, longitude: 85.1376, imageUrl: '{{URL::asset('map_images/location-pin.png') }}', },
                { name: 'Lucknow',            latitude: 26.8467, longitude: 80.9462  ,  imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Kolkata',            latitude: 22.5726, longitude: 88.3639 ,  imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Prayagraj',          latitude: 25.4358, longitude: 81.8463 ,  imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Pune',               latitude: 18.5204, longitude: 73.8567 ,  imageUrl: '{{URL::asset('map_images/map.png') }}'},
                { name: 'Bhubaneswar',        latitude: 20.2961, longitude: 85.8245 ,  imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Noida',              latitude: 28.200, longitude: 77.3910,  imageUrl: '{{URL::asset('map_images/map.png') }}' },
                { name: 'Guwahati',           latitude: 26.1445, longitude: 91.7362 ,  imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Delhi - Janakpuri',  latitude: 28.6219, longitude: 77.0878 ,  imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Srinagar',           latitude: 34.0836, longitude: 74.7973 ,  imageUrl: '{{URL::asset('map_images/map.png') }}'},
                { name: 'Jammu',              latitude: 32.7266, longitude: 74.8570 ,  imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Varanasi',           latitude: 25.9176, longitude: 82.9739 ,  imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Jodhpur',            latitude: 26.2389, longitude: 73.0243 ,  imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Kanpur',             latitude: 26.4499, longitude: 80.3319 ,  imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Nanded',             latitude: 19.1383, longitude: 77.3210 ,  imageUrl: '{{URL::asset('map_images/map.png') }}'},
                { name: 'Siliguri',           latitude: 26.7271, longitude: 88.3953 ,  imageUrl: '{{URL::asset('map_images/map.png') }}'},
                { name: 'Indore',             latitude: 22.7196, longitude: 75.8577 , imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Gorakhpur',          latitude: 26.7606, longitude: 83.3732 , imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Nashik',             latitude: 20.5937, longitude: 73.7730 , imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                { name: 'Nagpur',               latitude: 21.1458, longitude: 79.0882 , imageUrl: '{{URL::asset('map_images/location-pin.png') }}'},
                // Add similar entries for other cities
            ];

            var map = new Datamap({
                scope: 'india',
                element: document.getElementById('map'),
                setProjection: function (element) {
                    var projection = d3.geo.mercator()
                        .center([77.729, 31.5937]) // always in [East Latitude, North Longitude]
                        .scale(2500);
                    var path = d3.geo.path().projection(projection);
                    return { path: path, projection: projection };
                },
    

                geographyConfig: {
                    dataUrl: 'https://rawgit.com/Anujarya300/bubble_maps/master/data/geography-data/india.topo.json',
                    borderColor: '#FF9C00',
                    borderWidth: 3,
                    highlightFillColor: '#ffffff',
                    
                },
                fills: { defaultFill: '#fdb913', 'RJ': 'green' },
            });


            map.svg.selectAll('.datamaps-subunit').on('click', function (geography) {
    if (geography.properties.name === 'Rajasthan') {
        map.updateChoropleth({ 'RJ': 'green' });
    } else {
        map.updateChoropleth({ 'RJ': '#fdb913' });
    }
});


            var jaipurCoords = map.latLngToXY(26.9124, 75.7873);
        cities.forEach(function (city) {
            var cityCoords = map.latLngToXY(city.latitude, city.longitude);

            map.svg.insert("line", ":first-child")
                .attr("x1", jaipurCoords[0])
                .attr("y1", jaipurCoords[1])
                .attr("x2", cityCoords[0])
                .attr("y2", cityCoords[1])
                .attr("stroke", "white")
                .attr("stroke-width", 2)
                .attr("stroke-dasharray", "5,5");
        });


            cities.forEach(function (city) {
                var container = map.svg.append('g')
                    .attr('transform', 'translate(' + map.latLngToXY(city.latitude, city.longitude)[0] + ',' + map.latLngToXY(city.latitude, city.longitude)[1] + ')');

                var marker = container.append('image')
                    .attr('x', -8)
                    .attr('y', -8)
                    .attr('width', 16)
                    .attr('height', 16)
                    .attr('xlink:href', city.imageUrl)
                    .classed('blink', true)
                    .classed('marker-image', true);

                var label = container.append('text')
                    .attr('y', 20)
                    .attr('class', 'marker-label')
                    .text(city.name);


                var popup = container.append('text')
                    .attr('y', -30)
                    .attr('class', 'popup')
                    .text(city.name + ':' + "Health Gennie")
                    .style('backgroud-color' , "green")
                    .style('display', 'none');

                container.on('mouseover', function () {
                    popup.style('display', 'block' , )
                    .style('background-color', 'white')
             
                    
                }).on('mouseout', function () {
                    popup.style('display', 'none');
                });
            });
        });
    </script>
</head>
<body>
    <div id="map" style="height: 1400px;"></div>
    <center>
    <h1>Presence of Health Gennie in India.</h1>

    </center></body>
</html>
@endsection
