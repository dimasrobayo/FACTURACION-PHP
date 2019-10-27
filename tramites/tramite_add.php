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

<?php 
    if (isset($_GET['cod_unidad'])){
    	$datos_unidad= $_GET['cod_unidad'];
        $query = "SELECT * FROM unidades WHERE cod_unidad=$datos_unidad";
        $resulta = pg_query($query)or die(pg_last_error());
	$resulta_unidad=pg_fetch_array($resulta);
    }
?> 

<?php 
    if (isset($_POST[save])) {
        $categoria = $_POST['cod_categoria'];
        $tramite = $_POST['tramite'];
        $descripcion = $_POST['descripcion'];
        $unidad=$_POST['unidad'];
        $otorga = $_POST['otorga'];
        $costor = $_POST['costor'];
        $costoh = $_POST['costoh'];
        $entregar = $_POST['entregar'];
        $entregah = $_POST['entregah'];
        $horarioc = $_POST['horarioc'];
        $horarioe = $_POST['horarioe'];
        $observaciones = $_POST['observaciones'];
        if (isset($_POST["tramite_online"])){	
            $tramite_online=1;
        }else {
            $tramite_online=0;
        }
        
        $query = "insert into tramites (cod_categoria,nombre_tramite,descripcion_tramite,cod_unidad,cod_tipo_solicitante,costo_regular,costo_habilitado,entrega_regular,entrega_habilitada,horario_consignacion,horario_entrega,observaciones,status_tramite_online) values ('$categoria','$tramite','$descripcion','$unidad','$otorga','$costor','$costoh','$entregar','$entregah','$horarioc','$horarioe','$observaciones',$tramite_online)";
        $result = pg_query($query)or die(pg_last_error());
        pg_free_result($result);
        $error="bien";
        
        $query = "SELECT * FROM unidades WHERE cod_unidad=$unidad";
        $resulta = pg_query($query)or die(pg_last_error());
	$resulta_unidad=pg_fetch_array($resulta);
    }//fin del add        
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
        
        <table class="admintramites" width="100%">
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
                    <td width="100"><b>CODIGO:</b> <?php echo $resulta_unidad[cod_unidad]; ?></td> 
                    <td width="120"><b>SIGLAS:</b> <?php echo $resulta_unidad['siglas_unidad']; ?></td>
                    <td width="300"><b>NOMBRE:</b> <?php echo $resulta_unidad['nombre_unidad']; ?></td>
                    <td><b>RESPONSABLE:</b> <?php echo $resulta_unidad['responsable_unidad']; ?></td>
                </tr>
        </table>	
        <br />
        
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <input class="inputbox" type="hidden" id="unidad" name="unidad" value="<?php echo $resulta_unidad[cod_unidad]; ?>" maxlength="15" size="15"/>
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        <img src="images/add.png" width="16" height="16" alt="Nuevo Registro">
                        NUEVO REGISTRO DE TRAMITE
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
                                            window.location="?view=tramites&cod_unidad=<?php echo $unidad;?>";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=tramites&cod_unidad=<?php echo $unidad;?>" name="Continuar"> Continuar </a>]
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
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos Básicos del Tramite:</b></td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                
                                <tr>
                                    <td width="20%">
                                        TRAMITE: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <input  type="text" id="tramite" autofocus="true" name="tramite" value="" onkeyup="" class="validate[required] text-input" size="50" maxlength="100"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="20%">
                                        CATEGORIA DEL TRAMITE: <font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <select id="cod_categoria" name="cod_categoria" size="0" class="validate[required]">
                                            <option value="">----</option>	        
                                                <?php
                                                    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
                                                    $consulta=pg_query("select * from categorias order by cod_categoria");
                                                    while ($array_consulta=pg_fetch_array($consulta)) {
                                                         echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                    }
                                                    pg_free_result($consulta);
                                                ?>
                                        </select>
                                    </td>			
                                </tr>
                                <tr>
                                    <td width="20%">
                                        DESCRIPCIÓN DEL TRAMITE: <font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <textarea name="descripcion" id="descripcion" class="validate[required]" cols="50" rows="3"></textarea>				
                                    </td>			
                                </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Otros Datos del Tramite:</b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td  width="20%">
                                        SE OTORGA A: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select id="otorga" name="otorga" size="0" class="validate[required]">
                                                            <option value="">----</option>	        
                                                                <?php
                                                                    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
                                                                    $consulta=pg_query("select * from tipo_solicitantes order by cod_tipo_solicitante");
                                                                    while ($array_consulta=pg_fetch_array($consulta)) {
                                                                         echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                                    }
                                                                    pg_free_result($consulta);
                                                                ?>
                                                        </select>														
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        COSTO REGULAR (Bs.): <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td>														         
                                                         <input  style="text-align:right" type="text" id="costor" class="validate[required,custom[number]] text-input"  name="costor" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="0.00" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                                                         <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Monto','Ingrese el monto incluyendo los decimales. ej: 1300.00, El separador decimal es colocado automáticamente por el sistema.',' (Campo Opcional)')">       		
                                                     </td>
                                                    <td>
                                                        COSTO HABILITADO (Bs.): <font color="Red">(*)</font> 
                                                         <input  style="text-align:right" type="text" id="costoh" class="validate[required,custom[number]] text-input"  name="costoh" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="0.00" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                                                         <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Monto','Ingrese el monto incluyendo los decimales. ej: 1300.00, El separador decimal es colocado automáticamente por el sistema.',' (Campo Opcional)')">       		
                                                     </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        ENTREGA REGULAR (D&iacute;as): <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td align="right">
                                                        <input  type="text" id="entregar" name="entregar" value="0" class="validate[required,custom[integer]" size="6" maxlength="8"/>
                                                    </td>
                                                    <td>
                                                        ENTREGA HABILITADA (D&iacute;as): <font color="Red">(*)</font> 
                                                        <input  type="text" id="entregah" name="entregah" value="0" class="validate[required,custom[integer]" size="6" maxlength="8"/>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        HORARIO DE CONSIGNACIÓN: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <input  type="text" id="horarioc" name="horarioc" value="" class="validate[required]" size="50" maxlength="200"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        HORARIO DE ENTREGA: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <input  type="text" id="horarioe" name="horarioe" value="" class="validate[required]" size="50" maxlength="200"/>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td width="20%">
                                        OBSERVACIONES:
                                    </td>

                                    <td>
                                        <textarea name="observaciones" id="observaciones" class="validate[required]" cols="50" rows="3"></textarea>				
                                    </td>			
                                </tr>
                                
                            </tbody>
                        </table>	
                    </td>
                </tr>
                
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>INFORMACIÓN DEL TRAMITE ON-LINE</b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="20%" >
                                        <input type="checkbox"  name="tramite_online" id="tramite_online" />
                                        El Tramite estar&aacute; disponible On-Line:
                                    </td>
                                </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=tramites&cod_unidad=<?php echo $resulta_unidad[cod_unidad];?>'" value="Cerrar" name="cerrar" />  
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
