<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<style>
      #map_canvas {
        width: 100%;
        height: 296px;
        margin-top: 0px;
        margin-right: 0px;
        position: relative;
        overflow: visible !important;
      }
    </style>';

echo '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>';

$this->wrapscript("function initialize() {
        var map_canvas = document.getElementById('map_canvas');
        var myLatlng = new google.maps.LatLng(-1.283042, 36.830615);
        
        var map_options = {
          center: myLatlng,
          zoom: 15
        }
      var map = new google.maps.Map(map_canvas, map_options);
        
      var contentString = '<div><p><strong>International Center for Humanitarian Affairs</strong></p></div>';

      var infowindow = new google.maps.InfoWindow({
          content: contentString
      });

        var marker = new google.maps.Marker({
              position: myLatlng,
              map: map,
              title: 'International Center for Humanitarian Affairs'
          });
          
        infowindow.open(map,marker);
      }
      
      google.maps.event.addDomListener(window, 'load', initialize);
      ");

echo '<div id="map_canvas"></div>';