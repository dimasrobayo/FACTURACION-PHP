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
    
    $datos_consulta = pg_query("SELECT * FROM unidades order by cod_unidad") or die(pg_last_error());
?>
<!-- Ventanas emergentes -->
<script type="text/javascript" charset="utf-8">			      
    jQuery(document).ready(function(){
        /* normal effects*/ 
        jQuery('.fancybox-normal').fancybox();

        /* Con effects*/ 		
        jQuery(".fancybox").fancybox({
            maxWidth	: 550,
            maxHeight	: 290,
            fitToView	: false,
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none',
//			padding : 0, 
//			type: 'iframe',       		
            helpers : {
                title : null            
            }        		
        });


        jQuery(".fancybox-foto").fancybox({
            maxWidth	: 550,
            maxHeight	: 250,
            fitToView	: false,
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none',
//			padding : 0, 
//			type: 'iframe',      		
            helpers : {
                title : null            
            }        		
        });			
    });  
	
	                    
</script>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="adminunidades">
            <tr>
                <th>
                    DEPENDENCIAS/UNIDADES:
                </th>
            </tr>
        </table>

        <br>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="3%">
                    ID
                </th>
                <th width="15%" align="center">
                    Nombre
                </th>
                <th width="17%" align="center">
                    Responsable
                </th>
                <th width="17%" align="center">
                    Direcci&oacute;n
                </th>
                <th width="17%" align="center">
                    Horario
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
                     <?php echo $resultados[cod_unidad];?>
                </td>
                <td>
                    <?php echo $resultados[nombre_unidad];?>
                </td>
                <td>
                    <?php echo $resultados[responsable_unidad];?>
                </td>
                <td>
                    <?php echo $resultados[direccion_unidad];?>
                </td>
                <td>
                    <?php echo $resultados[horario_unidad];?>
                </td>

                <?php if ($resultados[status_unidad]=='0') {
                          $ico=4;
                      } else {
                          $ico=3;
                      }
                ?>
                
                <td align="center"> 
                    <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=unidad_drop&cod_unidad=<?php echo $resultados[cod_unidad];?>" title="Pulse para eliminar el registro">
                        <img border="0" src="images/borrar28.png" alt="borrar">
                    </a>
                    <a href="index2.php?view=unidad_update&cod_unidad=<?php echo $resultados[cod_unidad];?>" title="Pulse para Modificar los datos registrados">
                        <img border="0" src="images/modificar.png" alt="borrar">
                    </a>
                    <a href="index2.php?view=tramites&cod_unidad=<?php echo $resultados[cod_unidad];?>" title="Pulse para Gestionar Traites de la Unidad">
                        <img border="0" src="images/tramites28.png" alt="borrar">
                    </a> 
                    <a class="fancybox fancybox.iframe" href="unidades/unidad_view.php?cod_unidad=<?php echo $resultados[cod_unidad];?>"  title="Ver Detalles del Registro">
                        <img border="0" src="images/icon-28-search.png" alt="borrar">				
                    </a>
                    
                    <a onclick="return confirm('CONFIRMAR CAMBIO DE STATUS A ESTA UNIDAD ?');" href="index2.php?view=unidad_status&status=<?php echo $resultados[status_unidad];?>&cod_unidad=<?php echo $resultados[cod_unidad];?>" title="Pulse para Cambiar STATUS de la Unidad">
                        <img border="0" src="images/<?php echo $ico;?>.png" alt="borrar">
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
                                    <a href="reportes/imprimir_lista_unidades.php" target="_blank">
                                        <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                        <span>Imprimir</span>
                                    </a>
                                </div>
                            </div>
                    
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=unidad_add">
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
