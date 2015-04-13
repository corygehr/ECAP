<?php
    /**
     * LotConsole/html/manage.tpl
     * Contains the HTML template for the manage subsection
     *
     * @author Cory Gehr
     */

// Get our Lot Object to work with
$targetLot = $this->get('Lot');
$targetAttendance = $this->get('Attendance');

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
				<label for="current_attendance">Current Attendance:</label><br>
				<?php echo  $targetAttendance->attendance . "/" . $targetLot->max_capacity; ?>
			</p>
			<p>
				<label for="status">Current Status:</label><br>
				<?php echo "Open"; ?>
			</p>
			<p>
				<label for="attendance_update">Last Lot Attendance Update:</label><br>
				<?php echo $targetAttendance->create_time; ?><br>
			</p>
			<p>
				<label for="location_name">Last Lot Information Update:</label><br>
				<?php echo $targetLot->update_time; ?><br>
			</p>
		</span>
	<div id="map-canvas" style="float:right; min-width: 380px; height: 350px;"></div>
</fieldset>
<form method="post">
	<legend id="updateAttendance"><a class="fsLink" onclick="showHideFieldset('updateAttendance')">Update Attendance <span class="expandButton">[-]</span></a></legend>
	<fieldset id="updateAttendance">
		<p>
			<label for="attendance">Current Attendance<span class="required">*</span>:</label><br>
			<input type="number" name="attendance" value="<?php echo $targetAttendance->attendance; ?>" required />
		</p>
		<input type="hidden" name="id" value="<?php echo $targetLot->id; ?>" />
		<input type="hidden" name="phase" value="updateAttendance" />
		<input type="submit" value="Update Attendance" />
	</fieldset>
</form>
<form method="post">
	<legend id="updateReadiness"><a class="fsLink" onclick="showHideFieldset('updateReadiness')">Update Readiness <span class="expandButton">[+]</span></a></legend>
	<fieldset id="updateReadiness" style="display:none">
		<p>
			<label for="status">Current Status<span class="required">*</span>:</label><br>
			<select name="status" required>
				<option value="Open">Open</option>
				<option value="Closed">Closed</option>
				<option value="Limited">Limited Availability</option>
			</select>
		</p>
		<p>
			<label for="readiness_note">Comment<span class="required">*</span>:</label><br>
			<textarea name="readiness_comment"></textarea>
		</p>
		<p>
			<label for="notify">Notify EOC?</label><br>
			<input type="checkbox" name="notify" />
		</p>
		<p>
			By checking 'Notify', the EOC staff will be alerted with your comment. Use this 
			if you need supplies or need to mark hazards that need to be cleared.
		</p>
		<input type="hidden" name="id" value="<?php echo $targetLot->id; ?>" />
		<input type="hidden" name="phase" value="updateReadiness" />
		<input type="submit" value="Update Readiness" />
	</fieldset>
</form>
<legend id="attendanceHistory"><a class="fsLink" onclick="showHideFieldset('attendanceHistory')">Attendance History <span class="expandButton">[+]</span></a></legend>
<fieldset id="attendanceHistory" style="display:none">
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
	$attendanceHistory = $this->get('ATTENDANCE_HISTORY');

	if($attendanceHistory)
	{
?>
	<tbody>
<?php
		// Keep track of the current index
		$count = 0;

		// Output rows
		foreach($attendanceHistory as $history)
		{
			// Find out if attendance went up or down from the previous value
			$prevString = "";

			// Perform checking while we're in bounds of the array
			if($count+1 < count($attendanceHistory))
			{
				$ind = $count+1;

				$change = $history['attendance'] - $attendanceHistory[$ind]['attendance'];

				if($change < 0)
				{
					$prevString = '<span style="font-size:1.25em;font-weight:bold;color:red;">&darr;</span> ' . $change;
				}
				elseif($change > 0)
				{
					$prevString = '<span style="font-size:1.25em;font-weight:bold;color:green;">&uarr;</span> ' . $change;
				}
				else // $attendanceHistory[$ind]['attendance'] == $history['attendance']
				{
					$prevString = '<b>=</b> 0';
				}
			}

?>
		<tr>
			<td><?php echo $history['create_time']; ?></td>
			<td><?php echo $history['attendance']; ?></td>
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
	No attendance history found.
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
        	'<p><b>Current Attendance:</b> <?php echo $targetAttendance->attendance . "/" . $targetLot->max_capacity; ?></p>'+
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