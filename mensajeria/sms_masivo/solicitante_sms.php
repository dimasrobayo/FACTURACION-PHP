<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
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

     //Conexion a la base de datos
    require("conexion_sms/aut_config.inc.php");
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
   
    if (isset($_GET['cedula_rif'])){ // Recibir los Datos 
        $cedula_rif= $_GET['cedula_rif'];

        $query="select * from solicitantes,comunidades where solicitantes.cedula_rif='$cedula_rif' AND solicitantes.idcom=comunidades.idcom";
        $result = pg_query($query)or die(pg_last_error());
        $result_solicitantes=pg_fetch_array($result);	
        pg_free_result($result);
    }

    if (isset($_POST[save])){   // Insertar Datos del formulario
        $cedula_rif=$_POST['cedula_rif'];		
        $destino=$_POST['telefono_movil'];
        $sms=$_POST['texto'];
        $creatorId=$_SESSION['username'];
        
        //Conexion a la base de datos
        require("conexion_sms/aut_config.inc.php");
        $db_conexion=pg_connect("host=$sql_host_sms dbname=$sql_db_sms user=$sql_usuario_sms password=$sql_pass_sms");	
        
        $dest = preg_replace("/\s+/", "", $destino);
        $dest = str_replace("(", "", $dest);
        $dest = str_replace(")-", "", $dest);
        
        $total_send=0;
        if ( strlen($dest)==11 and ((stristr($dest, '0414') or stristr($dest, '0424') or stristr($dest, '0426') or stristr($dest, '0416') or stristr($dest, '0412') ))){
            $error="bien";	
            $query="SELECT insert_outbox('$dest','$sms','$creatorId')";								
            $result = pg_query($query)or die(pg_last_error());
            if(pg_affected_rows($result)){ // Verificamos y Cargamos la auditoria
                $total_send++;	
            }
            pg_free_result($result);
        }
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
        <table class="adminclientes" width="100%">
            <tr>
                <th>
                    SOLICITANTE
                </th>
            </tr>
        </table>
	    					
	<form id="QForm" name="QForm" method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr>
                    <th colspan="2" align="center">
                        <img src="images/edit.png" width="16" height="16" alt="Editar Registro">
                        MENSAJE DE TEXTO INSTANTANEO
                    </th>
                </tr>

                <?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Mensaje Enviados con &Eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=solicitante_load_view<?php echo '&cedula_rif='.substr_replace($cedula_rif,'-',1,0);?>";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=solicitante_load_view<?php echo '&cedula_rif='.substr_replace($cedula_rif,'-',1,0);?>" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>

                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
                <tr>
                   <td  colspan="2"   height="18">
                       <span> Modulo de Mensaje de Texto Al Solicitante </span>
                   </td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Solicitante:</b></td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td  width="20%" height="22">
                                            C&Eacute;DULA / RIF: <font color="Red">(*)</font>
                                    </td>
                                    <td  width="80%"  height="22">														
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td width="100">									
                                                        <input type="hidden" id="cedula_rif" name="cedula_rif"  value="<?php echo $result_solicitantes['cedula_rif'];?>" />
                                                        <input type="hidden" id="telefono_movil" name="telefono_movil"  value="<?php echo $result_solicitantes['telefono_movil'];?>" />
                                                        <?php  echo substr_replace($result_solicitantes['cedula_rif'],'-',1,0); ?>																																						
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>	

                                <tr>
                                    <td>
                                        NOMBRE DEL SOLICITANTE: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <?php echo $result_solicitantes[nombre_solicitante];?>																	
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                            SEXO: <font color="Red">(*)</font>
                                    </td>
                                    <td>														
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <?php
                                                            if($result_solicitantes[sexo_solicitante]=="1") {
                                                                echo 'MASCULINO';
                                                            }elseif($result_solicitantes[sexo_solicitante]=="2") {
                                                                echo 'FEMENINO';
                                                            }else{
                                                                echo 'NO APLICA';
                                                            }
                                                        ?>													
                                                    </td>
                                                    <td>
                                                        FECHA NATAL: 
                                                        <?php echo implode('/',array_reverse(explode('-',$result_solicitantes['fecha_nac'])));?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        TELEFONO MOVIL: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <a color="red"><h1 style="font-size:1.8em;"><?php echo $result_solicitantes[telefono_movil];?></h1></a>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        MENSAJE DE TEXTO: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <div>
                                            <textarea class="validate[required]" name="texto" id="texto" rows="5" cols="55"><?php if ($error!="") echo $texto; else echo $title_sms;?></textarea>		
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>
                <?php 
                    if ($status_dispositivo==1){
                        $comando='ls /dev/ | grep '.$puerto_dev;
                        $comd = popen ($comando,'r');
                        $excute = fread($comd, 2096);

                        if(!ereg($puerto_dev, $excute)){
                            echo '<tr><td colspan="2"><div align="center"><font size="2" color="red">El Dispositivo no se encuentra conectado o no esta disponible, por favor revise la conexión del equipo (Los mensajes serán enviados al ser conectado el Dispositivo).</font></div></td></tr>';
                        }  
                    }
                ?>

                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Enviar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=solicitante_load_view<?php echo '&cedula_rif='.substr_replace($cedula_rif,'-',1,0);?>'" value="Cerrar" name="cerrar" />  
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

