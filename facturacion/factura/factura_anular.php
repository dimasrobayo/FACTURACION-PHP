<?php //ENRUTTADOR

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
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;
    
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_GET['n_factura'])){
        $n_factura= $_GET['n_factura'];

        $query="SELECT * FROM detalle_factura WHERE n_factura='$n_factura'";
        $result_detalles = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());

        while($resultados = pg_fetch_array($result_detalles)){
            $codconcepto=$resultados[codigo_concepto];
            $cantidad=$resultados[cantidad];

            $query="SELECT * FROM concepto_factura  WHERE codigo_concepto = '$codconcepto'";				
            $result = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
            $resultados_concepto=pg_fetch_array($result);
            pg_free_result($result);
            
            $status_stock=$resultados_concepto[status_stock];
            $stock=$resultados_concepto[stock]+$cantidad;
            
            if($status_stock==1){
                $query="UPDATE concepto_factura SET stock='$stock' WHERE codigo_concepto='$codconcepto'";								
                $result = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
                pg_free_result($result);
            }
        }


        //MODIFICAR ESTATUS DE LA FACTURA
        $query="UPDATE factura SET status=0 WHERE n_factura='$n_factura'";
        $result = pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
        pg_free_result($result);
        $error="bien";	
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
        <table class="admintramites" width="100%">
            <tr>
                <th>
                    FACTURACIÓN
                </th>
            </tr>
        </table>
        
        <table class="adminform" border="0" width="100%">
            <tr bgcolor="#55baf3">
                <th colspan="2">
                    ANULAR FACTURA
                </th>
            </tr>
			
            <tr>
                <td colspan="2" align="center">
                    <div align="center"> 
                        <h3 class="info">	
                            <font size="2">
                                <?php
                                    if ($error=="bien"){	
                                        echo 'Factura Nro: <font color="blue">'.$n_factura.'</font> Anulada con &eacute;xito';
                                    }
                                ?>
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
            
       </table>	
    </div>
</div>
