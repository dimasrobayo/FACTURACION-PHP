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
    //Conexion a la base de datos
    include ("conexion_r/aut_config.inc.php");
    include ("conexion_r/connect.php");
	
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web	
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;	
	
    if (isset($_POST[submit])){

        $fecha_acta=implode('-',array_reverse(explode('/',$_POST['fecha_acta']))); 
        $fecha_nac=implode('-',array_reverse(explode('/',$_POST['fecha_nac']))); 
        $nro_acta=$_POST['nro_acta'];
        $texto_buscar=$_POST['texto_buscar'];	
		
        // consultar registros
        //$query="SELECT * FROM nacimientos INNER JOIN nacimientospresentados ON nacimientos.idactanatal=nacimientospresentados.idactanatal WHERE numeroacta like '%".$nro_acta."%' OR fechaacta like '%".$fecha_acta."%' OR fechanatal like '%".$fecha_nac."%'";
        if ($fecha_acta) {
            $query="SELECT * FROM nacimientos, nacimientospresentados WHERE nacimientos.idactanatal=nacimientospresentados.idactanatal AND  nacimientos.fechaacta='$fecha_acta'";
            $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
            $total_result= mysql_num_rows($result);
        }
        
        if ($nro_acta) {
           $query="SELECT * FROM nacimientos, nacimientospresentados WHERE nacimientos.idactanatal=nacimientospresentados.idactanatal AND nacimientos.numeroacta='$nro_acta' ";
           $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
            $total_result= mysql_num_rows($result);
        }
        
        if ($fecha_acta and $nro_acta) {
            $query="SELECT * FROM nacimientos, nacimientospresentados WHERE nacimientos.idactanatal=nacimientospresentados.idactanatal AND nacimientos.numeroacta='$nro_acta' AND nacimientos.fechaacta='$fecha_acta'";
            $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
            $total_result= mysql_num_rows($result);
        }
        
        if ($total_result==0){
            $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">La búsqueda no ha obtenido ningún resultado!</h3></div>';      
        }
        
        if ($fecha_acta=="" and $nro_actacondition=="") {
             $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">La consulta no se puede realizar con campos vacios!</h3></div>';
        }
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
           maxHeight	: 600,
                fitToView	: false,
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none',
                <!-- padding : 0, -->
                <!-- type: 'iframe',     -->    		
        helpers : {
        title : null            
        }        		
        });							
    });  	                 
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
 <!--aqui es donde esta el diseño del formulario-->
<div align="left">
    <h4>GENERAR SOLICITUD</h4>				
      <div>				        
        <div style="text-align: justify; font-size : 14px">
            <strong>Para Realizar la solicitud:</strong>
            <br />
            <strong>&nbsp;&nbsp;1- </strong>Ingrese el Dato a Consultar y luego presione click en Continuar.<br />		    
            <br />
            <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_acta,'dd/mm/yyyy',this);">
            <br /><br />
            <strong>Dudas, Recomendaciones o Sugerencias a: <a href="<?php echo $name_email;?>"><?php echo $name_email;?></a>     	 
        </div>    
     </div>   
