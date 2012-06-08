<?php

require_once('includes/config.inc.php');

# die if hash not sent
if($_REQUEST['hash'] == '') die('no hash');
# check version
$version = $_REQUEST['a2v'];
# if($version != $config['ver']) die('version mismatch - please upgrade');
# grab variables
$hash = $_REQUEST['hash'];
$id = $_REQUEST['id'];
$genny = $_REQUEST['genny'];
$cades = $_REQUEST['cades'];
$ruins = $_REQUEST['ruins'];
$zeds = $_REQUEST['zeds'];
$szeds = $_REQUEST['szeds'];
$sgennys = $_REQUEST['sgennys'];
$sruins = $_REQUEST['sruins'];
$indoors = $_REQUEST['indoors'];
$cx = $_REQUEST['cx'];
$cy = $_REQUEST['cy'];
$mostwanted = $_REQUEST['mw'];
if (! isset($mostwanted)) {
  $mostwanted = "none";
 }
# if($id == '164793') echo "G:$genny C:$cades Z:$zeds I:$indoors X:$cx Y:$cy SZ:$szeds R:$ruins";
# connect to db
$mysqli = new mysqli('localhost', 'dennisse_arms', $config['pwd'], 'dennisse_arms');
# check hash against db
$stmt =  $mysqli->stmt_init();
if (! $stmt->prepare('select udid from officer where hash = ?'))
	die('officer query error');
$stmt->bind_param('s', $hash);
$stmt->execute();
$stmt->bind_result($officer);
$stmt->fetch();
$stmt->close();
# check hash match
if(! isset($officer)) die('officer error');
# check ID match
if($officer != $id) die('officer mismatch');
$time = time();
# check old barricade status
$stmt = $mysqli->stmt_init();
if(! $stmt->prepare('select cades from barricades where x = ? and y = ?'))
	die('barricades check error');
$stmt->bind_param('ii', $cx, $cy);
$stmt->execute();
$stmt->bind_result($oldcades);
$stmt->fetch();
$stmt->close();
# check old barricade status
$stmt = $mysqli->stmt_init();
if(! $stmt->prepare('select ruin from barricades where x = ? and y = ?'))
	die('barricades check error');
$stmt->bind_param('ii', $cx, $cy);
$stmt->execute();
$stmt->bind_result($oldrs);
$stmt->fetch();
$stmt->close();
$oldruins = preg_match('/R:\d+/i', $oldrs);
# if($id == '164793') echo "OR:$oldrs, OC:$oldcades, OldRuins: $oldruins";
# don't overwrite with generic R:?? if it was already R:##
if($oldrs != null && $oldrs != 'z' && $oldruins == 1 && $ruins == 'R:??')
{
#   if($id == '164793') echo "Setting ruins";
  $ruins = $oldrs;
 }
$stmt = $mysqli->stmt_init();
if(! $stmt->prepare('delete from barricades where x = ? and y = ?'))
  die('barricades delete error');
$stmt->bind_param('ii', $cx, $cy);
$stmt->execute();
$stmt->close();
$stmt = $mysqli->stmt_init();
if(! $stmt->prepare('insert into barricades (x, y, cades, officer, '
		    . 'stamp, mostwanted, ruin, ruinstamp) values (?, ?, ?, ?, ?, ?, ?, ?)'))
  die('barricades insert error');
$stmt->bind_param('iisiissi', $cx, $cy, $cades, $officer, $time, $mostwanted, $ruins, $time);
$stmt->execute();
$stmt->close();
#if($id == '164793') echo "C:$cades, T:$time";

# choose indoors/outdoors
$tbl = 'outdoors';
if($indoors) $tbl = 'indoors';
$stmt = $mysqli->stmt_init();
# clear old status record
if(! $stmt->prepare('delete from status_' . $tbl
	. ' where x = ? and y = ?'))
	die('status delete error');
$stmt->bind_param('ii', $cx, $cy);
$stmt->execute();
$stmt->close();

