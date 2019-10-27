<?php
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }

    require("../conexion/aut_config.inc.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
	
	
	
    if (isset($_POST[save])){
        if ($_POST['personvalue1']!="") {
            if (is_array($_POST['personvalue1'])) {
                while (list($key, $value) = each($_POST['personvalue1'])) {
                    $cedula_rif=substr_replace($value,'-',1,0);
                    $cedula_rif_cliente=$value;
                }
            }

            $comunidad='<script> document.QForm.getElementById("codcom").value; </script>';
            $comunidad;
            $query="SELECT * FROM solicitantes,tipo_solicitantes WHERE solicitantes.cedula_rif='$cedula_rif_cliente' AND solicitantes.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante order by cedula_rif";				
            $result = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
            $resultado_cliente=  pg_fetch_array($result);
            echo "<script type=\"text/javascript\">
                    opener.document.QForm.ci_ubch.value='$cedula_rif';
                    opener.document.QForm.responsable_ubch.value='$resultado_cliente[nombre_solicitante]' + ' ' + '$resultado_cliente[alapsolicitante]';
                    close();
                  </script>";
            
        }else{
            $error="Error";
            $div_menssage='<div align="left"><h3 class="error"><font color="#CC0000" style="text-decoration:blink;">Error: Debe Seleccionar el Solicitante; por favor verifique los datos!</font></h3></div>';				
        }
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<html>
    <head>
        <title>Mensaje</title>
        <meta charset="UTF-8">
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
        <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8"/>
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Language" content="es-VE">
        <meta http-equiv="Content-Script-Type" content="text/javascript">
        <link rel="shortcut icon" href="../images/favicon.ico" />

        <link rel="stylesheet" href="../css/template_portada.css" type="text/css" />
        
        <link rel="stylesheet" type="text/css" href="../../css/styles_general.css" media="screen" />
        <link rel="stylesheet" href="../css/template.css" type="text/css" />

    <!-- script del jquery, ajax y funciones javascript-->
       <script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script> 
       <script language="javascript" src="../js/ajax.js"></script>
       <script type="text/javascript" src="../js/lib_javascript.js"></script>
       <script type="text/javascript" language="JavaScript1.2" src="../shared/js/funciones.js"></script>    	

    <!-- script de efectos -->	
        <script src="../js/prototype.js" type="text/javascript"></script>
        <script src="../js/scriptaculous.js" type="text/javascript"></script>
        <script src="../js/unittest.js" type="text/javascript"></script> 
        
        <!-- Token Input -->
      <script type="text/javascript" src="../js/tokeninput/src/jquery.tokeninput.min.js"></script>
      <link rel="stylesheet" href="../js/tokeninput/styles/token-input.css" type="text/css" />
      <link rel="stylesheet" href="../js/tokeninput/styles/token-input-facebook.css" type="text/css" />
        
        <script type="text/javascript">
           jQuery(document).ready(function(){
               jQuery("#personvalue").tokenInput("../solicitante_search.php", {
                    hintText:"Insertar desde la lista de Solicitantes",
                    noResultsText:"No se Encontraron Resultados",
                    searchingText: "Buscando...",
                    preventDuplicates: false,
                    tokenLimit: 1,
                    method: "POST",
                    propertyToSearch: "cedula_rif",
                    tokenValue: "nombre_solicitante",
                    theme: "facebook",
                    tokenFormatter: function(item) { return "<li><p>" + item[this.propertyToSearch]+ " - " + item[this.tokenValue]+ "</p></li>" },
                    resultsFormatter: function(item){ return "<li>" + item[this.propertyToSearch]+ " - " + item[this.tokenValue]+ "</li>" },
                });

            });
       </script>

       <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery("#submit").click(function() {
                        var abc = jQuery('#personvalue').tokenInput("get");
                        jQuery.each( abc, function( key, value ) {
    //                      alert( key + ": " + value.celular );
                          jQuery('#personvalue1').append('<option value="'+value.cedula_rif+'" selected="selected">'+value.cedula_rif+'</option>');
    //                      jQuery('#personvalue1').val(value.celular);
                        });
                    });
                });

        </script>
      
        <script language="JavaScript">
            function aceptar(cedula) {
                opener.document.QForm.codcom.value=cedula;
                close();
            }
        </script>

    </head>
<body style="background-color: #f9f9f9;">
<?php if($div_menssage) { ?>					
    <script type="text/javascript">
            function ver_msg(){
                    Effect.Fade('msg');
            }  
            setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
    </script>
 <?php } ?>
    <table class="adminclientes" width="100%">
        <tr>
            <th>
                RESPONSABLE UBCH
            </th>
        </tr>
    </table>
    <!-- Codigo para mostrar la ayuda al usuario  -->
    <div style="top: 477px; left: 966px; display: none;" id="mensajesAyuda">
            <div id="ayudaTitulo">Código de Seguridad (Obligatorio)</div>
            <div id="ayudaTexto">Ingresa el código de seguridad que muestra la imagen</div>
    </div>
    
    <table class="container_contenido_cat" border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>  			
            <tr>
                <td>
                    <form method="POST" action="resp_ubch_load.php" id="QForm" name="QForm" enctype="multipart/form-data">
                    <table class="adminform_cat" width="100%"  align="center">
                        <tbody>
                            <tr>
                                <th align="center">
                                    BUSQUEDA DEL RESPONSABLE UBCH
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <?php if($div_menssage) { ?>
                                            <tr>
                                                <td colspan="3" id="msg" align="center">		
                                                    <?php echo $div_menssage;?>
                                                </td>
                                            </tr>
                                            <?php } ?>

                                            <tr>
                                                <td width="16%">
                                                    <b>RESPONSABLE UBCH:</b>
                                                </td>		
                                                <td>
                                                    <div id="contenedor_condicion_buscar">
                                                        <!--<textarea id="personvalue"  name="personvalue" /></textarea>-->
                                                        <input autofocus="true"  name="personvalue"  type="text" id="personvalue"  />
                                                        <select hidden="true"  id="personvalue1"  name="personvalue1[]" multiple="multiple"  ></select>
                                                    </div>											 
                                                </td>                       
                                            </tr>
                                            
                                        </tbody>
                                    </table>	
                                </td>
                            </tr>
                            <tr>
                                <td  class="botones" align="center" >
                                    <input id="submit"  class="button" type="submit" name="save" value="Enviar" />									
                                    <input class="button"  type="button" onclick="javascript:parent.close();" value="Cerrar" name="cerrar" /> 
                                </td>		
                            </tr>	
                        </tbody>
                    </table>
                    </form>    
                </td>	 				 			  
            </tr>
        </tbody>
    </table>
</body>
</html>
    
    
        