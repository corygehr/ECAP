<?php
    /**
     * SiteManagement/html/manage.tpl
     * Contains the HTML template for the manage subsection
	*
     *
     * @author Cory Gehr
     */

// Get list of lots
$lots = $this->get('LOTS');

?>
<h1>Site Management</h1>
<form method="post">
	<legend id="userAdd"><a class="fsLink" onclick="showHideFieldset('userAdd')">Authorize New User <span class="expandButton">[+]</span></a></legend>
	<fieldset id="userAdd" class="expandable" style="display:none">
		<p>
			<label for="username">Username<span class="required">*</span>:</label><br>
			<input name="username" required />
		</p>
		<p>
			<label for="full_name">Full Name<span class="required">*</span>:</label><br>
			<input name="full_name" required />
		</p>
		<p>
			<label for="access_type">Access Type<span class="required">*</span>:</label><br>
			<select id="access_type" name="access_type" required>
				<option value="">Select One:</label>
				<option value="admin">Administrator</label>
				<option value="attendant">Lot Attendant</label>
			</select>
		</p>
		<p>
			<label for="lot">Responsible Lot (only for Attendants):</label><br>
			<select id="lot" name="lot" disabled>
				<option value="">Select One:</option>
<?php
	// Output all lots the user could be responsible for
	if($lots)
	{
		foreach($lots as $lot)
		{
?>
				<option value="<?php echo $lot['id']; ?>"><?php echo $lot['name']; ?></option>
<?php
		}
	}
?>
			</select>
		</p>
		<input type="hidden" name="phase" value="addUser" />
		<input type="submit" value="Add User" />
	</fieldset>
</form>
<fieldset>
	<legend>Authorized User Accounts</legend>
</fieldset>
<script type="text/javascript" src="html/psueoc/js/SiteManagement/manage.js"></script>