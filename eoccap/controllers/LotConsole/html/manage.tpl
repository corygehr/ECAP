<?php
    /**
     * LotConsole/html/manage.tpl
     * Contains the HTML template for the manage subsection
     *
     * @author Cory Gehr
     */

// Get our Lot Object to work with
$targetLot = $this->get('Lot');
$targetCapacity = $this->get('Capacity');

?>
<h4><a href="<?php echo \Thinker\Http\Url::create('LotManagement'); ?>">Back to All Lots</a></h4>
<?php
if($targetLot)
{
?>
<h1><?php echo $targetLot->name; ?> Console</h1>
<legend id="currentInformation"><a class="fsLink" onclick="showHideFieldset('currentInformation')">Current Information <span class="expandButton">[-]</span></a></legend>
	<fieldset id="currentInformation">
		<span style="float:left">
			<p>
				<label for="location_name">Location Name:</label><br>
				<?php echo $targetLot->location_name; ?>
			</p>
			<p>
				<label for="current_capacity">Current Capacity:</label><br>
				<?php echo $targetCapacity->capacity; ?>%
			</p>
			<p>
				<label for="status">Current Status:</label><br>
				<?php echo "Open"; ?>
			</p>
			<p>
				<label for="attendance_update">Last Lot Attendance Update:</label><br>
				<?php echo $targetCapacity->create_time; ?><br>
			</p>
			<p>
				<label for="location_name">Last Lot Information Update:</label><br>
				<?php echo $targetLot->update_time; ?><br>
			</p>
		</span>
	<div id="map-canvas" style="float:right; min-width: 380px; height: 350px;"></div>
</fieldset>
<form method="post">
	<legend id="updateCapacity"><a class="fsLink" onclick="showHideFieldset('updateCapacity')">Update Capacity <span class="expandButton">[-]</span></a></legend>
	<fieldset id="updateCapacity">
		<p>
			<label for="attendance">Current Capacity<span class="required">*</span>:</label><br>
			<input type="number" name="attendance" value="<?php echo $targetCapacity->capacity; ?>" required />%
		</p>
		<input type="hidden" name="id" value="<?php echo $targetLot->id; ?>" />
		<input type="hidden" name="phase" value="updateCapacity" />
		<input type="submit" value="Update Capacity" />
	</fieldset>
</form>
<form method="post">
	<legend id="completeReadiness"><a class="fsLink" onclick="showHideFieldset('completeReadiness')">Update Readiness <span class="expandButton">[+]</span></a></legend>
	<fieldset id="completeReadiness" style="display:none">
		
		<input type="hidden" name="id" value="<?php echo $targetLot->id; ?>" />
		<input type="hidden" name="phase" value="completeReadiness" />
		<input type="submit" value="Update Readiness" />
	</fieldset>
</form>
<legend id="capacityHistory"><a class="fsLink" onclick="showHideFieldset('capacityHistory')">Attendance History <span class="expandButton">[+]</span></a></legend>
<fieldset id="capacityHistory" style="display:none">
	<p>
		Below are the last ten entries made for this lot:
	</p>
	<table id="attendance_history" class="tablesorter">
		<thead>
			<tr>
				<th>Date/Time</th>
				<th>Attendance</th>
				<th>Change (from Previous Entry)</th>
				<th>Recording User</th>
			</tr>
		</thead>
<?php
	// Get the lots
	$capacityHistory = $this->get('CAPACITY_HISTORY');

	if($capacityHistory)
	{
?>
	<tbody>
<?php
		// Keep track of the current index
		$count = 0;

		// Output rows
		foreach($capacityHistory as $history)
		{
			// Find out if capacity went up or down from the previous value
			$prevString = "";

			// Perform checking while we're in bounds of the array
			if($count+1 < count($capacityHistory))
			{
				$ind = $count+1;

				$change = $history['capacity'] - $capacityHistory[$ind]['capacity'];

				if($change < 0)
				{
					$prevString = '<span style="font-size:1.25em;font-weight:bold;color:red;">&darr;</span> ' . $change;
				}
				elseif($change > 0)
				{
					$prevString = '<span style="font-size:1.25em;font-weight:bold;color:green;">&uarr;</span> ' . $change;
				}
				else // $capacityHistory[$ind]['attendance'] == $history['attendance']
				{
					$prevString = '<b>=</b> 0';
				}
			}

?>
		<tr>
			<td><?php echo $history['create_time']; ?></td>
			<td><?php echo $history['capacity']; ?></td>
			<td><?php echo $prevString; ?></td>
			<td><?php echo $history['create_user_name']; ?></td>
		</tr>
<?php
			// Update counter
			$count++;
		}
?>
	</tbody>
</table>
<?php
	}
	else
	{
?>
</table>
<p>
	No capacity history found.
</p>
<?php
	}
?>
	</table>
</fieldset>
<form method="post">
	<legend id="updateDetails"><a class="fsLink" onclick="showHideFieldset('updateDetails')">Update Lot Details <span class="expandButton">[+]</span></a></legend>
	<fieldset id="updateDetails" style="display:none">
		<p>
			<label for="name">Lot Name<span class="required">*</span>:</label><br>
			<input name="name" value="<?php echo $targetLot->name; ?>" required />
		</p>
		<p>
			<label for="color">Lot Color:</label><br>
			<input name="color" value="<?php echo $targetLot->color; ?>" />
		</p>
		<p>
			<label for="location_name">Location Name<span class="required">*</span>:</label><br>
			<input name="location_name" value="<?php echo $targetLot->location_name; ?>" required />
		</p>
		<p>
			<label for="latitude">Latitude:</label><br>
			<input type="number" step="any" name="latitude" value="<?php echo $targetLot->latitude; ?>" />
		</p>
		<p>
			<label for="longitude">Longitude:</label><br>
			<input type="number" step="any" name="longitude" value="<?php echo $targetLot->longitude; ?>" />
		</p>
		<p>
			<label for="max_capacity">Maximum Capacity<span class="required">*</span>:</label><br>
			<input name="max_capacity" value="<?php echo $targetLot->max_capacity; ?>" required />
		</p>
		<input type="hidden" name="id" value="<?php echo $targetLot->id; ?>" />
		<input type="hidden" name="phase" value="updateDetails" />
		<input type="submit" value="Update Details" />
	</fieldset>
</form>
<form id="deleteLot" method="post">
	<legend id="deleteLot"><a class="fsLink" onclick="showHideFieldset('deleteLot')">Delete Lot <span class="expandButton">[+]</span></a></legend>
	<fieldset id="deleteLot" style="display:none">
		<p>
			<b>WARNING!</b> This action cannot be undone.
		</p>
		<input type="hidden" name="id" value="<?php echo $targetLot->id; ?>" />
		<input type="hidden" name="phase" value="deleteLot" />
		<input type="submit" value="Delete Lot" />
	</fieldset>
</form>
<?php
}
else
{
?>
<h1>(Unavailable)</h1>
<p>
	No lot information to load.
</p>
<?php
}
?>
<script src="html/psueoc/js/LotConsole/manage.js"></script>
<?php
// Output Google Maps if we have lat+lng info
if($targetLot->latitude && $targetLot->longitude)
{
?>
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript">
	// Google Maps
	function initialize() {
        var lotLoc = new google.maps.LatLng(<?php echo $targetLot->latitude . "," . $targetLot->longitude; ?>);

        var mapOptions = {
          center: lotLoc,
          zoom: 16,
          mapTypeId: google.maps.MapTypeId.HYBRID
        }

        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions)

        var lotMarker = new google.maps.Marker({
		    position: lotLoc,
		    map: map,
		    title: "<?php echo $targetLot->name; ?>"
		});

        var contentHtml = '<div id="content">'+
        	'<h4><?php echo $targetLot->name . " (" . $targetLot->location_name . ")"; ?></h4>'+
        	'<p><b>Current Capacity:</b> <?php echo $targetCapacity->attendance; ?>%</p>'+
        	'</div>';

		var lotInfo = new google.maps.InfoWindow({
      		content: contentHtml
 		});

		google.maps.event.addListener(lotMarker, 'click', function() {
    		lotInfo.open(map,lotMarker);
  		});

  		lotInfo.open(map,lotMarker);
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php
}
?>