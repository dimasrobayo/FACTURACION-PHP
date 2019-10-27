<?php
    define('INCLUDE_CHECK',true); // Activamos como Principal, para verificar los de mas formularios como dependientes.
//    require ("aut_sys_config.inc.php"); //consultar datos de variables
//    require("conexion/aut_config.inc.php"); //validar sessiones del usuario
    require("conexion/aut_verifica.inc.php"); //validar sessiones del usuario
    require ("funciones.php"); // llamado de funciones de la pagina
    
    if (isset($_GET["view"])){
        $view=$_GET["view"];		
    }	
    // Error reporting:
    error_reporting(E_ALL^E_NOTICE);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>	
    <title><?php echo $sistema_name;?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8"/>
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta http-equiv="Content-Language" content="es-VE">
    <meta http-equiv="Content-Script-Type" content="text/javascript">   	
    <link rel="shortcut icon" href="images/favicon.png" />
    
    <!-- Estilos Generales -->
    <!-- styles form-->
    <link rel="stylesheet" href="css/general_portada.css" type="text/css" />
    <link rel="stylesheet" href="css/styles_nuevo.css" type="text/css">
    <link rel="stylesheet" href="css/template_portada.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/variations/css/variations/orangeblack.css" media="screen" /> 
    
    <!-- script del jquery, ajax y funciones javascript-->
    <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script> 
    <script language="javascript" src="js/ajax.js"></script>
    <script type="text/javascript" src="js/lib_javascript.js"></script>
    <script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
    
    <!-- script de la mascaras -->
