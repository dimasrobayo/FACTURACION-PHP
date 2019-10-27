<html>
<head>
    <!-- Estilos Generales -->
    <!-- styles form-->
    <link rel="stylesheet" href="../../css/general_portada.css" type="text/css" />
    <link rel="stylesheet" href="../../css/styles_nuevo.css" type="text/css">
    <link rel="stylesheet" href="../../css/template_portada.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../../css/variations/css/variations/orangeblack.css" media="screen" /> 

    <!-- script del jquery, ajax y funciones javascript-->
    <script type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script> 
    <script language="javascript" src="../../js/ajax.js"></script>
    <script type="text/javascript" src="../../js/lib_javascript.js"></script>
    <script type="text/javascript" language="JavaScript1.2" src="../../js/funciones.js"></script>

    <!-- script de la mascaras -->
    <script src="../../js/jquery.maskedinput.js" type="text/javascript"></script>

    <!-- styles y script del calendario Fecha -->	
    <link type="text/css" rel="stylesheet" href="../../js/calendario/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
    <script type="text/javascript" src="../../js/calendario/dhtmlgoodies_calendar.js?random=20060118"></script>

    <!-- styles y script Validaciones -->
    <link rel="stylesheet" href="../../css/validationEngine.jquery.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/LiveValidation.css" type="text/css" media="screen" />	
    <script src="../../js/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
    <script src="../../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>	
    <script type="text/javascript" SRC="../../js/livevalidation_standalone.js"></script>

    <!-- styles y script de las Tablas Busqueda -->
    <link rel="stylesheet" href="../../css/table.css" type="text/css" media="screen" />	
    <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>		

    <script type="text/javascript">
        $.noConflict();
    </script>

    <!-- funciones javascript  -->
    <script type="text/javascript" charset="utf-8">
        jQuery(document).ready(function(){  
            jQuery("#QForm").validationEngine();
            jQuery("#facturacion").validationEngine();
            jQuery('#tabla').dataTable({
                "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "Todos"]],
                "sPaginationType": "full_numbers"          		
            });
            jQuery( "#accordion" ).accordion();
            jQuery("#hora_cita_programada").clockpick({
                starthour : 8,
                endhour : 18,
                military: false,
                minutedivisions: 12,
                showminutes : true
            });
            jQuery("#hora_atencion").clockpick({
                starthour : 8,
                endhour : 18,
                military: false,
                minutedivisions: 12,
                showminutes : true
            });
        }); 
    </script>
    
    <script language="javaScript">
	function enviar(codigo,descripcion,costo,status_stock,stock,cantidad,nombre_marca,excento,iva_producto)
            {
                parent.opener.document.formulario_lineas.codigo_concepto.value=codigo;
                parent.opener.document.formulario_lineas.descripcion_concepto.value=descripcion + " - " + nombre_marca;
                parent.opener.document.formulario_lineas.costo_unitario.value=costo;
                parent.opener.document.formulario_lineas.iva_producto.value=iva_producto;
                parent.opener.document.formulario_lineas.total.value=costo;
                parent.opener.document.formulario_lineas.status_stock.value=status_stock;
                parent.opener.document.formulario_lineas.stock.value=stock;
                parent.opener.document.formulario_lineas.cantidad.value=1;
                parent.opener.document.formulario_lineas.cantidad.select();
                parent.opener.document.formulario_lineas.cantidad.focus();
                parent.window.close();
            }
	
	</script>
</head>

<?php
    if (isset($_GET["codigo_nota"])) { 
        $codigo_nota=$_GET["codigo_nota"];
    }
        
    require("../../conexion/aut_config.inc.php");
    /*este es el enlace de conexion a la base de datos*/
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    $datos_consulta = pg_query("SELECT * FROM concepto_factura, marca_concepto, categoria_concepto WHERE categoria_concepto.codigo_categoria = concepto_factura.codigo_categoria and concepto_factura.codigo_marca = marca_concepto.codigo_marca and concepto_factura.status=1 order by concepto_factura.codigo_concepto") or die("No se pudo realizar la consulta a la Base de datos");
?>

