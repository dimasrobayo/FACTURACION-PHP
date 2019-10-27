
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
        $error="";
    }
	
    if (isset($_POST[save])) {
        $cod_estado=$_POST['codestado'];	
        $cod_municipio=$_POST['codmunicipio'];
        $cod_parroquia=$_POST['codparroquia'];
        
        $codest=$_POST['codestado'];	
        $codmun=$_POST['codmunicipio'];
        $codpar=$_POST['codparroquia'];
        $comunidad=$_POST['comunidad'];

        if (($codmun!="") && ($codest!="") && ($codpar!="") && ($comunidad!="") ) {
            // Consultamos si existe
            $query="Select MAX(codcom) as max_codmun from comunidades where codest='$codest' and codmun='$codmun' and codpar='$codpar'";
            $result = pg_query($query)or die(pg_last_error());
            $result_max=pg_fetch_array($result);
            pg_free_result($result);
            
            $query = "SELECT * FROM comunidades WHERE codest='$codest' and codmun='$codmun' and codpar='$codpar' and descom='$comunidad' ";
            $result = pg_query($query)or die(pg_last_error());
            $result_insert=pg_fetch_array($result);
            pg_free_result($result);

            
            if ($result_insert[0]==0){
                $codcom=str_pad($result_max['max_codmun']+1,3,"0",STR_PAD_LEFT);
                $query="insert into comunidades (codest,codmun,codpar,codcom,descom) values ('$codest','$codmun','$codpar','$codcom','$comunidad')";
                $result = pg_query($query)or die(pg_last_error());
                if(pg_affected_rows($result)){
                    $error="bien";
                    echo "<script type=\"text/javascript\">
                        opener.comunidad_add();
                        close();
                      </script>";
                }
            }else{
                $error="Error";
                $div_menssage='<div align="left">
                        <h3 class="error">
                            <font color="red" style="text-decoration:blink;">
                                Error: Ya Existe una Comunidad con la descripcion: <font color="blue">'.$comunidad.'</font>; por favor verifique los datos!
                            </font>
                        </h3>
                    </div>';
            }
            
        } else {
            $error="Error";
            $div_menssage='<div align="left">
                <h3 class="error">
                    <font color="red" style="text-decoration:blink;">
                        Error: Datos Incompletos, por favor verifique los datos!
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
                    <form method="POST" action="comunidad_add.php" id="QForm" name="QForm" enctype="multipart/form-data">
                    <table class="adminform_cat" width="100%"  align="center">
                        <tbody>
                            <tr>
                                <th align="center">
                                    <img src="../images/add.png" width="16" height="16" alt="Nuevo Registro">
                                    REGISTRO DE COMUNIDAD
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
                                               <td  colspan="2"   height="18">
                                                   <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                                                </td>
                                            </tr>
                                           <tr>
                                                <td class="titulo" colspan="2" height="18"  align="left"><b>Datos de la Comunidad:</b></td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>						
                                                        <tr>
                                                            <td width="15%" align="right">
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
                                                            <td width="15%" align="right">
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
                            //                                                $ejec_consultax1=mysql_query($consultax1);
                            //                                                while($vector=mysql_fetch_array($ejec_consultax1)){                                                
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
                            //                                                mysql_free_result($ejec_consultax1);	
                                                                        }else {
                                                                            echo '<select disabled="true" name="codmun" id="codmun" class="validate[required]" onChange="cargarContenidoParroquia();">';
                                                                            echo '<option value="">----</option>';
                                                                            $consultax1="SELECT * from municipios where codest='$cod_estado' order by codmun";
                            //                                                $ejec_consultax1=mysql_query($consultax1);
                            //                                                while($vector=mysql_fetch_array($ejec_consultax1)){
                                                                            $ejec_consultax1=pg_query($consultax1);
                                                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                                                if ($vector[2]==$cod_municipio){
                                                                                    echo '<option value="'.$vector[2].'" selected="selected">'.$vector[3].'</option>';
                                                                                }else {
                                                                                    echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
                                                                                }
                                                                            }
                                                                            echo '</select>';
                            //                                                mysql_free_result($ejec_consultax1);
                                                                            pg_free_result($ejec_consultax1);
                                                                        }	
                                                                    ?>															
                                                                </div>
                                                            </td>	
                                                        </tr>

                                                        <tr >
                                                            <td width="15%" align="right">
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
                            //                                                $ejec_consultax1=mysql_query($consultax1);
                            //                                                while($vector=mysql_fetch_array($ejec_consultax1)){
                                                                            $ejec_consultax1=pg_query($consultax1);
                                                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                                                if ($vector[3]==$codpar){
                                                                                    echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                                                }else {
                                                                                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                                                }
                                                                            }
                                                                            echo '</select>';
                            //                                                mysql_free_result($ejec_consultax1);	
                                                                            pg_free_result($ejec_consultax1);	
                                                                        }else {
                                                                            echo '<select disabled="true" name="codpar" id="codpar" class="validate[required]" ';
                                                                            echo '<option value="">----</option>';
                                                                            $consultax1="SELECT * from parroquias where codest='$cod_estado' and codmun='$cod_municipio' order by codpar";
                            //                                                $ejec_consultax1=mysql_query($consultax1);
                            //                                                while($vector=mysql_fetch_array($ejec_consultax1)){
                                                                            $ejec_consultax1=pg_query($consultax1);
                                                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                                                if ($vector[3]==$cod_parroquia){
                                                                                    echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                                                }else {
                                                                                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                                                }
                                                                            }
                                                                            echo '</select>';
                            //                                                mysql_free_result($ejec_consultax1);																		
                                                                            pg_free_result($ejec_consultax1);																		
                                                                        } 
                                                                    ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="15%" align="right">
                                                                COMUNIDAD: <font color="Red">(*)</font>
                                                            </td>
                                                            <td>
                                                                <input  type="text" autofocus="true" id="comunidad" name="comunidad" value="<?php if ($error!='') echo $comunidad;?>" class="validate[required] text-input" size="30" maxlength="50"/>
                                                            </td>
                                                        </tr>
                                                    </tbody>
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
    
  		  	
</body>
    
    
    
    
</html>
    
    
        