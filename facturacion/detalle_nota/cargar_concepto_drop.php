<?php //ENRUTTADOR
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;
    
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

if ((isset($_GET['codigo_nota']))||(isset($_GET['concepto']))){
	$codigo_concepto=$_GET['codigo_concepto'];
	$codigo_nota=$_GET['codigo_nota'];
	$cantidad=$_GET['cantidad'];  
    //actualizacion del stock del inventario.
    $n_combo=$_GET['n_combo'];
    $stock=$_GET['stock'];
    $status_stock=$_GET['status_stock'];
    $stock_update=($stock+($cantidad));

    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.    
    require("conexion/aut_config.inc.php");
	$db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_empresa password=$sql_pass");

	$error="bien";	
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;
    
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

	if(($status_stock==1)||($stock_update>0)){
    	$error="bien";	
		//se le hace el llamado a la funcion de insertar.	
		$result_borrar=pg_query("SELECT drop_detalle_nota($codigo_nota,'$codigo_concepto')") or die(pg_last_error());

        $query_concepto="UPDATE concepto_factura SET codigo_concepto='$codigo_concepto',stock='$stock_update' WHERE codigo_concepto='$codigo_concepto'";
        $result_concepto=pg_query($query_concepto) or die(pg_last_error());
        pg_free_result($result_concepto);
        pg_close();
    }
}
?> 

<div align="center" class="centermain">
	<div class="main">  
		<table class="adminnota" width="100%">
			<tr>
				<th>
						NOTA DE ENTREGA:
					<small>
						Borrar
					</small>
				</th>
			</tr>
		</table>
        
		<table class="adminform" border="0" width="100%">
			<tr bgcolor="#55baf3">
				<th colspan="2">
					BORRAR DETALLE DE NOTA
				</th>
			</tr>
			
			<tr>
				<td colspan="2" align="center">                        	
					<br />
					<strong>Resultado</strong>: 
					<?php 
					if ($error=="bien")
					{
						echo 'LOS DATOS FUERON ELIMINADOS CON  &Eacute;XITO!';
					}
					else 
					{
						echo 'LOS DATOS NO PUEDEN SER ELIMINADOS!';			
					}			
					?>
					<br />	
				</td>
			</tr> 
			
			<table class="adminform" align="center" width="100%">
				<tr align="center">
					<td width="100%" valign="top" align="center">
						<div id="cpanel">
							<div style="float:center;">
								<div class="icon">
									<a href="index2.php?view=detalle_nota_add&codigo_nota=<?php echo $codigo_nota;?>">
										<img src="images/nota_entrega.png" alt="salir" align="middle"  border="0" />
										<span>Gestor de Datos</span>
									</a>
								</div>
							</div>	
						</div>
					</td>
				</tr>
			</table>	
	</div>
</div>
