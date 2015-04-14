<?php
    /**
     * LotConsole/html/manage.tpl
     * Contains the HTML template for the manage subsection
     *
     * @author Cory Gehr
     */

// Get our Lot Objects to work with
$targetLot = $this->get('Lot');
$targetCapacity = $this->get('Capacity');
$targetReadiness = $this->get('Readiness');
$targetStatus = $this->get('Status');
$possibleStatuses = $this->get('STATUSES');

// Simple function to determine if a select option should read 'selected'
function selected($val1, $val2)
{
	if($val1 === $val2)
	{
		return "selected";
	}

	return null;
}

?>
<h4><a href="<?php echo \Thinker\Http\Url::create('LotManagement'); ?>">Back to All Lots</a></h4>
<?php
if($targetLot)
{
?>
<h1><?php echo $targetLot->name; ?> Console</h1>
<legend id="currentInformation"><a class="fsLink" onclick="showHideFieldset('currentInformation')">Current Information <span class="expandButton">[-]</span></a></legend>
	<fieldset id="currentInformation">
		<div id="map-canvas" class="gmapsScaleConsole"></div>
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
				<span style="font-weight:bold;color:#<?php echo $targetStatus->Status->color; ?>"><?php echo $targetStatus->Status->name . ($targetStatus->isStale(24) == true ? " (Stale)" : ''); ?></span>
			</p>
			<p>
				<label for="capacity_update">Last Lot Capacity Update:</label><br>
				<?php echo $targetCapacity->create_time; ?><br>
			</p>
			<p>
				<label for="readiness_create_time">Last Lot Readiness Assessment:</label><br>
				<?php echo $targetReadiness->create_time; ?><br>
			</p>
			<p>
				<label for="location_name">Last Lot Information Update:</label><br>
				<?php echo $targetLot->update_time; ?><br>
			</p>
		</span>
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
<legend id="capacityHistory"><a class="fsLink" onclick="showHideFieldset('capacityHistory')">Capacity History <span class="expandButton">[+]</span></a></legend>
<fieldset id="capacityHistory" style="display:none">
	<p>
		Below are the last ten entries made for this lot:
	</p>
	<table id="capacity_history" class="tablesorter">
		<thead>
			<tr>
				<th>Date/Time</th>
				<th>Capacity</th>
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
			<td><?php echo $history['capacity']; ?>%</td>
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
	<legend id="updateReadiness"><a class="fsLink" onclick="showHideFieldset('updateReadiness')">Complete Lot Readiness Assessment <span class="expandButton"><?php echo ($targetReadiness->isStale() == false ? '[+]' : '[-]'); ?></span></a></legend>
	<fieldset id="updateReadiness"<?php echo ($targetReadiness->isStale() == false ? ' style="display:none"' : ''); ?>>
		<p>
			<label for="last_create_time">Last Completion Date/Time:</label><br>
			<?php echo $targetReadiness->create_time; ?>
		</p>
		<p>
			<label for="radios">Radios:</a></label><br>
			<select name="radios">
				<option value="">Select One:</option>
				<option value="1" <?php echo selected("1", $targetReadiness->radios); ?>>Ready</option>
				<option value="0" <?php echo selected("0", $targetReadiness->radios); ?>>Not Ready</option>
			</select>
		</p>
		<p>
			<label for="portajohns">Porta-Johns:</a></label><br>
			<select name="portajohns">
				<option value="">Select One:</option>
				<option value="1" <?php echo selected("1", $targetReadiness->portajohns); ?>>Ready</option>
				<option value="0" <?php echo selected("0", $targetReadiness->portajohns); ?>>Not Ready</option>
			</select>
		</p>
		<p>
			<label for="aframes">A-Frames:</a></label><br>
			<select name="aframes">
				<option value="">Select One:</option>
				<option value="1" <?php echo selected("1", $targetReadiness->aframes); ?>>Ready</option>
				<option value="0" <?php echo selected("0", $targetReadiness->aframes); ?>>Not Ready</option>
			</select>
		</p>
		<p>
			<label for="lighttowers">Light Towers:</a></label><br>
			<select name="lighttowers">
				<option value="">Select One:</option>
				<option value="1" <?php echo selected("1", $targetReadiness->lighttowers); ?>>Ready</option>
				<option value="0" <?php echo selected("0", $targetReadiness->lighttowers); ?>>Not Ready</option>
			</select>
		</p>
		<p>
			<label for="supervisor">Lot Supervisor:</a></label><br>
			<select name="supervisor">
				<option value="">Select One:</option>
				<option value="1" <?php echo selected("1", $targetReadiness->supervisor); ?>>Ready</option>
				<option value="0" <?php echo selected("0", $targetReadiness->supervisor); ?>>Not Ready</option>
			</select>
		</p>
		<p>
			<label for="parker">Lot Parker:</a></label><br>
			<select name="parker">
				<option value="">Select One:</option>
				<option value="1" <?php echo selected("1", $targetReadiness->parker); ?>>Ready</option>
				<option value="0" <?php echo selected("0", $targetReadiness->parker); ?>>Not Ready</option>
			</select>
		</p>
		<p>
			<label for="sellers">Seller(s):</a></label><br>
			<select name="sellers">
				<option value="">Select One:</option>
				<option value="1" <?php echo selected("1", $targetReadiness->sellers); ?>>Ready</option>
				<option value="0" <?php echo selected("0", $targetReadiness->sellers); ?>>Not Ready</option>
			</select>
		</p>
		<p>
			<label for="liaison">Parking Liaison:</a></label><br>
			<select name="liaison">
				<option value="">Select One:</option>
				<option value="1" <?php echo selected("1", $targetReadiness->liaison); ?>>Ready</option>
				<option value="0" <?php echo selected("0", $targetReadiness->liaison); ?>>Not Ready</option>
			</select>
		</p>
		<p>
			<label for="notes">Notes:</a></label><br>
			<textarea name="notes"><?php echo $targetReadiness->notes; ?></textarea>
		</p>
		<input type="hidden" name="id" value="<?php echo $targetLot->id; ?>" />
		<input type="hidden" name="phase" value="updateReadiness" />
		<input type="submit" value="Complete Readiness Assessment" />
	</fieldset>
</form>
<?php
	if($_SESSION['USER']->user_type == 1)
	{
?>
<form method="post">
	<legend id="updateStatus"><a class="fsLink" onclick="showHideFieldset('updateStatus')">Update Lot Status <span class="expandButton">[+]</span></a></legend>
	<fieldset id="updateStatus" style="display:none">
		<p>
			<label for="status">Status<span class="required">*</span>:</label><br>
			<select name="status" required>
<?php
		// Echo status choices and select the current status by default
		if($possibleStatuses)
		{
			foreach($possibleStatuses as $s)
			{
?>
				<option value="<?php echo $s['id']; ?>" <?php echo selected($s['id'], $targetStatus->status_id); ?>><?php echo $s['name']; ?></option>			
<?php
			}
		}
?>
			</select>
		</p>
		<p>
			<label for="comment">Comment:</label><br>
			<textarea name="comment"></textarea>
		</p>
		<input type="hidden" name="id" value="<?php echo $targetLot->id; ?>" />
		<input type="hidden" name="phase" value="updateStatus" />
		<input type="submit" value="Update Status" />
	</fieldset>
</form>
<form method="post">
	<legend id="updateInformation"><a class="fsLink" onclick="showHideFieldset('updateInformation')">Update Lot Information <span class="expandButton">[+]</span></a></legend>
	<fieldset id="updateInformation" style="display:none">
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
		<input type="hidden" name="phase" value="updateInformation" />
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
<legend id="contactInformation"><a class="fsLink" onclick="showHideFieldset('contactInformation')">Contact Information <span class="expandButton">[+]</span></a></legend>
<fieldset id="contactInformation" style="display:none">
	<ul>
		<li><b>Emergency:</b> 814-863-1111</li>
		<li><b>Stadium Operations:</b> 814-863-1548 or 814-863-15409</li>
		<li><b>Customer Service:</b> 1-800-NITTANY</li>
		<li><b>Text Hotline:</b> Text to 69050 the word NITTANY followed by your name and location. Stadium personnel will respond</li>
	</ul>
</fieldset>
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
  		});

  		lotInfo.open(map,lotMarker);
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php
}
?>