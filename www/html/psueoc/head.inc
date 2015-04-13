<?php
	/**
	 * head.inc
	 * Contains the HTML Header for THINKer
	 *
	 * @author Cory Gehr
	 */

?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
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

    <!-- Bootstrap core css -->
    <link href="html/psueoc/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery-ui CSS -->
    <link href="html/psueoc/css/jquery-ui.min.css" rel="stylesheet">
    <link href="html/psueoc/css/jquery-ui.theme.min.css" rel="stylesheet">

    <!--Custom CSS-->
    <link href="html/psueoc/css/eoc.css" rel="stylesheet">
    <link href="html/psueoc/css/justified-nav.css" rel="stylesheet">
    <link rel="shortcut icon" href="html/psueoc/img/favicon.ico" type="image/vnd.microsoft.icon" />

    <!-- jQuery 1.9.1 -->
    <script type='text/javascript' src='https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.1.min.js'></script>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- jQuery UI 1.10.3.custom -->
    <script type='text/javascript' src='html/psueoc/js/jquery-ui.min.js'></script>

    <!-- jQuery Validate -->
    <script type='text/javascript' src="html/psueoc/js/jquery.validate.min.js"></script>
    <!-- Additional Methods also contains PSU Access ID Validation -->
    <script type='text/javascript' src='html/psueoc/js/additional-methods.min.js'></script>

    <!-- Tablesorter -->
    <script type='text/javascript' src='html/psueoc/js/jquery.tablesorter.min.js'></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--Custom Functions-->
    <script type="text/javascript" src="html/psueoc/js/common.js"></script>