<?php
    /**
     * EventManagement/html/edit.tpl
     * Contains the HTML template for the edit subsection
     *
     * @author Cory Gehr
     */

// Get list of Events
$event = $this->get('event');

if($event)
{
?>
<h1>Edit <?php echo $event->name; ?> Event</h1>
<form method="post">
	<legend id="updateEvent"><a class="fsLink" onclick="showHideFieldset('updateEvent')">Update Event <span class="expandButton">[-]</span></a></legend>
	<fieldset id="updateEvent" class="expandable">
		<p>
			<label for="name">Name<span class="required">*</span>:</label><br>
			<input name="name" value="<?php echo $event->name; ?>" required />
		</p>
		<p>
			<label for="start_date">Start Date<span class="required">*</span>:</label><br>
			<input type="date" id="start_date" name="start_date" class="datepicker" value="<?php echo date("Y-m-d", strtotime($event->start_time)); ?>" required />
		</p>
		<p>
			<label for="start_time">Start Time<span class="required">*</span>:</label><br>
			<input type="time" id="start_time" name="start_time" value="<?php echo date("H:i", strtotime($event->start_time)); ?>" required />
		</p>
		<p>
			<label for="end_date">End Date<span class="required">*</span>:</label><br>
			<input type="date" id="end_date" name="end_date" class="datepicker" value="<?php echo date("Y-m-d", strtotime($event->end_time)); ?>" required />
		</p>
		<p>
			<label for="end_time">End Time<span class="required">*</span>:</label><br>
			<input type="time" id="end_time" name="end_time" value="<?php echo date("H:i", strtotime($event->end_time)); ?>" required />
		</p>
		<input type="hidden" name="id" value="<?php echo $event->id; ?>" />
		<input type="hidden" name="phase" value="updateEvent" />
		<input type="submit" value="Update Event" />
	</fieldset>
</form>
<form id="deleteEvent" method="post">
	<legend id="deleteEvent"><a class="fsLink" onclick="showHideFieldset('deleteEvent')">Delete Event <span class="expandButton">[+]</span></a></legend>
	<fieldset id="deleteEvent" style="display:none">
		<p>
			<b>WARNING!</b> This action cannot be undone.
		</p>
		<input type="hidden" name="id" value="<?php echo $event->id; ?>" />
		<input type="hidden" name="phase" value="deleteEvent" />
		<input type="submit" value="Delete Event" />
	</fieldset>
</form>
<?php
}
else
{
?>
<h1>(Unavailable)</h1>
<p>
	No event information found.
</p>
<?php
}
?>
<script type="text/javascript" src="html/psueoc/js/EventManagement/edit.js"></script>