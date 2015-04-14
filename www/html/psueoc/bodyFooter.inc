<?php
	/**
 	 * bodyFooter.inc
 	 * Contains the footer for each THINKer page
	 *
	 * @author Cory Gehr
	 */
?>
    </div>
    <!--END WORKING BODY-->

    <!--BEGIN FOOTER-->

    <footer class="footer">
<?php
  // Display section for user information if logged in
  if(isset($_SESSION['USER']))
  {
?>
        <p>
        	Logged in as <b><?php echo $_SESSION['USER']->full_name; ?></b> (<a href="<?php echo \Thinker\Http\Url::create('Logout', 'handle'); ?>")>Logout</a>)
        </p>
<?php
  }
?>
        <p>
        	Copyright &copy; <script>document.write(new Date().getFullYear())</script> 
        	<a href="http://psu.edu/" target="_blank">The Pennsylvania State University</a> | 
        	<a href="http://www.psu.edu/ur/legal.html" target="_blanK">Privacy and Legal Statements</a> <br>
    </footer>
    <!--END FOOTER-->
    </div>
    <!--END CONTAINER-->
    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="html/psueoc/js/bootstrap.min.js"></script>