<?php

if(! defined('arms2')) die('No direct script access allowed');
if(strpos($_SESSION['arms2']['privs'], 'p') === false)
	die('You do not have the Promote privilege');
if($_GET['udid'] == '') die('No ID provided');
if($_GET['udid'] == $_SESSION['arms2']['udid'])
	die('You can\'t demote yourself, stupid!');
echo '<h3>Demote Admin</h3>';
$stmt = $mysqli->stmt_init();
$stmt->prepare('delete from admin where udid = ?');
$stmt->bind_param('i', $_GET['udid']);
$stmt->execute();

if($stmt->affected_rows > 0)
{
?>
	Officer has been demoted.
	<meta http-equiv="refresh" content="3;url=/arms/admin.php?m=lista" />
<?php
}
else
{
?>
	There was an error demoting the officer. (Are you sure they're an admin?)
<?php
}
