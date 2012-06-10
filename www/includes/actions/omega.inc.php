<?php

if(! defined('arms2')) die('No direct script access allowed');
if(strpos($_SESSION['arms2']['privs'], 'o') === false)
	die('You do not have the Omega privilege');
echo '<h3>Omega Squad post</h3>';

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
	$stmt = $mysqli->stmt_init();
	$stmt->prepare('select t, l, b, r from omega');
	$stmt->execute();
	$stmt->bind_result($top, $left, $bottom, $right);
	$stmt->fetch();
	$stmt->close();

?>
	<form method="POST">
		<table>
			<tr>
				<td>Top-Left X:</td>
				<td><input name="l" type="text" value="<?=$left?>" /></td>
			</tr>
			<tr>
				<td>Top-Left Y:</td>
				<td><input name="t" type="text" value="<?=$top?>" /></td>
			</tr>
			<tr>
				<td>Bottom-Right X:</td>
				<td><input name="r" type="text" value="<?=$right?>" /></td>
			</tr>
			<tr>
				<td>Bottom-Right Y:</td>
				<td><input name="b" type="text" value="<?=$bottom?>" /></td>
			</tr>
			<tr>
				<td colspan="2">
					<button type="submit">Submit</button>
				</td>
			</tr>
		</table>
	</form>
<?php
}
else
{
	if(! isset($_POST['t']) || ! isset($_POST['l']) || ! isset($_POST['b'])
		|| ! isset($_POST['r']))
	{
?>
	All 4 coordinates are required.
	<meta http-equiv="refresh" content="3" />
<?php
		exit;
	}

	$stmt = $mysqli->stmt_init();
	$stmt->prepare('update omega set t = ?, l = ?, b = ?, r = ?');
	$stmt->bind_param('iiii', $_POST['t'], $_POST['l'], $_POST['b'],
		$_POST['r']);
	$stmt->execute();

	if($stmt->affected_rows > 0)
	{
		system('/home/haliphax/vhosts/dhpd-ssl/omega.php');
?>
	Coordinates changed successfully. New report posted.
	<meta http-equiv="refresh" content="3" />
<?php
	}
	else
	{
?>
	Error updating coordinates. (Did you actually change them?)
	<meta http-equiv="refresh" content="3" />
<?php
	}

	$stmt->close();
}
