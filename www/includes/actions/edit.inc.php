<?php

if(! defined('arms2')) die('No direct script access allowed');
if(strpos($_SESSION['arms2']['privs'], 'e') === false)
	die('You do not have the Edit privilege');
if($_GET['udid'] == '') die('No ID provided');
echo '<h3>Edit Officer</h3>';

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
	$stmt = $mysqli->stmt_init();
	$stmt->prepare('select udid, name, hash from officer where udid = ?');
	$stmt->bind_param('i', $_GET['udid']);
	$stmt->execute();
	$stmt->bind_result($udid, $name, $hash);
	$stmt->fetch();
	$stmt->close();
?>
	<form method="POST">
		<table>
			<tr>
				<td>UDID:</td>
				<td><input name="udid" type="text" value="<?=$udid?>" /></td>
			</tr>
			<tr>
				<td>Name:</td>
				<td><input name="name" type="text" value="<?=$name?>" /></td>
			</tr>
			<tr>
				<td>Hash:</td>
				<td><input name="hash" type="text" value="<?=$hash?>" /></td>
				<td><input name="regen" type="checkbox" value="1" /> Regen</td>
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
	if(! isset($_POST['udid']) || ! isset($_POST['name'])
		|| ! isset($_POST['hash']))
	{
?>
	All fields are required.
	<meta http-equiv="refresh" content="3" />
<?php
		exit;
	}

	$hash = $_POST['hash'];

	if($_POST['regen'] == true)
	{
		$now = microtime(true);
		$hash = md5(md5($config['salt'] . $now) . md5($now . $config['salt']));
		echo 'New hash: <b>' . $hash . '</b><br />';
	}

	$stmt = $mysqli->stmt_init();
	$stmt->prepare(
		'update officer set udid = ?, name = ?, hash = ? where udid = ?');
	$stmt->bind_param('issi', $_POST['udid'], $_POST['name'], $hash,
		$_GET['udid']);
	$stmt->execute();

	if($stmt->affected_rows > 0)
	{
?>
	Officer edited successfully.
<?php

		if($_POST['regen'] != true)
			echo '<meta http-equiv="refresh" content="3;url=/arms/admin.php?m=list" />';
	}
	else
		echo 'Error editing officer';

	$stmt->close();
}
