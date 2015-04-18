<?php
    /**
     * Welcome/html/info.tpl
     * Contains the HTML template for the info subsection
     *
     * @author Cory Gehr
     */
?>
<h1>Parking Lot Readiness Application</h1>
<p>
	This site is designed to be a resource to the 
	parking staff of Penn State's Emergency Operations 
	Center to determine parking lot readiness and 
	overall status for events.
</p>
<p>
	To begin, please choose the type of user you are 
	below:
</p>
<ul>
	<li><a href="<?php echo \Thinker\Http\Url::create('Login', 'attendant'); ?>">Parking Lot Attendant</a></li>
	<li><a href="<?php echo \Thinker\Http\Url::create('Login', 'administrator'); ?>">Administrative Staff</a></li>
</ul>