<?php
    require("conexion/aut_config.inc.php");
    require ("funciones.php"); // llamado de funciones de la pagina
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");


$texto_buscar=  pg_escape_string($_POST["q"]);

# Perform the query
$query = "SELECT rif_cooperativa, nombre_cooperativa from cooperativas_transporte WHERE rif_cooperativa::text ILIKE  '%$texto_buscar%' OR nombre_cooperativa::text ILIKE  '%$texto_buscar%' ORDER BY nombre_cooperativa ASC LIMIT 10";
//$query = sprintf("SELECT celular, nombre_persona from personas WHERE nombre_persona LIKE '%%%s%%' ORDER BY nombre_persona DESC LIMIT 10", mysql_real_escape_string($_POST["q"]));
$arr = array();
$rs = pg_query($query);

# Collect the results
while($obj = pg_fetch_object($rs)) {
    $arr[] = $obj;
}

# JSON-encode the response
$json_response = json_encode($arr);

# Optionally: Wrap the response in a callback function for JSONP cross-domain support
if($_POST["callback"]) {
    $json_response = $_POST["callback"] . "(" . $json_response . ")";
}

# Return the response
echo $json_response;

pg_freeresult($rs);

?>
