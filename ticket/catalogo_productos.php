<html>
<head>
    <!-- Estilos Generales -->
    <!-- styles form-->
    <link rel="stylesheet" href="../css/general_portada.css" type="text/css" />
    <link rel="stylesheet" href="../css/styles_nuevo.css" type="text/css">
    <link rel="stylesheet" href="../css/template_portada.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../../css/variations/css/variations/orangeblack.css" media="screen" /> 

    <!-- script del jquery, ajax y funciones javascript-->
    <script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script> 
    <script language="javascript" src="../js/ajax.js"></script>
    <script type="text/javascript" src="../js/lib_javascript.js"></script>
    <script type="text/javascript" language="JavaScript1.2" src="../js/funciones.js"></script>

    <!-- script de la mascaras -->
    <script src="../js/jquery.maskedinput.js" type="text/javascript"></script>

    <!-- styles y script del calendario Fecha -->	
    <link type="text/css" rel="stylesheet" href="../js/calendario/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
    <script type="text/javascript" src="../js/calendario/dhtmlgoodies_calendar.js?random=20060118"></script>

    <!-- styles y script Validaciones -->
    <link rel="stylesheet" href="../css/validationEngine.jquery.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../css/LiveValidation.css" type="text/css" media="screen" />	
    <script src="../js/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
    <script src="../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>	
    <script type="text/javascript" SRC="../js/livevalidation_standalone.js"></script>

    <!-- styles y script de las Tablas Busqueda -->
    <link rel="stylesheet" href="../css/table.css" type="text/css" media="screen" />	
    <script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>		

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
	function enviar(codigo,descripcion,costo,stock){
            parent.opener.document.QForm.descripcion_ticket.value=parent.opener.document.QForm.descripcion_ticket.value+" "+descripcion;
            parent.window.close();
        }
	
	</script>
</head>

<?php
    if (isset($_GET["cod_uni"])) { 
        $unidad=$_GET["cod_uni"];
    }
        
    require("../conexion/aut_config.inc.php");
    /*este es el enlace de conexion a la base de datos*/
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

//    $datos_consulta = pg_query("SELECT * from tramites where cod_unidad='$unidad' and status_tramite=1 order by nombre_tramite") or die("No se pudo realizar la consulta a la Base de datos");
    $datos_consulta = pg_query("SELECT * from concepto_factura where status=1 order by nombre_concepto") or die("No se pudo realizar la consulta a la Base de datos");
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
                    CATALOGO DE PRODUCTOS
                </th>
            </tr>
        </table>

<!--Estructura de Tabla de Contenidos de la Tabla usuario-->
        <table border="0" class="display" id="tabla">
            <thead>
                <tr>
                    <th align="center" width="20%">
                        C&oacute;digo
                    </th>

                    <th width="50%" align="center">
                        Descripci&oacute;n
                    </th>

                    <th width="20%" align="center">
                        Precio Venta
                    </th>
                    <th width="12%" align="center">
                        Existencia
                    </th>

                    
                </tr>
            </thead>

<?php
    while($resultados = pg_fetch_array($datos_consulta)) {
?>
            
            <tr class="row0">
                <td  align="center">
                    
                    <?php 
                        echo "<a href=\"javascript:enviar('".$resultados[codigo_concepto]."','".$resultados[nombre_concepto]."','".$resultados[precio_venta]."','".$resultados[stock]."');\" title=\"Pulse para Seleccionar\">".$resultados[codigo_concepto]."</a>";
                    ?>
                </td>
                <td  align="left">
                    <?php echo $resultados[nombre_concepto];?>
                </td>

                <td  align="center">
                    <?php echo $resultados[precio_venta];?>
                </td>
                <?php if ($resultados[status_stock]=='1') {
                          $stock=$resultados[stock];
                      } else {
                          $stock="-";
                      }
                ?>

                <td align="center">
                    <?php echo $stock ?>
                </td>
            </tr>	
<?php
}
?>
            <tfoot>
                <tr align="right">
                    <th colspan="4" align="center">
                        <div id="cpanel">
                            <div style="float:left;">
                                <div class="icon">
                                    <a onclick="parent.window.close();">
                                    <img src="../images/cpanel.png" alt="salir" align="middle"  border="0" />
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