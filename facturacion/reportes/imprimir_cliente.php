<?php
define('FPDF_FONTPATH','font/');
require('pg_table.php');
include("comunes.php");
include ("conectar.php"); 

$pdf=new PDF();
$pdf->Open();
$pdf->AddPage();

$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetY(50);
$pdf->SetX(0);
$pdf->MultiCell(220,6,"LISTADO DE CLIENTE",0,C,0);//

$pdf->Ln();    

//T�tulos de las columnas
$header=array('CEDULA','NOMBRE','SEXO','TELEFONO');

	//Colores, ancho de linea y fuente en negrita
    $pdf->SetFillColor(85,186,243);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('Arial','B',10);
	
	//Cabecera
    $w=array(20,80,30,30);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
    $pdf->Ln();
	
	//Restauracion de colores y fuentes
    $pdf->SetFillColor(246,246,246);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',7);

	//Buscamos y listamos los clientes
	$consulta = "SELECT * FROM cliente order by cliente.cedula_cliente";
	$query = pg_query($consulta);

while ($row = pg_fetch_array($query))
  {
		//posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda
		//imprimo nombre, apellidos y localidad
		$pdf->Cell($w[0],6,utf8_decode($row["cedula_cliente"]),'LRTB',0,'L',$fill);
		$pdf->Cell($w[1],6,utf8_decode($row["nombre_cliente"] ." " .$row["apellido_cliente"]),'LRTB',0,'L',$fill);
		$pdf->Cell($w[2],6,utf8_decode($row["sexo"]),'LRTB',0,'L',$fill);
		$pdf->Cell($w[3],6,utf8_decode($row["telefono_ubicacion"]),'LRTB',0,'R',$fill);
		$pdf->Ln(); //Este es para alinear los campos de la base de datos en el pdf.
		$fill = !$fill;

  };
$pdf->Output();
?> 
