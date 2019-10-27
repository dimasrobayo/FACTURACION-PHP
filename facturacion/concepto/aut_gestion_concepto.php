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

//    $query = "SELECT * FROM empresa, banco, tipo_cuenta, cuenta, concepto where empresa.rif_empresa = cuenta.rif_empresa and banco.codigo_banco = cuenta.codigo_banco and tipo_cuenta.codigo_tipo_cuenta = cuenta.codigo_tipo_cuenta and concepto.codigo_cuenta = cuenta.codigo_cuenta order by cuenta.codigo_cuenta";
    $query = "SELECT concepto_factura.status AS status_concepto,* FROM empresa, concepto_factura,categoria_concepto,marca_concepto,almacen_concepto where empresa.rif_empresa = concepto_factura.rif_empresa and marca_concepto.codigo_marca = concepto_factura.codigo_marca and categoria_concepto.codigo_categoria = concepto_factura.codigo_categoria and almacen_concepto.codigo_almacen=concepto_factura.codigo_almacen";
    $datos_consulta = pg_query($query)or die(pg_last_error());
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="adminconcepto">
            <tr>
                <th>
                    CONCEPTOS REGISTRADOS
                </th>
            </tr>
        </table>

        <br>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="8%">
                    C&oacute;digo
                </th>

                <th width="20%" align="center">
                    Descripci&oacute;n
                </th>

                <th width="10%" align="center">
                    Categoria
                </th>
                
                <th width="10%" align="center">
                    Ubicación/Almacen
                </th>
                
                <th width="8%" align="center">
                    Stock
                </th>

                <th width="8%" align="center">
                    C / U
                </th>

                <th width="8%" align="center">
                    Alicuota.
                </th>

                <th width="8%" align="center">
                    IVA.
                </th>

                <th width="8%" align="center">
                    P.V.P
                </th>

                <th width="8%" align="center">
                    Acciones
                </th>
            </tr>
        </thead>

<?php
$xxx=0;
while($resultados = pg_fetch_array($datos_consulta)){
    $xxx=$xxx+1;
?>
            <tr class="row0">
                <td align="center">
                    <?php echo $resultados[codigo_concepto] ?>
                </td>

                <td>
                    <?php echo $resultados[nombre_concepto]?> <?php echo " - "?> <?php echo $resultados[nombre_marca];?>
                </td>

                <td>
                    <?php echo $resultados[nombre_categoria] ?>
                </td>
                <td>
                    <?php echo $resultados[nombre_almacen] ?>
                </td>
                
                <?php if ($resultados[status_stock]=='1') {
                            $stock=$resultados[stock];
                          
                            $query="SELECT SUM(detalle_factura.cantidad)  as cant_concepto_facturado FROM detalle_factura INNER JOIN factura ON detalle_factura.n_factura=factura.n_factura WHERE detalle_factura.codigo_concepto = '$resultados[codigo_concepto]' and factura.status=1 GROUP BY detalle_factura.codigo_concepto";
                            $result = pg_query($query)or die(pg_last_error());
                            $resultados_facturado=pg_fetch_array($result);  
                            pg_free_result($result);
                            if ($resultados_facturado){
                                $stock_facturado=$resultados_facturado[cant_concepto_facturado];
                            }else{
                                $stock_facturado=0;
                            }
                        
                        } else {
                            $stock="-";
                            $stock_comprometido="-";
                            $stock_facturado="-";
                        }
                ?>

                <td align="center">
                    <?php echo number_format($resultados[stock],0, ',', '.') ?>
                </td>

                <td align="right">
                    <?php echo number_format($resultados[costo_unitario],2, ',', '.') ?>
                </td>

                <td align="right">
                    <?php echo number_format($resultados[alicuota_producto],2, ',', '.') ?>
                </td>

                <td align="right">
                    <?php echo number_format($resultados[iva_producto],2, ',', '.') ?>
                </td>

                <td align="right">
                    <?php echo number_format($resultados[precio_venta],2, ',', '.') ?>
                </td>
                
                <?php if ($resultados[status_concepto]==0) {
                          $ico=4;
                      } else {
                          $ico=3;
                      }
                ?>

                <td align="center">
                    <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=concepto_drop&codigo_concepto=<?php echo $resultados[codigo_concepto];?>" title="Pulse para eliminar el registro">
                        <img border="0" src="images/borrar28.png" alt="borrar">
                    </a>
                    <a href="index2.php?view=concepto_update&codigo_concepto=<?php echo $resultados[codigo_concepto];?>" title="Pulse para Modificar">
                        <img border="0" src="images/modificar.png" alt="borrar">
                    </a> 
                    <a onclick="return confirm('CONFIRMAR CAMBIO DE STATUS A ESTE CONCEPTO ?');" href="index2.php?view=concepto_status&status=<?php echo $resultados[status_concepto];?>&codigo_concepto=<?php echo $resultados[codigo_concepto];?>" title="Pulse para Cambiar STATUS del Concepto">
                        <img border="0" src="images/<?php echo $ico;?>.png" alt="borrar">
                    </a>
                </td>
            </tr>

<?php
}
?>
            
        <tfoot>
            <tr align="center">
                <th colspan="10" align="center">
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
                                <a href="reportes/imprimir_concepto.php" target="_blank">
                                    <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                    <span>Imprimir</span>
                                </a>
                            </div>
                        </div>

                        <div style="float:right;">
                            <div class="icon">
                                <a href="index2.php?view=concepto_add">
                                    <img src="images/producto.png" alt="agregar" align="middle"  border="0" />
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