<?php
define('FPDF_FONTPATH','font/');
require('pg_table.php');
include("comunes.php");
include ("conectar.php");
$rif_empresa=$_GET['rif_empresa'];

	$pdf=new PDF();
	$pdf->Open();
	$pdf->AddPage();

//consulta de filas
	$consulta = "SELECT * FROM empresa where empresa.rif_empresa = '$rif_empresa' order by empresa.rif_empresa";
	$resultado = pg_query($consulta);
	$lafila=pg_fetch_array($resultado);

        

	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','B',12);
        
             
	$pdf->Cell(1,1,utf8_decode('Planilla de la Empresa'));
	$pdf->SetX(10);

	$pdf->Cell(95);
	$pdf->Ln(4);
	
	$pdf->Cell(95);
	$pdf->MultiCell(100,4,utf8_decode("Rif: " . $lafila["rif_empresa"]),0,'L',1);
	$pdf->Ln(0);
	
	$pdf->Cell(95);
	$pdf->MultiCell(100,4,utf8_decode("Empresa: " . $lafila["nombre_empresa"]),0,'L',1);
	$pdf->Ln(0);
	
	$pdf->Cell(95);
	$pdf->MultiCell(100,4,utf8_decode("Direccion Fiscal: " . $lafila["direccion_empresa"]),0,'L',1);
	$pdf->Ln(0);
	
	$pdf->Cell(95);
	$pdf->MultiCell(100,4,utf8_decode("Pagin Web: " . $lafila["pagina_web"]),0,'L',1);
	$pdf->Ln(0);

	//ahora mostramos las lineas de los detalles
	$pdf->Ln(10);
	
	//T�tulos de las columnas
	$header=array('ADMINISTRADOR','CIUDAD','TLF OFICINA','FAX','E-MAIL');

	$pdf->SetFillColor(85,186,243);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','',12);
	
//Cabecera
    $w=array(50,25,40,25,50);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
    $pdf->Ln();
    
	//Restauracion de colores y fuentes
    $pdf->SetFillColor(246,246,246);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',8);

//consulta para detalles
	$consulta2 = ("SELECT * FROM empresa where empresa.rif_empresa = '$rif_empresa' order by empresa.rif_empresa");
	$resultado2=pg_query($consulta2) or die("error en la linea 42".pg_error());

	while ($row=pg_fetch_array($resultado2))
	{
		$pdf->Cell($w[0],6,utf8_decode($row["nombre_administrador"]),'LRTB',0,'L',$fill);
		$pdf->Cell($w[1],6,utf8_decode($row["ciudad"]),'LRTB',0,'L',$fill);
		$pdf->Cell($w[2],6,utf8_decode($row["telefono_oficina"]),'LRTB',0,'L',$fill);
		$pdf->Cell($w[3],6,utf8_decode($row["telefono_fax"]),'LRTB',0,'L',$fill);
		$pdf->Cell($w[4],6,utf8_decode($row["correo_electronico"]),'LRTB',0,'L',$fill);
		$pdf->Ln(); //Este es para alinear los campos de la base de datos en el pdf.
		$fill = !$fill;
	};

$pdf->Output();
?>