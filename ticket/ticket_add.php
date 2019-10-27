<script>
    jQuery( document ).ready(function( $ ) {
        $.fn.selectOption = function() {

           return this.each(function() { //para cada combo box ejecutamos la siguiente funcion 
            
              id = $(this).attr('id');

              //el "selected" se podria cambiar por "title" u otro atributo si queremos un html mas valido
              val = $(this).attr('set');
              
              //si no hay un id, agregamos uno temporalmente
              if(!id) {
                 id = 'fake_id';
                 $(this).attr('id', 'fake_id');
                 fakeId = true;
              } else {
                 fakeId = false;
              }
                 
              if(val) {
              
                 //y aqui lo mas importante, utilizamos el selector de jquery para buscar el option que necesita
                 //el atributo selected y agregarselo...
                 $('#' + id + ' option[value='+val+']').attr('selected', 'selected');
              }
              
              //eliminamos el id temporal en caso de haberlo utilizado
              if(fakeId) {
                  $(this).removeAttr('id');
              }
              
           });
        }

        window.onload = function() {
            var cedula = $("#cedula_rif").val();
            validarGetName(cedula); //SE CARGAR LA FUNCIÓN AL CARGAR LA PÁGINA
        };

        function validarGetName(cedulaRif){
            var naci = cedulaRif.substring(0, 1); //OBTIENE LA NACIONALIDAD
            var ced = cedulaRif.substring(2, 12); //OBTIENE LA CEDULA O RIF SEA EL CASO

            switch(naci){ //IDENTIFICAR SI ES RIF O CEDULA
                case ('J' || 'G'):
                    getSENIAT(naci,ced);
                break;

                case ('V' || 'E'):
                    getCNE(naci,ced);
                break;
            }
        }


        function getSENIAT(n,c) {
            $.ajax({
                url: "library/Rif.php",
                type: 'POST',
                data: 'rif='+n+'-'+c,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.code_result<0){ //NO OBTUVO RESULTADOS CON EL RIF, SE INSERTA EL NOMBRE MANUAL
                        document.getElementById("nombreapellido").readOnly = false;
                    } else {
                        $('input[name="nombreapellido"]').val(obj.seniat.nombre); //SE ASIGNA LOS NOMBRES OBTENIDOS DEL RIF
                        document.getElementById("nombreapellido").readOnly = false;
                    }

                }
            })
        }


        function getCNE(n,c){
            $.ajax({
                url: "library/getCNE.php",
                type: 'POST',
                data: 'nacionalidad='+n+'&cedula='+c,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if (obj.inscrito == "NO") { // SINO EXISTE EN EL CNE, SE CALCULA EL RIF Y SE ENVIA A GETSENIAT
                        $.ajax({
                            url: "library/setRif.php",
                            type: 'POST',
                            data: 'nacionalidad='+n+'&cedula='+c,
                            success: function(data) {
                                var naci = data.substring(0, 1);
                                var ced1 = data.substring(1, 9)+"-"+data.substring(9, 10);
                                getSENIAT(naci,ced1); //SE ENVIA A GETSENIAT
                            }
                        });
                    } else { // SI EXISTE EL REGISTRO ASIGNA EL NOMBRE, ESTADO, MUNICIPIO Y PARROQUIA
                        document.getElementById("nombreapellido").readOnly = false;
                        $('input[name="nombreapellido"]').val(obj.nombres+" "+obj.apellidos);
                        var estado, parroquia, municipio;
                        estado = obj.cvestado.substring(5,20);
                        municipio = obj.cvmunicipio.substring(4,20);
                        parroquia = obj.cvparroquia.substring(4,20);
                        //console.log("ESTADO: "+estado);
                        //console.log("MUNICIPIO: "+municipio);
                        //console.log("PARROQUIA: "+parroquia);

                        setTimeout(function() {
                            $.ajax({
                                url: "library/localidad.php",
                                type: 'POST',
                                data: 'estado='+estado,
                                success: function(data) {
                                    //console.log("1. "+data);
                                    $('#codest').attr('set',data);
                                    $("#codest").selectOption();
                                    cargarContenidoMunicipio();
                                }   
                            });
                        }, 0);

                        setTimeout(function() {
                            $.ajax({
                                url: "library/localidad.php",
                                type: 'POST',
                                data: 'municipio='+municipio,
                                success: function(data) {
                                    //console.log("2. "+data);
                                    $('#codmun').attr('set',data);
                                    $("#codmun").selectOption();
                                    cargarContenidoParroquia();
                                }   
                            });
                        }, 200);

                        setTimeout(function() {
                            $.ajax({
                                url: "library/localidad.php",
                                type: 'POST',
                                data: 'parroquia='+parroquia,
                                success: function(data) {
                                    $("#codmun").selectOption();
                                    //console.log("3. "+data);
                                    $('#codpar').attr('set',data);
                                    $("#codpar").selectOption();
                                    cargarContenidoComunidad();
                                }   
                            });
                        }, 500);
                    } //FIN ELSE
                } //FIN DEL SUCCES AJAX
            }); //FIN AJAX
        }
    });
