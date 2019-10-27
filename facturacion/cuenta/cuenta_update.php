<?php //seccion de mensajes del sistema.
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
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$type;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_GET['codigo_cuenta'])){
	$datos_modificar= $_GET['codigo_cuenta'];

	//se le hace el llamado a la funcion de insertar.	
	$query = "SELECT * FROM cuenta,empresa,banco,tipo_cuenta where codigo_cuenta = $datos_modificar and cuenta.rif_empresa=empresa.rif_empresa and cuenta.codigo_banco=banco.codigo_banco and cuenta.codigo_tipo_cuenta=tipo_cuenta.codigo_tipo_cuenta";	
        $result = pg_query($query)or die(pg_last_error());
        $resultados=pg_fetch_array($result);	
        pg_free_result($result);
    }

    if (isset($_POST[save])){//se resive los datos a ser modificados
        $codigo_cuenta = $_POST['codigo_cuenta'];
        $rif_empresa = $_POST['rif_empresa'];
        $codigo_banco = $_POST['codigo_banco'];
        $codigo_tipo = $_POST['codigo_tipo_cuenta'];
        $cuenta = $_POST['cuenta'];
        $observacion = $_POST['observacion'];
        
        
        // Consultamos si existe la descripcion
        $query = "SELECT * FROM cuenta,empresa,banco,tipo_cuenta where codigo_cuenta = $codigo_cuenta and cuenta.rif_empresa=empresa.rif_empresa and cuenta.codigo_banco=banco.codigo_banco and cuenta.codigo_tipo_cuenta=tipo_cuenta.codigo_tipo_cuenta";
        $result = pg_query($query)or die(pg_last_error());
        $resultados=pg_fetch_array($result);
        pg_free_result($result);
        
        if($resultados[n_cuenta]!=$cuenta){
            // Consultamos si existe la descripcion
            $query = "SELECT * FROM cuenta WHERE n_cuenta='$cuenta'";
            $result = pg_query($query)or die(pg_last_error());
            $resultado_load=pg_fetch_array($result);
            pg_free_result($result);
            
            if (!$resultado_load[0]) {
                //se le hace el llamado a la funcion de editar
                $query="SELECT update_cuenta($codigo_cuenta,'$rif_empresa', $codigo_banco, $codigo_tipo, '$cuenta', '$observacion')";
                $result = pg_query($query)or die(pg_last_error());
                $error="bien";
            } else {
                $error="Error";
                $div_menssage='<div align="left">
                            <h3 class="error">
                                <font color="red" style="text-decoration:blink;">
                                    Error: Ya Existe un Registro con la Cuenta: <font color="blue">'.$cuenta.'</font>; por favor verifique los datos!
                                </font>
                            </h3>
                        </div>';	
            }
            
        }else{
            //se le hace el llamado a la funcion de editar
            $query="SELECT update_cuenta($codigo_cuenta,'$rif_empresa', $codigo_banco, $codigo_tipo, '$cuenta', '$observacion')";
            $result = pg_query($query)or die(pg_last_error());
            $error="bien";
        }
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
        
        <table class="admincuenta" width="100%">
            <tr bgcolor="#55baf3">
                <th colspan="8">
                    REGISTRO DE CARGA FAMILIAR            
                </th>
            </tr>

        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm"  enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="3">
                        MODIFICAR DATOS DEL BANCO
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
                                            window.location="?view=cuenta";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=cuenta" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>

                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
                <tr>
                    <td width="15%">
                        C&oacute;digo de Cuenta:				
                    </td>

                    <td width="85%">
                        <input class="inputbox" type="text" id="codigo_cuenta" name="codigo_cuenta" size="6" readonly="true" value="<?php echo $resultados[codigo_cuenta]; ?>"/>
                    </td>               
                </tr>
			
                <tr>
                    <td width="12%">
                        Empresa:
                    </td>

                    <td>
                        <select id="rif_empresa" name="rif_empresa" size="0" class="validate[required]" >
                            <option value="<?php echo $resultados[rif_empresa]; ?>"><?php echo $resultados['nombre_empresa']; ?></option>	        
                            <option value="">----</option>
                            <?php
                                $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

                                $consulta=pg_query("select * from empresa order by nombre_empresa");
                                while ($array_consulta=pg_fetch_array($consulta)){
                                    echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                }
                                pg_free_result($consulta);
                            ?>
                        </select>
                        <font color="#ff0000">*</font>				
                    </td>			
                </tr>	

                <tr>
                    <td width="12%">
                        Banco:
                    </td>

                    <td>
                        <select id="codigo_banco" name="codigo_banco" size="0" class="validate[required]" >
                            <option value="<?php echo $resultados[codigo_banco]; ?>"><?php echo $resultados[nombre_banco]; ?></option>	        
                            <option value="">----</option>
                            <?php
                                $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

                                $consulta=pg_query("select * from banco order by nombre_banco");
                                while ($array_consulta=pg_fetch_array($consulta)){
                                    echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                }
                                pg_free_result($consulta);
                            ?>
                        </select>
                        <font color="#ff0000">*</font>				
                    </td>			
                </tr>		
				
                <tr>
                    <td width="12%">
                        Tipo de Cuenta:
                    </td>

                    <td>
                        <select id="codigo_tipo_cuenta" name="codigo_tipo_cuenta" size="0" class="validate[required]" >
                            <option value="<?php echo $resultados[codigo_tipo_cuenta]; ?>"><?php echo $resultados[nombre_tipo_cuenta]; ?></option>	        
                            <option value="">----</option>
                            <?php
                                $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

                                $consulta=pg_query("select * from tipo_cuenta order by nombre_tipo_cuenta");
                                while ($array_consulta=pg_fetch_array($consulta)){
                                    echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                }
                                pg_free_result($consulta);
                            ?>
                        </select>
                        <font color="#ff0000">*</font>				
                    </td>			
                </tr>
						
                <tr>
                    <td width="15%">
                        Cuenta:
                    </td>

                    <td width="85%">
                        <input class="validate[required] text-input" value="<?php echo $resultados[n_cuenta]; ?>" class="inputbox" type="text" id="cuenta" name="cuenta" maxlength="30" size="30"/>
                        <font color="#ff0000">*</font>		
                    </td>			
                </tr>
			
                <tr>
                    <td width="15%">
                        Observaci&oacute;n:
                    </td>

                    <td width="85%">
                        <textarea id="observacion" name="observacion" cols="80" rows="4"><?php echo $resultados[observacion]; ?></textarea>				
                    </td>			
                </tr>
						
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=cuenta'" value="Cerrar" name="cerrar" />  
                    </td>													
                </tr>
                <?php }  ?>	
            </table>    
        </form>					
    </div>
</div>
