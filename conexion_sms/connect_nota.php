<?php


/* Database config */

$db_host		= 'localhost';
$db_user		= 'dimas';
$db_pass		= 'hannah3868';
$db_database	= 'finger_print';

/* End config */



$link = pg_connect("host=$db_host dbname=$db_database user=$db_user password=$db_pass") or die('Unable to establish a DB connection');


?>