</script>

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
        $cod_tramite=strtoupper($_POST["cod_tramite"]);
        $cod_unidad=strtoupper($_POST["cod_unidad"]);
        $persona_contacto_dep=strtoupper($_POST["persona_contacto_dep"]);
        $descripcion_ticket=strtoupper($_POST["descripcion_ticket"]);
        $monto_solicitud=strtoupper($_POST["monto_solicitud"]);
        $prioridad=$_POST['prioridad'];
        if($_POST['cod_vehiculo']!="") $cod_vehiculo=$_POST['cod_vehiculo']; else $cod_vehiculo=0;
        
        
        
        //DATOS DEL SOLICITANTE
        $cod_tipo_solicitante=$_POST['cod_tipo_solicitante'];
        $nombreapellido=strtoupper($_POST["nombreapellido"]);
        $sexo=$_POST['sexo'];
        $fecha_nac=implode('-',array_reverse(explode('/',$_POST['fecha_nac']))); 
        $direccion=$_POST['direccion'];
        $telefono=$_POST['telefono'];
        $celular=$_POST['celular'];
        $email=$_POST['email'];
        $codcom=$_POST['codcom'];
        
        if ($result_solicitantes[0]){
            $query="SELECT update_solicitante('$cedula','$cod_tipo_solicitante','$nombreapellido','$sexo','$fecha_nac','$direccion','$telefono','$celular','$email','$codcom','now()')";
            $result = pg_query($query)or die(pg_last_error());
            $result_update_solicitante=pg_fetch_array($result);
            pg_free_result($result);
        }else{
            $query="SELECT insert_solicitante('$cedula','$cod_tipo_solicitante','$nombreapellido','$sexo','$fecha_nac','$direccion','$telefono','$celular','$email','$codcom')";
            $result = pg_query($query)or die(pg_last_error());
            $result_insert_solicitante=pg_fetch_array($result);
            pg_free_result($result);
        }
        
        //CONSULTAR DATOS DE LA EMPRESA
        $query="SELECT * FROM empresa where rif_empresa = '$id_empresa'";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_empresa=pg_fetch_array($result);	
        pg_free_result($result);

        $send_sms=$resultados_empresa[send_sms];
        $send_email=$resultados_empresa[send_email];
        $sms=$resultados_empresa[sms_nueva_solicitud];
        // FIN CONSULTA DE EMPRESA
        
        $query="insert into ticket (cedula_rif,cod_tramite,persona_contacto_dep,descripcion_ticket,monto_solicitud,prioridad_ticket,id_vehiculo) values ('$cedula','$cod_tramite','$persona_contacto_dep','$descripcion_ticket','$monto_solicitud','$prioridad','$cod_vehiculo') RETURNING cod_ticket";
        $result = pg_query($query)or die(pg_last_error());
        $result_insert=pg_fetch_row($result);
        $cod_ticket = $result_insert[0];
        pg_free_result($result);

        if ($result_insert[0]){
            $error="bien";
            $query="insert into detalles_ticket (cod_ticket,cod_unidad,user_login) values ('$cod_ticket','$cod_unidad','$_SESSION[user]') RETURNING cod_detalle_ticket";
            $result = pg_query($query)or die(pg_last_error());
            $result_insert_detalle=pg_fetch_row($result);
            $cod_subticket = $result_insert_detalle[0];
            pg_free_result($result);

            if ($result_insert_detalle[0]){
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
            
            $query="select * from unidades where cod_unidad='$cod_unidad'";
            $result = pg_query($query)or die(pg_last_error());
            $result_unidad=pg_fetch_array($result);	
            pg_free_result($result);
            
            $query="select * from estados_tramites where cod_estado_tramite='2'";
            $result = pg_query($query)or die(pg_last_error());
            $result_estado_tramite=pg_fetch_array($result);	
            pg_free_result($result);
            
            
            /// ENVIAR EMAIL A UNIDAD
            if($send_email==1){
                if($result_unidad[email_unidad]!="") {
                    require ("aut_sys_config_email_gmail.inc.php"); //consultar datos de variable
                    $body="Saludos de la Oficina de Atención al Soberano,<br><br> ".
                            " Se ha registrado un Ticket en el Sistema de Atención Al Ciudadano (SAC). <br> ".
                            " A continuación los detalles: <br><br> ".
                            " <strong>Ticket Nro.:</strong> ".str_pad($cod_ticket,10,"0",STR_PAD_LEFT)."<br> ".
                            " <strong>Descripción de la Solicitud:</strong> $descripcion_ticket<br> ".
                            " <strong>Monto de la Solicitud:</strong> $monto_solicitud<br><br>".
                            " <strong>Estado actual del Tramite:</strong> $result_estado_tramite[descripcion_estado_tramite]<br><br>".
                            " <a href=\"$ip_server/$dir_name/reportes/imprimir_tac_online.php?cod_ticket=$cod_ticket\" target=\"_blank\"> ".
                            " Pulse aquí para visualizar el Ticket</a><br><br>".
                            " No responda a este mensaje ya que ha sido generado automáticamente para su información.";						
                    $mail->Subject    = "Ticket Nro.: ".str_pad($cod_ticket,10,"0",STR_PAD_LEFT);
                    $mail->AltBody    = "Detalles del Ticket de Solicitud!"; // optional, comment out and test
                    $mail->MsgHTML($body);						
                    $mail->AddAddress($result_unidad[email_unidad], $result_unidad[nombre_unidad]);
                    $mail->Send();
                    $mail->ClearAddresses();
                }
                //// ENVIAR EMAIL A SOLICITANTE
                if($email!="") {
                    require ("aut_sys_config_email_gmail.inc.php"); //consultar datos de variable
                    $body="Saludos de la Oficina de Atención al Soberano,<br><br> ".
                            " Se ha registrado un Ticket en el Sistema de Atención Al Ciudadano (SAC). <br> ".
                            " A continuación los detalles: <br><br> ".
                            " <strong>Ticket Nro.:</strong> ".str_pad($cod_ticket,10,"0",STR_PAD_LEFT)."<br> ".
                            " <strong>Descripción de la Solicitud:</strong> $descripcion_ticket<br> ".
                            " <strong>Monto de la Solicitud:</strong> $monto_solicitud<br><br>".
                            " <strong>Estado actual del Tramite:</strong> $result_estado_tramite[descripcion_estado_tramite]<br><br>".
                            " <a href=\"$ip_server/$dir_name/reportes/imprimir_tac_online.php?cod_ticket=$cod_ticket\" target=\"_blank\"> ".
                            " Pulse aquí para visualizar el Ticket</a><br><br>".
                            " No responda a este mensaje ya que ha sido generado automáticamente para su información.";						
                    $mail->Subject    = "Ticket Nro.: ".str_pad($cod_ticket,10,"0",STR_PAD_LEFT);
                    $mail->AltBody    = "Detalles del Ticket de Solicitud!"; // optional, comment out and test
                    $mail->MsgHTML($body);						
                    $mail->AddAddress($email, $nombreapellido);
                    $mail->Send();
                    $mail->ClearAddresses();
                }
                
                
            }
            //// ENVIAR SMS AL SOLICITANTE
            if($send_sms==1){
                //INFORMACION PARA EL ENVIO DE SMS
                $destino=$celular;
                $sms=$sms.'; Ticket Nro.: '.$cod_ticket;
                $creatorId=$_SESSION['user'];

                //Conexion a la base de datos
                require("conexion_sms/aut_config.inc.php");
                $db_conexion=pg_connect("host=$sql_host_sms dbname=$sql_db_sms user=$sql_usuario_sms password=$sql_pass_sms");	

                $dest = preg_replace("/\s+/", "", $destino);
                $dest = str_replace("(", "", $dest);
                echo $dest = str_replace(")-", "", $dest);

                $total_send=0;
                if ( strlen($dest)==11 and ((stristr($dest, '0414') or stristr($dest, '0424') or stristr($dest, '0426') or stristr($dest, '0416') or stristr($dest, '0412') ))){
                    $error="bien";	
                    $query="SELECT insert_outbox('$dest','$sms','$creatorId')";								
                    $result = pg_query($query)or die(pg_last_error());
                    if(pg_affected_rows($result)){ 
                        echo $total_send++;	
                    }
                    pg_free_result($result);
                }

            }// FIN ENVIO SMS   
        }
        
        
        
    }
