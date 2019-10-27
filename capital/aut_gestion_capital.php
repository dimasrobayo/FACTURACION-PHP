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
    
    $query="SELECT * FROM capital_semilla";
    $result = pg_query($query) or die(pg_last_error());
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="admincapital">
            <tr>
                <th>
                    CAPITAL SEMILLA
                </th>
            </tr>
        </table>

        <br>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="5%">
                    ID
                </th>
                <th align="center" width="40%">
                    DESCRIPCION
                </th>
                <th align="center" width="20%">
                    MONTO INICIAL
                </th>
                <th align="center" width="20%">
                    MONTO RESTANTE
                </th>
		
                <th width="12%" align="center">
                    Acciones
                </th>
            </tr>
        </thead>

<?php

while($resultados = pg_fetch_array($result)) {

?>

            <tr class="row0">
                <td  align="center">
                     <?php echo $resultados[codigo_capital];?>
                </td>

                <td>
                    <?php echo $resultados[nombre_capital];?>
                </td>

                <td>
                    <?php echo $resultados[monto_inicial];?>
                </td>

                <td>
                    <?php echo $resultados[monto_restante];?>
                </td>

                <td align="center"> 
                    <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=comunidad_drop&idcomunidad=<?php echo $resultados[idcom];?>" title="Pulse para eliminar el registro">
                        <img border="0" src="images/borrar28.png" alt="borrar">
                    </a>
                    <a href="index2.php?view=comunidad_update&idcom=<?php echo $resultados[idcom];?>&estado=<?php echo $resultados[desest];?>&munic=<?php echo $resultados[desmun];?>&parroq=<?php echo $resultados[despar];?>" title="Pulse para Modificar los datos registrados">
                        <img border="0" src="images/modificar.png" alt="borrar">
                    </a>  
                </td>
            </tr>
<?php
}
?>

            <tfoot>
                <tr align="center">
                    <th colspan="6" align="center">
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
                                    <a href="reportes/imprimir_lista_comunidades.php" target="_blank">
                                        <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                        <span>Imprimir</span>
                                    </a>
                                </div>
                            </div>
                    
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=capital_add">
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
