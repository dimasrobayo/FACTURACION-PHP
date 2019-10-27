<!-- styles y script del  tab -->  	
<link rel="stylesheet" type="text/css" href="css/tabcontent.css" media="screen"  />
<script language="javascript" src="js/tabcontent.js"></script> 

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

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

// consultar registro de fecha ultimoacceso	
    list($fecha_acceso,$hora_acceso) = explode(" ",$_SESSION['fecha_ultimoacceso']);
    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    list($anno,$mes,$dia) = explode("-",$fecha_acceso);
    $hora=date_create($hora_acceso);

    $query="SELECT * FROM unidades WHERE cod_unidad='$_SESSION[cod_unidad]'";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_unidad_user=pg_fetch_array($result);	
    pg_free_result($result);
    
//Informacion de  Ultimo ticket asignado 
    $query="SELECT cod_ticket AS cod_ticket_ultimo  FROM ticket ORDER BY  fecha_registro DESC LIMIT 1 ";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_ticket_ultimo_asignado=  pg_fetch_array($result);	
    pg_free_result($result);
    
//Informacion de Solicitantes Registrados 
    $query="SELECT COUNT(*) FROM solicitantes";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_solicitantes=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM solicitantes WHERE date_part('year',fecha_registro)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_solicitantes_year=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM solicitantes WHERE date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_solicitantes_month=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM solicitantes WHERE date_part('WEEK',fecha_registro)= date_part('WEEK',now()) AND date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_solicitantes_week=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM solicitantes WHERE date_part('DAY',fecha_registro)= date_part('DAY',now()) AND date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_solicitantes_day=pg_fetch_row($result);	
    pg_free_result($result);
        
//Informacion de Solicitantes Actualizados 
    $query="SELECT COUNT(*) FROM solicitantes WHERE date_part('year',fecha_registro_update)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_solicitantes_year_update=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM solicitantes WHERE date_part('MONTH',fecha_registro_update)= date_part('MONTH',now()) AND date_part('year',fecha_registro_update)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_solicitantes_month_update=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM solicitantes WHERE date_part('WEEK',fecha_registro_update)= date_part('WEEK',now()) AND date_part('MONTH',fecha_registro_update)= date_part('MONTH',now()) AND date_part('year',fecha_registro_update)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_solicitantes_week_update=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM solicitantes WHERE date_part('DAY',fecha_registro_update)= date_part('DAY',now()) AND date_part('MONTH',fecha_registro_update)= date_part('MONTH',now()) AND date_part('year',fecha_registro_update)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_solicitantes_day_update=pg_fetch_row($result);	
    pg_free_result($result);
        
//Informacion de Tickets 
    $query="SELECT COUNT(*) FROM ticket";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('year',fecha_registro)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_year=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_month=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('WEEK',fecha_registro)= date_part('WEEK',now()) AND date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_week=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('DAY',fecha_registro)= date_part('DAY',now()) AND date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now())";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_day=pg_fetch_row($result);	
    pg_free_result($result);
        
//Informacion de Tickets registrados en UAC 
    $query="SELECT COUNT(*) FROM ticket where online=0";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_uac=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('year',fecha_registro)= date_part('year',now()) and online=0";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_uac_year=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now()) and online=0";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_uac_month=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('WEEK',fecha_registro)= date_part('WEEK',now()) AND date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now()) and online=0";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_uac_week=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('DAY',fecha_registro)= date_part('DAY',now()) AND date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now()) and online=0";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_uac_day=pg_fetch_row($result);	
    pg_free_result($result);
        
//Informacion de Tickets registrados en online 
    $query="SELECT COUNT(*) FROM ticket where online=1 LIMIT 1";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_online=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('year',fecha_registro)= date_part('year',now()) and online=1";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_online_year=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now()) and online=1";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_online_month=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('WEEK',fecha_registro)= date_part('WEEK',now()) AND date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now()) and online=1";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_online_week=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket WHERE date_part('DAY',fecha_registro)= date_part('DAY',now()) AND date_part('MONTH',fecha_registro)= date_part('MONTH',now()) AND date_part('year',fecha_registro)= date_part('year',now()) and online=1";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_online_day=pg_fetch_row($result);	
    pg_free_result($result);
    
    
