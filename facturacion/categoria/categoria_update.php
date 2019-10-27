<?php //seccion de mensajes del sistema.
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

//seccion para recibir los datos y modificarlos.
if (isset($_GET['codigo_categoria'])){
    $datos_modificar= $_GET['codigo_categoria'];

    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

    //se le hace el llamado a la funcion de insertar.	
    $datos_consulta = pg_query("SELECT * FROM categoria_concepto where codigo_categoria = $datos_modificar") or die("No se pudo realizar la consulta a la Base de datos");

    $resultados1=pg_fetch_array($datos_consulta);
    pg_free_result($datos_consulta);
    pg_close();
}

if (isset($_POST[save]))
{//se resive los datos a ser modificados
    $codigo_categoria = $_POST['codigo_categoria'];
    $nombre_categoria = $_POST['nombre_categoria'];
    $status = $_POST['status'];

    $error="bien";	
    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

    //se le hace el llamado a la funcion de insertar.	
    $inserta_registro = pg_query("SELECT update_categoria($codigo_categoria,'$nombre_categoria',$status)") or die("NO SE PUEDE MODIFICAR LOS DATOS EN LA BASE DE DATOS.");		
    $result_insert=pg_fetch_array($inserta_registro);	
    $resultado_insert=$result_insert[0];

    pg_free_result($inserta_registro);
    //header ("Location: $pagina");
    pg_close();	//exit;	   
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
        
        <table class="admincategoria" width="100%">
            <tr>
                <th class="admincategoria">
                    REGISTRAR NUEVA CATEGORIA:
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="3">
                        MODIFICAR DATOS DEL CATEGORIA
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
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos del Categoria:</b></td>
                </tr>
                
                <tr>
                    <td width="15%">
                        C&oacute;digo del Categoria:				
                    </td>

                    <td width="85%">
                        <input class="inputbox" type="text" id="codigo_categoria" name="codigo_categoria" readonly="true" value="<?php echo $resultados1[codigo_categoria]; ?>" maxlength="12" size="12"/>
                        <font color="#ff0000">*</font>
                        <script type="text/javascript">
                            var codigo = new LiveValidation('codigo_categoria');
                            codigo.add(Validate.Presence);
                        </script>				
                    </td>               
                </tr>

                <tr>
                    <td>
                        Categoria:
                    </td>

                    <td>
                        <input class="inputbox" type="text" id="nombre_categoria" name="nombre_categoria" value="<?php echo $resultados1[nombre_categoria]; ?>" maxlength="45" size="45"/>
                        <font color="#ff0000">*</font>
                        <script type="text/javascript">
                            var codigo = new LiveValidation('nombre_categoria');
                            codigo.add(Validate.Presence);
                        </script>
                    </td>						
                </tr>

                <tr width="15%">
                    <td>
                        STATUS:
                    </td>

                    <td width="85%">
                        <select id="status" name="status" class="validate[required]" size="0">
                            <option value="<?php echo $resultados1[codigo_categoria]; ?>">--Selecciones para Modificar--</option>          
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>  
                        <script type="text/javascript">
                            var codigo = new LiveValidation('nombre_categoria');
                            codigo.add(Validate.Presence);
                        </script>            
                    </td>           
                </tr>

                <tr colspan="2" class="botones" align="center">
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=almacen'" value="Cerrar" name="cerrar" />  
                    </td>
                </tr>
            <?php }  ?>	
            </table>
        </form>			
    </div>
</div>
