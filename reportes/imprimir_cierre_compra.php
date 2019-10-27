<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    // RECIBIENDO LOS VALORES
    if (isset($_POST["fecha_compra"])){
       $fecha_compra=$_POST["fecha_compra"];
        $cajero=$_POST["cajero"];   
    }
    if (isset($_GET["fecha_compra"])){
       $fecha_compra=$_GET["fecha_compra"];
        $cajero=$_GET["cajero"];
    }

    $query= "Select *, lpad(to_char(compra.codigo_compra,'0999999'),8,'0') AS codigo_compra from compra, solicitantes where compra.cedula_rif=solicitantes.cedula_rif";
    $result = pg_query($query);
    $resultados=pg_fetch_array($result);
    pg_free_result($result);
    
    $query= "Select * from empresa";
    $result = pg_query($query);
    $resultados_empresa=pg_fetch_array($result);
    pg_free_result($result);
    
    $pdf=new PDF('L','mm','Letter'); // Tamaño del Papel Personalizado
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
    
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo_evalsa.png',10,10,'38','','png','http://www.estadoportuguesa.com.ve');
    $pdf->SetLeftMargin(45);
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFontSize(14);
    $pdf->MultiCell(160,4,utf8_decode($resultados_empresa['nombre_empresa']),0,'C',0);//
    $pdf->Ln(0);
    $pdf->SetFontSize(9);
    
    $pdf->Ln(1);
    $pdf->SetFontSize(9);
    $pdf->Cell(16,6,utf8_decode('Dirección:'),'T',0,'L',0);//
    $pdf->SetFont('');
    $pdf->MultiCell(0,6,utf8_decode($resultados_empresa['direccion_empresa']),'T','J',0);
    $pdf->Line(10,41,270,41);

    $pdf->SetFont('Arial','B',12);
    $pdf->SetLeftMargin(130);
    $pdf->SetY(25);
    
    $pdf->Cell(0,6,utf8_decode('CIERRE DIARIO DE COMPRAS'),0,1,'C',0);
    
    $pdf->Cell(0,6,utf8_decode("FECHA CIERRE: ".$fecha_compra),0,0,'C',0);//
    $pdf->SetY(43);
    $pdf->SetX(10);
    $pdf->SetLeftMargin(7);
    
    //MOSTRAR FACTURAS
    $pdf->Ln(5);
    $pdf->Cell(1);

    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',9);

    $pdf->Cell(22,5,utf8_decode("N° FACTURA"),1,0,'C',1);    
    $pdf->Cell(12,5,"CANT",1,0,'C',1);
    $pdf->Cell(110,5,"DESCRIPCION",1,0,'C',1);
    $pdf->Cell(30,5,"PRECIO",1,0,'C',1);
    $pdf->Cell(30,5,"ALIC",1,0,'C',1);
    $pdf->Cell(30,5,"IVA",1,0,'C',1);
    $pdf->Cell(30,5,"TOTAL",1,0,'C',1);
    $pdf->Ln(5);

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',10);

    $query = "Select * from compra, detalle_compra,concepto_factura where compra.fecha_compra = '$fecha_compra' and compra.codigo_compra = detalle_compra.codigo_compra and detalle_compra.codigo_concepto=concepto_factura.codigo_concepto";
    $result_detalle = pg_query($query);

    $contador=1;
    while ($row=pg_fetch_array($result_detalle)){
        $pdf->Cell(1);
        $contador++;

        $n_factura=$row["n_factura"];
        $pdf->Cell(22,5,$row["n_factura"],'LR',0,'C');
        
        $cantidad=$row["cantidad"];
        $pdf->Cell(12,5,$row["cantidad"],'LR',0,'C');
        
        $pdf->Cell(110,5,utf8_decode($row["nombre_concepto"]),'LR',0,'L');

        $precio= $row["precio_venta"];
        $pdf->Cell(30,5,number_format($precio,2,",","."),'LR',0,'R');

        $alicuota= ($precio * $var_alicuota)/100;
        $pdf->Cell(30,5,number_format($alicuota,2,",","."),'LR',0,'R');

        $iva_producto= $row["iva_producto"];
        $iva_individual= ($cantidad * $iva_producto);
        $pdf->Cell(30,5,number_format($iva_individual,2,",","."),'LR',0,'R');

        $importe= ($precio*$cantidad);
        $pdf->Cell(30,5,number_format($importe,2,",","."),'LR',0,'R');
        $pdf->Ln(5);

        //vamos acumulando el importe
        $importet=$importet+$importe;

        $importe_iva=$importe_iva+$iva_individual;

        $contador=$contador + 1;
        $lineas=$lineas + 1;
    };

    $pdf->Cell(1);
    $pdf->Cell(22,0,"",'LRB',0,'C');    
    $pdf->Cell(12,0,"",'LRB',0,'C');
    $pdf->Cell(110,0,"",'LRB',0,'C');
    $pdf->Cell(30,0,"",'LRB',0,'C');
    $pdf->Cell(30,0,"",'LRB',0,'C');
    $pdf->Cell(30,0,"",'LRB',0,'C');
    $pdf->Cell(30,0,"",'LRB',0,'C');
    $pdf->Ln(0);


//ahora mostramos el final de la factura
    $pdf->Ln(0);

    $total=$importet+$importe_iva;
    $total=sprintf("%01.2f", $total);
    
    $pdf->SetFont('','B');

    $pdf->Cell(1);
    $pdf->Cell(204,5,utf8_decode('Total en Letras: '),1,0,'L',0);
    $pdf->SetFont('','B');

    $pdf->Cell(30,5,"Sub-Total",1,0,'C',0);
    $pdf->SetFont('');
    $pdf->Cell(30,5,number_format($importet,2,",","."),1,1,'R',0);

    $pdf->Cell(1);
    $pdf->Cell(204,5,utf8_decode(numtoletras($total)),1,0,'L',0);
    $pdf->SetFont('','B');

    $pdf->Cell(30,5,"IVA (".$resultados["iva"] . "%)",1,0,'R',0);
    $pdf->SetFont('');
    $pdf->Cell(30,5,number_format($importe_iva,2,",","."),1,1,'R',0);
    
    $pdf->Cell(1);
    $pdf->Cell(204);
    $pdf->SetFont('','B');
    $pdf->Cell(30,5,"TOTAL Bs. ",1,0,'C',0);
    $pdf->SetFont('');
    $pdf->Cell(30,5,number_format($total,2,",","."),1,1,'R',0);

    $pdf->Ln(25);
    $pdf->SetX(0);
    $pdf->SetLeftMargin(10);
    $pdf->Cell(65,5,utf8_decode($resultados_empresa['nombre_administrador']),'T',0,'C');
    $pdf->Cell(130,5,"",0,0,'C');
    $pdf->Cell(65,5,utf8_decode($cajero),'T',0,'C');
    $pdf->Ln(5);
    $pdf->Cell(65,5,utf8_decode('Administrador'),0,0,'C');
    $pdf->Cell(130,5,"",0,0,'C');
    $pdf->Cell(65,5,utf8_decode('Cajero'),0,0,'C');
    
    pg_close();
    $pdf->Output("Factura".$codfactura.".pdf","I");
?>
