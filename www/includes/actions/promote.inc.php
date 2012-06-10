<?php

if(! defined('arms2')) die('No direct script access allowed');
if(strpos($_SESSION['arms2']['privs'], 'p') === false)
	die('You do not have the Promote privilege');
if($_GET['udid'] == $_SESSION['arms2']['udid'])
	die('You can\'t promote yourself, stupid!');
echo '<h3>Promote Officer</h3>';
$pass = '';

for($a = 0; $a < 8; $a++)
{
	$rnd = rand(0,2);

	switch($rnd)
	{
		case 0: $pass .= chr(rand(48, 57)); break;
		case 1: $pass .= chr(rand(65, 90)); break;
		case 2: $pass .= chr(rand(97, 122)); break;
	}
}

$stmt = $mysqli->stmt_init();
$stmt->prepare('insert into admin (udid, pass) values (?, md5(?))');
$stmt->bind_param('is', $_GET['udid'], $pass);
$stmt->execute();

if($stmt->affected_rows > 0)
{
?>
	Temporary password: <b style="font-family:courier;text-decoration:underline;"><?=$pass?></b>
	<br />
	Officer has been granted Admin status. If you wish to assign this user privileges, click <a href="?m=privs&udid=<?=$_GET['udid']?>">here</a>.
<?php
}
else
{
?>
	There has been an error promoting the officer. (Are they already an admin?)
<?php
}

$stmt->close();
