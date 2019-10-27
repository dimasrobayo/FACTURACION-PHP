<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina

    $pdf=new PDF('L','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
        
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'32','','jpg','http://www.portuguesa.gob.ve');
    $pdf->Image('./logo/logo_busportuguesa.png',200,10,'70','','png','http://busportuguesa.com.ve/');
    $pdf->Image('./logo/logo_proveduria_busportuguesa.png',210,20,'50','','png','http://busportuguesa.com.ve/');
    
    //$pdf->SetFillColor(200,220,255); //AZUL
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->SetLeftMargin(55);
    
    $pdf->SetY(20);
    $pdf->Cell(150,6,utf8_decode('LISTADO DE UNIDADES / DEPENDENCIAS'),0,0,'C',0);//
    
    $pdf->SetFontSize(9);
    $pdf->Line(10,38,269,38);
    $pdf->Ln(10);
    
    $pdf->SetLeftMargin(10);
    $pdf->SetX(10);
    $pdf->SetY(40);
    
    //Consulta
    $query = "SELECT * FROM unidades order by cod_unidad";
    $result_detalle = pg_query($query);
    $total_registros=  pg_num_rows($result_detalle);
    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->SetFont('Times','B',11);
    $pdf->SetLeftMargin(10);

    $pdf->SetFillColor(200,220,255);	
    $pdf->SetFont('Times','B',9);	
    $pdf->SetWidths(array(12,20,46,45,45,45,45));	//336 total ancho PAGINA HORIZONTAL
    $pdf->SetAligns(array('C','C','C','C','C','C','C'));    
    
    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("COD"),utf8_decode("SIGLAS"),utf8_decode("UNIDAD"),utf8_decode("RESPONSABLE"),utf8_decode("DIRECCIÓN"),utf8_decode("TELÉFONO"),utf8_decode("HORARIO")));
    $fill = false;
    $pdf->fill("$fill");
    $fila=1;
////////////////////////////////////////////////////////////////////////////////////
    while ($row = pg_fetch_array($result_detalle)){
        $pdf->SetFillColor(230,235,255);
        $pdf->SetFont('Times','',8);	
        $pdf->SetWidths(array(12,20,46,45,45,45,45));	//196 total ancho	
        $pdf->SetAligns(array('C','C','L','L','L','L','L'));
        $pdf->fill("$fill");
        
        $pdf->Row(array(utf8_decode($row[cod_unidad]),utf8_decode($row['siglas_unidad']),utf8_decode($row['nombre_unidad']),utf8_decode($row['responsable_unidad']),utf8_decode($row['direccion_unidad']),utf8_decode($row['telefono_1']).'--'.utf8_decode($row['telefono_2']),utf8_decode($row['horario_unidad'])));
    }  
    $pdf->Ln(1);
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(0,6,utf8_decode('TOTAL REGISTROS IMPRESOS: '.$total_registros),0,0,'',0);//
    
    pg_close();
    $pdf->Output("unidades.pdf","I");
?>
