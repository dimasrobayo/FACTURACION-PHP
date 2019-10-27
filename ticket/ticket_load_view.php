<?php
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }	
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
	
    //verifica la recepcion de los datos para buscar y mostrar
    if (isset($_POST['cod_ticket'])){
        $cod_ticket=intval($_POST['cod_ticket']);
    }
    if (isset($_GET['cod_ticket'])){
        $cod_ticket=intval($_GET['cod_ticket']);
    } 
    
    if ($cod_ticket){  // consulta de los datos para Mostrar
        
        if($_SESSION['nivel']==1){
            $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades,categorias". 
                " WHERE ticket.cod_ticket='$cod_ticket'  AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND ticket.cedula_rif=solicitantes.cedula_rif AND ticket.cod_tramite=tramites.cod_tramite ".
                " AND tramites.cod_categoria=categorias.cod_categoria AND tramites.cod_unidad=unidades.cod_unidad";
            
            
            $result = pg_query($query)or die(pg_last_error());
            $total_result_ticket= pg_num_rows($result);
            $resultados_ticket=pg_fetch_array($result);	
            pg_free_result($result);

            if ($resultados_ticket[0]){
                if($resultados_ticket[cod_unidad]==$_SESSION[cod_unidad]){
                    $total_result_ticket_unidad_inicial=1;
                    if($resultados_ticket[cod_subticket]!=""){

                            $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                                " WHERE detalles_ticket.cod_detalle_ticket='$resultados_ticket[cod_subticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                                " AND detalles_ticket.cod_unidad=unidades.cod_unidad";
                            $result = pg_query($query)or die(pg_last_error());
                            $resultados_subticket=pg_fetch_array($result);	
                            pg_free_result($result);


                            $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                                    " WHERE detalles_ticket.cod_ticket='$cod_ticket' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                                    " AND detalles_ticket.cod_unidad=unidades.cod_unidad ORDER BY cod_detalle_ticket";
                            $result_detalle_ticket = pg_query($query)or die(pg_last_error());
                            $total_result_detalle_ticket= pg_num_rows($result_detalle_ticket);
                    }else{
                        $total_result_detalle_ticket=0;
                        $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; Fue Registrado en Linea! En espera por Asignación</font></h3></div>';

                    }
                
                }else{
                    $total_result_ticket_unidad_inicial=0;
                    if($resultados_ticket[cod_subticket]!=""){

                            $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                                " WHERE detalles_ticket.cod_detalle_ticket='$resultados_ticket[cod_subticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                                " AND detalles_ticket.cod_unidad=unidades.cod_unidad";
                            $result = pg_query($query)or die(pg_last_error());
                            $resultados_subticket=pg_fetch_array($result);	
                            pg_free_result($result);
                            
                            if($resultados_subticket[cod_unidad]==$_SESSION[cod_unidad]){
                                $total_result_ticket_unidad_encargada=1;
                            }else{
                                $total_result_ticket_unidad_encargada=0;
                            }
                            $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                                    " WHERE detalles_ticket.cod_ticket='$resultados_ticket[cod_ticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                                    " AND detalles_ticket.cod_unidad=unidades.cod_unidad ORDER BY cod_detalle_ticket";
                            $result_detalle_ticket = pg_query($query)or die(pg_last_error());
                            $total_result_detalle_ticket= pg_num_rows($result_detalle_ticket);
                    }else{
                        $total_result_detalle_ticket=0;
                        $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; Fue Registrado en Linea! En espera por Asignación</font></h3></div>';

                    }
                    
                }
            }

            if (isset($_POST[submit])){
                if($total_result_ticket==0){
                    $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; No Exite Registrado! </font></h3></div>';		
                }else{
                    if($total_result_ticket_unidad_inicial==0 AND $total_result_ticket_unidad_encargada==0 ){
                        $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; No Exite Asignado a la Unidad! </font></h3></div>';		
                    }
                }
            }
            
            
            
        }else{
            $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades,categorias". 
                " WHERE ticket.cod_ticket='$cod_ticket'  AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND ticket.cedula_rif=solicitantes.cedula_rif AND ticket.cod_tramite=tramites.cod_tramite ".
                " AND tramites.cod_categoria=categorias.cod_categoria AND tramites.cod_unidad=unidades.cod_unidad";
            
            $result = pg_query($query)or die(pg_last_error());
            $total_result_ticket= pg_num_rows($result);
            $resultados_ticket=pg_fetch_array($result);	
            pg_free_result($result);

            if ($resultados_ticket[0]){
		$total_result_ticket_unidad_inicial=1;
		$total_result_ticket_unidad_encargada=1;
                if($resultados_ticket[cod_subticket]!=""){
                    $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                        " WHERE detalles_ticket.cod_detalle_ticket='$resultados_ticket[cod_subticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                        " AND detalles_ticket.cod_unidad=unidades.cod_unidad";
                    $result = pg_query($query)or die(pg_last_error());
                    $resultados_subticket=pg_fetch_array($result);	
                    pg_free_result($result);


                    $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                            " WHERE detalles_ticket.cod_ticket='$cod_ticket' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                            " AND detalles_ticket.cod_unidad=unidades.cod_unidad ORDER BY cod_detalle_ticket";
                    $result_detalle_ticket = pg_query($query)or die(pg_last_error());
                    $total_result_detalle_ticket= pg_num_rows($result_detalle_ticket);
                    
                }else{
                    $total_result_detalle_ticket=0;
                    $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; Fue Registrado en Linea! En espera por Asignación</font></h3></div>';
                    
                }
                
            }

            if (isset($_POST[submit]) AND ($total_result_ticket==0)){
                $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; No Exite Registrado!</font></h3></div>';		
            }
            
            
        }
        
        
        


    }			
