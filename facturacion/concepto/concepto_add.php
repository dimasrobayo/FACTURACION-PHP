<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
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

    if (isset($_POST[save])){
        $codigo_concepto = $_POST['codigo_concepto'];
        $rif_empresa= $_POST['rif_empresa'];
        $codigo_categoria= $_POST['codigo_categoria'];
        $codigo_marca= $_POST['codigo_marca'];
        $codigo_almacen= $_POST['codigo_almacen'];
        $nombre_concepto = $_POST['nombre_concepto'];
        $costo_unitario = $_POST['costo_unitario'];
        $precio_venta = $_POST['precio_venta'];
        $iva_producto=$_POST['iva'];
        $alicuota_producto=$_POST['alicuota'];
        $stock = $_POST['stock'];
        $stock_minimo = $_POST['stock_minimo'];
        $excento = $_POST["excento_iva"];

        if (isset($_POST["status_stock"])){	
            $status_stock=1;
        }else {
            $status_stock=0;
        }
        
        // Consultamos si existe la descripcion
        $query = "SELECT * FROM concepto_factura WHERE codigo_concepto='$codigo_concepto'";
        $result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);
        pg_free_result($result);						

        if (!$resultado[0]) {
            $query = "INSERT INTO concepto_factura (codigo_concepto, rif_empresa, codigo_categoria, codigo_marca,codigo_almacen, nombre_concepto, costo_unitario, precio_venta, status_stock,stock,stock_minimo,excento, iva_producto, alicuota_producto) values ('$codigo_concepto', '$rif_empresa', '$codigo_categoria', '$codigo_marca','$codigo_almacen','$nombre_concepto', '$costo_unitario','$precio_venta','$status_stock','$stock','$stock_minimo','$excento','$iva_producto','$alicuota_producto')";	
            $result = pg_query($query)or die(pg_last_error());
            
            if(pg_affected_rows($result)){
                $error="bien";
            }
        } else {
            $error="Error";
            $div_menssage='<div align="left">
                                <h3 class="error">
                                    <font color="red" style="text-decoration:blink;">
                                        Error: Ya Existe un Registro con el Código: <font color="blue">'.$codigo_concepto.'</font>; por favor verifique los datos!
                                    </font>
                                </h3>
                            </div>';	
        }
    }//fin del add        
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
        
        <table class="adminconcepto">
            <tr>
                <th>
                    REGISTRO DE CONCEPTO
                </th>
            </tr>
        </table>

        <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr bgcolor="#55baf3">
                    <th colspan="2">
                        Registro de Nuevo Concepto
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
                                            window.location="?view=concepto";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=concepto" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>
			
                <?php }else{ ?> 
			
                <tr>
                   <td  colspan="2"   height="18">
                       <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                    </td>
                </tr>

                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Concepto:</b></td>
                </tr>
                
	            <tr>
                    <td width="10%">
                        Codigo de Concepto: <font color="Red">(*)</font>
                    </td>

                    <td width="90%">
                        <input class="validate[required] text-input" autofocus="true"  type="text" id="codigo_concepto" name="codigo_concepto" value="<?php if ($error!='') echo $codigo_concepto;?>"  size="25" maxlength="30"/>	
                    </td>
                </tr>

                <tr>
                    <td>
                        Empresa: <font color="Red">(*)</font>
                    </td>

                    <td align="left">
                        <select id="rif_empresa" name="rif_empresa" class="validate[required]" size="0">
                            <option value="">----</option>	        
                            <?php
                                $consulta=pg_query("select * from empresa order by nombre_empresa");
                                while ($array_consulta=pg_fetch_array($consulta)){
                                    if ($array_consulta[0]==$rif_empresa){
                                        echo '<option value="'.$array_consulta[0].'"  selected="selected">'.$array_consulta[1].'</option>';																			
                                    }else{
                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';																			
                                    }
                                }
                                pg_free_result($consulta);
                            ?>
                        </select>
                    </td>
                </tr>
	
                <tr>
                    <td>
                        Nombre del Concepto: <font color="Red">(*)</font>
                    </td>

                    <td>
                        <input class="validate[required] text-input" type="text" id="nombre_concepto" name="nombre_concepto" value="<?php if ($error!='') echo $nombre_concepto;?>"  size="80" maxlength="100"/>	
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Categoria: <font color="Red">(*)</font>
                    </td>

                    <td align="left">
                        <select id="codigo_categoria" name="codigo_categoria" class="validate[required]" size="0">
                            <option value="">----</option>	        
                            <?php
                                $consulta=pg_query("select * from categoria_concepto order by nombre_categoria");
                                while ($array_consulta=pg_fetch_array($consulta)){
                                    if ($array_consulta[0]==$codigo_categoria){
                                        echo '<option value="'.$array_consulta[0].'"  selected="selected">'.$array_consulta[1].'</option>';																			
                                    }else{
                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';																			
                                    }
                                }
                                pg_free_result($consulta);
                            ?>
                        </select>
                    </td>
                </tr>
            
                <tr>
                    <td>
                        Marca: <font color="Red">(*)</font>
                    </td>

                    <td align="left">
                        <select id="codigo_marca" name="codigo_marca" class="validate[required]" size="0">
                            <option value="">----</option>	        
                            <?php
                                $consulta=pg_query("select * from marca_concepto order by nombre_marca");
                                while ($array_consulta=pg_fetch_array($consulta)){
                                    if ($array_consulta[0]==$codigo_marca){
                                        echo '<option value="'.$array_consulta[0].'"  selected="selected">'.$array_consulta[1].'</option>';																			
                                    }else{
                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';																			
                                    }
                                }
                                pg_free_result($consulta);
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        Ubicación: <font color="Red">(*)</font>
                    </td>

                    <td align="left">
                        <select id="codigo_almacen" name="codigo_almacen" class="validate[required]" size="0">
                            <option value="">----</option>	        
                            <?php
                                $consulta=pg_query("select * from almacen_concepto order by nombre_almacen");
                                while ($array_consulta=pg_fetch_array($consulta)){
                                    if ($array_consulta[0]==$codigo_almacen){
                                        echo '<option value="'.$array_consulta[0].'"  selected="selected">'.$array_consulta[1].'</option>';																			
                                    }else{
                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';																			
                                    }
                                }
                                pg_free_result($consulta);
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        Costo Unitario: <font color="Red">(*)</font>
                    </td>

                    <td>
                        <table border="0" >
                            <tbody>
                                <tr>
                                <input type="hidden" id="var_alicuota" name="var_alicuota" value="<?php echo $var_alicuota;?>"/>
                                <input type="hidden" id="var_iva" name="var_iva" value="<?php echo $var_iva;?>"/>

                                    <td>
                                        <input  style="text-align:right" type="text" id="costo_unitario" class="validate[required,custom[number]] text-input"  name="costo_unitario" onKeyPress="return(ue_formatonumero(this,'','.',event));" onfocus="CalcularPrecioVenta()" onblur="CalcularPrecioVenta()" value="<?php if ($error!='') echo $costo_unitario;?>" maxlength="15" size="15" placeholder="0.00"  />
                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Costo Unitario','Ingrese el monto incluyendo los decimales. ej: 1300.00, El separador decimal es colocado automáticamente por el sistema.',' (Campo Opcional)')">       		
                                    </td>

                                    <td>
                                        Monto Alicuota: <font color="Red">(*)</font>
                                        <input  style="text-align:right" type="text" id="alicuota" class="validate[custom[number]] text-input"  name="alicuota" onKeyPress="return(ue_formatonumero(this,'','.',event)); " onfocus="CalcularPrecioVenta()" onblur="CalcularPrecioVenta()"  maxlength="15" size="15" placeholder="0.00" />
                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, '% Alicuota','Ingrese el % incluyendo los decimales. ej: 1300.00, El separador decimal es colocado automáticamente por el sistema.',' (Campo Opcional)')">       		
                                    </td>

                                    <td>
                                        Monto IVA: <font color="Red">(*)</font>
                                        <input  style="text-align:right" type="text" id="iva" class="validate[custom[number]] text-input"  name="iva" onKeyPress="return(ue_formatonumero(this,'','.',event)); " onfocus="CalcularPrecioVenta()" onblur="CalcularPrecioVenta()"  maxlength="15" size="15" placeholder="0.00" />
                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, '% Alicuota','Ingrese el % incluyendo los decimales. ej: 1300.00, El separador decimal es colocado automáticamente por el sistema.',' (Campo Opcional)')">              
                                    </td>

                                    <td>
                                        Precio Venta: <font color="Red">(*)</font>
                                        <input  style="text-align:right" type="text" id="precio_venta" class="validate[required,custom[number]] text-input"  name="precio_venta" onKeyPress="return(ue_formatonumero(this,'','.',event));"  value="<?php if ($error!='') echo $precio_venta;?>" maxlength="15" size="15" placeholder="0.00" />
                                        <img src="images/ayuda.png" width="16" height="16" alt="Ayuda" onmouseover="muestraAyuda(event, 'Precio Venta','Ingrese el monto incluyendo los decimales. ej: 1300.00, El separador decimal es colocado automáticamente por el sistema.',' (Campo Opcional)')">       		
                                    </td>

                                    <td>
                                        Excento IVA:
                                        <select id="excento_iva" name="excento_iva" onclick="CalcularPrecioVenta()">
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>	
                </tr>
            
            
                <tr colspan="4">
                    <td>
                        Seguimiento Stock:
                    </td>
                    <td>
                        <table border="0" >
                            <tbody>
                                <tr>
                                    <td>
                                        <?php
                                            if ($error!=""){
                                                if($status_stock==1){																					
                                                    echo '<input type="checkbox"  name="status_stock" id="status_stock" onchange="activar_stock()" checked="true" />';
                                                }else {
                                                    echo '<input type="checkbox"  name="status_stock" id="status_stock" onchange="activar_stock()"/>';
                                                }
                                            }else{
                                                echo '<input type="checkbox"  name="status_stock" id="status_stock" onchange="activar_stock()" checked="true"/>';
                                            }
                                        ?>
                                        
                                    </td>
                                    
                                    <td id="contenedor_stock">
                                        Stock Actual:
                                        <input  style="text-align:right" type="text" id="stock" class="validate[custom[integer]] text-input"  name="stock"  value="<?php if ($error!='') echo $stock; else echo '0';?>" maxlength="10" size="6" placeholder="0" />

                                        Stock Minimo: <font color="Red">(*)</font>
                                        <input  style="text-align:right" type="text" id="stock_minimo" class="validate[custom[integer]min[1]] text-input"  name="stock_minimo" value="<?php if ($error!='') echo $stock_minimo; else echo '1';?>" maxlength="10" size="6" placeholder="0" />
                                    </td>
                                    
<!--                                <?php if ($error!='') echo $stock_minimo; else echo '1';?>" maxlength="10" size="6" placeholder="0" />
                                    </td>-->
                                    
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
		
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=concepto'" value="Cerrar" name="cerrar" />  
                    </td>													
                </tr> 
            </table>
        </form>
        <?php } ?> 
    </div>
</div>
