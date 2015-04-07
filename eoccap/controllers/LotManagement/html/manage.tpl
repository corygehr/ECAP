<?php
    /**
     * LotManagement/html/manage.tpl
     * Contains the HTML template for the manage subsection
     *
     * @author Cory Gehr
     */
?>
<h1>Manage Lot</h1>
<p>
	Existing Lots
</p>
<table>
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
			<td><a href="
				<?php echo \Thinker\Html\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
			<td><?php echo $lot['location_name']; ?></td>
			<td><?php echo $lot['max_capacity']; ?></td>
		</tr>
<?php
		}
?>
	</tbody>
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