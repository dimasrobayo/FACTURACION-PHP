<?php 
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    if (isset($_POST['codfacturatmp'])){
        $codfacturatmp=$_POST["codfacturatmp"];
        $cedula_solicitante=strtoupper($_POST['cedula_solicitante']);
        $cedula_rif_fac = preg_replace("/\s+/", "", $cedula_solicitante);
        $cedula_rif_fac = str_replace("-", "", $cedula_rif_fac); 
        $iva=$_POST["iva"];
        $cedula_usuario=$_SESSION['id'];
        $tipo_factura = $_POST["tipo_factura"];
        $status = 1;
        
        $control=$_POST["control"];
        $cod_ticket=$_POST["cod_ticket"];

        $query="insert into factura (cedula_rif,fecha_factura,hora_factura,iva,cedula_usuario,tipo_factura,status) values ('$cedula_rif_fac','now()','now()','$iva','$cedula_usuario','$tipo_factura','$status') RETURNING n_factura";
        $result_insert_factura=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());	
        $result_insert = pg_fetch_row($result_insert_factura);
        $codfactura = $result_insert[0];

        if(pg_affected_rows($result_insert_factura)){
            $query="SELECT * FROM detalle_facturatmp WHERE n_factura='$codfacturatmp'";
            $result_detalle_factura=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());	

            while($resultados = pg_fetch_array($result_detalle_factura)){
                
                $codconcepto=$resultados[codigo_concepto];
                $cantidad=$resultados[cantidad];
                $monto_concepto=$resultados[monto_concepto];
                
                $query="SELECT * FROM concepto_factura where codigo_concepto='$codconcepto'";
                $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());	
                $resultados_concepto=pg_fetch_array($result);
                pg_free_result($result);
                
                $status_stock=$resultados_concepto[status_stock];
                $stock = $resultados_concepto[stock];
                $stock_update=($stock-$cantidad);
                
                
                $query="INSERT INTO detalle_factura (codigo_concepto,n_factura,cantidad,monto_concepto) VALUES ('$codconcepto','$codfactura','$cantidad','$monto_concepto')";
                $result_insert_detalle_factura=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());	
                
                if(pg_affected_rows($result_insert_detalle_factura)){
                    if($status_stock==1){
                        $query="UPDATE concepto_factura SET codigo_concepto='$codconcepto',stock='$stock_update' WHERE codigo_concepto='$codconcepto'";
                        $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());
                        pg_free_result($result);
                    }
                }
            }
            
            $query="SELECT * FROM facturatmp WHERE n_factura='$codfacturatmp'";
            $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());
            $result_facturatmp = pg_fetch_array($result);
            pg_free_result($result);

            $status_fp_efectivo=$result_facturatmp[status_fp_efectivo];
            $status_fp_deposito=$result_facturatmp[status_fp_deposito];
            $status_fp_cheque=$result_facturatmp[status_fp_cheque];

            $monto_efectivo=$result_facturatmp["monto_efectivo"];
            $nro_deposito=$result_facturatmp["nro_deposito"];
            $cod_banco_deposito=$result_facturatmp["cod_banco"];
            $cod_cuenta_banco=$result_facturatmp["cod_cuenta_banco"];
            $fecha_deposito=$result_facturatmp["fecha_deposito"];
            $monto_deposito=$result_facturatmp["monto_deposito"];
            $nro_cheque=$result_facturatmp["nro_cheque"];
            $cod_banco_cheque=$result_facturatmp["cod_banco_cheque"];
            $monto_cheque=$result_facturatmp["monto_cheque"];

            $query="UPDATE factura SET status_fp_efectivo='$status_fp_efectivo', ".
                " monto_efectivo='$monto_efectivo',status_fp_deposito='$status_fp_deposito',cod_banco_deposito='$cod_banco_deposito',cod_cuenta_banco='$cod_cuenta_banco',nro_deposito='$nro_deposito',fecha_deposito='$fecha_deposito',monto_deposito='$monto_deposito', ".
                " status_fp_cheque='$status_fp_cheque',cod_banco_cheque='$cod_banco_cheque',nro_cheque='$nro_cheque',monto_cheque='$monto_cheque' ".
                " WHERE n_factura='$codfactura'";
            $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());
            pg_free_result($result);
            
            $query="DELETE FROM facturatmp WHERE n_factura='$codfacturatmp'";
            $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());
            pg_free_result($result);
            
            if ($control==1) {
                
                $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades,categorias". 
                " WHERE ticket.cod_ticket='$cod_ticket' AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND ticket.cedula_rif=solicitantes.cedula_rif AND ticket.cod_tramite=tramites.cod_tramite ".
                " AND tramites.cod_categoria=categorias.cod_categoria AND tramites.cod_unidad=unidades.cod_unidad";
                $result = pg_query($query)or die(pg_last_error());
                $total_result_ticket= pg_num_rows($result);
                $resultados_ticket=pg_fetch_array($result);	
                pg_free_result($result);

                if ($total_result_ticket){
                    $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                            " WHERE detalles_ticket.cod_detalle_ticket='$resultados_ticket[cod_subticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                            " AND detalles_ticket.cod_unidad=unidades.cod_unidad";
                    $result = pg_query($query)or die(pg_last_error());
                    $resultados_subticket=pg_fetch_array($result);	
                    pg_free_result($result);
                }
                
                $situacion_actual="LA SOLICITUD FUE ATENDIDA SATISFACTORIAMENTE";
                $actuacion="LA SOLICITUD FUE ATENDIDA SATISFACTORIAMENTE";
                $observaciones="";
                $fecha_atencion=date('Y-m-d'); 
                $hora_atencion=date('H:i');

                $cod_unidad=$resultados_ticket[cod_unidad];
                $cod_estado_tramite=6;
                $respuesta=1;
                $monto_autorizado=$resultados_ticket[monto_solicitud];
                $cantidad_autorizado=$resultados_ticket[cantidad_solicitud];
                $user=$_SESSION[user];
                
                //CONSULTAR DATOS DE LA EMPRESA
                $query="SELECT * FROM empresa where rif_empresa = '$id_empresa'";
                $result = pg_query($query)or die(pg_last_error());
                $resultados_empresa=pg_fetch_array($result);	
                pg_free_result($result);

                $send_sms=$resultados_empresa[send_sms];
                $send_email=$resultados_empresa[send_email];
                $sms=$resultados_empresa[sms_campletar_ticket];
                // FIN CONSULTA DE EMPRESA

        //        $query="insert into detalles_ticket (cod_ticket,cod_estado_tramite,cod_unidad,fecha_cita_programada,hora_cita_programada,fecha_atencion,hora_atencion,situacion_actual,actuacion,monto_autorizado,observaciones,user_login) values ('$cod_ticket','$cod_estado_tramite','$cod_unidad','$fecha_cita_programada','$hora_cita_programada','$fecha_atencion','$hora_atencion','$situacion_actual','$actuacion','$monto_autorizado','$observaciones','$user') RETURNING cod_detalle_ticket";
                $query="insert into detalles_ticket (cod_ticket,cod_estado_tramite,cod_unidad,fecha_atencion,hora_atencion,situacion_actual,actuacion,monto_autorizado,observaciones,user_login,cantidad_autorizado) values ('$cod_ticket','$cod_estado_tramite','$cod_unidad','$fecha_atencion','$hora_atencion','$situacion_actual','$actuacion','$monto_autorizado','$observaciones','$user','$cantidad_autorizado') RETURNING cod_detalle_ticket";
                $result = pg_query($query)or die(pg_last_error());
                $result_insert_detalle=pg_fetch_row($result);
                $cod_subticket = $result_insert_detalle[0];
                pg_free_result($result);

                if ($result_insert_detalle[0]){
                    $query="update ticket set cod_estado_tramite='$cod_estado_tramite', cod_subticket='$cod_subticket', respuesta='$respuesta', n_factura='$codfactura' where cod_ticket='$cod_ticket'";
                    $result = pg_query($query)or die(pg_last_error());

                    $query="SELECT * FROM ticket,tramites,solicitantes,estados_tramites,unidades,categorias". 
                        " WHERE ticket.cod_ticket='$cod_ticket' AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                        " AND ticket.cedula_rif=solicitantes.cedula_rif AND ticket.cod_tramite=tramites.cod_tramite ".
                        " AND tramites.cod_categoria=categorias.cod_categoria AND tramites.cod_unidad=unidades.cod_unidad";
                    $result = pg_query($query)or die(pg_last_error());
                    $total_result_ticket= pg_num_rows($result);
                    $resultados_ticket=pg_fetch_array($result);	
                    pg_free_result($result);

                    if ($total_result_ticket){
                        $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                                " WHERE detalles_ticket.cod_detalle_ticket='$resultados_ticket[cod_subticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                                " AND detalles_ticket.cod_unidad=unidades.cod_unidad";
                        $result = pg_query($query)or die(pg_last_error());
                        $resultados_subticket=pg_fetch_array($result);	
                        pg_free_result($result);
                    }

                    //// ENVIAR EMAIL A SOLICITANTE
                    if($send_email==1){
                        if($resultados_ticket[email]!="") {
                            $fecha=date_format(date_create($resultados_subticket['fecha_cita_programada']), 'd/m/Y');
                            $hora= date_format(date_create($resultados_subticket['hora_cita_programada']), 'g:i A.');

                            $fecha_atencion=date_format(date_create($resultados_subticket['fecha_atencion']), 'd/m/Y');
                            $hora_atencion= date_format(date_create($resultados_subticket['hora_atencion']), 'g:i A.');
                            require ("aut_sys_config_email_gmail.inc.php"); //consultar datos de variable
                            $body="Saludos de la Oficina de Atención al Soberano,<br><br> ".
                                    " Se ha Actualizado su Solicitud en el Sistema de Atención Al Ciudadano (SAC). <br> ".
                                    " A continuación los detalles: <br><br> ".
                                    " <strong>Ticket Nro.:</strong> ".str_pad($cod_ticket,10,"0",STR_PAD_LEFT)."<br> ".
                                    " <strong>Descripción de la Solicitud:</strong> $resultados_ticket[descripcion_ticket]<br> ".
                                    " <strong>Monto de la Solicitud:</strong> $resultados_ticket[monto_solicitud]<br><br>".
                                    " <strong>Estado actual del Tramite:</strong> $resultados_ticket[descripcion_estado_tramite]<br><br>".
                                    " <strong>Fecha y Hora de Atención:</strong> $fecha_atencion $hora_atencion <br>".
                                    " <strong>Situación Actual:</strong> $resultados_subticket[situacion_actual]<br>".
                                    " <strong>Actuación:</strong> $resultados_subticket[actuacion]<br>".
                                    " <strong>Cantidad Autorizado:</strong> $resultados_subticket[cantidad_autorizado]<br><br>".
                                    " <strong>Monto Autorizado:</strong> $resultados_subticket[monto_autorizado]<br><br>".
                                    " <a href=\"$ip_server/$dir_name/reportes/imprimir_tac_online.php?cod_ticket=$cod_ticket\" target=\"_blank\"> ".
                                    " Pulse aquí para visualizar el Ticket</a><br><br>".
                                    " No responda a este mensaje ya que ha sido generado automáticamente para su información.";						
                            $mail->Subject    = "Ticket Nro.: ".str_pad($cod_ticket,10,"0",STR_PAD_LEFT);
                            $mail->AltBody    = "Detalles Actualizacion del Ticket de Solicitud!"; // optional, comment out and test
                            $mail->MsgHTML($body);						
                            $mail->AddAddress($resultados_ticket[email], $resultados_ticket[nombre_solicitante]);
                            $mail->Send();
                            $mail->ClearAddresses();
                        }
                    } 
                    //// ENVIAR SMS AL SOLICITANTE
                    if($send_sms==1){
                        //INFORMACION PARA EL ENVIO DE SMS
                        $destino=$resultados_ticket[telefono_movil];
                        $sms=$sms.'; Ticket Nro.: '.$cod_ticket;
                        $creatorId=$_SESSION['user'];

                        //Conexion a la base de datos
                        require("conexion_sms/aut_config.inc.php");
                        $db_conexion=pg_connect("host=$sql_host_sms dbname=$sql_db_sms user=$sql_usuario_sms password=$sql_pass_sms");	

                        $dest = preg_replace("/\s+/", "", $destino);
                        $dest = str_replace("(", "", $dest);
                        $dest = str_replace(")-", "", $dest);

                        $total_send=0;
                        if ( strlen($dest)==11 and ((stristr($dest, '0414') or stristr($dest, '0424') or stristr($dest, '0426') or stristr($dest, '0416') or stristr($dest, '0412') ))){
                            $error="bien";	
                            $query="SELECT insert_outbox('$dest','$sms','$creatorId')";								
                            $result = pg_query($query)or die(pg_last_error());
                            if(pg_affected_rows($result)){ 
                                $total_send++;	
                            }
                            pg_free_result($result);
                        }

                    }// FIN ENVIO SMS   
                }
            }
        }
    }
