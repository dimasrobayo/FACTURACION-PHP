<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    // RECIBIENDO LOS VALORES
    if (isset($_POST["fecha_cierre"])){
	$fecha_cierre=$_POST["fecha_cierre"];
        $cajero=$_POST["cajero"];
        $cuenta=$_POST["cuenta"];
        $ndeposito=$_POST["n_deposito"];
       
        
    }
    if (isset($_GET["fecha_cierre"])){
	$fecha_cierre=$_GET["fecha_cierre"];
        $cajero=$_GET["cajero"];
        $cuenta=$_POST["cuenta"];
        $ndeposito=$_POST["n_deposito"];
        
    }
    
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
    //$pdf->SetFillColor(200,220,255); //AZUL
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
    $pdf->SetLeftMargin(130);
    $pdf->SetY(20);
    
    $pdf->Cell(0,6,utf8_decode('CIERRE DIARIO DE FACTURACIÓN'),0,1,'C',0);
    
    $pdf->Cell(0,6,utf8_decode("FECHA: ".$fecha_cierre),0,0,'C',0);//
    $pdf->SetY(43);
    $pdf->SetX(10);
    $pdf->SetLeftMargin(10);
    
    //
    $query= "select * from cuenta,banco where cuenta.codigo_banco=banco.codigo_banco order by n_cuenta";
    $result = pg_query($query);
    $resultados=pg_fetch_array($result);
    pg_free_result($result);
    
    $pdf->Ln(0);
    $pdf->MultiCell(200,6,"RELACION DE DEPOSITO DE LA CUENTA Nro. ".$resultados[n_cuenta],0,C,0);//
    $pdf->MultiCell(200,6,"Y RECIBO DE CAJA INGRESOS PROPIOS",0,C,0);//
    $pdf->MultiCell(200,6,"UNELLEZ-GUANARE",0,C,0);//
    $pdf->SetX(10);
    $pdf->Ln(6); 
    $pdf->MultiCell(200,6,"DEPOSITO Nro. ".$ndeposito,0,C,0);//
    $pdf->SetFont('Arial','B',7);
    $pdf->SetX(10);
    $pdf->Ln(3);  



//MOSTRAR FACTURAS
    $pdf->Ln(3);
    $pdf->Cell(1);

    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',9);

    $pdf->Cell(22,5,utf8_decode("N° RECIBO"),1,0,'C',1);    
    $pdf->Cell(110,5,"DESCRIPCION",1,0,'C',1);
    $pdf->Cell(25,5,"PRECIO",1,0,'C',1);
    $pdf->Cell(12,5,"CANT",1,0,'C',1);
    $pdf->Cell(25,5,"TOTAL",1,0,'C',1);
    $pdf->Ln(5);

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    $pdf->SetFont('Arial','',8);

    $query = "Select * from factura, detalle_factura,concepto where factura.fecha_factura = '$fecha_cierre' and factura.n_factura = detalle_factura.n_factura and detalle_factura.codigo_concepto=concepto.codigo_concepto";
    $result_detalle = pg_query($query);

    $contador=1;
    while ($row=pg_fetch_array($result_detalle)){
        $pdf->Cell(1);
        $contador++;

        $n_factura=$row["n_factura"];
        $pdf->Cell(22,5,$row["n_factura"],'LR',0,'C');
        $pdf->Cell(110,5,utf8_decode($row["codigo_concepto"].' - '.$row["nombre_concepto"]),'LR',0,'L');
        $precio= $row["monto_concepto"];
        $pdf->Cell(25,5,number_format($precio,2,",","."),'LR',0,'R');
        $cantidad=$row["cantidad"];
        $pdf->Cell(12,5,$row["cantidad"],'LR',0,'C');
        $importe= $precio*$cantidad;
        $pdf->Cell(25,5,number_format($importe,2,",","."),'LR',0,'R');
        $pdf->Ln(5);

        //vamos acumulando el importe
        $importet=$importet+$importe;

        $contador=$contador + 1;
        $lineas=$lineas + 1;

//        if ($lineas % 43 == 0){
//            $pdf->AddPage();
//
//            //ahora mostramos las lineas de la factura
//            $pdf->Ln(30);
//            $pdf->Cell(1);
//
//            $pdf->SetFillColor(255,191,116);
//            $pdf->SetTextColor(0);
//            $pdf->SetDrawColor(0,0,0);
//            $pdf->SetLineWidth(.2);
//            $pdf->SetFont('Arial','B',8);
//
//            $pdf->Cell(20,5,"N° Factura",1,0,'C',1);           
//            $pdf->Cell(20,5,"Cantidad",1,0,'C',1);
//            $pdf->Cell(100,5,"Descripcion",1,0,'C',1);
//            $pdf->Cell(25,5,"Precio",1,0,'C',1);
//            $pdf->Cell(25,5,"Total",1,0,'C',1);
//            $pdf->Ln(5);
//
//
//            $pdf->SetFillColor(224,235,255);
//            $pdf->SetTextColor(0);
//            $pdf->SetDrawColor(0,0,0);
//            $pdf->SetLineWidth(.2);
//            $pdf->SetFont('Arial','',8);
//        }

    };

