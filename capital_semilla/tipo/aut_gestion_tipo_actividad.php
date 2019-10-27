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
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    //codigo para colocar la hora.
    $hora=date("h").":".date("i")." ".date("a");

if (!isset($_GET['accion']))
{
    $datos_consulta = pg_query("SELECT * FROM tipo_actividad order by tipo_actividad.codigo_tipo") or die("No se pudo realizar la consulta a la Base de datos");
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="admintipo_actividad">
            <tr>
                <th>
                    TIPOS DE ACTIVIDADES REGISTRADAS
                </th>
            </tr>
        </table>

        <br>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="12%">
                    C&oacute;digo
                </th>

                <th width="70%" align="center">
                    Descripci&oacute;n
                </th>

                <th width="12%" align="center">
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
            <tr class="row0">
                <td  align="center">
                     <?php echo $resultados[codigo_tipo];?>
                </td>

                <td>
                    <?php echo $resultados[descripcion];?>
                </td>

                <td align="center"> 
                    <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=tipo_actividad_drop&codigo_tipo=<?php echo $resultados[codigo_tipo];?>" title="Pulse para eliminar el registro">
                        <img border="0" src="images/borrar28.png" alt="borrar">
                    </a>
                    <a href="index2.php?view=tipo_actividad_update&codigo_tipo=<?php echo $resultados[codigo_tipo];?>" title="Pulse para Modificar el Nivel de Acceso">
                        <img border="0" src="images/modificar.png" alt="borrar">
                    </a>  
                </td>
            </tr>

<?php } ?>

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
                                <a href="index2.php?view=tipo_actividad_add">
                                    <img src="images/tipo_actividad.png" alt="agregar" align="middle"  border="0" />
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
}
?>