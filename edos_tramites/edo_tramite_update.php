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
    
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_GET['cod_estado_tramite'])){
    	$datos_modificar= $_GET['cod_estado_tramite'];

	$query="SELECT * FROM estados_tramites where cod_estado_tramite = $datos_modificar";
        $result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);	
        pg_free_result($result);   	
    }

    if (isset($_POST[save])) {
    	$codigo = trim($_POST['cod_estado_tramite']);
    	$descripcion = trim($_POST['descripcion']);
        $siglas = trim($_POST['siglas']);
        $etramite = trim($_POST['etramite']);
        $tetramite = trim($_POST['tetramite']);
        
        // Consultamos si existe la descripcion
        $query = "SELECT * FROM estados_tramites WHERE cod_estado_tramite='$codigo'";
        $result = pg_query($query)or die(pg_last_error());
        $resultado_estado_tramite=pg_fetch_array($result);
        pg_free_result($result);
        
        if($resultado_estado_tramite[descripcion_estado_tramite]!=$descripcion){
            // Consultamos si existe la descripcion
            $query = "SELECT * FROM estados_tramites WHERE descripcion_estado_tramite='$descripcion'";
            $result = pg_query($query)or die(pg_last_error());
            $resultado_load=pg_fetch_array($result);
            pg_free_result($result);
            
            if (!$resultado_load[0]) {
                //se le hace el llamado a la funcion de editar
                $query="SELECT update_edo_tramite($codigo,'$siglas','$descripcion','$etramite',$tetramite)";
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
            $query="SELECT update_edo_tramite($codigo,'$siglas','$descripcion','$etramite',$tetramite)";
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
        
        <table class="adminetramites" width="100%">
            <tr>
                <th>
                    Bancos Registrados
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        <img src="images/edit.png" width="16" height="16" alt="Editar Registro">
                        MODIFICAR DATOS DE BANCOS REGITRADOS
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

                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 

                <tr>
                    <td colspan="2" height="16" align="left">
                        <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>
                
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos del Estado de los Tramites:</b></td>
                </tr>
                
		<tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                
				<tr>
		                    <td width="17%" align="right">
                		        C&Oacute;DIGO: <font color="Red">(*)</font>
                		    </td>		
                		    <td width="85%">
                		        <input id="cod_estado_tramite" name="cod_estado_tramite" value="<?php  if ($error!="") echo $cod_estado_tramite; else  echo $resultado[cod_estado_tramite]; ?>" readonly="true" class="inputbox" type="text"/>
                		    </td>                       
                		</tr>
			
                		<tr>
                		    <td width="17%" align="right">
                		        SIGLAS: <font color="Red">(*)</font>
                		    </td>
                		    <td>
		                        <input  type="text" id="siglas" name="siglas" value="<?php if ($error!="") echo $siglas; else   echo $resultado[siglas_estado_tramite]; ?>" onkeyup="" class="validate[required] text-input" size="5" maxlength="5"/>				
		                    </td>			
		                </tr>

		                <tr>
		                    <td width="17%" align="right">
		                        ESTADO: <font color="Red">(*)</font>
		                    </td>
		                    <td>
		                        <input  type="text" id="etramite" name="etramite" value="<?php if ($error!="") echo $etramite; else   echo $resultado[estado_tramite]; ?>" class="validate[required] text-input" size="5" maxlength="5"/>
                                    </td>			
		                </tr>
                                
		                <tr>
		                    <td width="17%" align="right">
		                        DESCRIPCI&Oacute;N: <font color="Red">(*)</font>
		                    </td>
		                    <td>
		                        <input  type="text" id="descripcion" name="descripcion" value="<?php if ($error!="") echo $descripcion; else   echo $resultado[descripcion_estado_tramite]; ?>" class="validate[required] text-input" size="50" maxlength="50"/>
                                    </td>			
		                </tr>
                                
		                <tr>
		                    <td width="17%" align="right">
		                        TIPO DE ESTADO: <font color="Red">(*)</font>
		                    </td>
                                    <td>
                                        <select id="tetramite" name="tetramite" class="inputbox validate[required]" size="1">									
                                            <?php
                                            if ($error!=""){
                                                if($tetramite=="1") {
                                                    echo '<option value="'.$tetramite.'" selected="selected">Asignado</option>';
                                                }elseif($tetramite=="2") {
                                                    echo '<option value="'.$tetramite.'" selected="selected">Completado</option>';
                                                }else{
                                                    echo '<option value="'.$tetramite.'" selected="selected">Cancelado</option>';
                                                }
                                            }else{
                                                if($resultado[tipo_estado_tramite]=="1") {
                                                    echo '<option value="'.$resultado[tipo_estado_tramite].'" selected="selected">Asignado</option>';
                                                }elseif($resultado[tipo_estado_tramite]=="2") {
                                                    echo '<option value="'.$resultado[tipo_estado_tramite].'" selected="selected">Completado</option>';
                                                }else{
                                                    echo '<option value="'.$resultado[tipo_estado_tramite].'" selected="selected">Cancelado</option>';
                                                }
                                            }
                                            ?>
                                            <option value="" >---</option>
                                            <option value="1">Asignado</option>
                                            <option value="2">Completado</option>																						
                                            <option value="3">Cancelado</option>																						
                                        </select>
                                    </td>
		                </tr>
			
               		   </tbody>
                        </table>	
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=edos_tramites'" value="Cerrar" name="cerrar" />  
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
