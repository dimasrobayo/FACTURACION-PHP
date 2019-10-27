<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
        exit;
    }
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_POST[save])) {
        $codest= $_POST['codest'];	
        $nombre_centro_acopio=$_POST['nombre_centro_acopio'];
        $red_distribucion=$_POST['red_distribucion'];
        $capacidad_almacenamiento=$_POST['capacidad_almacenamiento'];
        $unidad_medida=$_POST['unidad_medida'];
        $direccion_centro_acopio=$_POST['direccion_centro_acopio'];
        $responsable=$_POST['responsable'];
        $responsable_insert = preg_replace("/\s+/", "", $responsable);
        $responsable_insert = str_replace("-", "", $responsable_insert);

        if (($codmun!="") && ($codest!="") && ($codpar!="") && ($comunidad!="") ) {
            $query="insert into centro_acopio (codest,nombre_centro_acopio,red_distribucion,capacidad_almacenamiento,unidad_medida,direccion_centro_acopio,responsable) values ($codest,'$nombre_centro_acopio','$red_distribucion',$capacidad_almacenamiento,'$unidad_medida','$direccion_centro_acopio,responsable','$responsable')";
            $result = pg_query($query)or die(pg_last_error());
            if(pg_affected_rows($result)){
                $error="bien";
            }
            
        } else {
            $error="Error";
            $div_menssage='<div align="left">
                <h3 class="error">
                    <font color="red" style="text-decoration:blink;">
                        Error: Datos Incompletos, por favor verifique los datos!
                    </font>
                </h3>
            </div>';
        }
    }//fin del add
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

        <table class="admincentro_acopio" width="100%">
            <tr>
                <th>
                    CENTRO DE ACOPIO:
                </th>
            </tr>
        </table>
        
        <form id="QForm" name="QForm" method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr>
                    <th colspan="2" align="center">
                        <img src="images/add.png" width="16" height="16" alt="Nuevo Registro">
                        INGRESAR DATOS DE LA COMUNIDAD
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
                                            window.location="?view=centro_acopio";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=centro_acopio" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>

                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
                <tr>
                   <td colspan="2">
                       <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>

                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos de la Comunidad:</b></td>
                </tr>
 		 
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>						
                            <tr>
                                <td width="15%">
                                    ESTADO: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <select id="codest"  name="codest" class="validate[required]" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
                                        <option value="">----</option>
                                        <?php
                                            $consulta_sql=pg_query("SELECT * FROM estados order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
                                            while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                                if ($error!=""){
                                                    if ($array_consulta[1]==$codest){
                                                        echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                    }else {
                                                        echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                    }
                                                }else {
                                                    if ($array_consulta[1]==$cod_estado){
                                                        echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                    }else {
                                                        echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                    }
                                                }
                                            }
                                            pg_free_result($consulta_sql);
                                        ?>
                                    </select>
                                </td>	
                            </tr>

                            <tr>
                                <td>
                                    NOMBRE DEL CENTRO DE ACOPIO: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <input  type="text" id="nombre_centro_acopio" name="nombre_centro_acopio" value="<?php if ($error!='') echo $comunidad;?>" class="validate[required] text-input" size="50" maxlength="80"/>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    RED DE DISTRIBUCION: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <input  type="text" id="red_distribucion" name="red_distribucion" value="<?php if ($error!='') echo $comunidad;?>" class="validate[required] text-input" size="30" maxlength="50"/>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    CAPACIDAD DE ALMACENAMIENTO: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <input  type="text" id="capacidad_almacenamiento" name="capacidad_almacenamiento" value="<?php if ($error!='') echo $comunidad;?>" class="validate[required] text-input" size="10" maxlength="10"/>
                                    <select id="unidad_medida"  name="unidad_medida" class="validate[required]" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
                                        <option value="">----</option>
                                        <option value="TONELADA">TONELADA</option>
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    RESPONSABLE: <font color="Red">(*)</font>
                                </td>

                                <td>
                                   <div align="left">
                                       <input id="responsable" name="responsable" type="text"  value="" class="validate[required] text-input"  size="10" maxlength="12"/>
                                        
                                        <a href="javascript: ue_buscarresponsable();">
                                            <img src="images/busqueda.png" alt="Buscar" title="Buscar Colaborador" width="15" height="15" border="0">
                                        </a>

                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Cédula RIF','Ingrese la Cédula ó RIF.   Ej.: Cedula:V-123456 ó RIF:J-12345678-0', ' (Campo Requerido)')">                                                      
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <div id="ContenedorPersonas"> 
                                        <table class="adminform"  border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="15%">
                                                        NOMBRE DEL RESPONSABLE:
                                                    </td>

                                                    <td width="85%">
                                                        <input readonly="true" type="text" id="nombre_apellido" name="nombre_apellido" maxlength="50" size="50" />
                                                        TIPO PERSONA:
                                                        <input readonly="true" type="text" id="tipo_solicitante" name="tipo_solicitante"  maxlength="50" size="50" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        DIRECCION DEL RESPONSABLE:
                                                    </td>

                                                    <td>
                                                        <input readonly="true" type="text" id="direccion" name="direccion" maxlength="90" size="140" />         
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    DIRECCION DEL CENTRO DE ACOPIO: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <input type="text" id="direccion_centro_acopio" name="direccion_centro_acopio" maxlength="90" size="140" />         
                                </td>
                            </tr>
                        </tbody>
                        </table>	
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=centro_acopio'" value="Cerrar" name="cerrar" />  
                    </td>													
                </tr> 
            <?php }  ?>	
        </table>
    </form>     
    <br>	 
    </div>
</div>

<script type="text/javascript" >
    jQuery(function($) {
        $.mask.definitions['~']='[VMvm]';
        $('#telefono').mask('(9999)-9999999');
        $('#celular').mask('(9999)-9999999');
        $('#telefono_trabajo').mask('(9999)-9999999');
        $('#telefono_fax').mask('(9999)-9999999');
        $('#rif').mask('~-9999?9999-9',{placeholder:" "});
        $('#cedula_solicitante').mask('~-9999?99999',{placeholder:" "});
    });
    function ue_buscarresponsable() {                                        
        document.QForm.responsable.value="";                                            
        window.open("centro_acopio/centro_acopio_load.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=310,left=50,top=50,location=no,resizable=no");
    } 
    
    function ue_cliente_add()   {
        var mensaje="";
        var cedula_rif=document.QForm_comunidad.jefe_comunidad.value;
        var cedula_rif=cedula_rif.toUpperCase();
        window.open("facturacion/factura/cliente_add.php?cedula_rif="+cedula_rif,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=350,left=50,top=50,location=no,resizable=no");
    } 
</script>