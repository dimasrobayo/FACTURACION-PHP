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

$redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
$pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
$type=$_GET["type"];
$pagina=$pag.'?type='.$type;

//Conexion a la base de datos
$db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?>
	
<?php 
if (isset($_POST[save]))
{
    $codigo_grupo = $_POST['codigo_grupo'];

    if (($codigo_grupo==""))
    {
        $error='<div align="left">
            <h3 class="error">
                <font color="red" style="text-decoration:blink;">
                    Error: Datos Incompletos, por favor verifique los datos!
                </font>
            </h3>
        </div>';
    }
    
    if (($codigo_grupo=="todos"))
    {
        require("conexion/aut_config.inc.php");
        $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

        //consulta para buscar los militantes del grupo seleccionado
        $datos_consulta = pg_query("SELECT miembro.telefono_movil FROM red, grupo, detalle_grupo, miembro where red.codigo_red = grupo.codigo_red and grupo.codigo_grupo = detalle_grupo.codigo_grupo and detalle_grupo.cedula_miembro = miembro.cedula_miembro order by miembro.cedula_miembro") or die("No se pudo realizar la consulta a la Base de datos");
        while($resultados = pg_fetch_array($datos_consulta))
        {
            $destino = $resultados[telefono_movil];
            $sms=$_POST['sms'];
            $error="bien";

            //aqui es donde inicia el envio por grupo
            $array_cell=explode(',', $destino);
            foreach ($array_cell as $dest)
            {
                    str_replace('.','',$dest);
                    if ( strlen($dest)==11 and ((stristr($dest, '0414') or stristr($dest, '0424') or stristr($dest, '0426') or stristr($dest, '0416') or stristr($dest, '0412') )))
                    {
                            $datoom=date('Y').date('m').date('d').date('H').date('i').date('s');
                            $creatorId= $_SESSION['usuario_nombre'];
                            $send = pg_query("SELECT insert_outbox('$dest','$sms','$creatorId')") or die('La consulta fall&oacute;: ' . pg_last_error());	
                            $result_insert=pg_fetch_array($send);	
                            pg_free_result($send);
                            $resultado_insert=$result_insert[0];
                            $error="bien";
                            if ($send)
                            {
                                    $out=$out."SMS enviado a $dest<br>";
                            }
                            else
                            {
                                    $out=$out."Error al enviar SMS a $dest<br>";
                            }
                    }
                    else
                    {
                            $out='Número Telefónico no Válido';
                    }
            }
            unset ($GLOBALS);
            //unset ($_POST['cant']);
            //unset($_POST['cant']);
            $sms="";
            $cel="";
            $dest="";
        }   
    }
    else
    {
        require("conexion/aut_config.inc.php");
        $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

        //consulta para buscar los militantes del grupo seleccionado
        $datos_consulta = pg_query("SELECT miembro.telefono_movil FROM red, grupo, detalle_grupo, miembro where red.codigo_red = grupo.codigo_red and grupo.codigo_grupo = detalle_grupo.codigo_grupo and detalle_grupo.cedula_miembro = miembro.cedula_miembro and grupo.codigo_grupo = '$codigo_grupo' order by miembro.cedula_miembro") or die("No se pudo realizar la consulta a la Base de datos");
        while($resultados = pg_fetch_array($datos_consulta))
        {
            $destino = $resultados[telefono_movil];
            $sms=$_POST['sms'];
            $error="bien";

            //aqui es donde inicia el envio por grupo
            $array_cell=explode(',', $destino);
            foreach ($array_cell as $dest)
            {
                    str_replace('.','',$dest);
                    if ( strlen($dest)==11 and ((stristr($dest, '0414') or stristr($dest, '0424') or stristr($dest, '0426') or stristr($dest, '0416') or stristr($dest, '0412') )))
                    {
                            $datoom=date('Y').date('m').date('d').date('H').date('i').date('s');
                            $creatorId= $_SESSION['usuario_nombre'];
                            $send = pg_query("SELECT insert_outbox('$dest','$sms','$creatorId')") or die('La consulta fall&oacute;: ' . pg_last_error());	
                            $result_insert=pg_fetch_array($send);	
                            pg_free_result($send);
                            $resultado_insert=$result_insert[0];
                            $error="bien";
                            if ($send)
                            {
                                    $out=$out."SMS enviado a $dest<br>";
                            }
                            else
                            {
                                    $out=$out."Error al enviar SMS a $dest<br>";
                            }
                    }
                    else
                    {
                            $out='Número Telefónico no Válido';
                    }
            }
            unset ($GLOBALS);
            //unset ($_POST['cant']);
            //unset($_POST['cant']);
            $sms="";
            $cel="";
            $dest="";
        }
    } 		   
}//fin del add        
?>

