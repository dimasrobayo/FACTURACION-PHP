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

    if (isset($_GET['cod_unidad'])){
    	$datos_modificar= $_GET['cod_unidad'];

	$query="SELECT * FROM unidades where cod_unidad = $datos_modificar";
    	$result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);	
        pg_free_result($result);
    }
    
?>

<div align="center" class="centermain">
    <div class="main"> 
        <table class="adminunidades">
            <tr>
                <th>
                    Departamento/Unidad:
                    <small>
                        MODIFICANDO DATOS DEL REGISTRO
                    </small>
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        MODIFICAR DATOS DEL DEPARTAMENTO SELECCIONADO
                    </th>
                </tr>

                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>                

		                <tr>
                		    <td width="15%" align="right">
                		        C&oacute;digo: <font color="Red">(*)</font>
                		    </td>		
                		    <td width="85%">
                		        <input id="cod_unidad" name="cod_unidad" value="<?php echo $resultado[cod_unidad]; ?>" readonly="true" class="inputbox" type="text"/>
                		    </td>                       
                		</tr>

		                <tr>
		                    <td width="15%" align="right">
		                        Siglas:
		                    </td>
		                    <td>
		                        <input class="inputbox" type="text" id="siglas" name="siglas" value="<?php echo $resultado[siglas_unidad]; ?>" readonly="true" maxlength="20" size="20"/>				
                   		    </td>			
		                </tr>
		
                		<tr>
		                    <td width="15%" align="right">
		                        Departamento/Unidad: <font color="Red">(*)</font>
		                    </td>
		                    <td>
		                        <input type="text" id="nombre" name="nombre" value="<?php echo $resultado[nombre_unidad]; ?>" readonly="true" maxlength="50" size="50"/>
                		    </td>			
                		</tr>
			
                		<tr>
                		    <td width="15%" align="right">
                		        Direcci&oacute;n:
                		    </td>
                		    <td>
                		        <input class="inputbox" type="text" id="direccion" name="direccion" value="<?php echo $resultado[direccion_unidad]; ?>" readonly="true" maxlength="100" size="100"/>			
		                    </td>
		                </tr>

		                <tr>
		                    <td width="15%" align="right">
		                        Tel&eacute;fono 1:
		                    </td>
		                    <td>
		                        <input type="text" id="telefono1" name="telefono1" value="<?php echo $resultado[telefono_1]; ?>" readonly="true" maxlength="50" size="50"/>				
		                    </td>			
		                </tr>

		                <tr>
		                    <td width="15%" align="right">
		                        Tel&eacute;fono 2:
		                    </td>
		                    <td>
		                        <input readonly="true" type="text" id="telefono2" name="telefono2" value="<?php echo $resultado[telefono_2]; ?>" maxlength="50" size="50"/>			
		                    </td>			
		                </tr>

		                <tr>
		                    <td width="15%" align="right">
		                        eMail:
		                    </td>
		                    <td>
		                        <input type="text" id="email" name="email" value="<?php echo $resultado[email_unidad]; ?>" readonly="true" maxlength="50" size="50"/>				
		                    </td>			
		                </tr>

		                <tr>
		                    <td width="15%" align="right">
		                        Horario:
		                    </td>
		                    <td>
		                        <input class="inputbox" type="text" id="horario" name="horario" value="<?php echo $resultado[horario_unidad]; ?>" readonly="true" maxlength="50" size="50"/>				
		                    </td>			
		                </tr>

		                <tr bgcolor="#55baf3" align="center">
		                    <th colspan="2">
		                        Datos del Responsable del Departamento/Unidad
		                    </th>
		                </tr>

		                <tr>
		                    <td width="15%" align="right">
		                        Responsable: <font color="Red">(*)</font>
		                    </td>
		                    <td>
		                        <input type="text" id="responsable" name="responsable" value="<?php echo $resultado[responsable_unidad]; ?>" readonly="true" maxlength="50" size="50"/>			
		                    </td>			
		                </tr>

		                <tr>
		                    <td width="15%" align="right">
		                        Cargo del Resposable:
		                    </td>
		                    <td>
		                        <input class="inputbox" type="text" id="cargo" name="cargo" value="<?php echo $resultado[cargo_responsable]; ?>" readonly="true" maxlength="50" size="50"/>				
		                    </td>			
		                </tr>
			
                            </tbody>
                        </table>	
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=unidades'" value="Cerrar" name="cerrar" />  
                    </td>													
                </tr> 
        </table>
    </form>     
    <br>	 
    </div>
</div> 
