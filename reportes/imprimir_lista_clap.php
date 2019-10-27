<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina

    $pdf=new PDF('L','mm','Legal');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
        
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'40','','jpg','http://www.estadoportuguesa.com.ve');
    
    //$pdf->SetFillColor(200,220,255); //AZUL
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->SetLeftMargin(55);
    
    $pdf->SetY(20);
    $pdf->Cell(0,6,utf8_decode('LISTADO DE CLAP REGISTRADOS'),0,0,'C',0);//
    
    $pdf->SetFontSize(9);
    $pdf->Line(10,38,206,38);
    $pdf->Ln(10);
    
    $pdf->SetLeftMargin(2);
    $pdf->SetX(10);
    $pdf->SetY(40);
    
    $query = "SELECT * FROM clap,comunidades,parroquias,municipios,estados WHERE clap.idcom=comunidades.idcom and comunidades.codpar=parroquias.codpar and comunidades.codmun=parroquias.codmun and comunidades.codest=parroquias.codest and parroquias.codest=municipios.codest and parroquias.codmun=municipios.codmun and municipios.codest=estados.codest ORDER BY municipios.codmun";
    $result_detalle = pg_query($query);
    $total_registros=  pg_num_rows($result_detalle);
    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->SetFont('Times','B',12);
    $pdf->SetLeftMargin(2);

    $pdf->SetFillColor(200,220,255);	
    $pdf->SetFont('Times','B',10);	
    $pdf->SetWidths(array(10,45,35,30,35,40,38,42,22,24,30));	//196 total ancho
    $pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));    
    
    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("ID"),utf8_decode("NOMBRE DEL CLAP"),utf8_decode("JEFE COMUNIDAD"),utf8_decode("RESP. UBCH"),utf8_decode("RESP. UNAMUJER"),utf8_decode("RESP. F.F MIRANDA"),utf8_decode("RESP. MINCOMUNA"),utf8_decode("RESP. P. PRODUCTIVO"),utf8_decode("MUNICIPIO"),utf8_decode("PARROQUIA"),utf8_decode("COMUNIDAD")));
    $fill = false;
    $pdf->fill("$fill");
////////////////////////////////////////////////////////////////////////////////////
    while ($row = pg_fetch_array($result_detalle)){
        $pdf->SetFillColor(230,235,255);
        $pdf->SetFont('Times','',10);	
        $pdf->SetWidths(array(10,45,35,30,35,40,38,42,22,24,30));	//196 total ancho	
        $pdf->SetAligns(array('C','L','C','C','C','C','C','C','L','L','L'));
        $pdf->fill("$fill");
        
        $pdf->Row(array(utf8_decode($row[codigo_clap]),utf8_decode($row['nombre_clap']),utf8_decode($row['jefe_comunidad']),utf8_decode($row['resp_ubch']),utf8_decode($row['resp_unamujer']),utf8_decode($row['resp_francisco']),utf8_decode($row['resp_mincomuna']),utf8_decode($row['resp_pregonero']),utf8_decode($row['desmun']),utf8_decode($row['despar']),utf8_decode($row['descom'])));

    }  
    $pdf->Ln(1);
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(0,6,utf8_decode('TOTAL REGISTROS IMPRESOS: '.$total_registros),0,0,'',0);//

    pg_close();
    $pdf->Output("CLAP REGISTRADOS.pdf","I");
?>