# only add record if there is something to note
if($zeds != '' || $genny != '')
{

	# insert indoors status - zeds, genny
	if($tbl == 'indoors')
	{
	  $stmt = $mysqli->stmt_init();
		if(! $stmt->prepare('insert into status_indoors (x, y, zeds, '
			. 'genny, officer, stamp, gennystamp) values (?, ?, ?, ?, ?, ?, ?)'))
			die('status_indoors insert error');
		# if($id == '164793') echo "cZ:$zeds X:$cx Y:$cy";
		$stmt->bind_param('iiisiii', $cx, $cy, $zeds, $genny, $officer,
				  $time, $time);
		$stmt->execute();
		$stmt->close();
	}
	# insert outdoors status - zeds
	else if($zeds != '')
	{
	  $stmt = $mysqli->stmt_init();
		if(! $stmt->prepare('insert into status_outdoors (x, y, zeds, '
			. 'officer, stamp) values (?, ?, ?, ?, ?)'))
			die('status_outdoors insert error');
		$stmt->bind_param('iiiii', $cx, $cy, $zeds, $officer, $time);
		$stmt->execute();
		$stmt->close();
	}

}

# if they submitted report of surrounding zeds, do it up
if($szeds != '')
{
	$tbl = '';
	if($indoors == '')
		$tbl = 'outdoors';
	else
		$tbl = 'indoors';
	$shells = split('/', $szeds);

	foreach($shells as $shell)
	{
		if(! $shell) continue;
		$outer = split(',', $shell);
		$inner = split('-', $outer[1]);
		$x = preg_replace('#\D#', '', $outer[0]);
		$y = preg_replace('#\D#', '', $inner[0]);
		$z = preg_replace('#\D#', '', $inner[1]);
		$stmt = $mysqli->stmt_init();

		if($stmt->prepare("delete from status_{$tbl} where x = ? and y = ?"))
		{
			$stmt->bind_param('ii', $x, $y);
			$stmt->execute();
			$stmt->close();
		}
		else
			die('szeds del error');

		$stmt = $mysqli->stmt_init();

		if($z != '0')
		{
			if($stmt->prepare("insert into status_{$tbl} (x, y, zeds, officer, "
				. "stamp) values (?, ?, ?, ?, ?)"))
			{
			  # if($id == '164793') echo "sZ:$z X:$x Y:$y";
				$stmt->bind_param('iiiii', $x, $y, $z, $officer, $time);
				$stmt->execute();
				$stmt->close();
			}
			else
				die('szeds ins error');
		}
	}
}


# if they submitted report of surrounding lit status, do it up
#      if($id == '164793') echo "SG: $sgennys";
if($sgennys != '')
{
  $tbl = 'indoors';
  $shells = split('/', $sgennys);

  foreach($shells as $shell)
    {
      if(! $shell) continue;
      $outer = split(',', $shell);
      $inner = split('-', $outer[1]);
      $x = preg_replace('#\D#', '', $outer[0]);
      $y = preg_replace('#\D#', '', $inner[0]);
      $z = $inner[1];
#      if($id == '164793') echo "G:$z X:$x Y:$y";

      if($z != '0')
	{
	  
	  # Fetch old information
	  $stmt = $mysqli->stmt_init();

	  $oldzeds = 0;
	  $found_table = 1;
	  if($stmt->prepare("select zeds from status_{$tbl} where x = ? and y = ?")) {
	    $stmt->bind_param('ii', $x, $y);
	    $stmt->execute();
	    $stmt->bind_result($oldzeds);
	    $stmt->fetch();
	    $stmt->close();
	    # if($id == '164793') echo "OLDZ:$oldzeds X:$x Y:$y";
	    
	  } else {
	    $oldzeds = 0;
	    $found_table = 0;
	  }

	  if ($found_table && $z != '') {
	    $stmt = $mysqli->stmt_init();
	    if($stmt->prepare("select stamp from status_{$tbl} where x = ? and y = ?")) {
	      $stmt->bind_param('ii', $x, $y);
	      $stmt->execute();
	      $stmt->bind_result($oldstamp);
	      $stmt->fetch();
	      $stmt->close();
	    } else {
	      $oldstamp = $time;
	    }


	    $stmt = $mysqli->stmt_init();

	    if($stmt->prepare("delete from status_{$tbl} where x = ? and y = ?"))
	      {
		$stmt->bind_param('ii', $x, $y);
		$stmt->execute();
		$stmt->close();
	      }
	    else
	      die('sgennys del error');

	    $stmt = $mysqli->stmt_init();
	    
	    if($stmt->prepare("insert into status_{$tbl} (x, y, zeds,"
			      . "genny, officer, stamp, gennystamp) values (?, ?, ?, ?, ?, ?, ?)"))
	      {
		$stmt->bind_param('iiisiii', $x, $y, $oldzeds, $z, $officer, $oldstamp, $time);
		$stmt->execute();
		$stmt->close();
	      }
	    else
	      die('sgennys ins error');
	  }
	}
    }
}

