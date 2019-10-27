<?php 
	require("../conexion/aut_config.inc.php");
    $con=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

	if (isset($_REQUEST['estado'])) {
		$estado = $_REQUEST['estado'];
		$query="SELECT codest FROM estados WHERE desest='$estado'";
        $result = pg_query($query)or die(pg_last_error());
        $result_max=pg_fetch_array($result);
        echo $result_max[0];
        pg_free_result($result);
	} else if (isset($_REQUEST['municipio'])) {
		$municipio = $_REQUEST['municipio'];
		$query="SELECT codmun FROM municipios WHERE desmun='$municipio'";
        $result = pg_query($query)or die(pg_last_error());
        $result_max=pg_fetch_array($result);
        echo $result_max[0];
        pg_free_result($result);
	} else if (isset($_REQUEST['parroquia'])) {
		$parroquia = $_REQUEST['parroquia'];
		$query="SELECT codpar FROM parroquias WHERE despar='$parroquia'";
        $result = pg_query($query)or die(pg_last_error());
        $result_max=pg_fetch_array($result);
        echo $result_max[0];
        pg_free_result($result);
	}



 ?>