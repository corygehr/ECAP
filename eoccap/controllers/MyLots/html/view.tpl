<?php
    /**
     * MyLots/html/view.tpl
     * Contains the HTML template for the info subsection
     *
     * @author Cory Gehr
     */

$lots = $this->get('LOTS');
?>
<h1>My Lots</h1>
<p>
	Below are the lot(s) you have been assigned for the current event.
</p>
<?php

// Divide lots by status
$open = array();
$closed = array();
$limited = array();
$attention = array();
$ready = array();

foreach($lots as $lot)
{
     // Organize into separate arrays
     switch($lot['status'])
     {
          case 'Open':
               $open[] = $lot;
          break;

          case 'Closed':
               $closed[] = $lot;
          break;

          case 'Limited':
               $limited[] = $lot;
          break;

          case 'Needs Attention':
               $attention[] = $lot;
          break;

          case 'Ready':
               $ready[] = $lot;
          break;
     }
}

// Now, output each lot per table

// Output closed lots
if($closed)
{
?>
<legend>Closed</legend>
<p>
     These lots are inactive. You may need to complete a 
     Readiness Report before they can be opened.
</p>
<table id="closed" class="tablesorter">
     <thead>
          <tr>
               <th>Lot Name</th>
               <th>Comment</th>
               <th>Last Update</th>
               <th>Updated By</th>
          </tr>
     </thead>
     <tbody>
<?php
     foreach($closed as $lot)
     {
?>
          <tr>
               <td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
               <td><?php echo $lot['comment']; ?></td>
               <td><?php echo $lot['update_time']; ?></td>
               <td><?php echo $lot['status_create_user_name']; ?></td>
          </tr>
<?php
     }
?>
     </tbody>
</table>
<?php
}

// Output Ready lots
if($ready)
{
?>
<legend>Ready</legend>
<p>
     These are lots that have a completed Readiness Report that 
     still needs reviewed by Parking Supervisors.
</p>
<table id="ready" class="tablesorter">
     <thead>
          <tr>
               <th>Lot Name</th>
               <th>Comment</th>
               <th>Last Update</th>
               <th>Updated By</th>
          </tr>
     </thead>
     <tbody>
<?php
     foreach($ready as $lot)
     {
?>
          <tr>
               <td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
               <td><?php echo $lot['comment']; ?></td>
               <td><?php echo $lot['status_create_time']; ?></td>
               <td><?php echo $lot['status_create_user_name']; ?></td>
          </tr>
<?php
     }
?>
     </tbody>
</table>
<?php
}

// Output Limited Availability lots
if($limited)
{
?>
<legend>Limited Availability</legend>
<p>
     These are lots that are open, but may only have limited 
     availability for various reasons.
</p>
<table id="limited" class="tablesorter">
     <thead>
          <tr>
               <th>Lot Name</th>
               <th>Comment</th>
               <th>Last Update</th>
               <th>Updated By</th>
          </tr>
     </thead>
     <tbody>
<?php
     foreach($limited as $lot)
     {
?>
          <tr>
               <td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
               <td><?php echo $lot['comment']; ?></td>
               <td><?php echo $lot['update_time']; ?></td>
               <td><?php echo $lot['status_create_user_name']; ?></td>
          </tr>
<?php
     }
?>
     </tbody>
</table>
<?php
}

// Output open lots
if($open)
{
?>
<legend>Open</legend>
<p>
     These are lots that are active.
</p>
<table id="open" class="tablesorter">
     <thead>
          <tr>
               <th>Lot Name</th>
               <th>Comment</th>
               <th>Last Update</th>
               <th>Updated By</th>
          </tr>
     </thead>
     <tbody>
<?php
     foreach($open as $lot)
     {
?>
          <tr>
               <td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
               <td><?php echo $lot['comment']; ?></td>
               <td><?php echo $lot['update_time']; ?></td>
               <td><?php echo $lot['status_create_user_name']; ?></td>
          </tr>
<?php
     }
?>
     </tbody>
</table>
<?php
}

// Output Needs Attention lots
if($attention)
{
?>
<legend>Needs Attention</legend>
<p>
     These are lots that have been marked as ready, but may 
     require attention from Parking Supervisors.
</p>
<table id="attention" class="tablesorter">
     <thead>
          <tr>
               <th>Lot Name</th>
               <th>Comment</th>
               <th>Last Update</th>
               <th>Updated By</th>
          </tr>
     </thead>
     <tbody>
<?php
     foreach($attention as $lot)
     {
          // Determine location for status open comment
          if(strpos($lot['comment'],REPORT_FAIL_TEXT) !== false)
          {
               $comment = $lot['notes'];
               $updateTime = $lot['readiness_create_time'];
               $updateUser = $lot['readiness_create_user_name'];
          }
          else
          {
               $comment = $lot['comment'];
               $updateTime = $lot['status_create_time'];
               $updateUser = $lot['status_create_user_name'];
          }
?>
          <tr>
               <td><a href="<?php echo \Thinker\Http\Url::create('LotConsole', 'manage', array('id' => $lot['id'])); ?>"><?php echo $lot['name']; ?></a></td>
               <td><?php echo $comment; ?></td>
               <td><?php echo $updateTime; ?></td>
               <td><?php echo $updateUser; ?></td>
          </tr>
<?php
     }
?>
     </tbody>
</table>
<?php
}
?>