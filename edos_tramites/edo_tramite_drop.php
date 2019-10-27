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

<?php 
    if (isset($_GET['cod_estado_tramite'])){
        $datos_borrar= $_GET['cod_estado_tramite'];
        
        $query="SELECT * FROM estados_tramites WHERE estados_tramites.cod_estado_tramite ='$datos_borrar' AND estados_tramites.cod_estado_tramite IN (SELECT cod_estado_tramite FROM ticket)";			
        $result = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());	
        $total_registros = pg_num_rows ($result);			
        pg_free_result($result);

        if ($total_registros == 1){
            $error="Error";
        }else {
            $error="bien";	
            //se le hace el llamado a la funcion de borrar.	
            $result_borrar=pg_query("SELECT drop_edo_tramite($datos_borrar)") or die(pg_last_error());
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
        <table class="adminetramites" width="100%">
            <tr>
                <th>
                    ESTADOS DE TRAMITES:
                </th>
            </tr>
        </table>
        
        <table class="adminform" border="0" width="100%">
            <tr bgcolor="#55baf3">
                <th colspan="2">
                    BORRAR ESTADO DE TRAMITE
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
                                        window.location="?view=edos_tramites";
                                    }  
                                    setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                </script> 						
                                [<a href="?view=edos_tramites" name="Continuar"> Continuar </a>]
                            </font>							
                        </h3>
                    </div> 
                </td>
            </tr>	
            
       </table>	
    </div>
</div>
