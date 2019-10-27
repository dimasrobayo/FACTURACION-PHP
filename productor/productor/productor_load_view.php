<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
	
    //verifica la recepcion de los datos para buscar y mostrar
    if (isset($_POST['cedula_rif'])){
        $cedula_rif=strtoupper($_POST['cedula_rif']);
    }else {
        $cedula_rif=strtoupper($_GET['cedula_rif']);
    } 
	
    if ($cedula_rif){  // consulta de los datos para Mostrar
        
        $cedula_rif_buscar = preg_replace("/\s+/", "", $cedula_rif);
        $cedula_rif_buscar = str_replace("-", "", $cedula_rif_buscar);
        
        // Verificar si existe el Registro
        $query="SELECT * FROM productor WHERE productor.cedula_rif='$cedula_rif_buscar' order by cedula_rif";
        $result = pg_query($query) or die(pg_last_error());
        $total_result= pg_num_rows($result);	
        $resultados_solicitantes=pg_fetch_array($result);
        pg_free_result($result);
        
        if ($total_result){
            // Verificar si existe el Registro
            $query="SELECT actividad.codigo_actividad,tipo_actividad.descripcion, rubro.descripcion, actividad.cantidad, "
                    . "actividad.superficie_rubro, actividad.fecha_siembra, actividad.fecha_cosecha, actividad.experiencia_rubro FROM tipo_actividad, rubro, actividad". 
                    " WHERE actividad.cedula_rif='$cedula_rif_buscar' AND tipo_actividad.codigo_tipo=rubro.codigo_tipo".
                    " AND rubro.codigo_rubro=actividad.codigo_rubro order by actividad.codigo_actividad DESC";
            $result = pg_query($query) or die(pg_last_error());
            $total_result_ticket= pg_num_rows($result);	
        }


        if (isset($_POST[submit]) AND ($total_result==0)){
            $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">La Cédula ó RIF: <font color="blue">'.$cedula_rif.'</font>; No Exite!</font></h3></div>';		
        }

//        if (isset($_POST[submit_add]) AND ($total_result==0)){
//            //header ("Location: ?view=pastor_add&submenuheader=$submenuheader&codigo=$cedula");
//            echo "<script type='text/javascript'>window.location.href='?view=pastor_add&submenuheader=$submenuheader&menuitem=$menuitem&codigo=$cedula'</script>";	 
//            exit;
//        }

    }			
?>
<!-- Ventanas emergentes -->
<script type="text/javascript" charset="utf-8">			      
	jQuery(document).ready(function(){
		/* normal effects*/ 
		jQuery('.fancybox-normal').fancybox();
				
		/* Con effects*/ 		
		jQuery(".fancybox").fancybox({
			maxWidth	: 550,
		   maxHeight	: 550,
			fitToView	: false,
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none',
//			padding : 0, 
//			type: 'iframe',       		
        	helpers : {
         	title : null            
     		}        		
		});
		
		jQuery(".fancybox-iglesia").fancybox({
			maxWidth	: 550,
		   maxHeight	: 420,
			fitToView	: false,
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none',
//			padding : 0, 
//			type: 'iframe',        		
        	helpers : {
         	title : null            
     		}        		
		});
		
		jQuery(".fancybox-foto").fancybox({
			maxWidth	: 550,
		   maxHeight	: 250,
			fitToView	: false,
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none',
//			padding : 0, 
//			type: 'iframe',      		
        	helpers : {
         	title : null            
     		}        		
		});			
	});  
	
	<!-- Ventanas emergentes -->
	function ue_buscarsolicitante()	{
		document.QForm.cedula_rif.value="";											
		window.open("productor/productor_load.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=310,left=50,top=50,location=no,resizable=no");
	}                     
</script> 
<!-- sincronizar mensaje cuando de muestra al usuario -->
<?php if($div_menssage) { ?>					
	<script type="text/javascript">
		function ver_msg(){
		 	Effect.Fade('msg');
		}  
		setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
	</script>
 <?php } ?>		    					
 <!--aqui es donde esta el diseño del formulario-->
<table border="0" width="100%" align="center">
    <tbody>			
        <tr>
            <td id="msg" align="center">	
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

	<!-- Formulario de la Busqueda -->
