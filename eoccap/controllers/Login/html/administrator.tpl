<?php
    /**
     * Login/html/administrator.tpl
     * Contains the HTML template for the info subsection
     */
?>
<h1>Administrator Login</h1>
<p>
	Please log into the system below using the credentials you were provided.
</p>
<form method="post">
	<fieldset>
		<legend>Credentials</legend>
		<p>
			<label for="username">Username<span class="required">*</span>:</label><br>
			<input name="username" required />
		</p>
		<p>
			<label for="password">Password<span class="required">*</span>:</label><br>
			<input type="password" name="password" required />
		</p>
		<input type="hidden" name="phase" value="login" />
		<input type="submit" value="Login" />
	</fieldset>
</form>