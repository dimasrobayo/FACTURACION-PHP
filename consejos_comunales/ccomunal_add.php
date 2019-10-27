<?php
    error_reporting(E_ERROR | E_PARSE);
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");


    if (isset($_POST[save])) {
        $nombre_cc=$_POST['nombre_cc'];
        $codcom=$_POST['codcom'];
        $sector=$_POST['sector'];
        if (($nombre_cc!="") && ($codcom!="") && ($sector!="")) {
            $query="insert into consejo_comunal(nombre_cc,codcomu,sector) values ('$nombre_cc',$codcom,$sector)";
            $result = pg_query($query)or die(pg_last_error());
            if(pg_affected_rows($result)){
                $error="bien";
            }
            
        } else {
            $error="Error";
            $div_menssage='<div align="left">
                <h3 class="error">
                    <font color="red" style="text-decoration:blink;">
                        Error: Datos Incompletos, por favor verifique los datos!
                    </font>
                </h3>
            </div>';    
        }
    }//fin del add   
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

        <table class="adminccomunal" width="100%">
            <tr>
                <th>
                    CONSEJOS COMUNALES:
                </th>
            </tr>
        </table>
        
        <form id="QForm" name="QForm" method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr>
                    <th colspan="2" align="center">
                        <img src="images/add.png" width="16" height="16" alt="Nuevo Registro">
                        INGRESAR DATOS DEL CONSEJO COMUNAL
                    </th>
                </tr>

                <?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Datos registrados con &eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=consejo_comunal";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=consejo_comunal" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>

                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 

                <tr>
                   <td  colspan="2"   height="18">
                       <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>

                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Datos del Consejo Comunal:</b></td>
                </tr>
 		 
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>						
                            <tr>
                                <td align="right" >
                                    ESTADO: <font color="Red">(*)</font>
                                </td>

                                <td>
                                <select id="codest"  name="codest" class="validate[required]" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
                                    <option value="">----</option>
                                    <?php
                                        $consulta_sql=pg_query("SELECT * FROM estados order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
                                        while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                            if ($error!=""){
                                                if ($array_consulta[1]==$codest){
                                                    echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                }else {
                                                    echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                }
                                            }else {
                                                if ($array_consulta[1]==$cod_estado){
                                                    echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                }else {
                                                    echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                }
                                            }
                                        }
                                        pg_free_result($consulta_sql);
                                    ?>
                                </select>
                                </td>	
                            </tr>

                            <tr>
                                <td align="right" >
                                    MUNICIPIO: <font color="Red">(*)</font>
                                </td>
                                <td>
                                    <div id="contenedor2">
                                        <?php                                       
                                            if ($error!=""){
                                                echo '<select name="codmun" class="validate[required]" id="codmun"  onChange="cargarContenidoParroquia();" onClick="cargarContenidoParroquia();>';
                                                echo '<option value="">----</option>';
                                                $consultax1="SELECT * from municipios where codest='$codest' order by codmun";                                          
                                                $ejec_consultax1=pg_query($consultax1);
                                                while($vector=pg_fetch_array($ejec_consultax1)){
                                                    if ($vector[2]==$codmun){
                                                        echo '<option value="'.$vector[2].'" selected="selected">'.$vector[3].'</option>';
                                                    }else {
                                                        echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
                                                    }
                                                }
                                                echo '</select>';
                                                pg_free_result($ejec_consultax1);
                                            }else {
                                                echo '<select name="codmun" id="codmun" class="validate[required]" onChange="cargarContenidoParroquia();">';
                                                echo '<option value="">----</option>';
                                                $consultax1="SELECT * from municipios where codest='$cod_estado' order by codmun";
                                                $ejec_consultax1=pg_query($consultax1);
                                                while($vector=pg_fetch_array($ejec_consultax1)){
                                                    if ($vector[2]==$cod_municipio){
                                                        echo '<option value="'.$vector[2].'" selected="selected">'.$vector[3].'</option>';
                                                    }else {
                                                        echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
                                                    }
                                                }
                                                echo '</select>';
                                                pg_free_result($ejec_consultax1);
                                            }   
                                        ?>                                                          
                                    </div>
                                </td>   	
                            </tr>

                            <tr >
                                <td align="right" >
                                    PARROQUIA: <font color="Red">(*)</font>
                                </td>
                                <td>        
                                <div id="contenedor3">
                                    <?php 
                                        if ($error!=""){
                                            echo '<select name="codpar" id="codpar" class="validate[required]" onchange="cargarContenidoComunidad()"';
                                            echo '<option value="">----</option>';
                                            $consultax1="SELECT * from parroquias where codest='$codest' and codmun='$codmun' order by codpar";
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                if ($vector[3]==$codpar){
                                                    echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                }else {
                                                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                }
                                            }
                                            echo '</select>';
                                            pg_free_result($ejec_consultax1);   
                                        }else {
                                            echo '<select name="codpar" id="codpar" class="validate[required]" ';
                                            echo '<option value="">----</option>';
                                            $consultax1="SELECT * from parroquias where codest='$cod_estado' and codmun='$cod_municipio' order by codpar";
//                                                $ejec_consultax1=mysql_query($consultax1);
//                                                while($vector=mysql_fetch_array($ejec_consultax1)){
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                            }
                                            echo '</select>';                                                            
                                            pg_free_result($ejec_consultax1);                                
                                        } 
                                    ?>
                                </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="15%" align="right">
                                    COMUNIDAD: <font color="Red">(*)</font>
                                </td>
                                <td>        
                                    <div id="contenedor4">          
                                        <select name="codcom" id="codcom" disabled="true" class="validate[required]" style="width:180px" >
                                            <option value="">----</option>
                                            <?php 
                                                $consultax1="SELECT * from comunidades where codest='$result_solicitantes[codest]' and codmun='$result_solicitantes[codmun]' and codpar='$result_solicitantes[codpar]'  order by descom";
                                                $ejec_consultax1=pg_query($consultax1);
                                                while($vector=pg_fetch_array($ejec_consultax1)){
                                                    if ($vector[0]==$result_solicitantes[idcom]){
                                                        echo '<option value="'.$vector[0].'" selected="selected">'.$vector[5].'</option>';
                                                    }else {
                                                        echo '<option value="'.$vector[0].'">'.$vector[5].'</option>';
                                                    }
                                                }
                                                pg_free_result($ejec_consultax1);                                 
                                            ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="15%" align="right">
                                    SECTOR: <font color="Red">(*)</font>
                                </td>
                                <td>
                                    <input  class="validate[required,custom[integer]] text-input" type="text"   name="sector" value="" size="3" maxlength="5" />
                                </td>                                
                            </tr>
                            <tr>
                                <td width="15%" align="right">
                                    NOMBRE DEL C. COMUNAL: <font color="Red">(*)</font>
                                </td>
                                <td>
                                    <input  class="validate[required]" type="text"   name="nombre_cc" value="" size="50" maxlength="250" />
                                </td>                                
                            </tr>
                        </tbody>
                        </table>	
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=consejo_comunal'" value="Cerrar" name="cerrar" />  
                    </td>													
                </tr> 
            <?php }  ?>	
        </table>
    </form>     
    <br>	 
    </div>
</div>
