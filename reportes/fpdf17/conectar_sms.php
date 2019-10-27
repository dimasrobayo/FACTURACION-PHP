<?php 
    require("../conexion_sms/aut_config.inc.php");
    /*este es el enlace de conexion a la base de datos*/
    $db_conexion=pg_connect("host=$sql_host_sms dbname=$sql_db_sms user=$sql_usuario_sms password=$sql_pass_sms");
?>
