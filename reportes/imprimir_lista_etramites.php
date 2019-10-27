<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina

    $pdf=new PDF('P','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
        
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'32','','jpg','http://www.portuguesa.gob.ve');
    $pdf->Image('./logo/logo_busportuguesa.png',140,10,'70','','png','http://busportuguesa.com.ve/');
    $pdf->Image('./logo/logo_proveduria_busportuguesa.png',150,20,'50','','png','http://busportuguesa.com.ve/');
    
    //$pdf->SetFillColor(200,220,255); //AZUL
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->SetLeftMargin(55);
    
    $pdf->SetY(20);
    $pdf->Cell(90,6,utf8_decode('ESTADOS DE LOS TRAMITES'),0,0,'C',0);//
    
    $pdf->SetFontSize(9);
    $pdf->Line(10,38,206,38);
    $pdf->Ln(10);
    
    $pdf->SetLeftMargin(10);
    $pdf->SetX(10);
    $pdf->SetY(40);
    
    $query = "SELECT * FROM estados_tramites ORDER BY cod_estado_tramite";
    $result_detalle = pg_query($query);
    $total_registros=  pg_num_rows($result_detalle);
    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->SetFont('Times','B',11);
    $pdf->SetLeftMargin(10);

    $pdf->SetFillColor(200,220,255);	
    $pdf->SetFont('Times','B',10);	
    $pdf->SetWidths(array(10,12,15,147,12));	//196 total ancho
    $pdf->SetAligns(array('C','C','C','C','C'));    
    
    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("ID"),utf8_decode("EDO"),utf8_decode("SIGLAS"),utf8_decode("DESCRIPCIÓN"),utf8_decode("TIPO")));
    $fill = false;
    $pdf->fill("$fill");
////////////////////////////////////////////////////////////////////////////////////
    while ($row = pg_fetch_array($result_detalle)){
        $pdf->SetFillColor(230,235,255);
        $pdf->SetFont('Times','',10);	
        $pdf->SetWidths(array(10,12,15,147,12));	//196 total ancho	
        $pdf->SetAligns(array('C','C','C','L','C'));
        $pdf->fill("$fill");
        
        $pdf->Row(array(utf8_decode($row[cod_estado_tramite]),utf8_decode($row['estado_tramite']),utf8_decode($row['siglas_estado_tramite']),utf8_decode($row['descripcion_estado_tramite']),utf8_decode($row['tipo_estado_tramite'])));

    }  
    $pdf->Ln(1);
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(0,6,utf8_decode('TOTAL REGISTROS IMPRESOS: '.$total_registros),0,0,'',0);//

    pg_close();
    $pdf->Output("estados_tramites.pdf","I");
?>
