#!/usr/bin/php
<?php

require_once('/path-to/config.inc.php');
$limit = time() - (2 * 86400);
$con = mysql_pconnect('localhost', 'DBNAME', $config['pwd']);
mysql_select_db('TABLENAME');
mysql_query('delete from barricades where stamp < ' . $limit);
mysql_query('delete from status_indoors where stamp < ' . $limit);
mysql_query('delete from status_outdoors where stamp < ' . $limit);
mysql_close($con);
