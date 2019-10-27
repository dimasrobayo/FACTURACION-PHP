<?php  
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
?>
<table  border="0" cellpadding="0" cellspacing="1" width="100%">
<tbody>										
    <tr>
        <td>
            <div class="module mod-polaroid">
                <div class="badge-tape"></div>
                <div class="box-1">															
                    <div class="box-2 deepest">
                        <div id='cssmenu'>
                            <ul>
                                <li><a href="index.php?view=inicio"><span>Inicio</span></a></li>
                                <li class='active has-sub'><a href='#'><span>Solicitudes</span></a>
                                    <ul>
                                        <li><a href="index.php?view=solicitud_online"><span>Generar Solicitud</span></a></li>
                                        <li><a href="index.php?view=consultar_solicitud"><span>Verificar Solicitud</span></a></li>
                                    </ul>
                                </li>
                                <li><a href="index.php?view=login"><span>Entrar al Sistema</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="box-b1">
                    <div class="box-b2">
                        <div class="box-b3"></div>
                    </div>
                </div>															
            </div>														
        </td>													
    </tr>
</tbody>
</table>

