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
    $pagina=$pag.'?view='.$view;
    
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    //codigo para colocar la hora.
    $hora=date("h").":".date("i")." ".date("a");

    if (isset($_POST['fecha_factura'])){
        $fecha_factura=implode('-',array_reverse(explode('/',$_POST["fecha_factura"])));
        $condicion=$_POST['condicion'];
    }else{
        $fecha_factura=date('Y-m-d');
    }

    $cedula_usuario=$_SESSION['id'];
    
    $query="SELECT factura.n_factura, factura.cedula_rif,solicitantes.nombre_solicitante,solicitantes.nombre_solicitante, factura.fecha_factura, factura.iva ,SUM(detalle_factura.monto_concepto*detalle_factura.cantidad)  as total_factura,SUM((detalle_factura.monto_concepto*detalle_factura.cantidad*factura.iva)/100)  as total_factura_iva , factura.status,factura.monto_efectivo,factura.monto_deposito,factura.monto_cheque,COUNT(*) as nro_detalles
            FROM detalle_factura INNER JOIN factura ON detalle_factura.n_factura=factura.n_factura INNER JOIN solicitantes ON solicitantes.cedula_rif = factura.cedula_rif
            WHERE factura.fecha_factura = '$fecha_factura' and factura.status::text like  '%$condicion%'
            GROUP BY factura.n_factura,solicitantes.cedula_rif";
    
//    $query = "SELECT * FROM solicitantes,factura where factura.fecha_factura = '$fecha_factura' and solicitantes.cedula_rif = factura.cedula_rif order by solicitantes.cedula_rif";
    $datos_consulta = pg_query($query)or die(pg_last_error());
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="adminfactura">
            <tr>
                <th>
                    VENTAS
                </th>
            </tr>
        </table>
        
        <form  name="QForm" id="QForm" method="POST" action="" enctype="multipart/form-data">
            <table class="adminlist"  width="100%" border="0" >		
                <tr>	        
                    <td width="20%" >	        		
                        <div>
                            <strong>FECHA:</strong>
                            <input class="validate[required,custom[date],past[NOW]]" id="fecha_factura" name="fecha_factura" type="text"  value="<?php echo implode('/',array_reverse(explode('-',$fecha_factura)));?>" size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"/>
                            <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_factura,'dd/mm/yyyy',this);">
                            <img src="images/ver.png" width="16" height="16" onClick="javascript: submit_facturas();" onMouseOver="style.cursor=cursor">
                        </div> 					
                    </td>
                    
                    <td width="50%" >	        		
                        <div>
                            
                        </div> 					
                    </td>
                    
                    
                    <td align="right">	        		
                        <div>
                            <strong>STATUS FACTURA:</strong>
                            <select name="condicion" id="condicion" onchange="javascript: submit_facturas();" >
                                <?php 
                                    echo '<option value="" selected="selected">TODOS</option>';
                                    if ($condicion!=""){
                                        if ($condicion==1){
                                            echo '<option value="1" selected="selected">ACTIVOS</option>';
                                            echo '<option value="0" >ANULADOS</option>';
                                        }else {
                                            echo '<option value="1" >ACTIVOS</option>';
                                            echo '<option value="0" selected="selected">ANULADOS</option>';
                                        }
                                    }else {
                                        echo '<option value="1" >ACTIVOS</option>';
                                        echo '<option value="0" >ANULADOS</option>';
                                    }
                                ?>
                            </select>
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
                    <th width="30%" align="center">
                            Nombre Persona/Razón Social
                    </th>
                    <th width="8%" align="center">
                            Fecha
                    </th>
                    <th width="10%" align="center">
                            Monto Factura
                    </th>
                    <th width="8%" align="center">
                            Monto Efectivo
                    </th>
                    <th width="8%" align="center">
                            Monto Deposito
                    </th>
                    <th width="8%" align="center">
                            Monto Cheque
                    </th>
                    <th width="5%" align="center">
                            Status
                    </th>
                    <th width="5%" align="center">
                            Acciones
                    </th>
                </tr>
        </thead>

<?php
    while($resultados = pg_fetch_array($datos_consulta)){
        $importe= $resultados['total_factura'];
        $importe_iva=$resultados['total_factura_iva'];
        $total_factura=number_format($importe+$importe_iva,2,".","");
//        $total_factura=sprintf("%01.2f", $importe+$importe_iva);
        $total_facturado+=$total_factura;
        
?>

        <tr class="row0">
            <td>
                <?php echo str_pad($resultados['n_factura'],10,"0",STR_PAD_LEFT);?>

            </td>
            <td>
                <?php echo substr_replace($resultados['cedula_rif'],'-',1,0).'  -  '.$resultados['nombre_solicitante'];?>
            </td>

            <td align="center">
                <?php echo date_format(date_create($resultados['fecha_factura']), 'd/m/Y');?>
            </td>
            <td align="right">
                <?php echo $total_factura;?>
            </td>
            <td align="right">
                <?php echo $resultados['monto_efectivo'];?>
            </td>
            <td align="right">
                <?php echo $resultados['monto_deposito'];?>
            </td>
            <td align="right">
                <?php echo $resultados['monto_cheque'];?>
            </td>
            <td align="center">
                <?php 
                    if ($resultados['status']==0){
                        echo '<font color="Red">ANULADO</font>';
                    }else {
                        echo '<font color="Green">ACTIVO</font>';
                    }
                ?>
            </td>

            <td align="center"> 
                <?php if ($resultados[status]==1) { ?>  
                    <a onclick="return confirm('Esta seguro que desea Anular la Factura?');" href="index2.php?view=factura_anular&n_factura=<?php echo $resultados[n_factura];?>" title="Pulse para Anular la Factura">
                        <img border="0" src="images/borrar28.png" alt="borrar">
                    </a>
                <?php }else{ ?> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php } ?> 
                
                <a href="reportes/imprimir_factura.php?codfactura=<?php echo $resultados[n_factura];?>" title="Pulse para Imprimir el Registro" target="_black">
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
                                    <a href="reportes/imprimir_cierre.php?fecha_cierre=<?php echo $fecha_factura;?>&cajero=<?php echo ($_SESSION['username'])?>" title="Pulse para Imprimir el Registro Diario" target="_black">
                                        <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                        <span>Imprimir</span>
                                    </a>
                                </div>
                            </div>

                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=factura_add">
                                        <img src="images/facturanueva.png" alt="agregar" align="middle"  border="0" />
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
                  <td width="15%" >	        		
                    <div>
                        <strong>TOTAL FACTURADO: </strong><font color="Green"> <strong><?php echo number_format($total_facturado, 2, '.', '');?></strong></font>
                        
                    </div> 					
                  </td>

                  <td>	        		
                    <div>
                         <strong>TOTAL EN LETRAS: </strong><?php  echo numtoletras(number_format($total_facturado, 2, '.', ''));   ?>
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