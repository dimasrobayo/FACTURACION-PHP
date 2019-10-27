<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones_factura.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    // RECIBIENDO LOS VALORES
    $codigo_nota=$_GET["codigo_nota"];

    $query= "Select *, lpad(to_char(nota_entrega.codigo_nota,'09999999'),9,'0') AS codigo_nota from nota_entrega,solicitantes,tipo_solicitantes,usuarios where nota_entrega.codigo_nota='$codigo_nota' and nota_entrega.cedula_rif=solicitantes.cedula_rif and  tipo_solicitantes.cod_tipo_solicitante=solicitantes.cod_tipo_solicitante and nota_entrega.cedula_usuario=usuarios.cedula_usuario";
    $result = pg_query($query);
    $resultados=pg_fetch_array($result);
    pg_free_result($result);
    
    $pdf=new PDF('P','mm','letter'); // Tamaño del Papel Personalizado
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
    
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo_portuguesa.jpg',10,10,'82','','jpg','http://www.estadoportuguesa.com.ve');
    $pdf->SetLeftMargin(30);
    //$pdf->SetFillColor(200,220,255); //AZUL
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);



//SECCION DE LOS DATOS DE LA EMPRESA
    $query= "Select * from empresa";
    $result = pg_query($query);
    $resultados_empresa=pg_fetch_array($result);
    pg_free_result($result);
    
    $pdf->SetFont('Arial','',8);
    $pdf->SetY(24);

    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(176,20,utf8_decode('NOTA DE ENTREGA'),0,0,'R',0);
   
    $pdf->SetLeftMargin(141);
    $pdf->SetY(15);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(40,5,utf8_decode('N°:'),0,0,'R',0);//
    $pdf->Cell(0,5,utf8_decode($resultados['codigo_nota']),0,1,'L',0);//
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,5,utf8_decode('FECHA:'),0,0,'R',0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,5,' '.implode('/',array_reverse(explode('-',$resultados['fecha_nota']))),0,1,'L',0);
    
    $pdf->SetY(36);
    $pdf->SetX(5);
    $pdf->SetLeftMargin(3);
    $pdf->Cell(0,1,'','T',1,'C',0);

    
