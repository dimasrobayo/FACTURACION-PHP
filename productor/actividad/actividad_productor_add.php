<?php
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    if (isset($_GET['cedula_rif'])){ // Recibir los Datos 
        $cedula_rif= $_GET['cedula_rif'];

        $query="select * from productor,comunidades where productor.cedula_rif='$cedula_rif' AND productor.idcom=comunidades.idcom";
        $result = pg_query($query)or die(pg_last_error());
        $result_solicitantes=pg_fetch_array($result);	
        pg_free_result($result);
    }

    if (isset($_POST[save])){   // Insertar Datos del formulario
        $cedula_rif=$_POST['cedula_rif'];
        $codigo_rubro=$_POST["codigo_rubro"];
        $cantidad=$_POST["cantidad"];
        $superficie_rubro=$_POST["superficie_rubro"];
        $fecha_siembra=$_POST["fecha_siembra"];
        $fecha_cosecha=$_POST["fecha_cosecha"];
        $experiencia_rubro=$_POST["experiencia_rubro"];
        
        $query="insert into actividad(codigo_rubro,cantidad,superficie_rubro,fecha_siembra,fecha_cosecha,experiencia_rubro,cedula_rif) values ($codigo_rubro,'$cantidad','$superficie_rubro','$fecha_siembra','$fecha_cosecha','$experiencia_rubro','$cedula_rif') RETURNING codigo_actividad";
        $result = pg_query($query)or die(pg_last_error());
        $result_insert=pg_fetch_row($result);
        $cod_ticket = $result_insert[0];
        pg_free_result($result);
   
        $query="select * from productor,rubro,tipo_actividad,actividad where actividad.cedula_rif='$cedula_rif' AND tipo_actividad.codigo_tipo=rubro.codigo_tipo AND rubro.codigo_rubro= actividad.codigo_rubro AND actividad.cedula_rif=productor.cedula_rif";
        $result = pg_query($query)or die(pg_last_error());
        $result_solicitantes=pg_fetch_array($result);	
        pg_free_result($result);
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
        <table class="adminproductor" width="100%">
            <tr>
                <th>
                    PRODUCTOR
                </th>
            </tr>
        </table>
	    					
	<form id="QForm" name="QForm" method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr>
                    <th colspan="2" align="center">
                        <img src="images/add.png" width="16" height="16" alt="Nuevo Registro">
                        REGISTRAR ACTIVIDAD AL PRODUCTOR
                    </th>
                </tr>
                <?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->
                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">	
                                    <?php echo 'Ticket Nro.: '.$cod_ticket.' Registrado con &eacute;xito.<br/><font color="#CC0000" style="text-decoration:blink;">'.$upload_menssage.'</font>';?>
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=productor_load_view<?php echo '&cedula_rif='.$cedula_rif;?>";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=productor_load_view<?php echo '&cedula_rif='.$cedula_rif;?>" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>
                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
                <tr>
                   <td  colspan="2"   height="18">
                       <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información Básica del Productor:</b></td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td  width="15%" height="22">
                                            C&Eacute;DULA / RIF: <font color="Red">(*)</font>
                                    </td>
                                    <td  width="85%"  height="22">														
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td width="100">									
                                                        <input type="hidden" id="cedula_rif" name="cedula_rif"  value="<?php echo $result_solicitantes['cedula_rif'];?>" />
                                                        <input size="10" class="inputbox validate[required]"  readonly="readonly" type="text" name="cedula1"  value="<?php  echo substr_replace($result_solicitantes['cedula_rif'],'-',1,0); ?>" /> 																																						
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>	

                                <tr>
                                    <td>
                                        NOMBRE DEL PRODUCTOR: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <input readonly="true" class="validate[required] text-input" type="text" id="nombre"  name="nombre" value="<?php echo $result_solicitantes[nombre];?>" onkeyup="" size="50" maxlength="50"/>																	
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        DOCUMENTO TENENCIA: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <?php echo $result_solicitantes['documento_tenencia'];?>														
                                                    </td>

                                                    <td>
                                                        POSEE MAQUINARIA: 
                                                        <?php echo $result_solicitantes['posee_maquinaria'];?>
                                                    </td>
                                                    <td>
                                                        POSEE INFRAESTRUCTURA: 
                                                        <?php echo $result_solicitantes['posee_infra'];?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        TEL&Eacute;FONO HAB.:
                                    </td>
                                    <td>														
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td width="130">
                                                        <input readonly="true" class="validate[custom[phone]] text-input" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" id="telefono" type="text" name="telefono" size="15" value="<?php echo $result_solicitantes['telefono_fijo'];?>" maxlength="15"/>														
                                                    </td>
                                                    <td>
                                                        TEL&Eacute;FONO CEL.: <font color="Red">(*)</font>
                                                    </td>
                                                    <td>
                                                        <input readonly="true" class="validate[required,custom[phone]] text-input" placeholder="(0414)-1234567" title="Ej.: (0414)-1234567" id="celular" type="text" name="celular" size="15" value="<?php echo $result_solicitantes['telefono_movil'];?>" maxlength="15"/>														
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
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información de la Actividad del Productor:</b></td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                 <tr>
                                    <td width="15%">
                                        RUBRO:
                                    </td>
                                    <td>
                                        <select name="codigo_rubro" id="codigo_rubro" class="validate[required]">
                                            <option selected="selected" value="">---</option>
                                            <?php 
                                                $consulta_sql=pg_query("SELECT tipo_actividad.descripcion, rubro.descripcion, rubro.codigo_rubro FROM tipo_actividad, rubro where tipo_actividad.codigo_tipo=rubro.codigo_tipo order by rubro.descripcion");
                                                while ($array_consulta=pg_fetch_array($consulta_sql)){																																				
                                                    echo '<option value="'.$array_consulta[2].'">'.$array_consulta[1].'</option>';																			
                                                }																																						
                                                pg_free_result($consulta_sql);								
                                            ?>				
                                        </select> 
                                        CANTIDAD:
                                        <input type="text" id="cantidad" name="cantidad" value="" onkeyup="" size="10" maxlength="10"/>																	
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        SUPERFICIE DEL RUBRO: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <textarea class="validate[required]" name="superficie_rubro" id="superficie_rubro" cols="48" rows="3" onkeyup=""></textarea>																	
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        FECHA SIEMBRA: 
                                    </td>
                                    <td>
                                        <input class="validate[custom[date]]" name="fecha_siembra" type="text" value="<?php if ($error!="") echo implode('/',array_reverse(explode('-',$fecha_siembra)));?>"  id="fecha_siembra"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"  />
                                        <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_siembra,'dd/mm/yyyy',this);">
                                        FECHA COSECHA:
                                        <input class="validate[custom[date]]" name="fecha_cosecha" type="text" value="<?php if ($error!="") echo implode('/',array_reverse(explode('-',$fecha_cosecha)));?>"  id="fecha_cosecha"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"  />
                                        <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_cosecha,'dd/mm/yyyy',this);">
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>EXPERIENCIA EN EL RUBRO: <font color="Red">(*)</font></td>
                                    <td>														         
                                         <input type="text" id="experiencia_rubro" class="validate[required] text-input"  name="experiencia_rubro" maxlength="10" size="10"/>
                                     </td>
                               </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=productor_load_view<?php echo '&cedula_rif='.substr_replace($cedula_rif,'-',1,0);?>'" value="Cerrar" name="cerrar" />  
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
	      $('#telefono').mask('(9999)-9999999');
	      $('#celular').mask('(9999)-9999999');
	      
	});
</script>

