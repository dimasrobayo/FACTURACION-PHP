<?php 
// chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "") {
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];    
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_POST[cargar])) {
        $codigo_nota=$_POST['codigo_nota'];
        $codigo_concepto=$_POST['codigo_concepto'];
        $cantidad=$_POST['cantidad'];
        $monto_concepto=$_POST['total'];
        $iva_facturado=$_POST['iva_producto'];

        $cedula_solicitante=strtoupper($_POST['cedula_solicitante']);
        $cedula_rif_fac = preg_replace("/\s+/", "", $cedula_solicitante);
        $cedula_rif_fac = str_replace("-", "", $cedula_rif_fac);

        //actualizacion del stock del inventario.
        $status_stock=$_POST['status_stock'];
        $stock=$_POST['stock'];

        $stock_update=($stock-($cantidad));

        if(($status_stock==1)||($stock_update>0)){
            $query_concepto="UPDATE concepto_factura SET codigo_concepto='$codigo_concepto',stock='$stock_update' WHERE codigo_concepto='$codigo_concepto'";
            $result_concepto=pg_query($query_concepto) or die('La consulta fall&oacute;:' . pg_last_error());
            pg_free_result($result_concepto);
        
            //cargando del detalle de la nota.
            $query="insert into detalle_nota(codigo_nota,codigo_concepto,cantidad,monto_concepto,iva_facturado) values ('$codigo_nota','$codigo_concepto','$cantidad','$monto_concepto','$iva_facturado')";
            $result_insert_factura=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());  
            $result_insert = pg_fetch_row($result_insert_factura);
            $codfactura = $result_insert[0];
            $error="bien";

            

            $solicitante="SELECT * from solicitantes, tipo_solicitantes, nota_entrega, usuarios where solicitantes.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante and solicitantes.cedula_rif=nota_entrega.cedula_rif and usuarios.cedula_usuario=nota_entrega.cedula_usuario and nota_entrega.codigo_nota=$codigo_nota order by nota_entrega.codigo_nota";
            $result_solicitante = pg_query($solicitante)or die(pg_last_error());
            $resultado_solicitante=pg_fetch_array($result_solicitante); 
            pg_free_result($result_solicitante);

            $detalle_consolta = pg_query("SELECT * from nota_entrega, detalle_nota, concepto_factura, marca_concepto where nota_entrega.codigo_nota=detalle_nota.codigo_nota and concepto_factura.codigo_concepto=detalle_nota.codigo_concepto and marca_concepto.codigo_marca = concepto_factura.codigo_marca and detalle_nota.codigo_nota=$codigo_nota order by detalle_nota.codigo_nota")or die('La consulta fall&oacute;: ' . pg_last_error());
            pg_close();
        }
    }

    if (isset($_GET['codigo_nota'])){
        $codigo_nota= $_GET['codigo_nota'];

        $solicitante="SELECT * from solicitantes, tipo_solicitantes, nota_entrega, usuarios where solicitantes.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante and solicitantes.cedula_rif=nota_entrega.cedula_rif and usuarios.cedula_usuario=nota_entrega.cedula_usuario and nota_entrega.codigo_nota=$codigo_nota order by nota_entrega.codigo_nota";
        $result_solicitante = pg_query($solicitante)or die(pg_last_error());
        $resultado_solicitante=pg_fetch_array($result_solicitante); 
        pg_free_result($result_solicitante);

        $detalle_consolta = pg_query("SELECT * from nota_entrega, detalle_nota, concepto_factura, marca_concepto where nota_entrega.codigo_nota=detalle_nota.codigo_nota and concepto_factura.codigo_concepto=detalle_nota.codigo_concepto and marca_concepto.codigo_marca = concepto_factura.codigo_marca and detalle_nota.codigo_nota=$codigo_nota order by detalle_nota.codigo_nota")or die('La consulta fall&oacute;: ' . pg_last_error());
        pg_close();
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

    <table class="adminnota">
        <tr>
            <th>
                NOTA DE ENTREGA:
            </th>
        </tr>
    </table>

    <table class="adminform" border="0" width="100%">
        <form id="facturacion" name="facturacion" method="POST" action="index2.php?view=nota_entrega_update" onSubmit="return validar_form_enviado();" enctype="multipart/form-data">
        <input id="cedula_usuario" name="cedula_usuario" value="<?php echo $_SESSION['id']?>" type="hidden">
        <tr bgcolor="#55baf3">
            <th colspan="2">
                GENERAR NOTAS DE ENTREGA: <font color="red"><?php echo $resultado_solicitante[codigo_nota]; ?></font>
            </th>
        </tr>

        <?php if ((isset($_POST[save])) and ($error=="bien")){  ?> <!-- Mostrar Mensaje -->

        <tr>
            <td colspan="2" align="center">
                <div align="center"> 
                    <h3 class="info">   
                        <font size="2">                     
                            Datos Modificados con &eacute;xito 
                            <br />
                            <script type="text/javascript">
                                function redireccionar(){
                                    window.location="?view=nota_entrega";
                                }  
                                setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                            </script>                       
                            [<a href="?view=nota_entrega" name="Continuar"> Continuar </a>]
                        </font>                         
                    </h3>
                </div> 
            </td>
        </tr>

        <?php   }else{  ?>   <!-- Mostrar formulario Original --> 

        <tr>
            <td colspan="2" height="16" align="left">
                <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
            </td>
        </tr>

        <tr>
            <td class="titulo" colspan="2" height="18"  align="left"><b>Datos de la Unidad:</b></td>
        </tr>
        
        <tr>
            <td colspan="2">
                <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                <input id="codigo_nota" name="codigo_nota" value="<?php echo $resultado_solicitante[codigo_nota]; ?>" type="hidden"/>
                <tr>
                    <td>
                        <table>
                            <tbody>
                                <tr>
                                    <div id="ContenedorPersonaAdd" align="left">

                                    </div>
                                </tr>

                                <tr>
                                    <td width="10%">
                                        C.I/RIF: <font color="Red">(*)</font>
                                        <input id="cedula_solicitante" name="cedula_solicitante" autofocus="true" type="text" value="<?php echo $resultado_solicitante[cedula_rif]; ?>" size="10" maxlength="12" readonly/>
                                                
                                        <a href="javascript: ue_buscarcliente();">
                                            <img src="images/busqueda.png" alt="Buscar" title="Buscar Colaborador" width="15" height="15" border="0">
                                        </a>
                                        
                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Cédula RIF','Ingrese la Cédula ó RIF.   Ej.: Cedula:V-123456 ó RIF:J-12345678-0', ' (Campo Requerido)')">
                                    </td>

                                    <td>
                                        RESPONSABLES: <font color="Red">(*)</font>
                                        <input class="validate[required]" name="responsable" id="responsable" size="60" align="center" value="<?php echo $resultado_solicitante[responsable]; ?>" readonly/>
                                    </td>

                                    <td>
                                        CHOFER: <font color="Red">(*)</font>
                                        <input class="validate[required]" name="chofer" id="chofer" size="60" align="center" value="<?php echo $resultado_solicitante[chofer]; ?>" readonly/>
                                    </td>

                                    <td>
                                        N° DE PLACA: <font color="Red">(*)</font>
                                        <input class="validate[required]" name="placa" id="placa" size="12" align="center"  value="<?php echo $resultado_solicitante[placa]; ?>" readonly/>
                                    </td>

                                    <td>
                                        FECHA DE LA NOTA: <font color="Red">(*)</font>
                                        <input name="fecha_nota" id="fecha_nota" size="12" align="center" value="<?php echo date("d/m/Y"); ?>" readonly />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                        
                <tr>
                    <td colspan="2">
                        <div id="ContenedorPersonas"> 
                            <table class="adminform"  border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td width="15%">
                                            NOMBRE DEL CLIENTE:
                                        </td>

                                        <td width="85%">
                                            <input readonly="true" type="text" id="nombre_apellido" name="nombre_apellido" maxlength="50" size="50" value="<?php echo $resultado_solicitante[nombre_solicitante]; ?>" readonly/>

                                            TIPO CLIENTE:
                                            <input readonly="true" type="text" id="tipo_solicitante" name="tipo_solicitante"  maxlength="50" size="50" value="<?php echo $resultado_solicitante[descripcion_tipo_solicitante]; ?>" readonly/>                     
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            DIRECCION:
                                        </td>

                                        <td>
                                            <input readonly="true" type="text" id="direccion" name="direccion" maxlength="90" size="90" value="<?php echo $resultado_solicitante[direccion_habitacion]; ?>" readonly/>                                                                 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                </tbody>
                </table>    
            </td>
        </tr>
        </form> 
    </table>

    <table class="adminform" border="0" width="100%">
        <form id="formulario_lineas" name="formulario_lineas" method="post" action="<?php echo $pagina?>">
        <input type="hidden" id="cedula_personal_carga" name="cedula_personal_carga" value="<?php echo $resultado_solicitante[cedula_rif]; ?>" size="15"/>
        <input id="status_stock" name="status_stock" value="" type="hidden">
        <input id="stock" name="stock" value="" type="hidden">
        <input id="codigo_nota" name="codigo_nota" value="<?php echo $resultado_solicitante[codigo_nota]; ?>" type="hidden"/>
        <input id="n_combo" name="n_combo" value="<?php echo $resultado_solicitante[n_combo]; ?>" type="hidden"/>

        <tr bgcolor="#55baf3">
            <th colspan="9">
                DETALLES
            </th>
        </tr>

        <tr>
            <td>
                <center>CODIGO:</center>
                <input type="text" placeholder="Código Concepto" id="codigo_concepto" name="codigo_concepto" maxlength="15" size="15" onblur="cargarContenedorConcepto()" onkeyup="cargarContenedorConcepto()"/>
                    <a href="javascript: ventanaconceptos_detalles();">
                        <img src="images/busqueda.png" alt="Buscar" title="Buscar Concepto" width="15" height="15" border="0">
                    </a>
            </td>

            <td>
                <center>DESCRIPCION:</center>
                <input type="text" id="descripcion_concepto" name="descripcion_concepto" readonly="true"  size="94"/>
            </td>

            <td>
                <center>CANTIDAD:</center>
                <input type="text" id="cantidad" style="text-align:center" name="cantidad" size="10" value="1" onfocus="actualizar_importe()" onkeyup="actualizar_importe()"> 
            </td>

            <td>
                <center>C / U:</center>
                <input type="text" id="costo_unitario" placeholder="0,00" style="text-align:right" name="costo_unitario" readonly="true" size="25">
            </td>

            <td>
                <center>IVA:</center>
                <input type="text" style="text-align:right" placeholder="0,00" id="iva_producto" name="iva_producto" readonly="true" size="25">
            </td>

            <td>
                <center>TOTAL PRODUCTO:</center>
                <input type="text" style="text-align:right" placeholder="0,00" id="total" name="total" size="25" readonly="true">
            </td>
            
            <td width="10%">
                <input type="submit" class="button" name="cargar" value="  CARGAR  " >
            </td>
        </tr>           

        <tr>
            <td align="center" width="10%" class="botones"  >
                <strong>CODIGO</strong>
            </td>

            <td align="center" width="32%" class="botones"  >
                <strong>PRODUCTO</strong>
            </td>

            <td align="center" width="5%" class="botones"  >
                <strong>CANTIDAD</strong>
            </td>

            <td align="center" width="10%" class="botones"  >
                <strong>C / U</strong>
            </td>

            <td align="center" width="10%" class="botones"  >
                <strong>IVA</strong>
            </td>

            <td align="center" width="10%" class="botones"  >
                <strong>TOTAL PRODUCTO</strong>
            </td>

            <td align="center" width="4%" class="botones"  >
                <strong>ACCION</strong>
            </td>
        </tr>
            
        <?php while($resultados_detalles = pg_fetch_array($detalle_consolta)) { ?>

        <tr class="row0">
            <td  align="center">
                <?php echo $resultados_detalles[codigo_concepto] ?>
            </td>
            
            <td align="left">
                <?php echo $resultados_detalles[nombre_concepto]; echo " - "; echo $resultados_detalles[nombre_marca];?> 
            </td>
            
            <td align="center">
                <?php echo $resultados_detalles[cantidad] ?>
            </td>
            
            <td align="right">
                <?php echo $resultados_detalles[costo_unitario] ?>
            </td>
            
            <td align="right">
                <?php echo $resultados_detalles[iva_producto] ?>
            </td>
            
            <td align="right">
                <?php echo $resultados_detalles[monto_concepto] ?>
            </td>
            
            <td align="center"> 
                <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=detalle_nota_drop&codigo_nota=<?php echo $resultados_detalles[codigo_nota];?>&codigo_concepto=<?php echo $resultados_detalles[codigo_concepto];?>&cantidad=<?php echo $resultados_detalles[cantidad];?>&n_combo=<?php echo $resultados_detalles[n_combo];?>&stock=<?php echo $resultados_detalles[stock];?>&status_stock=<?php echo $resultados_detalles[status_stock];?>" title="Pulse para eliminar el registro">
                    <img border="0" src="images/borrar.png" alt="borrar">
                </a>
            </td>
        </tr> 
        
        <?php } ?>

        <tr>
            <td colspan="9" >
                <table  width="100%" border=1 cellpadding=0 cellspacing=0 class="adminform">
                    <tr>
                        <td width="100%">
                            <div id="cpanel">
                                <div style="float:left;">
                                    <div class="icon">
                                        <a href="reportes/imprimir_nota.php?codigo_nota=<?php echo $resultado_solicitante[codigo_nota]; ?>" target="_blank">
                                            <img src="images/factura.png" alt="Imprimir" align="middle"  border="0" />
                                            <span>Nota de Entrega</span>
                                        </a>
                                    </div>
                                </div>                              
                            </div>

                            <div id="cpanel">
                                <div style="float:left;">
                                    <div class="icon">
                                        <a href="index2.php?view=nota_entrega">
                                            <img src="images/cpanel.png" alt="salir" align="middle"  border="0" />
                                            <span>Salir</span>
                                        </a>
                                    </div>
                                </div>                              
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php }  ?> 
        </form>
    </table>
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
    
    function ue_forma_pago()    {
       var mensaje="";
        var codfacturatmp=document.facturacion.codfacturatmp.value;
        var total_factura=document.getElementById("preciototal").value;
        var status_modif_fp=document.facturacion.status_modif_fp.value;
        if (document.getElementById("preciototal").value==0.00) mensaje+="  - Debe Registrar un Concepto\n";
        if (mensaje!="") {
            alert("Atencion, se han detectado las siguientes Errores:\n\n"+mensaje);
        } else {
            window.open("facturacion/factura/forma_pago.php?codfacturatmp="+codfacturatmp+"&status_modif_fp="+status_modif_fp+"&totalfactura="+total_factura,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=420,height=430,left=50,top=50,location=no,resizable=no");
        }
    }
    
    function ue_buscarcliente() {
        document.facturacion.cedula_solicitante.value="";                                           
        document.facturacion.nombre_solicitante.value="";                                           
        document.facturacion.tipo_solicitante.value="";                                         
        document.facturacion.direccion.value="";                                            
                window.open("facturacion/factura/cliente_load.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=310,left=50,top=50,location=no,resizable=no");
    } 
    
    function ue_cliente_add()   {
           var mensaje="";
            var cedula_rif=document.facturacion.cedula_solicitante.value;
            var cedula_rif=cedula_rif.toUpperCase();
            window.open("facturacion/factura/cliente_add.php?cedula_rif="+cedula_rif,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=350,left=50,top=50,location=no,resizable=no");
    } 
</script>

<?php
if ($total_result_ticket!=0) {
    echo "<script type=\"text/javascript\">
        
        validarCargarProductoFactura();  
        
      </script>";
}
?>