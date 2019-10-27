<?php
    define('INCLUDE_CHECK',true); // Activamos como Principal, para verificar los de mas formularios como dependientes.
    require("conexion/aut_config.inc.php"); //validar sessiones del usuario
    
    $view=$_GET["view"];
    
    if ($view){	
        if($view=="logoff"){
            session_name($usuarios_sesion);
            session_start();
            session_unset();
            session_destroy();
        }
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
    <link rel="stylesheet" href="css/template_portada.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/variations/css/variations/orangeblack.css" media="screen" /> 
  	
    <!-- styles y script para menu maker  -->
   <link rel="stylesheet" href="css/styles_menu_general.css" />
   <script src="js/js_menu_general/jquery-latest.min.js" type="text/javascript"></script>
   <script src="js/js_menu_general/script.js"></script>
    
    <!-- script del jquery, ajax y funciones javascript-->
    <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script> 
    <script language="javascript" src="js/ajax.js"></script>
    <script type="text/javascript" src="js/lib_javascript.js"></script>
    <script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>

    <!-- script de la mascaras -->
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

    <script src="js/jquery.clockpick.js"></script>
    <link rel="stylesheet" href="css/clockpick.css" type="text/css" />

   

    <!-- funciones javascript  -->
    <script type="text/javascript" charset="utf-8">			      
        jQuery(document).ready(function(){          
            jQuery("#QForm").validationEngine();
            jQuery("#facturacion").validationEngine();
            jQuery('#tabla').dataTable({
                "aLengthMenu": [[15, 25, 50, 75, 100, -1], [15, 25, 50, 75, 100, "Todos"]],
                "sPaginationType": "full_numbers"          		
            });   
            jQuery("#hora").clockpick({
                starthour : 8,
                endhour : 18,
                military: false,
                minutedivisions: 12,
                showminutes : false
            });  
            jQuery("#horac").clockpick({
                starthour : 8,
                endhour : 18,
                military: false,
                minutedivisions: 12,
                showminutes : false
            });
        });  	                      
    </script>
    
    <style type="text/css">
        .form-style-6{
            font: 95% Arial, Helvetica, sans-serif;
            max-width: 400px;
            margin: 10px auto;
            padding: 16px;
            background: #F7F7F7;
            box-shadow: 0px 0px 8px #999;
        }
        .form-style-6 h1{
            background: #c30404;
            padding: 20px 0;
            font-size: 140%;
            font: 22px Arial, Helvetica, sans-serif;
            font-weight: 300;
            text-align: center;
            color: #fff;
            margin: -16px -16px 16px -16px;
        }
        .form-style-6 input[type="text"],
        .form-style-6 input[type="date"],
        .form-style-6 input[type="datetime"],
        .form-style-6 input[type="email"],
        .form-style-6 input[type="number"],
        .form-style-6 input[type="search"],
        .form-style-6 input[type="time"],
        .form-style-6 input[type="url"],
        .form-style-6 textarea,
        .form-style-6 select 
        {
            -webkit-transition: all 0.30s ease-in-out;
            -moz-transition: all 0.30s ease-in-out;
            -ms-transition: all 0.30s ease-in-out;
            -o-transition: all 0.30s ease-in-out;
            outline: none;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            width: 100%;
            background: #fff;
            margin-bottom: 4%;
            border: 1px solid #ccc;
            padding: 3%;
            color: #555;
            font: 14px Arial, Helvetica, sans-serif;
        }
        .form-style-6 input[type="text"]:focus,
        .form-style-6 input[type="date"]:focus,
        .form-style-6 input[type="datetime"]:focus,
        .form-style-6 input[type="email"]:focus,
        .form-style-6 input[type="number"]:focus,
        .form-style-6 input[type="search"]:focus,
        .form-style-6 input[type="time"]:focus,
        .form-style-6 input[type="url"]:focus,
        .form-style-6 textarea:focus,
        .form-style-6 select:focus
        {
            box-shadow: 0 0 5px #c30404;
            padding: 3%;
            border: 1px solid #c30404;
        }

        .form-style-6 input[type="submit"],
        .form-style-6 input[type="button"]{
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            width: 100%;
            padding: 3%;
            background: #c30404;
            border-bottom: 2px solid #fff;
            border-top-style: none;
            border-right-style: none;
            border-left-style: none;    
            color: #fff;
            font: 14px Arial, Helvetica, sans-serif;
        }
        .form-style-6 input[type="submit"]:hover,
        .form-style-6 input[type="button"]:hover{
            background: #ae0505;
        }
    </style>
</head>

<body onload="<?php include ('referencias.php');?>">
    <div align="center"  bgcolor="fffff">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" >
	    <tr>
                <td>
                    <img src="images/banner/banner_sac.png" class="circlesRotate" width="100%" height="100%" />
                </td>
	    </tr>

	    <tr>
                <td>
                    <table class="container_contenido" border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>				  								    			
                                <td align="left" valign="top" width="220px">
                                    <div style="float: left; ">
                                        <?php include ('menu_general.php')?>
                                    </div>
                                </td>
                                <td id="spacertd">
                                    <img id="spacergif" src="images/spacer.gif">
                                </td>
                                <td align="center" valign="top">
                                    <div style="float: center;">
                                        <?php include ('view_content.php');?>
                                    </div>   				
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>      
        </table> 
    </div>
</body>
</html>