?>

<?php if($div_menssage) { ?>					
    <script type="text/javascript">
        function ver_msg(){
            Effect.Fade('msg');
        }  
        setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
    </script>
 <?php } ?>

    
<div align="center" class="centermain">
    <div class="main">
        <table border="0" width="100%" align="center">
            <tbody>			
                <tr>
                    <td  id="msg" align="center">		
                        <?php echo $div_menssage;?>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table class="adminfactura">
            <tr>
                <th class="adminfactura">
                    FACTURACIÓN
                </th>
            </tr>
        </table>   

        <br><br>       

        <table width="100%" class="adminform" align="center">
            <tr>
                <th colspan="2" align="center">
                    Registro de Factura
                </th>
            </tr> 
            
            <tr>
                <td colspan="2" align="center">
                    <div align="center"> 
                        <h3 class="info">	
                            <font size="2">						
                                Factura Nro: <?php echo $codfactura;?> Registrado con &eacute;xito
                                <br />
                                <script type="text/javascript">
                                    function redireccionar(){
                                        window.location="?view=factura";
                                    }
                                    setTimeout ("redireccionar()", 10000); //tiempo de espera en milisegundos
                                </script> 						
                                <!--[<a href="?view=factura" name="factura"> Continuar </a>]-->
                            </font>							
                        </h3>
                    </div> 
                </td>
            </tr>
            
            <tr align="center">
                <td width="100%" valign="top" align="center">
                    <div id="cpanel">
                        <div style="float:right;">
                            <div class="icon">
                                <a href="index2.php?view=factura">
                                    <img src="images/factura.png" alt="salir" align="middle"  border="0" />
                                    <span>Gestor de Datos</span>
                                </a>
                            </div>
                        </div>
                        <div style="float:right;">
                            <div class="icon">
                                <a href="reportes/imprimir_factura.php?codfactura=<?php echo $codfactura;?>" target="_blank">
                                    <img src="images/printer.png" alt="Imprimir" align="middle"  border="0" />
                                    <span>Imprimir</span>
                                </a>
                            </div>
                        </div>
                        <div style="float:right;">
                            <div class="icon">
                                <a href="index2.php?view=factura_add">
                                    <img src="images/facturanueva.png" alt="agregar" align="middle"  border="0" />
                                    <span>Facturar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>	
       <br />     
    </div>
</div>
