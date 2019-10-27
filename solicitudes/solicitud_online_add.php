<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

     //Conexion a la base de datos
    include("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass")or die(pg_last_error());

    if (isset($_POST['cedula_rif'])){ // Recibir los Datos 
        $cedula_rif=strtoupper($_POST['cedula_rif']);
        $cedula = preg_replace("/\s+/", "", $cedula_rif);
        $cedula = str_replace("-", "", $cedula);

        $query="select * from solicitantes,comunidades where cedula_rif='$cedula' AND solicitantes.idcom=comunidades.idcom";
        $result = pg_query($query)or die(pg_last_error());
        $result_solicitantes=pg_fetch_array($result);	
        pg_free_result($result);
    }
        
        
    if (isset($_POST[save])){   // Insertar Datos del formulario
        $cedula_rif=strtoupper($_POST['cedula_rif']);
        $cod_categoria=strtoupper($_POST["cod_categoria"]);
        echo $tramite=strtoupper($_POST["cod_tramite"]);
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
        
        if ($resultados_tramite[0]){
            $cod_tramite=$resultados_tramite[cod_tramite];
        }
        
        
        if ($result_solicitantes[0]){
            $error="bien";
            $query="insert into ticket (cedula_rif,cod_estado_tramite,cod_tramite,persona_contacto_dep,descripcion_ticket,monto_solicitud,online,declaracion) values ('$cedula','$cod_estado_tramite','$cod_tramite','$persona_contacto_dep','$descripcion_ticket','$monto_solicitud','$online','$declaracion') RETURNING cod_ticket";
            $result = pg_query($query)or die(pg_last_error());
            $result_insert_ticket=pg_fetch_row($result);
            $cod_ticket = $result_insert_ticket[0];
            pg_free_result($result);
            
            if ($result_insert_ticket[0]){
                $error="bien";
                $query="insert into detalles_ticket (cod_estado_tramite,cod_ticket,cod_unidad) values ('$cod_estado_tramite','$cod_ticket','$cod_unidad') RETURNING cod_detalle_ticket";
                $result = pg_query($query)or die(pg_last_error());
                $result_insert_detalle_ticket=pg_fetch_row($result);
                $cod_subticket = $result_insert_detalle_ticket[0];
                pg_free_result($result);

                if ($result_insert_detalle_ticket[0]){
                    $query="update ticket set cod_subticket='$cod_subticket' where cod_ticket='$cod_ticket'";
                    $result = pg_query($query)or die(pg_last_error());
                }
            
            
                //// SUBIR ARCHIVO AL SERVIDOR
                if(isset($_FILES['archivo']) ){
                    $name = $_FILES['archivo']['name'];	
                    $name_tmp = $_FILES['archivo']['tmp_name'];
                    $size = $_FILES['archivo']['size'];
                    $type = $_FILES['archivo']['type'];

    //                    $type_file=explode('image/',$_FILES["archivo"]["type"]); // Separamos name y type/ 
    //                    $ext_type_file=$type_file[1]; // Optenemos el tipo del archivo 
    //                    $type_file=explode('application/',$_FILES["archivo"]["type"]); // Separamos name y type/ 

                    $ext_permitidas = array('jpg','jpeg','png','pdf');
                    $part_name = explode('.', $name);
                    $ext_type=$part_name[1]; // Optenemos el tipo del archivo 
                    $ext_correcta = in_array($ext_type, $ext_permitidas);

    //                    $type_correcto = preg_match('/^image\/(pjpeg|jpeg|gif|png)$/', $ext_type);

                    $upload_max = 1000 * 1024; // Tamaño maximo del Archivo en Kb. (1 Mb)
                    $dir_upload='upload_file/'; // Nombre del Directorio de las subidas de archivos
                    $new_name_file=$cod_ticket.'.'.$ext_type;

                    if (is_uploaded_file($_FILES['archivo']['tmp_name'])){
                        if( $ext_correcta && $size <= $upload_max ){
                            if( $_FILES['archivo']['error'] > 0 ){
                              $upload_menssage= 'Error: ' . $_FILES['archivo']['error'].'.';
                            }else{
                               move_uploaded_file($_FILES['archivo']['tmp_name'],$dir_upload .'/'.$new_name_file); 
                               $upload_menssage="Archivo Adjuntado con éxito.";
                               $upload_ok=1;

                               $query="update ticket set name_file_upload='$new_name_file' where cod_ticket='$cod_ticket'";
                               $result = pg_query($query)or die(pg_last_error());
                            }

                        }else{
                            $upload_menssage="Formato ó Tamaño del Archivo es inválido.";
                        }
                    }else{
                        $upload_menssage="Sin Archivo Seleccionado que Subir.";
                    }
                }else{
                    $upload_menssage="Ticket Sin Archivo Adjunto.";
                }


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
            }
        }else{
            $cedula_rif_insert = preg_replace("/\s+/", "", $cedula_rif);
            $cedula_rif_insert = str_replace("-", "", $cedula_rif_insert);
            $cod_tipo_solicitante=$_POST['cod_tipo_solicitante'];
            $nombreapellido=strtoupper($_POST["nombreapellido"]);
            $sexo=$_POST['sexo'];
            $fecha_nac=implode('-',array_reverse(explode('/',$_POST['fecha_nac']))); 
            $direccion=$_POST['direccion'];
            $telefono=$_POST['telefono'];
            $celular=$_POST['celular'];
            $email=$_POST['email'];
            $codcom=$_POST['codcom'];

            $query="SELECT insert_solicitante('$cedula_rif_insert','$cod_tipo_solicitante','$nombreapellido','$sexo','$fecha_nac','$direccion','$telefono','$celular','$email','$codcom')";
            $result = pg_query($query)or die(pg_last_error());
            $result_insert=pg_fetch_array($result);
            pg_free_result($result);

            if ($result_insert[0]==1){
                $error="bien";
                $query="insert into ticket (cedula_rif,cod_estado_tramite,cod_tramite,persona_contacto_dep,descripcion_ticket,monto_solicitud,online,declaracion) values ('$cedula','$cod_estado_tramite','$cod_tramite','$persona_contacto_dep','$descripcion_ticket','$monto_solicitud','$online','$declaracion') RETURNING cod_ticket";
                $result = pg_query($query)or die(pg_last_error());
                $result_insert_ticket=pg_fetch_row($result);
                $cod_ticket = $result_insert_ticket[0];
                pg_free_result($result);
                
                if ($result_insert_ticket[0]){
                    $error="bien";
                    $query="insert into detalles_ticket (cod_estado_tramite,cod_ticket,cod_unidad) values ('$cod_estado_tramite','$cod_ticket','$cod_unidad') RETURNING cod_detalle_ticket";
                    $result = pg_query($query)or die(pg_last_error());
                    $result_insert_detalle_ticket=pg_fetch_row($result);
                    $cod_subticket = $result_insert_detalle_ticket[0];
                    pg_free_result($result);

                    if ($result_insert_detalle_ticket[0]){
                        $query="update ticket set cod_subticket='$cod_subticket' where cod_ticket='$cod_ticket'";
                        $result = pg_query($query)or die(pg_last_error());
                    }
                
                    //// SUBIR ARCHIVO AL SERVIDOR
                    if(isset($_FILES['archivo']) ){
                        $name = $_FILES['archivo']['name'];	
                        $name_tmp = $_FILES['archivo']['tmp_name'];
                        $size = $_FILES['archivo']['size'];
                        $type = $_FILES['archivo']['type'];

        //                    $type_file=explode('image/',$_FILES["archivo"]["type"]); // Separamos name y type/ 
        //                    $ext_type_file=$type_file[1]; // Optenemos el tipo del archivo 
        //                    $type_file=explode('application/',$_FILES["archivo"]["type"]); // Separamos name y type/ 

                        $ext_permitidas = array('jpg','jpeg','png','pdf');
                        $part_name = explode('.', $name);
                        $ext_type=$part_name[1]; // Optenemos el tipo del archivo 
                        $ext_correcta = in_array($ext_type, $ext_permitidas);

        //                    $type_correcto = preg_match('/^image\/(pjpeg|jpeg|gif|png)$/', $ext_type);

                        $upload_max = 1000 * 1024; // Tamaño maximo del Archivo en Kb. (1 Mb)
                        $dir_upload='upload_file/'; // Nombre del Directorio de las subidas de archivos
                        $new_name_file=$cod_ticket.'.'.$ext_type;

                        if (is_uploaded_file($_FILES['archivo']['tmp_name'])){
                            if( $ext_correcta && $size <= $upload_max ){
                                if( $_FILES['archivo']['error'] > 0 ){
                                  $upload_menssage= 'Error: ' . $_FILES['archivo']['error'].'.';
                                }else{
                                   move_uploaded_file($_FILES['archivo']['tmp_name'],$dir_upload .'/'.$new_name_file); 
                                   $upload_menssage="Archivo Adjuntado con éxito.";
                                   $upload_ok=1;

                                   $query="update ticket set name_file_upload='$new_name_file' where cod_ticket='$cod_ticket'";
                                   $result = pg_query($query)or die(pg_last_error());
                                }

                            }else{
                                $upload_menssage="Formato ó Tamaño del Archivo es inválido.";
                            }
                        }else{
                            $upload_menssage="Sin Archivo Seleccionado que Subir.";
                        }
                    }else{
                        $upload_menssage="Ticket Sin Archivo Adjunto.";
                    }


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
        }
    }
?>

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
<div align="left">
    <h4>REGISTRAR SOLICITUD</h4>				
</div>
<br />
<table border="0" width="100%" align="center">
    <tbody>			
        <tr>
            <td  id="msg" align="center">		
                <?php echo $div_menssage;?>
            </td>
        </tr>
    </tbody>
</table>			    					

<form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">		
<table class="adminform"  width="700px" align="center">
	<tr>
            <th colspan="2" align="center">
                <img src="images/add.png" width="16" height="16" alt="Nuevo Registro de Inscripción">
                INFORMACION DE LA SOLICITUD
            </th>
	</tr>
        <?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->
        <tr>
            <td colspan="2" align="center">
                <div align="center"> 
                    <h3 class="info">	
                        <font size="2">						
                            <?php echo 'Ticket Nro.: '.$cod_ticket.' Registrado con &eacute;xito.<br/><font color="#CC0000" style="text-decoration:blink;">'.$upload_menssage.'</font>';?>
                            <br />
                            <!-- <meta http-equiv="refresh" content="3; URL=?view=user<?php echo '&submenuheader='.$submenuheader;?>"> -->
                            <script type="text/javascript">
                                function redireccionar(){
                                    window.location="?view=solicitud_online&cedula=<?php echo $cedula;?>";
                                }  
                                setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                            </script> 						
                            [<a href="?view=solicitud_online&cedula=<?php echo $cedula;?>" name="Continuar"> Continuar </a>]
                        </font>							
                    </h3>
                </div> 
            </td>
	</tr>
	<?php	}else	{ 	?>   <!-- Mostrar formulario Original --> 
	
	<tr>
	   <td  colspan="2"   height="18">
               <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
            </td>
	</tr>
	
						
        <tr>
            <td class="titulo" colspan="2" height="18"  align="left"><b>Información Básica del Solicitante:</b></td>
        </tr>
        
        <?php if ($result_solicitantes[0]){  ?>
        
        <tr>
            <td colspan="2">
                <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td  width="25%" height="22">
                                C&Eacute;DULA / RIF: <font color="Red">(*)</font>
                            </td>
                            <td  height="22">
                                <input type="hidden" id="cedula_rif" name="cedula_rif"  value="<?php echo $cedula_rif;?>" />
                                <input size="10" class="inputbox validate[required]"  readonly="readonly" type="text" name="cedula1"  value="<?php  echo substr_replace($result_solicitantes['cedula_rif'],'-',1,0); ?>" /> 																																						
                            </td>
                        </tr>	

                        <tr>
                            <td>
                                TIPO DE SOLICITANTE: <font color="Red">(*)</font>
                            </td>
                            <td>														
                                <select id="cod_tipo_solicitante" disabled="true" name="cod_tipo_solicitante" class="validate[required]">	
                                    <option value="">----</option>							
                                    <?php 
                                        $consulta_sql=pg_query("SELECT * FROM tipo_solicitantes");
                                        while ($array_consulta=pg_fetch_array($consulta_sql)){
                                            if ($array_consulta[0]==$result_solicitantes['cod_tipo_solicitante']){
                                                    echo '<option value="'.$array_consulta[0].'" selected="selected">'.$array_consulta[1].'</option>';
                                            }else {
                                                    echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                            }
                                        }
                                        pg_free_result($consulta_sql);
                                    ?>
                                </select>														
                            </td>
                        </tr>
                        <tr>
                            <td>
                                NOMBRE DEL SOLICITANTE: <font color="Red">(*)</font>
                            </td>
                            <td>
                                <input class="validate[required] text-input" type="text" id="nombreapellido" readonly="true" name="nombreapellido" value="<?php echo $result_solicitantes[nombre_solicitante];?>" onkeyup="" size="50" maxlength="50"/>																	
                            </td>
                        </tr>

                        <tr>
                            <td>
                                    SEXO: <font color="Red">(*)</font>
                            </td>
                            <td>														
                                <table border="0" >
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select id="sexo" name="sexo" disabled="true"  class="inputbox validate[required]" size="1">
                                                    <?php
                                                        if($result_solicitantes[sexo_solicitante]=="1") {
                                                            echo '<option value="'.$result_solicitantes[sexo_solicitante].'" selected="selected">MASCULINO</option>';
                                                        }elseif($result_solicitantes[sexo_solicitante]=="2") {
                                                            echo '<option value="'.$result_solicitantes[sexo_solicitante].'" selected="selected">FEMENINO</option>';
                                                        }else{
                                                            echo '<option value="'.$result_solicitantes[sexo_solicitante].'" selected="selected">NO APLICA</option>';
                                                        }
                                                    ?>
                                                    <option value="" >---</option>
                                                    <option value="1">MASCULINO</option>
                                                    <option value="2">FEMENINO</option>																						
                                                    <option value="3">NO APLICA</option>																						
                                                </select>														
                                            </td>
                                            <td>
                                                FECHA NATAL/CONSTITUCIÓN: 
                                                <input class="validate[required,custom[date],past[NOW]]" readonly="true"   name="fecha_nac" type="text" value="<?php echo implode('/',array_reverse(explode('-',$result_solicitantes['fecha_nac'])));?>"  id="fecha_nac"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"  />
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
            <td class="titulo" colspan="2" height="18"  align="left"><b>Información de Ubicación del Solicitante:</b></td>
        </tr>

        <tr>
            <td colspan="2">
                <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr >
                            <td width="25%" >
                                PARROQUIA: <font color="Red">(*)</font>
                            </td>
                            <td>		
                                <div id="contenedor3">
                                    <select name="codpar" id="codpar"  disabled="true" class="validate[required]" onchange="cargarContenidoComunidad();" >
                                        <option value="">----</option>
                                        <?php 
                                            $consultax1="SELECT * from parroquias where codest='$result_solicitantes[codest]' and codmun='$result_solicitantes[codmun]' order by codpar";
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                if ($vector[3]==$result_solicitantes[codpar]){
                                                    echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                }else {
                                                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                }
                                            }
                                            pg_free_result($ejec_consultax1);																		
                                        ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr id="comunidades">
                            <td>
                                COMUNIDAD: <font color="Red">(*)</font>
                            </td>
                            <td>		
                                <div id="contenedor4">			
                                    <select name="codcom" id="codcom" disabled="true" class="validate[required]" style="width:180px" >
                                        <option value="">----</option>
                                        <?php 
                                            $consultax1="SELECT * from comunidades where codest='$result_solicitantes[codest]' and codmun='$result_solicitantes[codmun]' and codpar='$result_solicitantes[codpar]'  order by descom";
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                if ($vector[0]==$result_solicitantes[idcom]){
                                                    echo '<option value="'.$vector[0].'" selected="selected">'.$vector[5].'</option>';
                                                }else {
                                                    echo '<option value="'.$vector[0].'">'.$vector[5].'</option>';
                                                }
                                            }
                                            pg_free_result($ejec_consultax1);																		
                                        ?>
                                    </select>
                                </div>
                            </td>
                        </tr>


                        <tr>
                            <td>
                                DIRECCI&Oacute;N DE HABITACI&Oacute;N:  
                            </td>
                            <td>
                                <input type="text" id="direccion" readonly="true" name="direccion" value="<?php echo $result_solicitantes[direccion_habitacion];?>"  size="60" maxlength="150"/>	
                            </td>
                        </tr>


                        <tr>
                            <td>
                                TEL&Eacute;FONO HAB.:
                            </td>
                            <td>														
                                <table border="0" >
                                    <tbody>
                                        <tr>
                                            <td width="130">
                                                <input class="validate[custom[phone]] text-input" readonly="true" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" id="telefono" type="text" name="telefono" size="15" value="<?php echo $result_solicitantes[telefono_fijo];?>" maxlength="15"/>														
                                            </td>
                                            <td>
                                                TEL&Eacute;FONO CEL.:
                                            </td>
                                            <td>
                                                <input class="validate[custom[phone]] text-input" readonly="true" placeholder="(0414)-1234567" title="Ej.: (0414)-1234567" id="celular" type="text" name="celular" size="15" value="<?php echo $result_solicitantes[telefono_movil];?>" maxlength="15"/>														
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                CORREO ELECTR&Oacute;NICO:
                            </td>
                            <td>
                                <input class="validate[custom[email]] text-input" readonly="trues" placeholder="minombre@ejemplo.com" title="Ej.: minombre@ejemplo.com" type="text" id="email" name="email" size="50" value="<?php echo $result_solicitantes[email];?>" maxlength="50"/>																		
                            </td>
                        </tr>
                    </tbody>
                </table>	
            </td>
        </tr>
                
        <?php }else{  ?>
        
        <tr>
        <td colspan="2">
            <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td  width="25%" height="22">
                                C&Eacute;DULA / RIF: <font color="Red">(*)</font>
                        </td>
                        <td>
                            <input size="10" class="inputbox validate[required]"  type="text" id="cedula_rif" name="cedula_rif"  value="<?php echo $cedula_rif;?>" readonly="true" /> 																																						
                        </td>
                    </tr>
                    <tr>
                        <td>
                            TIPO DE SOLICITANTE: <font color="Red">(*)</font>
                        </td>
                        <td>														
                            <select id="cod_tipo_solicitante" name="cod_tipo_solicitante" class="inputbox validate[required]" size="1">
                                <option value="">---</option>
                                <?php 
                                    $consulta_sql=pg_query("SELECT * FROM tipo_solicitantes ");
                                    while ($array_consulta=pg_fetch_array($consulta_sql)){
                                        if ($error!=""){
                                            if ($array_consulta[0]==$cod_tipo_solicitante){
                                                echo '<option value="'.$array_consulta[0].'"  selected="selected">'.$array_consulta[1].'</option>';																			
                                            }else{
                                                echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';																			
                                            }
                                        }else{
                                            echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';																			
                                        }
                                    }																																						
                                    pg_free_result($consulta_sql);								
                                ?>																												
                            </select>														
                        </td>
                    </tr>
                    <tr>
                        <td>
                            NOMBRE DEL SOLICITANTE: <font color="Red">(*)</font>
                        </td>
                        <td>
                            <input class="validate[required] text-input" type="text" id="nombreapellido" name="nombreapellido" value="<?php if ($error!='') echo $nombreapellido;?>"  size="50" maxlength="50"/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            SEXO: <font color="Red">(*)</font>
                        </td>
                        <td>
                            <table border="0" >
                                <tbody>
                                    <tr>
                                        <td>
                                            <select id="sexo" name="sexo" class="inputbox validate[required]" size="1">									
                                                <?php
                                                    if($error!="") {
                                                        if($sexo=="1") {
                                                            echo '<option value="'.$sexo.'" selected="selected">MASCULINO</option>';
                                                        }elseif($sexo=="2") {
                                                            echo '<option value="'.$sexo.'" selected="selected">FEMENINO</option>';
                                                        }else{
                                                            echo '<option value="'.$sexo.'" selected="selected">NO APLICA</option>';
                                                        }
                                                    }																					
                                                ?>
                                                <option value="" >---</option>
                                                <option value="1">MASCULINO</option>
                                                <option value="2">FEMENINO</option>																						
                                                <option value="3">NO APLICA</option>																						
                                            </select>														
                                        </td>
                                        <td>
                                            FECHA NATAL/CONSTITUCIÓN: 
                                            <input class="validate[custom[date],past[NOW]]" name="fecha_nac" type="text" value="<?php if ($error!="") echo implode('/',array_reverse(explode('-',$fecha_nac)));?>"  id="fecha_nac"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"  />
                                            <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_nac,'dd/mm/yyyy',this);">
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
        <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Ubicación del Solicitante:</b></td>
    </tr>

    <tr>
        <td colspan="2">
            <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr >
                        <td width="25%" >
                            PARROQUIA: <font color="Red">(*)</font>
                        </td>
                        <td>		
                            <div id="contenedor3">
                               <input type="hidden" id="codest" name="codest"  value="<?php echo $cod_estado;?>" />
                                <input type="hidden" id="codmun" name="codmun"  value="<?php echo $cod_municipio;?>" />
                                <select name="codpar" id="codpar" class="validate[required]" onchange="cargarContenidoComunidad();" >
                                    <option value="">----</option>
                                    <?php 
                                        $consultax1="SELECT * from parroquias where codest='$cod_estado' and codmun='$cod_municipio' order by codpar";
                                        $ejec_consultax1=pg_query($consultax1);
                                        while($vector=pg_fetch_array($ejec_consultax1)){
                                            echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                        }
                                        pg_free_result($ejec_consultax1);																		
                                    ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr id="comunidades">
                        <td>
                            COMUNIDAD: <font color="Red">(*)</font>
                        </td>
                        <td>		
                            <div id="contenedor4">			
                                <select name="codcom" id="codcom" class="validate[required]" style="width:180px" >
                                    <option value="">----</option>
                                </select>
                            </div>
                        </td>
                    </tr>


                    <tr>
                        <td>
                            DIRECCI&Oacute;N DE HABITACI&Oacute;N:  <font color="Red">(*)</font>
                        </td>
                        <td>
                            <input class="validate[required] text-input" type="text" id="direccion" name="direccion" value="<?php if ($error!='') echo $direccion;?>" onkeyup=""  size="60" maxlength="150"/>	
                        </td>
                    </tr>


                    <tr>
                        <td>
                            TEL&Eacute;FONO HAB.:
                        </td>
                        <td>														
                            <table border="0" >
                                <tbody>
                                    <tr>
                                        <td width="130">
                                            <input class="validate[custom[phone]] text-input" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" id="telefono" type="text" name="telefono" size="15" value="<?php if ($error!='') echo $telefono;?>" maxlength="15"/>														
                                        </td>
                                        <td>
                                            TEL&Eacute;FONO CEL.: <font color="Red">(*)</font>
                                        </td>
                                        <td>
                                            <input class="validate[required,custom[phone]] text-input" placeholder="(0414)-1234567" title="Ej.: (0414)-1234567" id="celular" type="text" name="celular" size="15" value="<?php if ($error!='') echo $celular;?>" maxlength="15"/>														
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            CORREO ELECTR&Oacute;NICO: <font color="Red">(*)</font>
                        </td>
                        <td>
                            <input class="validate[required,custom[email]] text-input" placeholder="minombre@ejemplo.com" title="Ej.: minombre@ejemplo.com" type="text" id="email" name="email" size="50" value="<?php if ($error!='') echo $email;?>" maxlength="50"/>																		
                        </td>
                    </tr>
                </tbody>
            </table>	
        </td>
    </tr>
                
        <?php }  ?>
    <tr>
        <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Ticket de Atención al Soberano:</b></td>
    </tr>

    <tr>
        <td colspan="2">
            <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td>TIPO DE SOLICITUD: <font color="Red">(*)</font></td>
                        <td>
                            <div id="categorias">
                                <select name="cod_categoria" id="cod_categoria" class="validate[required]"  onchange="cargarContenidoTramite();">
                                    <option selected="selected" value="">---</option>
                                    <?php 
                                        $consulta_sql=pg_query("SELECT * FROM categorias WHERE status_categoria=1 and status_categoria_online=1 order by descripcion_categoria");
                                        while ($array_consulta=pg_fetch_array($consulta_sql)){																																				
                                            echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';																			
                                        }																																						
                                        pg_free_result($consulta_sql);								
                                    ?>				
                                </select> 
                                <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Tipo de solicitud','Seleccione la categoria del tipo de solicitud, luego seleccione el tipo de tramite a Registrar',' (Campo Requerido)')">														
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>TRAMITE TIPO DE SOLICITUD: <font color="Red">(*)</font></td>
                        <td>
                            <div id="tramites">
                                <select name="cod_tramite" id="cod_tramite" class="validate[required]" onchange="cargarContenidoTramiteUnidadSol();"  >
                                    <option  selected="selected" value="">---</option>
                                </select>
                            </div>											 
                        </td>
                    </tr>
                    <tr>
                        <td>UNIDAD Ó DEPENDENCIA: <font color="Red">(*)</font></td>
                        <td>
                            <div id="unidades">
                                <select name="cod_unidad" id="cod_unidad" class="validate[required]"  >
                                    <option  selected="selected" value="">---</option>
                                </select>
                            </div>											 
                        </td>
                    </tr>
                     
                    <tr>
                        <td>
                            DESCRIPCION DEL TRAMITE: <font color="Red">(*)</font>
                        </td>
                        <td>
                            <textarea class="validate[required]" name="descripcion_ticket" id="descripcion_ticket" cols="48" rows="3" onkeyup=""></textarea>																	
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            PERSONA CONTACTO / DEP.:
                        </td>
                        <td>
                            <input type="text" id="persona_contacto_dep" name="persona_contacto_dep" value="" onkeyup="" size="50" maxlength="50"/>																	
                        </td>
                    </tr>
                    

                    <tr>
                        <td>MONTO DE SOLICITUD: <font color="Red">(*)</font></td>
                        <td>														         
                             <input  style="text-align:right" type="text" id="monto_solicitud" class="validate[required,custom[number]] text-input"  name="monto_solicitud" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="0.00" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                             <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Monto','Ingrese el monto incluyendo los decimales. ej: 1300.00, El separador decimal es colocado automáticamente por el sistema.',' (Campo Opcional)')">       		
                         </td>
                   </tr>
                   <tr>
                        <td>ADJUNTAR ARCHIVO:</td>
                        <td>														         
                             <input type="file" id="archivo" name="archivo" maxlength="50" size="30">									
                             <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Adjuntar Archivo','Maximo de 1000 KB (1 MB) de tamaño en el Archivo Adjuntar.', '  (Campo Opcional)')">																					
                         </td>
                   </tr>
                </tbody>
            </table>	
        </td>
    </tr>
	   
    <tr>
        <td colspan="2">
            <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                <tbody>												
                    <tr >
                        <td>
                            <input id="declaracion" class="validate[required]" name="declaracion"  type="checkbox">DECLARO
                            QUE LOS DATOS SUMINISTRADOS SON VERDADEROS Y
                            AUTORIZO COMPROBAR LA VERACIDAD DE LOS
                            MISMOS.	
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
            <td colspan="2" class="botones" align="center" >			
                    <input  class="button" type="submit" name="save" value="   Enviar   " />			
                    <input  class="button" type="button" onclick="javascript:window.location.href='?view=solicitud_online'" value="Cerrar" name="cerrar" /> 
            </td>													
    </tr> 
