<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache'); 

require("../../conexion/aut_config.inc.php");
/*este es el enlace de conexion a la base de datos*/
$db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

$codfactura=$_GET["codfacturatmp"];
$codconcepto=$_GET["codconcepto"];

$consulta = "DELETE FROM detalle_facturatmp WHERE codigo_concepto='$codconcepto' AND n_factura ='$codfactura'";
$rs_consulta = pg_query($consulta);
echo "<script>parent.location.href='frame_lineas.php?codfacturatmp=".$codfactura."';</script>";

?>