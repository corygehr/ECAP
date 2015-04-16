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
$attention = array();
$limited = array();
$open = array();
$closed = array();
$ready = array();

foreach($lots as $lot)
{
	// Organize into separate arrays
	switch($lot['status'])
	{
		case 'Open':
			$open[] = $lot;
		break;

		case 'Closed':
			$closed[] = $lot;
		break;

		case 'Limited':
			$limited[] = $lot;
		break;

		case 'Needs Attention':
			$attention[] = $lot;
		break;

		case 'Ready':
			$ready[] = $lot;
		break;
	}
}

?>
<h1>Dashboard</h1>
<div id="map-canvas" style="clear:both;width:100%;height:500px"></div>
<h2>Lot List</h2>
<?php

// Output Needs Attention lots
if($attention)
{
?>
<legend>Needs Attention</legend>
<table id="attention" class="tablesorter">
	<thead>
		<tr>
			<th>Lot Name</th>
			<th>Comment</th>
			<th>Last Update</th>
			<th>Updated By</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($attention as $lot)
	{
		// Determine location for status open comment
		if(strpos($lot['comment'],REPORT_TRG_TEXT) !== false)
		{
			$comment = $lot['notes'];
			$updateTime = $lot['readiness_create_time'];
			$updateUser = $lot['readiness_create_user_name'];
		}
		else
		{
			$comment = $lot['comment'];
			$updateTime = $lot['status_create_time'];
			$updateUser = $lot['status_create_user_name'];
		}
?>
		<tr>
			<td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
			<td><?php echo $comment; ?></td>
			<td><?php echo $updateTime; ?></td>
			<td><?php echo $updateUser; ?></td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<?php
}

// Output Ready lots
if($ready)
{
?>
<legend>Ready</legend>
<table id="ready" class="tablesorter">
	<thead>
		<tr>
			<th>Lot Name</th>
			<th>Comment</th>
			<th>Last Update</th>
			<th>Updated By</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($ready as $lot)
	{
		// Determine location for status open comment
		if(strpos($lot['comment'],REPORT_TRG_TEXT) !== false)
		{
			$comment = (empty($lot['notes']) == true ? '(No comments entered)' : $lot['notes']);
			$updateTime = $lot['readiness_create_time'];
			$updateUser = $lot['readiness_create_user_name'];
		}
		else
		{
			$comment = $lot['comment'];
			$updateTime = $lot['status_create_time'];
			$updateUser = $lot['status_create_user_name'];
		}
?>
		<tr>
			<td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
			<td><?php echo $comment; ?></td>
			<td><?php echo $updateTime; ?></td>
			<td><?php echo $updateUser; ?></td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<?php
}

// Output Limited Availability lots
if($limited)
{
?>
<legend>Limited Availability</legend>
<table id="limited" class="tablesorter">
	<thead>
		<tr>
			<th>Lot Name</th>
			<th>Comment</th>
			<th>Last Update</th>
			<th>Updated By</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($limited as $lot)
	{
?>
		<tr>
			<td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
			<td><?php echo $lot['comment']; ?></td>
			<td><?php echo $lot['update_time']; ?></td>
			<td><?php echo $lot['status_create_user_name']; ?></td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<?php
}

// Output open lots
if($open)
{
?>
<legend>Open</legend>
<table id="open" class="tablesorter">
	<thead>
		<tr>
			<th>Lot Name</th>
			<th>Comment</th>
			<th>Last Update</th>
			<th>Updated By</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($open as $lot)
	{
?>
		<tr>
			<td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
			<td><?php echo $lot['comment']; ?></td>
			<td><?php echo $lot['update_time']; ?></td>
			<td><?php echo $lot['status_create_user_name']; ?></td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<?php
}

// Output closed lots
if($closed)
{
?>
<legend>Closed</legend>
<table id="closed" class="tablesorter">
	<thead>
		<tr>
			<th>Lot Name</th>
			<th>Comment</th>
			<th>Last Update</th>
			<th>Updated By</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($closed as $lot)
	{
?>
		<tr>
			<td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
			<td><?php echo $lot['comment']; ?></td>
			<td><?php echo $lot['update_time']; ?></td>
			<td><?php echo $lot['status_create_user_name']; ?></td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<?php
}
?>
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

		/* For the future
		// Test trafficcam link
		var camTestMarker = new google.maps.Marker({
			position: new google.maps.LatLng(40.829323,-77.805247),
			map: map,
			title: "SR 0150 & SR 0026"
		});

		var camTestContent = '<div id="content">'+
			'<h4>SR 00150 & SR 0026</h4>'+
			'<iframe style="height:100%;width:100%;" src="http://www.511pa.com/flowplayeri.aspx?CAMID=CAM-02-020&nocache=1429043634386"></iframe>'+
			'</div>';

		google.maps.event.addListener(camTestMarker, 'click', function() {
			infoWindow.close();
			infoWindow.setContent(camTestContent);
			infoWindow.open(map, camTestMarker);
  		});
		*/

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
        	'<p><b>Last Capacity Update:</b> <?php echo $lot['capacity_create_time']; ?></p>'+
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