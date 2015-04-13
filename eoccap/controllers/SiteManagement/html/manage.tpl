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
	<legend id="addUser"><a class="fsLink" onclick="showHideFieldset('addUser')">Authorize New User <span class="expandButton">[+]</span></a></legend>
	<fieldset id="addUser" class="expandable" style="display:none">
		<p>
			<label for="username">Username<span class="required">*</span>:</label><br>
			<input name="username" required />
		</p>
		<p>
			<label for="full_name">Full Name<span class="required">*</span>:</label><br>
			<input name="full_name" required />
		</p>
		<p>
			<label for="access_type">Type<span class="required">*</span>:</label><br>
			<select id="access_type" name="access_type" required>
				<option value="">Select One:</label>
<?php
	// Output list of User Types
	$types = $this->get('USER_TYPES');

	if($types)
	{
		foreach($types as $type)
		{
?>
				<option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
<?php
		}
	}
?>
			</select>
		</p>
		<p id="password" style="display:none">
			<label for="password">Password (only for Administrators)<span class="required">*</span>:</label><br>
			<input type="password" id="password" name="password" disabled />
		</p>
		<p id="lot" style="display:none">
			<label for="lot">Responsible Lot (only for Attendants)<span class="required">*</span>:</label><br>
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
<legend>Authorized User Accounts</legend>
<table id="user_list" class="tablesorter">
	<thead>
		<tr>
			<th>Username</th>
			<th>Full Name</th>
			<th>User Type</th>
		</tr>
	</thead>
<?php
	// Get the lots
	$users = $this->get('USERS');

	if($users)
	{
?>
	<tbody>
<?php
		// Output rows
		foreach($users as $user)
		{
?>
		<tr>
			<td><a href="<?php echo \Thinker\Http\Url::create('UserManagement', 'manage', array('username' => $user['username'])); ?>"><?php echo $user['username']; ?></a></td>
			<td><?php echo $user['full_name']; ?></td>
			<td><?php echo $user['user_type_name']; ?></td>
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
	No user information found.
</p>
<?php
	}
?>
<script type="text/javascript" src="html/psueoc/js/SiteManagement/manage.js"></script>