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
    
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    if (isset($_POST[save])){
        $fecha_ini=implode('-',array_reverse(explode('/',$_POST["fecha_ini"]))); 
        $fecha_fin=implode('-',array_reverse(explode('/',$_POST["fecha_fin"]))); 

        echo "<script type='text/javascript'>window.location.href='reportes/imprimir_censos.php?fecha_ini=$fecha_ini&fecha_fin=$fecha_fin'</script>";
    }
    
    
?> 


<!-- sincronizar mensaje cuando de muestra al usuario -->
<?php if($div_menssage) { ?>					
	<script type="text/javascript">
		function ver_msg(){
                    Effect.Fade('msg');
		}  
		setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
	</script>
 <?php } ?>
<div align="center" class="centermain">
    <div class="main">  
        <table border="0" width="100%" align="center">
            <tbody>			
                <tr>
                    <td  id="msg" align="center">		
                        <?php echo $div_menssage;?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="gestionreportes" width="100%">
            <tr>
                <th>
                    GESTIÓN DE REPORTE
                </th>
            </tr>
        </table>
	    					
        <form id="QForm" name="QForm" method="POST" action="reportes/imprimir_estadistico_ticket_fecha.php" enctype="multipart/form-data" target="_Blank">
            <table class="adminform" border="0" width="100%">
                <tr>
                    <th colspan="2" align="center">
                        <img src="images/iconos/ver_detalle.png" width="16" height="16" alt="Nuevo Registro">
                        ESTADISTICO DE TICKET POR FECHA
                    </th>
                </tr>
                <?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->
                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Datos registrados con &eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=solicitante_load_view<?php echo '&cedula_rif='.$cedula_rif;?>";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=solicitante_load_view<?php echo '&cedula_rif='.$cedula_rif;?>" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>
                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
                <tr>
                   <td  colspan="2"   height="18">
                       <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información Básica del Reporte:</b></td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                

                                <tr>
                                    <td width="15%"> 
                                        RANGO DE FECHA: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <table class="borded" border="0" >
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        FECHA INICIO:
                                                        <input autofocus="true" class="validate[required,custom[date],past[NOW]]" name="fecha_ini" type="text"   id="fecha_ini"  size="10" maxlength="10" value="<?php echo implode('/',array_reverse(explode('-',$fecha_ini)));?>" onKeyPress="ue_formatofecha(this,'/',patron,true);"/>
                                                        <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_ini,'dd/mm/yyyy',this)">														
                                                    </td>
                                                    <td>
                                                        FECHA FINAL: 
                                                        <input class="validate[required,custom[date],future[#fecha_ini],past[NOW]]" name="fecha_fin" type="text"   id="fecha_fin"  size="10" maxlength="10" value="<?php echo implode('/',array_reverse(explode('-',$fecha_fin)));?>" onKeyPress="ue_formatofecha(this,'/',patron,true);"/>
                                                        <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_fin,'dd/mm/yyyy',this)">														
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
                
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  REPORTE  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=home'" value="Cerrar" name="cerrar" />  
                    </td>													
                </tr> 
            <?php }  ?>	
        </table>
    </form>     
    <br>	 
    </div>
</div> 
        
<script type="text/javascript">
	var dtabs=new ddtabcontent("divsG")
	dtabs.setpersist(true)
	dtabs.setselectedClassTarget("link") //"link" or "linkparent"
	dtabs.init()
</script>		

<script type="text/javascript" >
	jQuery(function($) {
	      $.mask.definitions['~']='[JVGjvg]';
	      //$('#fecha_nac').mask('99/99/9999');
	      $('#telefono').mask('(9999)-9999999');
	      $('#celular').mask('(9999)-9999999');
	      
	});
</script>

