<?php //ENRUTTADOR

    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?>

<?php
    if (isset($_GET['codigo_actividad'])){
        $datos_borrar= $_GET['codigo_actividad'];
        $cedula_rif= $_GET['cedula_rif'];

        $error="bien";	
        //se le hace el llamado a la funcion de borrar.	
        $query="SELECT drop_actividad('$datos_borrar')";
        $result = pg_query($query)or die(pg_last_error());
        pg_close();
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
        <table class="adminproductor" width="100%">
            <tr>
                <th>
                    PRODUCTOR
                </th>
            </tr>
        </table>
        
        <table class="adminform" border="0" width="100%">
            <tr>
                <th colspan="2" align="center">
                    <img src="images/delete.png" width="16" height="16" alt="Eliminar Registro">
                    ELIMINAR DATOS DE PRODUCTOR
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
                                        echo '<font size="2" style="text-decoration:blink;">El Registro: <font color="blue">'.$cedula_rif.'</font>; no puede ser eliminado, contiene registros asociados.</font>';
                                    }
                                ?>
                                <br />
                                <script type="text/javascript">
                                    function redireccionar(){
                                        window.location="?view=productor_load_view<?php echo '&cedula_rif='.substr_replace($cedula_rif,'-',1,0);?>";
                                    }  
                                    setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                </script> 						
                                [<a href="?view=productor_load_view<?php echo '&cedula_rif='.substr_replace($cedula_rif,'-',1,0);?>" name="Continuar"> Continuar </a>]
                            </font>							
                        </h3>
                    </div> 
                </td>
            </tr>
        </table>
        <br>
    </div>
</div>
