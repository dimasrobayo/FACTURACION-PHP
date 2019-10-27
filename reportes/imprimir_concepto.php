<?php
include("fpdf17/conectar.php");
include("fpdf17/funciones_factura.php");
require ("../funciones.php"); // llamado de funciones de la pagina

$rif_empresa=$_GET['rif_empresa'];
$query= "Select * from empresa";
$result = pg_query($query);
$resultados_empresa=pg_fetch_array($result);
pg_free_result($result);

$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Image('./logo/logo_portuguesa.jpg',10,10,'82','','jpg','http://www.estadoportuguesa.com.ve');
$pdf->SetLeftMargin(45);
$pdf->SetFillColor(200,200,200);//GRIS
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.1);

$pdf->Cell(50);
$pdf->SetFontSize(12);
$pdf->MultiCell(90,4,utf8_decode($resultados_empresa['nombre_empresa']),0,'L',0);//
$pdf->Ln(0);

$pdf->Ln(1);

$pdf->Cell(50);
$pdf->SetFontSize(12);
$pdf->Cell(22,6,utf8_decode('Dirección:'),'T',0,'L',0);//
$pdf->SetFont('');
$pdf->MultiCell(0,6,utf8_decode($resultados_empresa['direccion_empresa']),'T','J',0);
$pdf->Line(10,38,350,38);

$pdf->SetFont('Arial','B',12);
$pdf->SetLeftMargin(8);

$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetY(40);
$pdf->SetX(0);


//
$pdf->Ln(1);
$pdf->MultiCell(350,6,"INVENTARIO GENERAL",0,C,0);//
$pdf->SetFont('Arial','B',10);
$pdf->SetX(10);
$pdf->Ln(3);    


//Colores, ancho de linea y fuente en negrita
$pdf->SetFillColor(85,186,243);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.3);
$pdf->SetFont('Arial','B',10);
	
//Cabecera
$pdf->SetFillColor(85,186,243);	
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',12);	
$pdf->SetWidths(array(28,65,30,30,30,25,25,25,25,25,30));	//196 para P; 259.4 para L
$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));

$pdf->Row(array(utf8_decode("CODIGO"),utf8_decode("CONCEPTO"),utf8_decode("CATEGORIA"),utf8_decode("GENERO"),utf8_decode("UBICACION"),utf8_decode("C / U"),utf8_decode("ALICUOTA"),utf8_decode("IVA"),utf8_decode("P.V.P"),utf8_decode("STOCK"),utf8_decode("STOCK MIN")));    
	
//Restauracion de colores y fuentes
$pdf->SetFillColor(246,246,246);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',10);

//Buscamos y listamos los clientes
$consulta = "SELECT concepto_factura.status AS status_concepto,* FROM empresa, concepto_factura,categoria_concepto,marca_concepto,almacen_concepto where empresa.rif_empresa = concepto_factura.rif_empresa and marca_concepto.codigo_marca = concepto_factura.codigo_marca and categoria_concepto.codigo_categoria = concepto_factura.codigo_categoria and almacen_concepto.codigo_almacen=concepto_factura.codigo_almacen";
$query = pg_query($consulta);

while ($row = pg_fetch_array($query))
  {
    //DATOS PRINCIPALES	
    $codigo_con=utf8_decode($row['codigo_concepto']);
    $concepto=utf8_decode($row['nombre_concepto']);
    $categoria=utf8_decode($row['nombre_categoria']);
    $marca=utf8_decode($row['nombre_marca']);
    $ubicacion=utf8_decode($row['nombre_almacen']);
    $costo_unitario=utf8_decode($row['costo_unitario']);
    $alicuota_producto=utf8_decode($row['alicuota_producto']);
    $iva_producto=utf8_decode($row['iva_producto']);
    $precio_venta=utf8_decode($row['precio_venta']);
    $stock=utf8_decode($row['stock']);
    $stock_minimo=utf8_decode($row['stock_minimo']);
    
    $pdf->SetFillColor(230,235,255);
    $pdf->SetFont('Arial','',8);	
    $pdf->SetWidths(array(28,65,30,30,30,25,25,25,25,25,30));	//196 para P; 259.4 para L
    $pdf->SetAligns(array('C','L','L','L','L','R','R','R','R','R','R'));

    $fill = false;
    $pdf->fill("$fill");

    if ($stock<=$stock_minimo){
        $pdf->SetTextColor(255, 57, 57); // establece el color del fondo de la celda
        $pdf->Row(array($codigo_con,$concepto,$categoria,$marca,$ubicacion,$costo_unitario,$alicuota_producto,$iva_producto,$precio_venta,$stock,$stock_minimo));
    }else{
        $pdf->SetTextColor(11, 11, 11); // establece el color del fondo de la celda
        $pdf->Row(array($codigo_con,$concepto,$categoria,$marca,$ubicacion,$costo_unitario,$alicuota_producto,$iva_producto,$precio_venta,$stock,$stock_minimo));
    }			

  };

