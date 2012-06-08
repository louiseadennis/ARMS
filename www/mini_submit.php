<?php

require_once('includes/config.inc.php');

# die if hash not sent
if($_REQUEST['hash'] == '') die('no hash');
# check version
$version = $_REQUEST['a2v'];
if($version != $config['ver']) die('version mismatch - please upgrade');
# grab variables
$hash = $_REQUEST['hash'];
$id = $_REQUEST['id'];
$genny = $_REQUEST['genny'];
$cades = $_REQUEST['cades'];
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
echo "G:$genny C:$cades Z:$zeds I:$indoors X:$cx Y:$cy";
die('aarrrg');