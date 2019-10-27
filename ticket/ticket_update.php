<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
	
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

     //Conexion a la base de datos
    include("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass")or die(pg_last_error());
    
    if (isset($_GET['cod_ticket'])){
        $cod_ticket=strtoupper($_GET['cod_ticket']);
        
        $query="SELECT * FROM ticket,tramites,solicitantes,estados_tramites,unidades,categorias". 
                " WHERE ticket.cod_ticket='$cod_ticket' AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND ticket.cedula_rif=solicitantes.cedula_rif AND ticket.cod_tramite=tramites.cod_tramite ".
                " AND tramites.cod_categoria=categorias.cod_categoria AND tramites.cod_unidad=unidades.cod_unidad";
        $result = pg_query($query)or die(pg_last_error());
        $total_result_ticket= pg_num_rows($result);
        $resultados_ticket=pg_fetch_array($result);	
        pg_free_result($result);
    }
        
        
    if (isset($_POST[save])){   // Insertar Datos del formulario
        $cod_ticket=strtoupper($_POST['cod_ticket']);
        $name_file_upload=$_POST['name_file_upload'];
        $cod_tramite=strtoupper($_POST["cod_tramite"]);
        $cod_unidad=strtoupper($_POST["cod_unidad"]);
        $persona_contacto_dep=strtoupper($_POST["persona_contacto_dep"]);
        $descripcion_ticket=strtoupper($_POST["descripcion_ticket"]);
        $monto_solicitud=strtoupper($_POST["monto_solicitud"]);
        $prioridad=$_POST['prioridad'];
        $user=$_SESSION[user];
        if($_POST['cod_vehiculo']!="") $cod_vehiculo=$_POST['cod_vehiculo']; else $cod_vehiculo=0;
        
        
        $query="update ticket set cod_tramite='$cod_tramite', persona_contacto_dep='$persona_contacto_dep',descripcion_ticket='$descripcion_ticket',monto_solicitud='$monto_solicitud',prioridad_ticket='$prioridad',fecha_registro_update='NOW()',user_login='$user',id_vehiculo='$cod_vehiculo' where cod_ticket='$cod_ticket'";
        $result = pg_query($query)or die(pg_last_error());
        $error="bien";
        
        //// SUBIR ARCHIVO AL SERVIDOR
        if(isset($_FILES['archivo']) ){
            $name = $_FILES['archivo']['name'];	
            $name_tmp = $_FILES['archivo']['tmp_name'];
            $size = $_FILES['archivo']['size'];
            $type = $_FILES['archivo']['type'];

//                    $type_file=explode('image/',$_FILES["archivo"]["type"]); // Separamos name y type/ 
//                    $ext_type_file=$type_file[1]; // Optenemos el tipo del archivo 
//                    $type_file=explode('application/',$_FILES["archivo"]["type"]); // Separamos name y type/ 

            $ext_permitidas = array('jpg','jpeg','png','pdf');
            $part_name = explode('.', $name);
            $ext_type=$part_name[1]; // Optenemos el tipo del archivo 
            $ext_correcta = in_array($ext_type, $ext_permitidas);

//                    $type_correcto = preg_match('/^image\/(pjpeg|jpeg|gif|png)$/', $ext_type);

            $upload_max = 1000 * 1024; // Tamaño maximo del Archivo en Kb. (1 Mb)
            $dir_upload='upload_file/'; // Nombre del Directorio de las subidas de archivos
            $new_name_file=$cod_ticket.'.'.$ext_type;

            if (is_uploaded_file($_FILES['archivo']['tmp_name'])){
                if( $ext_correcta && $size <= $upload_max ){
                    if( $_FILES['archivo']['error'] > 0 ){
                      $upload_menssage= 'Error: ' . $_FILES['archivo']['error'].'.';
                    }else{
                       $archivo = $dir_upload.$name_file_upload;														
                        if (file_exists($archivo)){
                            unlink($archivo);
                        }
                       move_uploaded_file($_FILES['archivo']['tmp_name'],$dir_upload .'/'.$new_name_file); 
                       $upload_menssage="Archivo Adjuntado con éxito.";
                       $upload_ok=1;

                       $query="update ticket set name_file_upload='$new_name_file' where cod_ticket='$cod_ticket'";
                       $result = pg_query($query)or die(pg_last_error());
                    }

                }else{
                    $upload_menssage="Formato ó Tamaño del Archivo es inválido.";
                }
            }else{
                $upload_menssage="Sin Archivo Seleccionado que Subir.";
            }
        }else{
            $upload_menssage="Ticket Sin Archivo Adjunto.";
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
<!--aqui es donde esta el diseño del formulario-->

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
        <table class="adminticketsgestion" width="100%">
            <tr>
                <th>
                    GESTIÓN DEL TICKET
                </th>
            </tr>
        </table>

        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <input class="inputbox" type="hidden" id="cod_unidad" name="cod_unidad" value="<?php echo $resultados_subticket['cod_unidad']; ?>"/>
            <input class="inputbox" type="hidden" id="cod_ticket" name="cod_ticket" value="<?php echo $resultados_ticket['cod_ticket']; ?>"/>
            <input class="inputbox" type="hidden" id="name_file_ipload" name="name_file_ipload" value="<?php echo $resultados_ticket['name_file_ipload']; ?>"/>
            <input type="hidden" id="cedula_rif" name="cedula_rif"  value="<?php echo substr_replace($resultados_ticket['cedula_rif'],'-',1,0);?>" />
            <input type="hidden" id="nombreapellido" name="nombreapellido"  value="<?php echo $resultados_ticket[nombre_solicitante]?>" />
            <input type="hidden" id="solicitanteload" name="solicitanteload"  value="1" />
            <table class="adminform"  width="100%" align="center">
                <tr>
                    <th colspan="2" align="center">
                        <img src="images/edit.png" width="16" height="16" alt="Nuevo Registro de Ticket">
                        ACTUALIZANDO LOS DATOS DEL TICKET
                    </th>
                </tr>

                <?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">	
                                    <?php echo 'Ticket Nro.: '.$cod_ticket.' Actualizado con &eacute;xito.<br/><font color="#CC0000" style="text-decoration:blink;">'.$upload_menssage.'</font>';?>
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=gestion_tickets<?php echo '&cod_ticket='.$cod_ticket;?>";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=gestion_tickets<?php echo '&cod_ticket='.$cod_ticket;?>" name="Continuar"> Continuar </a>]
                                </font>								
                            </h3>
                        </div> 
                    </td>
                </tr>
        
                <?php	}else	{ 	?>   <!-- Mostrar formulario Original --> 

                <tr>
                   <td  colspan="2"   height="18">
                       <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>


                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Ticket</b></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td  width="20%" height="22">
                                        NRO. TICKET: 
                                    </td>
                                    <td>
                                        <table  border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="20%"> 
                                                        <input type="text" id="cod_tac" name="cod_tac"  readonly="readonly" value="<?php echo str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT);?>"  size="10"/>
                                                    </td>
                                                    <td>
                                                        FECHA EMISIÓN: 
                                                        <input type="text" id="fecha_registro_ticket" name="fecha_registro_ticket" value="<?php echo date_format(date_create($resultados_ticket['fecha_registro']), 'd/m/Y');?>"  size="10" />
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
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Solicitante:</b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td  width="20%" height="22">
                                        C&Eacute;DULA / RIF: 
                                    </td>
                                    <td>
                                        <table  border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input size="10"  readonly="readonly" type="text" name="cedula1"  value="<?php echo substr_replace($resultados_ticket['cedula_rif'],'-',1,0);?>" /> 																																						
                                                    </td>
                                                    <td>
                                                        NOMBRE DEL SOLICITANTE:
                                                        <input  readonly="readonly"  type="text" id="nombreapellido1" name="nombreapellido1" value="<?php echo $resultados_ticket[nombre_solicitante];?>"  size="50" maxlength="50"/>																	
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
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Ticket de Atención al Soberano:</b></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                 <tr>
                                    <td width="20%">
                                        PERSONA CONTACTO / DEP.:
                                    </td>
                                    <td>
                                        <input type="text" id="persona_contacto_dep" name="persona_contacto_dep" value="<?php echo $resultados_ticket[persona_contacto_dep];?>" onkeyup="" size="50" maxlength="50"/>																	
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        DESCRIPCION DEL TRAMITE: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <textarea class="validate[required]" name="descripcion_ticket" id="descripcion_ticket" cols="48" rows="3" onkeyup=""><?php echo $resultados_ticket[descripcion_ticket];?></textarea>																	
                                    </td>
                                </tr>
                                <tr>
                                    <td>UNIDAD INICIAL DE ASIGNACIÓN <font color="Red">(*)</font></td>
                                    <td>
                                        <div id="unidades">
                                            <select disabled="true" name="cod_unidad_old" id="cod_unidad_old" class="validate[required]">
                                                <option selected="selected" value="">---</option>
                                                <?php 
                                                    $consulta_sql=pg_query("SELECT * FROM unidades order by nombre_unidad");
                                                    while ($array_consulta=pg_fetch_array($consulta_sql)){
                                                        if($array_consulta[0]==$resultados_ticket[cod_unidad]){
                                                            echo '<option value="'.$array_consulta[0].'" selected="selected">'.$array_consulta[2].'</option>';																			
                                                        }else{
                                                            echo '<option value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';																			
                                                        }
                                                    }																																						
                                                    pg_free_result($consulta_sql);								
                                                ?>				
                                            </select> 
                                            <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Unidad de Asignación','Seleccione la Unidad Inicial a la que se asigna el Ticket, luego seleccione el tipo de tramite a Registrar',' (Campo Requerido)')">														
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TIPO DE TRAMITE: <font color="Red">(*)</font></td>
                                    <td>
                                        <div id="tramites">
                                            <select name="cod_tramite" id="cod_tramite" class="validate[required]"  >
                                                <option  selected="selected" value="">---</option>
                                                <?php 
                                                    $consulta_sql=pg_query("SELECT * FROM tramites where cod_unidad='$resultados_ticket[cod_unidad]' order by cod_tramite");
                                                    while ($array_consulta=pg_fetch_array($consulta_sql)){
                                                        if($array_consulta[0]==$resultados_ticket[cod_tramite]){
                                                            echo '<option value="'.$array_consulta[0].'" selected="selected">'.$array_consulta[2].'</option>';																			
                                                        }else{
                                                            echo '<option value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';																			
                                                        }
                                                    }																																						
                                                    pg_free_result($consulta_sql);								
                                                ?>
                                            </select>
                                            <?php 
                                            if ($resultados_ticket[cod_unidad]==5) {
                                                echo '
                                                        <a href="javascript: ue_catalogo_tramite();"><img src="images/busqueda.png" alt="Buscar" title="Registrar Vehiulo" width="20" height="20" border="0"></a>


                                                            <div id="vehiculos" >
                                                                <select name="cod_vehiculo" id="cod_vehiculo" class="validate[required]"  >
                                                                    <option  selected="selected" value="">Seleccione el Vehículo</option>';

                                                                    $consultax2="SELECT * from vehiculo_solicitante where cedula_rif='$resultados_ticket[cedula_rif]'";
                                                                    $ejec_consultax2=pg_query($consultax2);
                                                                    while($vector2=pg_fetch_array($ejec_consultax2)){
                                                                        if($vector2[0]==$resultados_ticket[id_vehiculo]){
                                                                            echo '<option value="'.$vector2[0].'" selected="selected">Placa:'.$vector2[4].' - S/C:'.$vector2[7].'</option>';
                                                                        }else{
                                                                            echo '<option value="'.$vector2[0].'">Placa:'.$vector2[4].' - S/C:'.$vector2[7].'</option>';
                                                                        }
                                                                        
                                                                            
                                                                    }


                                                echo '           </select>
                                                                 <a href="javascript: ue_vehiculo_add();"><img src="images/agregar.png" alt="Buscar" title="Registrar Vehiulo" width="20" height="20" border="0"></a>
                                                            </div>
                                                        ';
                                            }
                                            ?>
                                        </div>											 
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>MONTO DE SOLICITUD: <font color="Red">(*)</font></td>
                                    <td>														         
                                         <input  style="text-align:right" type="text" id="monto_solicitud" class="validate[required,custom[number]] text-input"  name="monto_solicitud" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="<?php echo $resultados_ticket[monto_solicitud];?>" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                                         <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Monto','Ingrese el monto incluyendo los decimales. ej: 1300.00, El separador decimal es colocado automáticamente por el sistema.',' (Campo Opcional)')">       		
                                     </td>
                               </tr>
                               <tr>
                                    <td>ADJUNTAR ARCHIVO:</td>
                                    <td>														         
                                         <input type="file" id="archivo" name="archivo" maxlength="50" size="30">									
                                         <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Adjuntar Archivo','Maximo de 1000 KB (1 MB) de tamaño en el Archivo Adjuntar.', '  (Campo Opcional)')">																					
                                     </td>
                               </tr>
                               <tr>
                                    <td>PRIORIDAD DEL TICKET:</td>
                                    <td>
                                        <?php
                                            if($resultados_ticket['prioridad_ticket']==1){
                                                echo '<input id="prioridad"  class="validate[required] radio" name="prioridad" value="1"  checked="true" type="radio"> NORMAL';
                                                    echo '<input id="prioridad" class="validate[required] radio" name="prioridad" value="2"   type="radio"> <font color="ffd200">ALTA</font>';
                                                    echo '<input id="prioridad" class="validate[required] radio" name="prioridad" value="3"  type="radio"> <font color="Red">URGENTE</font>';
                                            }elseif($resultados_ticket['prioridad_ticket']==2){
                                                echo '<input id="prioridad" class="validate[required] radio" name="prioridad" value="1"  type="radio"> NORMAL
                                                    <input id="prioridad" class="validate[required] radio" name="prioridad" value="2"   checked="true" type="radio"> <font color="ffd200">ALTA</font>
                                                    <input id="prioridad" class="validate[required] radio" name="prioridad" value="3"  type="radio"> <font color="Red">URGENTE</font>';
                                            }else{
                                                echo '<input id="prioridad" class="validate[required] radio" name="prioridad" value="1"  type="radio"> NORMAL
                                                    <input id="prioridad" class="validate[required] radio" name="prioridad" value="2"   type="radio"> <font color="ffd200">ALTA</font>
                                                    <input id="prioridad" class="validate[required] radio" name="prioridad" value="3"  checked="true" type="radio"> <font color="Red">URGENTE</font>';
                                            }
                                        ?>
                                     </td>
                               </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input  class="button" type="submit" name="save" value="   Enviar   " />			
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=gestion_tickets&cod_ticket=<?php echo $cod_ticket;?>'" value="Cerrar" name="cerrar" /> 
                    </td>													
                </tr> 
                
                <?php	}  ?>	
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
          //$('#fecha_deposito').mask('99/99/9999');
          $('#telefono').mask('(9999)-9999999');
          $('#celular').mask('(9999)-9999999');
          $('#telefono_trabajo').mask('(9999)-9999999');
          $('#telefono_fax').mask('(9999)-9999999');
          $('#rif_iglesia').mask('~-99999999-9');
          //$('#phoneext').mask("(999) 999-9999? x99999");
          //$("#tin").mask("99-9999999");
          //$("#ssn").mask("999-99-9999");
          //$("#product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("Ha escrito lo siguiente: "+this.val());}});
          //$("#eyescript").mask("~9.99 ~9.99 999");

       });
           
    function ue_vehiculo_add(){
        var mensaje="";
        var ced_rif=document.QForm.cedula_rif.value;
        var nombreapellido=document.QForm.nombreapellido.value;
        var solicitanteload=document.QForm.solicitanteload.value;
        var ced_rif=ced_rif.toUpperCase();
        window.open("ticket/vehiculo_add.php?cedula_rif="+ced_rif+"&nombreapellido="+nombreapellido+"&solicitanteload="+solicitanteload,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=350,left=50,top=50,location=no,resizable=no");
        
    } 	   
    function vehiculo_add(){
        cargarContenidoVehiculo();
    } 	   
    function ue_catalogo_tramite()	{
        var mensaje="";
//        var unidad=document.QForm.cod_unidad.options[document.QForm.cod_unidad.selectedIndex].value;
        var unidad=document.QForm.cod_unidad.value;
        window.open("ticket/catalogo_productos.php?cod_uni="+unidad,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=350,left=50,top=50,location=no,resizable=no");
    }
	   
</script>