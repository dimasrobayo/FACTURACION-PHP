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
 
    if (isset($_GET['codigo_nota'])){
        $codigo_nota= $_GET['codigo_nota'];

    $query="SELECT * from solicitantes, tipo_solicitantes, nota_entrega, usuarios where solicitantes.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante and solicitantes.cedula_rif=nota_entrega.cedula_rif and usuarios.cedula_usuario=nota_entrega.cedula_usuario and nota_entrega.codigo_nota=$codigo_nota order by codigo_nota";
        $result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result); 
        pg_free_result($result);
    }

    if (isset($_POST[save])) {
        $codigo_nota=$_POST['codigo_nota'];
        $cedula_usuario=$_POST['cedula_usuario'];
        $n_combo=$_POST['n_combo'];
        $responsable=strtoupper($_POST["responsable"]);
        $chofer=strtoupper($_POST['chofer']);
        $placa=$_POST['placa']; 
        $fecha_nota=$_POST['fecha_nota'];
        $cedula_rif=strtoupper($_POST['cedula_solicitante']);
        $cedula_rif_buscar = preg_replace("/\s+/", "", $cedula_rif);
        $cedula_rif_buscar = str_replace("-", "", $cedula_rif_buscar);

        //se le hace el llamado a la funcion de editar. 
        $query="SELECT update_nota_entrega($codigo_nota, '$cedula_rif_buscar', $cedula_usuario, '$responsable','$chofer','$placa','$fecha_nota')";
        $result = pg_query($query)or die(pg_last_error());
        $error="bien";     
    }//fin del procedimiento modificar.
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
        
        <form id="facturacion" name="facturacion" method="POST" action="index2.php?view=nota_entrega_update" onSubmit="return validar_form_enviado();" enctype="multipart/form-data">
            <input id="cedula_usuario" name="cedula_usuario" value="<?php echo $_SESSION['id']?>" type="hidden">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        <img src="images/edit.png" width="16" height="16" alt="Editar Registro">
                        MODIFICAR DATOS DE LA NOTA DE ENTREGA
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
                        <input id="codigo_nota" name="codigo_nota" value="<?php echo $resultado[codigo_nota]; ?>" type="hidden"/>
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
                                                <input id="cedula_solicitante" name="cedula_solicitante" autofocus="true" type="text" value="<?php echo $resultado[cedula_rif]; ?>" size="10" maxlength="12" onblur="cargarContenidoPersona();" onkeyup="cargarContenidoPersona();"/>
                                                        
                                                <a href="javascript: ue_buscarcliente();">
                                                    <img src="images/busqueda.png" alt="Buscar" title="Buscar Colaborador" width="15" height="15" border="0">
                                                </a>
                                                
                                                <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Cédula RIF','Ingrese la Cédula ó RIF.   Ej.: Cedula:V-123456 ó RIF:J-12345678-0', ' (Campo Requerido)')">
                                            </td>

                                            <td>
                                                RESPONSABLES: <font color="Red">(*)</font>
                                                <input class="validate[required]" name="responsable" id="responsable" size="60" align="center" value="<?php echo $resultado[responsable]; ?>"/>
                                            </td>

                                            <td>
                                                CHOFER: <font color="Red">(*)</font>
                                                <input class="validate[required]" name="chofer" id="chofer" size="60" align="center" value="<?php echo $resultado[chofer]; ?>"/>
                                            </td>

                                            <td>
                                                N° DE PLACA: <font color="Red">(*)</font>
                                                <input class="validate[required]" name="placa" id="placa" size="12" align="center"  value="<?php echo $resultado[placa]; ?>"/>
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
                                                    <input readonly="true" type="text" id="nombre_apellido" name="nombre_apellido" maxlength="50" size="50" value="<?php echo $resultado[nombre_solicitante]; ?>" />

                                                    TIPO CLIENTE:
                                                    <input readonly="true" type="text" id="tipo_solicitante" name="tipo_solicitante"  maxlength="50" size="50" value="<?php echo $resultado[descripcion_tipo_solicitante]; ?>" />                     
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    DIRECCION:
                                                </td>

                                                <td>
                                                    <input readonly="true" type="text" id="direccion" name="direccion" maxlength="90" size="90" value="<?php echo $resultado[direccion_habitacion]; ?>" />                                                                 
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
                <tr>
                    <td colspan="2" class="botones" align="center" >            
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=nota_entrega'" value="Cerrar" name="cerrar" />  
                    </td>                                                   
                </tr> 
            <?php }  ?> 
        </table>
    </form>     
    <br>     
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
