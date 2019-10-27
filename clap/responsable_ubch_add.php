
<?php
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        exit;
    }
    
    require("../conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    if (isset($_GET["codest"])) { 
        $cod_estado=$_GET["codest"];
        $cod_municipio=$_GET["codmun"];
        $cod_parroquia=$_GET["codpar"];
        $cod_comunidad=$_GET["codcom"];
        $error="";
    }
	
    if (isset($_POST[save])) {
        $cedula_rif=$_POST['cedula_rif'];
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
        $codcom=$_POST['codcomunidad'];

        $query="SELECT insert_solicitante('$cedula_rif_insert',$cod_tipo_solicitante,'$nombreapellido','$sexo','$fecha_nac','$direccion','$telefono','$celular','$email','$codcom')";
        $result = pg_query($query)or die(pg_last_error());
        $result_insert=pg_fetch_array($result);
        pg_free_result($result);
        echo "<script type=\"text/javascript\">
                    opener.document.QForm.ci_ubch.value='$cedula_rif';
                    opener.document.QForm.responsable_ubch.value='$nombreapellido';
                    close();
                </script>";
        
        if ($result_insert[0]==1){
            $error="bien";
        }else{
            $error="Error";
            $div_menssage='<div align="left">
                    <h3 class="error">
                        <font color="red" style="text-decoration:blink;">
                            Error: La Cedula ó RIF Ya existe Registrada, por favor verifique los datos!
                        </font>
                    </h3>
                </div>';
        }
    }//fin del add 
    
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
                    <form method="POST" action="responsable_ubch_add.php" id="QForm" name="QForm" enctype="multipart/form-data">
                    <table class="adminform_cat" width="100%"  align="center">
                        <tbody>
                            <tr>
                                <th align="center">
                                    <img src="../images/add.png" width="16" height="16" alt="Nuevo Registro">
                                    REGISTRO DE LA PERSONA RESPONSABLE
                                </th>
                            </tr>

                            <tr>
                                <td>
                                    <table class="adminform" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <?php if($div_menssage) { ?>
                                            <tr>
                                                <td colspan="2" id="msg" align="center">		
                                                    <?php echo $div_menssage;?>
                                                </td>
                                            </tr>

                                            <?php } ?>

                                            <tr>
                                               <td  colspan="2" height="18">
                                                   <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                                                </td>
                                            </tr>
                                           <tr>
                                                <td class="titulo" colspan="2" height="18"  align="left">
                                                    <b>Datos de la Persona Responsable:</b>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td width="24%">
                                                            ESTADO: <font color="Red">(*)</font>
                                                        </td>

                                                        <td>
                                                            <input type="hidden" id="codestado" name="codestado"  value="<?php echo $cod_estado;?>" />
                                                            <select id="codest" disabled="true"  name="codest" class="validate[required]" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
                                                                <option value="">----</option>
                                                                <?php 
                        //                                            $consulta_sql=mysql_query("SELECT * FROM estados order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
                                                                    $consulta_sql=pg_query("SELECT * FROM estados order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
                        //                                            while ($array_consulta= mysql_fetch_array($consulta_sql)){
                                                                    while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                                                        if ($error!=""){
                                                                            if ($array_consulta[1]==$codest){
                                                                                echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                                            }else {
                                                                                echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                                            }
                                                                        }else {
                                                                            if ($array_consulta[1]==$cod_estado){
                                                                                echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                                            }else {
                                                                                echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                                            }
                                                                        }
                                                                    }
                                                                    pg_free_result($consulta_sql);
                        //                                            mysql_free_result($consulta_sql);
                                                                ?>
                                                            </select>
                                                        </td>	
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            MUNICIPIO: <font color="Red">(*)</font>
                                                        </td>

                                                        <td>
                                                            <div id="contenedor2">
                                                                <input type="hidden" id="codmunicipio" name="codmunicipio"  value="<?php echo $cod_municipio;?>" />
                                                                <?php										
                                                                    if ($error!=""){
                                                                        echo '<select name="codmun" disabled="true" class="validate[required]" id="codmun"  onChange="cargarContenidoParroquia();" onClick="cargarContenidoParroquia();>';
                                                                        echo '<option value="">----</option>';
                                                                        $consultax1="SELECT * from municipios where codest='$codest' order by codmun";                         
                                                                        $ejec_consultax1=pg_query($consultax1);
                                                                        while($vector=pg_fetch_array($ejec_consultax1)){
                                                                            if ($vector[2]==$codmun){
                                                                                echo '<option value="'.$vector[2].'" selected="selected">'.$vector[3].'</option>';
                                                                            }else {
                                                                                echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
                                                                            }
                                                                        }
                                                                        echo '</select>';
                                                                        pg_free_result($ejec_consultax1);
                                                                    }else {
                                                                        echo '<select disabled="true" name="codmun" id="codmun" class="validate[required]" onChange="cargarContenidoParroquia();">';
                                                                        echo '<option value="">----</option>';
                                                                        $consultax1="SELECT * from municipios where codest='$cod_estado' order by codmun";
                                                                        $ejec_consultax1=pg_query($consultax1);
                                                                        while($vector=pg_fetch_array($ejec_consultax1)){
                                                                            if ($vector[2]==$cod_municipio){
                                                                                echo '<option value="'.$vector[2].'" selected="selected">'.$vector[3].'</option>';
                                                                            }else {
                                                                                echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
                                                                            }
                                                                        }
                                                                        echo '</select>';
                                                                        pg_free_result($ejec_consultax1);
                                                                    }	
                                                                ?>															
                                                            </div>
                                                        </td>	
                                                    </tr>

                                                    <tr >
                                                        <td>
                                                            PARROQUIA: <font color="Red">(*)</font>
                                                        </td>

                                                        <td>		
                                                            <div id="contenedor3">
                                                                <input type="hidden" id="codparroquia" name="codparroquia"  value="<?php echo $cod_parroquia;?>" />
                                                                <?php 
                                                                    if ($error!=""){
                                                                        echo '<select disabled="true" name="codpar" id="codpar" class="validate[required]" ';
                                                                        echo '<option value="">----</option>';
                                                                        $consultax1="SELECT * from parroquias where codest='$codest' and codmun='$codmun' order by codpar";
                                                                        $ejec_consultax1=pg_query($consultax1);
                                                                        while($vector=pg_fetch_array($ejec_consultax1)){
                                                                            if ($vector[3]==$codpar){
                                                                                echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                                            }else {
                                                                                echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                                            }
                                                                        }
                                                                        echo '</select>';
                                                                        pg_free_result($ejec_consultax1);	
                                                                    }else {
                                                                        echo '<select disabled="true" name="codpar" id="codpar" class="validate[required]" ';
                                                                        echo '<option value="">----</option>';
                                                                        $consultax1="SELECT * from parroquias where codest='$cod_estado' and codmun='$cod_municipio' order by codpar";
                                                                        $ejec_consultax1=pg_query($consultax1);
                                                                        while($vector=pg_fetch_array($ejec_consultax1)){
                                                                            if ($vector[3]==$cod_parroquia){
                                                                                echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                                            }else {
                                                                                echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                                            }
                                                                        }
                                                                        echo '</select>';
                                                                        pg_free_result($ejec_consultax1);
                                                                    } 
                                                                ?>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            COMUNIDAD: <font color="Red">(*)</font>
                                                        </td>

                                                        <td>
                                                            <div id="contenedor4">
                                                                <input type="hidden" id="codcomunidad" name="codcomunidad"  value="<?php echo $cod_comunidad;?>" />
                                                                <?php 
                                                                    if ($error!=""){
                                                                        echo '<select disabled="true" name="codpar" id="codpar" class="validate[required]" ';
                                                                        echo '<option value="">----</option>';
                                                                        $consultax1="SELECT * from comunidades where codest='$codest' and codmun='$codmun' and iccom='$codcom' order by codcom";
                                                                        $ejec_consultax1=pg_query($consultax1);
                                                                        while($vector=pg_fetch_array($ejec_consultax1)){
                                                                            if ($vector[3]==$codpar){
                                                                                echo '<option value="'.$vector[4].'" selected="selected">'.$vector[5].'</option>';
                                                                            }else {
                                                                                echo '<option value="'.$vector[4].'">'.$vector[5].'</option>';
                                                                            }
                                                                        }
                                                                        echo '</select>';
                                                                        pg_free_result($ejec_consultax1);   
                                                                    }else {
                                                                        echo '<select disabled="true" name="codpar" id="codpar" class="validate[required]" ';
                                                                        echo '<option value="">----</option>';
                                                                        $consultax1="SELECT * from comunidades where codest='$cod_estado' and codmun='$cod_municipio' and idcom='$cod_comunidad' order by codcom";
                                                                        $ejec_consultax1=pg_query($consultax1);
                                                                        while($vector=pg_fetch_array($ejec_consultax1)){
                                                                            if ($vector[3]==$cod_parroquia){
                                                                                echo '<option value="'.$vector[4].'" selected="selected">'.$vector[5].'</option>';
                                                                            }else {
                                                                                echo '<option value="'.$vector[4].'">'.$vector[5].'</option>';
                                                                            }
                                                                        }
                                                                        echo '</select>';
                                                                        pg_free_result($ejec_consultax1);
                                                                    } 
                                                                ?>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            C&Eacute;DULA / RIF: <font color="Red">(*)</font>
                                                        </td>

                                                        <td width="100">
                                                            <input size="10" class="inputbox validate[required]"  type="text" id="cedula_rif" name="cedula_rif"  value="<?php echo $cedula_rif;?>"/>
                                                        </td>
                                                    <tr>

                                                    <tr>
                                                        <td>
                                                            NOMBRE DEL SOLICITANTE: <font color="Red">(*)</font>
                                                        </td>

                                                        <td>
                                                            <input onfocus="getCNE(document.getElementById('cedula_rif').value);" class="validate[required] text-input" type="text" id="nombreapellido" name="nombreapellido" value="<?php if ($error!='') echo $nombreapellido;?>"  size="50" maxlength="50"/>
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
                                                            SEXO: <font color="Red">(*)</font>
                                                        </td>

                                                        <td>
                                                            <table border="0" >
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
                                                                        FECHA NATAL: 
                                                                        <input class="validate[custom[date],past[NOW]]" name="fecha_nac" type="text" value="<?php if ($error!="") echo implode('/',array_reverse(explode('-',$fecha_nac)));?>"  id="fecha_nac"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"  />
                                                                    </td>
                                                                </tr>
                                                            </table>
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
                                                            CORREO ELECTR&Oacute;NICO:
                                                        </td>
                                                        
                                                        <td>
                                                            <input class="validate[custom[email]] text-input" placeholder="minombre@ejemplo.com" title="Ej.: minombre@ejemplo.com" type="text" id="email" name="email" size="50" value="<?php if ($error!='') echo $email;?>" maxlength="50"/>                                                                      
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            DIRECCI&Oacute;N DE HABITACI&Oacute;N:  <font color="Red">(*)</font>
                                                        </td>
                                                        
                                                        <td>
                                                            <input class="validate[required] text-input" type="text" id="direccion" name="direccion" value="<?php if ($error!='') echo $direccion;?>"  size="60" maxlength="150"/>  
                                                        </td>
                                                    </tr>
                                                    </table>	
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="botones" align="center" >
                                                    <input id="submit"  class="button" type="submit" name="save" value="Enviar" />									
                                                    <input class="button"  type="button" onclick="javascript:parent.close();" value="Cerrar" name="cerrar" /> 
                                                </td>		
                                            </tr>	
                                        </tbody>
                                    </table>
                                </td>
                            </tr>	
                        </tbody>
                    </table>
                </form> 
                </td>	 				 			  
            </tr>
        </tbody>
    </table>
    <script type="text/javascript" >
        function ue_marca_add()	{
            var mensaje="";
            miPopup = window.open("../ticket/marca_vehiculo_add.php?status=1","miwin","width=550,height=200,scrollbars=yes,left=50,top=50,location=no,resizable=no");
//            miPopup=window.open("../ticket/marca_vehiculo_add.php?status=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=200,left=50,top=50,location=no,resizable=no");
            miPopup.focus();
        } 	
        
        function marca_add(){
            cargarContenidoMarcaVehiculo();
        } 	   

    </script>  	

    <script type="text/javascript">
        var dtabs=new ddtabcontent("divsG")
        dtabs.setpersist(true)
        dtabs.setselectedClassTarget("link") //"link" or "linkparent"
        dtabs.init()
    </script>       

    <script type="text/javascript" >
        jQuery(function($) {
              $.mask.definitions['~']='[Vv]';
              //$('#fecha_nac').mask('99/99/9999');
              $('#telefono').mask('(9999)-9999999');
              $('#celular').mask('(9999)-9999999');
              $('#cedula_rif').mask('~-9999?99999',{placeholder:" "});
        });
    </script>
</body>  
</html>
    
    
        