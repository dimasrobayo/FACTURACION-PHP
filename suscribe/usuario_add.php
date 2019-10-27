<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
//        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    if (isset($_POST[save])){   // Insertar Datos del formulario
        $cedula_rif=strtoupper($_POST['cedula_rif']);
        $cod_categoria=strtoupper($_POST["cod_categoria"]);
        $tramite=strtoupper($_POST["cod_tramite"]);
        $cod_unidad=strtoupper($_POST["cod_unidad"]);
        $persona_contacto_dep=strtoupper($_POST["persona_contacto_dep"]);
        $descripcion_ticket=strtoupper($_POST["descripcion_ticket"]);
        $monto_solicitud=strtoupper($_POST["monto_solicitud"]);
        if (isset($_POST["declaracion"])) $declaracion=1;else $declaracion=0;
        $online=1;
        $cod_estado_tramite=1;
        
        $query="SELECT * FROM tramites where cod_categoria='$cod_categoria' and cod_unidad='$cod_unidad' and nombre_tramite='$tramite' LIMIT 1";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_tramite=pg_fetch_array($result);	
        pg_free_result($result);
        
       
        if($send_email==1){
            if($result_solicitantes['email']!="") {

                require ("aut_sys_config_email_gmail.inc.php"); //consultar datos de variable
                $body="Saludos de la Oficina de Atención al Soberano,<br><br> ".
                        " Ha registrado un Ticket en el Sistema de Atención Al Ciudadano (SAC). <br> ".
                        " A continuación los detalles: <br><br> ".
                        " <strong>Ticket Nro.:</strong> ".str_pad($cod_ticket,7,"0",STR_PAD_LEFT)."<br> ".
                        " <strong>Descripción de la Solicitud:</strong> $descripcion_ticket<br> ".
                        " <strong>Monto de la Solicitud:</strong> $monto_solicitud<br><br>".
                        " <a href=\"$server/$dir_name/reportes/imprimir_tac_online.php?cod_ticket=$cod_ticket\" target=\"_blank\"> ".
                        " Pulse aquí para visualizar el Ticket</a><br><br>".
                        " No responda a este mensaje ya que ha sido generado automáticamente para su información.";						
                $mail->Subject    = "Ticket Nro.: ".str_pad($cod_ticket,7,"0",STR_PAD_LEFT);
                $mail->AltBody    = "Detalles del Ticket de Solicitud!"; // optional, comment out and test
                $mail->MsgHTML($body);						
                $mail->AddAddress($result_solicitantes[email], $result_solicitantes[nombre_solicitante]);
                $mail->Send();
                $mail->ClearAddresses();
            }
        }
    }else{
            $cedula_rif_insert = preg_replace("/\s+/", "", $cedula_rif);
            $nombre=strtoupper($_POST["nombreapellido"]);
            $apellido=strtoupper($_POST["nombreapellido"]);
            $sexo=$_POST['sexo'];
            $fecha_nac=implode('-',array_reverse(explode('/',$_POST['fecha_nac']))); 
            $email=$_POST['email'];

//            $query="SELECT insert_usuario('$cedula_rif_insert','$cod_tipo_solicitante','$nombreapellido','$sexo','$fecha_nac','$direccion','$telefono','$celular','$email','$codcom')";
//            $result = pg_query($query)or die(pg_last_error());
//            $result_insert=pg_fetch_array($result);
//            pg_free_result($result);

            if ($result_insert[0]==1){
                if($send_email==1){
                    if($result_solicitantes['email']!="") {

                        require ("aut_sys_config_email_gmail.inc.php"); //consultar datos de variable
                        $body="Saludos de la Oficina de Atención al Soberano,<br><br> ".
                                " Ha registrado un Ticket en el Sistema de Atención Al Ciudadano (SAC). <br> ".
                                " A continuación los detalles: <br><br> ".
                                " <strong>Ticket Nro.:</strong> ".str_pad($cod_ticket,7,"0",STR_PAD_LEFT)."<br> ".
                                " <strong>Descripción de la Solicitud:</strong> $descripcion_ticket<br> ".
                                " <strong>Monto de la Solicitud:</strong> $monto_solicitud<br><br>".
                                " <a href=\"$ip_server/$dir_name/reportes/imprimir_tac_online.php?cod_ticket=$cod_ticket\" target=\"_blank\"> ".
                                " Pulse aquí para visualizar el Ticket</a><br><br>".
                                " No responda a este mensaje ya que ha sido generado automáticamente para su información.";						
                        $mail->Subject    = "Ticket Nro.: ".str_pad($cod_ticket,7,"0",STR_PAD_LEFT);
                        $mail->AltBody    = "Detalles del Ticket de Solicitud!"; // optional, comment out and test
                        $mail->MsgHTML($body);						
                        $mail->AddAddress($result_solicitantes[email], $result_solicitantes[nombre_solicitante]);
                        $mail->Send();
                        $mail->ClearAddresses();
                    }
                }      
            }
        }

?>
<div align="left">
    <form>
        <h4>CREA TU PROPIA CUENTA</h4>				
        <div>				        
            <div style="text-align: justify; font-size : 14px">
                <strong>ES FACIL Y GRATIS</strong>
                <br /><br />
                Coloca los datos Solicitados en el formulario a continuaci&oacute;n y luego al hacer clic en el boton "Crear una Cuenta", deber concluir el proceso entrando a tu correo electronico y siguiendo las instrucciones que indiquen el correo electronico.<br />
                <br />
                Es Importante colocar tu Cedula de identidad debido a que los datos aqui suministrados nos ayudara a poder canalizar, gestionar todas tus solicitudes y darte una respuesta eficiente. 
                <br />
            </div>    
        </div>  

        <div class="form-style-6">
            <h1>Registrate Gratis</h1>
                <input id="cedula_rif" autofocus="true" name="cedula_rif"  class="validate[required,minSize[6]] text-input" type="text"  value="<?php if($total_result==0) echo $cedula_rif;?>" maxlength="12" placeholder="CEDULA"/>
                <input type="text" name="nombre" placeholder="NOMBRES" />
                <input type="text" name="apellido" placeholder="APELLIDOS" />
                <input type="email" name="email" placeholder="DIRECCION DE CORREO ELECTRONICO" />
                <input class="validate[custom[date],past[NOW]]" name="fecha_nac" type="text" value="<?php if ($error!="") echo implode('/',array_reverse(explode('-',$fecha_nac)));?>"  id="fecha_nac"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);" placeholder="FECHA DE NACIMIENTO" />
                <input type="submit" value="Crear una Cuenta" />
        </div>

        <div style="text-align: center; font-size : 14px">
            <input id="declaracion" class="validate[required]" name="declaracion"  type="checkbox">DECLARO
            QUE LOS DATOS SUMINISTRADOS SON VERDADEROS Y
            AUTORIZO COMPROBAR LA VERACIDAD DE LOS
            MISMOS.
        </div>
    </form>
</div> 
<br />
<br />     

<script type="text/javascript" >
	jQuery(function($) {
	      $.mask.definitions['~']='[JEVGDCjevgdc]';
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
</script>

