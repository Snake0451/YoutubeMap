@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div style="width: 500px; height: 500px;">
                <div id="map-canvas-0" style="width: 100%; height: 100%; margin: 0px; padding: 0px; position: relative; overflow: hidden;">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="floating-panel">
      <input id="address" type="textbox" value="Sydney, NSW">
      <input id="submit" type="button" value="Geocode">
</div>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3.31&region=GB&language=ru-ru&key=AIzaSyCjptbERqG3kcBjppdA1zaYL6aGHLLsweA&libraries=places"></script>
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">

	var maps = [];

	function initialize_0() {
		var bounds = new google.maps.LatLngBounds();
		var infowindow = new google.maps.InfoWindow();
        var position = new google.maps.LatLng(40.7127753, -74.0059728);
        var markers = [];
		var mapOptions_0 = {
            center: position,
            zoom: 8,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			disableDefaultUI:  false ,
			scrollwheel:  true ,
            zoomControl:  true ,
            mapTypeControl:  true ,
            scaleControl:  false ,
            streetViewControl:  true ,
            rotateControl:  false ,
            fullscreenControl:  true };

		var map = new google.maps.Map(document.getElementById('map-canvas-0'), mapOptions_0);
        map.setTilt(0);

        var geocoder = new google.maps.Geocoder();

        document.getElementById('submit').addEventListener('click', function() {
                geocodeAddress(geocoder, map);
        });

        function geocodeAddress(geocoder, resultsMap) {
            var address = document.getElementById('address').value;
            geocoder.geocode({'address': address}, function(results, status) {
                if (status === 'OK') {
                    resultsMap.setCenter(results[0].geometry.location);
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
        
        map.addListener('dragend', function(){
            //console.log(map.getCenter().lat() + '  ' + map.getCenter().lng())
            var marker, i, videos;
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/getVideos',
                type: 'POST',
                data: {
                        _token: CSRF_TOKEN,
                        lat: map.getCenter().lat(),
                        long: map.getCenter().lng()
                    },
                dataType: 'JSON',
                success: function (data) {
                    videos = data;
                    setMapOnAll(null, markers);
                    markers = [];
                    
                    for(i in videos){
                        //console.log(videos[i]);
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(videos[i].lat, videos[i].long),
                            map: map,
                            label: i
                        });
                        
                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {
                            infowindow.setContent('<a href="' + videos[i].url + '"><h3>' + videos[i].title + '</h3></a>');
                            infowindow.open(map, marker);
                            }
                        })(marker, i));
                        
                        markers.push(marker);
                    }
                }
            });
            

        });
    }

    function setMapOnAll(map, markers) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }
        
	    google.maps.event.addDomListener(window, 'load', initialize_0);


</script>

@endsection