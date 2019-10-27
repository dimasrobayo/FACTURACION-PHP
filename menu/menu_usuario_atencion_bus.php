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
?>
<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <tr>
            <td class="menubackgr" style="padding-left:5px;">
                <div id="myMenuID">
                    <script language="JavaScript" view="text/javascript">
                    var myMenu =
                    [
                        ['<img src="images/iconos/home.png" />',' Inicio','index2.php?view=home',null,'Panel de Control Principal'],
                         _cmSplit,
                        ['<img src="images/iconos/file.png" />', 'Archivos', null, null, 'Archivos',
                            ['<img src="images/iconos/tramites.png" />', 'Estados de Tramites', "index2.php?view=edos_tramites", null, 'Registro-Control de Estados de los Tramites'],
                            _cmSplit,
                            ['<img src="images/iconos/categorias.png" />', 'Categorias', "index2.php?view=categorias", null, 'Registro-Control de Categorias'],
                            ['<img src="images/iconos/unidades.png" />', 'Unidades', "index2.php?view=unidades", null, 'Registro-Control de Unidades'],			
                            _cmSplit,
                            ['<img src="images/iconos/mnuusuarios.png" />', 'Tipos de Solicitantes', "index2.php?view=tipo_solicitantes", null, 'Registro-Control de Tipos de Solicitantes'],			
                            ['<img src="images/iconos/comunidades.png" />', 'Comunidades', "index2.php?view=comunidades", null, 'Registro-Control de Comunidades'],
                        ],
                        _cmSplit,
                        ['<img src="images/iconos/mnuconfiguracion.png" />', 'Procesos', null, null, 'Procesos',
                            ['<img src="images/mnu_gestion_buscar.png" />', 'Gestión Busqueda de Ticket', "index2.php?view=gestion_tickets_load", null, 'Gestión Busqueda de Tickets'],
                             _cmSplit,
                            ['<img src="images/iconos/ticket.png"] />', 'Registro de Ticket', "index2.php?view=tickets", null, 'Registro-Control de Tickets'],
                            ['<img src="images/iconos/gestion-ticket.png" />', 'Gestión de Ticket', "index2.php?view=gestion_tickets", null, 'Gestión de Tickets'],
                             _cmSplit,
                            ['<img src="images/iconos/solicitantes.png"] />', 'Solicitantes', "index2.php?view=solicitante_load_view", null, 'Registro-Control de Solicitantes'],
                        ],
                        _cmSplit,
                        ['<img src="images/iconos/imprimir.png" />',' Reportes',null,null,'Reportes generales del Sistema',
                            ['<img src="images/menu/reporte.png" />', 'Reportes Generales', null, null,
                                _cmSplit,
                                ['<img src="images/iconos/ver_detalle.png" />', 'Estados de los Tramites', "reportes/imprimir_lista_etramites.php", 'target="_blank"','Estados de los Tramites'],
                                _cmSplit,
                                ['<img src="images/iconos/ver_detalle.png" />', 'Comunidades', "reportes/imprimir_lista_comunidades.php", 'target="_blank"','Comunidades'],
                                _cmSplit,
                                ['<img src="images/iconos/ver_detalle.png" />', 'Categorias', "reportes/imprimir_lista_categorias.php", 'target="_blank"','Categorias'],
                                ['<img src="images/iconos/ver_detalle.png" />', 'Unidades', "reportes/imprimir_lista_unidades.php", 'target="_blank"','Unidades'],
                            ],
                            _cmSplit,
                            ['<img src="images/menu/reporte.png" />', 'Reportes Específicos', null, null,
                                _cmSplit,
                                ['<img src="images/iconos/ver_detalle.png" />', 'Estadisticos de Ticket de Unidad por Fecha', "index2.php?view=gestion_reporte_est_fecha", null,'Estadisticos de Ticket de Unidad por Fecha'],
                                _cmSplit,
                                ['<img src="images/iconos/ver_detalle.png" />', 'Estadisticos de Ticket por Año', "index2.php?view=gestion_reporte_est_year", null,'Estadisticos de Ticket por Año'],
                                
                            ],
                        ],
                        _cmSplit,
                        ['<img src="images/iconos/config.png" />', ' Herramientas', null, null, 'Opciones del Sistema',
                            ['<img src="images/iconos/usuarios.png" />', 'Editar Perfil de Usuario', "index2.php?view=usuarios_update_perfil", null, 'Gestionar los Usuarios del Sistema'],
                            ['<img src="images/menu/cambiar_clave.png" />', 'Cambiar clave de Usuario', "index2.php?view=usuarios_update_perfil_clave", null, 'Gestionar los Usuarios del Sistema'],
                         ],
                        _cmSplit,
                        ['<img src="images/iconos/creditos2.png" />', 'Acerca de', null, null, 'Opciones del Sistema',
                            ['<img src="images/iconos/mnucredito.png" />', 'Cr&eacute;ditos', "index2.php?view=credit", null, 'Cr&eacute;ditos del Sistema'],
                            ['<img src="images/iconos/mnusysinfo.png" />', 'Informaci&oacute;n del Sistema', "index2.php?view=system_info", null, 'Informaci&oacute;n general del Sistema'],
                        ],
                    ];
                    cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
                </script>
            </div>
        </td>
		
        <td class="menubackgr" >
            <?php 	
                $m=date("n");
                switch($m) {
                   case 1:
                      $mes="Enero"; 
                      break;
                   case 2:
                      $mes="Febrero"; 
                      break;
                   case 3:
                      $mes="Marzo"; 
                      break;
                   case 4:
                      $mes="Abril"; 
                      break;
                   case 5:
                      $mes="Mayo"; 
                      break;
                   case 6:
                      $mes="Junio"; 
                      break;
                   case 7:
                      $mes="Julio"; 
                      break;
                   case 8:
                      $mes="Agosto"; 
                      break;
                   case 9:
                      $mes="Septiembre"; 
                      break;
                   case 10:
                      $mes="Octubre"; 
                      break;
                   case 11:
                      $mes="Noviembre"; 
                      break;
                   case 12:
                      $mes="Diciembre"; 
                      break; 
                }
                $fecha=date("d")." de ".$mes." de ".date("Y");
                //$hora=date("h").":".date("m").":".date("s")." ".date("a");
                echo $fecha; 
                //echo "<br>";		
            ?>
        </td>
          
        <td class="menubackgr" align="right" style="padding-right:10px;">
            <?php 
            if ($_SESSION['username']){
            ?>
                Login: 
                <font color="blue">
                    <strong>
                        <?php echo $_SESSION['user']?>
                    </strong>
                </font> 
                ( <a onclick="return confirm('Esta seguro que desea Salir del Sistema?');" href="index.php?view=logoff&user=<?php echo $_SESSION['id']?>" style="color: blue; font-size:8pt; ">Salir</a> )
            <?php 			
            }
            ?>	
        </td>	
    </tr>
</table>