</div> 
<br />
<br />
<table border="0" width="650px" align="center">
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
            <table class="adminform"  width="650px" align="center">
                <tr>
                    <th colspan="2" align="center">
                            IDENTIFICACI&Oacute;N DE LA SOLICITUD
                    </th>
                </tr>	   					
                <tr>
                    <td colspan="2" align="center"> 
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                            <tbody>
                            	<tr>
                                    <td width="25%" height="22">
                                            FECHA DE PRESENTACION: &nbsp;
                                    </td>
                                    
                                    <td height="22">
                                        <input autofocus="true"  class="validate[required,custom[date],past[NOW]]" name="fecha_acta" type="text" value="<?php if ($error!="") echo implode('/',array_reverse(explode('-',$fecha_acta)));?>"  id="fecha_acta"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"  />
                                        <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_acta,'dd/mm/yyyy',this);">
                                    </td>
                                </tr>

                                <tr>
                                    <td  height="22">
                                            NRO. ACTA: &nbsp;
                                    </td>
                                    <td  height="22">
                                        <input id="nro_acta" name="nro_acta"  class="validate[required,custom[integer]] text-input" type="text"  value="<?php echo $nro_acta;?>"  size="10" maxlength="10"/>
                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Nro. TAC','Ingrese el Numero de Acta.   Ej.: 100', ' (Campo Requerido)')">														
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td height="22">
                                            FECHA DE NACIMIENTO: &nbsp;
                                    </td>
                                    
                                    <td height="22">
                                        <input class="validate[custom[date],past[NOW]]" name="fecha_nac" type="text" value="<?php if ($error!="") echo implode('/',array_reverse(explode('-',$fecha_nac)));?>"  id="fecha_nac"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"  />
                                        <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_nac,'dd/mm/yyyy',this);">
                                    </td>
                                </tr>
 -->
                            </tbody>
                        </table> 
                    </td>
                </tr>
                <?php if(isset($_POST[submit]) and $total_result==0){?>
			        <tr>
		                <td colspan="2">
		                    <b>Resultado de la búsqueda...</b>
		                    <br />
		                        La búsqueda no ha obtenido ningún resultado
						</td>
					</tr>
			        <?php 	}else{ if(isset($_POST[submit])){ // fin del IF ?>
		            <tr>
		                <td colspan="2">
		                    <b>Resultado de la búsqueda...</b>
		                    <br />
		                        <b>Todos los campos </b> <?php echo $total_result;?> resultado(s).
						</td>
		            </tr>
			        <?php 	}} // fin del IF ?>													
                <tr>
                    <td colspan="2" class="botones" align="center" >											
                        <input class="button" type="submit" name="submit" value="CONTINUAR" />	
                    </td>			
                </tr>										   
            </table> 												
            <br>
        </form>
        <?php if ($total_result!=0 ){ ?>        
        <table class="gen_table_form" cellspacing="1" cellpadding="2" width="700" align="center" border="1">
            <tbody>
                <tr>
                    <th class="section_name" colspan="8">DATOS DE LA CONSULTA RALIZADA</th>
                </tr>
                <tr>											
                    <td class="item_text" width="15%"  align="center">Nº Acta Natal</td>											
                    <td class="item_text" width="15%" align="center">Nº Acta</td>																					
                    <td class="item_text" width="15%" align="center">Fecha Acta</td>											
                    <td class="item_text" width="15%" align="center">Nº Folio</td>
                    <td class="item_text" width="30%" align="center">Nombres</td>
                	<td class="item_text" width="15%" align="center">Fecha Natal</td>
                    <td class="item_text" width="5%" align="center">Acción</td>											
                </tr>

                <?php
                    $xxx=0;
                    while($resultados = mysql_fetch_array($result)) {	
                        $xxx=$xxx+1;			
                        if (($xxx %2)==0) $i='item_claro'; else $i='item_oscuro';
                ?>
                    <tr class="<?php echo $i;?>">		
                        <td align="center"><?php echo $resultados['idactanatal']?> </td>
                        <td align="center"><?php echo $resultados['numeroacta']?> </td>
                        <td align="center"><?php echo date_format(date_create($resultados['fechaacta']), 'd/m/Y') ;?> </td>
                        
                        <td align="center"><?php echo $resultados['folio']?> </td>
                        <td><?php echo $resultados['primernombre'].' '.$resultados['segundonombre']?> </td>
                        <td align="center"><?php echo date_format(date_create($resultados['fechanatal']), 'd/m/Y') ;?> </td>
                        
                        <td align="center">	
                            <a title="Imprimir" target="_Blank" href="reportes/imprimir_partida_nacimiento.php?idactanatal=<?php echo $resultados['idactanatal']?>">
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
    					
				    				   				
