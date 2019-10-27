<!-- styles y script del  tab -->  	
<link rel="stylesheet" type="text/css" href="css/tabcontent.css" media="screen"  />
<script language="javascript" src="js/tabcontent.js"></script> 

<?php //seccion de mensajes del sistema.
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

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_GET['rif_empresa'])){
        $datos_modificar= $_GET['rif_empresa'];

        //se le hace el llamado a la funcion de insertar.	
        $datos_consulta = pg_query("SELECT * FROM empresa where rif_empresa = '$datos_modificar'") or die("No se pudo realizar la consulta a la Base de datos");

        $resultados1=pg_fetch_array($datos_consulta);
        pg_free_result($datos_consulta);
        pg_close();
    }
 
    if (isset($_POST[save])){//se resive los datos a ser modificados
        $rif_empresa = $_POST['rif_empresa'];
        $nombre_empresa = $_POST['nombre_empresa'];
        $nombre_administrador = $_POST['nombre_administrador'];
        $ciudad = $_POST['ciudad'];
        $telefono_oficina = $_POST['telefono_oficina'];
        $telefono_fax = $_POST['telefono_fax'];
        $pagina_web = $_POST['pagina_web'];
        $correo_electronico = $_POST['correo_electronico'];
        $direccion_empresa = $_POST['direccion_empresa'];
        $siglas_empresa = $_POST['siglas_empresa'];
        $send_sms = $_POST['send_sms'];
        $send_email = $_POST['send_email'];
        $sms_nueva_solicitud = $_POST['sms_nueva_solicitud'];
        $sms_programar_ticket = $_POST['sms_programar_ticket'];
        $sms_reprogramar_ticket = $_POST['sms_reprogramar_ticket'];
        $sms_escalar_ticket = $_POST['sms_escalar_ticket'];
        $sms_completar_ticket = $_POST['sms_completar_ticket'];
        $sms_cancelar_ticket = $_POST['sms_cancelar_ticket'];
        $sms_anular_ticket = $_POST['sms_anular_ticket'];

        $datos_logo = $_POST['$logo_empresa']; //este es para saber si la caja de texto tiene un logo nuevo para ser modificado

        //aqui es para los logos de la empresa
        $logo_empresa = $_POST['rif_empresa']; //este es para copiar el archivo del logo de la empresa
        $prefijo = $logo_empresa; //logo que va a ser modificado

        $logo_name = $HTTP_POST_FILES['logo_empresa']['name'];
        $tipo_archivo = $HTTP_POST_FILES['logo_empresa']['type'];
        $tamano_archivo = $HTTP_POST_FILES['logo_empresa']['size']; 

        //se le hace el llamado a la funcion de insertar.	
        $query = "SELECT update_empresa('$rif_empresa','$nombre_empresa','$nombre_administrador','$ciudad','$telefono_oficina','$telefono_fax','$pagina_web','$correo_electronico','$logo_empresa','$direccion_empresa','$siglas_empresa','$send_sms','$send_email','$sms_nueva_solicitud','$sms_programar_ticket','$sms_reprogramar_ticket','$sms_escalar_ticket','$sms_completar_ticket','$sms_cancelar_ticket','$sms_anular_ticket')";		
        $result = pg_query($query)or die(pg_last_error());
        $result_update=pg_fetch_array($result);
        pg_free_result($result);

        $error="bien";
        
        if ($datos_logo != ""){	
            unlink("logo_empresa/$datos_borrar");
        }else{
            // guardamos el archivo a la carpeta files
            $destino =  "logo_empresa/".$prefijo;
            if (copy($_FILES['logo_empresa']['tmp_name'],$destino)){
                $status = "Archivo subido: <b>".$logo_name."</b>";
            }
        }
    }//fin del procedimiento modificar.
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
        
        <table class="adminempresa">
            <tr>
                <th>
                    REGISTRO DE EMPRESA
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm"  enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="3">
                        MODIFICAR DATOS DE LA EMPRESA
                    </th>
                </tr>

                <?php 
                if ((isset($_POST[save])) and ($error=="bien")){		
                ?> 
			
                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Datos registrados con &eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=empresa";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=empresa" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>
		
                <?php } else { ?> 
                
                <tr>
                    <td>
                        <ul id="divsG" name="divsG" class="shadetabs">
                            <li><a class="selected" href="javascript:void(0);" rel="divG0" >Datos de la Empresa</a></li>
                            <li><a href="javascript:void(0);" rel="divG1">Datos de Confirmaci&oacute;n</a></li>							
                        </ul>
                        
                        <div style="border:1px solid gray;  margin-bottom: 3px; padding: 7px">
                            <div style="display: block;" id="divG0" class="tabcontent" name="divG0">
                            <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                            <tbody>
                                <tr>
                                    <td width="15%">
                                        Rif de la Empresa:
                                    </td>

                                    <td width="60%">
                                        <input enable="true"  class="validate[required] text-input" value="<?php echo $resultados1[rif_empresa]; ?>" type="text" id="rif_empresa" name="rif_empresa" maxlength="12" size="12"/>
                                        <font color="#ff0000">*</font>
                                    </td> 
                                    
                                    <td width="25%" rowspan="4">
					<img src="logo_empresa/<?php echo $resultados1[rif_empresa];?>" height="110"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Nombre de la Empresa:
                                    </td>

                                    <td>
                                        <input  class="validate[required] text-input" value="<?php echo $resultados1[nombre_empresa]; ?>" type="text" id="nombre_empresa" name="nombre_empresa" maxlength="80" size="80"/>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>

                                <tr>
                                    <td>
                                        Siglas de la Empresa:
                                    </td>

                                    <td>
                                        <input class="validate[required] text-input" value="<?php echo $resultados1[siglas_empresa];?>" type="text" id="siglas_empresa" name="siglas_empresa" maxlength="25" size="25"/>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>

                                <tr>
                                    <td>
                                        Nombre del Administrador:
                                    </td>

                                    <td>
                                        <input class="validate[required] text-input" value="<?php echo $resultados1[nombre_administrador];?>" type="text" id="nombre_administrador"  name="nombre_administrador" maxlength="25" size="25"/>
                                        <font color="#ff0000">*</font>
                                    </td>			
                                </tr>

                                <tr>
                                    <td>
                                        Ciudad:
                                    </td>

                                    <td>
                                        <input class="validate[required] text-input" value="<?php echo $resultados1[ciudad];?>" type="text" id="ciudad" name="ciudad" maxlength="20" size="20"/>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>

                                <tr>
                                    <td>
                                        Telefono de Oficina:
                                    </td>

                                    <td>
                                        <input value="<?php echo $resultados1[telefono_oficina];?>" class="validate[required,custom[phone]] text-input" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" type="text" id="telefono_oficina" name="telefono_oficina" maxlength="12" size="12"/>
                                        <font color="#ff0000">*</font>
                                    </td>			
                                </tr>

                                <tr>
                                    <td>
                                        Telefono Fax:
                                    </td>

                                    <td>			
                                        <input value="<?php echo $resultados1[telefono_fax];?>" class="validate[custom[phone]] text-input" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" type="text" id="telefono_fax" name="telefono_fax" maxlength="12" size="12"/>
                                    </td>			
                                </tr>

                                <tr>
                                    <td>
                                        Pagina Web:
                                    </td>

                                    <td>			
                                        <input value="<?php echo $resultados1[paginia_web];?>" class="validate[requerid] text-input"  placeholder="www.mipaginaweb.com" type="text" id="pagina_web" name="pagina_web" maxlength="30" size="30"/>
                                    </td>			
                                </tr>

                                <tr>
                                    <td>
                                        Correo Electronico:
                                    </td>

                                    <td>
                                        <input value="<?php echo $resultados1[correo_electronico];?>" class="validate[custom[email]] text-input" placeholder="minombre@ejemplo.com" type="text" id="correo_electronico" name="correo_electronico" maxlength="30" size="30"/>				
                                    </td>			
                                </tr>

                                <tr>
                                    <td>
                                        Logo de la Empresa:
                                    </td>

                                    <td>
                                        <input type="file" id="logo_empresa" name="logo_empresa" maxlength="30" size="30" class="inputbox">
                                        <font size="1" color="#ff0000">(.jpg, m&aacute;ximo 100Kb)*</font>				
                                    </td>			
                                </tr>

                                <tr>
                                    <td>
                                        Direcci&oacute;n de la Empresa:
                                    </td>

                                    <td>
                                        <textarea class="validate[required] text-input" name="direccion_empresa" id="direccion_empresa" cols="70" rows="3"><?php echo $resultados1[direccion_empresa];?></textarea>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>
                            </tbody>
                            </table>
                            </div>
                            
                            <div style="display: block;" id="divG1" class="tabcontent">			
                            <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                            <tbody>
                                <tr>
                                    <td width="12%">
                                        SMS:
                                    </td>

                                    <td>
                                        <select class="validate[required] text-input" id="send_sms" name="send_sms">
                                            <?php
                                                if($resultados1[send_sms]=="1") {
                                                    echo '<option value="'.$resultados1[send_sms].'" selected="selected">Si</option>';
                                                }else{
                                                    echo '<option value="'.$resultados1[send_sms].'" selected="selected">No</option>';
                                                }
                                            ?>
                                            <option value="" >---</option>
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>	
                                        <font color="#ff0000">*</font>			
                                    </td>			
				</tr> 
                                
                                <tr>
                                    <td width="12%">
                                        Correo Electronico:
                                    </td>

                                    <td>
                                        <select class="validate[required] text-input" id="send_email" name="send_email">
                                            <?php
                                                if($resultados1[send_email]=="1") {
                                                    echo '<option value="'.$resultados1[send_email].'" selected="selected">Si</option>';
                                                }else{
                                                    echo '<option value="'.$resultados1[send_email].'" selected="selected">No</option>';
                                                }
                                            ?>
                                            <option value="" >---</option>
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>	
                                        <font color="#ff0000">*</font>			
                                    </td>			
				</tr>
                                
                                <tr>
                                    <td>
                                        SMS Para Nueva Solicitud:
                                    </td>

                                    <td>
                                        <textarea maxlength="140" class="validate[required] text-input" name="sms_nueva_solicitud" id="sms_nueva_solicitud" cols="70" rows="3"><?php echo $resultados1[sms_nueva_solicitud];?></textarea>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>
                                
                                <tr>
                                    <td>
                                        SMS Programar Ticket:
                                    </td>

                                    <td>
                                        <textarea maxlength="140" class="validate[required] text-input" name="sms_programar_ticket" id="sms_programar_ticket" cols="70" rows="3"><?php echo $resultados1[sms_programar_ticket];?></textarea>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>
                                <tr>
                                    <td>
                                        SMS Reprogramar Ticket:
                                    </td>

                                    <td>
                                        <textarea maxlength="140" class="validate[required] text-input" name="sms_reprogramar_ticket" id="sms_reprogramar_ticket" cols="70" rows="3"><?php echo $resultados1[sms_reprogramar_ticket];?></textarea>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>
                                
                                <tr>
                                    <td>
                                        SMS Escalar Ticket:
                                    </td>

                                    <td>
                                        <textarea maxlength="140" class="validate[required] text-input" name="sms_escalar_ticket" id="sms_escalar_ticket" cols="70" rows="3"><?php echo $resultados1[sms_escalar_ticket];?></textarea>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>
                                
                                <tr>
                                    <td>
                                        SMS Completar Ticket:
                                    </td>

                                    <td>
                                        <textarea maxlength="140" class="validate[required] text-input" name="sms_completar_ticket" id="sms_completar_ticket" cols="70" rows="3"><?php echo $resultados1[sms_completar_ticket];?></textarea>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>
                                
                                <tr>
                                    <td>
                                        SMS Cancelar Ticket:
                                    </td>

                                    <td>
                                        <textarea maxlength="140" class="validate[required] text-input" name="sms_cancelar_ticket" id="sms_cancelar_ticket" cols="70" rows="3"><?php echo $resultados1[sms_cancelar_ticket];?></textarea>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>
                                
                                <tr>
                                    <td>
                                        SMS Anular Ticket:
                                    </td>

                                    <td>
                                        <textarea maxlength="140" class="validate[required] text-input" name="sms_anular_ticket" id="sms_anular_ticket" cols="70" rows="3"><?php  echo $resultados1[sms_anular_ticket];?></textarea>
                                        <font color="#ff0000">*</font>				
                                    </td>			
                                </tr>
                            </tbody>
                            </table>
                            </div>
                        </div>
                    </td>
                </tr>	
			
                <tr>
                    <td colspan="2" class="botones" align="center">
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=empresa'" value="Cerrar" name="cerrar" />  
                        <!--<input  class="button" type="button" onClick="history.back()" value="Regresar">-->
                    </td>
                </tr>
                
                <?php } ?> 
                
            </table> 
        </form>			
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
      $('#telefono_oficina').mask('(9999)-9999999');
      $('#telefono_fax').mask('(9999)-9999999');
      $('#celular').mask('(9999)-9999999');
      $('#rif_empresa').mask('~-9999?9999-9',{placeholder:" "});
      $('#cedula_rif').mask('~-9999?99999',{placeholder:" "});
      $('#ip_server').mask('999.999.9.9',{placeholder:" "});

    });
</script>
