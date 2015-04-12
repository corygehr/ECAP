<?php
    /**
     * LotConsole/html/manage.tpl
     * Contains the HTML template for the manage subsection
     *
     * @author Cory Gehr
     */

// Get our Lot Object to work with
$targetLot = $this->get('Lot');
?>
<h4><a href="<?php echo \Thinker\Http\Url::create('LotManagement'); ?>">Back to All Lots</a></h4>
<?php
if($targetLot)
{
?>
<h1><?php echo $targetLot->name; ?></h1>
<fieldset>
	<legend>Current Information</legend>
	<label for="location_name">Location Name:</label><br>
	<?php echo $targetLot->location_name; ?>
</fieldset>
<br>
<form method="post">
	<fieldset>
		<legend>Update Attendance</legend>
		<p>
			<label for="current_capacity">Current Attendance<span class="required">*</span>:</label><br>
			<input type="number" name="current_capacity" value="<?php echo 0; ?>" required />
		</p>
		<input type="hidden" name="id" value="<?php echo $targetLot->id; ?>" />
		<input type="hidden" name="phase" value="updateAttendance" />
		<input type="submit" value="Update Attendance" />
	</fieldset>
</form>
<br>
<form method="post">
	<fieldset>
		<legend>Update Readiness</legend>
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
<br>
<form method="post">
	<fieldset>
		<legend>Update Lot Information</legend>
		<p>
			<label for="name">Lot Name<span class="required">*</span>:</label><br>
			<input name="name" value="<?php echo $targetLot->name; ?>" required />
		</p>
		<p>
			<label for="color">Lot Color:</label><br>
			<input name="color" value="<?php echo $targetLot->color; ?>" />
		</p>
		<p>
			<label for="latitude">Latitude:</label><br>
			<input type="number" step="any" name="latitude" value="<?php echo $targetLot->latitude; ?>" />
		</p>
		<p>
			<label for="longitude">Longitude:</label><br>
			<input type="number" step="any" name="longitude" value="<?php echo $targetLot->longitude; ?>" />
		</p>
		<input type="hidden" name="id" value="<?php echo $targetLot->id; ?>" />
		<input type="hidden" name="phase" value="updateInformation" />
		<input type="submit" value="Update Information" />
	</fieldset>
</form>
<br>
<form method="post">
	<fieldset>
		<legend>Delete Lot</legend>
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