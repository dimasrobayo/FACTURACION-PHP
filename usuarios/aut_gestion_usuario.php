<?php
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
    
    $pag=$_SERVER['PHP_SELF']; 
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    $datos_consulta = pg_query("SELECT * FROM usuarios,unidades,niveles_acceso WHERE usuarios.nivel_acceso=niveles_acceso.codigo_nivel and  usuarios.cod_unidad=unidades.cod_unidad order by usuarios.cedula_usuario") or die(pg_last_error());
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="adminusuarios">
            <tr>
                <th>
                    USUARIOS DEL SISTEMA
                    
                </th>
            </tr>
        </table>
    
        <br>

    <!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="6%">
                    Código
                </th>
                <th align="center" width="8%">
                    Login
                </th>

                <th width="15%" align="center">
                    Nombre y Apellido
                </th>

                <th width="18%" align="center">
                    Nivel Acceso
                </th>

                <th width="18%" align="center">
                    Unidad/Dependencia
                </th>

                <th width="15%" align="center">
                    Ùltimo Acceso
                </th>

                <th width="17%" align="center">
                    Acciones
                </th>
            </tr>
        </thead>

        <?php
            
            while($resultados = pg_fetch_array($datos_consulta))
            {
               
        ?>

        <tr class="row0">
            <td  align="center">
                 <?php echo $resultados[cedula_usuario];?>
            </td>
            <td>
                 <?php echo $resultados[usuario];?>
            </td>

            <td>
                <?php echo $resultados[nombre_usuario];?> <?php echo " "; ?> <?php echo $resultados[apellido_usuario];?>
            </td>

            <td>
                 <?php echo $resultados[nombre_nivel];?>
            </td>

	    <td>
                 <?php echo $resultados[nombre_unidad];?>
            </td>

            <td  align="center">
                <?php echo date_format(date_create($resultados['fecha_ultimoacceso']), 'd/m/Y g:i A.') ;?> 
            </td>
            
            <td align="center"> 
                <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=usuarios_drop&cedula=<?php echo $resultados[cedula_usuario];?>" title="Pulse para eliminar el registro">
                    <img border="0" src="images/borrar28.png" alt="borrar">
                </a>
                <a href="index2.php?view=usuarios_update&cedula=<?php echo $resultados[cedula_usuario];?>" title="Pulse para Modificar los datos personales">
                    <img border="0" src="images/modificar.png" alt="borrar">
                </a>  
                <a href="index2.php?view=usuarios_update_clave&cedula=<?php echo $resultados[cedula_usuario];?>" title="Pulse para Cambio de Clave">
                    <img border="0" src="images/modificar_clave.png" alt="borrar">
                </a>
                <a href="index2.php?view=usuarios_update_nivel&cedula=<?php echo $resultados[cedula_usuario];?>" title="Pulse para Modificar el Nivel de Acceso">
                    <img border="0" src="images/nivel_acceso.png" alt="borrar">
                </a>
                <a onclick="return confirm('Esta seguro que desea Bloquear o Desbloquear el Usuario?');" href="index2.php?view=usuarios_unlock&cedula=<?php echo $resultados[cedula_usuario];?>&status=<?php echo $resultados[status];?>" title="Pulse para bloquear o desbloquear el registro">
                    <img border="0" src="images/<?php echo $resultados[status];?>.png" alt="borrar">
                </a>
            </td>
        </tr>

<?php
}
?>

            <tfoot>
                <tr align="center">
                    <th colspan="7" align="center">
                        <div id="cpanel">
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=home">
                                        <img src="images/cpanel.png" alt="salir" align="middle"  border="0" />
                                        <span>Salir</span>
                                    </a>
                                </div>
                            </div>	

                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=usuarios_add">
                                        <img src="images/usuario_add.png" alt="agregar" align="middle"  border="0" />
                                        <span>Agregar</span>
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php
pg_free_result($datos_consulta);
pg_close();
?>
