<?php	
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    } 
    switch ($view){
        // USUARIOS	
        case "user_add": 
            echo 'setFocusUser(); activaremail();';
            break;			
        case "user_update": 
            echo 'setFocusUserName(); activaremail();';
            break;
        ///////////// SOLICITUDES /////////////////		
        case "solicitud_online": 
            echo 'setFocusCedulaRif();';
            break;			
        case "solicitante_add": 
            echo 'setFocusUser();';
            break;			
        case "solicitante_load_view": 
            echo 'setFocusCedulaRif();';
            break;
        ///////////// CONCEPTOS FACTURA /////////////////		
        case "concepto_add": 
            echo 'activar_stock();';
            break;			
        case "concepto_update": 
            echo 'activar_stock();';
            break;			
        ///////////// TICKETS /////////////////		
        case "gestion_tickets":
            echo 'setFocusCodTicket();';
            break;
        
        			
        // POR DEFECTO CUANDO VIEW NO POSEE VALOR SE LLAMA AL FORMULARIO DE AUNTENTICACION
        default:
            echo 'setFocusLogin();';							
    }
?>
