<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('classForm/class.form');

$article = $this->runquery("SELECT * FROM articles WHERE title='".'Our Contacts'."'",'single');

echo '<h1 class="fullarticleTitle">Contact Us</h1>';

echo '<style>
      #contactsholder{
        display: table;
        width: 50%;
        float: left;
      }      
      #map_canvas {
        width: 500px;
        height: 400px;
        float: right;
        margin-top: 0px;
        margin-right: 50px;
      }
      @media (max-width: 767px){
        #contactsholder{
            width: 100%;
        }
        #map_canvas {
            margin-top: 10px;
            width: 320px;
        }
      }
    </style>';

echo '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>';

$this->wrapscript("function initialize() {
        var map_canvas = document.getElementById('map_canvas');
        var myLatlng = new google.maps.LatLng(-1.323696, 36.833532);
        
        var map_options = {
          center: myLatlng,
          zoom: 16
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


echo '<div id="itemContainer" class="row">';

echo '<div id="contactsholder" class="col-xs-12">';

echo $article['body'];
include(ABSOLUTE_PATH.'components/contacts/contactform.php');

echo '</div>';

echo '<div id="map_canvas" class="col-xs-12"></div>'
. '</div>';