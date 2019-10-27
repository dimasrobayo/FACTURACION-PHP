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

    $datos_consulta = pg_query("SELECT * FROM estados_tramites order by cod_estado_tramite") or die(pg_last_error());
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="adminetramites">
            <tr>
                <th>
                    ESTADOS DE TRAMITES:
                </th>
            </tr>
        </table>

        <br>

<!--Estructura de Tabla de Contenidos Estados de Tramites-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="10%">
                    C&oacute;digo
                </th>

		<th align="center" width="10%">
                    Siglas
                </th>
                
		<th align="center" width="10%">
                    Estado
                </th>

                <th width="55%" align="center">
                    Descripci&oacute;n Estado
                </th>
                
                <th align="center" width="10%">
                    Tipo Estado
                </th>
		
                <th width="12%" align="center">
                    Acciones
                </th>
            </tr>
        </thead>

<?php
$xxx=0;
while($resultados = pg_fetch_array($datos_consulta)) {
    $xxx=$xxx+1;
?>
            <tr class="row0">
                <td  align="center">
                     <?php echo $resultados[cod_estado_tramite];?>
                </td>

		<td>
                    <?php echo $resultados[siglas_estado_tramite];?>
                </td>

                <td  align="center">
                     <?php echo $resultados[estado_tramite];?>
                </td>
                
                <td>
                    <?php echo $resultados[descripcion_estado_tramite];?>
                </td>
                
                <td  align="center">
                    <?php 
                        if($resultados[tipo_estado_tramite]==1) {
                            echo '<font color="gree">';
                            $tetramite="Asignado";
                        } elseif($resultados[tipo_estado_tramite]==2) {
                            echo '<font color="blue">';
                            $tetramite="Completado";
                        }
                        else {
                            echo '<font color="red">';
                            $tetramite="Cerrada";
                        }
                        echo $tetramite.'</font>';
                    ?>
                </td>

                <td align="center"> 
                    <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=edo_tramite_drop&cod_estado_tramite=<?php echo $resultados[cod_estado_tramite];?>" title="Pulse para eliminar el registro">
                        <img border="0" src="images/borrar28.png" alt="borrar">
                    </a>
                    <a href="index2.php?view=edo_tramite_update&cod_estado_tramite=<?php echo $resultados[cod_estado_tramite];?>" title="Pulse para Modificar los datos registrados">
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
                                <a href="reportes/imprimir_lista_etramites.php" target="_blank">
                                    <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                    <span>Imprimir</span>
                                </a>
                            </div>
                        </div>

                        <div style="float:right;">
                            <div class="icon">
                                <a href="index2.php?view=edo_tramite_add">
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