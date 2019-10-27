<!-- styles y script del  tab -->   
<link rel="stylesheet" type="text/css" href="css/tabcontent.css" media="screen"  />
<script language="javascript" src="js/tabcontent.js"></script>

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
        $nombre_clap=$_POST['nombre_clap'];
        $codcom=$_POST['codcom'];
        $ci_ubch=$_POST['ci_ubch'];
        $ci_ubch_insert = preg_replace("/\s+/", "", $ci_ubch);
        $ci_ubch_insert = str_replace("-", "", $ci_ubch_insert);
        $ci_umujer=$_POST['ci_umujer'];
        $ci_umujer_insert = preg_replace("/\s+/", "", $ci_umujer);
        $ci_umujer_insert = str_replace("-", "", $ci_umujer_insert);
        $ci_ffm=$_POST['ci_ffm'];
        $ci_ffm_insert = preg_replace("/\s+/", "", $ci_ffm);
        $ci_ffm_insert = str_replace("-", "", $ci_ffm_insert);
        $ci_mc=$_POST['ci_mc'];
        $ci_mc_insert = preg_replace("/\s+/", "", $ci_mc);
        $ci_mc_insert = str_replace("-", "", $ci_mc_insert);
        $ci_pp=$_POST['ci_pp'];
        $ci_pp_insert = preg_replace("/\s+/", "", $ci_pp);
        $ci_pp_insert = str_replace("-", "", $ci_pp_insert);
        $ci_jefe_com=$_POST['ci_jefe_com'];
        $ci_jefe_com_insert = preg_replace("/\s+/", "", $ci_jefe_com);
        $ci_jefe_com_insert = str_replace("-", "", $ci_jefe_com_insert);
        
        if (($nombre_clap!="") && ($ci_ubch!="") && ($ci_umujer!="") && ($ci_ffm!="") ) {
            $query="insert into clap(nombre_clap,resp_ubch,resp_unamujer,resp_francisco,idcom,resp_mincomuna,resp_pregonero,jefe_comunidad)values('$nombre_clap','$ci_ubch_insert','$ci_umujer_insert','$ci_ffm_insert',$codcom,'$ci_mc_insert','$ci_pp_insert','$ci_jefe_com_insert')";
            $result = pg_query($query)or die(pg_last_error());
            $result_insert=pg_fetch_array($result);
            pg_free_result($result);
            $error="bien";
        
        }else{
            $error="Error";
            $div_menssage='<div align="left">
                <h3 class="error">
                    <font color="red" style="text-decoration:blink;">
                        Error: Datos Incompletos, por favor verifique los datos!
                    </font>
                </h3>
            </div>';
        }
                                    
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

        <table class="adminclap" width="100%">
            <tr>
                <th>
                    CLAP
                </th>
            </tr>
        </table>
                            
        <form id="QForm" name="QForm" method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
        <table class="adminform" border="0" width="100%">
            <tr>
                <th colspan="2" align="center">
                    <img src="images/add.png" width="16" height="16" alt="Nuevo Registro">
                    INGRESAR DATOS DEL CLAP
                </th>
            </tr>

            <?php if ((isset($_POST[save])) and ($error=="bien")){  ?> <!-- Mostrar Mensaje -->

            <tr>
                <td colspan="2" align="center">
                    <div align="center"> 
                        <h3 class="info">   
                            <font size="2">                     
                                Datos Registrados con &Eacute;xito 
                                <br />
                                <script type="text/javascript">
                                    function redireccionar(){
                                        window.location="?view=clap_add";
                                    }  
                                    setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                </script>                       
                                [<a href="?view=clap_add" name="Continuar"> Continuar </a>]
                            </font>                         
                        </h3>
                    </div> 
                </td>
            </tr>

            <?php   }else{  ?>   <!-- Mostrar formulario Original --> 
            
            <tr>
               <td  colspan="2"   height="18">
                   <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                </td>
            </tr>

            <tr>
                <td class="titulo" colspan="2" height="18"  align="left"><b>Información Básica del CLAP:</b></td>
            </tr>
            
            <tr>
                <td colspan="2">
                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                            <tr>
                                <td  width="15%">
                                        NOMBRE DEL CLAP: <font color="Red">(*)</font>
                                </td>

                                <td> 
                                    <input size="120" maxlength="120" class="inputbox validate[required]"  type="text" id="nombre_clap" name="nombre_clap"  value="<?php echo $nombre_clap;?>"/>
                                </td>
                            </tr>   

                            <tr>
                                <td>
                                    ESTADO: <font color="Red">(*)</font>
                                </td>
                                <td>
                                    <select id="codest" name="codest" class="validate[required]" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
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
                                <td>
                                    MUNICIPIO: <font color="Red">(*)</font>
                                </td>
                                <td>
                                    <div id="contenedor2">
                                        <select name="codmun" id="codmun" class="validate[required]" onChange="cargarContenidoParroquia();">
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
                                <td>
                                    PARROQUIA: <font color="Red">(*)</font>
                                </td>
                                <td>        
                                    <div id="contenedor3">
                                        <select name="codpar" id="codpar" class="validate[required]" onchange="cargarContenidoComunidad();" >
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
                                        <a href="javascript: ue_comunidad_add();">
                                            <img src="images/agregar.png" alt="Buscar" title="Registrar Comunidad" width="20" height="20" border="0">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>    
                </td>
            </tr>

            <tr>
                <td>
                    <ul id="divsG" name="divsG" class="shadetabs">
                        <li><a class="selected" href="javascript:void(0);" rel="divG0" >Jefe Comunidad</a></li>
                        <li><a class="selected" href="javascript:void(1);" rel="divG1" >Responsable UBCH</a></li>
                        <li><a class="selected" href="javascript:void(2);" rel="divG2" >Responsable UNAMUJER</a></li>
                        <li><a class="selected" href="javascript:void(3);" rel="divG3" >Responsable F.F. MIRANDA</a></li>
                        <li><a class="selected" href="javascript:void(4);" rel="divG4" >Responsable MIN COMUNA</a></li>
                        <li><a class="selected" href="javascript:void(5);" rel="divG5" >Responsable Pregonero Productivo</a></li>
                    </ul>
                    <div style="border:1px solid gray;  margin-bottom: 3px; padding: 7px">
                    <div class="cpanelinicio">
                        <div style="display: block;" id="divG0" class="tabcontent" name="divG0">
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>
                            <tr>
                                <td width="18%">
                                    CEDULA DE IDENTIDAD: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <input id="ci_jefe_com" name="ci_jefe_com" type="text"  value="" class="validate[required] text-input"  size="10" maxlength="12" readonly="true"/>

                                    <a href="javascript: buscar_jefe_com();">
                                        <img src="images/busqueda.png" alt="Buscar" title="Buscar Colaborador" width="15" height="15" border="0">
                                    </a>

                                    <a href="javascript: jefe_comunidad_add();">
                                            <img src="images/agregar.png" alt="Buscar" title="Registrar Responsalbe de ubch" width="20" height="20" border="0">
                                    </a>                                                     
                                </td>
                            </tr>

                            
                            <tr>
                                <td>
                                    JEFE DE COMUNIDAD:
                                </td>

                                <td>
                                    <input readonly="true" type="text" id="jefe_comunidad" name="jefe_comunidad" maxlength="50" size="50" />
                                </td>   
                            </tr>         
                        </tbody>
                        </table>
                        </div>

                        <div style="display: block;" id="divG1" class="tabcontent" name="divG0">
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>
                            <tr>
                                <td width="18%">
                                    CEDULA DE IDENTIDAD: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <input id="ci_ubch" name="ci_ubch" type="text"  value="" class="validate[required] text-input"  size="10" maxlength="12" readonly="true"/>

                                    <a href="javascript: buscar_resp_ubch();">
                                        <img src="images/busqueda.png" alt="Buscar" title="Buscar Colaborador" width="15" height="15" border="0">
                                    </a>

                                    <a href="javascript: responsable_ubch_add();">
                                            <img src="images/agregar.png" alt="Buscar" title="Registrar Responsalbe de ubch" width="20" height="20" border="0">
                                    </a>                                                     
                                </td>
                            </tr>

                            
                            <tr>
                                <td>
                                    RESPONSABLE UBCH:
                                </td>

                                <td>
                                    <input readonly="true" type="text" id="responsable_ubch" name="responsable_ubch" maxlength="50" size="50" />
                                </td>   
                            </tr>         
                        </tbody>
                        </table>
                        </div>

                        <div style="display: block;" id="divG2" class="tabcontent" name="divG0">
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>
                            <tr>
                                <td width="18%">
                                    CEDULA DE IDENTIDAD: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <input id="ci_umujer" name="ci_umujer" type="text"  value="" class="validate[required] text-input"  size="10" maxlength="12" readonly="true"/>

                                    <a href="javascript: buscar_resp_umujer();">
                                        <img src="images/busqueda.png" alt="Buscar" title="Buscar Colaborador" width="15" height="15" border="0">
                                    </a>

                                    <a href="javascript: responsable_umujer_add();">
                                            <img src="images/agregar.png" alt="Buscar" title="Registrar Responsable de UNA MUJER" width="20" height="20" border="0">
                                    </a>                                                   
                                </td>
                            </tr>

                            
                            <tr>
                                <td>
                                    UNAMUJER:
                                </td>

                                <td>
                                    <input readonly="true" type="text" id="responsable_umujer" name="responsable_umujer" maxlength="50" size="50" />
                                </td>
                            </tr>       
                        </tbody>
                        </table>
                        </div>

                        <div style="display: block;" id="divG3" class="tabcontent" name="divG0">
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>
                            <tr>
                                <td width="18%">
                                    CEDULA DE IDENTIDAD: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <input id="ci_ffm" name="ci_ffm" type="text"  value="" class="validate[required] text-input"  size="10" maxlength="12" readonly="true"/>

                                    <a href="javascript: buscar_resp_ffm();">
                                        <img src="images/busqueda.png" alt="Buscar" title="Buscar Colaborador" width="15" height="15" border="0">
                                    </a>

                                    <a href="javascript: responsable_ffm_add();">
                                            <img src="images/agregar.png" alt="Buscar" title="Registrar Responsable de FRENTE FRANCISCO MIRANDA" width="20" height="20" border="0">
                                    </a>                                                       
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    FRENTE FRANCISCO MIRANDA:
                                </td>

                                <td>
                                    <input readonly="true" type="text" id="responsable_ffm" name="responsable_ffm" maxlength="50" size="50" />
                                </td>
                            </tr>        
                        </tbody>
                        </table>
                        </div>

                        <div style="display: block;" id="divG4" class="tabcontent" name="divG0">
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>
                            <tr>
                                <td width="18%">
                                    CEDULA DE IDENTIDAD: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <input id="ci_mc" name="ci_mc" type="text"  value="" class="validate[required] text-input"  size="10" maxlength="12" readonly="true"/>

                                    <a href="javascript: buscar_resp_mc();">
                                        <img src="images/busqueda.png" alt="Buscar" title="Buscar Colaborador" width="15" height="15" border="0">
                                    </a>

                                    <a href="javascript: responsable_mc_add();">
                                            <img src="images/agregar.png" alt="Buscar" title="Registrar Responsable de MIN COMUNA" width="20" height="20" border="0">
                                    </a>                                                  
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    MIN COMUNA:
                                </td>

                                <td>
                                    <input readonly="true" type="text" id="responsable_mc" name="responsable_mc" maxlength="50" size="50" />
                                </td>
                            </tr>        
                        </tbody>
                        </table>
                        </div>

                        <div style="display: block;" id="divG5" class="tabcontent" name="divG0">
                        <table class="borded" border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tbody>
                            <tr>
                                <td width="18%">
                                    CEDULA DE IDENTIDAD: <font color="Red">(*)</font>
                                </td>

                                <td>
                                    <input id="ci_p" name="ci_pp" type="text"  value=""  size="10" maxlength="12" readonly="true"/>

                                    <a href="javascript: buscar_resp_pp();">
                                        <img src="images/busqueda.png" alt="Buscar" title="Buscar Colaborador" width="15" height="15" border="0">
                                    </a>

                                    <a href="javascript: responsable_pp_add();">
                                            <img src="images/agregar.png" alt="Buscar" title="Registrar Responsable de PREGONERO PRODUCTIVO" width="20" height="20" border="0">
                                    </a>                                                   
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    PREGONERO PRODUCTIVO:
                                </td>

                                <td>
                                    <input readonly="true" type="text" id="responsable_pp" name="responsable_pp" maxlength="50" size="50" />
                                </td>
                            </tr>        
                        </tbody>
                        </table>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2" class="botones" align="center" >            
                    <input type="submit" class="button" name="save" value="  Guardar  " >
                    <input  class="button" type="button" onclick="javascript:window.location.href='?view=inicio'" value="Cerrar" name="cerrar" />  
                </td>                                                   
            </tr> 
        <?php }  ?> 
        </table>
        </form>     
        <br>     
        </div>
