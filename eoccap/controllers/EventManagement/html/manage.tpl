<?php
    /**
     * EventManagement/html/manage.tpl
     * Contains the HTML template for the manage subsection
     *
     * @author Cory Gehr
     */

// Get list of Events
$events = $this->get('EVENTS');

?>
<h1>Event Management</h1>
<form method="post">
	<legend id="addEvent"><a class="fsLink" onclick="showHideFieldset('addEvent')">Add New Event <span class="expandButton">[+]</span></a></legend>
	<fieldset id="addEvent" class="expandable" style="display:none">
		<p>
			<label for="name">Name<span class="required">*</span>:</label><br>
			<input name="name" required />
		</p>
		<p>
			<label for="start_date">Start Date<span class="required">*</span>:</label><br>
			<input type="date" id="start_date" name="start_date" class="datepicker" required />
		</p>
		<p>
			<label for="start_time">Start Time (24-hour HH:MM format)<span class="required">*</span>:</label><br>
			<input type="time" id="start_time" name="start_time" required />
		</p>
		<p>
			<label for="end_date">End Date<span class="required">*</span>:</label><br>
			<input type="date" id="end_date" name="end_date" class="datepicker" required />
		</p>
		<p>
			<label for="end_time">End Time (24-hour HH:MM format)<span class="required">*</span>:</label><br>
			<input type="time" id="end_time" name="end_time" required />
		</p>
		<input type="hidden" name="phase" value="addEvent" />
		<input type="submit" value="Add Event" />
	</fieldset>
</form>
<legend>Event List</legend>
<table id="event_list" class="tablesorter">
	<thead>
		<tr>
			<th>Name</th>
			<th>Start Time</th>
			<th>End Time</th>
		</tr>
	</thead>
<?php
	if($events)
	{
?>
	<tbody>
<?php
		// Output rows
		foreach($events as $event)
		{
?>
		<tr>
			<td><a href="<?php echo \Thinker\Http\Url::create('EventManagement', 'edit', array('id' => $event['id'])); ?>"><?php echo $event['name']; ?></a></td>
			<td><?php echo $event['start_time']; ?></td>
			<td><?php echo $event['end_time']; ?></td>
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
	No event information found.
</p>
<?php
	}
?>
<script type="text/javascript" src="html/psueoc/js/EventManagement/manage.js"></script>