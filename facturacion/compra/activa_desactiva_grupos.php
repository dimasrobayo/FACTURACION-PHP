<?php
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["type"];
    $pagina=$pag.'?type='.$type;

    //Conexion a la base de datos
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?>
	
<?php 
if (isset($_POST[save])) {

    $codigo_tipo_personal = $_POST['codigo_tipo_personal'];
    $accion = $_POST['accion'];

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

    $error="bien";	

    if ($codigo_tipo_personal==0) {
        $consulta="UPDATE personal SET status=$accion";
    } else {
        $consulta="UPDATE personal SET status=$accion WHERE codigo_tipo_personal=$codigo_tipo_personal";
    }
    
    $actualiza_registro = pg_query($consulta) or die('La consulta fall&oacute;: ' . pg_last_error());	
    $result_insert=pg_fetch_array($actualiza_registro);	
    pg_free_result($actualiza_registro);
    pg_close(); 		     
}//fin del add   
?>
<div align="center" class="centermain">
    <div class="main">  
        <table class="admin_personal">
            <tr>
                <th>
                    Activar/Desactivar Grupos
                </th>
            </tr>
        </table>
        
        <form id="personal_ad" name="personal_ad" method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        Activar/Desactivar Grupos para Efectos de N&oacute;mina
                    </th>
                </tr>

                <?php 
                if ((isset($_POST[save])) and ($error=="bien"))
                {		
                ?> 

                <tr>
                    <td colspan="2" align="center">                        	
                        <br />
                        <strong>Resultado</strong>: 
                        <?php 
                            switch($resultado_insert) {
                                case 0: 
                                    echo 'El Registro fue procesado con &eacute;xito';	
                                    break;
                                case 1: 
                                    echo 'No se pudo procesar el registro ';
                                    break;
                            }				
                            echo '<br />'.$msg;
                        ?>
                        <br />	
                    </td>
                </tr> 

                <table class="adminform" align="center">
                    <tr align="center">
                        <td width="100%" valign="top" align="center">
                            <div id="cpanel">
                                <div style="float:right;">
                                    <div class="icon">
                                        <a href="index2.php">
                                            <img src="images/personal.png" alt="salir" align="middle"  border="0" />
                                            <span>Gestor de Datos</span>
                                        </a>
                                    </div>
                                </div>	
                            </div>
                        </td>
                    </tr>
                </table>

                <?php 
                }
                else
                {
                ?> 

                <?php echo $error;?>
 		
                <tr>
                    <td width="12%">
                        Tipo de Personal:
                    </td>

                    <td>
                        <select id="codigo_tipo_personal" name="codigo_tipo_personal" size="0" class="options">
                            <option value="0">Todos los Grupos</option>	        
                            <?php
                                $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

                                $consulta=pg_query("select * from tipo_personal order by nombre_tipo_personal");
                                while ($array_consulta=pg_fetch_array($consulta)) {
                                    echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                }
                                pg_free_result($consulta);
                            ?>
                        </select>
                        <script type="text/javascript">
                            var codigo = new LiveValidation('codigo_tipo_personal');
                            codigo.add(Validate.Presence);
                            codigo.add( Validate.texto );
                        </script>
                        <font color="#ff0000">*</font>				
                    </td>			
                </tr>

                <tr>
                    <td width="12%">
                        Acci&oacute;n a aplicar:
                    </td>

                    <td>
                        <select id="accion" name="accion" size="0" class="options">
                            <option value="">----</option>	        
                            <option value="1">Activar</option>
                            <option value="0">Desactivar</option>
                        </select>
                        <script type="text/javascript">
                            var codigo = new LiveValidation('sexo');
                            codigo.add(Validate.Presence);
                            codigo.add( Validate.texto );
                        </script>	
                        <font color="#ff0000">*</font>			
                    </td>			
                </tr>
                            
                <tr>
                    <td bgcolor="#55baf3" colspan="2" align="center">
                        <input type="submit" class="button" name="save" value="  Procesar  " >
                        <input class="button" type="reset" value="Limpiar" name="Refresh"> 
                        <input  class="button" type="button" onClick="history.back()" value="Regresar">
                    </td>
                </tr>
		
                <?php 
                    }
                ?> 
            </form> 
        </table>		       
    </div>
</div>

<script type="text/javascript">
    var dtabs=new ddtabcontent("divsG")
    dtabs.setpersist(true)
    dtabs.setselectedClassTarget("link") //"link" or "linkparent"
    dtabs.init()
</script>