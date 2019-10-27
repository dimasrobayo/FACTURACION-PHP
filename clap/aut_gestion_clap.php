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
    
    $query="SELECT * FROM clap,comunidades,parroquias,municipios,estados WHERE clap.idcom=comunidades.idcom and comunidades.codpar=parroquias.codpar and comunidades.codmun=parroquias.codmun and comunidades.codest=parroquias.codest and parroquias.codest=municipios.codest and parroquias.codmun=municipios.codmun and municipios.codest=estados.codest";
    $result = pg_query($query) or die(pg_last_error());
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="adminclap">
            <tr>
                <th>
                    CLAP:
                </th>
            </tr>
        </table>

        <br>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla" width="95%">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="3%">
                    ID
                </th>

                <th align="center" width="15%">
                    Nombre del CLAP
                </th>

                <th align="center" width="8%">
                    Jefe de Comunidad
                </th>

                <th align="center" width="8%">
                    Resp. UBCH
                </th>

                <th align="center" width="8%">
                    Resp. UNAMUJER
                </th>

                <th align="center" width="8%">
                    Resp. F. F. Miranda
                </th>

                <th align="center" width="8%">
                    Resp. MIN COMUNA
                </th>

                <th align="center" width="8%">
                    Resp. Pregonero Productivo
                </th>

                <th align="center" width="10%">
                    Municipio
                </th>

                <th align="center" width="10%">
                    Parroquia
                </th>

                <th align="center" width="10%">
                    Comunidad
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
                     <?php echo $resultados[codigo_clap];?>
                </td>

                <td>
                    <?php echo $resultados[nombre_clap];?>
                </td>

                <td>
                    <?php echo $resultados[jefe_comunidad];?>
                </td>

                <td>
                    <?php echo $resultados[resp_ubch];?>
                </td>

                <td>
                    <?php echo $resultados[resp_unamujer];?>
                </td>

                <td>
                    <?php echo $resultados[resp_francisco];?>
                </td>

                <td>
                    <?php echo $resultados[resp_mincomuna];?>
                </td>

                <td>
                    <?php echo $resultados[resp_pregonero];?>
                </td>

                <td>
                    <?php echo $resultados[desmun];?>
                </td>

                <td>
                    <?php echo $resultados[despar];?>
                </td>

                <td>
                    <?php echo $resultados[descom];?>
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
                    <th colspan="12" align="center">
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
                                    <a href="reportes/imprimir_lista_clap.php" target="_blank">
                                        <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                        <span>Imprimir</span>
                                    </a>
                                </div>
                            </div>
                    
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=clap_add">
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