<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    //RECIBIENDO VALORES
    if (isset($_GET['cod_unidad'])){
        $cod_unidad=$_GET['cod_unidad'];
    }

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
    $pdf->Cell(150,6,utf8_decode('LISTADO DE TRAMITES POR DEPENDENCIA/UNIDAD'),0,0,'C',0);//
    
    $pdf->SetFontSize(9);
    $pdf->Line(10,38,269,38);
    $pdf->Ln(10);
    
    $pdf->SetLeftMargin(10);
    $pdf->SetX(10);
    $pdf->SetY(40);
    
    //Consulta
    $query = "SELECT * FROM unidades where cod_unidad='$cod_unidad'";
    $result = pg_query($query)or die(pg_last_error());
    $resultado_unidad=pg_fetch_array($result);	
    pg_free_result($result);
    
    // DATOS PRINCIPALES DEL REPORTE
    
    $pdf->SetFont('','B');
    $pdf->SetFontSize(10);
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->Cell(0,6,utf8_decode('DATOS DEL DEPARTAMENTO/UNIDAD:'),1,1,'',0);//
    $pdf->Ln(1);
    $pdf->SetFontSize(9);
    
    $pdf->SetFont('','B');
    $pdf->Cell(40,5,utf8_decode('COD. UNIDAD: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(20,5,utf8_decode($resultado_unidad['cod_unidad']),0,0,'L',0);
    
    $pdf->SetFont('','B');
    $pdf->Cell(14,5,utf8_decode('SIGLAS: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(40,5,utf8_decode($resultado_unidad['siglas_unidad']),0,0,'L',0);
    
    $pdf->SetFont('','B');
    $pdf->Cell(30,5,utf8_decode('NOMBRE UNIDAD: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode($resultado_unidad['nombre_unidad']),0,1,'L',0);
    
    $pdf->SetFont('','B');
    $pdf->Cell(40,5,utf8_decode('RESPONSABLE: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(100,5,utf8_decode($resultado_unidad['responsable_unidad']),0,0,'L',0);
    
    $pdf->SetFont('','B');
    $pdf->Cell(46,5,utf8_decode('CARGO DEL RESPONSABLE: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode($resultado_unidad['cargo_responsable']),0,1,'L',0);
    
    $pdf->SetFont('','B');
    $pdf->Cell(46,5,utf8_decode('DIRECCIÓN DE LA UNIDAD: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode($resultado_unidad['direccion_unidad']),0,1,'L',0);
    
    $pdf->SetFont('','B');
    $pdf->Cell(40,5,utf8_decode('TELEFONO 1: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(40,5,utf8_decode($resultado_unidad['telefono_1']),0,0,'L',0);
    
    $pdf->SetFont('','B');
    $pdf->Cell(22,5,utf8_decode('TELEFONO 2: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(40,5,utf8_decode($resultado_unidad['telefono_2']),0,0,'L',0);
    
    $pdf->SetFont('','B');
    $pdf->Cell(14,5,utf8_decode('EMAIL: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode($resultado_unidad['email_unidad']),0,1,'L',0);
    
    $pdf->SetFont('','B');
    $pdf->Cell(40,5,utf8_decode('HORARIO DE LA UNIDAD: '),0,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(0,5,utf8_decode($resultado_unidad['horario_unidad']),0,1,'L',0);
    
    //CONSULTA DETALLES
    $query="SELECT * FROM tramites,categorias,tipo_solicitantes WHERE tramites.cod_categoria=categorias.cod_categoria AND tramites.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante AND  tramites.cod_unidad = '$cod_unidad' ORDER BY cod_tramite";
    $result = pg_query($query)or die(pg_last_error());
    $total_registros=  pg_num_rows($result);
    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->Ln(2);
    $pdf->SetFont('Times','B',11);
    $pdf->SetLeftMargin(10);

    $pdf->SetFillColor(200,220,255);	
    $pdf->SetFont('Times','B',9);	
    $pdf->SetWidths(array(12,40,45,73,45,22,22));	//336 total ancho PAGINA HORIZONTAL
    $pdf->SetAligns(array('C','C','C','C','C','C','C'));    
    
    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("COD"),utf8_decode("CATEGORIA"),utf8_decode("TRAMITE"),utf8_decode("DESCRIPCIÓN TRAMITE"),utf8_decode("OTORGADO A"),utf8_decode("COSTO REG."),utf8_decode("COSTO HAB.")));
    $fill = false;
    $pdf->fill("$fill");
    $fila=1;
////////////////////////////////////////////////////////////////////////////////////
    while ($row = pg_fetch_array($result)){
        $pdf->SetFillColor(230,235,255);
        $pdf->SetFont('Times','',8);	
        $pdf->SetWidths(array(12,40,45,73,45,22,22));	//336 total ancho PAGINA HORIZONTAL
        $pdf->SetAligns(array('C','L','L','L','L','R','R'));
        $pdf->fill("$fill");
        
        $pdf->Row(array(utf8_decode($row[cod_tramite]),utf8_decode($row['descripcion_categoria']),utf8_decode($row['nombre_tramite']),utf8_decode($row['descripcion_tramite']),utf8_decode($row['descripcion_tipo_solicitante']),utf8_decode($row['costo_regular']),utf8_decode($row['costo_habilitado'])));
    }  
    $pdf->Ln(1);
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(0,6,utf8_decode('TOTAL REGISTROS IMPRESOS: '.$total_registros),0,0,'',0);//
    
    pg_close();
    $pdf->Output("unidades.pdf","I");
?>
