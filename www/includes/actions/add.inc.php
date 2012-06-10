<?php

if(! defined('arms2')) die('No direct script access allowed');
if(strpos($_SESSION['arms2']['privs'], 'a') === false)
	die('You do not have the Add privilege.');
echo '<h3>Add Officer</h3>';

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
?>
	<form action="?m=add" method="POST">
		<table>
			<tr>
				<td>UDID:</td>
				<td><input name="udid" type="text" /></td>
			</tr>
			<tr>
				<td>Name:</td>
				<td><input name="name" type="text" /></td>
			</tr>
			<tr>
				<td><button type="submit">Add</button></td>
			</tr>
		</table>
	</form>
<?php
}
else
{
	if(! isset($_POST['udid']) || ! isset($_POST['name']))
	{
?>
	I need both UDID and name. Duh.
	<meta http-equiv="refresh" content="3" />
<?php
		exit;
	}

	$now = microtime(true);
	$hash = md5(md5($salt . $now) . md5($now . $salt));
	$stmt = $mysqli->stmt_init();
	$stmt->prepare('insert into officer (udid, name, hash) values (?, ?, ?)');
	$stmt->bind_param('iss', $_POST['udid'], $_POST['name'], $hash);
	$stmt->execute();
	$stmt->close();
	echo 'Generated hash: <b>' . $hash . '</b>';
}
