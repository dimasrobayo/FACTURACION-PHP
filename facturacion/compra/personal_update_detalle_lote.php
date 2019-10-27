<?php
	$redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
	$pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
	$type=$_GET["type"];
	$pagina=$pag.'?type='.$type;

        //Conexion a la base de datos
        $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

	
	//Consulta de Personal Registrado
//        $query="SELECT * FROM personal,cargo_personal WHERE cargo_personal.codigo_cargo_personal=personal.codigo_cargo and personal.status=1";
        $query="select * from personal, detalle_personal, concepto, cargo_personal where personal.cedula_personal = detalle_personal.cedula_personal and personal.codigo_cargo = cargo_personal.codigo_cargo_personal and concepto.codigo_concepto = detalle_personal.codigo_concepto";
        $consulta_personal = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
        
	//inicio del ciclo para la incluir los conceptos de carga automatica al personal
	while($resultados_personal = pg_fetch_array($consulta_personal))
	{
            $cedula_personal=$resultados_personal[cedula_personal];
            $sueldo_cargo=$resultados_personal[sueldo_cargo_personal];
            //encapsulamos los valores para posteriormente pasar a incluir los conceptos automatico
            $codigo_concepto = $resultados_personal['codigo_concepto'];
            $presupuesto = $resultados_personal['presupuesto'];
            $porcentaje_r = $resultados_personal['porcentaje'];
            $monto_concepto = $resultados_personal['monto_concepto'];
            $monto_detalle_personal = $resultados_concepto['monto'];
            $base_calculo_r = $resultados_personal['base_calculo'];
            $condicion = "F";
            $cantidad = $resultados_personal['cantidad'];
            $descena=100;

            if($base_calculo_r == "SB"){
                    $sueldo=$sueldo_cargo;
            }if($base_calculo_r == "SI") {
                    $asignaciones = pg_query("select SUM(detalle_personal.monto) as total_asignaciones from personal, concepto, detalle_personal where personal.cedula_personal = detalle_personal.cedula_personal and concepto.codigo_concepto = detalle_personal.codigo_concepto and concepto.tipo='A' and detalle_personal.cedula_personal='$cedula_personal'")  or die ('La consulta fall&oacute;: ' . pg_last_error());	
                    $arreglo_asignaciones=pg_fetch_array($asignaciones);
                    pg_fetch_result($asignaciones);
                    $sueldo = $arreglo_asignaciones['total_asignaciones'];
            }
            
            if($porcentaje_r > 0) {
                $monto_total = ($sueldo*($porcentaje_r/$descena));
//Consulta de Personal
            $query="UPDATE  detalle_personal SET monto='$monto_total' WHERE cedula_personal='$cedula_personal' AND codigo_concepto='$codigo_concepto'";
            $consulta_update_detalle_personal = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
            }
                    
            if($codigo_concepto == '0001') {
                $monto_total = $sueldo_cargo;
//Consulta de Personal
            $query="UPDATE  detalle_personal SET monto='$monto_total' WHERE cedula_personal='$cedula_personal' AND codigo_concepto='$codigo_concepto'";
            $consulta_update_detalle_personal = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
            }
	}
        $error="bien";
	pg_close(); 		     
?>
<div align="center" class="centermain">
    <?php echo $div_menssage; ?>
    <div class="main">
        <table class="admin_ejecutar_nomina">
            <tr>
                <th>
                    PROCESAR NOMINA
                </th>
            </tr>
        </table>

        <table class="adminform">	
            <tr>
                <td colspan="2" align="center">                        	
                    <br />
                    <strong>Resultado</strong>: 
                    <?php 
                    if ($error=="bien") {                            
                        echo "N&oacute;mina procesada con Exito";
                    }
                    else {
                        echo "Ocurri&ocaute; un error en el proceso";
                    }
                    ?>

                    <br />
                    <br />
                </td>
            </tr> 

            
	
            <table class="adminform" align="center">
                <tr align="center">
                    <td width="100%" valign="top" align="center">
                        <div id="cpanel">
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?type=personal_conceptos">
                                        <img src="images/ejecutar_nomina.png" alt="salir" align="middle"  border="0" />
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

</body>
</html>