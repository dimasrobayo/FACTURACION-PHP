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
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_POST[save])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];
        $password1 = $_POST['confirm_password'];
        $nivel_acceso = $_POST['nivel_acceso'];
        $status = 1;
        $unidad = $_POST['cod_unidad'];
        
        
        $query="SELECT * FROM $sql_tabla WHERE usuario='$usuario'";
        $result = pg_query($query)or die(pg_last_error());
        $total_encontrados = pg_num_rows ($result);
        pg_free_result($result);
        
        if ($total_encontrados != 0){
                $error="Error";
                $div_menssage='<div align="left"><h3 class="error"><font color="#CC0000" style="text-decoration:blink;">Error: El Usuario ya est&aacute; registrado.</font></h3></div>';	
        }else {	
            $query="SELECT * FROM $sql_tabla WHERE cedula_usuario='$cedula'";
            $result = pg_query($query)or die(pg_last_error());
            $result_user = pg_fetch_array($result);
            pg_free_result($result);
            
            if ($result_user[0]) {
                $error="Error";
                $div_menssage='<div align="left"><h3 class="error"><font color="#CC0000" style="text-decoration:blink;">Error: Ya Existe un Registro con la misma C&eacute;dula: <font color="blue">'.$result[0].'</font>; por favor verifique los datos!</font></h3></div>';				
            }else {	
                $error="bien";	
                $usuario=stripslashes($usuario);
                $passmd5=md5($password);
                
                $query="SELECT insert_usuario($cedula,'$nombre','$apellido','$usuario','$passmd5',$nivel_acceso, $status,$unidad)";
                $result = pg_query($query)or die(pg_last_error());
                if(pg_affected_rows($result)){
                    $error="bien";
                }					
            }					
        }
        

    } //fin del add        
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
                    USUARIOS
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data"> 
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        <img src="images/add.png" width="16" height="16" alt="Nuevo Registro">
                        REGISTRO NUEVO USUARIO
                    </th>
                </tr>

                <?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Datos Registrados con &eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=usuarios";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=usuarios" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>

                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
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
                                            <input autofocus="true" class="validate[required,custom[integer]] text-input" type="text" id="cedula" name="cedula" value="<?php if ($error!="") echo $cedula; ?>" maxlength="12" size="12"/>
                    			</td>                       
                		</tr>

                		<tr>
                    			<td align="right">
                        			Nombres: <font color="Red">(*)</font>
                    			</td>
                    			<td>
                        			<input class="validate[required] text-input" type="text" id="nombre"  name="nombre" value="<?php if ($error!="") echo $nombre; ?>"  maxlength="50" size="25"/>			
                   			 </td>			
                		</tr>

                		<tr>
                    			<td align="right">
                        			Apellidos: <font color="Red">(*)</font>
                    			</td>
                    			<td>
                        			<input class="validate[required] text-input" type="text" id="apellido"  name="apellido"  value="<?php if ($error!="") echo $apellido; ?>" maxlength="50" size="25"/>
                    			</td>			
                		</tr>

				<tr>
		    			<td width="20%" align="right">
                    				Unidad/Departamento: <font color="Red">(*)</font>
                   			</td>
					<td>
                                            <select id="cod_unidad" name="cod_unidad" size="0" class="validate[required]">
                                                <option value="">----</option>	        
                                                <?php
                                                    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
                                                    $consulta=pg_query("select * from unidades order by cod_unidad");
                                                    while ($array_consulta=pg_fetch_array($consulta)) {
                                                        if( $array_consulta[0]==$cod_unidad){
                                                            echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';
                                                        }else{
                                                            echo '<option value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';
                                                        }
                                                    }
                                                    pg_free_result($consulta);
                                                ?>
                                            </select>
                    			</td>
				</tr>

                		<tr>
                    			<td align="right">
                        			Usuario: <font color="Red">(*)</font>
                    			</td>
                    			<td>
                        			<input type="text" id="usuario" name="usuario" value="<?php if ($error!="") echo $usuario; ?>" class="validate[required] text-input" maxlength="50" size="20"/>				
                    			</td>			
                		</tr>

                		<tr>
                    			<td align="right">
                        			Password: <font color="Red">(*)</font>
                    			</td>
                    			<td>
                        			<input class="inputbox validate[required]" id="password" name="password" type="password" maxlength="30" size="20"/>	
                    			</td>			
                		</tr>

                		<tr>
                    			<td align="right">
                        			Confirmar Password: <font color="Red">(*)</font>
                    			</td>
                    			<td>
                        			<input class="validate[required,equals[password]]" type="password" id="password1" name="confirm_password" maxlength="30" size="20"/>
                    			</td>			
                		</tr>

                		<tr>
                                    <td align="right">
                                            Nivel de Acceso: <font color="Red">(*)</font>
                                    </td>
                                    <td>			
                                        <select id="nivel_acceso" name="nivel_acceso" class="validate[required]" size="0" class="options">
                                            <option  value="">----</option>	
                                            <?php
                                                $consulta=pg_query("select * from niveles_acceso order by codigo_nivel");
                                                while ($array_consulta=pg_fetch_array($consulta)){
                                                    if( $array_consulta[0]==$nivel_acceso){
                                                        echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                    }else{
                                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                    }
                                                    
                                                }
                                                pg_free_result($consulta);
                                            ?>
                                        </select>	
                                    </td>
                                </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=usuarios'" value="Cerrar" name="cerrar" />  
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