$pdf->Ln(8);  

//Buscamos y listamos los clientes
$consulta0a = "SELECT SUM(stock) FROM concepto_factura WHERE codigo_categoria = '1'";
$query0a = pg_query($consulta0a);
$row0a=pg_fetch_array($query0a);

//Buscamos y listamos los clientes
$consulta2a = "SELECT SUM(stock) FROM concepto_factura WHERE codigo_categoria = '2'";
$query2a = pg_query($consulta2a);
$row2a=pg_fetch_array($query2a);

//Buscamos y listamos los clientes
$consulta4a = "SELECT SUM(stock) FROM concepto_factura WHERE codigo_categoria = '3'";
$query4a = pg_query($consulta4a);
$row4a=pg_fetch_array($query4a);

//Buscamos y listamos los clientes
$consulta6a = "SELECT SUM(stock) FROM concepto_factura WHERE codigo_categoria = '4'";
$query6a = pg_query($consulta6a);
$row6a=pg_fetch_array($query6a);

//Buscamos y listamos los clientes
$consulta8a = "SELECT SUM(stock) FROM concepto_factura WHERE codigo_categoria = '6'";
$query8a = pg_query($consulta8a);
$row8a=pg_fetch_array($query8a);

//Buscamos y listamos los clientes
$consulta10a = "SELECT SUM(stock) FROM concepto_factura WHERE codigo_categoria = '7'";
$query10a = pg_query($consulta10a);
$row10a=pg_fetch_array($query10a);



//Cabecera
$pdf->SetFillColor(85,186,243); 
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',12);  
$pdf->SetWidths(array(93,40,41,41,41,41,41));   //196 para P; 259.4 para L
$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));

$pdf->Row(array(utf8_decode("TOTALES DE JUGUETES POR EDAD"),utf8_decode("DE 0 A 2 AÑOS"),utf8_decode("DE 2 A 4 AÑOS"),utf8_decode("DE 4 A 6 AÑOS"),utf8_decode("DE 6 A 8 AÑOS"),utf8_decode("DE 8 A 10 AÑOS"),utf8_decode("DE 10 A 12 AÑOS")));    

//Cabecera
$pdf->SetFillColor(85,186,243); 
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',12);  
$pdf->SetWidths(array(40,41,41,41,41,41));   //196 para P; 259.4 para L
$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));

$pdf->Cell(93);
$pdf->Row(array(number_format($row0a[0]),number_format($row2a[0]-1000),number_format($row4a[0]-2000),number_format($row6a[0]-3000),number_format($row8a[0]-2000),number_format($row10a[0]-2000))); 

//Restauracion de colores y fuentes
$pdf->SetFillColor(246,246,246);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',10);

$pdf->Ln(3);  

//Buscamos y listamos los clientes
$consultani = "SELECT SUM(stock) FROM concepto_factura WHERE codigo_marca = '1'";
$queryni = pg_query($consultani);
$rowni=pg_fetch_array($queryni);

//Buscamos y listamos los clientes
$consultana = "SELECT SUM(stock) FROM concepto_factura WHERE codigo_marca = '2'";
$queryna = pg_query($consultana);
$rowna=pg_fetch_array($queryna);

//Buscamos y listamos los clientes
$consultaun = "SELECT SUM(stock) FROM concepto_factura WHERE codigo_marca = '7'";
$queryun = pg_query($consultaun);
$rowun=pg_fetch_array($queryun);

//Buscamos y listamos los clientes
$consultatl = "SELECT SUM(stock) FROM concepto_factura";
$querytl = pg_query($consultatl);
$rowtl=pg_fetch_array($querytl);

//Cabecera
$pdf->SetFillColor(85,186,243); 
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',12);  
$pdf->SetWidths(array(93,40,41,41,41,41,41));   //196 para P; 259.4 para L
$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));

$pdf->Row(array(utf8_decode("TOTALES DE JUGUETES POR GENERO"),utf8_decode("NIÑOS"),utf8_decode("NIÑAS"),utf8_decode("UNISEX"),utf8_decode("TOTAL GENERAL")));  

//Cabecera
$pdf->SetFillColor(85,186,243); 
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',12);  
$pdf->SetWidths(array(40,41,41,41,41,41));   //196 para P; 259.4 para L
$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));

$pdf->Cell(93);
$pdf->Row(array(number_format($rowni[0]-4000),number_format($rowna[0]-2000),number_format($rowun[0]-4000),number_format($rowtl[0]-10000)));   
    
//Restauracion de colores y fuentes
$pdf->SetFillColor(246,246,246);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',10);


$pdf->Output("imprimir_inventario.pdf","I");

?> 