<form method="POST" action="?view=productor_load_view" id="QForm" name="QForm" enctype="multipart/form-data">																				
    <table class="adminform"  width="350px" align="center">
        <tr>
            <th colspan="2" align="center">
                    IDENTIFICACI&Oacute;N DEL PRODUCTOR
            </th>
        </tr>	   					
        <tr>
            <td colspan="2" align="center"> 
                <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                    <tbody>
                        <tr>
                            <td width="30%" height="22">
                                    C&Eacute;DULA/RIF: &nbsp;
                            </td>
                            <td  height="22">
                                <input id="cedula_rif" name="cedula_rif"  class="validate[required,minSize[6]] text-input" type="text"  value="<?php if($total_result==0) echo $cedula_rif;?>"  size="10" maxlength="12"/>
                                <a href="javascript: ue_buscarpruductor();"><img src="images/busqueda.png" alt="Buscar" title="Buscar Productor" width="15" height="15" border="0"></a>
                                <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Cédula RIF','Ingrese la Cédula ó RIF.   Ej.: Cedula:V-123456 ó RIF:J-12345678-0', ' (Campo Requerido)')">														
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </td>
        </tr>													
        <tr>
            <td colspan="2" class="botones" align="center" >											
                <input class="button" type="submit" name="submit" value="CONTINUAR" />	
                <?php 
                    if (isset($_POST[submit]) AND ($total_result==0)  AND ($_SESSION[nivel]!=1)){
                        echo "<input  class=\"button\" type=\"button\" onclick=\"javascript:window.location.href='?view=productor_add&cedula_rif=$cedula_rif'\" value=\"AGREGAR REGISTRO\" name=\"cerrar\" /> ";
                    }
                ?>
            </td>			
        </tr>										   
    </table> 												
    <br>
