<?php  // el NOMBRE y ruta de esta misma pagina.
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$type;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    $iva=$var_iva;
    $consulta=pg_query("insert into facturatmp (fecha_factura,hora_factura) values ('now()','now()') RETURNING n_factura") or die('La consulta fall&oacute;: ' . pg_last_error());
    $result_consulta=pg_fetch_row($consulta);
    $codfacturatmp = $result_consulta[0];

?>

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

        <table class="adminfactura">
            <tr>
                <th class="adminfactura">
                    FACTURACION
                </th>
            </tr>
        </table>
        <!-- formulario para buscar al cliente registrado -->			
        <form id="facturacion" name="facturacion" method="POST" action="index2.php?view=factura_save" onSubmit="return validar_form_enviado();" enctype="multipart/form-data">  				
            <input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp?>" type="hidden">
            <input id="iva" name="iva" value="<?php echo $iva?>" type="hidden">
            
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        Datos del Cliente
                    </th>
                </tr>
			
                <?php if ((isset($_POST[save])) and ($error=="bien")) { ?> 
                
                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Datos registrados con &eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=factura";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=factura" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>
			
                <?php }else{ ?> 
                	
                <tr>
                    <td colspan="2" height="16" align="left">
                        <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>
                
                <tr>
                    <td width="15%" align="right">
                        CEDULA SOLICITANTE: <font color="Red">(*)</font>
                    </td>
                    <td width="85%">														
                        <input id="cedula_solicitante" name="cedula_solicitante" class="validate[required,minSize[5]] text-input" type="text"  value=""  size="10" maxlength="12" onblur="cargarContenidoPersona();"/> 
                        <img src="images/ver.png" width="16" height="16" onClick="cargarContenidoPersona()" onMouseOver="style.cursor=cursor">
                    </td>
		</tr>
                
		<tr>
                    <td colspan="2">
                        <div id="ContenedorPersonas"> 
                            <table class="adminform"  border="0" width="80%">
                                <tbody>
                                    <tr>
                                        <td width="20%">
                                            NOMBRE SOLICITANTE:
                                        </td>

                                        <td>
                                            <input type="hidden" id="nombre_solicitante" name="nombre_solicitante" value="" />
                                            <input readonly="true" type="text" id="nombre_apellido" name="nombre_apellido" maxlength="50" size="50" />																	
                                        
                                            TIPO SOLICITANTE:
                                        
                                            <input readonly="true" type="text" id="tipo_solicitante" name="tipo_solicitante"  maxlength="50" size="50" />																	
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="22%">
                                            DIRECCION:
                                        </td>

                                        <td>
                                            <input readonly="true" type="text" id="carrera" name="carrera" maxlength="90" size="90" />																	
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
		</tr>
            </table>
        </form>
        
        <br/>
            
        <form id="formulario_lineas" name="formulario_lineas" method="post" action="facturacion/factura/frame_lineas.php" target="frame_lineas">
            <input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp ?>" type="hidden">
            <input id="iva" name="iva" value="<?php echo $iva?>" type="hidden">
            <input type="hidden" id="cedula_personal" name="cedula_personal" value="<?php echo $resultados[cedula_personal]?>">	
            
            <table class="adminform" border="0" width="100%">
                <tr>
                    <th class="rowformleft" colSpan="2" width="100%"  height="16">
                        <span>CONCEPTOS</span> 				
                    </th>
                </tr>
                
                <tr id="periodo">
                    <td width="90%">
                        <div id="ContenedorConcepto">
                            CONCEPTO: <font color="Red">(*)</font>
                            <input id="codigo_concepto" name="codigo_concepto" class="validate[required,custom[integer],minSize[5]] text-input" type="text"  value=""  size="8" maxlength="25" onblur="cargarContenedorConcepto();"/> 
                            <img src="images/ver.png" width="16" height="16" onClick="ventanaconceptos()" onMouseOver="style.cursor=cursor" id="buscar_concepto" name="buscar_concepto">
                            &nbsp;&nbsp;&nbsp;
                            
                            DESCRIPCION:
                            <input type="text" id="descripcion_concepto" name="descripcion_concepto" readonly="true"  size="50"/>       		
                            &nbsp;&nbsp;&nbsp;
                            
                            PRECIO:
                            <input type="text" id="costo_unitario" style="text-align:right" name="costo_unitario" readonly="true" size="12">	
                            &nbsp;&nbsp;&nbsp;
                            
                            CANTIDAD:
                            <input type="text" id="cantidad" style="text-align:right" name="cantidad" size="6" value="1" onfocus="actualizar_importe()" onkeyup="actualizar_importe()">	
                            &nbsp;&nbsp;&nbsp;
                            
                            MONTO:
                            <input type="text" style="text-align:right" id="total" name="total" size="12" readonly="true">
                            &nbsp;&nbsp;&nbsp;
                            
                            <img src="images/botonagregar.jpg" width="72" height="22"  border="1" onClick="validar()" onMouseOver="style.cursor=cursor" title="Agregar articulo">
                        </div>
                    </td>
                </tr>			

                <tr>
                    <td colspan="2">
                        <div id="frmBusqueda">
                            <table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 id="Table1">
                                <tr class="cabeceraTabla">
                                    <td class="aCentro" width="5%"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ITEM</td>
                                    <td align="center" width="5%">&nbsp;REFERENCIA</td>
                                    <td align="center" width="35%">DESCRIPCION</td>
                                    <td align="center" width="6%">&nbsp;&nbsp;&nbsp;CANTIDAD</td>
                                    <td align="center" width="6%">&nbsp;&nbsp;&nbsp;PRECIO</td>
                                    <td align="center" width="6%">MONTO</td>
                                    <td align="center" width="3%">&nbsp;</td>
                                </tr>
                            </table>

                            <div id="lineaResultado">
                                <iframe width="100%" height="160" id="frame_lineas" name="frame_lineas" frameborder="0">
                                    <ilayer width="100%" height="160" id="frame_lineas" name="frame_lineas"></ilayer>
                                </iframe>
                            </div>
                        </div>
                    </td>	
                </tr>

                <tr>
                    <td  colspan="2"   height="16">
                        <hr size="1" width="100%" color="black" noshade>				
                    </td>
                </tr> 

                <tr>
                    <td colspan="4" >
                        <table width="100%" border=0 cellpadding=0 cellspacing=0 class="fuente8">
                            <tr>
                                <td width="75%" >
                                    <div id="cpanel">
                                        <div style="float:left;">
                                            <div class="icon">
                                                <a href="#">
                                                    <img src="images/factura.png"  onClick="validar_cabecera()" border="0" onMouseOver="style.cursor=cursor">
                                                    <span>Facturar</span>
                                                </a>
                                            </div>
                                        </div>								
                                    </div>

                                    <div id="cpanel">
                                        <div style="float:left;">
                                            <div class="icon">
                                                <a href="index2.php?view=factura">
                                                    <img src="images/cpanel.png" alt="salir" align="middle"  border="0" />
                                                    <span>Salir</span>
                                                </a>
                                            </div>
                                        </div>								
                                    </div>
                                </td>

                                <td width="25%">			  	
                                    <div id="frmBusqueda">
                                        <table width="100%" border=0 align="left" cellpadding=3 cellspacing=0 class="fuente8">
                                            <tr>
                                                <td width="25%" class="busqueda">Sub-Total</td>
                                                <td width="75%" align="right">
                                                    <div align="left">
                                                    <input class="cajaTotales" name="subtotal" type="text" id="subtotal" size="10" value=0.00 align="right" readonly> 
                                                    Bs.
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="busqueda">IVA:(<?php echo $var_iva ?>&#37;)</td>
                                                <td align="right">
                                                    <div align="left">
                                                    <input class="cajaTotales" name="totalimpuestos" type="text" id="totalimpuestos" size="10" align="right" value=0.00 readonly> 
                                                    Bs.    
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="busqueda">Precio Total:</td>
                                                <td align="right">
                                                    <div align="left">
                                                    <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="10" align="right" value=0.00 readonly> 
                                                    Bs.
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
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
        $('#cedula_solicitante').mask('~-9999?99999',{placeholder:" "});
        //$('#phoneext').mask("(999) 999-9999? x99999");
        //$("#tin").mask("99-9999999");
        //$("#ssn").mask("999-99-9999");
        //$("#product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("Ha escrito lo siguiente: "+this.val());}});
        //$("#eyescript").mask("~9.99 ~9.99 999");
    });	
</script>