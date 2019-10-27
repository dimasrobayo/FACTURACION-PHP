<?php
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo ('<div align="center"><img  src="../../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        exit;
    }
    //Conexion a la base de datos
    require("../../conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

	
    
    if (isset($_GET["codfacturatmp"])) { 
        $codfacturatmp=$_GET["codfacturatmp"];
        $total_factura=$_GET["totalfactura"];
        $status_modif_fp=$_GET["status_modif_fp"];
        
        $query="SELECT  SUM(monto_concepto) AS total_aporte FROM detalle_facturatmp WHERE n_factura='$codfacturatmp'";				
        $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());	
        $resultados=pg_fetch_array($result);
        pg_free_result($result);
        
        $query="SELECT  * FROM facturatmp WHERE n_factura='$codfacturatmp'";	
        $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());	
        $resultados_aporte=pg_fetch_array($result);
        pg_free_result($result);
    }
	
    if (isset($_POST[save])){
        $codfacturatmp=$_POST["codfacturatmp"];
        $status_fp_efectivo=0;
        $status_fp_deposito=0;
        $status_fp_cheque=0;
        $status_modif_fp=1;

        $seleccionados = $_POST["status_fp"];
        for($i=0; $i < count($seleccionados); $i++){
            if ($seleccionados[$i]=="efectivo") $status_fp_efectivo=1;
            if ($seleccionados[$i]=="deposito") $status_fp_deposito=1;
            if ($seleccionados[$i]=="cheque") $status_fp_cheque=1;
        }

        if($_POST["monto_efectivo"]=="") $monto_efectivo="0.00"; else $monto_efectivo=number_format($_POST["monto_efectivo"],2,".","");;
        $nro_deposito=$_POST["nro_deposito"];
        $cod_banco_deposito=$_POST["cod_banco"];
        $cod_cuenta_banco=$_POST["cod_cuenta_banco"];
        $fecha_deposito=implode('-',array_reverse(explode('/',$_POST["fecha_deposito"])));
        if($_POST["monto_deposito"]=="") $monto_deposito="0.00"; else $monto_deposito=number_format($_POST["monto_deposito"],2,".","");;
        $nro_cheque=$_POST["nro_cheque"];
        $cod_banco_cheque=$_POST["cod_banco_cheque"];
        if($_POST["monto_cheque"]=="") $monto_cheque="0.00"; else $monto_cheque=number_format($_POST["monto_cheque"],2,".","");
        
        number_format($cantidad*$monto_concepto,2,".","");
        $total_pago=$_POST["total_pago"];
        $total_cambio=$_POST["total_cambio"];
        
//        $query="UPDATE facturatmp SET status_fp_efectivo='$status_fp_efectivo', ".
//            " monto_efectivo='$monto_efectivo',status_fp_deposito='$status_fp_deposito',cod_banco_deposito='$cod_banco_deposito',cod_cuenta_banco='$cod_cuenta_banco',nro_deposito='$nro_deposito',fecha_deposito='$fecha_deposito',monto_deposito='$monto_deposito', ".
//            " status_fp_cheque='$status_fp_cheque',cod_banco_cheque='$cod_banco_cheque',nro_cheque='$nro_cheque',monto_cheque='$monto_cheque' ".
//            " WHERE n_factura='$codfacturatmp'";								
        $query="UPDATE facturatmp SET status_fp_efectivo='$status_fp_efectivo', ".
            " monto_efectivo='$monto_efectivo',status_fp_deposito='$status_fp_deposito',cod_banco_deposito='$cod_banco_deposito',cod_cuenta_banco='$cod_cuenta_banco',nro_deposito='$nro_deposito',fecha_deposito='$fecha_deposito',monto_deposito='$monto_deposito', ".
            " status_fp_cheque='$status_fp_cheque',cod_banco_cheque='$cod_banco_cheque',nro_cheque='$nro_cheque',monto_cheque='$monto_cheque' ".
            " WHERE n_factura='$codfacturatmp'";								
        $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());      
            
        echo "<script type=\"text/javascript\">
                opener.document.formulario_lineas.total_pago.value='$total_pago';
                opener.document.formulario_lineas.total_cambio.value='$total_cambio';
                opener.document.formulario_lineas.total_efectivo.value='$monto_efectivo';
                opener.document.formulario_lineas.total_deposito.value='$monto_deposito';
                opener.document.formulario_lineas.total_cheque.value='$monto_cheque';
                opener.document.facturacion.status_modif_fp.value='$status_modif_fp';
                close();
              </script>";
            
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

        <link rel="stylesheet" href="../../css/template_portada.css" type="text/css" />
        
        <link rel="stylesheet" type="text/css" href="../../css/styles_general.css" media="screen" />
        <link rel="stylesheet" href="../../css/template.css" type="text/css" />

    <!-- script del jquery, ajax y funciones javascript-->
    <script type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>   
   <script language="javascript" src="../../js/ajax.js"></script>
    <script type="text/javascript" src="../../js/lib_javascript.js"></script>
    <script type="text/javascript" language="JavaScript1.2" src="../../js/funciones.js"></script>
    <!-- <script type="text/javascript" language="JavaScript1.2" src="../js/disabled_keys.js"></script> -->

    <!-- styles y script del calendario Fecha -->	
    <link type="text/css" rel="stylesheet" href="../../js/calendario_cat/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
    <script type="text/javascript" src="../../js/calendario_cat/dhtmlgoodies_calendar_cat.js?random=20060118"></script>

    <!-- styles y script Validaciones -->
    
    <link rel="stylesheet" href="../../css/validationEngine.jquery.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/LiveValidation.css" type="text/css" media="screen" />	
    <script src="../../js/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
    <script src="../../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>	
    <script type="text/javascript" SRC="../../js/livevalidation_standalone.js"></script>    	

    <!-- script de efectos -->	
        <script src="../../js/prototype.js" type="text/javascript"></script>
        <script src="../../js/scriptaculous.js" type="text/javascript"></script>
        <script src="../../js/unittest.js" type="text/javascript"></script> 
        
        <!-- Token Input -->
      <script type="text/javascript" src="../../js/tokeninput/src/jquery.tokeninput.min.js"></script>
      <link rel="stylesheet" href="../../js/tokeninput/styles/token-input.css" type="text/css" />
      <link rel="stylesheet" href="../../js/tokeninput/styles/token-input-facebook.css" type="text/css" />
        
        <!-- funciones javascript  -->
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
<body style="background-color: #f9f9f9;" onload="<?php echo 'activar_forma_pago_efectivo();activar_forma_pago_deposito();activar_forma_pago_cheque();actualizar_total_pago();';?>">
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
                    <form method="POST" action="forma_pago.php" id="QForm" name="QForm" enctype="multipart/form-data">
                     <input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp?>" type="hidden">
                    <table class="adminform_cat" width="100%"  align="center">
                        <tbody>
                            <tr>
                                <th align="center">
                                        FORMA DE PAGO
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <?php if($div_menssage) { ?>
                                            <tr>
                                                <td  id="msg" align="center">		
                                                    <?php echo $div_menssage;?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td>
                                                    <font size="3"><strong>TOTAL A PAGAR:</strong></font>
                                                    <input  style="width:100px; height:20px; font-size:18px;   text-align:right" placeholder="0.00" type="text" id="monto_total"  name="monto_total"  maxlength="10" size="8" value="<?php echo $total_factura;?>" readonly/>	 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>	
                                </td>
                            </tr>
                            <tr  >
                                <td >
                                    
                                    <table class="adminform" border="0" cellpadding="0" cellspacing="1" width="100%">
                                        <tbody>												
                                            <tr>
                                                <td>
                                                    <strong>Forma de Pago:</strong> 
                                                    <?php 
                                                        if ($status_modif_fp==0){
                                                            echo '<input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="status_fp[]" id="status_fp_efectivo" value="efectivo"  onchange="activar_forma_pago_efectivo();" /> Efectivo';
                                                            echo '<input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="status_fp[]" id="status_fp_deposito" value="deposito" onchange="activar_forma_pago_deposito();" checked="true" /> Depósito';
                                                            echo '<input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="status_fp[]" id="status_fp_cheque" value="cheque" onchange="activar_forma_pago_cheque();" /> Cheque';
                                                        }else {
                                                            if($resultados_aporte[status_fp_efectivo]==1){																					
                                                                    echo '<input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="status_fp[]" id="status_fp_efectivo" value="efectivo" checked="true" onchange="activar_forma_pago_efectivo();" /> Efectivo';
                                                            }else {
                                                                    echo '<input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="status_fp[]" id="status_fp_efectivo" value="efectivo" onchange="activar_forma_pago_efectivo();" /> Efectivo';
                                                            }

                                                            if($resultados_aporte[status_fp_deposito]==1){																					
                                                                    echo '<input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="status_fp[]" id="status_fp_deposito" value="deposito" checked="true" onchange="activar_forma_pago_deposito();" /> Depósito';
                                                            }else {
                                                                    echo '<input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="status_fp[]" id="status_fp_deposito" value="deposito" onchange="activar_forma_pago_deposito();" /> Depósito';
                                                            }

                                                            if($resultados_aporte[status_fp_cheque]==1){																					
                                                                    echo '<input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="status_fp[]" id="status_fp_cheque" value="cheque" checked="true" onchange="activar_forma_pago_cheque();" /> Cheque';
                                                            }else {
                                                                    echo '<input class="validate[minCheckbox[1]] checkbox" type="checkbox" name="status_fp[]" id="status_fp_cheque" value="cheque" onchange="activar_forma_pago_cheque();" /> Cheque';
                                                            }
                                                        }

                                                        ?>
                                               </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>

                            </tr>
                            <tr  id="fp_pago_efectivo">
                                <td  >
                                    <table class="adminform" border="0" cellpadding="0" cellspacing="1" width="100%">
                                        <tbody>												
                                            <tr>
                                               <td class="titulo" colspan="2" height="18"  align="left"><b>Efectivo</b></td>
                                           </tr>
                                            <tr >
                                                <td>
                                                    <table width="100%" border="0">
                                                        <tbody>
                                                          <tr >
                                                            <td width="35%">
                                                                Monto en Efectivo:
                                                            </td>
                                                            <td >
                                                                <input  style="text-align:right" placeholder="0.00" autofocus="true"  type="text" id="monto_efectivo" class="validate[required,custom[number]] text-input"  name="monto_efectivo" onKeyPress="return(ue_formatonumero(this,'','.',event));" value="<?php if ($status_modif_fp==1)  echo $resultados_aporte[monto_efectivo];?>" maxlength="10" size="8" onblur="actualizar_total_pago();"  title="Ingrese el monto en Efectivo incluyendo los decimales. ej: 1300.00"/>
                                                            </td>
                                                          </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                              </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr id="fp_pago_deposito">
                                <td  >
                                    <table class="adminform" border="0" cellpadding="0" cellspacing="1" width="100%">
                                        <tbody>												
                                            <tr>
                                               <td class="titulo" colspan="2" height="18"  align="left"><b>Deposito/Transferencia</b></td>
                                           </tr>
                                            <tr >
                                                <td>
                                                    <table width="100%" border="0">
                                                        <tbody>
                                                          <tr >
                                                            <td width="35%">
                                                                Nro. Deposito: 
                                                            </td>
                                                            <td >
                                                                <input id="nro_deposito" placeholder="Nro. Despósito " class="validate[required,custom[integer],minSize[4]] text-input"  name="nro_deposito" value="<?php if ($status_modif_fp==1)  echo $resultados_aporte[nro_deposito];?>" maxlength="20" size="12" type="text">
                                                            </td>
                                                          </tr>
                                                          <tr >
                                                            <td >
                                                                Fecha Deposito: 
                                                            </td>
                                                            <td >
                                                                <input name="fecha_deposito" type="text" class="validate[required,custom[date],past[NOW]]" value="<?php if ($status_modif_fp==1) echo implode('/',array_reverse(explode('-',$resultados_aporte[fecha_deposito])));?>"  id="fecha_deposito"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"/>
                                                                <img src="../../images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_deposito,'dd/mm/yyyy',this)">												              								
                                                            </td>
                                                          </tr>
                                                          <tr >
                                                            <td >
                                                                Monto Deposito: 
                                                            </td>
                                                            <td >
                                                                <input  style="text-align:right" placeholder="0.00" type="text" id="monto_deposito" class="validate[required,custom[number]] text-input"  name="monto_deposito" onKeyPress="return(ue_formatonumero(this,'','.',event));" value="<?php if ($status_modif_fp==1)  echo $resultados_aporte[monto_deposito];  else echo $total_factura;?>" maxlength="10" size="8" onblur="actualizar_total_pago();"  title="Ingrese el monto del Deposito incluyendo los decimales. ej: 1300.00"/>
                                                            </td>
                                                          </tr>
                                                          <tr >
                                                            <td >
                                                                Banco Deposito: 
                                                            </td>
                                                            <td >
                                                                <select name="cod_banco" id="cod_banco" class="validate[required]"  onclick="cargarContenidoCuentasBancoDep();" onchange="cargarContenidoCuentasBancoDep();">
                                                                    <option selected="selected" value="">Seleccione...</option>
                                                                    <?php 						
                                                                            $consulta_sql=pg_query("SELECT * FROM banco WHERE codigo_banco IN (SELECT codigo_banco FROM cuenta) order by nombre_banco");
                                                                            while ($array_consulta=pg_fetch_array($consulta_sql)){
                                                                                if ($status_modif_fp==0){
                                                                                    echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                                                }else{
                                                                                    if ($array_consulta[0]==$resultados_aporte['cod_banco_deposito']){																			
                                                                                        echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                                                    }else {
                                                                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                                                    }
                                                                                }
                                                                            }	
                                                                            pg_free_result($consulta_sql);
                                                                    ?>
                                                                </select>
                                                            </td>
                                                          </tr>
                                                          <tr >
                                                            <td >
                                                                Cuenta Banco Deposito: 
                                                            </td>
                                                            <td >
                                                                <div id="contenedor_cuenta_banco">
                                                                    <select name="cod_cuenta_banco" id="cod_cuenta_banco" class="validate[required]" >
                                                                            <option  selected="selected" value="">Seleccione...</option>
                                                                            <?php
                                                                                if ($status_modif_fp==1){
                                                                                    $consultax1="SELECT * from cuenta where codigo_banco='$resultados_aporte[cod_banco_deposito]'  order by codigo_banco";
                                                                                    $ejec_consultax1=pg_query($consultax1);
                                                                                    while($vector=pg_fetch_array($ejec_consultax1)){
                                                                                        if ($vector[0]==$resultados_aporte[cod_cuenta_banco]){
                                                                                            echo '<option selected="selected" value="'.$vector[0].'">'.$vector[0].' - '.$vector[4].' </option>';
                                                                                        }else {
                                                                                            echo '<option value="'.$vector[0].'">'.$vector[0].' - '.$vector[4].' </option>';
                                                                                        }					  		
                                                                                    }
                                                                                    pg_free_result($ejec_consultax1);
                                                                                }
                                                                            ?>
                                                                    </select> 														
                                                                </div>
                                                            </td>
                                                          </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                              </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr id="fp_pago_cheque">
                                <td  >
                                    <table class="adminform" border="0" cellpadding="0" cellspacing="1" width="100%">
                                        <tbody>												
                                            <tr>
                                               <td class="titulo" colspan="2" height="18"  align="left"><b>Cheque</b></td>
                                           </tr>
                                            <tr >
                                                <td>
                                                    <table width="100%" border="0">
                                                        <tbody>
                                                          <tr >
                                                            <td width="35%">
                                                                Nro. del Cheque:
                                                            </td>
                                                            <td >
                                                                <input id="nro_cheque" placeholder="Nro. Cheque " class="validate[required,custom[integer],minSize[4]] text-input"  name="nro_cheque" value="<?php if ($status_modif_fp==1)  echo $resultados_aporte[nro_cheque];?>" maxlength="20" size="12" type="text">
                                                            </td>
                                                          </tr>
                                                            <td>
                                                                Monto del Cheque:
                                                            </td>
                                                            <td >
                                                                <input  style="text-align:right" placeholder="0.00" type="text" id="monto_cheque" class="validate[required,custom[number]] text-input"  name="monto_cheque" onKeyPress="return(ue_formatonumero(this,'','.',event));" value="<?php if ($status_modif_fp==1)  echo $resultados_aporte[monto_cheque];?>" maxlength="10" size="8" onblur="actualizar_total_pago();" title="Ingrese el monto del Cheque incluyendo los decimales. ej: 1300.00"/>
                                                            </td>
                                                          </tr>
                                                          <tr >
                                                            <td>
                                                                Banco:
                                                            </td>
                                                            <td >
                                                                <select name="cod_banco_cheque" id="cod_banco_cheque" class="validate[required]">
                                                                    <option selected="selected" value="">Seleccione...</option>
                                                                    <?php 						
                                                                            $consulta_sql=pg_query("SELECT * FROM banco  order by nombre_banco");
                                                                            while ($array_consulta=pg_fetch_array($consulta_sql)){
                                                                                if ($status_modif_fp==0){
                                                                                    echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                                                }else{
                                                                                    if ($array_consulta[0]==$resultados_aporte['cod_banco_cheque']){																			
                                                                                        echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                                                    }else {
                                                                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                                                    }
                                                                                }
                                                                            }
                                                                            pg_free_result($consulta_sql);
                                                                    ?>
                                                                </select>
                                                            </td>
                                                          </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                              </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <table class="adminform" border="0" cellpadding="0" cellspacing="1" width="100%">
                                        <tbody>												
                                            <tr>
                                                <td>
                                                    <font size="3"><strong>TOTAL PAGO:</strong></font>
                                                </td>
                                                <td>
                                                     
                                                    <input  style="width:100px; height:20px; font-size:18px; color:green;  text-align:right" placeholder="0.00" type="text" id="total_pago"  name="total_pago"  maxlength="10" size="8" value="" readonly/>	
                                               </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <font size="3"><strong>CAMBIO:</strong></font> 
                                                </td>
                                                 <td>
                                                    <input  style="width:100px; height:20px; font-size:18px; color:red;  text-align:right" placeholder="0.00" type="text" id="total_cambio"  name="total_cambio"  maxlength="10" size="8" value="" readonly/>	
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td  class="botones" align="center" >
                                    <input id="submit"  class="button" type="submit" name="save" value="Enviar" />									
                                    <input class="button"  type="button" onclick="javascript:parent.close();" value="Cerrar" name="cerrar" /> 
                                </td>		
                            </tr>	
                        </tbody>
                    </table>
                    </form>    
                </td>	 				 			  
            </tr>
        </tbody>
    </table>
  		  	
  	<!-- <br />
	<a href="javascript:parent.jQuery.fancybox.close();">
		<img border="0" name="Salida" src="../../images/salida.png">	
	</a> -->	
</body>
    
    
</html>
    
    
        