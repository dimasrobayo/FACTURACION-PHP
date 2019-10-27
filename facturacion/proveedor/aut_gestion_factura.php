<?php
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$type;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    //codigo para colocar la hora.
    $hora=date("h").":".date("i")." ".date("a");

    //codigo para colocar la hora.
    $hora=date("h").":".date("i")." ".date("a");

    if (isset($_POST['fecha_factura'])){
            $fecha_factura=implode('-',array_reverse(explode('/',$_POST["fecha_factura"])));

    }else{
        $fecha_factura=date('Y-m-d');
    }

    $datos_consulta = pg_query("SELECT * FROM solicitantes,factura where factura.fecha_factura = '$fecha_factura' and solicitantes.cedula_rif = factura.cedula_rif order by solicitantes.cedula_rif") or die("Error: ".  pg_last_error());
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
                    FACTURAS REGISTRADAS
                </th>
            </tr>
        </table>
        
        <br>
        
        <form  name="aut_facturas" method="POST" action="" enctype="multipart/form-data">
            <table class="dataTables_length" border="0">		
                <tr >	        
                    <td>	        		
                        BUSCAR FECHA:		        		
                           <input class="validate[required,custom[date],past[01/01/1998],future[01/01/1934]]" id="fecha_factura" name="fecha_factura" type="text"  value="<?php echo implode('/',array_reverse(explode('-',$fecha_factura)));?>" size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"/>
                            <input type="button" value="Cal" onclick="displayCalendar(document.forms[0].fecha_factura,'dd/mm/yyyy',this)">         
                            <img src="images/ver.png" width="16" height="16" onClick="javascript: submit_facturas();" onMouseOver="style.cursor=cursor">
                        					
                    </td> 		
                    <td>	        		
                       					
                    </td> 		
                </tr>
            </table>
        </form>
 
        <br>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
            <thead>
                <tr bgcolor="#55baf3">
                    <th align="center" width="10%">
                            Nº de Factura
                    </th>

                    <th width="12%">
                            Fecha de Factura
                    </th>

                    <th width="8%">
                            Cedula/RIF 
                    </th>

                    <th width="25%" align="center">
                            Nombre y Apellido del Cliente
                    </th>


                    <th width="10%" align="center">
                            Tel&eacute;fono
                    </th>
                    <th width="20%" align="center">
                            Estatus
                    </th>
                    <th width="20%" align="center">
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
                    <?php echo $resultados[n_factura] ?>
            </td>

            <td  align="center">
                    <?php echo $resultados[fecha_factura] ?>
            </td>

            <td  align="center">
                    <?php echo $resultados[cedula_solicitante] ?>
            </td>

            <td>
                    <?php echo $resultados[nombre_solicitante]; echo " "; echo $resultados[apellido_solicitante];?> 
            </td>

            <td>
                    <?php echo $resultados[telefono_solicitante] ?>
            </td>
            
            <td align="center">
                <?php 
                    
                    if ($resultados[id_status]==0) {
                        echo '<font color="red">';
                        echo 'Anulada'.'</font>';
                    } elseif ($resultados[id_status]==1)
                    {
                        echo '<font color="gren">';
                        echo 'Activa'.'</font>';
                    }
                    
                ?> 
            </td>

            <td align="center"> 
                   <a onclick="return confirm('Esta seguro que desea Anular la Factura?');" href="index2.php?view=factura_unlock&n_factura=<?php echo $resultados[n_factura];?>&status=<?php echo $resultados[id_status];?>" title="Pulse para Anular la Factura">
                            <img border="0" src="images/<?php echo $resultados[id_status];?>.png" alt="borrar">
                    </a>
                    
                    <?php
                       if ($resultados[id_status]<> 0) {
                    ?>
                        <a href="reportes/imprimir_factura.php?codfactura=<?php echo $resultados[n_factura];?>" title="Pulse para Imprimir el Registro" target="_black">
                                <img border="0" src="images/imprimir.png" alt="imprimir">
                        </a> 
                    <?php
                       }
                    ?>        
            </td>
        </tr>

<?php
}
?>

            <tfoot>
                <tr align="center">
                    <th colspan="7" align="center">
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
                                    <a href="reportes/imprimir_cierre.php?fecha_cierre=<?php echo $fecha_factura;?>&cajero=<?php echo $_SESSION[usuario_nombre]?>" title="Pulse para Imprimir el Registro Diario" target="_black">
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
    </div>
</div>

<?php
    pg_free_result($datos_consulta);
    pg_close();
?>