<body>

<div align="center" class="centermain">
    <div class="main">  
        <div align="center">
            <font color="red" style="text-decoration:blink;"><?php $error_accion_ms[$error_cod]?></font>
        </div>

        <table class="adminconcepto">
            <tr>
                <th>
                    CATALOGO DE CONCEPTOS
                </th>
            </tr>
        </table>

<!--Estructura de Tabla de Contenidos de la Tabla usuario-->
        <table border="0" class="display" id="tabla">
            <thead>
                <tr>
                    <th align="center" width="15%">
                        C&oacute;digo
                    </th>

                    <th width="30%" align="center">
                        Descripci&oacute;n
                    </th>

                    <th width="15%" align="center">
                        Categoria
                    </th>

                    <th width="15%" align="center">
                        Genero
                    </th>

                    <th width="15%" align="center">
                        TOTAL
                    </th>

                    <th width="15%" align="center">
                        STOCK
                    </th>
                </tr>
            </thead>

<?php
    $xxx=0;
    while($resultados = pg_fetch_array($datos_consulta))
    {
        $query="SELECT * FROM detalle_nota WHERE codigo_nota='$codigo_nota' and codigo_concepto='$resultados[codigo_concepto]'";				
        $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());	
        $resultados_facturatmp=pg_fetch_array($result);
        if($resultados_facturatmp[0]){
            $cant_facturatmp=$resultados_facturatmp[cantidad];
        }else{
            $cant_facturatmp=0;
        }
        
        pg_free_result($result);
    
	$xxx=$xxx+1;
?>
            
            <tr class="row0">
                <td  align="center">
                    <?php
                        if ($resultados[status_stock]=='1') {
                            $total_producto=$resultados[costo_unitario]+$resultados[iva_producto];
                            if (($resultados[stock]-$cant_facturatmp)>0){
                                echo "<a href=\"javascript:enviar('".$resultados[codigo_concepto]."','".$resultados[nombre_concepto]."','".$resultados[costo_unitario]."','".$resultados[status_stock]."','".$resultados[stock]."','".$cant_facturatmp."','".$resultados[nombre_marca]."','".$resultados[excento]."','".$resultados[iva_producto]."');\" title=\"Pulse para Seleccionar\">".$resultados[codigo_concepto]."</a>";
                            }else{ 
                                echo  $resultados['codigo_concepto'];
                            }
                      } else {
                          echo "<a href=\"javascript:enviar('".$resultados[codigo_concepto]."','".$resultados[nombre_concepto]."','".$resultados[costo_unitario]."','".$resultados[status_stock]."','".$resultados[stock]."','".$cant_facturatmp."');\" title=\"Pulse para Seleccionar\">".$resultados[codigo_concepto]."</a>";
                      }
                    ?>
                </td>

                <td  align="left">
                    <?php echo $resultados[nombre_concepto]; echo " - "; echo $resultados[nombre_marca];?>
                </td>

                <td  align="center">
                    <?php echo $resultados[nombre_categoria];?>
                </td>

                <td  align="center">
                    <?php echo $resultados[nombre_marca];?>
                </td>

                <td  align="center">
                    <?php 
                        $total_producto=$resultados[costo_unitario]+$resultados[iva_producto];
                        echo $total_producto;
                    ?>
                </td>
                
                <?php if ($resultados[status_stock]=='1') {
                          $stock=$resultados[stock]-$cant_facturatmp;
                      } else {
                          $stock="-";
                      }
                ?>

                <td align="center">
                    <?php echo $resultados[stock] ?>
                </td>
            </tr>	

<?php
    }
?>
            <tfoot>
                <tr align="right">
                    <th colspan="8" align="center">
                        <div id="cpanel">
                            <div style="float:left;">
                                <div class="icon">
                                    <a onclick="parent.window.close();">
                                    <img src="../../images/cpanel.png" alt="salir" align="middle"  border="0" />
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
</body>
</html>