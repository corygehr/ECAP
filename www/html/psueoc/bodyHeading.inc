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
        <img src="html/psueoc/img/psu_logo.png" alt="Penn State University Logo" width="230px" height="97px" />
        <br>
        <h2>Emergency Operations Capacity Management</h2>
        <br>
      </div>
      <!--END MASTHEAD-->
      <!--START NAV MENU-->
      <nav>
        <ul class="nav nav-justified">
            <li class="active">
              <a href="/">Home</a>
            </li>
            <li>
              <a href="<?php echo \Thinker\Http\Url::create('Dashboard'); ?>">Dashboard</a>
            </li>
            <li>
              <a href="<?php echo \Thinker\Http\Url::create('LotManagement'); ?>">Manage Lots</a>
            </li>
            <li>
              <a href="<?php echo \Thinker\Http\Url::create('SiteManagement'); ?>">Site Management</a>
            </li>      
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