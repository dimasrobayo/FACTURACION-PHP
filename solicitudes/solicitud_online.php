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
?>
<div align="left">
    <h4>GENERAR SOLICITUD</h4>				
      <div>				        
        <div style="text-align: justify; font-size : 14px">
            <strong>Para realizar la solicitud:</strong>
            <br />
            <strong>&nbsp;&nbsp;1- </strong>Ingrese el numero de Cédula o RIF y luego presione click en Continuar.<br />
            <strong>&nbsp;&nbsp;2- </strong>Si es un nuevo usuario, complete los datos Basicos o Personales.<br />
            <strong>&nbsp;&nbsp;3- </strong>Hacer click en el tipo de solicitud y seleccione un tramite que se adapte al tipo de solicitud que va realizar.<br />
            <strong>&nbsp;&nbsp;4- </strong>Luego complete los datos de informacion de su solictud.
            	
            <br />		    
            <br />		    
            <strong>Dudas, Recomendaciones o Sugerencias a: <a href="mailto:evalsa@gmail.com">evalsa@gmail.com</a>     	 
        </div>    
     </div>   
</div> 
<br />
<br />
<div align="center" class="centermain">
    <div class="main"> 
        
        <form method="POST" action="index.php?view=solicitud_online_add" id="QForm" name="QForm" enctype="multipart/form-data">																				
            <table class="adminform"  width="400px" align="center">
                <tr>
                    <th colspan="2" align="center">
                            IDENTIFICACIÓN DEL USUARIO
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
                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Cédula RIF','Ingrese la Cédula ó RIF.   Ej.: Cedula:V-123456 ó RIF:J-12345678-0', ' (Campo Requerido)')">														
                                    </td>
                                </tr>

                            </tbody>
                        </table> 
                    </td>
                </tr>
                <tr>
                    <td class="botones" colspan="2" align="center" >											
                        <input class="button"  type="submit" name="submit" value="Continuar" />	
                    </td>			
                </tr>
                
            </table> 												
            <br>
        </form>
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

