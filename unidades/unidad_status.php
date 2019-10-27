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
    
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_GET['status'])){
        $status = $_GET[status];
        $cod_unidad = $_GET[cod_unidad];

        if($status == 1) {
            $status_update = 0;
            $resultado=pg_query("UPDATE unidades SET status_unidad='$status_update' WHERE cod_unidad='$cod_unidad';") or die(pg_last_error());
            pg_close();	
            $error="bien";
        } else {
            $status_update = 1;
            $resultado=pg_query("UPDATE unidades SET status_unidad='$status_update' WHERE cod_unidad='$cod_unidad';") or die(pg_last_error());
            pg_close();	
            $error="bien";
        }
    }
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
        <table class="adminunidades">
            <tr>
                <th>
                    DEPARTAMENTO/UNIDAD:
                </th>
            </tr>
        </table>
        
        <table class="adminform" border="0" width="100%">
            <tr bgcolor="#55baf3">
                <th colspan="2">
                    <img src="images/edit.png" width="16" height="16" alt="Editar Registro">
                    MODIFICAR STATUS DEL DEPARTAMENTO/UNIDAD
                </th>
            </tr>

            <?php if ($error=="bien") {	?> <!-- Mostrar Mensaje -->

            <tr>
                <td colspan="2" align="center">
                    <div align="center"> 
                        <h3 class="info">	
                            <font size="2">						
                                Datos Modificados con &eacute;xito 
                                <br />
                                <script type="text/javascript">
                                    function redireccionar(){
                                        window.location="?view=unidades";
                                    }  
                                    setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                </script> 						
                                [<a href="?view=unidades" name="Continuar"> Continuar </a>]
                            </font>							
                        </h3>
                    </div> 
                </td>
            </tr>

            <?php  } else { ?>   <!-- Mostrar formulario Original --> 
            
            <tr>
                <td colspan="2" align="center">
                    <div align="center"> 
                        <h3 class="info">	
                            <font size="2">						
                                Datos Modificados con &eacute;xito 
                                <br />
                                <script type="text/javascript">
                                    function redireccionar(){
                                        window.location="?view=unidades";
                                    }  
                                    setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                </script> 						
                                [<a href="?view=unidades" name="Continuar"> Continuar </a>]
                            </font>							
                        </h3>
                    </div> 
                </td>
            </tr>

            <?php  } ?>   <!-- Mostrar formulario Original --> 
            
        </table>
    </div>
</div>