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
    
    $datos_consulta = pg_query("SELECT * FROM marca_concepto order by codigo_marca") or die(pg_last_error());
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="adminmarca">
            <tr>
                <th>
                    MARCAS CONCEPTOS:
                </th>
            </tr>
        </table>

        <br>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="10%">
                    Cod
                </th>
                <th width="65%" align="center">
                    Descripción
                </th>
                <th width="10%" align="center">
                    Status
                </th>
		
                <th width="15%" align="center">
                    Acciones
                </th>
            </tr>
        </thead>

<?php
    while($resultados = pg_fetch_array($datos_consulta)) {
?>

            <tr class="row0">
                <td  align="center">
                     <?php echo $resultados[codigo_marca];?>
                </td>
                <td>
                    <?php echo $resultados[nombre_marca];?>
                </td>
                <td>
                    <?php 
                        if ($resultados[status]==1) {
                            echo "Activa";
                        }else {
                            echo "Desactiva";
                        }   
                        $img=$resultados[status]+2;
                    ?>
                </td>
                
                
                <td align="center"> 
                    <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=marca_drop&codigo_marca=<?php echo $resultados[codigo_marca];?>" title="Pulse para eliminar el registro">
                        <img border="0" src="images/borrar28.png" alt="borrar">
                    </a>
                    <a href="index2.php?view=marca_concepto_update&codigo_marca=<?php echo $resultados[codigo_marca];?>" title="Pulse para Modificar los datos registrados">
                        <img border="0" src="images/modificar.png" alt="borrar">
                    </a>
                    <a onclick="return confirm('CONFIRMAR CAMBIO DE STATUS A ESTA MARCA ?');" href="index2.php?view=marca_status&status=<?php echo $resultados[status];?>&codigo_marca=<?php echo $resultados[codigo_marca];?>" title="Pulse para Cambiar STATUS de la Marca">
                        <img border="0" src="images/<?php echo $img;?>.png" alt="cambiar">
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
                                    <a href="reportes/imprimir_lista_vehiculos.php" target="_blank">
                                        <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                        <span>Imprimir</span>
                                    </a>
                                </div>
                            </div>
                    
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=marca_concepto_add">
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
pg_free_result($datos_consulta);
pg_close();
?>