//Consulta de Usuarios en linea
    $yo =$_SESSION['id'];
    $query="SELECT * FROM usuarios WHERE status_login = 'on' and cedula_usuario <> '$yo' order by cedula_usuario";
    $result = pg_query($query) or die(pg_last_error());
?>

<div align="center">
    <div> 
        <?php echo $div_menssage;?>
        <br />			
        <table class="home" border="1">
            <tr>
                <th>
                    Principal
                </th>
            </tr>
        </table>

        <div align="left">
            <font size="3">Bienvenido(a): <strong><?php echo $_SESSION['username']?></strong> </font>
            <br />
            <?php if ($_SESSION['fecha_ultimoacceso']==""){?>
                <font size="2"> <strong>¡Esta es su primera visita al portal! </strong> </font>	
            <?php }else{?>
                <font size="2"> <strong>Su ultima visita al portal fue el <?php echo $dia.' de '.$meses[((int) $mes)-1].' del '.$anno.' a las '.date_format($hora,"g:i a.")?></strong> </font>	
            <?php }?>  
             <br /> 
             <br /> 
             <table class="adminlist"  width="100%" border="0">	
                <tr  height="25">
                    <td width="70%" >
                        <font size="2">
                            Unidad Asignada: 
                            <strong><?php echo $resultados_unidad_user[nombre_unidad]; ?></strong> 
                        </font>
                    </td>
                    
                    <td align="right">
                        <font size="2">
                            Ultimo Ticket Registrado: 
                            <strong><?php echo '<a title="Gestionar Ticket" href="?view=gestion_tickets&cod_ticket='.$resultados_ticket_ultimo_asignado[cod_ticket_ultimo].'"><font color="black">'.str_pad($resultados_ticket_ultimo_asignado[cod_ticket_ultimo],10,"0",STR_PAD_LEFT).'</font></a>'; ?></strong> 
                        </font>
                    </td>
                </tr>
            </table>
        </div>	
    </div>	
</div>

<br />

