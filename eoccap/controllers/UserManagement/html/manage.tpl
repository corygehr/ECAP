<?php
    /**
     * UserManagement/html/manage.tpl
     * Contains the HTML template for the manage subsection
     *
     * @author Cory Gehr
     */

// Get the target user
$targetUser = $this->get('User');
$lots = $this->get('LOTS');
$userLots = $this->get('USER_LOTS');

if($targetUser)
{
?>
<h1><?php echo $targetUser->full_name; ?></h1>
<form method="post">
     <legend id="userInformation"><a class="fsLink" onclick="showHideFieldset('userInformation')">User Information <span class="expandButton">[-]</span></a></legend>
     <fieldset id="userInformation" class="expandable">
          <p>
               <label for="user_type">Type:</label><br>
               <?php echo $this->get('user_type_name'); ?>
          </p>
          <p>
               <label for="username">Username:</label><br>
               <?php echo $targetUser->username; ?>
          </p>
          <p>
               <label for="full_name">Full Name<span class="required">*</span>:</label><br>
               <input name="full_name" value ="<?php echo $targetUser->full_name; ?>" required />
          </p>
          <input type="hidden" name="phase" value="updateInformation" />
          <input type="hidden" name="username" value="<?php echo $targetUser->username; ?>" />
          <input type="submit" value="Update User" />
     </fieldset>
</form>
<?php
     if($targetUser->user_type == 2)
     {
          // Allow lot assignment for Attendants
?>
<form method="post">
     <legend id="addRight"><a class="fsLink" onclick="showHideFieldset('addRight')">Add Responsible Lot <span class="expandButton">[+]</span></a></legend>
     <fieldset id="addRight" class="expandable" style="display:none">
          <p>
               <label for="lot">Lot<span class="required">*</span>:</label><br>
               <select name="lot" required>
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
          <input type="hidden" name="phase" value="addRight" />
          <input type="hidden" name="username" value="<?php echo $targetUser->username; ?>" />
          <input type="submit" value="Add Responsible Lot" />
     </fieldset>
</form>
<legend id="responsibleLots"><a class="fsLink" onclick="showHideFieldset('responsibleLots')">Responsible Lots <span class="expandButton">[-]</span></a></legend>
<table id="responsible_lots" class="tablesorter">
     <thead>
          <tr>
               <th>Lot Name</th>
               <th>Lot Location</th>
               <th>Remove</th>
          </tr>
     </thead>
<?php
          // Output Lot Rows
          if($userLots)
          {
?>
     <tbody>
<?php
               // Output rows
               foreach($userLots as $lot)
               {
?>
          <tr>
               <td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
               <td><?php echo $lot['location_name']; ?></td>
               <td><a href="<?php echo \Thinker\Http\Url::create('UserManagement', 'manage', array('username' => $targetUser->username, 'phase' => 'deleteRightIdentifier', 'rightIdentifierVal' => $lot['id'])) ?>">Remove</a></td>
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
     No lot information found.
</p>
<?php
          }
     }

     if($targetUser->user_type == 1)
     {
?>
<form method="post">
     <legend id="updatePassword"><a class="fsLink" onclick="showHideFieldset('updatePassword')">Update Password <span class="expandButton">[+]</span></a></legend>
     <fieldset id="updatePassword" class="expandable" style="display:none">
<?php
          if($targetUser->username == $_SESSION['USER']->username)
          {
          // Require current user to re-enter their password
?>
          <p>
               <label for="confirm_current_pwd">Current Password<span class="required">*</span>:</label><br>
               <input type="password" name="confirm_current_pwd" required />
          </p>
<?php
          }
?>
          <p>
               <label for="password">New Password<span class="required">*</span>:</label><br>
               <input type="password" name="password" required />
          </p>
          <p>
               <label for="confirm_pwd">Confirm Password<span class="required">*</span>:</label><br>
               <input type="password" name="confirm_pwd" required />
          </p>
          <input type="hidden" name="phase" value="updatePassword" />
          <input type="hidden" name="username" value="<?php echo $targetUser->username; ?>" />
          <input type="submit" value="Update Password" />
     </fieldset>
</form>
<?php
     }
?>
<form method="post" id="deleteUser">
     <legend id="deleteUser"><a class="fsLink" onclick="showHideFieldset('deleteUser')">Delete User <span class="expandButton">[+]</span></a></legend>
     <fieldset id="deleteUser" class="expandable" style="display:none">
          <p>
               <b>WARNING!</b> You cannot undo this action.
          </p>
          <input type="hidden" name="phase" value="deleteUser" />
          <input type="hidden" name="username" value="<?php echo $targetUser->username; ?>" />
          <input type="submit" value="Delete User" />
     </fieldset>
</form>
<?php
}
else
{
?>
<h1>(Unknown User)</h1>
<p>
     The specified user could not be found.
</p>
<?php
}
?>
<script type="text/javascript" src="html/psueoc/js/UserManagement/manage.js"></script>