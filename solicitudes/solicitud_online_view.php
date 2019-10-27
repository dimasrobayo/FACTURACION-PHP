<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
//        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    if (isset($_POST[submit])){
        $cod_ticket= intval($_POST['cod_ticket']);
        // Verificar si existe el Registro
        $query="SELECT * FROM ticket,tramites,unidades,estados_tramites". 
                " WHERE ticket.cod_ticket='$cod_ticket' AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND ticket.cod_tramite=tramites.cod_tramite AND tramites.cod_unidad=unidades.cod_unidad  order by cod_ticket DESC";
        $result = pg_query($query) or die(pg_last_error());
        $total_result_ticket= pg_num_rows($result);
        
        if ($total_result_ticket==0){
            $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Nro. Ticket:: <font color="blue">'.$cod_ticket.'</font>; No Exite!</font></h3></div>';		
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
		    					
 <!--aqui es donde esta el diseño del formulario-->
<div align="left">
    <h4>CONSULTAR SOLICITUD</h4>				
      <div>				        
        <div style="text-align: justify; font-size : 14px">
            <strong>Para consultar la solicitud:</strong>
            <br />
            <strong>&nbsp;&nbsp;1- </strong>Ingrese el numero de Ticket  y luego presione click en Continuar.<br />
            <br />		    
            <br />		    
            <strong>Dudas, Recomendaciones o Sugerencias a: <a href="mailto:atencion.evalsa@gmail.com">evalsa@gmail.com</a>     	 
        </div>    
     </div>   
</div> 
<br />
<br />
<table border="0" width="350px" align="center">
    <tbody>			
        <tr>
            <td id="msg" align="center">	
                <?php echo $div_menssage;?>						
            </td>
        </tr>
    </tbody>
</table>
<div align="center" class="centermain">
    <div class="main"> 
        	<!-- Formulario de la Busqueda -->
        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">																				
            <table class="adminform"  width="350px" align="center">
                <tr>
                    <th colspan="2" align="center">
                            IDENTIFICACI&Oacute;N DEL TICKET
                    </th>
                </tr>	   					
                <tr>
                    <td colspan="2" align="center"> 
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                            <tbody>

                                <tr>
                                    <td width="35%" height="22">
                                            NRO. TICKET : &nbsp;
                                    </td>
                                    <td  height="22">
                                        <input id="cod_ticket" autofocus="true" name="cod_ticket"  class="validate[required,custom[number]] text-input" type="text"  value="<?php echo $cod_ticket;?>"  size="10" maxlength="10"/>
                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Nro. TAC','Ingrese el Numero del Ticket.   Ej.: 100', ' (Campo Requerido)')">														
                                    </td>
                                </tr>

                            </tbody>
                        </table> 
                    </td>
                </tr>													
                <tr>
                    <td colspan="2" class="botones" align="center" >											
                        <input class="button" type="submit" name="submit" value="CONTINUAR" />	
                    </td>			
                </tr>										   
            </table> 												
            <br>
        </form>
        <?php if ($total_result_ticket!=0 ){ ?>        
        <table class="gen_table_form" cellspacing="1" cellpadding="2" width="700" align="center" border="1">
            <tbody>
                <tr>
                    <th class="section_name" colspan="8">DATOS DE Ticket de Atención al Soberano</th>
                </tr>
                <tr>											
                    <td class="item_text" width="10%"  align="center">Nº TICKET</td>											
                    <td class="item_text" width="18%" align="center">FECHA REGISTRO</td>																					
                    <td class="item_text" width="30%" align="center">UNIDAD INICIAL ASIGNADA</td>											
                    <td class="item_text" width="30%" align="center">TRAMITE</td>
                    <td class="item_text" width="10%" align="center">ESTADO</td>
                    <td class="item_text" width="5%" align="center">ACCION</td>											
                </tr>

                <?php
                    $xxx=0;
                    while($resultados_ticket = pg_fetch_array($result)) {	
                        $xxx=$xxx+1;			
                        if (($xxx %2)==0) $i='item_claro'; else $i='item_oscuro';
                ?>
                    <tr class="<?php echo $i;?>">		
                        <td align="center">
                            <?php
                                if($resultados_ticket[prioridad_ticket]==1){
                                    echo '<font color="black">'.str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT).'</font>';
                                }elseif($resultados_ticket[prioridad_ticket]==2){
                                    echo '<font color="ffba00">'.str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT).'</font>';
                                }else{
                                    echo '<font color="red">'.str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT).'</font>';
                                }

                            ?>
                        </td>													
                        <td align="center"><?php echo date_format(date_create($resultados_ticket['fecha_registro']), 'd/m/Y g:i A.') ;?> </td>
                        <td><?php echo $resultados_ticket['nombre_unidad']?> </td>

                        <td><?php echo $resultados_ticket['nombre_tramite']?> </td>

                        <td>
                            <?php
                                if ($resultados_ticket['tipo_estado_tramite']==1){
                                    echo '<img  border="0" name="Image_Encab" src="images/help.png">';
                                }elseif ($resultados_ticket['tipo_estado_tramite']==2){
                                    echo '<img  border="0" name="Image_Encab" src="images/tick.png">';
                                }elseif ($resultados_ticket['tipo_estado_tramite']==3 && $resultados_ticket['siglas_estado_tramite']=="NUL" ){
                                    echo '<img  border="0" name="Image_Encab" src="images/borrar.png">';
                                }else{
                                    echo '<img  border="0" name="Image_Encab" src="images/delete.png">';
                                }
                                echo ' '.$resultados_ticket['siglas_estado_tramite']
                            ?> 
                        </td>
                        <td align="center">	
                            <a title="Imprimir Ticket" target="_Blank" href="reportes/imprimir_tac_online.php?cod_ticket=<?php echo $resultados_ticket['cod_ticket']?>">
                            <img  border="0" name="Image_Encab" src="images/printer28.png">
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
                
    </div>
</div>     

<script type="text/javascript" >
	jQuery(function($) {
	      $.mask.definitions['~']='[JEVGDCHjevgdch]';
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

