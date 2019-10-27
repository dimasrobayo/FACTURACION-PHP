<?php 
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    switch ($_SESSION['nivel']){
        			
        case 0: //Administrador Genral
            include("menu/menu_administrador.php");
            break;
        case 1: // Usuario General de Unidad
            include("menu/menu_usuario_general.php");
            break;
        case 2: //  Atencion al Soberano
            include("menu/menu_usuario_atencion.php");
            break;
        case 3: // Facturación
            include("menu/menu_facturacion_bus.php");
            break;
        
}				

?>
