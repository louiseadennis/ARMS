<?php

if(! defined('arms2')) die('No direct script access allowed');
echo '<h3>Change password</h3>';

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
?>
	<form action="?m=changepw" method="POST">
		<table>
			<tr>
				<td>New password:</td>
				<td><input name="new" type="password" /></td>
			</tr>
			<tr>
				<td>Confirm password:</td>
				<td><input name="confirm" type="password" /></td>
			</tr>
			<tr>
				<td><button type="submit">Submit</button></td>
			</tr>
		</table>
	</form>
<?php
}
else
{
	if(! isset($_POST['new']) || ! isset($_POST['confirm']))
	{
?>
		All fields are required, you silly goose.
		<meta http-equiv="refresh" content="3" />
<?php
		exit;
	}

	if($_POST['new'] !== $_POST['confirm'])
	{
?>
		New password and confirmation do not match. You suck.
		<meta http-equiv="refresh" content="3" />
<?php
		exit;
	}

	$stmt = $mysqli->stmt_init();
	$stmt->prepare('update admin set pass = md5(?) where udid = ?');
	$stmt->bind_param('si', $_POST['new'], $_SESSION['arms2']['udid']);
	$stmt->execute();
	$stmt->close();
?>
	Password changed.
<?php
}
