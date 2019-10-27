<?php 

if (isset($_GET['error']))
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["type"];
    $pagina=$pag.'?type='.$type;

    //Conexion a la base de datos
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_empresa password=$sql_pass");
?>

<?php //seccion para recibir los datos y borrar.
if (isset($_GET['condicion'])){
    $condicion = $_GET['condicion'];
    $cedula_personal = $_GET['cedula_personal'];

    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

    $consulta = pg_query("SELECT * FROM personal") or die(pg_last_error());
    $total_registros = pg_num_rows ($consulta);
    pg_free_result($consulta);

    if($condicion == 1) {
        $condicion_update = 0;
        $error="bien";	
        //se le hace el llamado a la funcion de insertar.	
        $result_borrar=pg_query("SELECT personal_unlock($cedula_personal,$condicion_update)") or die(pg_last_error());
        pg_close();	
    } else {
        $condicion_update = 1;
        $error="bien";	
        //se le hace el llamado a la funcion de insertar.	
        $result_borrar=pg_query("SELECT personal_unlock($cedula_personal,$condicion_update)") or die(pg_last_error());
        pg_close();	
    }
}
?> 

<div align="center" class="centermain">
    <div class="main">  
        <table class="admin_conceptos">
            <tr>
                <th>
                    PERSONAL:
                    <small>
                        Status 
                    </small>
                </th>
            </tr>
        </table>
        
        <table class="adminform" border="0">
            <tr bgcolor="#55baf3">
                <th colspan="2">
                    STATUS DEL TRABAJADOR
                </th>
            </tr>

            <tr>
                <td colspan="2" align="center">                        	
                    <br />
                    <strong>Resultado</strong>: 
                    <?php 
                    if ($error=="bien") {
                        echo 'DATOS PROCESADOS CON  &Eacute;XITO!';
                    } else {
                        echo 'LOS DATOS NO PUEDEN SER PROCESADO!';			
                    }			
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
                                    <a href="index2.php?type=personal">
                                        <img src="images/personal.png" alt="salir" align="middle"  border="0" />
                                        <span>Gestor de Datos</span>
                                    </a>
                                </div>
                            </div>	
                        </div>
                    </td>
                </tr>
            </table>	
        </table>
    </div>
</div>