<div align="center" class="centermain">
    <div class="main">  
        <table class="admin_sms_masivo_celula">
            <tr>
                <th>
                        SMS - Celular:
                        <small>
                            Nuevo
                        </small>
                </th>
            </tr>
        </table>
        
        <table class="adminform" border="0">
            <tr bgcolor="#55baf3">
                <th colspan="2">
                    Envio de un SMS Masivo
                </th>
            </tr>

            <tr>
                <th colspan="2">
                    <font color="#ff0000">Los Campos con * son Obligatorios</font>
                </th>
            </tr>
			
            <?php 
            if ((isset($_POST[save])) and ($error=="bien"))
            {		
            ?> 
			
            <tr>
                <td colspan="2" align="center">                        	
                    <br />
                    <strong>Resultado</strong>: 
                    <?php 
                    switch($resultado_insert)
                    {
                        case 0: 
                            echo 'No se pudo Procesar el Registro porque ya est&aacute; registrado en el sistema.';
                            break;
                        case 1: 
                            echo 'Registro Procesado con &eacute;xito';	
                            break;
                    }				
                    echo '<br />'.$msg;
                    ?>
                    <br />	
                </td>
            </tr> 
			
            <table class="adminform" align="center">
            <tr align="center">
                <td width="100%" valign="top" align="center">
                    <div id="cpanel">
                        <div style="float:right;">
                            <div class="icon">
                                <a href="index2.php?type=por_enviar">
                                    <img src="images/sms_por_enviar.png" alt="salir" align="middle"  border="0" />
                                    <span>Gestor de Datos</span>
                                </a>
                            </div>
                        </div>	
                    </div>
                </td>
            </tr>
            </table>
			
            <?php 
            }
            else
            {
            ?> 

            <?php echo $error;?>
	
            <form method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <tr width="15%">
                <td>
                    Celulas:
                </td>

                <td width="85%">
                    <select id="codigo_grupo" name="codigo_grupo">
                        <option value="">---</option>
                        <option value="todos">TODOS</option>
                        <?php 
                            $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
                            $consulta=pg_query("SELECT * FROM grupo order by codigo_grupo");
                            while ($array_consulta=pg_fetch_array($consulta))
                            {
                                echo '<option value="'.$array_consulta[codigo_grupo].'">'.$array_consulta[codigo_red].'-'.$array_consulta[descripcion_grupo].'</option>';
                            }
                            pg_free_result($consulta);
                        ?>
                    </select>
                    <font color="#ff0000">*</font>
                    <script type="text/javascript">
                        var codigo = new LiveValidation('codigo_red');
                        codigo.add(Validate.Presence);
                    </script>				
                </td>			
            </tr>

            <tr>
                <td>
                    Mensaje:
                </td>

                <td width="90%">
                    <textarea name="sms" id="sms" cols="70" rows="6" maxlength="160"></textarea>
                    <font color="#ff0000">*</font>
                    <script type="text/javascript">
                        var codigo = new LiveValidation('sms');
                        codigo.add(Validate.Presence);
                    </script>				
                </td>			
            </tr>
			
            <tr>
                <td bgcolor="#55baf3" colspan="2" align="center">
                    <input type="submit" class="button" name="save" value="  Enviar  " >
                    <input class="button" type="reset" value="Limpiar" name="Refresh"> 
                    <input  class="button" type="button" onClick="history.back()" value="Regresar">
                </td>
            </tr>
            </form>            
        </table>
            <?php 
            }
            ?> 
	</div>
</div>
