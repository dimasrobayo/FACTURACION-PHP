<script>
    jQuery( document ).ready(function( $ ) {
        $.fn.selectOption = function() {

           return this.each(function() { //para cada combo box ejecutamos la siguiente funcion 
            
              id = $(this).attr('id');

              //el "selected" se podria cambiar por "title" u otro atributo si queremos un html mas valido
              val = $(this).attr('set');
              
              //si no hay un id, agregamos uno temporalmente
              if(!id) {
                 id = 'fake_id';
                 $(this).attr('id', 'fake_id');
                 fakeId = true;
              } else {
                 fakeId = false;
              }
                 
              if(val) {
              
                 //y aqui lo mas importante, utilizamos el selector de jquery para buscar el option que necesita
                 //el atributo selected y agregarselo...
                 $('#' + id + ' option[value='+val+']').attr('selected', 'selected');
              }
              
              //eliminamos el id temporal en caso de haberlo utilizado
              if(fakeId) {
                  $(this).removeAttr('id');
              }
              
           });
        }

        window.onload = function() {
            var cedula = $("#cedula_rif").val();
            validarGetName(cedula); //SE CARGAR LA FUNCIÓN AL CARGAR LA PÁGINA
        };

        function validarGetName(cedulaRif){
            var naci = cedulaRif.substring(0, 1); //OBTIENE LA NACIONALIDAD
            var ced = cedulaRif.substring(2, 12); //OBTIENE LA CEDULA O RIF SEA EL CASO

            switch(naci){ //IDENTIFICAR SI ES RIF O CEDULA
                case ('J' || 'G'):
                    getSENIAT(naci,ced);
                break;

                case ('V' || 'E'):
                    getCNE(naci,ced);
                break;
            }
        }


        function getSENIAT(n,c) {
            $.ajax({
                url: "library/Rif.php",
                type: 'POST',
                data: 'rif='+n+'-'+c,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.code_result<0){ //NO OBTUVO RESULTADOS CON EL RIF, SE INSERTA EL NOMBRE MANUAL
                        document.getElementById("nombreapellido").readOnly = false;
                    } else {
                        $('input[name="nombreapellido"]').val(obj.seniat.nombre); //SE ASIGNA LOS NOMBRES OBTENIDOS DEL RIF
                        document.getElementById("nombreapellido").readOnly = false;
                    }

                }
            })
        }


        function getCNE(n,c){
            $.ajax({
                url: "library/getCNE.php",
                type: 'POST',
                data: 'nacionalidad='+n+'&cedula='+c,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if (obj.inscrito == "NO") { // SINO EXISTE EN EL CNE, SE CALCULA EL RIF Y SE ENVIA A GETSENIAT
                        $.ajax({
                            url: "library/setRif.php",
                            type: 'POST',
                            data: 'nacionalidad='+n+'&cedula='+c,
                            success: function(data) {
                                var naci = data.substring(0, 1);
                                var ced1 = data.substring(1, 9)+"-"+data.substring(9, 10);
                                getSENIAT(naci,ced1); //SE ENVIA A GETSENIAT
                            }
                        });
                    } else { // SI EXISTE EL REGISTRO ASIGNA EL NOMBRE, ESTADO, MUNICIPIO Y PARROQUIA
                        document.getElementById("nombreapellido").readOnly = false;
                        $('input[name="nombreapellido"]').val(obj.nombres+" "+obj.apellidos);
                        var estado, parroquia, municipio;
                        estado = obj.cvestado.substring(5,20);
                        municipio = obj.cvmunicipio.substring(4,20);
                        parroquia = obj.cvparroquia.substring(4,20);
                        //console.log("ESTADO: "+estado);
                        //console.log("MUNICIPIO: "+municipio);
                        //console.log("PARROQUIA: "+parroquia);

                        setTimeout(function() {
                            $.ajax({
                                url: "library/localidad.php",
                                type: 'POST',
                                data: 'estado='+estado,
                                success: function(data) {
                                    //console.log("1. "+data);
                                    $('#codest').attr('set',data);
                                    $("#codest").selectOption();
                                    cargarContenidoMunicipio();
                                }   
                            });
                        }, 0);

                        setTimeout(function() {
                            $.ajax({
                                url: "library/localidad.php",
                                type: 'POST',
                                data: 'municipio='+municipio,
                                success: function(data) {
                                    //console.log("2. "+data);
                                    $('#codmun').attr('set',data);
                                    $("#codmun").selectOption();
                                    cargarContenidoParroquia();
                                }   
                            });
                        }, 200);

                        setTimeout(function() {
                            $.ajax({
                                url: "library/localidad.php",
                                type: 'POST',
                                data: 'parroquia='+parroquia,
                                success: function(data) {
                                    $("#codmun").selectOption();
                                    //console.log("3. "+data);
                                    $('#codpar').attr('set',data);
                                    $("#codpar").selectOption();
                                    cargarContenidoComunidad();
                                }   
                            });
                        }, 500);
                    } //FIN ELSE
                } //FIN DEL SUCCES AJAX
            }); //FIN AJAX
        }
    });