?>
<!-- Ventanas emergentes -->
<script type="text/javascript" charset="utf-8">			      
    jQuery(document).ready(function(){
        /* normal effects*/ 
        jQuery('.fancybox-normal').fancybox();

        /* Con effects*/ 		
        jQuery(".fancybox").fancybox({
            maxWidth	: 550,
            maxHeight	: 400,
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
<!-- sincronizar mensaje cuando de muestra al usuario -->
<?php if($div_menssage) { ?>					
	<script type="text/javascript">
		function ver_msg(){
		 	Effect.Fade('msg');
		}  
		setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
	</script>
 <?php } ?>		    					
 <!--aqui es donde esta el diseño del formulario-->
<table border="0" width="100%" align="center">
    <tbody>			
        <tr>
            <td id="msg" align="center">	
                <?php echo $div_menssage;?>						
            </td>
        </tr>
    </tbody>
</table>
<table class="adminticketsgestion" width="100%">
    <tr>
        <th>
            GESTIÓN DEL TICKET
        </th>
    </tr>
</table>

	<!-- Formulario de la Busqueda -->
<form method="POST" action="?view=gestion_tickets" id="QForm" name="QForm" enctype="multipart/form-data">																				
    <table class="adminform"  width="350px" align="center">
        <tr>
            <th colspan="2" align="center">
                    IDENTIFICACI&Oacute;N DEL TICKET
            </th>
        </tr>	   					
        <tr>
            <td colspan="2" align="center"> 
                <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                    <tbody>
                        
                        <tr>
                            <td width="35%" height="22">
                                    NRO. TICKET : &nbsp;
                            </td>
                            <td  height="22">
                                <input id="cod_ticket" autofocus="true" name="cod_ticket"  class="validate[required,custom[number]] text-input" type="text"  value="<?php echo $cod_ticket;?>"  size="10" maxlength="10"/>
                                <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Nro. TAC','Ingrese el Numero del Ticket.   Ej.: 100', ' (Campo Requerido)')">														
                            </td>
                        </tr>
                        
                    </tbody>
                </table> 
            </td>
        </tr>													
        <tr>
            <td colspan="2" class="botones" align="center" >											
                <input class="button" type="submit" name="submit" value="CONTINUAR" />	
            </td>			
        </tr>										   
    </table> 												
    <br>
</form>
<?php if ($total_result_ticket!=0 AND $total_result_ticket_unidad_inicial!=0 OR $total_result_ticket_unidad_encargada!=0 ){ ?>
    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">		
        <table class="adminform"  width="800px" align="center">
            <tr>
                <th colspan="2" align="center">
                    <img src="images/gestion_ticket16.png" width="16" height="16" alt="Nuevo Registro de Ticket">
                    INFORMACI&Oacute;N DEL TICKET
                </th>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                            <tr>
                                <td  width="20%" height="22">
                                    NRO. TICKET: 
                                </td>
                                <td>
                                    <table  border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" id="cod_tac" name="cod_tac"  readonly="readonly" value="<?php echo str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT);?>"  size="10"/>
                                                </td>
                                                <td>
                                                    FECHA EMISIÓN: 
                                                    <input type="text" id="fecha_registro" name="fecha_registro" value="<?php echo date_format(date_create($resultados_ticket['fecha_registro_ticket']), 'd/m/Y');?>"  size="10" />
                                                </td>
                                                <td>
                                                    PRIORIDAD:
                                                    <?php
                                                        if($resultados_ticket['prioridad_ticket']==1){
                                                            echo '<input id="prioridad"  class="validate[required] radio" name="prioridad" value="1" disabled="true" checked="true" type="radio"> NORMAL';
                                                                echo '<input id="prioridad" class="validate[required] radio" name="prioridad" value="2" disabled="true"  type="radio"> <font color="ffd200">ALTA</font>';
                                                                echo '<input id="prioridad" class="validate[required] radio" name="prioridad" value="3" disabled="true" type="radio"> <font color="Red">URGENTE</font>';
                                                        }elseif($resultados_ticket['prioridad_ticket']==2){
                                                            echo '<input id="prioridad" class="validate[required] radio" name="prioridad" value="1" disabled="true" type="radio"> NORMAL
                                                                <input id="prioridad" class="validate[required] radio" name="prioridad" value="2"  disabled="true" checked="true" type="radio"> <font color="ffd200">ALTA</font>
                                                                <input id="prioridad" class="validate[required] radio" name="prioridad" value="3" disabled="true" type="radio"> <font color="Red">URGENTE</font>';
                                                        }else{
                                                            echo '<input id="prioridad" class="validate[required] radio" name="prioridad" value="1" disabled="true" type="radio"> NORMAL
                                                                <input id="prioridad" class="validate[required] radio" name="prioridad" value="2" disabled="true"  type="radio"> <font color="ffd200">ALTA</font>
                                                                <input id="prioridad" class="validate[required] radio" name="prioridad" value="3" disabled="true" checked="true" type="radio"> <font color="Red">URGENTE</font>';
                                                        }
                                                    ?>
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
                <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Solicitante:</b></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                            <tr>
                                <td  width="20%" height="22">
                                    C&Eacute;DULA / RIF: 
                                </td>
                                <td>
                                    <table  border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input size="10"  readonly="readonly" type="text" name="cedula1"  value="<?php echo substr_replace($resultados_ticket['cedula_rif'],'-',1,0);?>" /> 																																						
                                                </td>
                                                <td>
                                                    NOMBRE DEL SOLICITANTE:
                                                    <input  readonly="readonly"  type="text" id="nombreapellido" name="nombreapellido" value="<?php echo $resultados_ticket[nombre_solicitante];?>"  size="50" maxlength="50"/>																	
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
                <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Ticket de Atención al Soberano:</b></td>
            </tr>

            <tr>
                <td colspan="2">
                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                            <tr>
                                <td width="20%">
                                    DESCRIPCION DEL TRAMITE:
                                </td>
                                <td>
                                    <textarea  readonly="true" name="descripcion_ticket" id="descripcion_ticket" cols="84" rows="2" ><?php echo $resultados_ticket[descripcion_ticket];?></textarea>																	
                                </td>
                            </tr>
                            <tr>
                                <td>UNIDAD INICIAL ASIGNADA:</td>
                                <td>
                                    <input  readonly="readonly"  type="text" id="nombre_unidad" name="nombre_unidad" value="<?php echo $resultados_ticket[nombre_unidad];?>"  size="50" maxlength="50"/>
                                </td>
                            </tr>
                            <tr>
                                <td>TIPO DE TRAMITE: </td>
                                <td>
                                    <input  readonly="readonly"  type="text" id="nombre_tramite" name="nombre_tramite" value="<?php echo $resultados_ticket[nombre_tramite];?>"  size="50" maxlength="50"/>																												 
                                </td>
                            </tr>

                            <tr>
                                <td>MONTO DE SOLICITUD: </td>
                                <td>
                                    <table  border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input  style="text-align:right" readonly="true" type="text" id="monto_solicitud"  name="monto_solicitud" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="<?php echo $resultados_ticket[monto_solicitud];?>" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                                                </td>
                                                <td>
                                                    PERSONA CONTACTO / DEP.:
                                                    <input readonly="true" type="text" id="persona_contacto_dep" name="persona_contacto_dep" value="<?php echo $resultados_ticket[persona_contacto_dep];?>" size="50" maxlength="50"/>																	
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
                <td class="titulo" colspan="2" height="18"  align="left"><b>Ultima actuación del ticket:</b></td>
            </tr>

            <tr>
                <td colspan="2">
                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                             <tr>
                                <td width="20%">
                                    FECHA ASIGNACÓN:
                                </td>
                                <td>
                                    <table  border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" id="fecha_registro" name="fecha_registro" value="<?php echo date_format(date_create($resultados_subticket['fecha_registro']), 'd/m/Y');?>"  size="10" />																	
                                                </td>
                                                <td>
                                                    ULTIMA UNIDAD ENCARGADA:
                                                    <input  readonly="readonly"  type="text" id="nombre_unidad_ult" name="nombre_unidad_ult" value="<?php echo $resultados_subticket[nombre_unidad];?>"  size="50" maxlength="50"/>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>ESTADO DEL TICKET:</td>
                                <td>
                                    <input  readonly="readonly"  type="text" id="estado_ticket" name="estado_ticket" value="<?php echo $resultados_subticket[descripcion_estado_tramite];?>"  size="50" maxlength="50"/>
                                </td>
                            </tr>
                            <td width="20%">
                                    FECHA CITA:
                                </td>
                                <td>
                                    <table  border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" readonly="true" id="fecha_cita_programada" name="fecha_cita_programada" value="<?php if ($resultados_subticket['fecha_cita_programada']==NULL) echo ''; else echo date_format(date_create($resultados_subticket['fecha_cita_programada']), 'd/m/Y');?>"  size="8" />																	
                                                </td>
                                                <td>
                                                    HORA PROGRAMADA:
                                                    <input type="text" readonly="true" id="hora_cita_programada" name="hora_cita_programada" value="<?php if ($resultados_subticket['hora_cita_programada']==NULL) echo ''; else echo date_format(date_create($resultados_subticket['hora_cita_programada']), 'g:i A.');?>"  size="8" />
                                                </td>
                                                <td>
                                                    FECHA ATENCIÓN:
                                                    <input type="text" readonly="true" id="fecha_atencion" name="fecha_atencion" value="<?php if ($resultados_subticket['fecha_atencion']==NULL) echo ''; else echo date_format(date_create($resultados_subticket['fecha_atencion']), 'd/m/Y');?>"  size="8" />
                                                </td>
                                                <td>
                                                    HORA ATENCIÓN:
                                                    <input type="text" readonly="true" id="hora_atencion" name="hora_atencion" value="<?php if ($resultados_subticket['hora_atencion']==NULL) echo ''; else echo date_format(date_create($resultados_subticket['hora_atencion']), 'g:i A.');?>"  size="8" />
                                                </td>
                                     
                                    
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            <tr>
                                <td>
                                    SITUACIÓN ACTUAL:
                                </td>
                                <td>
                                    <textarea  readonly="true" name="situacion_actual" id="situacion_actual" cols="84" rows="2" ><?php echo $resultados_subticket[situacion_actual];?></textarea>																	
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ACTUACIÓN:
                                </td>
                                <td>
                                    <textarea  readonly="true" name="actuacion" id="actuacion" cols="84" rows="2" ><?php echo $resultados_subticket[actuacion];?></textarea>																	
                                </td>
                            </tr>
                            

                            <tr>
                                <td>MONTO DE AUTORIZADO:</td>
                                <td>														         
                                    <input  style="text-align:right" readonly="true" type="text" id="monto_autorizado"  name="monto_autorizado" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="<?php echo $resultados_subticket[monto_autorizado];?>" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                                 </td>
                           </tr>
                           <tr>
                                <td>
                                    OBSERVACIONES:
                                </td>
                                <td>
                                    <textarea  readonly="true" name="observaciones" id="observaciones" cols="84" rows="2" ><?php echo $resultados_subticket[observaciones];?></textarea>																	
                                </td>
                            </tr>
                        </tbody>
                    </table>	
                </td>
                
                
            </tr>

            <tr>
                													
                <td colspan="2" class="botones" align="right" >	
                    
                    <?php
                        
                    
                        switch ($resultados_ticket[cod_estado_tramite]){
                            case 1: // Administrador
                                echo '<a href="index2.php?view=ticket_status_update&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Programar Ticket">
                                            <img border="0" src="images/tramites28.png" alt="borrar">
                                        </a>
                                        <a href="index2.php?view=ticket_status_escalar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Escalar Ticket">
                                            <img border="0" src="images/escalar_ticket.png" alt="borrar">
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="index2.php?view=ticket_status_completar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Completar Ticket">
                                            <img border="0" src="images/completar_ticket2.png" alt="borrar">
                                        </a>
                                        <a onclick="return confirm(\'Esta seguro que desea Cancelar el Ticket?\');" href="index2.php?view=ticket_status_cancelar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Cancelar Ticket">
                                            <img border="0" src="images/cancelar_ticket.png" alt="borrar">
                                        </a>
                                        <a onclick="return confirm(\'Esta seguro que desea Anular el Ticket?\');" href="index2.php?view=ticket_status_anular&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Anular Ticket">
                                            <img border="0" src="images/borrar28.png" alt="borrar">
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="index2.php?view=ticket_update&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Editar los datos del Ticket">
                                            <img border="0" src="images/modificar.png" alt="borrar">
                                        </a>';
                                break;			
                            case 2: // Administrador
                                echo '<a href="index2.php?view=ticket_status_update&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Programar Ticket">
                                            <img border="0" src="images/tramites28.png" alt="borrar">
                                        </a>
                                        <a href="index2.php?view=ticket_status_escalar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Escalar Ticket">
                                            <img border="0" src="images/escalar_ticket.png" alt="borrar">
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="index2.php?view=ticket_status_completar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Completar Ticket">
                                            <img border="0" src="images/completar_ticket2.png" alt="borrar">
                                        </a>
                                        <a onclick="return confirm(\'Esta seguro que desea Cancelar el Ticket?\');" href="index2.php?view=ticket_status_cancelar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Cancelar Ticket">
                                            <img border="0" src="images/cancelar_ticket.png" alt="borrar">
                                        </a>
                                        <a onclick="return confirm(\'Esta seguro que desea Anular el Ticket?\');" href="index2.php?view=ticket_status_anular&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Anular Ticket">
                                            <img border="0" src="images/borrar28.png" alt="borrar">
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="index2.php?view=ticket_update&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Editar los datos del Ticket">
                                            <img border="0" src="images/modificar.png" alt="borrar">
                                        </a>';
                                break;			
                            case 3: // Administrador
                                echo '<a href="index2.php?view=ticket_status_escalar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Escalar Ticket">
                                            <img border="0" src="images/escalar_ticket.png" alt="borrar">
                                        </a>
                                        <a href="index2.php?view=ticket_status_reprogramar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Reprogramar Ticket">
                                            <img border="0" src="images/reprogramar28.png" alt="borrar">
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="index2.php?view=ticket_status_completar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Completar Ticket">
                                            <img border="0" src="images/completar_ticket2.png" alt="borrar">
                                        </a>
                                        <a onclick="return confirm(\'Esta seguro que desea Cancelar el Ticket?\');" href="index2.php?view=ticket_status_cancelar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Cancelar Ticket">
                                            <img border="0" src="images/cancelar_ticket.png" alt="borrar">
                                        </a>
                                        <a onclick="return confirm(\'Esta seguro que desea Anular el Ticket?\');" href="index2.php?view=ticket_status_anular&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Anular Ticket">
                                            <img border="0" src="images/borrar28.png" alt="borrar">
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;';
                                break;			
                            case 4: // Administrador
                                echo '<a href="index2.php?view=ticket_status_escalar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Escalar Ticket">
                                            <img border="0" src="images/escalar_ticket.png" alt="borrar">
                                        </a>
                                        <a href="index2.php?view=ticket_status_reprogramar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Reprogramar Ticket">
                                            <img border="0" src="images/reprogramar28.png" alt="borrar">
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="index2.php?view=ticket_status_completar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Completar Ticket">
                                            <img border="0" src="images/completar_ticket2.png" alt="borrar">
                                        </a>
                                        <a onclick="return confirm(\'Esta seguro que desea Cancelar el Ticket?\');" href="index2.php?view=ticket_status_cancelar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Cancelar Ticket">
                                            <img border="0" src="images/cancelar_ticket.png" alt="borrar">
                                        </a>
                                        <a onclick="return confirm(\'Esta seguro que desea Anular el Ticket?\');" href="index2.php?view=ticket_status_anular&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Anular Ticket">
                                            <img border="0" src="images/borrar28.png" alt="borrar">
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;';
                                break;			
                            case 5: // Administrador
                                echo '<a href="index2.php?view=ticket_status_update&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Programar Ticket">
                                            <img border="0" src="images/tramites28.png" alt="borrar">
                                        </a>
                                        <a href="index2.php?view=ticket_status_escalar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Escalar Ticket">
                                            <img border="0" src="images/escalar_ticket.png" alt="borrar">
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="index2.php?view=ticket_status_completar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Completar Ticket">
                                            <img border="0" src="images/completar_ticket2.png" alt="borrar">
                                        </a>
                                        <a onclick="return confirm(\'Esta seguro que desea Cancelar el Ticket?\');" href="index2.php?view=ticket_status_cancelar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Cancelar Ticket">
                                            <img border="0" src="images/cancelar_ticket.png" alt="borrar">
                                        </a>
                                        <a onclick="return confirm(\'Esta seguro que desea Anular el Ticket?\');" href="index2.php?view=ticket_status_anular&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Anular Ticket">
                                            <img border="0" src="images/borrar28.png" alt="borrar">
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;';
                                break;			
                           			
                           			
                            			
                            
                        }
                        
                        if($resultados_ticket[name_file_upload]!=""){
                            $dir_upload='upload_file/'; // Nombre del Directorio de las subidas de archivos
                            $archivo = $dir_upload.$resultados_ticket[name_file_upload];														
                            if (file_exists($archivo)){
                                echo '<a href="'.$archivo.'" download="'.$archivo.'" target="_blank" title="Descargar Archivo para Visualizar">
                                        <img src="images/download28.png" name="Image_Encab"  border="0"/>
                                    </a>';
                            }
                            
                        }
                        
                        echo '<a title="Imprimir Ticket" target="_Blank" href="reportes/imprimir_tac.php?cod_ticket='.$resultados_ticket[cod_ticket].'&user='.$_SESSION[user].'">
                                <img  border="0" name="Image_Encab" src="images/printer28.png">
                            </a>
                            &nbsp;&nbsp;';
                        
                     ?>
                                
                    
                    <!--<input  class="button" type="button" onclick="javascript:window.location.href='?view=tickets'" value="Cerrar" name="cerrar" />--> 
                </td>													
            </tr>
        </table>
    </form>   
    <br>
        
<br />
	<!-- Datos de la carga familia del registro Registro -->
<table class="gen_table_form" cellspacing="1" cellpadding="2" width="800px" align="center" border="1">
    <tbody>
        <tr>
            <th class="section_name" colspan="8">DETALLES DE LAS ACTUACIONES DEL TICKET</th>
        </tr>
        <tr>											
            <td class="item_text" width="5%"  align="center">ASIGNACÓN</td>											
            <td class="item_text" width="8%" align="center">ESTADO</td>																					
            <td class="item_text" width="35%" align="center">UNIDAD ENCARGADA</td>											
            <td class="item_text" width="12%" align="center">CITA</td>
            <td class="item_text" width="12%" align="center">ATENCIÓN</td>
            <td class="item_text" width="5%" align="center">ACCIÓN</td>											
        </tr>

        <?php
            if($total_result_detalle_ticket==0){
                echo '<tr class="item_oscuro">';		
                echo '<td align="center" colspan="8"> NO EXISTEN REGISTROS DE ACTUACION DEL TAC</td>';
                echo '</tr>';
            }

            $xxx=0;
            while($resultados_detalle_ticket = pg_fetch_array($result_detalle_ticket)) {	
                $xxx=$xxx+1;			
                if (($xxx %2)==0) $i='item_claro'; else $i='item_oscuro';
        ?>
            <tr class="<?php echo $i;?>">		
                <td align="center"><?php echo date_format(date_create($resultados_detalle_ticket['fecha_registro']), 'd/m/Y') ;?> </td>													
                <td>
                    <?php
                        if ($resultados_detalle_ticket['tipo_estado_tramite']==1){
                            echo '<img  border="0" name="Image_Encab" src="images/help.png" title="'.$resultados_detalle_ticket['descripcion_estado_tramite'].'" >';
                        }elseif ($resultados_detalle_ticket['tipo_estado_tramite']==2){
                            echo '<img  border="0" name="Image_Encab" src="images/tick.png" title="'.$resultados_detalle_ticket['descripcion_estado_tramite'].'">';
                        }elseif ($resultados_detalle_ticket['tipo_estado_tramite']==3 && $resultados_detalle_ticket['siglas_estado_tramite']=="NUL" ){
                            echo '<img  border="0" name="Image_Encab" src="images/borrar.png" title="'.$resultados_detalle_ticket['descripcion_estado_tramite'].'">';
                        }else{
                            echo '<img  border="0" name="Image_Encab" src="images/delete.png" title="'.$resultados_detalle_ticket['descripcion_estado_tramite'].'">';
                        }
                        echo ' '.$resultados_detalle_ticket['siglas_estado_tramite']
                    ?> 
                </td>
                					
                <td><?php echo $resultados_detalle_ticket['nombre_unidad']?> </td>
                <td align="center">
                    <?php 
                        if ($resultados_detalle_ticket['fecha_cita_programada']==NULL){
                            echo '';
                        }else{
                            echo date_format(date_create($resultados_detalle_ticket['fecha_cita_programada']), 'd/m/Y').' '.date_format(date_create($resultados_detalle_ticket['hora_cita_programada']), 'g:i A.') ;
                        }
                    ?> 
                </td>
                <td align="center">
                    <?php 
                        if ($resultados_detalle_ticket['fecha_atencion']==NULL){
                            echo '';
                        }else{
                            echo date_format(date_create($resultados_detalle_ticket['fecha_atencion']), 'd/m/Y').' '.date_format(date_create($resultados_detalle_ticket['hora_atencion']), 'g:i A.') ;
                        }
                    ?> 
                </td>
                <td align="center">
                    <a class="fancybox fancybox.iframe" href="ticket/ticket_detalle_view.php?cod_detalle_ticket=<?php echo $resultados_detalle_ticket[cod_detalle_ticket];?>"  title="Ver Detalles del Registro">
                        <img  border="0" name="Image_Encab" src="images/ver_detalle.png">			
                    </a>
                </td>												
            </tr>
        <?php 
            } //fin del while
        ?>
    </tbody>
</table>
<br>
<br>
<?php } ?>	

<script type="text/javascript" >
	jQuery(function($) {
	      $.mask.definitions['~']='[JEVGDCHjevgdch]';
	      //$('#fecha_nac').mask('99/99/9999');
	      //$('#fecha_deposito').mask('99/99/9999');
	      $('#telefono').mask('(9999)-9999999');
	      $('#celular').mask('(9999)-9999999');
	      $('#telefono_trabajo').mask('(9999)-9999999');
	      $('#telefono_fax').mask('(9999)-9999999');
	      $('#rif').mask('~-9999?9999-9',{placeholder:" "});
	      $('#cedula_rif').mask('~-9999?99999',{placeholder:" "});
	      //$('#phoneext').mask("(999) 999-9999? x99999");
	      //$("#tin").mask("99-9999999");
	      //$("#ssn").mask("999-99-9999");
	      //$("#product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("Ha escrito lo siguiente: "+this.val());}});
	      //$("#eyescript").mask("~9.99 ~9.99 999");
	      
	   });
	   
   function ue_buscariglesia()	{
		document.QForm.igl_cod_iglesia_mision.value="";
		document.QForm.igl_nombre_iglesia_mision.value="";									
		window.open("iglesias/cat_iglesias.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=565,height=500,left=50,top=50,location=no,resizable=no");
	}	
</script>

				    				