?>

<?php if (!$result_solicitantes[0]){ ?>
<script>
    jQuery( document ).ready(function( $ ) {
        $.fn.selectOption = function() {

           return this.each(function() { //para cada combo box ejecutamos la siguiente funcion 
            
              id = $(this).attr('id');

              //el "selected" se podria cambiar por "title" u otro atributo si queremos un html mas valido
              val = $(this).attr('set');
              
              //si no hay un id, agregamos uno temporalmente
              if(!id) {
                 id = 'fake_id';
                 $(this).attr('id', 'fake_id');
                 fakeId = true;
              } else {
                 fakeId = false;
              }
                 
              if(val) {
              
                 //y aqui lo mas importante, utilizamos el selector de jquery para buscar el option que necesita
                 //el atributo selected y agregarselo...
                 $('#' + id + ' option[value='+val+']').attr('selected', 'selected');
              }
              
              //eliminamos el id temporal en caso de haberlo utilizado
              if(fakeId) {
                  $(this).removeAttr('id');
              }
              
           });
        }

        window.onload = function() {
            var cedula = $("#cedula_rif").val();
            validarGetName(cedula); //SE CARGAR LA FUNCIÓN AL CARGAR LA PÁGINA
        };

        function validarGetName(cedulaRif){
            var naci = cedulaRif.substring(0, 1); //OBTIENE LA NACIONALIDAD
            var ced = cedulaRif.substring(2, 12); //OBTIENE LA CEDULA O RIF SEA EL CASO

            switch(naci){ //IDENTIFICAR SI ES RIF O CEDULA
                case ('J' || 'G'):
                    getSENIAT(naci,ced);
                break;

                case ('V' || 'E'):
                    getCNE(naci,ced);
                break;
            }
        }


        function getSENIAT(n,c) {
            $.ajax({
                url: "library/Rif.php",
                type: 'POST',
                data: 'rif='+n+'-'+c,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.code_result<0){ //NO OBTUVO RESULTADOS CON EL RIF, SE INSERTA EL NOMBRE MANUAL
                        document.getElementById("nombreapellido").readOnly = false;
                    } else {
                        $('input[name="nombreapellido"]').val(obj.seniat.nombre); //SE ASIGNA LOS NOMBRES OBTENIDOS DEL RIF
                        document.getElementById("nombreapellido").readOnly = false;
                    }

                }
            })
        }


        function getCNE(n,c){
            $.ajax({
                url: "library/getCNE.php",
                type: 'POST',
                data: 'nacionalidad='+n+'&cedula='+c,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if (obj.inscrito == "NO") { // SINO EXISTE EN EL CNE, SE CALCULA EL RIF Y SE ENVIA A GETSENIAT
                        $.ajax({
                            url: "library/setRif.php",
                            type: 'POST',
                            data: 'nacionalidad='+n+'&cedula='+c,
                            success: function(data) {
                                var naci = data.substring(0, 1);
                                var ced1 = data.substring(1, 9)+"-"+data.substring(9, 10);
                                getSENIAT(naci,ced1); //SE ENVIA A GETSENIAT
                            }
                        });
                    } else { // SI EXISTE EL REGISTRO ASIGNA EL NOMBRE, ESTADO, MUNICIPIO Y PARROQUIA
                        document.getElementById("nombreapellido").readOnly = false;
                        $('input[name="nombreapellido"]').val(obj.nombres+" "+obj.apellidos);
                        var estado, parroquia, municipio;
                        estado = obj.cvestado.substring(5,20);
                        municipio = obj.cvmunicipio.substring(4,20);
                        parroquia = obj.cvparroquia.substring(4,20);
                        //console.log("ESTADO: "+estado);
                        //console.log("MUNICIPIO: "+municipio);
                        //console.log("PARROQUIA: "+parroquia);

                        setTimeout(function() {
                            $.ajax({
                                url: "library/localidad.php",
                                type: 'POST',
                                data: 'estado='+estado,
                                success: function(data) {
                                    //console.log("1. "+data);
                                    $('#codest').attr('set',data);
                                    $("#codest").selectOption();
                                    cargarContenidoMunicipio();
                                }   
                            });
                        }, 0);

                        setTimeout(function() {
                            $.ajax({
                                url: "library/localidad.php",
                                type: 'POST',
                                data: 'municipio='+municipio,
                                success: function(data) {
                                    //console.log("2. "+data);
                                    $('#codmun').attr('set',data);
                                    $("#codmun").selectOption();
                                    cargarContenidoParroquia();
                                }   
                            });
                        }, 200);

                        setTimeout(function() {
                            $.ajax({
                                url: "library/localidad.php",
                                type: 'POST',
                                data: 'parroquia='+parroquia,
                                success: function(data) {
                                    $("#codmun").selectOption();
                                    //console.log("3. "+data);
                                    $('#codpar').attr('set',data);
                                    $("#codpar").selectOption();
                                    cargarContenidoComunidad();
                                }   
                            });
                        }, 500);
                    } //FIN ELSE
                } //FIN DEL SUCCES AJAX
            }); //FIN AJAX
        }
    });
