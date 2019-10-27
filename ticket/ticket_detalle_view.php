<?php
    if ($_SERVER['HTTP_REFERER'] == "")	{
            echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
            exit;
    }

    require("../conexion/aut_config.inc.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_GET['cod_detalle_ticket'])){
    	$cod_detalle_ticket= $_GET['cod_detalle_ticket'];

	$query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                " WHERE detalles_ticket.cod_detalle_ticket='$cod_detalle_ticket' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND detalles_ticket.cod_unidad=unidades.cod_unidad";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_detalle_ticket=pg_fetch_array($result);	
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<html>
    <head>
        <title>detalles del Ticket</title>
        <meta charset="UTF-8">
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
        <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8"/>
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Language" content="es-VE">
        <meta http-equiv="Content-Script-Type" content="text/javascript">
        <link rel="shortcut icon" href="../images/favicon.ico" />

        <link rel="stylesheet" type="text/css" href="../css/styles_general.css" media="screen" />
        <link rel="stylesheet" href="../css/template.css" type="text/css" />		

        <link href="../css/css_catalogos/ventanas.css" rel="stylesheet" type="text/css">
        <link href="../css/css_catalogos/general.css" rel="stylesheet" type="text/css">
        <link href="../css/css_catalogos/tablas.css" rel="stylesheet" type="text/css">
    </head>
<body style="background-color: #f9f9f9;">	
    <table class="container_contenido_cat" border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>  			
            <tr>
                <td>
                    <table class="adminform_cat" width="100%"  align="center">
                        <tbody>
                            <tr>
                                <th align="center">
                                        DETALLES DE LA ACTUACIÓN SOBRE EL TICKET
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>                

                                            <tr>
                                                <td width="25%" align="right">
                                                    <b>NRO. TICKET:</b>
                                                </td>		
                                                <td >
                                                    <table border="0" cellpadding="0" cellspacing="1" width="100%">
                                                        <tbody>

                                                            <tr>
                                                                <td width="35%" height="22">
                                                                       <input type="text" id="cod_tac" name="cod_tac"  readonly="readonly" value="<?php echo str_pad($resultados_detalle_ticket['cod_ticket'],10,"0",STR_PAD_LEFT);?>"  size="10"/>
                                                                </td>
                                                                <td width="25%" align="right">
                                                                    <b>Fecha Emisión:</b>
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="fecha_registro" name="fecha_registro" value="<?php echo date_format(date_create($resultados_detalle_ticket['fecha_registro']), 'd/m/Y');?>"  size="10" />
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                    
                                                </td>                       
                                            </tr>
                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Unidad Encargada:</b>
                                                </td>
                                                <td>
                                                    <input  readonly="readonly"  type="text" id="nombre_unidad_ult" name="nombre_unidad_ult" value="<?php echo $resultados_detalle_ticket[nombre_unidad];?>"  size="50" maxlength="50"/>
                                                </td>			
                                            </tr>

                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Estado del Ticket:</b>
                                                </td>
                                                <td>
                                                    <input  readonly="readonly"  type="text" id="estado_ticket" name="estado_ticket" value="<?php echo $resultados_detalle_ticket[descripcion_estado_tramite];?>"  size="50" maxlength="50"/>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Fecha Cita:</b>
                                                </td>
                                                <td>
                                                    <table border="0" cellpadding="0" cellspacing="1" width="100%">
                                                        <tbody>

                                                            <tr>
                                                                <td width="35%" height="22">
                                                                    <input type="text" readonly="true" id="fecha_cita_programada" name="fecha_cita_programada" value="<?php if ($resultados_detalle_ticket['fecha_cita_programada']==NULL) echo ''; else echo date_format(date_create($resultados_detalle_ticket['fecha_cita_programada']), 'd/m/Y');?>"  size="8" />
                                                                </td>
                                                                <td width="25%" align="right">
                                                                    <b>Hora Cita:</b>
                                                                </td>
                                                                <td>
                                                                    <input type="text" readonly="true" id="hora_cita_programada" name="hora_cita_programada" value="<?php if ($resultados_detalle_ticket['hora_cita_programada']==NULL) echo ''; else echo date_format(date_create($resultados_detalle_ticket['hora_cita_programada']), 'g:i A.');?>"  size="8" />
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </td>			
                                            </tr>

                                            <tr>
                                                			
                                            </tr>
                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Fecha Atención:</b>
                                                </td>
                                                <td>
                                                    <table border="0" cellpadding="0" cellspacing="1" width="100%">
                                                        <tbody>

                                                            <tr>
                                                                <td width="35%" height="22">
                                                                    <input type="text" readonly="true" id="fecha_atencion" name="fecha_atencion" value="<?php if ($resultados_detalle_ticket['fecha_atencion']==NULL) echo ''; else echo date_format(date_create($resultados_detalle_ticket['fecha_atencion']), 'd/m/Y');?>"  size="8" />
                                                                </td>
                                                                <td width="25%" align="right">
                                                                    <b>Hora Atención:</b>
                                                                </td>
                                                                <td>
                                                                    <input type="text" readonly="true" id="hora_atencion" name="hora_atencion" value="<?php if ($resultados_detalle_ticket['hora_atencion']==NULL) echo ''; else echo date_format(date_create($resultados_detalle_ticket['hora_atencion']), 'g:i A.');?>"  size="8" />
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                    
                                                </td>			
                                            </tr>

                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Situación Actual:</b>
                                                </td>
                                                <td>
                                                    <textarea  readonly="true" name="situacion_actual" id="situacion_actual" cols="50" rows="3" ><?php echo $resultados_detalle_ticket[situacion_actual];?></textarea>																	
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Actuación:</b>
                                                </td>
                                                <td>
                                                    <textarea  readonly="true" name="actuacion" id="actuacion" cols="50" rows="3" ><?php echo $resultados_detalle_ticket[actuacion];?></textarea>																	
                                                </td>
                                            </tr>


                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Monto Autorizado:</b>
                                                </td>
                                                <td>														         
                                                    <input  style="text-align:right" readonly="true" type="text" id="monto_autorizado"  name="monto_autorizado" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="<?php echo $resultados_detalle_ticket[monto_autorizado];?>" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                                                 </td>
                                           </tr>
                                           <tr>
                                                <td width="15%" align="right">
                                                    <b>Observaciones:</b>
                                                </td>
                                                <td>
                                                    <textarea  readonly="true" name="observaciones" id="observaciones" cols="50" rows="2" ><?php echo $resultados_detalle_ticket[observaciones];?></textarea>																	
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>	
                                </td>
                            </tr>
                            <tr>
                                <td  class="botones" align="center" >									 
                                    <input class="button"  type="button" onclick="javascript:parent.jQuery.fancybox.close();" value="Cerrar" name="cerrar" /> 
                                </td>		
                            </tr>	
                        </tbody>
                    </table> 
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