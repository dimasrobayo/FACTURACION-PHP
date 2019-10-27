<?php
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?>
<!-- Ventanas emergentes -->
<script type="text/javascript" charset="utf-8">			      
    jQuery(document).ready(function(){
        /* normal effects*/ 
        jQuery('.fancybox-normal').fancybox();

        /* Con effects*/ 		
        jQuery(".fancybox").fancybox({
            maxWidth	: 550,
            maxHeight	: 400,
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
            maxHeight	: 300,
            fitToView	: false,
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none',
            beforeShow: function(){
             // get the value of pageload INSIDE the iframe
             pageload = jQuery('#cedula').val();
            },
            afterClose: function() {
//                jQuery('iframe#fancybox-foto').contents().find('input#cedula_rif').val
                jQuery('#cedula_rif').val=jQuery('#cedula').val();
            },
//			padding : 0, 
			type: 'iframe',      		
            helpers : {
                title : null            
            }        		
        });			
    });   
	                    
</script> 
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
<table class="adminproyecto" width="100%">
    <tr>
        <th>
            NUEVA SOLICITUD DE PROYECTO
        </th>
    </tr>
</table>

<div align="center" class="centermain">
    <div class="main"> 
        
        <form method="POST" action="?view=proyecto_add" id="QForm" name="QForm" enctype="multipart/form-data">																				
            <table class="adminform"  width="400px" align="center">
                <tr>
                    <th colspan="2" align="center">
                            IDENTIFICACIÓN DEL SOLICITANTE
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
                                        <input id="cedula_rif" autofocus="true" name="cedula_rif"  class="validate[required,minSize[6]] text-input" type="text"  value="<?php if($total_result==0) echo $cedula_rif;?>"  size="10" maxlength="12"/>
                                        <a href="javascript: ue_buscarsolicitante();"><img src="images/busqueda.png" alt="Buscar" title="Buscar Solicitante" width="15" height="15" border="0"></a>
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
</script>