</script>


<?php } ?>

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
        <table class="admintickets" width="100%">
            <tr>
                <th>
                    NUEVA SOLICITUD DE ATENCI&Oacute;N
                </th>
            </tr>
        </table>

        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">		
            <table class="adminform"  width="100%" align="center">
                <tr>
                    <th colspan="2" align="center">
                        <img src="images/add.png" width="16" height="16" alt="Nuevo Registro de Ticket">
                        INFORMACI&Oacute;N DE LA SOLICITUD
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
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=gestion_tickets<?php echo '&cod_ticket='.$cod_ticket;?>";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=gestion_tickets<?php echo '&cod_ticket='.$cod_ticket;?>" name="Continuar"> Continuar </a>]
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

                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <?php 
                                        if($result_solicitantes[0]){
                                            echo '<input type="hidden" id="solicitanteload" name="solicitanteload"  value="1" />';
                                        }else{
                                            echo '<input type="hidden" id="solicitanteload" name="solicitanteload"  value="0" />';
                                        }
                                    ?>
                                    <td  width="20%" height="22">
                                        C&Eacute;DULA / RIF: <font color="Red">(*)</font>
                                    </td>
                                    <td  height="22">
                                        <input type="hidden" id="cedula_rif" name="cedula_rif"  value="<?php echo $cedula_rif;?>" />
                                        <input size="10" class="inputbox validate[required]"  readonly="readonly" type="text" name="cedula1"  value="<?php echo $cedula_rif;?>" /> 																																						
                                    </td>
                                </tr>	

                                <tr>
                                    <td>
                                        TIPO DE SOLICITANTE: <font color="Red">(*)</font>
                                    </td>
                                    <td>														
                                        <select id="cod_tipo_solicitante" name="cod_tipo_solicitante" class="validate[required]">	
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
                                        <input onfocus="getCNE(document.getElementById('cedula_rif').value);" class="validate[required] text-input" type="text" id="nombreapellido" name="nombreapellido" value="<?php echo $result_solicitantes[nombre_solicitante];?>" onkeyup="" size="50" maxlength="50"/>																	
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
                                                        <select id="sexo" name="sexo"  class="inputbox validate[required]" size="1">
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
                                                        <input class="validate[custom[date],past[NOW]]"  name="fecha_nac" type="text" value="<?php echo implode('/',array_reverse(explode('-',$result_solicitantes['fecha_nac'])));?>"  id="fecha_nac"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"  />
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
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información de Ubicación del Solicitante:</b></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="20%" >
                                        ESTADO: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <select id="codest" name="codest" class="validate[required]" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
                                            <option value="">----</option>
                                            <?php 
                                                $consulta_sql=pg_query("SELECT * FROM estados order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
                                                while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                                    if ($array_consulta[1]==$result_solicitantes[codest]){
                                                        echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                    }else {
                                                        echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                    }
                                                }
                                                pg_free_result($consulta_sql);
                                            ?>
                                        </select>
                                    </td>	
                                </tr>

                                <tr>
                                    <td width="15%" >
                                        MUNICIPIO: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <div id="contenedor2">
                                            <select name="codmun" id="codmun" class="validate[required]" onChange="cargarContenidoParroquia();">
                                                <option value="">----</option>
                                                <?php										
                                                    $consultax1="SELECT * from municipios where codest='$result_solicitantes[codest]' order by codmun";
                                                    $ejec_consultax1=pg_query($consultax1);
                                                    while($vector=pg_fetch_array($ejec_consultax1)){
                                                        if ($vector[2]==$result_solicitantes[codmun]){
                                                            echo '<option value="'.$vector[2].'" selected="selected">'.$vector[3].'</option>';
                                                        }else {
                                                            echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
                                                        }
                                                    }
                                                    pg_free_result($ejec_consultax1);
                                                ?>
                                            </select>
                                        </div>
                                    </td>	
                                </tr>

                                <tr >
                                    <td width="15%" >
                                        PARROQUIA: <font color="Red">(*)</font>
                                    </td>
                                    <td>		
                                        <div id="contenedor3">
                                            <select name="codpar" id="codpar" class="validate[required]" onchange="cargarContenidoComunidad();" >
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
                                        COMUNIDAD: 
                                    </td>
                                    <td>		
                                        <div id="contenedor4">			
                                            <select name="codcom" id="codcom" class="text-input" style="width:180px" >
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
                                        DIRECCI&Oacute;N DE HABITACI&Oacute;N:  <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <input class="validate[required] text-input" type="text" id="direccion" name="direccion" value="<?php echo $result_solicitantes[direccion_habitacion];?>"  size="60" maxlength="150"/>	
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
                                                        <input class="validate[custom[phone]] text-input" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" id="telefono" type="text" name="telefono" size="15" value="<?php echo $result_solicitantes[telefono_fijo];?>" maxlength="15"/>														
                                                    </td>
                                                    <td>
                                                        TEL&Eacute;FONO CEL.: <font color="Red">(*)</font>
                                                    </td>
                                                    <td>
                                                        <input class="validate[required, custom[phone]] text-input" placeholder="(0414)-1234567" title="Ej.: (0414)-1234567" id="celular" type="text" name="celular" size="15" value="<?php echo $result_solicitantes[telefono_movil];?>" maxlength="15"/>														
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
                                        <input class="validate[custom[email]] text-input" placeholder="minombre@ejemplo.com" title="Ej.: minombre@ejemplo.com" type="text" id="email" name="email" size="50" value="<?php echo $result_solicitantes[email];?>" maxlength="50"/>																		
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
                                        PERSONA CONTACTO / DEP.:
                                    </td>
                                    <td>
                                        <input type="text" id="persona_contacto_dep" name="persona_contacto_dep" value="" onkeyup="" size="50" maxlength="50"/>																	
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
                                    <td>UNIDAD INICIAL DE ASIGNACIÓN <font color="Red">(*)</font></td>
                                    <td >
                                        <div id="unidades">
                                            <select name="cod_unidad" id="cod_unidad" class="validate[required]"  onchange="cargarContenidoTramiteUnidad();">
                                                <option selected="selected" value="">---</option>
                                                <?php 
                                                    $consulta_sql=pg_query("SELECT * FROM unidades where status_unidad=1 order by nombre_unidad");
                                                    while ($array_consulta=pg_fetch_array($consulta_sql)){																																				
                                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';																			
                                                    }																																						
                                                    pg_free_result($consulta_sql);								
                                                ?>				
                                            </select> 
                                            <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Unidad de Asignación','Seleccione la Unidad Inicial a la que se asigna el Ticket, luego seleccione el tipo de tramite a Registrar',' (Campo Requerido)')">														
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TIPO DE TRAMITE: <font color="Red">(*)</font></td>
                                    <td >
                                        <div id="tramites">
                                            <select name="cod_tramite" id="cod_tramite" class="validate[required]"  >
                                                <option  selected="selected" value="">---</option>
                                            </select>
                                        </div>											 
                                    </td>
                                </tr>
                                
                                
                                <tr>
                                    <td>MONTO DE SOLICITUD: </td>
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
                               <tr>
                                    <td>PRIORIDAD DEL TICKET:</td>
                                    <td>														         
                                        <input id="prioridad" class="validate[required] radio" name="prioridad" value="1" checked="true" type="radio"> NORMAL
                                        <input id="prioridad" class="validate[required] radio" name="prioridad" value="2"  type="radio"> <font color="ffd200">ALTA</font>
                                        <input id="prioridad" class="validate[required] radio" name="prioridad" value="3" type="radio"> <font color="Red">URGENTE</font>
                                     </td>
                               </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input  class="button" type="submit" name="save" value="   Enviar   " />			
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=tickets'" value="Cerrar" name="cerrar" /> 
                    </td>													
                </tr> 
                
                <?php	}  ?>	
            </table>
        </form>   
        <br>
    </div>
