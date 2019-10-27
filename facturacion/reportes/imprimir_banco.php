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
$pdf->SetLeftMargin(110);
$pdf->SetY(20);

$pdf->Cell(60,6,utf8_decode('FECHA:'),0,0,'R',0);
$pdf->Cell(0,6,implode('/',array_reverse(explode('-',$resultados['fecha_factura']))),0,1,'C',0);

$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetY(50);
$pdf->SetX(0);
$pdf->MultiCell(220,6,"LISTADO DEL BANCOS",0,C,0);//

$pdf->Ln();    


//T�tulos de las columnas
    $header=array('CODIGO','BANCOS');

    $pdf->SetFillColor(85,186,243);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('Arial','',12);
	
//Cabecera
    $w=array(20,60);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
    $pdf->Ln();
    
	//Restauracion de colores y fuentes
    $pdf->SetFillColor(246,246,246);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',8);

//Buscamos y listamos los clientes
	$consulta = "SELECT * FROM banco order by banco.codigo_banco";
	$query = pg_query($consulta);

while ($row = pg_fetch_array($query))
  {
		//posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda
		//imprimo nombre, apellidos y localidad
		$pdf->Cell($w[0],6,$row["codigo_banco"],'LRTB',0,'C',$fill);
		$pdf->Cell($w[1],6,utf8_decode($row["nombre_banco"]),'LRTB',0,'L',$fill);
		$pdf->Ln(); //Este es para alinear los campos de la base de datos en el pdf.
		$fill = !$fill;

  };
	
$pdf->Output();
?> 
