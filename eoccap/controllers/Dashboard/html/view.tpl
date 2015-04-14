<?php
    /**
     * Dashboard/html/view.tpl
     * Contains the HTML template for the view subsection
     *
     * @author Cory Gehr
     */

// Get list of Lots
$lots = $this->get('LOTS');

// Determine what to do with each lot

?>
<h1>Dashboard</h1>
<div id="map-canvas" style="clear:both;width:100%;height:500px"></div>
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

        var centerLoc = new google.maps.LatLng(40.812152,-77.856176); // Beaver Stadium Coordinates

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

		// Traffic overlay
		var trafficLayer = new google.maps.TrafficLayer();
		trafficLayer.setMap(map);

		// Use a single infoWindow to ensure we don't have more than one open at a time
		var infoWindow = new google.maps.InfoWindow();

<?php
	// Output markers for each lot
	if($lots)
	{
		foreach($lots as $lot)
		{
			$markerId = preg_replace("/[^A-Za-z0-9]/", '', $lot['name']) . $lot['id'];
?>
		var <?php echo $markerId; ?>Marker = new google.maps.Marker({
			position: new google.maps.LatLng(<?php echo $lot['latitude'] . "," . $lot['longitude']; ?>),
			map: map,
			title: '<?php echo $lot['name']; ?>',
			icon: <?php echo str_replace(' ', '', $lot['status']); ?>PinImage
		});

		var <?php echo $markerId; ?>Content = '<div id="content">'+
        	'<h4><?php echo $lot['name'] . " (" . $lot['location_name'] . ")"; ?></h4>'+
        	'<p><b>Status:</b> <?php echo $lot['status']; ?></p>'+
        	'<p><b>Current Capacity:</b> <?php echo $lot['capacity']; ?>%</p>'+
        	'<p><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>">Manage Lot</a></p>'+
        	'</div>';

		google.maps.event.addListener(<?php echo $markerId; ?>Marker, 'click', function() {
			infoWindow.close();
			infoWindow.setContent(<?php echo $markerId; ?>Content);
			infoWindow.open(map, <?php echo $markerId; ?>Marker);
  		});
<?php
		}
	}
?>
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>