</div> 
        
<script type="text/javascript">
    var dtabs=new ddtabcontent("divsG")
    dtabs.setpersist(true)
    dtabs.setselectedClassTarget("link") //"link" or "linkparent"
    dtabs.init()
</script>       

<script type="text/javascript" >
    jQuery(function($) {
          $.mask.definitions['~']='[JEVGDCHjevgdch]';
          //$('#fecha_nac').mask('99/99/9999');
          $('#telefono').mask('(9999)-9999999');
          $('#celular').mask('(9999)-9999999');
          
    });

    function buscar_jefe_com() {                                        
        document.QForm.ci_ubch.value="";                               
        window.open("clap/jefe_comunidad_load.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=310,left=50,top=50,location=no,resizable=no");
    }

    function buscar_resp_ubch() {                                        
        document.QForm.ci_ubch.value="";                               
        window.open("clap/resp_ubch_load.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=310,left=50,top=50,location=no,resizable=no");
    }

    function buscar_resp_umujer() {                                        
        document.QForm.ci_umujer.value="";                                            
        window.open("clap/resp_umujer_load.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=310,left=50,top=50,location=no,resizable=no");
    }

    function buscar_resp_ffm() {                                        
        document.QForm.ci_ffm.value="";                                            
        window.open("clap/resp_francisco_load.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=310,left=50,top=50,location=no,resizable=no");
    }


    function buscar_resp_mc() {                                        
        document.QForm.ci_mc.value="";                                            
        window.open("clap/resp_mc_load.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=310,left=50,top=50,location=no,resizable=no");
    }


    function buscar_resp_pp() {                                        
        document.QForm.ci_pp.value="";                                            
        window.open("clap/resp_pp_load.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=310,left=50,top=50,location=no,resizable=no");
    }

    function comunidad_add(){
        cargarContenidoComunidad();
    }

    function ue_comunidad_add() {
        var mensaje="";
        var d1 = document.QForm.codpar.options[document.QForm.codpar.selectedIndex].value;
        var d2 = document.QForm.codest.value;
        var d3 = document.QForm.codmun.value;
        window.open("clap/comunidad_add.php?codpar="+d1+"&codest="+d2+"&codmun="+d3,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=250,left=50,top=50,location=no,resizable=no");
    } 

    function jefe_comunidad_add() {
        var mensaje="";
        var d1 = document.QForm.codpar.options[document.QForm.codpar.selectedIndex].value;
        var d2 = document.QForm.codest.value;
        var d3 = document.QForm.codmun.value;
        var d4 = document.QForm.codcom.value;
        window.open("clap/jefe_comunidad_add.php?codpar="+d1+"&codest="+d2+"&codmun="+d3+"&codcom="+d4,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=420,left=100,top=100,location=no,resizable=no");
    } 

    function responsable_ubch_add() {
        var mensaje="";
        var d1 = document.QForm.codpar.options[document.QForm.codpar.selectedIndex].value;
        var d2 = document.QForm.codest.value;
        var d3 = document.QForm.codmun.value;
        var d4 = document.QForm.codcom.value;
        window.open("clap/responsable_ubch_add.php?codpar="+d1+"&codest="+d2+"&codmun="+d3+"&codcom="+d4,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=420,left=100,top=100,location=no,resizable=no");
    } 

    function responsable_umujer_add() {
        var mensaje="";
        var d1 = document.QForm.codpar.options[document.QForm.codpar.selectedIndex].value;
        var d2 = document.QForm.codest.value;
        var d3 = document.QForm.codmun.value;
        var d4 = document.QForm.codcom.value;
        window.open("clap/responsable_umujer_add.php?codpar="+d1+"&codest="+d2+"&codmun="+d3+"&codcom="+d4,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=420,left=100,top=100,location=no,resizable=no");
    } 

    function responsable_ffm_add() {
        var mensaje="";
        var d1 = document.QForm.codpar.options[document.QForm.codpar.selectedIndex].value;
        var d2 = document.QForm.codest.value;
        var d3 = document.QForm.codmun.value;
        var d4 = document.QForm.codcom.value;
        window.open("clap/responsable_ffm_add.php?codpar="+d1+"&codest="+d2+"&codmun="+d3+"&codcom="+d4,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=420,left=100,top=100,location=no,resizable=no");
    } 

    function responsable_mc_add() {
        var mensaje="";
        var d1 = document.QForm.codpar.options[document.QForm.codpar.selectedIndex].value;
        var d2 = document.QForm.codest.value;
        var d3 = document.QForm.codmun.value;
        var d4 = document.QForm.codcom.value;
        window.open("clap/responsable_mc_add.php?codpar="+d1+"&codest="+d2+"&codmun="+d3+"&codcom="+d4,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=420,left=100,top=100,location=no,resizable=no");
    } 

    function responsable_pp_add() {
        var mensaje="";
        var d1 = document.QForm.codpar.options[document.QForm.codpar.selectedIndex].value;
        var d2 = document.QForm.codest.value;
        var d3 = document.QForm.codmun.value;
        var d4 = document.QForm.codcom.value;
        window.open("clap/responsable_pp_add.php?codpar="+d1+"&codest="+d2+"&codmun="+d3+"&codcom="+d4,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=420,left=100,top=100,location=no,resizable=no");
    } 
</script>