<?php

if(! defined('arms2')) die('No direct script access allowed');
echo '<h3>List Officers</h3>';
$stmt = $mysqli->stmt_init();
$stmt->prepare('select udid, name, hash from officer order by lower(name)');
$stmt->execute();
$stmt->bind_result($udid, $name, $hash);

?>
	<table border="1" cellpadding="4">
		<thead>
			<th>UDID</th>
			<th>Name</th>
			<?php if(strpos($_SESSION['arms2']['privs'], 'h') !== false) { ?>
				<th>Hash</th>
			<?php } ?>
		</thead>
		<tbody>
<?php

$a = 0;
$rowclass = '';

while($stmt->fetch())
{
	$rowclass = '';
	if($a == 0) $rowclass = ' class="even"';

?>
			<tr<?=$rowclass?>>
				<td><a href="http://www.urbandead.com/profile.cgi?id=<?=$udid?>" target="_blank"><?=$udid?></a></td>
				<td><?=$name?></td>
				<?php if(strpos($_SESSION['arms2']['privs'], 'h') !== false) { ?>
					<td><?=$hash?></td>
				<?php } ?>
				<?php if(strpos($_SESSION['arms2']['privs'], 'd') !== false) { ?>
					<td><a href="javascript:confirmMe('delete', '<?=addslashes($name)?>', '?m=delete&udid=<?=$udid?>');">Delete</a></td>
				<?php } ?>
				<?php if(strpos($_SESSION['arms2']['privs'], 'p') !== false) { ?>
					<td><a href="javascript:confirmMe('promote', '<?=addslashes($name)?>', '?m=promote&udid=<?=$udid?>');">Promote</a></td>
				<?php } ?>
				<?php if(strpos($_SESSION['arms2']['privs'], 'e') !== false) { ?>
					<td><a href="?m=edit&udid=<?=$udid?>">Edit</a></td>
				<?php } ?>
			</tr>
<?php

	$a = ($a + 1) % 2;
}

?>
		</tbody>
	</table>
