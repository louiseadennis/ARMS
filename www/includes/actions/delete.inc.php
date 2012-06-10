<?php

if(! defined('arms2')) die('No direct script access allowed');
if(strpos($_SESSION['arms2']['privs'], 'd') === false)
	die('You do not have the Delete privilege');
if($_GET['udid'] == $_SESSION['arms2']['udid'])
	die('You can\'t delete yourself, stupid!');
if($_GET['udid'] == '') die('No ID provided');
echo '<h3>Delete Officer</h3>';
$stmt = $mysqli->stmt_init();
$stmt->prepare('delete from officer where udid = ?');
$stmt->bind_param('i', $_GET['udid']);
$stmt->execute();
$stmt->close();

?>
	Officer deleted.
	<meta http-equiv="refresh" content="3;url=/arms/admin.php?m=list" />
