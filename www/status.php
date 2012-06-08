<?php

require_once('includes/config.inc.php');

# die if hash not sent
if($_REQUEST['hash'] == '') die(json_encode(array('error' => 'no hash')));
# grab variables
$hash = $_REQUEST['hash'];
$cx = $_REQUEST['x'];
$cy = $_REQUEST['y'];
$outdoors = false;
if($_REQUEST['outdoors'] == 1) $outdoors = true;

#logging
# $logfile = "log.txt";
# $fh = fopen($logfile, 'a');
# fwrite($fh, $hash);
# fwrite($fh, " ");
# fwrite($fh, date('Y-m-d'));
# fwrite($fh, "\n");
# fclose($fh);

# if not x/y (cell mode), then t/l/b/r (block mode)
if($cx == '')
{
	$top = $_REQUEST['t'];
	$left = $_REQUEST['l'];
	$bottom = $_REQUEST['b'];
	$right = $_REQUEST['r'];
}

# connect to db
$mysqli = new mysqli('localhost', 'dennisse_arms', $config['pwd'], 'dennisse_arms');
# check hash against db
$stmt = $mysqli->stmt_init();
if(! $stmt->prepare('select udid from officer where hash = ?'))
	die(json_encode(array('error' => 'pull hash failure')));
$stmt->bind_param('s', $hash);
$stmt->execute();
$stmt->bind_result($officer);
$stmt->fetch();
$stmt->close();
# check hash match
if(! isset($officer)) die(json_encode(array('error' => 'hash failure')));
# prep stmt for grab
$stmt = $mysqli->stmt_init();

# t/l/b/r (block mode)
if(isset($top))
{
	# grab info for entire block
	$sql = <<<SQL
		select
			barricades.x as x, barricades.y as y,
			barricades.cades as cades, barricades.stamp as stamp,
			status_indoors.genny as genny, status_indoors.zeds as zeds_in,
	  status_indoors.stamp as indoor_stamp,
	  status_indoors.gennystamp as genny_stamp,
	  status_outdoors.zeds as zeds_out,
	  status_outdoors.stamp as outdoor_stamp,
	  barricades.ruin as ruin, barricades.ruinstamp as ruinstamp
			from barricades
		left join status_indoors on barricades.x = status_indoors.x
			and barricades.y = status_indoors.y
		left join status_outdoors on barricades.x = status_outdoors.x
			and barricades.y = status_outdoors.y
		where barricades.x >= ? and barricades.x <= ?
			and barricades.y >= ? and barricades.y <= ?
		order by barricades.y, barricades.x
SQL;
	if(! $stmt->prepare($sql)) die(json_encode(
		array('error' => 'pull block status failure')));
	$stmt->bind_param('iiii', $left, $right, $top, $bottom);
	$stmt->execute();
	$stmt->store_result();
	$status = array();
	$stmt->bind_result($x, $y, $cades, $stamp, 
			   $genny, $zeds_in, $indoor_stamp, $genny_stamp, $zeds_out, $outdoor_stamp, $ruin, $ruinstamp);
#	$stmt->bind_result($x, $y, $cades, $stamp, $genny, $zeds_in, $zeds_out);

	# build 2D array for json encoding
	while($stmt->fetch())
	{
	  $key = count($status);
	  $status[] = array();
	  if ($ruinstamp < $stamp) {
	    $tstamp = $stamp;
	  } else {
	    $tsamp = $ruinstamp;
	  }
	  $age = round(time() - $tstamp) ;
	  $hours = intval(floor($age / 3600));
	  $indoor_age = round(time() - $indoor_stamp);
	  $genny_age = round(time() - $genny_stamp);
#		echo "$indoor_age, $genny_age";
	  $indoor_hours = intval(floor($indoor_age / 3600));
	  $genny_hours = intval(floor($genny_age / 3600));
	  $outdoor_age = round(time() - $outdoor_stamp);
	  $outdoor_hours = intval(floor($outdoor_age / 3600));
	  $ruin_age = round(time() - $ruinstamp);
	  $ruin_hours = intval(floor($ruin_age / 3600));
	  $status[$key]['x'] = $x;
	  $status[$key]['y'] = $y;
	  if ($hours < 72 || $indoor_hours < 72 || $outdoor_hours < 72 || $genny_hours < 72 || $ruin_hours < 72) {
	    if($cades == '') $cades = 'z';
	    if($ruin == '') $ruin = 'z';
	    $status[$key]['cades'] = $cades;
	    $status[$key]['genny'] = $genny;
	    
# don't show outside zed count outdoors
	    if(! $outdoors)
	      {
		$status[$key]['zeds_out'] = $zeds_out;
	      }
	    
	    $status[$key]['zeds_in'] = $zeds_in;
	    $status[$key]['age'] = round((time() - $stamp) / 3600, 2);
	    $status[$key]['indoor_age'] = round((time() - $indoor_stamp) / 3600, 2);
	    $status[$key]['genny_age'] = round((time() - $genny_stamp) / 3600, 2);
	    $status[$key]['outdoor_age'] = round((time() - $outdoor_stamp) / 3600, 2);
	    $status[$key]['ruin'] = $ruin;
	    $status[$key]['ruin_age'] = round((time() - $ruinstamp) / 3600, 2);
	  } else {
	    $status[$key]['cades'] = 'z';
	    $status[$key]['ruin'] = 'z';
	  }
	}
}
# x/y (cell mode)
else
{
	# grab info for cell
	$sql = <<<SQL
		select
			barricades.cades as cades, barricades.stamp as stamp,
			status_indoors.genny as genny, status_indoors.zeds as zeds_in,
	  status_indoors.stamp as indoor_stamp,
	  status_indoors.gennystamp as genny_stamp,
	  status_outdoors.zeds as zeds_out,
	  status_outdoors.stamp as outdoor_stamp
	  barricades.ruin as ruin, barricades.ruinstamp as ruinstamp
			from barricades
		left join status_indoors on barricades.x = status_indoors.x
			and barricades.y = status_indoors.y
		left join status_outdoors on barricades.x = status_outdoors.x
			and barricades.y = status_outdoors.y
		where barricades.x = ? and barricades.y = ?
SQL;
	if(! $stmt->prepare($sql)) die(json_encode(
		array('error' => 'pull status error')));
	$stmt->bind_param('ii', $cx, $cy);
	$stmt->execute();
	$status = array();
	# build 1D array for json encoding
	$stmt->bind_result($status['cades'], $stamp, $status['genny'],
			   $status['zeds_in'], $indoor_stamp, $genny_stamp, $status['zeds_out'], $outdoor_stamp, $status['ruin'], $ruinstamp);
	$stmt->fetch();

	# don't show zed count outdoors
	if($outdoors)
	{
		delete($status['zeds_out']);
	}

	if($status['cades'] == '') $status['cades'] = 'z';
	if($status['ruin'] == '') $status['ruin'] = 'z';
	$status['age'] = round((time() - $stamp) / 3600, 2);
	$status['indoor_age'] = round((time() - $indoor_stamp) / 3600, 2);
	$status['genny_age'] = round((time() - $genny_stamp) / 3600, 2);
	$status['outdoor_age'] = round((time() - $outdoor_stamp) / 3600, 2);
	$status['ruin_age'] = round((time() - $ruinstamp) / 3600, 2);
}

$stmt->close();
# close db connection
$mysqli->close();
# send json-encoded data back to client
header('Status: 200');
echo json_encode($status);
