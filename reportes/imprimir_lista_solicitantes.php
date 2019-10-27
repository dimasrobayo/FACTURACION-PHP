<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina

    $pdf=new PDF('L','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
        
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'32','','jpg','http://www.esomep.com.ve');
    
    //$pdf->SetFillColor(200,220,255); //AZUL
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->SetLeftMargin(55);
    
    $pdf->SetY(20);
    $pdf->Cell(0,6,utf8_decode('LISTADO DE SOLICITANTES'),0,0,'C',0);//
    
    $pdf->SetFontSize(9);
    $pdf->Line(10,38,269,38);
    $pdf->Ln(10);
    
    $pdf->SetLeftMargin(10);
    $pdf->SetX(10);
    $pdf->SetY(40);
    
    //Consulta
    $query = "SELECT * FROM solicitantes,tipo_solicitantes WHERE solicitantes.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante order by cedula_rif";
    $result_detalle = pg_query($query);
    $total_registros=  pg_num_rows($result_detalle);

    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->SetFont('Times','B',11);
    $pdf->SetLeftMargin(10);

    $pdf->SetFillColor(200,220,255);	
    $pdf->SetFont('Times','B',9);	
    $pdf->SetWidths(array(17,45,35,12,20,45,45,40));	//336 total ancho PAGINA HORIZONTAL
    $pdf->SetAligns(array('C','C','C','C','C','C','C','C'));    
    
    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("CI/RIF"),utf8_decode("NOMBRE SOLICITANTE"),utf8_decode("TIPO"),utf8_decode("SEXO"),utf8_decode("FEC. NAC."),utf8_decode("DIRECCIÓN"),utf8_decode("TELÉFONOS"),utf8_decode("EMAIL")));
    $fill = false;
    $pdf->fill("$fill");
    $fila=1;
////////////////////////////////////////////////////////////////////////////////////
    while ($row = pg_fetch_array($result_detalle)){
        $pdf->SetFillColor(230,235,255);
        $pdf->SetFont('Times','',8);	
         $pdf->SetWidths(array(17,45,35,12,20,45,45,40));	//257 total ancho PAGINA HORIZONTAL	
        $pdf->SetAligns(array('C','L','L','C','L','L','L','L'));
        $pdf->fill("$fill");

	if ($row[sexo_solicitante]==1) {
	    $sexo='M'; 
	} else { $sexo='F'; }
        
        $pdf->Row(array(utf8_decode($row['cedula_rif']),utf8_decode($row['nombre_solicitante']),utf8_decode($row['descripcion_tipo_solicitante']),$sexo,implode('/',array_reverse(explode('-',$resultados[fecha_nac]))),utf8_decode($row['direccion_habitacion']),utf8_decode($row['telefono_fijo']).'--'.utf8_decode($row['telefono_movil']),utf8_decode($row['email'])));
    }  
    $pdf->Ln(1);
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(0,6,utf8_decode('TOTAL REGISTROS IMPRESOS: '.$total_registros),0,0,'',0);//
    
    pg_close();
    $pdf->Output("solicitantes.pdf","I");
?>
