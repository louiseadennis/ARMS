<?php

if(! defined('arms2')) die('No direct script access allowed');
if(strpos($_SESSION['arms2']['privs'], 's') === false)
	die('You do not have the Squad Report privilege');
echo '<h3>Squad report</h3>';

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
?>
	<form method="POST">
		<table>
			<tr>
				<td>Top-Left X:</td>
				<td><input name="l" type="text" /></td>
			</tr>
			<tr>
				<td>Top-Left Y:</td>
				<td><input name="t" type="text" /></td>
			</tr>
			<tr>
				<td>Bottom-Right X:</td>
				<td><input name="r" type="text" /></td>
			</tr>
			<tr>
				<td>Bottom-Right Y:</td>
				<td><input name="b" type="text" /></td>
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
	$pattern = '#\D#';
	$t = preg_replace($pattern, '', $_POST['t']);
	$l = preg_replace($pattern, '', $_POST['l']);
	$b = preg_replace($pattern, '', $_POST['b']);
	$r = preg_replace($pattern, '', $_POST['r']);

	if($t == '' || $l == '' || $b == '' || $r == '')
	{
?>
		All coordinates are required.
		<meta http-equiv="refresh" content="3" />
<?php
		exit;
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_URL,
		'http://redrum.soul-fantasy.net/map.php?menu=route');
	curl_setopt($ch, CURLOPT_POSTFIELDS,
		array(
			'coord' => $l . '|' . $t,
			'dcoord' => $r . '|' . $b
		)
	);
	$html = curl_exec($ch);
	if(! $html) die('Error retrieving Red Rum map');
	preg_match('#<table style=".*?</table>#is', $html, $table);
	preg_match_all(
		'#<td.*?>(?:<div class="highlight">)?(?:<img.*?>)?(.*?)<br/>(\d+), (\d+)(?:</div>)?</td>#is',
		$table[0], $cells);
	$postcells = array();

	for($a = 0; $a < count($cells[0]); $a++)
	{
		$x = $cells[2][$a];
		$y = $cells[3][$a];

		if($x >= $l && $x <= $r && $y >= $t && $y <= $b)
		{
			if(! is_array($postcells[$x])) $postcells[$x] = array();
			$postcells[$x][$y] = $cells[1][$a];
		}
	}

	$url = 	"https://www.dennis-selelrs.com/arms/status.php?a2v={$config['ver']}"
		. "&hash={$config['hash']}&t=$t&l=$l&b=$b&r=$r";
	$html = file_get_contents($url);
	if(! $html) die('Error retrieving ARMS/2 data');
	$arms = json_decode($html);
	$post = "[u]Squad Report: [{$l},{$t}] to [{$r},{$b}][/u]\n\n";

	foreach($arms as $k => $cell)
	{
		$cades = $cell->cades;
		$genny = $cell->genny;
		$zedsi = $cell->zeds_in;
		$zedso = $cell->zeds_out;

		if(($cades == 'z' || $cades == '') && $genny == '' && $zedsi == ''
			&& $zedso == '')
		{
			continue;
		}

		switch($cades)
		{
			case 'z':
				break;
			case 'LiB':
			case 'LoB':
				$cades = "[color=yellow]{$cades}[/color]";
				break;
			case 'QSB':
			case 'VSB':
				$cades = "[color=orange]{$cades}[/color]";
				break;
			case 'HeB':
			case 'VHB':
			case 'EHB':
				$cades = "[color=green]{$cades}[/color]";
				break;
			default:
				$cades = "[color=red]{$cades}[/color]";
				break;
		}

		switch($genny)
		{
			case 'lit':
				$genny = "[color=green]{$genny}[/color]";
				break;
			case 'low':
				$genny = "[color=orange]{$genny}[/color]";
				break;
			case 'out':
				$genny = "[color=red]{$genny}[/color]";
				break;
		}

		$post .= "[size=1]{$cell->age}h[/size] ";
		$post .= "[b]{$postcells[$cell->x][$cell->y]}[/b] [{$cell->x},{$cell->y}] - ";
		if($cades !== 'z') $post .= "(Cades: {$cades}) ";
		if($genny != '') $post .= "(Genny: {$genny}) ";
		if($zedsi != '' || $zedso != '') $post .= '(Zombies';
		if($zedsi > 0) $post .= " In: {$zedsi}";
		if($zedso > 0) $post .= " Out: {$zedso}";
		if($zedsi != '' || $zedso != '') $post .= ') ';
		$post .= "\n";
	}

	echo <<<HTML
		<p>Copy and paste the following into a forum post:</p>
		<textarea id="squadreport" wrap="off" rows="10" cols="80">{$post}</textarea>
		<script type="text/javascript">
			document.getElementById('squadreport').select();
		</script>
HTML;
}
