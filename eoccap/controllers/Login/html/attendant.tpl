<?php
    /**
     * Login/html/attendant.tpl
     * Contains the HTML template for the attendant subsection
     */
?>
<h1>Lot Attendant Login</h1>
<p>
	Please log into the system below using the credentials you were provided.
</p>
<form method="post">
	<fieldset>
		<legend>Credentials</legend>
		<p>
			<label for="username">Access Code<span class="required">*</span>:</label><br>
			<input name="username" required />
		</p>
		<input type="hidden" name="phase" value="login" />
		<input type="submit" value="Login" />
	</fieldset>
</form>