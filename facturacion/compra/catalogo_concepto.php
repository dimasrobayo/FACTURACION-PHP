<html>
<head>
	<title>BUSCAR CONCEPTOS</title>
	<link href="../css/template_css.css" type="text/css" rel="stylesheet">
	<link rel="stylesheet" href="../css/table_css.css" type="text/css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>

	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() 
		{
			$('#tabla').dataTable();
		} );
	</script>
	
	<script language="javascript">
	function pon_prefijo(codigo,descripcion,monto,porcentaje,base_calculo,presupuesto)
		{
			parent.opener.document.formulario_asignacion.codigo_concepto.value=codigo;
			parent.opener.document.formulario_asignacion.descripcion_concepto.value=descripcion;
			parent.opener.document.formulario_asignacion.monto.value=monto;
			parent.opener.document.formulario_asignacion.porcentaje.value=porcentaje;
			parent.opener.document.formulario_asignacion.base_calculo.value=base_calculo;
			parent.opener.document.formulario_asignacion.presupuesto.value=presupuesto;
			parent.window.close();
		}
	</script>
</head>

<?php
	require("../conexion/aut_config.inc.php");
	/*este es el enlace de conexion a la base de datos*/
	$db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
	
if (!isset($_GET['accion']))
{
	$datos_consulta = pg_query("SELECT * FROM concepto order by codigo_concepto") or die("No se pudo realizar la consulta a la Base de datos");
?>

<body>

<div align="center" class="centermain">
	<div class="main">  
		<div align="center">
			<font color="red" style="text-decoration:blink;"><?php $error_accion_ms[$error_cod]?></font>
		</div>

		<table class="admin_nomina">
			<tr>
				<th>
					CATALOGO DE CONCEPTOS:
					<small>
					GESTI&Oacute;N
					</small>
				</th>
			</tr>
		</table>

<!--Estructura de Tabla de Contenidos de la Tabla usuario-->
		<table border="0" class="display" id="tabla">
			<thead>
				<tr>
					<th align="center" width="10%">
						C&Oacute;DIGO
					</th>

					<th width="60%" align="center">
						DESCRIPCI&Oacute;N
					</th>
					
					<th width="10%" align="center">
						TIPO
					</th>
					
					<th width="10%" align="center">
						MONTO
					</th>
					
					<th width="10%" align="center">
						PORCENTAJE
					</th>

					<th width="15%" align="center">
						ACCIONES
					</th>
				</tr>
			</thead>

<?php
$xxx=0;
while($resultados = pg_fetch_array($datos_consulta))
{
	$xxx=$xxx+1;
?>

				<tr class="row0">
					<td  align="center">
						 <?php echo $resultados[codigo_concepto];?>
					</td>

					<td  align="left">
						<?php echo $resultados[descripcion_concepto];?>
					</td>
					
					<td  align="center">
						<?php if ($resultados[tipo] == "A")
						{ echo "Asignacion";	}
						else { echo "Deducci&oacute;n"; }
						?>
					</td>
					
					<td  align="center">
						<?php echo $resultados[monto];?>
					</td>
					
					<td  align="center">
						<?php echo $resultados[porcentaje];?>
					</td>
					
					<td align="center"> 
						<a href="javascript:pon_prefijo('<?php echo $resultados[codigo_concepto];?>','<?php echo $resultados[descripcion_concepto];?>','<?php echo $resultados[monto];?>','<?php echo $resultados[porcentaje];?>','<?php echo $resultados[base_calculo];?>','<?php echo $resultados[presupuesto];?>')" title="Pulse para Modificar el Nivel de Acceso">
							<img src="../images/botonagregar.jpg" alt="borrar">
						</a>
					</td>
				</tr>	
<?php
}
?>
			<tfoot>
				<tr align="right">
					<th colspan="7" align="center">
						<div id="cpanel">
							<div style="float:left;">
								<div class="icon">
									<a onclick="parent.window.close();">
									<img src="../images/cpanel.png" alt="salir" align="middle"  border="0" />
									<span>Salir</span>
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
}
?>


</body>
</html>