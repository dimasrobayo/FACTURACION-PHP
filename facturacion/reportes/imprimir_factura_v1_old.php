<?php
    define('FPDF_FONTPATH','font/');
    require('pg_table.php');
    include("comunes.php");
    include ("conectar.php");

    $pdf=new PDF();
    $pdf->Open();
    $pdf->AddPage();

    $query= "Select * from factura,cliente where factura.n_factura='$codfactura' and factura.cedula_cliente=cliente.cedula_cliente";
    $result = pg_query($query);
    $resultados=pg_fetch_array($result);
    pg_free_result($result);

//    $pdf->Cell(95);
//    $pdf->Cell(80,4,"$resultados[n_factura]",'',0,'C');
//    $pdf->Ln(4);

    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);

    $pdf->Cell(1);
    $pdf->Cell(0,0,'FACTURA');
    $pdf->SetX(10);

//
    //ahora mostramos las lineas de la factura
    $pdf->Ln(10);
    $pdf->Cell(1);

    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);

    $pdf->Cell(40,4,"Referencia",1,0,'C',1);
    $pdf->Cell(80,4,utf8_decode("Descripción"),1,0,'C',1);
    $pdf->Cell(20,4,"Cantidad",1,0,'C',1);
    $pdf->Cell(15,4,"Precio",1,0,'C',1);
    $pdf->Cell(15,4,"% Desc.",1,0,'C',1);
    $pdf->Cell(20,4,"Importe",1,0,'C',1);
    $pdf->Ln(4);
    
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);

    $query = "Select * from detalle_factura,concepto where detalle_factura.codigo_concepto=concepto.codigo_concepto";
    $result_detalle = pg_query($query);

    $contador=1;
    while ($row=pg_fetch_array($result_detalle)){
        $pdf->Cell(1);
        $contador++;

        $pdf->Cell(40,4,$row["codigo_concepto"],'LR',0,'L');
        $pdf->Cell(80,4,utf8_decode($row["nombre_concepto"]),'LR',0,'L');

        $cantidad=$row["cantidad"];
        $pdf->Cell(20,4,$row["cantidad"],'LR',0,'C');

        $precio= number_format($row["monto_concepto"],2,",",".");
        $pdf->Cell(15,4,$precio,'LR',0,'R');
        $pdf->Cell(15,4,"0 %",'LR',0,'C');
        $importe= number_format($precio*$cantidad,2,",",".");
        $pdf->Cell(20,4,$importe,'LR',0,'R');
        $pdf->Ln(4);

        //vamos acumulando el importe
        $importet=$importet+$importe;

        $contador=$contador + 1;
        $lineas=$lineas + 1;

        if ($lineas % 48 == 0){
            $pdf->AddPage();

            //ahora mostramos las lineas de la factura
            $pdf->Ln(30);
            $pdf->Cell(1);

            $pdf->SetFillColor(255,191,116);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('Arial','B',8);

            $pdf->Cell(40,4,"Referencia",1,0,'C',1);
            $pdf->Cell(80,4,"Descripcion",1,0,'C',1);
            $pdf->Cell(20,4,"Cantidad",1,0,'C',1);
            $pdf->Cell(15,4,"Precio",1,0,'C',1);
            $pdf->Cell(15,4,"% Desc.",1,0,'C',1);
            $pdf->Cell(20,4,"Importe",1,0,'C',1);
            $pdf->Ln(4);


            $pdf->SetFillColor(224,235,255);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('Arial','',8);
        }

    };

    while ($contador<35){
        $pdf->Cell(1);
        $pdf->Cell(40,4,"",'LR',0,'C');
        $pdf->Cell(80,4,"",'LR',0,'C');
        $pdf->Cell(20,4,"",'LR',0,'C');
        $pdf->Cell(15,4,"",'LR',0,'C');
        $pdf->Cell(15,4,"",'LR',0,'C');
        $pdf->Cell(20,4,"",'LR',0,'C');
        $pdf->Ln(4);
        $contador=$contador +1;
    }

    $pdf->Cell(1);
    $pdf->Cell(40,4,"",'LRB',0,'C');
    $pdf->Cell(80,4,"",'LRB',0,'C');
    $pdf->Cell(20,4,"",'LRB',0,'C');
    $pdf->Cell(15,4,"",'LRB',0,'C');
    $pdf->Cell(15,4,"",'LRB',0,'C');
    $pdf->Cell(20,4,"",'LRB',0,'C');
    $pdf->Ln(4);


//ahora mostramos el final de la factura
    $pdf->Ln(10);
    $pdf->Cell(36);

    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);


    $pdf->Cell(30,4,"Forma de pago",1,0,'C',1);
    $pdf->Cell(30,4,"Sub-Total",1,0,'C',1);
    $pdf->Cell(30,4,"Valor IVA",1,0,'C',1);
    $pdf->Cell(30,4,"IVA",1,0,'C',1);
    $pdf->Cell(35,4,"TOTAL",1,0,'C',1);
    $pdf->Ln(4);

    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);

    $pdf->Cell(36);
    $importet=number_format($importet,2,",",".");
    $pdf->Cell(30,4,$formadepago,1,0,'R',1);
    $pdf->Cell(30,4,$importet,1,0,'R',1);
    $pdf->Cell(30,4,$resultados["iva"] . "%",1,0,'C',1);

    $iva=$resultados["iva"];
    $importe_iva=$importet*($iva/100);
    $importe_iva=sprintf("%01.2f", $importe_iva);
    $importe_iva=number_format($importe_iva,2,",",".");
    
    $total=$importet+$importe_iva;
    $total=sprintf("%01.2f", $total);
    $total= number_format($total,2,",",".");
    
    $pdf->Cell(30,4,"$importe_iva",1,0,'R',1);
    $pdf->Cell(35,4,"$total"." Bs.",1,0,'R',1);
    $pdf->Ln(4);

    pg_close();
    $pdf->Output("Factura ".$codfactura.".pdf","I");
?>
