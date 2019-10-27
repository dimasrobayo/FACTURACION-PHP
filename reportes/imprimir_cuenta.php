<?php

include("fpdf17/conectar.php");
include("fpdf17/funciones_factura.php");
require ("../funciones.php"); // llamado de funciones de la pagina

$rif_empresa=$_GET['rif_empresa'];
$query= "Select * from empresa";
$result = pg_query($query);
$resultados_empresa=pg_fetch_array($result);
pg_free_result($result);

$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Image('./logo/logo_unellez.jpg',10,10,'30','','jpg','http://www.vpa.unellez.edu.ve');
$pdf->SetLeftMargin(45);
$pdf->SetFillColor(200,200,200);//GRIS
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.1);

$pdf->SetFontSize(12);
$pdf->MultiCell(90,4,utf8_decode($resultados_empresa['nombre_empresa']),0,'C',0);//
$pdf->Ln(0);
$pdf->SetFontSize(16);
$pdf->MultiCell(90,6,utf8_decode($resultados_empresa['siglas_empresa']),0,'C',0);//
$pdf->Ln(0);
$pdf->SetFontSize(12);
$pdf->MultiCell(90,6,'RIF: '.$resultados_empresa['rif_empresa'],0,'C',0);//

$pdf->Ln(1);
$pdf->SetFontSize(9);
$pdf->Cell(16,6,utf8_decode('Dirección:'),'T',0,'L',0);//
$pdf->SetFont('');
$pdf->MultiCell(0,6,utf8_decode($resultados_empresa['direccion_empresa']),'T','J',0);
$pdf->Line(10,41,206,41);

$pdf->SetFont('Arial','B',12);
$pdf->SetLeftMargin(10);

$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetY(45);
$pdf->SetX(0);


//
$pdf->Ln(1);
$pdf->MultiCell(180,6,"PLANILLA DE LA CUENTA",0,C,0);//
//$pdf->Cell(1,1,utf8_decode('PLANILLA DE LA CUENTA'));
$pdf->SetFont('Arial','B',10);
$pdf->SetX(10);
$pdf->Ln(4);    


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
$pdf->SetWidths(array(70,40,40,40));	//196 para P; 259.4 para L
$pdf->SetAligns(array('C','C','C','C'));

$fill = true;
$pdf->fill("$fill");
$pdf->Row(array(utf8_decode("EMPRESA"),utf8_decode("BANCO"),utf8_decode("TIPO DE CUENTA"),utf8_decode("Nº CUENTA")));

//Restauracion de colores y fuentes
$pdf->SetFillColor(246,246,246);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','B',10);

//Buscamos y listamos los clientes
$consulta = "SELECT * FROM empresa, banco, tipo_cuenta, cuenta where empresa.rif_empresa = cuenta.rif_empresa and banco.codigo_banco = cuenta.codigo_banco and tipo_cuenta.codigo_tipo_cuenta = cuenta.codigo_tipo_cuenta order by cuenta.codigo_cuenta";
$query = pg_query($consulta);

while ($row = pg_fetch_array($query))
  {
    //DATOS PRINCIPALES		
    $empresa=utf8_decode($row['nombre_empresa']);
    $banco=utf8_decode($row['nombre_banco']);
    $tipo_cuenta=utf8_decode($row['nombre_tipo_cuenta']);	
    $ncuenta=utf8_decode($row['n_cuenta']);	

    $pdf->SetFillColor(230,235,255);
    $pdf->SetFont('Arial','',8);	
    $pdf->SetWidths(array(70,40,40,40));	//196 para P; 259.4 para L
    $pdf->SetAligns(array('C','L','L','L'));

    $fill = false;
    $pdf->fill("$fill");
    $pdf->Row(array($empresa,$banco,$tipo_cuenta,$ncuenta));			

  };
$pdf->Output("imprimir_cuenta.pdf","I");
?> 
