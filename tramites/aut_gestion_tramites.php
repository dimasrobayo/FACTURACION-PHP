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
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$type;

    //Conexion a la base de datos
    include("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_GET['cod_unidad'])){
    	$datos_unidad= $_GET['cod_unidad'];

	$query="SELECT * FROM unidades WHERE cod_unidad = '$datos_unidad'";
    	$result = pg_query($query)or die(pg_last_error());
	$resultado=pg_fetch_array($result);
        pg_free_result($result);
        
	$query="SELECT * FROM tramites,categorias,unidades WHERE tramites.cod_categoria=categorias.cod_categoria AND tramites.cod_unidad=unidades.cod_unidad AND tramites.cod_unidad = '$datos_unidad' ORDER BY cod_tramite";
    	$result = pg_query($query)or die(pg_last_error());
    }
?>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

 	<table class="admintramites">
            <tr>
                <th>
                    TRAMITES DEL DEPARTAMENTO/UNIDAD:
                </th>
            </tr>
        </table>
        
        <table class="adminlist" border="1"  width="100%">	
                <tr class="row0">
                    <td class="titulo" width="100" colspan="5"><b>DATOS DEL DEPARTAMENTO/UNIDAD:</b> <?php echo implode('/',array_reverse(explode('-',$resultados_proceso['fecha'])));?></td>
                </tr>
                <tr class="row0">
                    <td width="100"><b>CODIGO:</b> <?php echo $resultado[cod_unidad]; ?></td> 
                    <td width="120"><b>SIGLAS:</b> <?php echo $resultado['siglas_unidad']; ?></td>
                    <td width="300"><b>NOMBRE:</b> <?php echo $resultado['nombre_unidad']; ?></td>
                    <td><b>RESPONSABLE:</b> <?php echo $resultado['responsable_unidad']; ?></td>
                </tr>
        </table>	
        <br />	
        <table class="adminlist" border="1"  width="100%">	
                <tr class="row0">
                    <td class="titulo" width="100" colspan="5"><b>TRAMITES DEL DEPARTAMENTO/UNIDAD:</b> <?php echo implode('/',array_reverse(explode('-',$resultados_proceso['fecha'])));?></td>
                </tr>
        </table>

<!--Estructura de Tabla de Contenidos Estados de Tramites-->
        <table class="display" id="tabla">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="8%">
                    C&oacute;digo
                </th>
		<th align="center" width="12%">
                    Categoria
                </th>
                <th width="15%" align="center">
                    Tramite
                </th>	
		<th width="50%" align="center">
                    Descripci&oacute;n Tramite
                </th>

                <th width="20%" align="center">
                    Acciones
                </th>
            </tr>
        </thead>

<?php
$xxx=0;
while($resultados = pg_fetch_array($result)) {
	$xxx=$xxx+1;
?>

            <tr class="row0">
                <td  align="center">
                     <?php echo $resultados[cod_tramite];?>
                </td>

		<td>
                    <?php echo $resultados[descripcion_categoria];?>
                </td>

                <td>
                    <?php echo $resultados[nombre_tramite];?>
                </td>

		<td>
                    <?php echo $resultados[descripcion_tramite];?>
                </td>

                <?php if ($resultados[status_tramite]=='0') {
                          $ico=4;
                      } else {
                          $ico=3;
                      }
                ?>
                
                <td align="center"> 
                    <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=tramite_drop&cod_tramite=<?php echo $resultados[cod_tramite];?>&cod_unidad=<?php echo $resultados[cod_unidad];?>" title="Pulse para eliminar el registro">
                        <img border="0" src="images/borrar28.png" alt="borrar">
                    </a>
                    <a href="index2.php?view=tramite_update&cod_tramite=<?php echo $resultados[cod_tramite];?>" title="Pulse para Modificar los datos registrados">
                        <img border="0" src="images/modificar.png" alt="borrar">
                    </a>
                    
                    <a onclick="return confirm('CONFIRMAR CAMBIO DE STATUS A ESTE TRAMITE ?');" href="index2.php?view=tramite_status&unidad=<?php echo $resultados[cod_unidad];?>&status=<?php echo $resultados[status_tramite];?>&cod_tramite=<?php echo $resultados[cod_tramite];?>" title="Pulse para Cambiar STATUS del Tramite">
                        <img border="0" src="images/<?php echo $ico;?>.png" alt="borrar">
                    </a>
                </td>
            </tr>
<?php
}
?>

            <tfoot>
                <tr align="center">
                    <th colspan="5" align="center">
                        <div id="cpanel">
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=unidades">
                                        <img src="images/cpanel.png" alt="salir" align="middle"  border="0" />
                                        <span>Salir</span>
                                    </a>
                                </div>
                            </div>
							
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="reportes/imprimir_lista_tramites_unidades.php?cod_unidad=<?php echo $resultado[cod_unidad];?>" target="_blank">
                                        <img src="images/printer.png" alt="agregar" align="middle"  border="0" />
                                        <span>Imprimir</span>
                                    </a>
                                </div>
                            </div>
                    
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=tramite_add&cod_unidad=<?php echo $resultado[cod_unidad];?>">
                                        <img src="images/nuevo.png" alt="agregar" align="middle"  border="0" />
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
