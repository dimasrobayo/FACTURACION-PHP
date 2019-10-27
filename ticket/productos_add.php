
<?php
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        exit;
    }
    
    require("../conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    if (isset($_GET["codfacturatmp"])) { 
        $codfacturatmp=$_GET["codfacturatmp"];
        $iva=$_GET["iva"];
        $subtotal=0;
        
        $query="SELECT * FROM detalle_facturatmp WHERE detalle_facturatmp.n_factura='$codfacturatmp'";
        $result_lineas=pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
        
        for ($i = 0; $i < pg_num_rows($result_lineas); $i++) {
            $cantidad=pg_result($result_lineas,$i,"cantidad");
            $monto_concepto=pg_result($result_lineas,$i,"monto_concepto");
            $total=number_format($cantidad*$monto_concepto,2,".","");
            $subtotal+=$total;
        }
        
        
    }
	
    if (isset($_POST["monto_total"])) {
        $monto_total=$_POST['monto_total'];
        $codfacturatmp=$_POST['codfacturatmp'];
        
        $query="SELECT * FROM concepto_factura,detalle_facturatmp WHERE detalle_facturatmp.n_factura='$codfacturatmp' AND detalle_facturatmp.codigo_concepto=concepto_factura.codigo_concepto";
        $result_lineas_general=pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
        $cantidad_solicitud=0;
        
        for ($j = 0; $j < pg_num_rows($result_lineas_general); $j++) {
            $cantidad=pg_result($result_lineas_general,$j,"cantidad");
            $monto_concepto=pg_result($result_lineas_general,$j,"monto_concepto");
            $nombre_concepto=pg_result($result_lineas_general,$j,"nombre_concepto");
            
            $cantidad_solicitud+=$cantidad;
            $descripcion_ticket=$descripcion_ticket." - ".$cantidad." ".$nombre_concepto.";";
        }
        
            echo "<script type=\"text/javascript\">
                    parent.opener.document.QForm.descripcion_ticket.value='$descripcion_ticket';
                    parent.opener.document.QForm.cantidad_solicitud.value='$cantidad_solicitud';
                    parent.opener.document.QForm.monto_solicitud.value=".$monto_total.".toFixed(2);
                    close();
                  </script>";
//            cargarContenidoTramiteUnidad();
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<html>
    <head>
        <title>Mensaje</title>
        <meta charset="UTF-8">
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
        <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8"/>
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Language" content="es-VE">
        <meta http-equiv="Content-Script-Type" content="text/javascript">
        <link rel="shortcut icon" href="../images/favicon.ico" />
        
        <!-- styles form-->
        <!--<link rel="stylesheet" href="../../css/template_portada.css" type="text/css" />-->
        <link rel="stylesheet" href="../css/general_portada.css" type="text/css" />
        <!--<link rel="stylesheet" type="text/css" href="../../css/styles_general.css" media="screen" />-->
        <link rel="stylesheet" href="../css/styles_nuevo.css" type="text/css"/>
        <!--<link rel="stylesheet" href="../../css/template.css" type="text/css" />-->
        <link rel="stylesheet" href="../css/template_portada.css" type="text/css" />

        <!-- script del jquery, ajax y funciones javascript-->
        <script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>   
       <script language="javascript" src="../js/ajax.js"></script>
        <script type="text/javascript" src="../js/lib_javascript.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../js/funciones.js"></script>
        <!-- <script type="text/javascript" language="JavaScript1.2" src="../js/disabled_keys.js"></script> -->

        <!-- script de la mascaras -->
        <script src="../js/jquery.maskedinput.js" type="text/javascript"></script>
        
        <!-- styles y script del calendario Fecha -->	
        <link type="text/css" rel="stylesheet" href="../js/calendario_cat/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
        <script type="text/javascript" src="../js/calendario_cat/dhtmlgoodies_calendar_cat.js?random=20060118"></script>

        <!-- styles y script Validaciones -->

        <link rel="stylesheet" href="../css/validationEngine.jquery.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="../css/LiveValidation.css" type="text/css" media="screen" />	
        <script src="../js/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>	
        <script type="text/javascript" SRC="../js/livevalidation_standalone.js"></script>    	

    <!-- script de efectos -->	
        <script src="../js/prototype.js" type="text/javascript"></script>
        <script src="../js/scriptaculous.js" type="text/javascript"></script>
        <script src="../js/unittest.js" type="text/javascript"></script> 
        
        <!-- Token Input -->
      <script type="text/javascript" src="../js/tokeninput/src/jquery.tokeninput.min.js"></script>
      <link rel="stylesheet" href="../js/tokeninput/styles/token-input.css" type="text/css" />
      <link rel="stylesheet" href="../js/tokeninput/styles/token-input-facebook.css" type="text/css" />
      
      <script type="text/javascript" charset="utf-8">            
            jQuery(document).ready(function(){          
              jQuery("#QForm").validationEngine();          
            });
            
            
       </script>
        <script language="JavaScript">
            function aceptar(cedula) {
                opener.document.QForm.cedula_rif.value=cedula;
                
                close();
            }
        </script>
        
    </head>
<body style="background-color: #f9f9f9;" >
<?php if($div_menssage) { ?>					
    <script type="text/javascript">
            function ver_msg(){
                    Effect.Fade('msg');
            }  
            setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
    </script>
 <?php } ?>
    
    <!-- Codigo para mostrar la ayuda al usuario  -->
    <div style="top: 477px; left: 966px; display: none;" id="mensajesAyuda">
            <div id="ayudaTitulo">Código de Seguridad (Obligatorio)</div>
            <div id="ayudaTexto">Ingresa el código de seguridad que muestra la imagen</div>
    </div>
    
    <table class="container_contenido_cat" border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>  			
            <tr>
                <td>
                    <form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_lineas">
                        <input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp ?>" type="hidden">
                        <input id="iva" name="iva" value="<?php echo $iva?>" type="hidden">
                        <input id="control" name="control" value="<?php echo $control?>" type="hidden">

                        <table class="adminform" border="0" width="100%">
                            <tr>
                                <th class="rowformleft" colSpan="2" width="100%"  height="16">
                                    <span>PRODUCTOS DE LA SOLICITUD</span> 				
                                </th>
                            </tr>

                            <tr id="periodo">
                                <td colspan="2">
                                    <table  class="adminform" width="100%" cellspacing=0 cellpadding=0 border=1 >
                                        <tr>
                                            <td width="16%" >
                                                <input type="text" placeholder="Código Concepto" id="codigo_concepto" name="codigo_concepto" maxlength="25" size="20" onblur="cargarContenedorProducto()"/>
                                                <a href="javascript: ventanaproductos();"><img src="../images/busqueda.png" alt="Buscar" title="Buscar Concepto" width="15" height="15" border="0"></a>
                                                <!--<img src="images/ver.png" width="16" height="16" onClick="ventanaconceptos()" onMouseOver="style.cursor=cursor" id="buscar_concepto" name="buscar_concepto">-->
                                                </td>
                                            <td width="84%">
                                                <div id="ContenedorConcepto">
                                                    <input id="status_stock" name="stock_stock" value="" type="hidden">
                                                    <input id="stock" name="stock" value="" type="hidden">
                                                    <input id="cantidad_factura" name="cantidad_factura" value="" type="hidden">
                                                    DESCRIPCION:
                                                    <input type="text" id="descripcion_concepto" name="descripcion_concepto" readonly="true"  size="50"/>       		
                                                    &nbsp;&nbsp;&nbsp;

                                                    PRECIO:
                                                    <input type="text" id="costo_unitario" style="text-align:right" name="costo_unitario" readonly="true" size="12">	
                                                    &nbsp;&nbsp;&nbsp;

                                                    CANTIDAD:
                                                    <input type="text" id="cantidad" style="text-align:right" name="cantidad" size="6" value="1" onfocus="actualizar_importe()" onkeyup="actualizar_importe()">	
                                                    &nbsp;&nbsp;&nbsp;

                                                    MONTO:
                                                    <input type="text" style="text-align:right" id="total" name="total" size="12" readonly="true">
                                                    &nbsp;&nbsp;&nbsp;


                                                </div>
                                            </td>
                                            <td align="center">
                                                <input  class="button" type="button" onClick="validarproducto()" value="Agregar" name="agregar" /> 
                                               <!--<img src="../images/botonagregar.jpg" width="72" height="22" border="1" onClick="validar()" onMouseOver="style.cursor=cursor" title="Agregar articulo">-->
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                            </tr>



                            <tr>
                                <td colspan="2">
                                    <div id="frmBusqueda">
            <!--                            <table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 id="Table1">
                                            <tr class="cabeceraTabla">
                                                <td class="aCentro" width="5%"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ITEM</td>
                                                <td align="center" width="5%">&nbsp;REFERENCIA</td>
                                                <td align="center" width="35%">DESCRIPCION</td>
                                                <td align="center" width="6%">&nbsp;&nbsp;&nbsp;CANTIDAD</td>
                                                <td align="center" width="6%">&nbsp;&nbsp;&nbsp;PRECIO</td>
                                                <td align="center" width="6%">MONTO</td>
                                                <td align="center" width="3%">&nbsp;</td>
                                            </tr>
                                        </table>-->
                                        <table class="adminform" width="100%" cellspacing=0 cellpadding=0 border=1 >
                                            <tr>
                                                <td align="center" width="5%" height="12"  class="botones"  ><strong>ITEM</strong></td>
                                                <td align="center" width="10%" height="10"  class="botones"  ><strong>REFERENCIA </strong></td>
                                                <td align="center" width="35%" height="12"  class="botones"  ><strong>DESCRIPCIÓN DEL CONCEPTO</strong></td>
                                                <td align="center" width="6%" height="12"  class="botones"  ><strong>CANTIDAD</strong></td>
                                                <td align="center" width="6%" height="12"  class="botones"  ><strong>PRECIO</strong></td>
                                                <td align="center" width="6%" height="12"  class="botones"  ><strong>MONTO</strong></td>
                                                <td align="center" width="3%" height="12"  class="botones"  >&nbsp;</td>
                                            </tr>
                                        </table>

                                        <div id="lineaResultado">
                                            <iframe width="100%" height="100" id="frame_lineas" name="frame_lineas" frameborder="0">
                                                <ilayer width="100%" height="100" id="frame_lineas" name="frame_lineas"></ilayer>
                                            </iframe>
                                        </div>
                                    </div>
                                </td>	
                            </tr>

                            <tr>
                                <td  colspan="2"   height="16">
                                    <hr size="1" width="100%" color="black" noshade>				
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" >
                                    <!--<table  width="100%" border=1 cellpadding=0 cellspacing=0 class="fuente8">-->
                                    <table  width="100%" border=1 cellpadding=0 cellspacing=0 class="adminform">
                                        <tr>
                                            <td width="58%" >
                                                <div id="cpanel">
                                                    <div style="float:left;">
                                                        <div class="icon">
                                                            <a href="#">
                                                                <img src="../images/cpanel.png"  onClick="validar_cabecera_producto()" border="0" onMouseOver="style.cursor=cursor">
                                                                <span>Salir</span>
                                                            </a>
                                                        </div>
                                                    </div>								
                                                </div>
                                            </td>
                                            <td width="22%">			  	
                                                <div id="frmBusqueda">
                                                    <table width="100%" border=0 align="left" cellpadding=3 cellspacing=0 class="adminform">
                                                        <tr>
                                                            <td colspan="2" align="center" width="5%" height="12"  class="botones"  ><font size="3"><strong>TOTAL FACTURA</strong></font></td>
                                                        </tr>
                                                        <tr>

                                                            <td width="42%" ><font size="2"><strong>SUB-TOTAL:</strong></font></td>
                                                            <td width="58%" align="right">
                                                                <div align="left">
                                                                <input  style=" height:20px; font-size:18px;  text-align:right"  name="subtotal" type="text" id="subtotal" value="<?php echo $subtotal ?>" size="10" value=0.00 align="right" readonly> 
                                                                <strong>Bs.</strong>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="busqueda"><font size="2"><strong>IVA: (<?php echo $var_iva ?>&#37;)</strong></font></td>
                                                            <td align="right">
                                                                <div align="left">
                                                                <input style=" height:20px; font-size:18px;  text-align:right" name="totalimpuestos" type="text" id="totalimpuestos" size="10" align="right" value=0.00 readonly> 
                                                                <strong>Bs.</strong>  
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="busqueda"><font size="2"><strong>TOTAL FACTURA:</strong></font></td>
                                                            <td align="right">
                                                                <div align="left">
                                                                <input style=" height:20px; font-size:18px;  background-color: #BBBBBB;  text-align:right" name="preciototal" type="text" id="preciototal" size="10" align="right" value=0.00 readonly> 
                                                                <strong>Bs.</strong>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>													
                                <td colspan="2" class="botones" align="center" >
                                    &nbsp;
                                </td>													
                            </tr> 

                        </table>
                    </form>
                    <form method="POST" action="productos_add.php" id="QForm" name="QForm" enctype="multipart/form-data">
                        <input id="monto_total" name="monto_total"  type="hidden"/>
                        <input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp ?>" type="hidden"/>
                    </form>
                </td>	 				 			  
            </tr>
        </tbody>
    </table>
  		  	
</body>
    
    
</html>
    
<?php
echo "<script type=\"text/javascript\">
        validarCargarProducto();
      </script>";
?>   
    
        