# if they submitted report of surrounding ruin status, do it up
if($sruins != '')
{
  $tbl = 'barricades';
  $shells = split('/', $sruins);

  foreach($shells as $shell)
    {
      if(! $shell) continue;
      $outer = split(',', $shell);
      $inner = split('-', $outer[1]);
      $x = preg_replace('#\D#', '', $outer[0]);
      $y = preg_replace('#\D#', '', $inner[0]);
      $z = $inner[1];

      if($z != '0')
	{
	  
	  $stmt = $mysqli->stmt_init();

	  if(! $stmt->prepare('select cades from barricades where x = ? and y = ?'))
	die('sruins barricades check error');
	  $stmt->bind_param('ii', $x, $y);
	  $stmt->execute();
	  $stmt->bind_result($oldcades);
	  $stmt->fetch();
	  $stmt->close();
	  $stmt = $mysqli->stmt_init();
	  if(! $stmt->prepare('select ruin from barricades where x = ? and y = ?'))
	die('sruins barricades check error');
	  $stmt->bind_param('ii', $x, $y);
	  $stmt->execute();
	  $stmt->bind_result($oldrs);
	  $stmt->fetch();
	  $stmt->close();
	  $stmt = $mysqli->stmt_init();
	  if(! $stmt->prepare('select stamp from barricades where x = ? and y = ?'))
	die('sruins barricades check error');
	  $stmt->bind_param('ii', $x, $y);
	  $stmt->execute();
	  $stmt->bind_result($oldstamp);
	  $stmt->fetch();
	  $stmt->close();

	  $oldruins = preg_match('/R:\d+/i', $oldrs);
	  $oldruins2 = preg_match('/R:\?\?/i', $oldrs);
	  $oldage = round((time() - $oldstamp) / 3600, 2);
#	  if($id == '164793') echo "OC:$oldcades, OR:$oldruins, OR2:$oldruins2, z:$z, OA:$oldage";

# don't overwrite with generic R:?? if it was already R:##
	  if(($oldruins == 0 && ($z != '' || $oldruins2 != 0)) || ($oldruins != 0 && $z != 'R:??') || $oldage > 24)
	    {
#	      if($id == '164793') echo "X:$x, Y:$y R:$z OR:$oldruins2";
	      $stmt = $mysqli->stmt_init();
	      if(! $stmt->prepare('delete from barricades where x = ? and y = ?'))
		die('barricades delete error');
	      $stmt->bind_param('ii', $x, $y);
	      $stmt->execute();
	      $stmt->close();
	      $stmt = $mysqli->stmt_init();
	      if(! $stmt->prepare('insert into barricades (x, y, cades, officer, '
		. 'stamp, ruin, ruinstamp) values (?, ?, ?, ?, ?, ?, ?)'))
		die('barricades insert error');
	      $stmt->bind_param('iisiisi', $x, $y, $oldcades, $officer, $oldstamp, $z, $time);
	      $stmt->execute();
	      $stmt->close();
#	      echo "inserting $x $y $z $oldstamp $time";
	    }

	}
    }
}

# close db connection
$mysqli->close();
