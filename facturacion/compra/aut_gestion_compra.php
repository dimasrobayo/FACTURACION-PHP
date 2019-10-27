<?php
   // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "") {
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

    $cedula_usuario=$_SESSION['id'];
    $iva=

    //codigo para colocar la hora.
    $hora=date("h").":".date("i")." ".date("a");

    if (isset($_POST['fecha_nota'])){
        $fecha_compra=implode('-',array_reverse(explode('/',$_POST["fecha_nota"])));
        $condicion=$_POST['condicion'];
    }else{
        $fecha_compra=date('Y-m-d');
    }
    
    $query="SELECT compra.codigo_compra, compra.n_factura, compra.cedula_rif, compra.cedula_usuario, compra.fecha_compra,solicitantes.nombre_solicitante,solicitantes.nombre_solicitante FROM compra, solicitantes WHERE compra.cedula_rif=solicitantes.cedula_rif and compra.fecha_compra = '$fecha_compra' GROUP BY compra.codigo_compra, solicitantes.cedula_rif";
    
    $datos_consulta = pg_query($query)or die(pg_last_error());

    $query_detalle="SELECT compra.codigo_compra, compra.n_factura, compra.cedula_rif,solicitantes.nombre_solicitante,solicitantes.nombre_solicitante, compra.fecha_compra, detalle_compra.cantidad, detalle_compra.monto_concepto, detalle_compra.iva_facturado FROM compra, detalle_compra, solicitantes WHERE compra.cedula_rif=solicitantes.cedula_rif and detalle_compra.codigo_compra=compra.codigo_compra and compra.fecha_compra = '$fecha_compra'";
    
    $datos_detalle = pg_query($query_detalle)or die(pg_last_error());
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="admincompra">
            <tr>
                <th>
                    COMPRAS A PROVEEDOR
                </th>
            </tr>
        </table>
        
        <form  name="QForm" id="QForm" method="POST" action="" enctype="multipart/form-data">
            <table class="adminlist"  width="100%" border="0" >     
                <tr>            
                    <td width="20%" >                   
                        <div>
                            <strong>FECHA:</strong>
                            <input class="validate[required,custom[date],past[NOW]]" id="fecha_nota" name="fecha_nota" type="text"  value="<?php echo implode('/',array_reverse(explode('-',$fecha_nota)));?>" size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"/>
                            <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_nota,'dd/mm/yyyy',this);">
                            <img src="images/ver.png" width="16" height="16" onClick="javascript: submit_facturas();" onMouseOver="style.cursor=cursor">
                        </div>                  
                    </td>
                </tr>
            </table>
        </form>

        <br>
 
        <br>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
            <thead>
                <tr bgcolor="#55baf3">
                    <th width="5%" align="center">
                            C&oacute;digo
                    </th>
                    <th width="8%" align="center">
                            N Factura
                    </th>
                    <th width="45%" align="center">
                            Nombre Persona/Razón Social
                    </th>
                    <th width="8%" align="center">
                            Fecha
                    </th>
                    <th width="15%" align="center">
                            Acciones
                    </th>
                </tr>
        </thead>

<?php
    while($resultados = pg_fetch_array($datos_consulta)){
        while ($resultados_detalle = pg_fetch_array($datos_detalle)) {
            $importe = $resultados_detalle['monto_concepto'];
            $importe_iva=$resultados_detalle['iva_facturado'];
            $cantidad=$resultados_detalle['cantidad'];
            $total_nota=number_format(($importe)+$importe_iva,2,".","");
    //        $total_factura=sprintf("%01.2f", $importe+$importe_iva);
            $total_facturado+=$total_nota;
        }        
?>

        <tr class="row0">
            <td>
                <?php echo str_pad($resultados['codigo_compra'],10,"0",STR_PAD_LEFT);?>

            </td>

            <td>
                <?php echo str_pad($resultados['n_factura'],10,"0",STR_PAD_LEFT);?>

            </td>

            <td>
                <?php echo substr_replace($resultados['cedula_rif'],'-',1,0).'  -  '.$resultados['nombre_solicitante'];?>
            </td>

            <td align="center">
                <?php echo date_format(date_create($resultados['fecha_compra']), 'd/m/Y');?>
            </td>

            <td align="center">
                <a onclick="return confirm('Esta seguro que desea Eliminar la Factura?');" href="index2.php?view=compra_drop&codigo_compra=<?php echo $resultados[codigo_compra];?>" title="Pulse para Anular la Nota de Entrega">
                    <img border="0" src="images/borrar28.png" alt="borrar">
                </a>

                <a href="index2.php?view=compra_update&codigo_compra=<?php echo $resultados[codigo_compra];?>" title="Pulse para Modificar el Nivel de Acceso">
                    <img border="0" src="images/modificar.png" alt="borrar">
                </a>

                <a href="index2.php?view=detalle_compra_add&codigo_compra=<?php echo $resultados[codigo_compra];?>" title="Pulse para Modificar el Nivel de Acceso">
                    <img border="0" src="images/detalle_cliente.png" alt="borrar">
                </a>

                <a href="reportes/imprimir_compra.php?codigo_compra=<?php echo $resultados[codigo_compra];?>" title="Pulse para Imprimir el Registro" target="_black">
                    <img border="0" src="images/imprimir.png" alt="imprimir">
                </a>
            </td>
        </tr>

<?php } ?>

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
                                    <a href="reportes/imprimir_cierre_compra.php?fecha_compra=<?php echo $fecha_compra;?>&cajero=<?php echo ($_SESSION['username'])?>" title="Pulse para Imprimir el Registro Diario" target="_black">
                                        <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                        <span>Imprimir</span>
                                    </a>
                                </div>
                            </div>

                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=compra_add">
                                        <img src="images/factura_proveedor.png" alt="agregar" align="middle"  border="0" />
                                        <span>Agregar</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </th>
                </tr>
            </tfoot>
        </table>
        <table class="adminlist"  width="100%" border="1" >     
                <tr>            
                  <td width="35%" >                 
                    <div>
                        <strong>TOTAL EN COMPRAS: </strong><font color="Green"> <strong><?php echo number_format($total_facturado, 2, '.', '');?></strong></font>
                    </div>                  
                  </td>

                  <td>                  
                    <div>
                         <strong>TOTAL EN LETRAS: </strong>
                         <?php  echo numtoletras(number_format($total_facturado, 2, '.', ''));?>
                    </div>                  
                  </td>
              </tr>
          </table>
    </div>
</div>

<?php
    pg_free_result($datos_consulta);
    pg_close();
?>