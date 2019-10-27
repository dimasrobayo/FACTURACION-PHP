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

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?>

<?php //seccion para recibir los datos y borrar.
if (isset($_GET[condicion])){
    $condicion = $_GET[condicion];

    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

    $consulta = pg_query("SELECT * FROM marcas") or die(pg_last_error());
    $total_registros = pg_num_rows ($consulta);
    pg_free_result($consulta);

    if($condicion == 1) {
        $condicion_update = 0;
        $error="bien";	
        $result_update=pg_query("UPDATE marcas SET status=$condicion_update") or die(pg_last_error());
        pg_close();	
    } else {
        $condicion_update = 1;
        $error="bien";	
        $result_update=pg_query("UPDATE marcas SET status=$condicion_update") or die(pg_last_error());
        pg_close();	
    }
}
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
        <table class="adminmarca" width="100%">
            <tr>
                <th>
                    MARCAS
                </th>
            </tr>
        </table>
        
        <table class="adminform" border="0">
            <tr bgcolor="#55baf3">
                <th colspan="2">
                    STATUS DE LA MARCA
                </th>
            </tr>

           <tr>
                <td colspan="2" align="center">
                    <div align="center"> 
                        <h3 class="info">	
                            <font size="2">
                                <?php
                                    if ($error=="bien"){	
                                        echo 'Datos Actualizados con &eacute;xito';
                                    }else{
                                        echo '<font size="2" style="text-decoration:blink;">El Registro no puede ser actualizado, contiene registros asociados.</font>';
                                    }
                                ?>
                                <br />
                                <script type="text/javascript">
                                    function redireccionar(){
                                        window.location="?view=marcas_conceptos";
                                    }  
                                    setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                </script> 						
                                [<a href="?view=marcas_conceptos" name="Continuar"> Continuar </a>]
                            </font>							
                        </h3>
                    </div> 
                </td>
            </tr>
        </table>
        <br>
    </div>
</div>
