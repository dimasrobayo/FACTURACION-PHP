<?php 
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    if (isset($_POST['codfacturatmp'])){
        $codfacturatmp=$_POST["codfacturatmp"];
        $cedula_solicitante=strtoupper($_POST['cedula_solicitante']);
        $cedula_rif_fac = preg_replace("/\s+/", "", $cedula_solicitante);
        $cedula_rif_fac = str_replace("-", "", $cedula_rif_fac); 
        $iva=$_POST["iva"];
        $cedula_usuario=$_SESSION['id'];
        $id_status = 1;

        $query=pg_query("insert into factura (cedula_rif,fecha_factura,hora_factura,iva,cedula_usuario,id_status) values ('$cedula_rif_fac','now()','now()','$iva','$cedula_usuario','$id_status') RETURNING n_factura") or die('La consulta fall&oacute;: ' . pg_last_error());
        $result_insert = pg_fetch_row($query);
        $codfactura = $result_insert[0];

        if(pg_affected_rows($query)){
            $query="SELECT * FROM detalle_facturatmp WHERE n_factura='$codfacturatmp'";
            $result=pg_query($query);
            $total_detalles=pg_num_rows($result);

            while($resultados = pg_fetch_array($result)){
                $codconcepto=$resultados[codigo_concepto];
                $cantidad=$resultados[cantidad];
                $monto_concepto=$resultados[monto_concepto];
                
                $query="INSERT INTO detalle_factura (codigo_concepto,n_factura,cantidad,monto_concepto) VALUES ('$codconcepto','$codfactura','$cantidad','$monto_concepto')";
                $result_insert_detalles=pg_query($query);
                                
                $query_concepto="SELECT stock FROM concepto WHERE codigo_concepto='$codconcepto'";
                $result_concepto = pg_query($query_concepto);
                $resultado_stock = pg_fetch_array($result_concepto);
                
                echo $stock_actual = $resultado_stock[0];
                $stock_total=($stock_actual-$cantidad);

                $update_stock = pg_query("SELECT update_stock('$codconcepto','$stock_total')") or die('La consulta fall&oacute;: '.pg_last_error());		
                $result_update=pg_fetch_array($update_stock);	
                $resultado_update=$result_update[0];
            }
            $query="DELETE FROM facturatmp WHERE n_factura='$codfacturatmp'";
            $result_delete_facturatmp=pg_query($query);
            
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
        
        <table class="adminfactura">
            <tr>
                <th class="adminfactura">
                    REGISTRO DE CONCEPTO
                </th>
            </tr>
        </table>   

        <br><br>       

        <table width="100%" class="adminform" align="center">
            <tr>
                <td colspan="2" align="center">                        	
                    <br />
                    <strong>Resultado</strong>: 
                    <?php 
                    echo 'Factura <strong>#'.$codfactura.'</strong>  Registrada con Exito';
                    ?>
                </td>
            </tr> 
            
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
                                </script> 						
                                [<a href="?view=factura" name="factura"> Continuar </a>]
                            </font>							
                        </h3>
                    </div> 
                </td>
            </tr>
            
            <tr align="center">
                <td width="100%" valign="top" align="center">
                    <div id="cpanel">
                        <div style="float:right;">
                            <div class="icon">
                                <a href="index2.php?view=factura">
                                    <img src="images/factura.png" alt="salir" align="middle"  border="0" />
                                    <span>Gestor de Datos</span>
                                </a>
                            </div>
                        </div>
                        <div style="float:right;">
                            <div class="icon">
                                <a href="reportes/imprimir_factura.php?codfactura=<?php echo $codfactura;?>" target="_blank">
                                    <img src="images/printer.png" alt="Imprimir" align="middle"  border="0" />
                                    <span>Imprimir</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>	
       <br />     
    </div>
</div>