<?php	}  ?>	
</table>
</form>   
<br>	
<script type="text/javascript">
	var dtabs=new ddtabcontent("divsG")
	dtabs.setpersist(true)
	dtabs.setselectedClassTarget("link") //"link" or "linkparent"
	dtabs.init()
</script>		
<script type="text/javascript" >
	jQuery(function($) {
	      $.mask.definitions['~']='[JVGjvg]';
	      //$('#fecha_nac').mask('99/99/9999');
	      //$('#fecha_deposito').mask('99/99/9999');
	      $('#telefono').mask('(9999)-9999999');
	      $('#celular').mask('(9999)-9999999');
	      $('#telefono_trabajo').mask('(9999)-9999999');
	      $('#telefono_fax').mask('(9999)-9999999');
	      $('#rif_iglesia').mask('~-99999999-9');
	      //$('#phoneext').mask("(999) 999-9999? x99999");
	      //$("#tin").mask("99-9999999");
	      //$("#ssn").mask("999-99-9999");
	      //$("#product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("Ha escrito lo siguiente: "+this.val());}});
	      //$("#eyescript").mask("~9.99 ~9.99 999");
	      
	   });
	   
   function ue_buscariglesia()	{		   	
		document.QForm.igl_cod_iglesia_mision.value="";
		document.QForm.igl_nombre_iglesia_mision.value="";			
		
		document.QForm.iglesia_existe.disabled=false;
		document.QForm.iglesia_existe.checked=false;
										
		window.open("catalogos/cat_iglesias.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=565,height=500,left=50,top=50,location=no,resizable=no");
	}	
</script>

	    					
			    				   				