<!--    <link href='js/Shadowbox/shadowbox.css' rel='stylesheet' type='text/css'/>
    <script src='js/Shadowbox/shadowbox.js' type='text/javascript'/>-->
    <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
    
    <!-- styles y script del calendario Fecha -->	
    <link type="text/css" rel="stylesheet" href="js/calendario/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
    <script type="text/javascript" src="js/calendario/dhtmlgoodies_calendar.js?random=20060118"></script>

    <!-- styles y script Validaciones -->
    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/LiveValidation.css" type="text/css" media="screen" />	
    <script src="js/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>	
    <script type="text/javascript" SRC="js/livevalidation_standalone.js"></script>

    <!-- script de efectos -->	
    <script src="js/prototype.js" type="text/javascript"></script>
    <script src="js/scriptaculous.js" type="text/javascript"></script>
    <script src="js/unittest.js" type="text/javascript"></script>  

    <!-- styles y script de las Tablas Busqueda -->
    <link rel="stylesheet" href="css/table.css" type="text/css" media="screen" />	
    <script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>		

    <!-- styles y script  Menus -->	
    <link rel="stylesheet" href="css/theme.css" type="text/css" />	      
    <script language="JavaScript" src="js/JSCookMenu.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/theme.js" type="text/javascript"></script>    	

    <!-- styles y script fancyBox ventana Emergente  -->
    <script type="text/javascript" src="js/fancybox/source/jquery.fancybox.js"></script>
    <link rel="stylesheet" type="text/css" href="js/fancybox/source/jquery.fancybox.css" media="screen" /> 

    <script type="text/javascript" src="js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>  
    <link rel="stylesheet" type="text/css" href="js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=2.0.3" />
    <script type="text/javascript" src="js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=2.0.3"></script>
    <link rel="stylesheet" type="text/css" href="js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=2.0.3" />
    <script type="text/javascript" src="js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=2.0.3"></script>  

    <!-- styles y script slider jquery-ui  -->
    <link rel="stylesheet" href="jquery-ui/development-bundle/themes/base/jquery.ui.all.css">
    <link rel="stylesheet" href="jquery-ui/development-bundle/themes/base/jquery.ui.tooltip.css">
    <script src="jquery-ui/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="jquery-ui/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="jquery-ui/development-bundle/ui/jquery.ui.accordion.js"></script>	
    <script src="jquery-ui/development-bundle/ui/jquery.ui.position.js"></script>
    <script src="jquery-ui/development-bundle/ui/jquery.ui.tooltip.js"></script>
    
    <script src="js/jquery.clockpick.js"></script>
    <link rel="stylesheet" href="css/clockpick.css" type="text/css" />
    
    <!-- Multiple Select --> 
  <link href="js/multiple-select/multiple-select.css" rel="stylesheet" />
  <script src="js/multiple-select/jquery.multiple.select.js"></script>
  <script src="js/jquery.textareaCounter.plugin.js" type="text/javascript"></script>
    
    <!-- Skitter Styles -->
    <link href="css/skitter.styles.min.css" type="text/css" media="all" rel="stylesheet" />

    <!-- Skitter JS -->
    <script type="text/javascript" language="javascript" src="js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" language="javascript" src="js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" language="javascript" src="js/jquery.skitter.min.js"></script>
    
    <!--css y jquery para sala de chat-->
    <link href="css/css_chat/style.css" rel="stylesheet" type="text"/>
    <script type="text/javascript" src="js/chat/chat.js"></script>
    <script type="text/javascript">
        $.noConflict();
    </script>
    
    <!-- Init Skitter -->
    <script type="text/javascript" language="javascript">
        $(document).ready(function() {
            $('.box_skitter_large').skitter({
                theme: 'clean',
                numbers_align: 'center',
                progressbar: true, 
                dots: true, 
                preview: true
            });
        });
    </script>

    <!-- funciones javascript  -->
    <script type="text/javascript" charset="utf-8">
   	jQuery(document).ready(function(){  
            jQuery("#QForm").validationEngine();
            jQuery("#facturacion").validationEngine();
            jQuery('#tabla').dataTable({
                "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "Todos"]],
                "sPaginationType": "full_numbers"          		
            });
            jQuery( "#accordion" ).accordion();
            jQuery("#hora_cita_programada").clockpick({
                starthour : 8,
                endhour : 18,
                military: false,
                minutedivisions: 12,
                showminutes : true
            });
            jQuery("#hora_atencion").clockpick({
                starthour : 8,
                endhour : 18,
                military: false,
                minutedivisions: 12,
                showminutes : true
            });
        }); 
    </script>
    
    <script>
        jQuery(function() {
            jQuery('#cod_grupo_sms_masivo').change(function() {
                console.log($(this).val());
            }).multipleSelect({
                width: 365,
                filter: false,
                multiple: true,
                minumimCountSelected: 10,
                multipleWidth: 300
            });
        });
    </script>
    
    <script type="text/javascript">
        var info;
        jQuery(document).ready(function(){
                var options = {
                        'maxCharacterSize': -2,
                        'originalStyle': 'originalTextareaInfo',
                        'warningStyle' : 'warningTextareaInfo',
                        'warningNumber': 40
                };
                jQuery('#texto').textareaCount(options);

                var options2 = {
                                'maxCharacterSize': 200,
                                'originalStyle': 'originalTextareaInfo',
                                'warningStyle' : 'warningTextareaInfo',
                                'warningNumber': 40,
                                'displayFormat' : '#input/#max | #words words'
                };
                jQuery('#testTextarea2').textareaCount(options2);

                var options3 = {
                                'maxCharacterSize': 200,
                                'originalStyle': 'originalTextareaInfo',
                                'warningStyle' : 'warningTextareaInfo',
                                'warningNumber': 40,
                                'displayFormat' : '#left Characters Left / #max'
                };
                jQuery('#testTextarea3').textareaCount(options3, function(data){
                        $('#showData').html(data.input + " characters input. <br />" + data.left + " characters left. <br />" + data.max + " max characters. <br />" + data.words + " words input.");
                });
        });
    </script>
</head>

<body onload="<?php include ('referencias.php');?>">
    <!-- Codigo para mostrar la ayuda al usuario  -->
    <div style="top: 477px; left: 966px; display: none;" id="mensajesAyuda">
        <div id="ayudaTitulo">Código de Seguridad (Obligatorio)</div>
        <div id="ayudaTexto">Ingresa el código de seguridad que muestra la imagen</div>
    </div>
	
    <div align="center">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
	    <tr>
                <td>
                    <img src="images/banner/banner_sac.png" class="circlesRotate" width="100%" height="100%" />
                </td>
	    </tr>
            
            <tr>
                <td>
                    <?php include ('view_menu.php');?>
		</td>
	    </tr>
            
            <tr>
                <td>
                    <div align="center">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
                            <tr>
                                <td>
                                    <?php include ('view_content.php');?>  
				</td>
                            </tr>
			</tbody>
			</table>	  
                    </div>        	
                   
	       </td>      
	    </tr>
	    <tr>
                <td>
		</td>    
            </tr>
        </table> 
        <br/>
    </div>
</body>
</html>

