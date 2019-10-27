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

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?>

<?php //seccion para recibir los datos y modificarlos.
    if (isset($_GET['cod_categoria'])){
    	$datos_modificar= $_GET['cod_categoria'];

	$query="SELECT * FROM categorias where cod_categoria = $datos_modificar";
        $result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);	
        pg_free_result($result);  	
    }
?> 

<?php 
    if (isset($_POST[save])) {
    	$codigo = trim($_POST['cod_categoria']);
    	$descripcion = trim($_POST['descripcion']);
        if (isset($_POST["cate_online"])){	
            $cat_online=1;
        }else {
            $cat_online=0;
        }
        
        // Consultamos si existe la descripcion
        $query = "SELECT * FROM categorias WHERE cod_categoria='$codigo'";
        $result = pg_query($query)or die(pg_last_error());
        $resultado_categoria=pg_fetch_array($result);
        pg_free_result($result);
        
        if($resultado_categoria[descripcion_categoria]!=$descripcion){
            // Consultamos si existe la descripcion
            $query = "SELECT * FROM categorias WHERE descripcion_categoria='$descripcion'";
            $result = pg_query($query)or die(pg_last_error());
            $resultado_load=pg_fetch_array($result);
            pg_free_result($result);
            
            if (!$resultado_load[0]) {
                //se le hace el llamado a la funcion de editar
                $query="UPDATE categorias SET descripcion_categoria='$descripcion', status_categoria_online=$cat_online WHERE cod_categoria=$codigo;";
                $result = pg_query($query)or die(pg_last_error());
                $error="bien";
            } else {
                $error="Error";
                $div_menssage='<div align="left">
                            <h3 class="error">
                                <font color="red" style="text-decoration:blink;">
                                    Error: Ya Existe un Registro con la descripcion: <font color="blue">'.$descripcion.'</font>; por favor verifique los datos!
                                </font>
                            </h3>
                        </div>';	
            }
            
        }else{
            //se le hace el llamado a la funcion de editar
            $query="UPDATE categorias SET descripcion_categoria='$descripcion', status_categoria_online=$cat_online WHERE cod_categoria=$codigo;";
            $result = pg_query($query)or die(pg_last_error());
            $error="bien";
        }
        
        
    }//fin del procedimiento modificar.
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
        <table class="admincategorias" width="100%">
            <tr>
                <th>
                    CATEGORIAS:
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        <img src="images/edit.png" width="16" height="16" alt="Editar Registro">
                        MODIFICAR DATOS DE LA CATEGORIA
                    </th>
                </tr>

		<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Datos registrados con &eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=categorias";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=categorias" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>

                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 

                <tr>
                    <td colspan="2" height="16" align="left">
                        <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos de la Categoria:</b></td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>

				<tr>
                                    <td width="15%" >
                                       CÓDIGO CATEGORIA: <font color="Red">(*)</font>
                                    </td>		
                                    <td width="85%">
                                        <input id="cod_categoria" name="cod_categoria" value="<?php if ($error!="") echo $codigo; else  echo $resultado[cod_categoria]; ?>" readonly="true" class="inputbox" type="text"/>
                                    </td>                       
                		</tr>
			
                		<tr>
                                    <td width="15%">
                                         DESCRIPCIÓN DE LA CATEGORIA: <font color="Red">(*)</font>
                                    </td>	
                                    <td>
                                        <input  type="text" id="descripcion" name="descripcion" value="<?php if ($error!="") echo $descripcion; else  echo $resultado[descripcion_categoria]; ?>" class="validate[required] text-input" size="50" maxlength="50"/>
                                    </td>			
                		</tr>
                                
                                <tr>
                                    <td class="titulo" colspan="2" height="18"  align="left"><b>INFORMACIÓN DE LA CATEGORIA ON-LINE</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="20%" >
                                                        <?php
                                                            if ($error!=""){
                                                                if($cat_online==1){																					
                                                                        echo '<input type="checkbox"  name="cate_online" id="cate_online" checked="true" />';
                                                                }else {
                                                                        echo '<input type="checkbox"  name="cate_online" id="cate_online"/>';
                                                                }
                                                            }else{
                                                                if($resultado[status_categoria_online]==1){																					
                                                                        echo '<input type="checkbox"  name="cate_online" id="cate_online" checked="true" />';
                                                                }else {
                                                                        echo '<input type="checkbox"  name="cate_online" id="cate_online"/>';
                                                                }
                                                                
                                                            }
                                                        ?>
                                                        
                                                        La Categoria estar&aacute; disponible On-Line: 
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>	
                                    </td>
                                </tr>
                
                            </tbody>
                        </table>	
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=categorias'" value="Cerrar" name="cerrar" />  
                    </td>													
                </tr> 
            <?php }  ?>	
        </table>
    </form>     
    <br>	 
    </div>
</div> 
        
<script type="text/javascript">
	var dtabs=new ddtabcontent("divsG")
	dtabs.setpersist(true)
	dtabs.setselectedClassTarget("link") //"link" or "linkparent"
	dtabs.init()
</script>
