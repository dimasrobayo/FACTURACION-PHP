<?php //seccion de mensajes del sistema.
//
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
    
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

//seccion para recibir los datos y modificarlos.
if (isset($_GET['codigo_rubro'])){
    $datos_modificar= $_GET['codigo_rubro'];

    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

    //se le hace el llamado a la funcion de insertar.	
    $datos_consulta = pg_query("SELECT rubro.codigo_rubro, tipo_actividad.codigo_tipo, tipo_actividad.descripcion, rubro.descripcion FROM tipo_actividad, rubro where tipo_actividad.codigo_tipo = rubro.codigo_tipo and rubro.codigo_rubro = $datos_modificar order by rubro.codigo_rubro") or die("No se pudo realizar la consulta a la Base de datos");

    $resultados1=pg_fetch_array($datos_consulta);
    pg_free_result($datos_consulta);
    pg_close();
}

if (isset($_POST[save]))
{//se resive los datos a ser modificados
    $codigo_rubro = $_POST['codigo_rubro'];
    $codigo_tipo = $_POST['codigo_tipo'];
    $descripcion = $_POST['descripcion'];

    $error="bien";	
    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

    //se le hace el llamado a la funcion de insertar.	
    $inserta_registro = pg_query("SELECT update_rubro($codigo_rubro, $codigo_tipo,'$descripcion')") or die('La consulta fall&oacute;: ' . pg_last_error());
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
        
        <table class="adminrubro" width="100%">
            <tr>
                <th>
                    RUBRO
                </th>
            </tr>
        </table>

        <form method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="3">
                        MODIFICAR DATOS DEL RUBRO
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
                    <td width="15%">
                        Codigo:				
                    </td>

                    <td width="85%">
                        <input class="inputbox" type="text" id="codigo_rubro" name="codigo_rubro" readonly="true" value="<?php echo $resultados1[codigo_rubro]; ?>" maxlength="12" size="12"/>
                        <font color="#ff0000">*</font>
                        <script type="text/javascript">
                            var codigo = new LiveValidation('codigo_rubro');
                            codigo.add(Validate.Presence);
                        </script>				
                    </td>               
                </tr>
                
                <tr>
                    <td width="15%">
                        Tipo de Actividad:
                    </td>

                    <td>
                        <select id="codigo_tipo" name="codigo_tipo" size="0" class="options">
                            <option value="<?php echo $resultados1[codigo_tipo]; ?>">--Selecciones para Modificar--</option>	        
                            <?php
                                $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
                                $consulta_tipo=pg_query("select * from tipo_actividad order by codigo_tipo");
                                while ($array_tipo=pg_fetch_array($consulta_tipo))
                                {
                                        echo '<option value="'.$array_tipo[0].'">'.$array_tipo[1].'</option>';
                                }
                                pg_free_result($consulta_tipo);
                            ?>
                        </select>
                        <?php echo $resultados1[2]; ?>
                        <script type="text/javascript">
                            var codigo = new LiveValidation('codigo_tipo');
                            codigo.add(Validate.Presence);
                            codigo.add( Validate.texto );
                        </script>
                        <font color="#ff0000">*</font>				
                    </td>			
                </tr>

                <tr>
                    <td>
                        Descripci&oacute;n:
                    </td>

                    <td>
                        <input class="inputbox" type="text" id="descripcion" name="descripcion" value="<?php echo $resultados1[descripcion]; ?>" maxlength="45" size="45"/>
                        <font color="#ff0000">*</font>
                        <script type="text/javascript">
                            var codigo = new LiveValidation('descripcion');
                            codigo.add(Validate.Presence);
                        </script>
                    </td>						
                </tr>

                <tr colspan="2" class="botones" align="center">
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=rubro'" value="Cerrar" name="cerrar" />  
                    </td>
                </tr>
            <?php }  ?>	
            </table>    
        </form>		
    </div>
</div>