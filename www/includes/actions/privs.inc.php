<?php

if(! defined('arms2')) die('No direct script access allowed');
if(strpos($_SESSION['arms2']['privs'], 'p') === false)
	die('You do not have the Promote privilege');
if($_GET['udid'] == '') die('No ID provided');
echo '<h3>Edit privileges</h3>';

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
	$stmt = $mysqli->stmt_init();
	$stmt->prepare('select privs from admin where udid = ?');
	$stmt->bind_param('i', $_GET['udid']);
	$stmt->execute();
	$stmt->bind_result($privs);
	$stmt->fetch();
	$stmt->close();

?>
	<form method="POST">
		<table>
			<tr>
				<td>Privileges:</td>
				<td><input name="privs" type="text" value="<?=$privs?>" /></td>
			</tr>
			<tr>
				<td colspan="2"><button type="submit">Submit</button></td>
			</tr>
		</table>
	</form>
<?php
}
else
{
	$stmt = $mysqli->stmt_init();

	if(! isset($_POST['privs']) || $_POST['privs'] == '')
	{
		$stmt->prepare('update admin set privs = null where udid = ?');
		$stmt->bind_param('i', $_GET['udid']);
	}
	else
	{
		$stmt->prepare('update admin set privs = ? where udid = ?');
		$stmt->bind_param('si', $_POST['privs'], $_GET['udid']);
	}

	$stmt->execute();

	if($stmt->affected_rows > 0)
	{
?>
		Privileges changed successfully.
		<meta http-equiv="refresh" content="3;url=/arms/admin.php?m=lista" />
<?php
	}
	else
		echo 'Error changing privileges. (Are values identical to old privileges?)';

	$stmt->close();
}
