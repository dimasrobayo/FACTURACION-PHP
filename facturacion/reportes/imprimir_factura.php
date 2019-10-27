<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones_factura.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    // RECIBIENDO LOS VALORES
    $codfactura=$_GET["codfactura"];

    $query= "Select *, lpad(to_char(factura.n_factura,'0999999'),8,'0') AS codfactura from factura,solicitantes,municipio,programa,subprograma,tipo_solicitante,usuarios where factura.n_factura='$codfactura' and factura.cedula_solicitante=solicitantes.cedula_solicitante and municipio.codigo_municipio=solicitantes.codigo_municipio and programa.id_programa=solicitantes.id_programa and subprograma.id_subprograma=solicitantes.id_subprograma and tipo_solicitante.id_tipo_solicitante=solicitantes.id_tipo_solicitante and factura.cod_usuario=usuarios.cedula";
    $result = pg_query($query);
    $resultados=pg_fetch_array($result);
    pg_free_result($result);
    
    
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
    $pdf->Image('../../logo_empresa/'.$resultados_empresa['rif_empresa']'.jpg',10,10,'30','','jpg','http://www.vpa.unellez.edu.ve');
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
    $pdf->SetLeftMargin(110);
    $pdf->SetY(20);
    
    $pdf->Cell(60,6,utf8_decode('FECHA DE EMISION:'),0,0,'R',0);
    $pdf->Cell(0,6,implode('/',array_reverse(explode('-',$resultados['fecha_factura']))),0,1,'C',0);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(60,6,utf8_decode('FACTURA Nº:'),0,0,'R',0);//
    $pdf->SetFontSize(16);
    $pdf->Cell(0,6,utf8_decode($resultados['codfactura']),0,0,'C',0);//
    $pdf->SetY(43);
    $pdf->SetX(10);
    $pdf->SetLeftMargin(10);

    
    //DATOS DEL CLIENTE
    
    $pdf->Ln(1);
    $pdf->SetFontSize(12);
    $pdf->SetFont('','B');
    $pdf->Cell(34,5,utf8_decode('C.I. o Pasaporte: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(35,5,$resultados['cedula_solicitante'],0,0,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(42,5,utf8_decode('Nombre y Apellidos: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode($resultados['nombre_solicitante']." ".$resultados['apellido_solicitante']),0,1,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(22,5,utf8_decode('Dirección: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode($resultados['direccion_solicitante']),0,1,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(40,5,utf8_decode('Tipo de Solicitante: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(80,5,$resultados['descripcion_tipo_solicitante'],0,0,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(16,5,utf8_decode('Nucleo: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,$resultados['nombre_municipio'],0,1,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(18,5,utf8_decode('Carrera: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode($resultados['descripcion_subprograma']),0,1,'L',0);
    $pdf->SetFont('','B');


    //MOSTRAR FACTURA
    $pdf->Ln(5);
    $pdf->Cell(1);

    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',12);

    $pdf->Cell(20,5,"Cantidad",1,0,'C',1);
    $pdf->Cell(120,5,"Descripcion",1,0,'C',1);
    $pdf->Cell(25,5,"Precio",1,0,'C',1);
    $pdf->Cell(25,5,"Total",1,0,'C',1);
    $pdf->Ln(5);
    
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',10);

    $query = "Select * from detalle_factura,concepto where detalle_factura.n_factura='$codfactura' and detalle_factura.codigo_concepto=concepto.codigo_concepto";
    $result_detalle = pg_query($query);

    $contador=1;
    while ($row=pg_fetch_array($result_detalle)){
        $pdf->Cell(1);
        $contador++;
        $cantidad=$row["cantidad"];
        $pdf->Cell(20,5,$row["cantidad"],'LR',0,'C');
        $pdf->Cell(120,5,utf8_decode($row["codigo_concepto"].' - '.$row["nombre_concepto"]),'LR',0,'L');
        $precio= $row["monto_concepto"];
        $pdf->Cell(25,5,number_format($precio,2,",","."),'LR',0,'R');
        $importe= $precio*$cantidad;
        $pdf->Cell(25,5,number_format($importe,2,",","."),'LR',0,'R');
        $pdf->Ln(5);
        //vamos acumulando el importe
        $importet=$importet+$importe;

        if ($contador % 7 == 0){
            $pdf->Cell(1);
            $pdf->Cell(20,5,"",'T',0,'C');
            $pdf->Cell(120,5,"",'T',0,'C');
            $pdf->Cell(25,5,"",'T',0,'C');
            $pdf->Cell(25,5,"",'T',0,'C');
            $pdf->Ln(5);
            
            $pdf->Cell(0,5,utf8_decode('Continúa Siguiente Página...'),0,0,'L');
            $pdf->AddPage();
            //ahora mostramos las lineas de la factura
            
            $pdf->Cell(1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('Arial','B',12);

            $pdf->Cell(20,5,"Cantidad",1,0,'C',1);
            $pdf->Cell(120,5,"Descripcion",1,0,'C',1);
            $pdf->Cell(25,5,"Precio",1,0,'C',1);
            $pdf->Cell(25,5,"Total",1,0,'C',1);
            $pdf->Ln(5);

            $pdf->SetFillColor(224,235,255);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('Arial','',10);
        }

    };

    while ($contador<6){
        $pdf->Cell(1);
        $pdf->Cell(20,5,"",'LR',0,'C');
        $pdf->Cell(120,5,"",'LR',0,'C');
        $pdf->Cell(25,5,"",'LR',0,'C');
        $pdf->Cell(25,5,"",'LR',0,'C');
        $pdf->Ln(5);
        $contador++;
    }

    $pdf->Cell(1);
    $pdf->Cell(20,5,"",'T',0,'C');
    $pdf->Cell(120,5,"",'T',0,'C');
    $pdf->Cell(25,5,"",'T',0,'C');
    $pdf->Cell(25,5,"",'T',0,'C');
    $pdf->Ln(5);


//ahora mostramos el final de la factura
    
    $iva=$resultados["iva"];
    $importe_iva=$importet*($iva/100);
    $importe_iva=sprintf("%01.2f", $importe_iva);
    $total=$importet+$importe_iva;
    $total=sprintf("%01.2f", $total);
    
    $pdf->SetFontSize(10);
    $pdf->SetFont('','B');
    $pdf->Cell(29,5,utf8_decode('TOTAL LETRAS:'),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(111,5,utf8_decode(numtoletras($total)),0,0,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(25,5,"SUB-TOTAL",1,0,'C',0);
    $pdf->Cell(25,5,number_format($importet,2,",","."),1,1,'R',0);
    $pdf->Cell(140);
    $pdf->SetFont('','B');
    $pdf->Cell(25,5,"IVA ".$resultados["iva"] . "%",1,0,'C',0);
    $pdf->SetFont('');
    $pdf->Cell(25,5,number_format($importe_iva,2,",","."),1,1,'R',0);
    
    $pdf->SetFontSize(12);
    $pdf->Cell(140);
    $pdf->SetFont('','B');
    $pdf->Cell(25,5,"TOTAL",1,0,'C',0);
    $pdf->Cell(25,5,number_format($total,2,",",".")." Bs.",1,1,'R',0);
    
    $pdf->SetFontSize(10);
    $pdf->Ln(5);
    $pdf->Cell(60);
    $pdf->Cell(75,5,utf8_decode('FACTURADO POR: '.$resultados['nombre_usuario'].' '.$resultados['apellido_usuario']),'T',1,'C');

    pg_close();
    $pdf->Output("Factura ".$codfactura.".pdf","I");
?>
