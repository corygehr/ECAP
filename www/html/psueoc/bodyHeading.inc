<?php
	/**
	 * bodyHeading.inc
	 * Contains the heading for each THINKer page
	 *
	 * @author Cory Gehr
	 */
?>
    <!--BEGIN CONTAINER-->
    <div class="container">
    	<!--BEGIN MASTHEAD-->
    	<div class="masthead">
        <img src="html/psueoc/img/psu_logo.png" alt="Penn State University Logo" width="230" height="97" />
        <br>
        <h2>Penn State Event Parking Management</h2>
        <br>
     </div>
     <!--END MASTHEAD-->
     <!--START NAV MENU-->
     <nav>
        <ul class="nav nav-justified">
<?php
	if(isset($_SESSION['USER']))
	{
		switch($_SESSION['USER']->user_type)
		{
			case 1:
?>
			<li<?php echo (SECTION == 'Dashboard' ? " class='active'" : ''); ?>>
			 	<a href="<?php echo \Thinker\Http\Url::create('Dashboard'); ?>">Dashboard</a>
			</li>
			<li<?php echo (SECTION == 'LotManagement' || SECTION == 'LotConsole' ? " class='active'" : ''); ?>>
				<a href="<?php echo \Thinker\Http\Url::create('LotManagement'); ?>">Manage Lots</a>
			</li>
			<li<?php echo (SECTION == 'SiteManagement' ? " class='active'" : ''); ?>>
				<a href="<?php echo \Thinker\Http\Url::create('SiteManagement'); ?>">Site Management</a>
			</li>
<?php
			break;

			case 2:
?>
			<li<?php echo (SECTION == 'MyLots' ? " class='active'" : ''); ?>>
				<a href="<?php echo \Thinker\Http\Url::create('MyLots'); ?>">My Lots</a>
			</li>
<?php
			break;

			default:
?>
            <li<?php echo (SECTION == 'Welcome' ? " class='active'" : ''); ?>>
            	<a href="<?php echo \Thinker\Http\Url::create('Welcome'); ?>">Home</a>
            </li>
<?php
			break;
        }
    }
    else
    {
    	// Only output homepage link
?>
            <li<?php echo (SECTION == 'Welcome' ? " class='active'" : ''); ?>>
            	<a href="<?php echo \Thinker\Http\Url::create('Welcome'); ?>">Home</a>
            </li>
<?php
    }
?>   
        </ul>
     </nav>
     <!--END NAV MENU-->
     <!-- BEGIN WORKING BODY {PHP}-->
     <div class="body2">
<?php
	// Output notifications
	if(!empty($_MESSAGES))
	{
		foreach($_MESSAGES as $msg)
		{
			list($text, $level) = $msg;

			$output = "<div class='notification ";

			switch($level)
			{
				case 'error':
					$output .= "error'><p><span class='ui-icon ui-icon-circle-close' style='float: left; margin-right: .3em;'></span><strong>Error:</strong> $text</p></div>";
				break;

				case 'success':
					$output .= "success'><p><span class='ui-icon ui-icon-check' style='float: left; margin-right: .3em;'></span><strong>Success:</strong> $text</p></div>";
				break;

				case 'warning':
					$output .= "warning'><p><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'></span><strong>Warning:</strong> $text</p></div>";
				break;

				default:
					$output .= "info'><p><span class='ui-icon ui-icon-info' style='float: left; margin-right: .3em;'></span><strong>Info:</strong> $text</p></div>";
				break;
			}

			echo $output;
		}
	}
?>