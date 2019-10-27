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
?> 

<?php 
    if (isset($_POST[save])) {   // Insertar Datos del formulario
        $siglas = $_POST['siglas'];
        $nombre = $_POST['nombre'];
        $responsable = $_POST['responsable'];
        $cargo = $_POST['cargo'];
        $direccion = $_POST['direccion'];
        $telefono1 = $_POST['telefono1'];
        $telefono2 = $_POST['telefono2'];
        $email = $_POST['email'];
        $horario = $_POST['horario'];
        $unidad_online = '1';

        $query="insert into unidades (siglas_unidad,nombre_unidad,responsable_unidad,cargo_responsable,direccion_unidad,telefono_1,telefono_2,email_unidad,horario_unidad,status_unidad) values ('$siglas','$nombre','$responsable','$cargo','$direccion','$telefono1','$telefono2','$email','$horario',$unidad_online)";
        $result = pg_query($query)or die(pg_last_error());
        $error="bien";    
        
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
        <table class="adminunidades">
            <tr>
                <th>
                    DEPARTAMENTO/UNIDAD:
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr>
                    <th colspan="2" align="center">
                        <img src="images/add.png" width="16" height="16" alt="Nuevo Registro">
                        INGRESAR DATOS DE LA UNIDAD
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
                                            window.location="?view=unidades";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=unidades" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>
                
                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
		<tr>
                    <td colspan="2" height="16" align="left">
                        <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos de la Unidad:</b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="20%" >
                                        SIGLAS:
                                    </td>
                                    <td>
                                        <input class="inputbox" type="text" id="siglas" name="siglas" maxlength="20" size="20"/>				
                                    </td>			
                                </tr>

                                <tr>
                                    <td width="15%" >
                                        DEPARTAMENTO/UNIDAD: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <input class="validate[required] text-input" type="text" id="nombre" name="nombre" maxlength="50" size="50"/>				
                                    </td>			
                                </tr>
			
                                <tr>
                                    <td width="15%" >
                                        DIRECCIÓN:
                                    </td>
                                    <td>
                                        <input type="text" id="direccion" name="direccion" maxlength="100" size="50"/>			
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" >
                                        TELÉFONO 1:
                                    </td>
                                    <td>
                                        <input class="validate[custom[phone]] text-input" placeholder="(0414)-1234567" title="Ej.: (0414)-1234567" type="text" id="telefono1" name="telefono1" maxlength="15" size="12"/>				
                                    </td>			
                                </tr>
                                <tr>
                                    <td width="15%" >
                                        TELÉFONO 2:
                                    </td>
                                    <td>
                                        <input class="validate[custom[phone]] text-input" placeholder="(0414)-1234567" title="Ej.: (0414)-1234567" type="text" id="telefono2" name="telefono2" maxlength="15" size="12"/>			
                                    </td>			
                                </tr>
                                <tr>
                                    <td width="15%" >
                                        EMAIL:
                                    </td>
                                    <td>
                                        <input class="validate[custom[email]] text-input" placeholder="minombre@ejemplo.com" title="Ej.: minombre@ejemplo.com" type="text" id="email" name="email" maxlength="50" size="50"/>				
                                    </td>			
                                </tr>
                                <tr>
                                    <td width="15%" >
                                        HORARIO DE ATENCIÓN:
                                    </td>
                                    <td>
                                        <input class="inputbox" type="text" id="horario" name="horario" maxlength="50" size="50"/>
                                    </td>			
                                </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos del Responsable de la Unidad:</b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="20%" >
                                        NOMBRE DEL RESPONSABLE: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <input class="validate[required] text-input" type="text" id="responsable" name="responsable" maxlength="50" size="50"/>				
                                    </td>			
                                </tr>
                                <tr>
                                    <td width="15%" >
                                        CARGO:
                                    </td>
                                    <td>
                                        <input class="inputbox" type="text" id="cargo" name="cargo" maxlength="50" size="50"/>				
                                    </td>			
                                </tr>
                                
                            </tbody>
                        </table>	
                    </td>
                </tr>
                
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>INFORMACIÓN DE LA CATEGORIA ON-LINE</b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="20%" >
                                        <input type="checkbox" name="unidad_online">
                                        La Unidad estar&aacute; disponible On-Line: <font color="Red">(*)</font>
                                    </td>
                                </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=unidades'" value="Cerrar" name="cerrar" />  
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
	      $('#telefono1').mask('(9999)-9999999');
	      $('#telefono2').mask('(9999)-9999999');
	      
	});
</script>
