<?php	
    $db_connect = mysql_connect($db_host,$db_user,$db_pass) or die('No se puede establecer una conexión de base de datos' . mysql_error());
    mysql_select_db($db_database,$db_connect) or die('No se puede establecer la seleccion de base de datos'. mysql_error());
    mysql_query("SET names UTF8"); //mysql_query("SET NAMES 'utf8'");
?>
