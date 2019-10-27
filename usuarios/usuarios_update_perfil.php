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
    
    $datos_modificar= $_SESSION['id'];

    $query="SELECT * FROM usuarios where cedula_usuario = $datos_modificar";
    $result = pg_query($query)or die(pg_last_error());
    $result_usuarios=pg_fetch_array($result);	
    pg_free_result($result);

    if (isset($_POST[save])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $usuario = $_POST['usuario'];
        $unidad = $_POST['cod_unidad'];	

	$query="SELECT update_usuario($cedula,'$nombre','$apellido','$unidad')";
        $result = pg_query($query)or die(pg_last_error());
        $result_update=pg_fetch_array($result);
        pg_free_result($result);
        
        $error="bien";
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
        <table class="adminusuarios" width="100%">
            <tr>
                <th>
                    USUARIOS:
                </th>
            </tr>
        </table>
        
	<form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
	    <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        <img src="images/edit.png" width="16" height="16" alt="Editar Registro">
                        MODIFICAR DATOS DEL USUARIO
                    </th>
                </tr>

                <?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Datos Modificados con &eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=home";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=home" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>

                <?php	} else{ 	?>   <!-- Mostrar formulario Original -->
                
	        <tr>
            	    <td colspan="2" height="16" align="left" >
            	        <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>            

		                <tr>
		                    <td width="15%" align="right">
		                        C&oacute;digo Usuario: <font color="Red">(*)</font>
		                    </td>
		                    <td width="85%">
		                        <input id="cedula" name="cedula" value="<?php echo $result_usuarios[cedula_usuario]; ?>" readonly="true" class="validate[required]" type="text"/>
		                    </td>                       
		                </tr>
			
		                <tr>
		                    <td align="right">
		                        Nombres: <font color="Red">(*)</font>
		                    </td>        
		                    <td>
                                        <input autofocus="true" value="<?php echo $result_usuarios[nombre_usuario]; ?>" class="validate[required] text-input" type="text" id="nombre" name="nombre" maxlength="50" size="25"/>
		                    </td>			
                		</tr>
			
                		<tr>
                		    <td align="right">
                		        Apellidos: <font color="Red">(*)</font>
                		    </td>		
		                    <td>
                		        <input value="<?php echo $result_usuarios[apellido_usuario]; ?>" class="validate[required] text-input" type="text" id="apellido"  name="apellido" maxlength="50" size="25"/>
		                    </td>			
                		</tr>
		
				<tr>
				    <td width="20%" align="right">
                		    	Unidad/Departamento: <font color="Red">(*)</font>
                		    </td>
                		    <td>
                                        <input id="cod_unidad" name="cod_unidad" value="<?php echo $result_usuarios[cod_unidad]; ?>" type="hidden"/>
                                        <select id="cod_unidad1" disabled="true" name="cod_unidad1" size="0" class="validate[required]">
                                            <option  value="">----</option>	        
                		                <?php
                                			$db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
		                                        $consulta=pg_query("select * from unidades order by cod_unidad");
                		                        while ($array_consulta=pg_fetch_array($consulta)) {
                                                            if($array_consulta[0]==$result_usuarios[cod_unidad]){
                                		     	      echo '<option value="'.$array_consulta[0].'" selected="selected">'.$array_consulta[2].'</option>';
                                                            }else{
                                                              echo '<option value="'.$array_consulta[0].'" >'.$array_consulta[2].'</option>';  
                                                            }
                                		        }
		                                        pg_free_result($consulta);
                		                   ?>
		                        </select>	
                		    </td>			
				</tr>
	
                		<tr>
                		    <td align="right">
                		        Login Usuario: <font color="Red">(*)</font>
                		    </td>		
		                    <td>
                                        <input value="<?php echo $result_usuarios[usuario]; ?>" type="text" id="usuario" readonly="true" name="usuario" class="validate[required] text-input" maxlength="50" size="20"/>
		                    </td>			
                		</tr>

                            </tbody>
                        </table>	
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
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
