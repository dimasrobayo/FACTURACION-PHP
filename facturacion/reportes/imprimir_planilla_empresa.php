<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones_factura.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
        
    $rif_empresa=$_GET['rif_empresa'];
    $query= "Select * from empresa";
    $result = pg_query($query);
    $resultados_empresa=pg_fetch_array($result);
    pg_free_result($result);
    
    $pdf=new PDF('P','mm','Letter'); // Tamaño del Papel Personalizado
//    $pdf=new PDF('P','mm','MLetter');
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
    
       
   //consulta de filas
    $consulta = "SELECT * FROM empresa where empresa.rif_empresa = '$rif_empresa' order by empresa.rif_empresa";
    $resultado = pg_query($consulta);
    $lafila=pg_fetch_array($resultado);
    
    $pdf->SetY(43);
    $pdf->SetX(10);
    $pdf->SetLeftMargin(10);
    
    $pdf->Ln(3);
    $pdf->Cell(1,1,utf8_decode('Planilla de la Empresa'));
    $pdf->SetFont('Arial','B',10);
    $pdf->SetX(10);

    $pdf->Ln(5);
    $pdf->setfont('');
    $pdf->Cell(25,5,utf8_decode("Rif: "),0,0,'R');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,5,utf8_decode($lafila['rif_empresa']),0,1,'',0);
    $pdf->setfont('');
    $pdf->Ln(0);
    $pdf->setfont('');
    $pdf->Cell(25,5,utf8_decode("Empresa: "),0,0,'R');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,5,utf8_decode($lafila['nombre_empresa']),0,1,'',0);
    $pdf->setfont('');
    $pdf->Ln(0);
    $pdf->setfont('');
    $pdf->Cell(25,5,utf8_decode("Direccion: "),0,0,'R');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,5,utf8_decode($lafila['direccion_empresa']),0,1,'',0);
    $pdf->setfont('');
    $pdf->Ln(0);
    $pdf->setfont('');
    $pdf->Cell(25,5,utf8_decode("Pagina Web: "),0,0,'R');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,5,utf8_decode($lafila['pagina_web']),0,1,'',0);
    $pdf->setfont('');

    
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
	$resultado2=pg_query($consulta) or die("error en la linea 42".pg_error());

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
    
    

    pg_close();
    $pdf->Output("Factura ".$codfactura.".pdf","I");
?>
