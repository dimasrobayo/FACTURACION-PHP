<?php
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    } 
?>
<div align="center" class="centermain">
    <div class="main">
        <?php 
            switch ($_SESSION['nivel']){
                			
                case 0: //Usuarios Modulo de Produccion
                    include("panel_inicio/panel_administrador.php");
                    break;
                case 1: // Usuario General de Unidad
                    include("panel_inicio/panel_usuario_general.php");
                    break;
                case 2: // Atencion al Soberano
                    include("panel_inicio/panel_usuario_atencion.php");
                    break;
                case 3: // Facturación
                    include("panel_inicio/panel_facturacion_bus.php");
                    break;
                
            }			
        ?>		
    </div>
</div>