</form>
<?php if ($total_result!=0 ){ ?>
	<!-- Formulario de los datos encontrados -->
<table class="gen_table_form" cellspacing="1" cellpadding="2"  border="1" align="center" >
    <tbody>
       <tr>
            <th class="section_name" colspan="10">DATOS DEL PRODUCTOR</th>
       </tr>
       <tr>											
            <td class="item_text" width="8%"  align="center">C&Eacute;DULA/RIF</td>											
            <td class="item_text" width="20%" align="center">NOMBRE DEL PRODUCTOR</td>																					
            <td class="item_text" width="6%" align="center">DOC. TENENCIA</td>											
            <td class="item_text" width="6%" align="center">POSEE MAQUINARIA</td>
            <td class="item_text" width="6%" align="center">POSEE INFRA.</td>	
            <td class="item_text" width="8%" align="center">TELÉFONO</td>
            <td class="item_text" width="8%" align="center">CELULAR</td>
            <td class="item_text" width="6%" align="center">UTM NORTE</td>
            <td class="item_text" width="6%" align="center">UTM ESTE</td>
            <td class="item_text" width="6%" align="center">ACCIONES</td>											
        </tr>
        <tr class="item_claro"> 
            <td align="center"><?php  echo substr_replace($resultados_solicitantes['cedula_rif'],'-',1,0); ?></td>
            <td><font color="blue"><?php  echo $resultados_solicitantes['nombre']; ?></font></td>
            <td align="center"><?php  echo $resultados_solicitantes['documento_tenencia']; ?></td>
            <td align="center"><?php  echo $resultados_solicitantes['posee_maquinaria']; ?></td>
            <td align="center"><?php  echo $resultados_solicitantes['posee_infra']; ?></td>			
            <td align="center"><?php  echo $resultados_solicitantes['telefono_fijo']; ?></td>			
            <td align="center"><?php  echo $resultados_solicitantes['telefono_movil']; ?></td>	
            <td align="center"><?php  echo $resultados_solicitantes['coor_utm_norte']; ?></td>	
            <td align="center"><?php  echo $resultados_solicitantes['coor_utm_este']; ?></td>	
            <td align="center">
                <?php if($_SESSION[nivel]!=1){ ?>
                <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=productor_drop&cedula_rif=<?php echo $resultados_solicitantes[cedula_rif];?>" title="Pulse para eliminar el registro">
                    <img border="0" src="images/borrar28.png" alt="borrar">
                </a>
                <a href="index2.php?view=productor_update&cedula_rif=<?php echo $resultados_solicitantes[cedula_rif];?>" title="Pulse para Modificar los datos registrados">
                    <img border="0" src="images/modificar.png" alt="borrar">
                </a>	      
                <a href="index2.php?view=actividad_productor_add&cedula_rif=<?php echo $resultados_solicitantes[cedula_rif];?>" title="Pulse para Registrar un Ticket">
                    <img border="0" src="images/tramites28.png" alt="borrar">
                </a>	      
                <?php }else{ ?>     													     
                <a href="index2.php?view=productor_update&cedula_rif=<?php echo $resultados_solicitantes[cedula_rif];?>" title="Pulse para Modificar los datos registrados">
                    <img border="0" src="images/modificar.png" alt="borrar">
                </a>	      
                <?php } ?>
            </td>												
        </tr>
    </tbody>
</table>	
<br />
	<!-- Datos de la carga familia del registro Registro -->
<table class="gen_table_form" cellspacing="1" cellpadding="2" width="800" align="center" border="1">
    <tbody>
        <tr>
            <th class="section_name" colspan="10">DATOS DE ACTIVIDADES DEL PRODUCTOR</th>
        </tr>
        <tr>											
            <td class="item_text" width="5%"  align="center">Nº</td>											
            <td class="item_text" width="25%" align="center">TIPO_ACTIVIDAD</td>
            <td class="item_text" width="25%" align="center">RUBRO</td>
            <td class="item_text" width="10%" align="center">CANTIDAD</td>											
            <td class="item_text" width="10%" align="center">SUPERFICIE X RUBRO</td>
            <td class="item_text" width="15%" align="center">FECHA SIEMBRA</td>
            <td class="item_text" width="15%" align="center">FECHA COSECHA</td>
            <td class="item_text" width="20%" align="center">EXPERIENCIA CON RUBRO</td>           
            <td class="item_text" width="10%" align="center">ACCIONES</td>											
        </tr>

        <?php
            if($total_result_ticket==0){
                echo '<tr class="item_oscuro">';		
                echo '<td align="center" colspan="10"> NO EXISTE ACTIVIDAD REGISTRA PARA ESTE PRODUCTOR</td>';
                echo '</tr>';
            }

            $xxx=0;
            while($resultados_actividad = pg_fetch_array($result)) {	
                $xxx=$xxx+1;			
                if (($xxx %2)==0) $i='item_claro'; else $i='item_oscuro';
        ?>
            <tr class="<?php echo $i;?>">		
                <td align="center"><?php echo $resultados_actividad['0']?></td>													
                <td><?php echo $resultados_actividad['1']?> </td>    
                <td><?php echo $resultados_actividad['2']?> </td> 
                <td><?php echo $resultados_actividad['3']?> </td>
                <td><?php echo $resultados_actividad['4']?> </td>
                <td align="center"><?php echo date_format(date_create($resultados_actividad['fecha_siembra']), 'd/m/Y g:i A.') ;?> </td>
                <td align="center"><?php echo date_format(date_create($resultados_actividad['fecha_cosecha']), 'd/m/Y g:i A.') ;?> </td>
                <td><?php echo $resultados_actividad['7']?> </td>
                <td align="center">	
                    <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=actividad_drop&codigo_actividad=<?php echo $resultados_actividad[codigo_actividad];?>&cedula_rif=<?php echo $resultados_solicitantes[cedula_rif];?>" title="Pulse para eliminar el registro">
                        <img border="0" src="images/borrar28.png" alt="borrar">
                    </a>											
                </td>												
            </tr>
        <?php 
            } //fin del while
        ?>
    </tbody>
</table>
<br>
<br>
<?php } ?>	

<script type="text/javascript" >
	jQuery(function($) {
	      $.mask.definitions['~']='[JEVGDCjevgdc]';
	      //$('#fecha_nac').mask('99/99/9999');
	      //$('#fecha_deposito').mask('99/99/9999');
	      $('#telefono').mask('(9999)-9999999');
	      $('#celular').mask('(9999)-9999999');
	      $('#telefono_trabajo').mask('(9999)-9999999');
	      $('#telefono_fax').mask('(9999)-9999999');
	      $('#rif').mask('~-9999?9999-9',{placeholder:" "});
	      $('#cedula_rif').mask('~-9999?99999',{placeholder:" "});
	      //$('#phoneext').mask("(999) 999-9999? x99999");
	      //$("#tin").mask("99-9999999");
	      //$("#ssn").mask("999-99-9999");
	      //$("#product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("Ha escrito lo siguiente: "+this.val());}});
	      //$("#eyescript").mask("~9.99 ~9.99 999");
	      
	   });
	   
   function ue_buscariglesia()	{
		document.QForm.igl_cod_iglesia_mision.value="";
		document.QForm.igl_nombre_iglesia_mision.value="";									
		window.open("iglesias/cat_iglesias.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=565,height=500,left=50,top=50,location=no,resizable=no");
	}	
</script>

				    				