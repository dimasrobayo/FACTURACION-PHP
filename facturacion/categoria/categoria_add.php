<?php

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
$nombre_categoria = $_POST['nombre_categoria'];
$status = $_POST['status'];

if (($nombre_categoria==""))
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

    $inserta_registro = pg_query("insert into categoria_concepto (nombre_categoria, status) values ('$nombre_categoria', $status)") or die("NO SE PUEDE INSERTAR EL REGISTRO EN LA BASE DE DATOS.");	
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
        
        <table class="admincategoria" width="100%">
            <tr>
                <th>
                    REGISTRAR NUEVA CATEGORIA:
                </th>
            </tr>
        </table>
	
        <form method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                 <tr>
                    <th colspan="2" align="center">
                        <img src="images/add.png" width="16" height="16" alt="Nuevo Registro">
                        INGRESAR CATEGORIA
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
                                            window.location="?view=categoria_concepto";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=categoria_concepto" name="Continuar"> Continuar </a>]
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
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos de la Categoria:</b></td>
                </tr>
                
                <tr width="15%">
                    <td>
                        CATEGORIA:
                    </td>

                    <td width="85%">
                        <input class="validate[required]" type="text" id="nombre_categoria" name="nombre_categoria" maxlength="45" size="45"/>
                        <font color="#ff0000">*</font>			
                    </td>			
                </tr>

                <tr width="15%">
                    <td>
                        STATUS:
                    </td>

                    <td width="85%">
                        <select id="status" name="status" class="validate[required]" size="0">
                            <option value="">----</option>          
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>              
                    </td>           
                </tr>

                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=categoria_concepto'" value="Cerrar" name="cerrar" />  
                    </td>
                </tr>
                
                <?php }  ?> 
                
            </table>
        </form>
        
    </div>
</div>