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

    $datos_consulta = pg_query("SELECT * FROM outbox") or die("No se pudo realizar la consulta a la Base de datos");
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="admin_sms_por_enviar">
            <tr>
                <th>
                    SMS-Enviados
                </th>
            </tr>
        </table>

        <br>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="20%">
                    Enviado a
                </th>

                <th align="center" width="20%">
                    Fecha
                </th>

                <th width="50%" align="center">
                    Mensaje
                </th>

                <th width="10%" align="center">
                    Autor
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
                    <?php echo $resultados[DestinationNumber];?>
                </td>

                <td>
                    <?php echo $resultados[SendingDateTime];?>
                </td>

                <td>
                    <?php echo $resultados[TextDecoded];?>
                </td>

                <td>
                    <?php echo $resultados[CreatorID];?>
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