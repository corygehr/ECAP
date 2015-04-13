<?php
    /**
     * Dashboard/html/view.tpl
     * Contains the HTML template for the view subsection
     *
     * @author Cory Gehr
     */
?>
<h1>Dashboard</h1>
<div id="map-canvas" style="clear:both;width:100%"></div>
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript">
	// Google Maps
	function initialize() {
<?php
	// Create pins for each possible status
	$statuses = $this->get('STATUSES');

	if($statuses)
	{
		foreach($statuses as $s)
		{
?>
		var <?php echo str_replace(' ', '', $s['name']); ?>PinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|<?php echo $s['color']; ?>",
			new google.maps.Size(21, 34),
			new google.maps.Point(0,0),
			new google.maps.Point(10, 34));
<?php
		}
	}
?>

        var centerLoc = new google.maps.LatLng(40.7904188,-77.8431301); // Beaver Stadium Coordinates

        var mapOptions = {
          center: centerLoc,
          zoom: 14,
          mapTypeId: google.maps.MapTypeId.HYBRID
        }

        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions)

        // KML Overlay
		var kmlUrl = '<?php echo MAPS_KML_URL; ?>';
		var kmlOptions = {
		  suppressInfoWindows: false,
		  preserveViewport: true,
		  screenOverlays: true,
		  map: map
		};
		var kmlLayer = new google.maps.KmlLayer(kmlUrl, kmlOptions);
/*
        // Lot information window
        var lotMarker = new google.maps.Marker({
		    position: lotLoc,
		    map: map,
		    title: "<?php echo $targetLot->name; ?>"
		});

        var contentHtml = '<div id="content">'+
        	'<h4><?php echo $targetLot->name . " (" . $targetLot->location_name . ")"; ?></h4>'+
        	'<p><b>Current Capacity:</b> <?php echo $targetCapacity->capacity; ?>%</p>'+
        	'</div>';

		var lotInfo = new google.maps.InfoWindow({
      		content: contentHtml
 		});

		google.maps.event.addListener(lotMarker, 'click', function() {
    		lotInfo.open(map,lotMarker);
  		});*/
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>