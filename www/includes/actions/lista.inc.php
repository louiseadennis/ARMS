<?php

if(! defined('arms2')) die('No direct script access allowed');
echo '<h3>List Administrators</h3>';
$stmt = $mysqli->stmt_init();
$sql = <<<SQL
	select admin.udid as udid, officer.name as name, admin.privs as privs
	from admin join officer on admin.udid = officer.udid
	order by lower(officer.name)
SQL;
$stmt->prepare($sql);
$stmt->execute();
$stmt->bind_result($udid, $name, $privs);

?>
	<table border="1" cellpadding="4">
		<thead>
			<th>UDID</th>
			<th>Name</th>
			<th>Privileges</th>
		</thead>
		<tbody>
<?php

$a = 0;

while($stmt->fetch())
{
	$rowclass = '';
	if($a == 0) $rowclass = ' class="even"';
	$longprivs = '';

	for($b = 0; $b < strlen($privs); $b++)
	{
		if($b > 0) $longprivs .= ', ';

		switch($privs[$b])
		{
			case 'a': $longprivs .= 'Add'; break;
			case 'd': $longprivs .= 'Delete'; break;
			case 'e': $longprivs .= 'Edit'; break;
			case 'h': $longprivs .= 'Hash'; break;
			case 'o': $longprivs .= 'Omega'; break;
			case 'p': $longprivs .= 'Promote'; break;
			case 's': $longprivs .= 'Squad'; break;
		}
	}

	if($longprivs == '') $longprivs = '&nbsp;';

?>
			<tr<?=$rowclass?>>
				<td><a href="http://www.urbandead.com/profile.cgi?id=<?=$udid?>" target="_blank"><?=$udid?></a></td>
				<td><?=$name?></td>
				<td><?=$longprivs?></td>
				<td><a href="javascript:confirmMe('demote', '<?=addslashes($name)?>', '?m=demote&udid=<?=$udid?>');">Demote</a></td>
				<td><a href="?m=privs&udid=<?=$udid?>">Edit</a></td>
			</tr>
<?php

	$a = ($a + 1) % 2;
}

?>
		</tbody>
	</table>
