<?php //ENRUTTADOR
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_empresa password=$sql_pass");

//seccion para recibir los datos y borrar.
    if (isset($_GET['codigo_nota'])){
	$datos_borrar= $_GET['codigo_nota'];

	//se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
	require("conexion/aut_config.inc.php");
	$db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

	$consulta = pg_query("SELECT * FROM nota_entrega") or die(pg_last_error());
	$total_registros = pg_num_rows ($consulta);
	pg_free_result($consulta);
	
	if ($total_registros == 0){
            $error="malo";	
	}
	else{
            $error="bien";	
            unlink("logo_empresa/$datos_borrar");
            //se le hace el llamado a la funcion de insertar.	
            $result_borrar=pg_query("SELECT drop_nota_entrega($datos_borrar)") or die(pg_last_error());
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
        <table class="adminnota" width="100%">
            <tr>
                <th>
                    NOTA DE ENTREGA
                </th>
            </tr>
        </table>
        
        <table class="adminform" border="0" width="100%">
            <tr bgcolor="#55baf3">
                <th colspan="2">
                    BORRAR NOTA DE ENTREGA
                </th>
            </tr>

            <tr>
                <td colspan="2" align="center">
                    <div align="center"> 
                        <h3 class="info">	
                            <font size="2">
                                <?php
                                    if ($error=="bien"){	
                                        echo 'Datos Eliminados con &eacute;xito';
                                    }else{
                                        echo '<font size="2" style="text-decoration:blink;">El Registro: <font color="blue">'.$datos_borrar.'</font>; no puede ser eliminado, contiene registros asociados.</font>';
                                    }
                                ?>
                                <br />
                                <script type="text/javascript">
                                    function redireccionar(){
                                        window.location="?view=nota_entrega";
                                    }  
                                    setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                </script> 						
                                [<a href="?view=nota_entrega" name="Continuar"> Continuar </a>]
                            </font>							
                        </h3>
                    </div> 
                </td>
            </tr>	
	</table>
    </div>
</div>
