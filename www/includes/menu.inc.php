<?php

if(! defined('arms2')) die('No direct script access allowed');

?>
<div>
	<a href="?m=list">List Officers</a>
	<?php if(strpos($_SESSION['arms2']['privs'], 'p') !== false) { ?>
		<span>&nbsp;</span>
		<a href="?m=lista">List Administrators</a>
	<?php } ?>
	<?php if(strpos($_SESSION['arms2']['privs'], 'a') !== false) { ?>
		<span>&nbsp;</span>
		<a href="?m=add">Add Officer</a>
	<?php } ?>
	<?php if(strpos($_SESSION['arms2']['privs'], 's') !== false) { ?>
		<span>&nbsp;</span>
		<a href="?m=report">Report</a>
	<?php } ?>
	<span>&nbsp;</span>
	<a href="?m=changepw">Change password</a>
	<span>&nbsp;</span>
	<a href="/arms/admin.php">Logout</a>
</div>

<hr />
