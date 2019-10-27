<?php
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
    $pagina=$pag.'?view='.$type;
	
    //Conexion a la base de datos
    require("conexion_sms/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host_sms dbname=$sql_db_sms user=$sql_usuario_sms password=$sql_pass_sms");

    $datos_consulta = pg_query("SELECT * FROM inbox") or die("No se pudo realizar la consulta a la Base de datos");
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="admin_sms_recibidos">
            <tr>
                <th>
                    SMS-Recibidos
                </th>
            </tr>
        </table>

        <br>
        
<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="15%">
                    Enviado Por
                </th>

                <th align="center" width="15%">
                    Fecha
                </th>

                <th width="60%" align="center">
                    Mensaje
                </th>

                <th width="10%" align="center">
                    Acciones
                </th>

            </tr>
        </thead>

<?php
$xxx=0;
while($resultados = pg_fetch_array($datos_consulta))
{
    $xxx=$xxx+1;
?>
            </tr>
                <td align="center">
                    <?php echo $resultados[SenderNumber];?>
                </td>

                <td>
                    <?php echo $resultados[ReceivingDateTime];?>
                </td>

                <td>
                    <?php echo $resultados[TextDecoded];?>
                </td>

                <td align="center"> 
                    <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?type=recibido_drop&SenderNumber=<?php echo $resultados[SenderNumber];?>" title="Pulse para eliminar el registro">
                        <img border="0" src="images/borrar.png" alt="borrar">
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
                                    <a href="reportes/imprimir_recibidos.php" target="_blank">
                                        <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                        <span>Imprimir</span>
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