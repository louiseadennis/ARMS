<?php

require_once('includes/config.inc.php');

# connect to db
$mysqli = new mysqli('localhost', 'dennisse_arms', $config['pwd'], 'dennisse_arms');
# check hash against db
# $stmt = $mysqli->stmt_init();
# if(! $stmt->prepare('select udid from officer where hash = ?'))
#	die(json_encode(array('error' => 'pull hash failure')));
# $stmt->bind_param('s', $hash);
# $stmt->execute();
# $stmt->bind_result($officer);
# $stmt->fetch();
# $stmt->close();
# check hash match
# if(! isset($officer)) die(json_encode(array('error' => 'hash failure')));
# prep stmt for grab

$mwarray = array();

$stmt = $mysqli->stmt_init();

# t/l/b/r (block mode)

	# grab info for entire block
	$sql = <<<SQL
		select
			barricades.x as x, barricades.y as y,
			barricades.stamp as stamp,
			barricades.mostwanted as mostwanted
			from barricades
SQL;
	if(! $stmt->prepare($sql)) die(json_encode(
		array('error' => 'pull block status failure')));
	$stmt->execute();
	$stmt->store_result();
	$status = array();
	$stmt->bind_result($x, $y, $stamp, $mostwanted);

	# build 2D array for json encoding
	while($stmt->fetch())
	{
	  if ($mostwanted != "none") {
	    $age = round((time() - $stamp) / 3600, 2);
	    $coordstring = "[$x, $y] $mostwanted";
	    $mwarray[$coordstring] = $age;
	  }
	}


$stmt->close();
# close db connection
$mysqli->close();

asort($mwarray);

foreach($mwarray as $key => $val) {
  print("$val hours ago: $key<br>");
}



