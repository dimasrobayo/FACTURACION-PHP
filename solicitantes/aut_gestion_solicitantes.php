<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
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
    $pagina=$pag.'?view='.$type;

    //Conexion a la base de datos
    include("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    $query="SELECT * FROM solicitantes,tipo_solicitantes WHERE solicitantes.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante order by cedula_rif";
    $result = pg_query($query) or die(pg_last_error());
    
?>

<div align="center" class="centermain">
    <div>  
        <table class="adminclientes">
            <tr>
                <th>
                    SOLICITANTES
                </th>
            </tr>
        </table>
        <br>
<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="10%">
                    CI/RIF
                </th>
                <th width="20%" align="center">
                    Solicitante
                </th>
                <th width="8%" align="center">
                    Tipo
                </th>
                <th width="5%" align="center">
                    Sexo
                </th>
                <th width="10%" align="center">
                    Fec. Nac.
                </th>
                <th width="25%" align="center">
                    Direcci&oacute;n
                </th>
                <th width="18%" align="center">
                    Tel&eacute;fonos
                </th>
                <th width="12%" align="center">
                    eMail
                </th>
		
                <th width="10%" align="center">
                    Acciones
                </th>
            </tr>
        </thead>

<?php
    while($resultados = pg_fetch_array($result)) {
?>

            <tr class="row0">
                <td  align="center">
                     <?php echo $resultados[nacionalidad].'-'.$resultados[cedula_rif];?>
                </td>
                <td>
                    <?php echo $resultados[nombre_solicitante];?>
                </td>
                <td>
                    <?php echo $resultados[descripcion_tipo_solicitante];?>
                </td>
                <td>
                    <?php echo $resultados[sexo_solicitante];?>
                </td>
                <td>
                    <?php echo implode('/',array_reverse(explode('-',$resultados[fecha_nac])));?>
                </td>
                <td>
                    <?php echo $resultados[direccion_habitacion];?>
                </td>
                <td>
                    <?php echo trim($resultados[telefono_fijo]).' - '.trim($resultados[telefono_movil]);?>
                </td>
                <td>
                    <?php echo $resultados[email];?>
                </td>

                <td align="center"> 
                    <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=solicitante_drop&cedula_rif=<?php echo $resultados[cedula_rif];?>" title="Pulse para eliminar el registro">
                        <img border="0" src="images/borrar28.png" alt="borrar">
                    </a>
                    <a href="index2.php?view=solicitante_update&cedula_rif=<?php echo $resultados[cedula_rif];?>" title="Pulse para Modificar los datos registrados">
                        <img border="0" src="images/modificar.png" alt="borrar">
                    </a>  
                </td>
            </tr>
<?php
}
?>

            <tfoot>
                <tr align="center">
                    <th colspan="9" align="center">
                        <div id="cpanel">
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=home">
                                        <img src="images/cpanel.png" alt="salir" align="middle"  border="0" />
                                        <span>Salir</span>
                                    </a>
                                </div>
                            </div>
							
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="reportes/imprimir_lista_solicitantes.php" target="_blank">
                                        <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                        <span>Imprimir</span>
                                    </a>
                                </div>
                            </div>
                    
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=solicitante_add">
                                        <img src="images/nuevo.png" alt="agregar" align="middle"  border="0" />
                                        <span>Agregar</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php
pg_free_result($result);
pg_close($db_conexion);
?>
