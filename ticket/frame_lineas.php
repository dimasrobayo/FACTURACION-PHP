<script language="javascript" src="../js/ajax.js"></script>
<!--<script type="text/javascript" src="../../js/lib_javascript.js"></script>-->

<script type="text/javascript" charset="utf-8">
     function eliminar_linea(codfacturatmp,codconcepto,tmonto){
        if (confirm(" Desea eliminar esta linea ? ")){
            parent.document.formulario_lineas.subtotal.value=parseFloat(parent.document.formulario_lineas.subtotal.value) - parseFloat(tmonto);
            var original=parseFloat(parent.document.formulario_lineas.subtotal.value);
            var result=Math.round(original*100)/100 ;
            parent.document.formulario_lineas.subtotal.value=result.toFixed(2);

            parent.document.formulario_lineas.totalimpuestos.value=parseFloat(result * parseFloat(parent.document.formulario_lineas.iva.value / 100));
            var original1=parseFloat(parent.document.formulario_lineas.totalimpuestos.value);
            var result1=Math.round(original1*100)/100 ;
            parent.document.formulario_lineas.totalimpuestos.value=result1.toFixed(2);
            var original2=parseFloat(result + result1);
            var result2=Math.round(original2*100)/100 ;
            parent.document.formulario_lineas.preciototal.value=result2.toFixed(2);
            
            document.getElementById("frame_datos").src="eliminar_linea.php?codfacturatmp="+codfacturatmp+"&codconcepto=" + codconcepto;
        }
    }
     
</script>

<link href="../css/estilos.css" type="text/css" rel="stylesheet">

<?php 
    require("../conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    //Resiviendo los Datos por el Metodo POST
    $codfacturatmp=$_POST["codfacturatmp"];
    $control=$_POST["control"];
    $retorno=0;

    if (!isset($codfacturatmp)) { 
        $codfacturatmp=$_GET["codfacturatmp"]; 
        $retorno=1; 
    }
    if ($retorno==0) {
        if ($control!=1) {
            $codigo_concepto=$_POST["codigo_concepto"];
            $cantidad=$_POST["cantidad"];
            $monto_concepto=$_POST["costo_unitario"];

            $query = "SELECT * FROM detalle_facturatmp WHERE n_factura='$codfacturatmp' AND codigo_concepto='$codigo_concepto'";
            $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());	
            $result_consulta=pg_fetch_array($result);
            pg_free_result($result);						

            if ($result_consulta[0]){
                $cantidad+=$result_consulta[cantidad];
                $query="UPDATE  detalle_facturatmp SET cantidad='$cantidad' WHERE n_factura='$codfacturatmp' AND codigo_concepto='$codigo_concepto'";				
                $result=pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());				
            }else {
                $query="INSERT INTO detalle_facturatmp (codigo_concepto,n_factura,cantidad,monto_concepto) VALUES ('$codigo_concepto','$codfacturatmp','$cantidad','$monto_concepto')";				
                $result=pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
            }
        }
    }
?>

<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0     >
<?php
    $query="SELECT * FROM concepto_factura,detalle_facturatmp WHERE detalle_facturatmp.n_factura='$codfacturatmp' AND detalle_facturatmp.codigo_concepto=concepto_factura.codigo_concepto";
    $result_lineas=pg_query($query) or die('La consulta fall&oacute;: ' . pg_last_error());
    
    for ($i = 0; $i < pg_num_rows($result_lineas); $i++) {
        $codigo_concepto=pg_result($result_lineas,$i,"codigo_concepto");
        $nombre_concepto=pg_result($result_lineas,$i,"nombre_concepto");
        $cantidad=pg_result($result_lineas,$i,"cantidad");
        $monto_concepto=pg_result($result_lineas,$i,"monto_concepto");
        $total=number_format($cantidad*$monto_concepto,2,".","");
        
        if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
            <tr class="<?php echo $fondolinea?>">
                <td align="center" width="3%"><?php echo $i+1?></td>
                <td align="center" width="9%"><?php echo $codigo_concepto?></td>
                <td width="29%"><?php echo $nombre_concepto?></td>
                <td align="center" width="5%"><?php echo $cantidad?></td>
                <td align="right" width="5%"><?php echo $monto_concepto?></td>
                <td align="right" width="5%"><?php echo $total?></td>
                <td align="center" width="2%"><a href="javascript:eliminar_linea(<?php echo $codfacturatmp?>,'<?php echo $codigo_concepto?>',<?php echo $total?>)"><img src="../images/borrar.png" border="0"></a></td>
            </tr>
    <?php } ?>
</table>

<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
    <ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>