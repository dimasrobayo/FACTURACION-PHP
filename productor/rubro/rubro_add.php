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

if (isset($_GET['error']))
{
    $error_accion_ms[0]= "La Empresa No puede ser Borrada.<br>Si desea borrarlo, primero cree uno nuevo.";
    $error_accion_ms[1]= "Datos incompletos.";
    $error_accion_ms[2]= "Contrase&ntilde;as no coinciden.";
    $error_accion_ms[3]= "El Nivel de Acceso ha de ser num&eacute;rico.";
    $error_accion_ms[4]= "El Usuario ya est&aacute; registrado.";
    $error_accion_ms[5]= "Ya existe un usuario con el n&uacute;mero de c&eacute;dula que usted introdujo.";
    $error_accion_ms[6]= "El n&uacute;mero de c&eacute;dula que usted introdujo no es v&aacute;lido.";
    $error_cod = $_GET['error'];
}
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

if (isset($_POST[save]))
{
    $codigo_tipo = $_POST['codigo_tipo'];
    $descripcion = $_POST['descripcion'];

    if (($descripcion==""))
    {
        $error='<div align="left">
                    <h3 class="error">
                        <font color="red" style="text-decoration:blink;">
                            Error: Datos Incompletos, por favor verifique los datos!
                        </font>
                    </h3>
                </div>';
    }
    else
    {
        require("conexion/aut_config.inc.php");
        $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

        $error="bien";

        $inserta_registro = pg_query("insert into rubro (codigo_tipo, descripcion) values ($codigo_tipo,'$descripcion')") or die("NO SE PUEDE INSERTAR LA EMPRESA EN LA BASE DE DATOS.");	
        $result_insert=pg_fetch_array($inserta_registro);	
        pg_free_result($inserta_registro);
        $resultado_insert=$result_insert[0];
        pg_close();	
        //exit;
    } 		   
}//fin del add        
?>

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
        
        <table class="adminrubro" width="100%">
            <tr>
                <th>
                    REGISTRAR NUEVO RUBRO:
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr>
                    <th colspan="2" align="center">
                        <img src="images/add.png" width="16" height="16" alt="Nuevo Registro">
                        INGRESAR DATOS DEL RUBRO
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
                                            window.location="?view=rubro";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=rubro" name="Continuar"> Continuar </a>]
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
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos del Rubro:</b></td>
                </tr>
                
                <tr>
                    <td width="15%">
                        Tipo de Actividad:
                    </td>

                    <td>
                        <select id="codigo_tipo" name="codigo_tipo" size="0" class="options">
                            <option value="">----</option>	        
                            <?php
                                $consulta=pg_query("select * from tipo_actividad order by codigo_tipo");
                                while ($array_consulta=pg_fetch_array($consulta))
                                {
                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                }
                                pg_free_result($consulta);
                            ?>
                        </select>
                        <script type="text/javascript">
                            var codigo = new LiveValidation('codigo_tipo');
                            codigo.add(Validate.Presence);
                            codigo.add( Validate.texto );
                        </script>
                        <font color="#ff0000">*</font>				
                    </td>			
                </tr>

                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="15%" >
                                        Descripci&oacute;n: <font color="Red">(*)</font>
                                    </td>

                                    <td>
					<input class="inputbox" type="text" id="descripcion" name="descripcion" maxlength="45" size="45"/>
                                    </td>			
                                </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=tipo_actividad'" value="Cerrar" name="cerrar" />  
                    </td>													
                </tr> 
                
                <?php }  ?> 
                
            </table>
        </form>
        <br>
    </div>
</div>