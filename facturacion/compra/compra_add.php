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
?> 

<?php     
    if (isset($_GET['cedula_rif'])){ // Recibir los Datos 
        $cedula_rif= $_GET['cedula_rif'];
    }
?> 

<?php 
    if (isset($_POST[save])){   // Insertar Datos del formulario
        $cedula_usuario=$_POST['cedula_usuario'];
        $n_factura=$_POST['n_factura']; 

        $error="bien";

        //verifica la recepcion de los datos para buscar y mostrar
        if (isset($_POST['cedula_solicitante'])){
            $cedula_rif=strtoupper($_POST['cedula_solicitante']);
        }else {
            $cedula_rif=strtoupper($_GET['cedula_solicitante']);
        } 

        if ($cedula_rif){  // consulta de los datos para Mostrar
            $cedula_rif_buscar = preg_replace("/\s+/", "", $cedula_rif);
            $cedula_rif_buscar = str_replace("-", "", $cedula_rif_buscar);

            $query = "INSERT INTO compra (cedula_rif, n_factura, cedula_usuario) values ('$cedula_rif_buscar', '$n_factura', '$cedula_usuario')";  
            $result = pg_query($query)or die(pg_last_error());
            $result_insert=pg_fetch_array($result);
            pg_free_result($result);
        }                                   
    }
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

        <table class="admincompra">
            <tr>
                <th>
                    COMPRAS A PROVEEDOR
                </th>
            </tr>
        </table>
        <!-- formulario para buscar al cliente registrado -->           
        <form id="facturacion" name="facturacion" method="POST" action="index2.php?view=compra_add" onSubmit="return validar_form_enviado();" enctype="multipart/form-data">                  
            <input id="cedula_usuario" name="cedula_usuario" value="<?php echo $_SESSION['id']?>" type="hidden">

            
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        DATOS DEL PROVEEDOR
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
                                            window.location="?view=compra";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script>                       
                                    [<a href="?view=compra" name="Continuar"> Continuar </a>]
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
                    <td>
                        <table>
                            <tbody>
                                <tr>
                                    <div id="ContenedorPersonaAdd" align="left">

                                    </div>
                                </tr>

                                <tr>
                                    <td>
                                        C.I/RIF: <font color="Red">(*)</font>
                                        <input id="cedula_solicitante" name="cedula_solicitante" autofocus="true" type="text"  value=""  size="10" maxlength="12" onblur="cargarContenidoPersona();" onkeyup="cargarContenidoPersona();"/>
                                                
                                        <a href="javascript: ue_buscarcliente();">
                                            <img src="images/busqueda.png" alt="Buscar" title="Buscar Colaborador" width="15" height="15" border="0">
                                        </a>
                                        
                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Cédula RIF','Ingrese la Cédula ó RIF.   Ej.: Cedula:V-123456 ó RIF:J-12345678-0', ' (Campo Requerido)')">
                                    </td>

                                    <td>
                                        N° FACTURA: <font color="Red">(*)</font>
                                        <input class="validate[required,custom[number]]" name="n_factura" id="n_factura" size="15" align="center" />
                                    </td>

                                    <td>
                                        FECHA DE LA COMPRA: <font color="Red">(*)</font>
                                        <input name="fecha_compra" id="fecha_compra" size="12" align="center" value="<?php echo date("d/m/Y"); ?>" readonly />
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
                                            NOMBRE DEL PROVEEDOR:
                                        </td>

                                        <td width="85%">
                                            <input type="hidden" id="nombre_solicitante" name="nombre_solicitante" value="<?php if ($total_result_ticket!=0) echo $resultados_ticket[nombre_solicitante]; ?>" />
                                            <input readonly="true" type="text" id="nombre_apellido" name="nombre_apellido" maxlength="50" size="50" value="<?php if ($total_result_ticket!=0) echo $resultados_ticket[nombre_solicitante]; ?>" />

                                            TIPO PROVEEDOR:
                                            <input readonly="true" type="text" id="tipo_solicitante" name="tipo_solicitante"  maxlength="50" size="50" value="<?php if ($total_result_ticket!=0) echo $resultados_ticket[descripcion_tipo_solicitante]; ?>" />                     
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            DIRECCION:
                                        </td>

                                        <td>
                                            <input readonly="true" type="text" id="direccion" name="direccion" maxlength="90" size="90" value="<?php if ($total_result_ticket!=0) echo $resultados_ticket[direccion_habitacion]; ?>" />                                                                 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="botones" align="center" >            
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=compra'" value="Cerrar" name="cerrar" />  
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