<div id="element-box">		
    <div class="m">
        <div class="adminform">
            <div class="cpanel-left">  
                <ul id="divsG" name="divsG" class="shadetabs">
                    <li><a class="selected" href="javascript:void(0);" rel="divG0" >Atención al Ciudadano</a></li>
                    <li><a href="javascript:void(0);" rel="divG1">Productores</a></li>
                    <li><a href="javascript:void(0);" rel="divG2">Configuraci&oacute;n y Usuarios</a></li>
                    <li><a href="javascript:void(0);" rel="divG3">Reportes</a></li>
                </ul>
                <div style="border:1px solid gray;  margin-bottom: 3px; padding: 7px">
                    <div class="cpanelinicio">
                        <div style="display: block;" id="divG0" class="tabcontent" name="divG0">
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>
                            <div class="icon-wrapper">
                                <div class="icon">
                                    <a href="index2.php?view=gestion_tickets_load">
                                        <img src="images/gestion_buscar.png" alt="Administraci&oacute;n de Solicitantes Registrados" align="middle"  border="0" />
                                        <span>Busqueda de Tickets</span>
                                    </a>
                                </div>
                            </div>
                            <div class="icon-wrapper">
                                <div class="icon">
                                    <a href="index2.php?view=gestion_tickets">
                                        <img src="images/gestion-ticket.png" alt="Administraci&oacute;n de Solicitantes Registrados" align="middle"  border="0" />
                                        <span>Gesti&oacute;n de Tickets</span>
                                    </a>
                                </div>
                            </div>
                            <div class="icon-wrapper">
                                <div class="icon">
                                    <a href="index2.php?view=solicitante_load_view">
                                        <img src="images/clientes2.png" alt="Administraci&oacute;n de Solicitantes Registrados" align="middle"  border="0" />
                                        <span>Solicitantes</span>
                                    </a>
                                </div>
                            </div>
                        </tbody>
                        </table>
                        </div> 
                        
                        <div style="display: block;" id="divG1" class="tabcontent">			
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>
                            <div class="icon-wrapper">
                                <div class="icon">
                                    <a href="index2.php?view=tipo_actividad">
                                        <img src="images/tipo_actividad.png" alt="Registrar tipo de actividad" align="middle"  border="0" />
                                        <span>Tipo de Actividad</span>
                                    </a>
                                </div>
                            </div>
                        
                            <div class="icon-wrapper">
                                <div class="icon">
                                    <a href="index2.php?view=rubro">
                                        <img src="images/siembra.png" alt="Registrar Rubro" align="middle"  border="0" />
                                        <span>Rubro</span>
                                    </a>
                                </div>
                            </div>
                        
                            <div class="icon-wrapper">
                                <div class="icon">
                                    <a href="index2.php?view=productor_load_view">
                                        <img src="images/agricultor.png" alt="Registro de Productor" align="middle"  border="0" />
                                        <span>Productor</span>
                                    </a>
                                </div>
                            </div>
                        </tbody>
                        </table>
                        </div> 

                        <div style="display: block;" id="divG2" class="tabcontent">			
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>
                            <div class="icon-wrapper">
                                <div class="icon">
                                    <a href="index2.php?view=usuarios_update_perfil">
                                        <img src="images/perfil_usuario.png" alt="Perfil del Usuario" align="middle"  border="0" />
                                        <span>Perfil del Usuario</span>
                                    </a>
                                </div>
                            </div>

                            <div class="icon-wrapper">
                                <div class="icon">
                                    <a href="index2.php?view=usuarios_update_perfil_clave">
                                        <img src="images/cambiar_clave.png" alt="Perfil del Usuario" align="middle"  border="0" />
                                        <span>Cambiar Clave</span>
                                    </a>
                                </div>
                            </div>
                        </tbody>
                        </table>
                        </div>

                        <div style="display: block;" id="divG3" class="tabcontent">			
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>
                            <div class="icon-wrapper">
                                <div class="icon">
                                    <a href="index2.php?view=sms_enviados">
                                        <img src="images/reportes.png" alt="Perfil del Usuario" align="middle"  border="0" />
                                        <span>Reportes</span>
                                    </a>
                                </div>
                            </div>
                        </tbody>
                        </table>
                        </div>        
                    </div>
                </div>
            </div>
        	
            <div class="cpanel-right">									
                <div   id="accordion">
                    <h3 >
                        <a href="javascript:void(0);">
                            <span>Información Principal de Tickets</span>
                        </a>
                    </h3>
                    <div>
                        <table class="adminlistpanel">
                            <thead>
                                <tr>
                                    <th width="50%" >
                                        Modulo
                                    </th>
                                    <th width="10%">
                                        Hoy
                                    </th>
                                    <th width="10%">
                                        Semana
                                    </th>
                                    <th width="10%">
                                        Mes
                                    </th>	
                                    <th width="10%">
                                        Año
                                    </th>									
                                    <th width="10%">
                                        Total
                                    </th>									
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">
                                        <strong>Tickets Registrados</strong>											
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_day[0]!=0){
                                                echo '<strong>'.$resultados_ticket_day[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_week[0]!=0){
                                                echo '<strong>'.$resultados_ticket_week[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_month[0]!=0){
                                                echo '<strong>'.$resultados_ticket_month[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_year[0]!=0){
                                                echo '<strong>'.$resultados_ticket_year[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket[0]!=0){
                                                echo '<strong>'.$resultados_ticket[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>   										
                                </tr>
                                
                                <tr>
                                    <td scope="row">
                                        <strong>Tickets Registrados en UAC</strong>											
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_uac_day[0]!=0){
                                                echo '<strong>'.$resultados_ticket_uac_day[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_uac_week[0]!=0){
                                                echo '<strong>'.$resultados_ticket_uac_week[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_uac_month[0]!=0){
                                                echo '<strong>'.$resultados_ticket_uac_month[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_uac_year[0]!=0){
                                                echo '<strong>'.$resultados_ticket_uac_year[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_uac[0]!=0){
                                                echo '<strong>'.$resultados_ticket_uac[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td> 										
                                </tr>
                                
                                <tr>
                                    <td scope="row">
                                        <strong>Tickets Registrados en Linea</strong>											
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_online_day[0]!=0){
                                                echo '<strong>'.$resultados_ticket_online_day[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_online_week[0]!=0){
                                                echo '<strong>'.$resultados_ticket_online_week[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_online_month[0]!=0){
                                                echo '<strong>'.$resultados_ticket_online_month[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_online_year[0]!=0){
                                                echo '<strong>'.$resultados_ticket_online_year[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_ticket_online[0]!=0){
                                                echo '<strong>'.$resultados_ticket_online[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>   										
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- SEGUNDO PANEL-->
                    <h3>
                        <a href="javascript:void(0);">
                            <span>Registro de Solicitantes</span>
                        </a>
                    </h3>
                    <div>
                        <table class="adminlistpanel">
                            <thead>
                                <tr>
                                    <th width="50%" >
                                        Modulo
                                    </th>
                                    <th width="10%">
                                        Hoy
                                    </th>
                                    <th width="10%">
                                        Semana
                                    </th>
                                    <th width="10%">
                                        Mes
                                    </th>	
                                    <th width="10%">
                                        Año
                                    </th>									
                                    <th width="10%">
                                        Total
                                    </th>									
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">
                                        <strong>Datos Solicitantes Registrados</strong>											
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_solicitantes_day[0]!=0){
                                                echo '<strong>'.$resultados_solicitantes_day[0].'</strong>';	
//                                                echo '<strong><a title="Ver Detalles" href="?view=solicitantes">'.$resultados_solicitantes_day[0].'</a></strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_solicitantes_week[0]!=0){
                                                echo '<strong>'.$resultados_solicitantes_week[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_solicitantes_month[0]!=0){
                                                echo '<strong>'.$resultados_solicitantes_month[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_solicitantes_year[0]!=0){
                                                echo '<strong>'.$resultados_solicitantes_year[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_solicitantes[0]!=0){
                                                echo '<strong>'.$resultados_solicitantes[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>    										
                                </tr>
                                
                                <tr>
                                    <td scope="row">
                                        <strong>Datos Solicitantes Actualizados</strong>											
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_solicitantes_day_update[0]!=0){
                                                echo '<strong>'.$resultados_solicitantes_day_update[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_solicitantes_week_update[0]!=0){
                                                echo '<strong>'.$resultados_solicitantes_week_update[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_solicitantes_month_update[0]!=0){
                                                echo '<strong>'.$resultados_solicitantes_month_update[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <?php
                                            if ($resultados_solicitantes_year_update[0]!=0){
                                                echo '<strong>'.$resultados_solicitantes_year_update[0].'</strong>';	
                                            }else{
                                                echo '<strong>-</strong>';
                                            }
                                        ?>
                                    </td>
                                    <td align="center" >
                                        <strong>-</strong>
                                    </td>    										
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- TERCER PANEL-->
                    <h3>
                        <a href="javascript:void(0);">
                            <span>Usuarios On-Line</span>
                        </a>
                    </h3>
                    <div id="div_chat">
                        <table class="adminlistpanel">
                            <thead>
                                <tr>
                                    <th width="10%" >
                                        #
                                    </th>
                                    <th width="85%">
                                        Usuario
                                    </th>									
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    while($resultados = pg_fetch_array($result)) {
                                ?>
                                <tr>
                                    <td>
                                        <div class="imgSmall">
                                            <img src="images/fotos/<?php echo $resultados[foto];?>" border="0" />
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <a href="#" id="3:5" class="comecar"><?php echo $resultados[nombre_usuario] ." " .$resultados[apellido_usuario];?></a>
                                    </td>
                                </tr>
                                    <?php }?>
                            </tbody>
                        </table>
                    </div> 
                 </div>
            </div>
        </div>
    <div class="clr"></div> 
    </div>
</div>

<script type="text/javascript">
    var dtabs=new ddtabcontent("divsG")
    dtabs.setpersist(true)
    dtabs.setselectedClassTarget("link") //"link" or "linkparent"
    dtabs.init()
</script>