#!/usr/bin/php
<?php

$homedir = '????';
$botUser = 'haliphaxbots';
$botPass = '$pan deX1';

include('/path-to/config.inc.php');
$mysqli = new mysqli('localhost', 'DBNAME', $config['pwd'], 'DBUSSER');
if(! $mysqli) die('mysqli creation error');
$stmt = $mysqli->stmt_init();
$stmt->prepare('select t, l, b, r from omega');
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows < 1) die('no result');
$stmt->bind_result($t, $l, $b, $r);
$stmt->fetch();
$stmt->close();
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

$url = 	"https://oddnetwork.org/dhpd/arms2/status.php?a2v={$config['ver']}"
	. "&hash={$config['hash']}&t=$t&l=$l&b=$b&r=$r";
$html = file_get_contents($url);
if(! $html) die('Error retrieving ARMS/2 data');
$arms = json_decode($html);
$post = "[u]Squad Report: [{$l},{$t}] to [{$r},{$b}][/u]\n\n"
	. "(Please keep in mind this represents data gathered by ARMS/2, and may be incomplete.)\n\n";

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

curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_COOKIEFILE, "{$homedir}cookiefile");
curl_setopt($ch, CURLOPT_COOKIEJAR, "{$homedir}cookiefile");
curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());
curl_setopt($ch, CURLOPT_URL,
	'http://dhpdforums.proboards.com/index.cgi?action=login');
curl_setopt($ch, CURLOPT_POSTFIELDS,
	array(
		'action' 	=> 'login2',
		'username' 	=> $botUser,
		'password' 	=> $botPass,
		'minutes' 	=> '15'
	)
);

for($a = 0; $a < 5; $a++)
{
	if($a == 4) die('Couldn\'t log into forum' . PHP_EOL);
	$html = curl_exec($ch);
	if($html) break;
}

curl_setopt($ch, CURLOPT_URL, 'http://dhpdforums.proboards.com/index.cgi');
curl_setopt($ch, CURLOPT_POSTFIELDS,
	Array(
		'action'	=> 'modifypost2',
		'board' 	=> 'pa',
		'thread'	=> '1875',
		'post'		=> '71811',
		'subject'	=> 'Omega Squad Recon Report',
		'icon'		=> 'xx',
		'message'	=> $post
	)
);

for($a = 0; $a < 5; $a++)
{
	if($a == 4) die('Could not post message' . PHP_EOL);
	$html = curl_exec($ch);
	if($html) break;
}
