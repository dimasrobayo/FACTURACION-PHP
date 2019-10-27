<?php //ENRUTTADOR

if (isset($_GET['error']))
	$redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
	$pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
	$type=$_GET["type"];
	$pagina=$pag.'?type='.$type;

//Conexion a la base de datos
$db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_empresa password=$sql_pass");
?>

<?php //seccion para recibir los datos y borrar.
if (isset($_GET['seguimiento'])){
	$seguimiento = $_GET['seguimiento'];
	$codigo_concepto = $_GET['codigo_concepto'];
	
	//se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
	require("conexion/aut_config.inc.php");
	$db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

	$consulta = pg_query("SELECT * FROM concepto") or die(pg_last_error());
	$total_registros = pg_num_rows ($consulta);
	pg_free_result($consulta);

	if($seguimiento == 1) 
	{
		$seguimiento_update = 0;
		$error="bien";	
		//se le hace el llamado a la funcion de insertar.	
		$result_borrar=pg_query("SELECT unlock_seguimiento('$codigo_concepto',$seguimiento_update)") or die(pg_last_error());
		pg_close();	
	}
	else 
	{
		$seguimiento_update = 1;
		$error="bien";	
		//se le hace el llamado a la funcion de insertar.	
		$result_borrar=pg_query("SELECT unlock_seguimiento('$codigo_concepto',$seguimiento_update)") or die(pg_last_error());
		pg_close();	
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
        
        <table class="adminconcepto" width="100%">
            <tr>
                <th>
                    CONCEPTO
                </th>
            </tr>
        </table>

        <form method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="3">
                        MODIFICAR SEGUIMIENTO DEL CONCEPTO
                    </th>
                </tr>    
                
                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Datos registrados con &eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=concepto";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=concepto" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>
            </table>    
        </form>		
    </div>
</div>