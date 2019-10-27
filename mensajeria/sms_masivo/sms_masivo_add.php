<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$type;

    //Conexion a la base de datos
    require("conexion_sms/aut_config.inc.php");
    include("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?>
	
<?php 
if (isset($_POST[save])){
    
    $codigo_red = $_POST['codigo_red'];
    $standar_length = 160;
    $texto=$_POST['texto'];
    $creatorId= $_SESSION['usuario_nombre'];

    $senddateoption=$_POST['senddateoption'];
    switch($senddateoption){
        // Now
        case 'option1':
            $date = date('Y-m-d H:i:s');	
            break;

        // Date and time 
        case 'option2':
            $fecha=implode('-',array_reverse(explode('/',$_POST["fecha"])));
            $hour=$_POST['hour'];
            $minute=$_POST['minute'];
            $date = $fecha." ".$hour.":".$minute.":00";
            break;

        // Delay
        case 'option3':
            $delayhour=$_POST['delayhour'];
            $delayminute=$_POST['delayminute'];
            $date = date('Y-m-d H:i:s', mktime(date('H')+$delayhour, 
                        date('i')+$delayminute, date('s'), date('m'), date('d'), date('Y')));
            break;				
    }

    $messagelength = _get_message_length($texto);
    

    if (($codigo_red==""))
    {
        $error='<div align="left">
            <h3 class="error">
                <font color="red" style="text-decoration:blink;">
                    Error: Datos Incompletos, por favor verifique los datos!
                </font>
            </h3>
        </div>';
    }
    
    if (($codigo_red=="todos")){
        
        require("conexion_sms/aut_config.inc.php");
        $db_conexion=pg_connect("host=$sql_host_sms dbname=$sql_db_sms user=$sql_usuario_sms password=$sql_pass_sms");	
        
        $query="SELECT miembro.telefono_movil FROM red, grupo, detalle_grupo, miembro where red.codigo_red = grupo.codigo_red and grupo.codigo_grupo = detalle_grupo.codigo_grupo and detalle_grupo.cedula_miembro = miembro.cedula_miembro order by miembro.cedula_miembro";
        $consulta=pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
        $total_registros= pg_num_rows($consulta);
        $total_error=0;
        $total_send=0;

        if($messagelength > $standar_length){
            $UDH_length = 7;
            $multipart_length = $standar_length - $UDH_length; 

            // generate UDH
            $UDH = "050003";
            $hex = dechex(mt_rand(0, 255));
            $hex = str_pad($hex, 2, "0", STR_PAD_LEFT);
            $UDH .= strtoupper($hex);

            // split string
            $tmpmsg = _get_message_multipart($texto, $multipart_length);			

            // count part message
            $part = count($tmpmsg);
            if($part < 10) $part = '0'.$part;

            $multipart = 'true'; 
            $UDHP = $UDH.$part.'01';

            // insert first part to outbox and get last outbox ID
            $option = 'multipart';
            $sms= $tmpmsg[0];

            while($resultados = pg_fetch_array($consulta)) {
                $dest = preg_replace("/\s+/", "", $resultados['telefono_movil']);
                $dest = str_replace("(", "", $dest);
                $dest = str_replace(")-", "", $dest);
                $dest = str_replace(".", "", $dest);

                if ( strlen($dest)==11 and ((stristr($dest, '0414') or stristr($dest, '0424') or stristr($dest, '0426') or stristr($dest, '0416') or stristr($dest, '0412') ))){
                    $error="bien";	
                    $query="insert into outbox (UpdatedInDB,InsertIntoDB,SendingDateTime,Class,DestinationNumber,TextDecoded,RelativeValidity,SenderID,Coding,SendingTimeOut,CreatorID,MultiPart,UDH) VALUES(now(),now(),'$date','1','$dest','$sms','-1','','Default_No_Compression',now(),'$creatorId','$multipart','$UDHP')";
                    $result=pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
                    if(pg_affected_rows($result)){ // Verificamos y Cargamos la auditoria
                        $total_send++;	
                    }
                    $outboxid= pg_last_oid($result);
//                    $consulta=pg_query("insert into facturatmp (fecha_factura,hora_factura) values ('now()','now()') RETURNING n_factura") or die('La consulta fall&oacute;: ' . pg_last_error());
//                    $result_consulta=pg_fetch_row($consulta);
//                    $codfacturatmp = $result_consulta[0];
                    // insert the rest part to Outbox Multipart
                    for($i=1; $i<count($tmpmsg); $i++){
                        $tmpmsg[$i];
                        $code = $i+1;
                        if($code < 10) $code = '0'.$code;
                        $secuencia=$i+1;
                        $UDHS=$UDH.$part.''.$code;

                        $query="insert into outbox_multipart (ID,UDH,SequencePosition,Coding,Class,TextDecoded) VALUES('$outboxid','$UDHS','$secuencia','Default_No_Compression','1','$tmpmsg[$i]')";
                        $result=pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
                    }
                }else{
                    $total_error++;
                }
            }
        }else{
            $option = 'single';
            $sms=$texto;
            while($resultados = pg_fetch_array($consulta)) {
                $dest = preg_replace("/\s+/", "", $resultados['telefono_movil']);
                $dest = str_replace("(", "", $dest);
                $dest = str_replace(")-", "", $dest);
                $dest = str_replace(".", "", $dest);
                
                if ( strlen($dest)==11 and ((stristr($dest, '0414') or stristr($dest, '0424') or stristr($dest, '0426') or stristr($dest, '0416') or stristr($dest, '0412') ))){
                    $error="bien";	
//                    $query="INSERT INTO outbox(SendingDateTime,Class,DestinationNumber,TextDecoded,RelativeValidity,SenderID,Coding,SendingTimeOut,CreatorID) VALUES('$date','1','$dest','$sms','-1','','Default_No_Compression',now(),'$creatorId')";								
//                    $query="INSERT INTO outbox(SendingDateTime,DestinationNumber,Class,TextDecoded,CreatorID) VALUES('$date','$dest','1','$sms','$creatorId')";								
                    $query = "SELECT insert_outbox_single('$date','$dest','1','$sms','$creatorId')";
                    $result=pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
                    if(pg_affected_rows($result)){ // Verificamos y Cargamos la auditoria
                        $total_send++;	
                    }
                }else{
                    $total_error++;
                }
            }

        }
    }
    else
    {
        require("conexion/aut_config.inc.php");
        $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

        //consulta para buscar los militantes del grupo seleccionado
        $datos_consulta = pg_query("SELECT miembro.telefono_movil FROM red, grupo, detalle_grupo, miembro where red.codigo_red = grupo.codigo_red and grupo.codigo_grupo = detalle_grupo.codigo_grupo and detalle_grupo.cedula_miembro = miembro.cedula_miembro and red.codigo_red = '$codigo_red' order by miembro.cedula_miembro") or die("No se pudo realizar la consulta a la Base de datos");
        while($resultados = pg_fetch_array($datos_consulta))
        {
            $destino = $resultados[telefono_movil];
            $sms=$_POST['texto'];
            $error="bien";

            //aqui es donde inicia el envio por grupo
            $array_cell=explode(',', $destino);
            foreach ($array_cell as $dest)
            {
                    str_replace('.','',$dest);
                    if ( strlen($dest)==11 and ((stristr($dest, '0414') or stristr($dest, '0424') or stristr($dest, '0426') or stristr($dest, '0416') or stristr($dest, '0412') )))
                    {
                            $datoom=date('Y').date('m').date('d').date('H').date('i').date('s');
                            $creatorId= $_SESSION['usuario_nombre'];
                            $send = pg_query("SELECT insert_outbox('$dest','$sms','$creatorId')") or die('La consulta fall&oacute;: ' . pg_last_error());	
                            $result_insert=pg_fetch_array($send);	
                            pg_free_result($send);
                            $resultado_insert=$result_insert[0];
                            $error="bien";
                            if ($send)
                            {
                                    $out=$out."SMS enviado a $dest<br>";
                            }
                            else
                            {
                                    $out=$out."Error al enviar SMS a $dest<br>";
                            }
                    }
                    else
                    {
                            $out='Número Telefónico no Válido';
                    }
            }
            unset ($GLOBALS);
            //unset ($_POST['cant']);
            //unset($_POST['cant']);
            $sms="";
            $cel="";
            $dest="";
        }
    } 		   
}//fin del add        
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
        
        <table class="admin_sms_masivo">
            <tr>
                <th>
                    SMS-Masivo
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        Envio de un SMS Masivo
                    </th>
                </tr>

                <tr>
                    <td colspan="2" height="16" align="left">
                        <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>
                
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Configuracion del SMS Masivo:</b></td>
                </tr>

                <?php 
                    if ((isset($_POST[save])) and ($error=="bien")) {		
                ?> 

                <tr>
                    <td colspan="2" align="center">                        	
                        <br />
                        <strong>Resultado</strong>: 
                        <?php 
                        switch($resultado_insert)
                        {
                            case 0: 
                                echo 'No se pudo Procesar el Registro porque ya est&aacute; registrado en el sistema.';
                                break;
                            case 1: 
                                echo 'Registro Procesado con &eacute;xito';	
                                break;
                        }				
                        echo '<br />'.$msg;
                        ?>
                        <br />
                    </td>
                </tr> 

                <tr align="center">
                    <td width="100%" valign="top" align="center">
                        <div id="cpanel">
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?type=por_enviar">
                                        <img src="images/sms_por_enviar.png" alt="salir" align="middle"  border="0" />
                                        <span>Gestor de Datos</span>
                                    </a>
                                </div>
                            </div>	
                        </div>
                    </td>
                </tr>

                <?php 
                    }else{
                ?> 

                <?php echo $error;?>

                <tr>
                    <td width="20%" >
                        ESTADO: <font color="Red">(*)</font>
                    </td>
                    <td>
                        <select id="codest" name="codest" class="validate[required]" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
                            <option value="">----</option>
                            <option value="todos">TODOS</option>
                            <?php 
                                $consulta_sql=pg_query("SELECT * FROM estados order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
                                while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                    if ($array_consulta[1]==$cod_estado){
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
                                <option value="todos">TODOS</option>
                                <?php										
                                    $consultax1="SELECT * from municipios where codest='$cod_estado' order by codmun";
                                    $ejec_consultax1=pg_query($consultax1);
                                    while($vector=pg_fetch_array($ejec_consultax1)){
                                        if ($vector[2]==$cod_municipio){
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
                                <option value="todos">TODOS</option>
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
                                <option value="todos">TODOS</option>
                            </select>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Mensaje: <font color="#ff0000">*</font>
                    </td>
                    <td>
                        <div>
                            <textarea class="validate[required]" name="texto" id="texto" rows="5" cols="55"><?php if ($error!="") echo $texto; else echo $title_sms;?></textarea>		
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Enviar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=home'" value="Cerrar" name="cerrar" />  
                    </td>													
                </tr> 
            </table>
        </form>     
            <?php
            }
            ?> 
    </div>
</div>
        
<script type="text/javascript">
    var dtabs=new ddtabcontent("divsG")
    dtabs.setpersist(true)
    dtabs.setselectedClassTarget("link") //"link" or "linkparent"
    dtabs.init()
</script>
