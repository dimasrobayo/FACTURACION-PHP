<?php //ENRUTTADOR
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
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?>

<?php //seccion para recibir los datos y borrar.
if (isset($_GET['cedula'])){
    $datos_borrar= $_GET['cedula'];

    $consulta = pg_query("SELECT * FROM $sql_tabla") or die(pg_last_error());
    $total_registros = pg_num_rows ($consulta);
    pg_free_result($consulta);

    if ($total_registros == 1) {
        $error="malo";	
    }
    else {
        $error="bien";	
        $result_borrar=pg_query("SELECT drop_usuario($datos_borrar)") or die(pg_last_error());
        pg_close();
    }
}
?> 
<div align="center" class="centermain">
    <div class="main">  
        <table class="adminusuarios" width="100%">
            <tr>
                <th>
                    USUARIOS:
                </th>
            </tr>
        </table>
        
        <table class="adminform" border="0" width="100%">
            <tr bgcolor="#55baf3">
                <th colspan="2">
                     BORRAR DE USUARIO
                </th>
            </tr>
			
            <tr>
                <td colspan="2" align="center">
                    <div align="center"> 
                        <h3 class="info">	
                            <font size="2">
                                <?php 
                                    if ($error=="bien") {
                                        echo 'DATOS PROCESADOS CON  &Eacute;XITO!';
                                    }
                                    else  {
                                        echo 'LOS DATOS NO PUEDEN SER PROCESADOS!';			
                                    }			
                                ?>
                                <br />
                                <script type="text/javascript">
                                    function redireccionar(){
                                        window.location="?view=usuarios";
                                    }  
                                    setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                </script> 						
                                [<a href="?view=usuarios" name="Continuar"> Continuar </a>]
                            </font>							
                        </h3>
                    </div> 
                </td>
            </tr>	
            
       </table>	
    </div>
</div>