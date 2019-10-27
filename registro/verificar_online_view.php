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
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    include ("conexion_r/aut_config.inc.php");
    include ("conexion_r/connect.php");
    
    if (isset($_POST[submit])){
        $codigo= intval($_POST['codigo']);
        // Verificar si existe el Registro
        $query="SELECT * FROM estudiantes_asignados WHERE estudiantes_asignados.codigo='$codigo' and estudiantes_asignados.status=1";
        $result = pg_query($query) or die(pg_last_error());
        $total_result= pg_num_rows($result);
        $resultados = pg_fetch_array($result);
        
        if ($total_result==0){
            $div_menssage='<div align="left"><h3 class="pending"><font size="2" color="black" style="text-decoration:blink;">Disculpe, El Numero de Cédula: <font color="blue">'.$codigo.'</font>; que intenta buscar no tiene canaima asignada, debes esperar el censo de asignación de la segunda avanzada del programa Canaimas Universitarias</font></h3></div>';		
        }
    }
    
?>
<!-- sincronizar mensaje cuando de muestra al usuario -->
<?php if($div_menssage) { ?>					
	<script type="text/javascript">
		function ver_msg(){
		 	Effect.Fade('msg');
		}  
		setTimeout ("ver_msg()", 8000); //tiempo de espera en milisegundos
	</script>
 <?php } ?>
        
<div style="visibility: hidden;" id="mensaje_espera" class="msj_cargando"></div>
	<div id="mensaje_espera_texto" class="msj_cargando_texto" style="visibility: hidden;">Por favor espere...</div>
	<div style="top: 477px; left: 966px; display: none;" id="mensajesAyuda">
		<div id="ayudaTitulo">Código de Seguridad (Obligatorio)</div>
		<div id="ayudaTexto">Ingresa el código de seguridad que muestra la imagen</div>
	</div>
		    					
 <!--aqui es donde esta el diseño del formulario-->
<div align="left">
    <h4>VERIFICAR SOLICITUD</h4>				
      <div>				        
        <div style="text-align: justify; font-size : 14px">
            <strong>Para Verificar la Solicitud:</strong>
            <br />
            <strong>&nbsp;&nbsp;1- </strong>Ingrese el numero de Código y luego presione click en Continuar.<br />
            <br />		    
            <br />		    
            <strong>Dudas, Recomendaciones o Sugerencias a: <a href="<?php echo $name_email;?>"><?php echo $name_email;?></a>     	 
        </div>    
     </div>   
</div> 
<br />
<br />
<div align="center" class="centermain">
    <div class="main"> 
        	<!-- Formulario de la Busqueda -->
        <form method="GET" action="javascript:validarCodigo();" id="QForm" name="QForm" enctype="multipart/form-data">																				
            <table class="adminform"  width="350px" align="center">
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
                                    <td width="40%" height="22">
                                            INGRESE EL CODIGO: &nbsp;
                                    </td>
                                    <td  height="22">
                                        <input id="codigo" autofocus="true" name="codigo"  class="validate[required,custom[integer]] text-input" type="text"  value="<?php echo $codigo;?>"   size="12" maxlength="12"/>
                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Nro. CEDULA','Ingrese el Numero de Cedula.   Ej.: 17004011', ' (Campo Requerido)')">														
                                    </td>
                                </tr>

                            </tbody>
                        </table> 
                    </td>
                </tr>													
                <tr>
                    <td colspan="2" class="botones" align="center" >
                        <input class="button" value="Buscar" onclick="javascript:validarCodigo();" type="button" />	
                        <!--<input class="button" type="submit" name="submit" value="CONTINUAR" />-->	
                    </td>			
                </tr>										   
            </table> 												
            <br>
        </form>
        
        <table border="0px" >
            <tbody>
                <tr>
                    <td>
                        <div id="contenedor_informacion">       
                            
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
    </div>
</div>     

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