</div>

<script type="text/javascript">
	var dtabs=new ddtabcontent("divsG")
	dtabs.setpersist(true)
	dtabs.setselectedClassTarget("link") //"link" or "linkparent"
	dtabs.init()
</script>		
<script type="text/javascript" >
    jQuery(function($) {
          $.mask.definitions['~']='[JEVGDCHjevgdch]';
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
       
    function ue_vehiculo_add()	{
        var mensaje="";
        var ced_rif=document.QForm.cedula_rif.value;
        var nombreapellido=document.QForm.nombreapellido.value;
        var solicitanteload=document.QForm.solicitanteload.value;
        var ced_rif=ced_rif.toUpperCase();
        window.open("ticket/vehiculo_add.php?cedula_rif="+ced_rif+"&nombreapellido="+nombreapellido+"&solicitanteload="+solicitanteload,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=350,left=50,top=50,location=no,resizable=no");
        
    } 	   
    function vehiculo_add(){
        cargarContenidoVehiculo();
    } 	   
    function ue_catalogo_tramite()	{
        var mensaje="";
        var unidad=document.QForm.cod_unidad.options[document.QForm.cod_unidad.selectedIndex].value;
        window.open("ticket/catalogo_productos.php?cod_uni="+unidad,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=350,left=50,top=50,location=no,resizable=no");
    } 	   
</script>