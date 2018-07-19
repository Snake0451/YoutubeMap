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
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3.31&region=GB&language=ru-ru&key=AIzaSyCjptbERqG3kcBjppdA1zaYL6aGHLLsweA&libraries=places"></script>
<script type="text/javascript" src="//googlemaps.github.io/js-marker-clusterer/src/markerclusterer.js"></script>

<script type="text/javascript">

	var maps = [];

	function initialize_0() {
		var bounds = new google.maps.LatLngBounds();
		var infowindow = new google.maps.InfoWindow();
		var position = new google.maps.LatLng(40.7127753, -74.0059728);
        var videos = {!! json_encode($videos) !!};
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

		var map_0 = new google.maps.Map(document.getElementById('map-canvas-0'), mapOptions_0);
		map_0.setTilt(0);

		var markers = [];
		var infowindows = [];
		var shapes = [];
		var idleListener = google.maps.event.addListenerOnce(map_0, "idle", function () {
			map_0.setZoom(8);
        });
        var idleListener = google.maps.event.addListener(map_0, "dragend", function () {
			console.log("dragend");
        });
        var map = map_0;
					var markerClusterOptions = {
				imagePath: '//googlemaps.github.io/js-marker-clusterer/images/m',
				gridSize: 60,
				maxZoom:  null ,
				averageCenter:  false ,
				minimumClusterSize: 4
			};
			var markerCluster = new MarkerClusterer(map_0, markers, markerClusterOptions);
		
		maps.push({
			key: 0,
			markers: markers,
			infowindows: infowindows,
			map: map_0,
			shapes: shapes
        });
        
        for(var i in videos){
            // console.log(video);
            var markerPosition = new google.maps.LatLng(videos[i].lat, videos[i].long);    
            var marker = new google.maps.Marker({
            position: markerPosition,
            title: i,
            label: i,
            animation:  '' ,
            icon:   "",
            url: videos[i].url});
                
            bounds.extend(marker.position);
            marker.setMap(map_0);
            markers.push(marker);
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map_0, marker);
            });
        }
    
    }
    

	    google.maps.event.addDomListener(window, 'load', initialize_0);


</script>

@endsection