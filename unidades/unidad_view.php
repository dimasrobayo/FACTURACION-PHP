<?php
    
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }

    require("../conexion/aut_config.inc.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_GET['cod_unidad'])){
    	$datos_modificar= $_GET['cod_unidad'];

	$query="SELECT * FROM unidades where cod_unidad = $datos_modificar";
    	$result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);	
        pg_free_result($result);
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<html>
    <head>
        <title>Vista de Unidad</title>
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
                                        DEPENDENCIA/UNIDAD
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>                

                                            <tr>
                                                <td width="20%" align="right">
                                                    <b>C&oacute;digo:</b>
                                                </td>		
                                                <td width="80%">
                                                    <input id="cod_unidad" name="cod_unidad" value="<?php echo $resultado[cod_unidad]; ?>" readonly="true" class="inputbox" type="text"/>
                                                </td>                       
                                            </tr>

                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Siglas:</b>
                                                </td>
                                                <td>
                                                    <input class="inputbox" type="text" id="siglas" name="siglas" value="<?php echo $resultado[siglas_unidad]; ?>" readonly="true" maxlength="20" size="20"/>				
                                                </td>			
                                            </tr>

                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Departamento/Unidad:</b>
                                                </td>
                                                <td>
                                                    <input type="text" id="nombre" name="nombre" value="<?php echo $resultado[nombre_unidad]; ?>" readonly="true" maxlength="50" size="50"/>
                                                </td>			
                                            </tr>

                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Direcci&oacute;n:</b>
                                                </td>
                                                <td>
                                                    <input class="inputbox" type="text" id="direccion" name="direccion" value="<?php echo $resultado[direccion_unidad]; ?>" readonly="true" maxlength="100" size="50"/>			
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Tel&eacute;fono 1:</b>
                                                </td>
                                                <td>
                                                    <input type="text" id="telefono1" name="telefono1" value="<?php echo $resultado[telefono_1]; ?>" readonly="true" maxlength="50" size="12"/>				
                                                </td>			
                                            </tr>

                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Tel&eacute;fono 2:</b>
                                                </td>
                                                <td>
                                                    <input readonly="true" type="text" id="telefono2" name="telefono2" value="<?php echo $resultado[telefono_2]; ?>" maxlength="50" size="12"/>			
                                                </td>			
                                            </tr>

                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>eMail:</b>
                                                </td>
                                                <td>
                                                    <input type="text" id="email" name="email" value="<?php echo $resultado[email_unidad]; ?>" readonly="true" maxlength="50" size="50"/>				
                                                </td>			
                                            </tr>

                                            <tr>
                                                <td width="15%" align="right">
                                                    <b>Horario:</b>
                                                </td>
                                                <td>
                                                    <input class="inputbox" type="text" id="horario" name="horario" value="<?php echo $resultado[horario_unidad]; ?>" readonly="true" maxlength="50" size="50"/>				
                                                </td>			
                                            </tr>
                                        </tbody>
                                    </table>	
                                </td>
                            </tr>
                            <tr>
                                <td class="titulo"  height="18"  align="left"><b>Datos del Responsable de la Unidad:</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td width="25%" align="right">
                                                    <b>Responsable:</b>
                                                </td>
                                                <td width="75%">
                                                    <input type="text" id="responsable" name="responsable" value="<?php echo $resultado[responsable_unidad]; ?>" readonly="true" maxlength="50" size="50"/>			
                                                </td>			
                                            </tr>

                                            <tr>
                                                <td width="20%" align="right">
                                                    <b>Cargo:</b>
                                                </td>
                                                <td>
                                                    <input class="inputbox" type="text" id="cargo" name="cargo" value="<?php echo $resultado[cargo_responsable]; ?>" readonly="true" maxlength="50" size="50"/>				
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