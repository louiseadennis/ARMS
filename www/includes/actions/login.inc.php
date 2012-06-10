<?php

if ( ! defined('arms2')) die('No direct script access allowed');
echo '<h3>Login</h3>';
if(! isset($_POST['user']) || ! isset($_POST['pass'])) die('Error');
$stmt = $mysqli->stmt_init();
$sql = <<<SQL
	select admin.udid as udid, admin.privs as privs,
		officer.name as name
	from admin join officer on admin.udid = officer.udid
	where lower(officer.name) = ? and admin.pass = md5(?)
SQL;
$stmt->prepare($sql);
$stmt->bind_param('ss', strtolower($_POST['user']), $_POST['pass']);
$stmt->execute();
$stmt->bind_result($udid, $privs, $name);
$stmt->fetch();
$stmt->close();

if($udid == '')
{
	echo 'Invalid login.';
	echo '<meta http-equiv="refresh" content="3;url=/arms/admin.php" />';
	exit;
}

$_SESSION['arms2'] = array();
$_SESSION['arms2']['udid'] = $udid;
$_SESSION['arms2']['name'] = $name;
$_SESSION['arms2']['privs'] = $privs;
echo '<meta http-equiv="refresh" content="0;url=/arms/admin.php?m=list" />';
exit;
