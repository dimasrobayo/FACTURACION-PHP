<?php
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        exit;
    }

    if (isset($_GET['archivo'])){ // Recibir los Datos 
        $archivo_get= $_GET['archivo'];
        if($archivo_get!=""){
            $dir_upload='upload_file/'; // Nombre del Directorio de las subidas de archivos
            $archivo = $dir_upload.$archivo_get;														
//            if (file_exists($archivo)){
//                echo '| <a href="'.$archivo.'" download="'.$archivo.'" target="_blank" title="Descargar Archivo para Visualizar">
//                        <img src="images/ver.png" name="Image_Encab"  border="0"/>
//                    </a>';
//            }

        }
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
                                    Visualización del Documento
                                </th>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>                
                                            <tr>
                                                <td align="center">                   
                                                    <table border="0" >
                                                        <tbody>
                                                            <tr>
                                                                <td width="100" align="center">
                                                                <embed src="../<?php echo $archivo; ?>#toolbar=0" width="700" height="350">
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
                        </tbody>
                    </table> 
                </td>	 				 			  
            </tr>
        </tbody>
    </table>
</body>
</html>