</script>

<?php
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo ('<div align="center"><img  src="../../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        exit;
    }
    
    require("../../conexion/aut_config.inc.php");
//    require ("../../conexion/aut_verifica.inc.php"); //validar sessiones del usuario
    /*este es el enlace de conexion a la base de datos*/
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    
    if (isset($_GET["cedula_rif"])) { 
        $cedula_rif=$_GET["cedula_rif"];
    }
	
    if (isset($_POST[save])){
        $cedula_rif=$_POST['cedula_rif'];
        $cedula_rif_insert = preg_replace("/\s+/", "", $cedula_rif);
        $cedula_rif_insert = str_replace("-", "", $cedula_rif_insert);
        $cod_tipo_solicitante=$_POST['cod_tipo_solicitante'];
        $nombreapellido=strtoupper($_POST["nombreapellido"]);
        $sexo=$_POST['sexo'];
        $fecha_nac=implode('-',array_reverse(explode('/',$_POST['fecha_nac']))); 
        $direccion=$_POST['direccion'];
        $telefono=$_POST['telefono'];
        $celular=$_POST['celular'];
        $email=$_POST['email'];
        $codcom=$_POST['codcom'];
//        
        $query="SELECT insert_solicitante('$cedula_rif_insert','$cod_tipo_solicitante','$nombreapellido','$sexo','$fecha_nac','$direccion','$telefono','$celular','$email','$codcom')";
        $result = pg_query($query)or die(pg_last_error());
        $result_insert=pg_fetch_array($result);
        pg_free_result($result);
        
        if ($result_insert[0]==1){
            $error="bien";
            $query="SELECT * FROM solicitantes,tipo_solicitantes WHERE solicitantes.cedula_rif='$cedula_rif_insert' AND solicitantes.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante order by cedula_rif";				
            $result = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
            $resultado_cliente=  pg_fetch_array($result);
            echo "<script type=\"text/javascript\">
                    opener.document.facturacion.cedula_solicitante.value='$cedula_rif';
                    opener.document.facturacion.nombre_solicitante.value='$resultado_cliente[nombre_solicitante]';
                    opener.document.facturacion.tipo_solicitante.value='$resultado_cliente[descripcion_tipo_solicitante]';
                    opener.document.facturacion.direccion.value='$resultado_cliente[direccion_habitacion]';
                    close();
                  </script>";
        }else{
            $error="Error";
            $div_menssage='<div align="left"><h3 class="error"><font color="#CC0000" style="text-decoration:blink;">Error: Ya Existe un Registro con el Nro.: <font color="blue">'.$cedula_rif.'</font>; por favor verifique los datos!</font></h3></div>';				
//            $div_menssage='<div align="left">
//                    <h3 class="error">
//                        <font color="red" style="text-decoration:blink;">
//                            Error: La Cedula ó RIF Ya existe Registrada, por favor verifique los datos!
//                        </font>
//                    </h3>
//                </div>';
        }

        if ($result[0]) {
            $error="Error";
            $div_menssage='<div align="left"><h3 class="error"><font color="#CC0000" style="text-decoration:blink;">Error: Ya Existe un Registro con el Nro.: <font color="blue">'.$cedula_rif.'</font>; por favor verifique los datos!</font></h3></div>';				
        }
        else {			
            $error="bien";	
            $query="INSERT INTO colaboradores VALUES('$cedula_rif_add','$tipo_colaborador','$nombre','$direccion','$telefono','$celular','$email',now(),'$miembro','$status')";								
            $result=mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());						
            if(mysql_affected_rows()){ // Verificamos y Cargamos la auditoria	
                    $id_usuario=$_SESSION['id'];
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $accion= "insert";				
                    $modulo_accion="Colaboradores";
                    $data_accion=$cedula_rif_add.";".$nombre;
                    $query=mysql_query("INSERT INTO usuarios_logbook values('','$id_usuario',now(),'$ip','$accion','$modulo_accion','$data_accion')") or die(mysql_error());
            }
            mysql_close($db_connect);				
            echo "<script type=\"text/javascript\">
                opener.document.QForm.cedula_rif.value='$cedula_rif';
                opener.document.QForm.nombre_colaborador.value='$nombre';
                opener.document.QForm.direccion.value='$direccion';
                opener.document.QForm.telefono.value='$telefono';
                close();
              </script>";
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
        
        <!-- styles form-->
        <!--<link rel="stylesheet" href="../../css/template_portada.css" type="text/css" />-->
        <link rel="stylesheet" href="../../css/general_portada.css" type="text/css" />
        <!--<link rel="stylesheet" type="text/css" href="../../css/styles_general.css" media="screen" />-->
        <link rel="stylesheet" href="../../css/styles_nuevo.css" type="text/css"/>
        <!--<link rel="stylesheet" href="../../css/template.css" type="text/css" />-->
        <link rel="stylesheet" href="../../css/template_portada.css" type="text/css" />

        <!-- script del jquery, ajax y funciones javascript-->
        <script type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>   
        <script language="javascript" src="../../js/ajax.js"></script>
        <script type="text/javascript" src="../../js/lib_javascript.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../../js/funciones.js"></script>
        <!-- <script type="text/javascript" language="JavaScript1.2" src="../js/disabled_keys.js"></script> -->

        <!-- script de la mascaras -->
        <script src="../../js/jquery.maskedinput.js" type="text/javascript"></script>
        
        <!-- styles y script del calendario Fecha -->	
        <link type="text/css" rel="stylesheet" href="../../js/calendario_cat/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
        <script type="text/javascript" src="../../js/calendario_cat/dhtmlgoodies_calendar_cat.js?random=20060118"></script>

        <!-- styles y script Validaciones -->

        <link rel="stylesheet" href="../../css/validationEngine.jquery.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="../../css/LiveValidation.css" type="text/css" media="screen" />	
        <script src="../../js/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
        <script src="../../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>	
        <script type="text/javascript" SRC="../../js/livevalidation_standalone.js"></script>    	

    <!-- script de efectos -->	
        <script src="../../js/prototype.js" type="text/javascript"></script>
        <script src="../../js/scriptaculous.js" type="text/javascript"></script>
        <script src="../../js/unittest.js" type="text/javascript"></script> 
        
        <!-- Token Input -->
      <script type="text/javascript" src="../../js/tokeninput/src/jquery.tokeninput.min.js"></script>
      <link rel="stylesheet" href="../../js/tokeninput/styles/token-input.css" type="text/css" />
      <link rel="stylesheet" href="../../js/tokeninput/styles/token-input-facebook.css" type="text/css" />
      
        <!-- funciones javascript  -->
       <script type="text/javascript" charset="utf-8">            
            jQuery(document).ready(function(){          
              jQuery("#QForm").validationEngine();          
            });
            
            jQuery(function($) {
              $.mask.definitions['~']='[JEVGDCjevgdc]';
              //$('#fecha_nac').mask('99/99/9999');
              //$('#fecha_deposito').mask('99/99/9999');
              $('#telefono').mask('(9999)-9999999');
              $('#celular').mask('(9999)-9999999');
              $('#telefono_trabajo').mask('(9999)-9999999');
              $('#telefono_fax').mask('(9999)-9999999');
              $('#rif').mask('~-9999?9999-9',{placeholder:" "});
              $('#cedula_rif').mask('~-9?99999999',{placeholder:" "});
              $('#codigo_concepto').mask('9?99999999',{placeholder:" "});
              //$('#phoneext').mask("(999) 999-9999? x99999");
              //$("#tin").mask("99-9999999");
              //$("#ssn").mask("999-99-9999");
              //$("#product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("Ha escrito lo siguiente: "+this.val());}});
              //$("#eyescript").mask("~9.99 ~9.99 999");

           });
       </script>
      
        <script language="JavaScript">
            function aceptar(cedula) {
                opener.document.QForm.cedula_rif.value=cedula;
                close();
            }
        </script>
        
<!--        <script>
            function getCNE(cedula){
                var naci = cedula.substring(0, 1);
                var ced = cedula.substring(2, 10);

                console.log("CED: "+naci+"-"+ced);
                jQuery.ajax({
                    url: "../../library/getCNE.php",
                    type: 'POST',
                    data: 'nacionalidad='+naci+'&cedula='+ced,
                    success: function(data) {
                        console.log(data);
                        var obj = jQuery.parseJSON(data);
                        jQuery('input[name="nombreapellido"]').val(obj.nombres+" "+obj.apellidos);

                    }
                });
            }
        </script>-->
        
        <script>
            jQuery( document ).ready(function( $ ) {
                $.fn.selectOption = function() {

                   return this.each(function() { //para cada combo box ejecutamos la siguiente funcion 

                      id = $(this).attr('id');

                      //el "selected" se podria cambiar por "title" u otro atributo si queremos un html mas valido
                      val = $(this).attr('set');

                      //si no hay un id, agregamos uno temporalmente
                      if(!id) {
                         id = 'fake_id';
                         $(this).attr('id', 'fake_id');
                         fakeId = true;
                      } else {
                         fakeId = false;
                      }

                      if(val) {

                         //y aqui lo mas importante, utilizamos el selector de jquery para buscar el option que necesita
                         //el atributo selected y agregarselo...
                         $('#' + id + ' option[value='+val+']').attr('selected', 'selected');
                      }

                      //eliminamos el id temporal en caso de haberlo utilizado
                      if(fakeId) {
                          $(this).removeAttr('id');
                      }

                   });
                }

                window.onload = function() {
                    var cedula = $("#cedula_rif").val();
                    validarGetName(cedula); //SE CARGAR LA FUNCIÓN AL CARGAR LA PÁGINA
                };

                function validarGetName(cedulaRif){
                    var naci = cedulaRif.substring(0, 1); //OBTIENE LA NACIONALIDAD
                    var ced = cedulaRif.substring(2, 12); //OBTIENE LA CEDULA O RIF SEA EL CASO

                    switch(naci){ //IDENTIFICAR SI ES RIF O CEDULA
                        case ('J' || 'G'):
                            getSENIAT(naci,ced);
                        break;

                        case ('V' || 'E'):
                            getCNE(naci,ced);
                        break;
                    }
                }


                function getSENIAT(n,c) {
                    $.ajax({
                        url: "../../library/Rif.php",
                        type: 'POST',
                        data: 'rif='+n+'-'+c,
                        success: function(data) {
                            var obj = jQuery.parseJSON(data);
                            if(obj.code_result<0){ //NO OBTUVO RESULTADOS CON EL RIF, SE INSERTA EL NOMBRE MANUAL
                                document.getElementById("nombreapellido").readOnly = false;
                            } else {
                                $('input[name="nombreapellido"]').val(obj.seniat.nombre); //SE ASIGNA LOS NOMBRES OBTENIDOS DEL RIF
                                document.getElementById("nombreapellido").readOnly = false;
                            }

                        }
                    })
                }


                function getCNE(n,c){
                    $.ajax({
                        url: "../../library/getCNE.php",
                        type: 'POST',
                        data: 'nacionalidad='+n+'&cedula='+c,
                        success: function(data) {
                            var obj = jQuery.parseJSON(data);
                            if (obj.inscrito == "NO") { // SINO EXISTE EN EL CNE, SE CALCULA EL RIF Y SE ENVIA A GETSENIAT
                                $.ajax({
                                    url: "../../library/setRif.php",
                                    type: 'POST',
                                    data: 'nacionalidad='+n+'&cedula='+c,
                                    success: function(data) {
                                        var naci = data.substring(0, 1);
                                        var ced1 = data.substring(1, 9)+"-"+data.substring(9, 10);
                                        getSENIAT(naci,ced1); //SE ENVIA A GETSENIAT
                                    }
                                });
                            } else { // SI EXISTE EL REGISTRO ASIGNA EL NOMBRE, ESTADO, MUNICIPIO Y PARROQUIA
                                document.getElementById("nombreapellido").readOnly = false;
                                $('input[name="nombreapellido"]').val(obj.nombres+" "+obj.apellidos);
                                var estado, parroquia, municipio;
                                estado = obj.cvestado.substring(5,20);
                                municipio = obj.cvmunicipio.substring(4,20);
                                parroquia = obj.cvparroquia.substring(4,20);
                                //console.log("ESTADO: "+estado);
                                //console.log("MUNICIPIO: "+municipio);
                                //console.log("PARROQUIA: "+parroquia);

                                setTimeout(function() {
                                    $.ajax({
                                        url: "../../library/localidad.php",
                                        type: 'POST',
                                        data: 'estado='+estado,
                                        success: function(data) {
                                            //console.log("1. "+data);
                                            $('#codest').attr('set',data);
                                            $("#codest").selectOption();
                                            cargarContenidoMunicipioCat();
                                        }   
                                    });
                                }, 0);

                                setTimeout(function() {
                                    $.ajax({
                                        url: "../../library/localidad.php",
                                        type: 'POST',
                                        data: 'municipio='+municipio,
                                        success: function(data) {
                                            //console.log("2. "+data);
                                            $('#codmun').attr('set',data);
                                            $("#codmun").selectOption();
                                            cargarContenidoParroquiaCat();
                                        }   
                                    });
                                }, 200);

                                setTimeout(function() {
                                    $.ajax({
                                        url: "../../library/localidad.php",
                                        type: 'POST',
                                        data: 'parroquia='+parroquia,
                                        success: function(data) {
                                            $("#codmun").selectOption();
                                            //console.log("3. "+data);
                                            $('#codpar').attr('set',data);
                                            $("#codpar").selectOption();
                                            cargarContenidoComunidadCat();
                                        }   
                                    });
                                }, 500);
                            } //FIN ELSE
                        } //FIN DEL SUCCES AJAX
                    }); //FIN AJAX
                }
            });
        </script>
    </head>
<body style="background-color: #f9f9f9;" >
<?php if($div_menssage) { ?>					
    <script type="text/javascript">
            function ver_msg(){
                    Effect.Fade('msg');
            }  
            setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
    </script>
 <?php } ?>
    
    <!-- Codigo para mostrar la ayuda al usuario  -->
    <div style="top: 477px; left: 966px; display: none;" id="mensajesAyuda">
            <div id="ayudaTitulo">Código de Seguridad (Obligatorio)</div>
            <div id="ayudaTexto">Ingresa el código de seguridad que muestra la imagen</div>
    </div>
    
    <table class="container_contenido_cat" border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>  			
            <tr>
                <td>
                    <form method="POST" action="cliente_add.php" id="QForm" name="QForm" enctype="multipart/form-data">
                    <table class="adminform_cat" width="100%"  align="center">
                        <tbody>
                            <tr>
                                <th align="center">
                                    <img src="../../images/add.png" width="16" height="16" alt="Nuevo Registro">
                                    REGISTRO CLIENTE
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <table class="adminform" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <?php if($div_menssage) { ?>
                                            <tr>
                                                <td colspan="2" id="msg" align="center">		
                                                    <?php echo $div_menssage;?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                               <td  colspan="2"   height="18">
                                                   <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                                                </td>
                                            </tr>
                                           <tr>
                                                <td class="titulo" colspan="2" height="18"  align="left"><b>Datos Principales:</b></td>
                                           </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td  width="20%" height="22">
                                                                        C&Eacute;DULA / RIF: <font color="Red">(*)</font>
                                                                </td>
                                                                <td  width="80%"  height="22">					
                                                                    <table border="0" >
                                                                        <tbody>
                                                                            <tr>
                                                                                <td width="100">
                                                                                    <input size="10" class="inputbox validate[required]"  type="text" id="cedula_rif" name="cedula_rif"  value="<?php echo $cedula_rif;?>" readonly="true" /> 																																						
                                                                                </td>
                                                                                <td>
                                                                                    TIPO DE SOLICITANTE: <font color="Red">(*)</font>
                                                                                </td>
                                                                                <td>														
                                                                                    <select id="cod_tipo_solicitante" name="cod_tipo_solicitante" class="inputbox validate[required]" size="1">
                                                                                        <option value="">---</option>
                                                                                        <?php 
                                                                                            $consulta_sql=pg_query("SELECT * FROM tipo_solicitantes ");
                                                                                            while ($array_consulta=pg_fetch_array($consulta_sql)){
                                                                                                if ($error!=""){
                                                                                                    if ($array_consulta[0]==$cod_tipo_solicitante){
                                                                                                        echo '<option value="'.$array_consulta[0].'"  selected="selected">'.$array_consulta[1].'</option>';																			
                                                                                                    }else{
                                                                                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';																			
                                                                                                    }
                                                                                                }else{
                                                                                                    echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';																			
                                                                                                }
                                                                                            }																																						
                                                                                            pg_free_result($consulta_sql);								
                                                                                        ?>																												
                                                                                    </select>														
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>	

                                                            <tr>
                                                                <td>
                                                                    NOMBRE DEL SOLICITANTE: <font color="Red">(*)</font>
                                                                </td>
                                                                <td>
                                                                    <input onfocus="getCNE(document.getElementById('cedula_rif').value);" class="validate[required] text-input" type="text" id="nombreapellido" name="nombreapellido" value="<?php if ($error!='') echo $nombreapellido;?>"  size="50" maxlength="50"/>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    SEXO: <font color="Red">(*)</font>
                                                                </td>
                                                                <td>
                                                                    <table border="0" >
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <select id="sexo" name="sexo" class="inputbox validate[required]" size="1">									
                                                                                        <?php
                                                                                            if($error!="") {
                                                                                                if($sexo=="1") {
                                                                                                    echo '<option value="'.$sexo.'" selected="selected">MASCULINO</option>';
                                                                                                }elseif($sexo=="2") {
                                                                                                    echo '<option value="'.$sexo.'" selected="selected">FEMENINO</option>';
                                                                                                }else{
                                                                                                    echo '<option value="'.$sexo.'" selected="selected">NO APLICA</option>';
                                                                                                }
                                                                                            }																					
                                                                                        ?>
                                                                                        <option value="" >---</option>
                                                                                        <option value="1">MASCULINO</option>
                                                                                        <option value="2">FEMENINO</option>																						
                                                                                        <option value="3">NO APLICA</option>																						
                                                                                    </select>														
                                                                                </td>
                                                                                <td>
                                                                                    FECHA NATAL: 
                                                                                    <input class="validate[custom[date],past[NOW]]" name="fecha_nac" type="text" value="<?php if ($error!="") echo implode('/',array_reverse(explode('-',$fecha_nac)));?>"  id="fecha_nac"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"  />
                                                                                    <img src="../../images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_nac,'dd/mm/yyyy',this);">
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>	
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Ubicación:</b></td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td width="20%" >
                                                                    ESTADO: <font color="Red">(*)</font>
                                                                </td>
                                                                <td>
                                                                    <select id="codest" name="codest" class="validate[required]" onchange="cargarContenidoMunicipioCat();" onclick="cargarContenidoMunicipioCat();"  >
                                                                        <option value="">----</option>
                                                                        <?php 
                                                                            $consulta_sql=pg_query("SELECT * FROM estados order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
                                                                            while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                                                                if ($array_consulta[1]==$cod_estado){
                                                                                    echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                                                }else {
                                                                                    echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                                                }
                                                                            }
                                                                            pg_free_result($consulta_sql);
                                                                        ?>
                                                                    </select>
                                                                </td>	
                                                            </tr>

                                                            <tr>
                                                                <td width="15%" >
                                                                    MUNICIPIO: <font color="Red">(*)</font>
                                                                </td>
                                                                <td>
                                                                    <div id="contenedor2">
                                                                        <select name="codmun" id="codmun" class="validate[required]" onChange="cargarContenidoParroquiaCat();">
                                                                            <option value="">----</option>
                                                                            <?php										
                                                                                $consultax1="SELECT * from municipios where codest='$cod_estado' order by codmun";
                                                                                $ejec_consultax1=pg_query($consultax1);
                                                                                while($vector=pg_fetch_array($ejec_consultax1)){
                                                                                    if ($vector[2]==$cod_municipio){
                                                                                        echo '<option value="'.$vector[2].'" selected="selected">'.$vector[3].'</option>';
                                                                                    }else {
                                                                                        echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
                                                                                    }
                                                                                }
                                                                                pg_free_result($ejec_consultax1);
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>	
                                                            </tr>

                                                            <tr >
                                                                <td width="15%" >
                                                                    PARROQUIA: <font color="Red">(*)</font>
                                                                </td>
                                                                <td>		
                                                                    <div id="contenedor3">
                                                                        <select name="codpar" id="codpar" class="validate[required]" onchange="cargarContenidoComunidadCat();" >
                                                                            <option value="">----</option>
                                                                            <?php 
                                                                                $consultax1="SELECT * from parroquias where codest='$cod_estado' and codmun='$cod_municipio' order by codpar";
                                                                                $ejec_consultax1=pg_query($consultax1);
                                                                                while($vector=pg_fetch_array($ejec_consultax1)){
                                                                                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                                                }
                                                                                pg_free_result($ejec_consultax1);																		
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr id="comunidades">
                                                                <td>
                                                                    COMUNIDAD: <font color="Red">(*)</font>
                                                                </td>
                                                                <td>		
                                                                    <div id="contenedor4">			
                                                                        <select name="codcom" id="codcom" class="validate[required]" style="width:180px" >
                                                                            <option value="">----</option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td>
                                                                    DIRECCI&Oacute;N DE HABITACI&Oacute;N:  <font color="Red">(*)</font>
                                                                </td>
                                                                <td>
                                                                    <input class="validate[required] text-input" type="text" id="direccion" name="direccion" value="<?php if ($error!='') echo $direccion;?>"  size="60" maxlength="150"/>	
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td>
                                                                    TEL&Eacute;FONO HAB.:
                                                                </td>
                                                                <td>														
                                                                    <table border="0" >
                                                                        <tbody>
                                                                            <tr>
                                                                                <td width="130">
                                                                                    <input class="validate[custom[phone]] text-input" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" id="telefono" type="text" name="telefono" size="15" value="<?php if ($error!='') echo $telefono;?>" maxlength="15"/>														
                                                                                </td>
                                                                                <td>
                                                                                    TEL&Eacute;FONO CEL.: <font color="Red">(*)</font>
                                                                                </td>
                                                                                <td>
                                                                                    <input class="validate[required,custom[phone]] text-input" placeholder="(0414)-1234567" title="Ej.: (0414)-1234567" id="celular" type="text" name="celular" size="15" value="<?php if ($error!='') echo $celular;?>" maxlength="15"/>														
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    CORREO ELECTR&Oacute;NICO:
                                                                </td>
                                                                <td>
                                                                    <input class="validate[custom[email]] text-input" placeholder="minombre@ejemplo.com" title="Ej.: minombre@ejemplo.com" type="text" id="email" name="email" size="50" value="<?php if ($error!='') echo $email;?>" maxlength="50"/>																		
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>	
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="botones" align="center" >
                                                    <input id="submit"  class="button" type="submit" name="save" value="Enviar" />									
                                                    <input class="button"  type="button" onclick="javascript:parent.close();" value="Cerrar" name="cerrar" /> 
                                                </td>		
                                            </tr>	
                                        </tbody>
                                    </table>
                                </td>
                            </tr>	
                        </tbody>
                    </table>
                </form> 
                </td>	 				 			  
            </tr>
        </tbody>
    </table>
  		  	
  	<!-- <br />
	<a href="javascript:parent.jQuery.fancybox.close();">
		<img border="0" name="Salida" src="../../images/salida.png">	
	</a> -->	
</body>
    
    
</html>
    
    
        