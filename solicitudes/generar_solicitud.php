<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
//        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }

    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?>
	
<?php 
if (isset($_POST[save])) {
    $rifci = trim($_POST['rifci_cliente']);
    $nombre = trim($_POST['nombre_cliente']);
    $direccion = trim($_POST['direccion_cliente']);
    $telefono = trim($_POST['telefono_cliente']);

    $error="bien";	

    $inserta_registro = pg_query("insert into tblclientes (rifci,nombre_cliente,direccion_cliente,telefono_cliente) values ('$rifci','$nombre','$direccion','$telefono')") or die('La consulta fall&oacute;: ' . pg_last_error());	
    $result_insert=pg_fetch_array($inserta_registro);	
    pg_free_result($inserta_registro);
    $resultado_insert=$result_insert[0];

    pg_close(); 		     
}//fin del add   
?>
<div align="center" class="centermain">
    <div class="main">  
        <table class="adminclientes" width="100%">
            <tr>
                <th>
                    Clientes:
                    <small>
                        Nuevo
                    </small>
                </th>
            </tr>
        </table>
        
        <form id="personal_add" name="personal_add" method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="4">
                        NUEVA SOLICITD A PROCESAR
                    </th>
                </tr>

                <?php 
                if ((isset($_POST[save])) and ($error=="bien"))
                {		
                ?> 

                <tr>
                    <td colspan="4" align="center">                        	
                        <br />
                        <strong>Resultado</strong>: 
                        <?php 
                            switch($resultado_insert) {
                                case 0: 
                                    echo 'El Registro fue procesado con &eacute;xito';	
                                    break;
                                case 1: 
                                    echo 'No se pudo procesar el registro porque ya est&aacute; registrado en el sistema ';
                                    break;
                            }				
                            echo '<br />'.$msg;
                        ?>
                        <br />	
                    </td>
                </tr> 

                <table class="adminform" align="center" width="100%">
                    <tr align="center">
                        <td width="100%" valign="top" align="center">
                            <div id="cpanel">
                                <div style="float:right;">
                                    <div class="icon">
                                        <a href="index2.php?view=inicio">
                                            <img src="images/panel_inicio.png" alt="salir" align="middle"  border="0" />
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
 		 
                <tr>
                    <td>
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>						
                            <tr width="15%">
                                <td>
                                    RIF/CI:
                                </td>

                                <td width="85%">
                                    <input class="inputbox" type="text" id="rifci_cliente" name="rifci_cliente" maxlength="12" size="15"/>
                                    <font color="#ff0000">*</font>
                                    <script type="text/javascript">
                                        var codigo = new LiveValidation('rifci_cliente');
                                        codigo.add(Validate.Presence);
                                        codigo.add( Validate.Numericality);
                                    </script>
                                </td>			
                            </tr>

                            <tr>
                                <td width="12%">
                                    Nombre/Raz&oacute;n Social:
                                </td>

                                <td>
                                    <input class="inputbox" type="text" id="nombre_cliente" name="nombre_cliente" maxlength="50" size="50"/>
                                    <script type="text/javascript">
                                        var codigo = new LiveValidation('nombre_cliente');
                                        codigo.add(Validate.Presence);
                                        codigo.add( Validate.texto );
                                    </script>
                                    <font color="#ff0000">*</font>		
                                </td>			
                            </tr>

                            <tr>
                                <td width="12%">
                                    Direcci&oacute;n:
                                </td>

                                <td>
                                    <input class="inputbox" type="text" id="direccion_cliente" name="direccion_cliente" maxlength="255" size="50"/>
                                    <script type="text/javascript">
                                        var codigo = new LiveValidation('direccion_cliente');
                                        codigo.add(Validate.Presence);
                                        codigo.add( Validate.texto );
                                    </script>	
                                    <font color="#ff0000">(*)</font>			
                                </td>			
                            </tr>

                            <tr width="15%">
                                <td>
                                    Telefonos:
                                </td>

                                <td width="85%">
                                    <input class="inputbox" type="text" id="telefono_cliente" name="telefono_cliente" maxlength="50" size="50"/>
                                    <font color="#ff0000">*</font>
                                    <script type="text/javascript">
                                        var codigo = new LiveValidation('telefono_cliente');
                                        codigo.add(Validate.Presence);
                                        codigo.add( Validate.texto);
                                    </script>
                                </td>			
                            </tr>                     
                            
                        </tbody>
                        </table>	
                    </td>
                </tr>

                <tr>
                    <td bgcolor="#55baf3" colspan="4" align="center">
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input class="button" type="reset" value="Limpiar" name="Refresh"> 
                        <input  class="button" type="button" onClick="history.back()" value="Cancelar">
                    </td>
                </tr>
                
                <?php 
                }
                ?> 
            </table>		
        </form>
        
    </div>
</div>
