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
        $pass = $_POST['password1'];
        $error="bien";		

        $passmd5=md5($pass);

	$query="SELECT update_usuario_clave($cedula,'$passmd5')";
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
                    PERFIL DEL USUARIO:
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        <img src="images/edit.png" width="16" height="16" alt="Editar Registro">
                        MODIFICAR CLAVE DEL USUARIO
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
		                    <td width="15%">
                		        C&oacute;digo Usuario:
                		    </td>		
                		    <td width="85%">
                		        <input id="cedula" name="cedula" value="<?php echo $result_usuarios[cedula_usuario]; ?>" readonly="true" class="inputbox" type="text"/>           
                		    </td>
                		</tr>
			
                		<tr>
                		    <td>
                		        Nombres:
                		    </td>	
                		    <td>
                		        <input value="<?php echo $result_usuarios[nombre_usuario]; ?>" readonly="true" class="inputbox" type="text" id="nombre" name="nombre" maxlength="25" size="25"/>
		                    </td>			
                		</tr>	
                
                		<tr>
                		    <td>
                		        Apellidos:
                		    </td>
                		    <td>
                		        <input value="<?php echo $result_usuarios[apellido_usuario]; ?>" readonly="true" class="inputbox" type="text" id="apellido"  name="apellido" maxlength="25" size="25"/>
                		    </td>			
                		</tr>
			
                		<tr>
                		    <td>
                		        Login Usuario:
                		    </td>
                		    <td>
                		        <input value="<?php echo $result_usuarios[usuario]; ?>" readonly="true" class="inputbox" type="text" id="usuario" name="usuario" maxlength="20" size="20"/>
		                    </td>			
                		</tr>
			
                		<tr>
                		    <td>
                		        Nuevo Password: <font color="Red">(*)</font>
                		    </td>		
                		    <td>
                                        <input autofocus="true" class="inputbox validate[required]" id="password1" name="password1" type="password" maxlength="30" size="20"/>	
                		    </td>			
                		</tr>
			
                		<tr>
                		    <td>
                		        Confirmar Password: <font color="Red">(*)</font>
                		    </td>		
                		    <td>
                		        <input class="validate[required,equals[password1]]" type="password" id="password2" name="password2" maxlength="30" size="20"/>	
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
