<?php
    /**
     * LotManagement/html/manage.tpl
     * Contains the HTML template for the manage subsection
     *
     * @author Cory Gehr
     */
?>
<h1>Manage Lots</h1>
<form method="post">
	<legend id="addLot"><a class="fsLink" onclick="showHideFieldset('addLot')">Add Lot <span class="expandButton">[+]</span></a></legend>
	<fieldset id="addLot" style="display:none">
		<p>
			<label for="name">Lot Name<span class="required">*</span>:</label><br>
			<input name="name" required />
		</p>
		<p>
			<label for="color">Lot Color:</label><br>
			<input name="color" />
		</p>
		<p>
			<label for="max_capacity">Maximum Capacity<span class="required">*</span>:</label><br>
			<input type="number" name="max_capacity" required />
		</p>
		<p>
			<label for="location_name">Location Name<span class="required">*</span>:</label><br>
			<input name="location_name" required />
		</p>
		<p>
			<label for="latitude">Latitude:</label><br>
			<input type="number" name="latitude" step="any" />
		</p>
		<p>
			<label for="longitude">Longitude:</label><br>
			<input type="number" name="longitude" step="any" />
		</p>
		<input type="hidden" name="phase" value="addLot" />
		<input type="submit" value="Add Lot" />
	</fieldset>
</form>
<legend>Active Lots</legend>
<table id="lot_list" class="tablesorter">
	<thead>
		<tr>
			<th>Lot Name</th>
			<th>Location</th>
			<th>Capacity</th>
		</tr>
	</thead>
<?php
	// Get the lots
	$lots = $this->get('LOTS');

	if($lots)
	{
?>
	<tbody>
<?php
		// Output rows
		foreach($lots as $lot)
		{
?>
		<tr>
			<td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
			<td><?php echo $lot['location_name']; ?></td>
			<td><?php echo $lot['max_capacity']; ?></td>
		</tr>
<?php
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
	No lot information found.
</p>
<?php
	}
?>
<script type="text/javascript" src="html/psueoc/js/LotManagement/manage.js"></script>