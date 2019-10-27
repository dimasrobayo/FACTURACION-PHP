
<?php
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        exit;
    }
    
    require("../conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    if (isset($_GET["cedula_rif"])) { 
        $cedula_rif=$_GET["cedula_rif"];
        $nombreapellido=$_GET["nombreapellido"];
        $solicitanteload=$_GET["solicitanteload"];
        $cedula_rif_insert = preg_replace("/\s+/", "", $cedula_rif);
        $cedula_rif_insert = str_replace("-", "", $cedula_rif_insert);
        
        $query="SELECT * FROM solicitantes WHERE cedula_rif='$cedula_rif_insert'";
        $resulta = pg_query($query)or die(pg_last_error());
        $result_consulta=pg_fetch_array($resulta);
        pg_free_result($resulta);
        
        $error="";

		//$query="INSERT INTO marca_vehiculo (nombre_marca,status) VALUES ('VENIRAUTO',1)";
        //$resulta = pg_query($query)or die(pg_last_error());
		
		//$query="UPDATE vehiculo_solicitante SET placa='22A16AP' WHERE placa = '22A18AP' ";
        //$resulta = pg_query($query)or die(pg_last_error());
	
    }
	
    if (isset($_POST[save])){
        $cedula_rif=$_POST['cedula_rif'];
        $cedula_rif_insert=$_POST['cedula'];
        $nombreapellido=$_POST["nombreapellido"];
        $solicitanteload=$_POST["solicitanteload"];
        $placa=$_POST['placa'];
        $marca=$_POST['marca'];
        $modelo=$_POST['modelo'];
        $year=$_POST[year];
        $color=$_POST['color'];
        $serialc=$_POST['serial_carro'];
        
        
        
        // Consultamos si existe
        $query = "SELECT * FROM vehiculo_solicitante WHERE serial_carroceria='$serialc' or  (serial_carroceria='$serialc' and cedula_rif='$cedula_rif_insert')";
        $result = pg_query($query)or die(pg_last_error());
        $result_insert=pg_fetch_array($result);
        pg_free_result($result);
//        
//        $query="SELECT insert_solicitante('$cedula_rif_insert','$cod_tipo_solicitante','$nombreapellido','$sexo','$fecha_nac','$direccion','$telefono','$celular','$email','$codcom')";
//        $result = pg_query($query)or die(pg_last_error());
//        $result_insert=pg_fetch_array($result);
//        pg_free_result($result);
        
        if ($result_insert[0]==0){                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
            
            $error="bien";
            $query="INSERT INTO vehiculo_solicitante (cedula_rif,cod_marca,modelo,placa,year,color,serial_carroceria) VALUES ('$cedula_rif_insert','$marca','$modelo','$placa',$year,'$color','$serialc')";				
            $result = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
            $resultado_carro=  pg_fetch_array($result);
            echo "<script type=\"text/javascript\">
                    opener.vehiculo_add();
                    close();
                  </script>";
//            cargarContenidoTramiteUnidad();
        }else{
            if ($solicitanteload==1){
                $query="SELECT * FROM solicitantes WHERE cedula_rif='$cedula_rif_insert'";
                $resulta = pg_query($query)or die(pg_last_error());
                $result_consulta=pg_fetch_array($resulta);
                pg_free_result($resulta);

                $error="Error";
                $div_menssage='<div align="left"><h3 class="error"><font color="#CC0000" style="text-decoration:blink;">Error: Ya Existe un Vehiculo para el Solicitante con el Nro de carroceria.: <font color="blue">'.$serialc.'</font>; por favor verifique los datos!</font></h3></div>';				
            }else{
                $query="SELECT * FROM solicitantes WHERE cedula_rif='$result_insert[cedula_rif]'";
                $resulta = pg_query($query)or die(pg_last_error());
                $result_consulta=pg_fetch_array($resulta);
                pg_free_result($resulta);

                $error="Error";
                $div_menssage='<div align="left"><h3 class="error"><font color="#CC0000" style="text-decoration:blink;">Error: Ya Existe un Vehiculo con el Nro de carroceria.: <font color="blue">'.$serialc.'</font>; para otro solicitante: <font color="blue">'.$result_consulta[nombre_solicitante].'</font>; por favor verifique los datos!</font></h3></div>';				
                
            }
        }
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
                    <form method="POST" action="vehiculo_add.php" id="QForm" name="QForm" enctype="multipart/form-data">
                    <input id="cedula" name="cedula" value="<?php echo $cedula_rif_insert?>" type="hidden"/>
                    <input id="nombreapellido" name="nombreapellido" value="<?php echo $nombreapellido?>" type="hidden"/>
                    <input id="solicitanteload" name="solicitanteload" value="<?php echo $solicitanteload?>" type="hidden"/>
                    <table class="adminform_cat" width="100%"  align="center">
                        <tbody>
                            <tr>
                                <th align="center">
                                    <img src="../images/add.png" width="16" height="16" alt="Nuevo Registro">
                                    REGISTRO DE VEHICULOS
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
                                                <td class="titulo" colspan="2" height="18"  align="left"><b>Datos Principales:</b></td>
                                           </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td  width="25%" height="22">
                                                                    C&Eacute;DULA / RIF: <font color="Red">(*)</font>
                                                                </td>
                                                                <td  width="75%"  height="22">
                                                                    <input size="10" class="inputbox validate[required]"  type="text" id="cedula_rif" name="cedula_rif"  value="<?php echo $cedula_rif;?>" readonly="true" />
                                                                </td>
                                                            </tr>	
                                                            <tr>
                                                                <td width="25%" height="22">
                                                                    NOMBRE DEL SOLICITANTE: <font color="Red">(*)</font>
                                                                </td>
                                                                <td>
                                                                    <input readonly="true" class="validate[required] text-input" type="text" id="nombreapellido1" name="nombreapellido1" value="<?php echo $nombreapellido;?>"  size="50" maxlength="50"/>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>	
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Vehículo:</b></td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td  width="25%" height="22">
                                                                    NÚMERO PLACA: <font color="Red">(*)</font>
                                                                </td>
                                                                <td  width="75%"  height="22">					
                                                                    <table border="0" >
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <input class="validate[required] text-input" type="text" id="placa" name="placa" value="<?php if ($error!='') echo $placa;?>"  size="20" maxlength="15"/>	
                                                                                </td>
                                                                                <td width="25%" height="22">
                                                                                    MARCA: 
                                                                                </td>
                                                                                <td>
                                                                                    <select id="marca" name="marca" class="inputbox validate[required]" size="1">
                                                                                        <option value="">---</option>
                                                                                        <?php 
                                                                                            $consulta_sql=pg_query("SELECT * FROM marca_vehiculo ");
                                                                                            while ($array_consulta=pg_fetch_array($consulta_sql)){
                                                                                                if ($error!=""){
                                                                                                    if ($array_consulta[0]==$marca){
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
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td  width="25%" height="22">
                                                                    MODELO: <font color="Red">(*)</font>
                                                                </td>
                                                                <td  width="75%"  height="22">					
                                                                    <table border="0" >
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <input size="10" class="inputbox validate[required]"  type="text" id="modelo" name="modelo"  value="<?php if ($error!='') echo $modelo;?>" />
                                                                                </td>
                                                                                <td width="25%" height="22">
                                                                                    AÑO: <font color="Red">(*)</font>
                                                                                </td>
                                                                                <td>
                                                                                    <select id="year" name="year" class="validate[required]" size="1">
                                                                                            <?php 
                                                                                                if($error!="") {
                                                                                                        echo '<option value="'.$year.'" selected="selected">'.$year.'</option>';
                                                                                                }
                                                                                                echo '<option value="">---</option>';			
                                                                                                for ($i=date("Y"); $i>=1950; $i--){																						
                                                                                                        echo '<option value="'.$i.'">'.$i.'</option>';
                                                                                                }
                                                                                            ?>																																										
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td  width="25%" height="22">
                                                                    COLOR: <font color="Red">(*)</font>
                                                                </td>
                                                                <td  width="75%"  height="22">					
                                                                    <table border="0" >
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <input size="10" class="inputbox validate[required]"  type="text" id="color" name="color"  value="<?php if ($error!='') echo $color;?>" />
                                                                                </td>
                                                                                <td width="30%" height="22">
                                                                                    SERIAL CARROCERIA: <font color="Red">(*)</font>
                                                                                </td>
                                                                                <td>
                                                                                    <input class="validate[required] text-input" type="text" id="serial_carro" name="serial_carro" value="<?php if ($error!='') echo $serialc;?>"  size="30" maxlength="30"/>
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
  		  	
</body>
    
    
</html>
    
    
        