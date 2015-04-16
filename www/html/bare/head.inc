<?php
	/**
	 * head.inc
	 * Contains the HTML Head Tags
	 *
	 * @author Cory Gehr
	 */

?>
	<title>
<?php
    if($viewSettings && isset($viewSettings[SUBSECTION]['title']))
    {
        echo $viewSettings[SUBSECTION]['title'];
    }
    elseif($viewSettings && isset($viewSettings['defaults']['default_title']))
    {
        echo $viewSettings['defaults']['default_title'];
    }
    else
    {
        echo "THINKer";
    }
?>
	</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link rel='icon' type='image/png' href='View/html/shared/images/favicon.ico' />