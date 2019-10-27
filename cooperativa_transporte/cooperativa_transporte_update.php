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
?> 

<?php   
    if (isset($_GET['rif_cooperativa'])){ // Recibir los Datos 
        $rif_cooperativa= $_GET['rif_cooperativa'];

        $query="select * from cooperativas_transporte where rif_cooperativa='$rif_cooperativa'";
        $result = pg_query($query)or die(pg_last_error());
        $resultados=pg_fetch_array($result);	
        pg_free_result($result);
    }
?> 

<?php 
    if (isset($_POST[save])) {
    	$rif_cooperativa = strtoupper($_POST['rif_cooperativa']);
        $nombre_cooperativa= $_POST['nombre_cooperativa'];
        $direccion_cooperativa= $_POST['direccion_cooperativa'];
        $telefono_cooperativa= $_POST['telefono_cooperativa'];
        $persona_contacto_cooperativa= $_POST['persona_contacto_cooperativa'];
        $telefono_persona_contacto_cooperativa= $_POST['telefono_persona_contacto_cooperativa'];
        $cod_tipo_transporte_cooperativa= $_POST['cod_tipo_transporte_cooperativa'];

        
        $query="UPDATE cooperativas_transporte SET nombre_cooperativa='$nombre_cooperativa',direccion_cooperativa='$direccion_cooperativa',telefono_cooperativa='$telefono_cooperativa',persona_contacto_cooperativa='$persona_contacto_cooperativa',telefono_persona_contacto_cooperativa='$telefono_persona_contacto_cooperativa',cod_tipo_transporte_cooperativa='$cod_tipo_transporte_cooperativa' WHERE rif_cooperativa='$rif_cooperativa'";
        $result = pg_query($query)or die(pg_last_error());
        $result_update=pg_fetch_array($result);
        pg_free_result($result);
        
        $error="bien";
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
        <table class="cooperativatransporte" width="100%">
            <tr>
                <th>
                    COOPERATIVAS DE TRANSPORTE:
                </th>
            </tr>
        </table>
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        <img src="images/edit.png" width="16" height="16" alt="Editar Registro">
                        MODIFICAR DATOS DE LA COOPERATIVA
                    </th>
                </tr>

		<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Datos Modificados con &eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=cooperativas_transporte";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=cooperativas_transporte" name="Continuar"> Continuar </a>]
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
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos de la Cooperrativa:</b></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                
                                <tr>
                                    <td width="15%">
                                        RIF: <font color="Red">(*)</font>
                                    </td>
                                    <td width="85%">
                                        <input type="hidden" id="rif_cooperativa" name="rif_cooperativa"  value="<?php echo $resultados['rif_cooperativa'];?>" />
                                        <input size="12" class="inputbox validate[required]"  readonly="readonly" type="text" name="cedula1"  value="<?php  echo $resultados['rif_cooperativa']; ?>" /> 																																						
                                    </td>                       
                                </tr>

                                <tr>
                                    <td>
                                        NOMBRE COOPERATIVA:<font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <input autofocus="true"  class="validate[required] text-input" value="<?php echo $resultados['nombre_cooperativa'];?>" type="text" id="nombre_cooperativa" name="nombre_cooperativa" maxlength="100" size="60"/>
                                    </td>			
                                </tr>
                                <tr>
                                    <td>
                                        DIRECCI&Oacute;N:  <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <input class="validate[required] text-input" type="text" id="direccion_cooperativa" name="direccion_cooperativa" value="<?php echo $resultados['direccion_cooperativa'];?>"  size="60" maxlength="150"/>	
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        TEL&Eacute;FONO:
                                    </td>
                                    <td >
                                        <input class="validate[custom[phone]] text-input" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" id="telefono_cooperativa" type="text" name="telefono_cooperativa" size="15" value="<?php echo $resultados['telefono_cooperativa'];?>" maxlength="15"/>														
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        PERSONA CONTACTO/RESP.:
                                    </td>
                                    <td>
                                        <input type="text" id="persona_contacto_cooperativa" name="persona_contacto_cooperativa" value="<?php echo $resultados['persona_contacto_cooperativa'];?>" onkeyup="" size="50" maxlength="50"/>																	
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        TEL&Eacute;FONO PERSONA CONTACTO.:  
                                    </td>
                                    <td>
                                        <input class="validate[custom[phone]] text-input" placeholder="(0414)-1234567" title="Ej.: (0414)-1234567" id="telefono_persona_contacto_cooperativa" type="text" name="telefono_persona_contacto_cooperativa" size="15" value="<?php echo $resultados['telefono_persona_contacto_cooperativa'];?>" maxlength="15"/>														
                                    </td>
                                </tr>
                                <tr>
                                    <td>TIPO DE TRANSPORTE <font color="Red">(*)</font></td>
                                    <td >
                                        <div id="unidades">
                                            <select name="cod_tipo_transporte_cooperativa" id="cod_tipo_transporte_cooperativa" class="validate[required]">
                                                <option selected="selected" value="">---</option>
                                                <?php 
                                                    $consulta_sql=pg_query("SELECT * FROM tipos_transporte_cooperativas where status_tipo_transporte=1 order by descripcion_tipo_transporte");
                                                    while ($array_consulta=pg_fetch_array($consulta_sql)){
                                                        if ($array_consulta[0]==$resultados['cod_tipo_transporte_cooperativa']){
                                                            echo '<option value="'.$array_consulta[0].'" selected="selected">'.$array_consulta[1].'</option>';	
                                                        }else {
                                                            echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';	
                                                        }
                                                    }																																						
                                                    pg_free_result($consulta_sql);								
                                                ?>				
                                            </select> 
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=cooperativas_transporte'" value="Cerrar" name="cerrar" />  
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

<script type="text/javascript" >
    jQuery(function($) {
      $.mask.definitions['~']='[JVGjvg]';
      //$('#fecha_nac').mask('99/99/9999');
      $('#telefono_cooperativa').mask('(9999)-9999999');
      $('#telefono_persona_contacto_cooperativa').mask('(9999)-9999999');
      $('#celular').mask('(9999)-9999999');
      $('#rif_cooperativa').mask('~-9999?9999-9',{placeholder:" "});
      $('#cedula_rif').mask('~-9999?99999',{placeholder:" "});
    });
</script>
