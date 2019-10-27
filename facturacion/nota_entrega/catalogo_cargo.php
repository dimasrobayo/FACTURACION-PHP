<html>
<head>
	<title>BUSCAR CARGO</title>
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
	function pon_prefijo(codigo,nombre,sueldo)
		{
			parent.opener.document.personal_add.codigo_cargo.value=codigo;
			parent.opener.document.personal_add.nombre_cargo.value=nombre;
			parent.opener.document.personal_add.sueldo_cargo.value=sueldo;
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
	$datos_consulta = pg_query("SELECT * FROM cargo_personal order by codigo_cargo_personal") or die("No se pudo realizar la consulta a la Base de datos");
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
					CATALOGO DE CARGOS:
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

					<th width="50%" align="center">
						DESCRIPCI&Oacute;N
					</th>
					
					<th width="30%" align="center">
						SUELDO BASE
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
						 <?php echo $resultados[codigo_cargo_personal];?>
					</td>

					<td  align="left">
						<?php echo $resultados[nombre_cargo_personal];?>
					</td>
					
					<td  align="right">
						<?php echo $resultados[sueldo_cargo_personal];?>
					</td>
					
					<td align="center"> 
						<a href="javascript:pon_prefijo(<?php echo $resultados[codigo_cargo_personal];?>,'<?php echo $resultados[nombre_cargo_personal];?>','<?php echo $resultados[sueldo_cargo_personal];?>')" title="Pulse para Modificar el Nivel de Acceso">
							<img src="../images/botonagregar.jpg" alt="borrar">
						</a>
					</td>
				</tr>	
<?php
}
?>
			<tfoot>
				<tr align="right">
					<th colspan="6" align="center">
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