//SECCION DE LOS DATOS DEL CLIENTE   
    $pdf->Ln(1);
    $pdf->SetFontSize(10);
    $pdf->SetFont('','B');

    $pdf->Cell(50,5,utf8_decode('NOMBRE O RAZÓN SOCIAL: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode($resultados['nombre_solicitante']),0,1,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(25,5,utf8_decode('CEDULA/RIF: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,substr_replace($resultados['cedula_rif'],'-',1,0),0,1,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(25,5,utf8_decode('DIRECCIÓN: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode($resultados['direccion_habitacion']),0,1,'L',0);
    
    
//SECCION MOSTRAR FACTURA
    $pdf->Ln(2);
    $pdf->Cell(1);

    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(12,5,"CANT",1,0,'C',1);
    $pdf->Cell(120,5,utf8_decode("DESCRIPCIÓN"),1,0,'C',1);
    $pdf->Cell(25,5,"C / U",1,0,'C',1);
    $pdf->Cell(25,5,utf8_decode("IVA"),1,0,'C',1);
    $pdf->Cell(25,5,"TOTAL",1,0,'C',1);
    $pdf->Ln(5);
    
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',10);

    $query = "Select * from detalle_nota,concepto_factura, marca_concepto where detalle_nota.codigo_nota='$codigo_nota' and detalle_nota.codigo_concepto=concepto_factura.codigo_concepto and concepto_factura.codigo_marca = marca_concepto.codigo_marca";
    $result_detalle = pg_query($query);

    //TOTAL DE JUGUETES
$consultatl = "SELECT SUM(cantidad) FROM detalle_nota WHERE detalle_nota.codigo_nota='$codigo_nota'";
$querytl = pg_query($consultatl);
$rowtl=pg_fetch_array($querytl);

    $contador=1;
    while ($row=pg_fetch_array($result_detalle)){
        $pdf->Cell(1);
        $contador++;
        $cantidad=$row["cantidad"];
        $pdf->Cell(12,5,$row["cantidad"],'LR',0,'C');
        $pdf->Cell(120,5,utf8_decode($row["nombre_concepto"].' - '.$row["codigo_concepto"]),'LR',0,'J');
        $precio=$row["costo_unitario"];
        $pdf->Cell(25,5,number_format($precio,2,",",".")." Bs",'LR',0,'R');
        $iva_producto=$row["iva_producto"];
        $total_iva=$iva_producto*$cantidad;
        $pdf->Cell(25,5,number_format(0,2,",","."),'LR',0,'R');
        $importe= $precio*$cantidad;
        $pdf->Cell(25,5,number_format(0,2,",",".")." Bs",'LR',0,'R');
        $pdf->Ln(5);
        //vamos acumulando el importe
        $importet=$importet+$importe;
        $importeiva=$total_iva+$importeiva;
    };

    while ($contador<6){
        $pdf->Cell(1);
        $pdf->Cell(12,5,"",'LR',0,'C');
        $pdf->Cell(120,5,"",'LR',0,'C');
        $pdf->Cell(25,5,"",'LR',0,'C');
        $pdf->Cell(25,5,"",'LR',0,'C');
        $pdf->Cell(25,5,"",'LR',0,'C');
        $pdf->Ln(5);
        $contador++;
    }

    $pdf->Cell(1);
    $pdf->Cell(12,5,"",'T',0,'C');
    $pdf->Cell(120,5,"",'T',0,'C');
    $pdf->Cell(25,5,"",'T',0,'C');
    $pdf->Cell(25,5,"",'T',0,'C');
    $pdf->Cell(25,5,"",'T',0,'C');
    $pdf->Ln(0);


//SECCION FINAL DE LA FACTURA
    $pdf->SetFontSize(10);
    $pdf->SetFont('','B');

    $iva=$var_iva;
    $importe_iva=$total_iva*($iva/100);
    $importe_iva=sprintf("%01.2f", $importe_iva);
    $total=$importet+$importeiva;
    $total=sprintf("%01.2f", $total);
    $combo=$resultados['n_combo'];
    $total_general=$combo*$total;

    $pdf->Cell(1);
    $pdf->Cell(30,5,"RESPONSABLE:",'T',0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(102,5,utf8_decode($resultados['responsable']),'T',0,'L',0);
    $pdf->SetFont('','B');

    $pdf->Cell(32,5,"SUBTOTAL",1,0,'R',0);
    $pdf->Cell(43,5,number_format(0,2,",",".")." Bs",1,1,'R',0);

    $pdf->Cell(1);
    $pdf->Cell(18,5,"CHOFER:",0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(114,5,utf8_decode($resultados['chofer']),0,0,'L',0);    
    $pdf->SetFont('','B');

    $pdf->Cell(32,5,"IVA (".$resultados["iva"] . "%)",1,0,'R',0);
    $pdf->Cell(43,5,number_format($importeiva,2,",",".")." Bs",1,1,'R',0);

    $pdf->SetFontSize(10);
    $pdf->SetFont('','B');

    $pdf->Cell(1);
    $pdf->Cell(15,5,"PLACA:",'B',0,'L',0);
    $pdf->SetFont('');
    
    $pdf->Cell(117,5,utf8_decode($resultados['placa']),'B',0,'L',0);
    $pdf->SetFont('','B');

    $pdf->Cell(32,5,"TOTAL COMBO",1,0,'R',0);
    $pdf->Cell(43,5,number_format(0,2,",",".")." Bs.",1,1,'R',0);

    $pdf->Cell(1);
    $pdf->Cell(56,5,"N DE JUGUETES ENTREGADOS:",'B',0,'L',0);
    $pdf->SetFont('');
    
    $pdf->Cell(80,5,utf8_decode($rowtl[0]),'B',0,'L',0);
    $pdf->SetFont('','B');

    $pdf->Cell(32,5,"TOTAL GENERAL",1,0,'R',0);
    $pdf->Cell(43,5,number_format($total_general,2,",",".")." Bs.",1,1,'R',0);
    
    $pdf->SetFontSize(8);
    $pdf->SetFont('','B');

    $pdf->Cell(1);
    $pdf->Cell(24,5,utf8_decode('TOTAL LETRAS:'),'B',0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(183,5,utf8_decode(numtoletras($total)),'B',1,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode('GENERADO POR: '.$resultados['nombre_usuario'].' '.$resultados['apellido_usuario']),0,1,'L',0);

    $pdf->Cell(0,5,utf8_decode('EXONERADO'),0,10,'C',10);

    $pdf->Ln(10);
    $pdf->SetLeftMargin(75);
    $pdf->Cell(65,5,utf8_decode('ACTA DE CONFORMIDAD'),0,0,'C');
    $pdf->Ln(25);
    $pdf->Cell(60,5,"",0,0,'C');
    $pdf->SetX(0);
    $pdf->SetLeftMargin(75);
    $pdf->Cell(65,5,utf8_decode('RECIBE CONFORME'),'T',0,'C');
    $pdf->Cell(60,5,"",0,0,'C');
    $pdf->Ln(5);
    $pdf->Cell(65,5,utf8_decode('FIRMA Y SELLO'),0,0,'C');
    $pdf->Ln(20);
    $pdf->SetX(0);
    $pdf->SetLeftMargin(15);
    $pdf->Cell(1,5,utf8_decode('OBSERVACION:'),0,0,'C');

    pg_close();
    $pdf->Output("Factura".$codfactura.".pdf","I");
?>
