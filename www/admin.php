<?php

session_start();
define('arms2', true);
include('includes/config.inc.php');

?>
<html>
<head>
	<title>Administration - ARMS/2</title>
	<style type="text/css">
		tr.even { background-color: #dddddd; }
		textarea#squadreport { white-space: nowrap; }
	</style>
	<script type="text/javascript">
		function confirmMe(action, charname, url)
		{
			if(confirm('Are you sure you wish to ' + action + ' ' + charname
				+ '?'))
			{
				window.location = url;
			}
		}
	</script>
</head>
<body>
	<h2>Administration - ARMS/2</h2>
	<noscript>
		<b style="color:red;">
			You must enable Javascript to use this application.
		</b>
		<meta http-equiv="refresh" content="3;url=/arms/admin.php" />
	</noscript>
<?php

if(! isset($_SESSION['arms2']))
{
	if(! isset($_GET['m']))
	{
?>
	<h3>Login</h3>
	<form action="?m=login" method="POST">
		<table cellspacing="4">
			<tr>
				<td>
					Username:
				</td>
				<td>
					<input name="user" id="user" type="text" />
				</td>
			</tr>
			<tr>
				<td>
					Password:
				</td>
				<td>
					<input name="pass" type="password" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<button type="submit">Login</button>
				</td>
			</tr>
		</table>
	</form>
<br />
<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/us/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/3.0/us/80x15.png" /></a><br /><span xmlns:dc="http://purl.org/dc/elements/1.1/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dc:title" rel="dc:type">ARMS/2</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.dennis-sellers.com/arms/arms2/arms2.xpi" property="cc:attributionName" rel="cc:attributionURL">Todd Boyd</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/us/">Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 United States License</a>.
</body>
<script type="text/javascript">
	document.getElementById('user').focus();
</script>
</html>
<?php
		exit;
	}
}
else include('includes/menu.inc.php');

$mod = preg_replace('/[^a-z]/i', '', $_GET['m']);

if($mod == '')
{
	session_destroy();
	echo '<meta http-equiv="refresh" content="0;url=/arms/admin.php" />';
	exit;
}

$mysqli = new mysqli('localhost', 'dennisse_arms', $config['pwd'], 'dennisse_arms');
if(! $mysqli) die('mysqli creation error');
include('includes/actions/' . $mod . '.inc.php');

?>
<p>
	<hr />
	<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/us/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/3.0/us/80x15.png" /></a><br /><span xmlns:dc="http://purl.org/dc/elements/1.1/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dc:title" rel="dc:type">ARMS/2</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.dennis-sellers.com/arms/arms2/arms2.xpi" property="cc:attributionName" rel="cc:attributionURL">Todd Boyd</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/us/">Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 United States License</a>.
</p>
</body>
</html>