//    while ($contador<30){
//        $pdf->Cell(1);
//        $pdf->Cell(20,5,"",'LR',0,'C');
//        $pdf->Cell(20,5,"",'LR',0,'C');
//        $pdf->Cell(100,5,"",'LR',0,'C');
//        $pdf->Cell(25,5,"",'LR',0,'C');
//        $pdf->Cell(25,5,"",'LR',0,'C');
//        $pdf->Ln(5);
//        $contador=$contador +1;
//    }

    $pdf->Cell(1);
    $pdf->Cell(22,0,"",'LRB',0,'C');    
    $pdf->Cell(110,0,"",'LRB',0,'C');
    $pdf->Cell(25,0,"",'LRB',0,'C');
    $pdf->Cell(12,0,"",'LRB',0,'C');
    $pdf->Cell(25,0,"",'LRB',0,'C');
    $pdf->Ln(2);


//ahora mostramos el final de la factura
    $pdf->Ln(5);
    
    $iva=$resultados["iva"];
    $importe_iva=$importet*($iva/100);
    $importe_iva=sprintf("%01.2f", $importe_iva);
    $total=$importet+$importe_iva;
    $total=sprintf("%01.2f", $total);
    
    $pdf->SetFont('','B');
    $pdf->Cell(145,5,utf8_decode('Total en Letras: '),1,0,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(25,5,"Sub-Total",1,0,'C',0);
    $pdf->SetFont('');
    $pdf->Cell(25,5,number_format($importet,2,",","."),1,1,'R',0);
    $pdf->Cell(145,5,utf8_decode(numtoletras($total)),1,0,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(25,5,"TOTAL Bs. ",1,0,'C',0);
    $pdf->SetFont('');
    $pdf->Cell(25,5,number_format($total,2,",","."),1,1,'R',0);
    
//    $pdf->Cell(145);
//    $pdf->SetFont('','B');
//    $pdf->Cell(25,5,"TOTAL Bs. ",1,0,'C',0);
//    $pdf->SetFont('');
//    $pdf->Cell(25,5,number_format($total,2,",","."),1,1,'R',0);

    $pdf->Ln(20);
    $pdf->SetX(0);
    $pdf->SetLeftMargin(10);
    $pdf->Cell(65,5,utf8_decode($resultados_empresa['nombre_administrador']),'T',0,'C');
    $pdf->Cell(65,5,"",0,0,'C');
    $pdf->Cell(65,5,utf8_decode($cajero),'T',0,'C');
    $pdf->Ln(3);
    $pdf->Cell(65,5,utf8_decode('Administrador'),0,0,'C');
    $pdf->Cell(65,5,"",0,0,'C');
    $pdf->Cell(65,5,utf8_decode('Cajero'),0,0,'C');
    
    pg_close();
    $pdf->Output("Factura ".$codfactura.".pdf","I");
?>
