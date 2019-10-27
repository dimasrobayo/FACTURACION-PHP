<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
     //RECIBIENDO VALORES
    if (isset($_GET['cod_ticket'])){
        $cod_ticket=$_GET['cod_ticket'];
    }

    $pdf=new PDF('P','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
        
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'32','','jpg','http://www.alcaldiaguanare.gob.ve');
    
    //$pdf->SetFillColor(200,220,255); //AZUL
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->SetLeftMargin(55);
    
    $pdf->SetY(20);
    $pdf->Cell(110,6,utf8_decode('TICKET DE ATENCIÓN AL SOBERANO '),0,0,'C',1);//
    
    $pdf->SetLeftMargin(10);
    $pdf->SetX(10);
    $pdf->SetY(38);
    
    $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket  FROM ticket,tramites,solicitantes,estados_tramites,unidades,categorias". 
            " WHERE ticket.cod_ticket='$cod_ticket' AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND ticket.cedula_rif=solicitantes.cedula_rif AND ticket.cod_tramite=tramites.cod_tramite ".
            " AND tramites.cod_categoria=categorias.cod_categoria AND tramites.cod_unidad=unidades.cod_unidad";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_ticket=pg_fetch_array($result);	
    pg_free_result($result);
    
    if ($resultados_ticket[cod_subticket]!=""){
        $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                " WHERE detalles_ticket.cod_detalle_ticket='$resultados_ticket[cod_subticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND detalles_ticket.cod_unidad=unidades.cod_unidad";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_detalle_ticket=pg_fetch_array($result);	
        pg_free_result($result);
    }
    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->SetFont('Times','B',11);
    $pdf->SetLeftMargin(10);

    
    $pdf->SetFontSize(8);
    $pdf->SetFont('');
    $pdf->Cell(50,4,utf8_decode('REF:R-SGM-200-170a'),0,0,'L',0);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(0,4,utf8_decode('DATOS DEL SOLICITANTE'),1,1,'C',0);
    $pdf->Cell(25,4,utf8_decode('TAC Nro.'),1,0,'C',0);
    $pdf->Cell(25,4,utf8_decode('EMISIÓN'),1,0,'C',0);
    $pdf->Cell(30,4,utf8_decode('CI/RIF Nro.'),1,0,'C',0);
    $pdf->Cell(0,4,utf8_decode('NOMBRE DEL SOLICITANTE'),1,1,'C',0);
    
    
    $pdf->SetFontSize(11);
    $pdf->Cell(25,6,utf8_decode(str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT)),1,0,'C',0);
    $pdf->SetFont('');
    $pdf->Cell(25,6,utf8_decode(date_format(date_create($resultados_ticket['fecha_registro_ticket']), 'd/m/Y')),1,0,'C',0);
    $pdf->Cell(30,6,utf8_decode(substr_replace($resultados_ticket['cedula_rif'],'-',1,0)),1,0,'C',0);
    $pdf->Cell(0,6,utf8_decode($resultados_ticket['nombre_solicitante']),1,1,'C',0);
    
    $pdf->Cell(50,6,'',1,0,'C',1);
    $pdf->Cell(0,6,'',1,1,'C',0);
    $pdf->Ln(-5);
    $pdf->SetFontSize(6);
    $pdf->SetFont('','B');
    $pdf->MultiCell(50, 2, utf8_decode('SUMINISTRE SIEMPRE EL NUMERO DE TAC'),0, 'C',0);
    $pdf->MultiCell(50, 2, utf8_decode('PARA INFORMARLE SOBRE SU SOLICITUD'),0, 'C',0);
    
    $pdf->Ln(3);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(0,4,utf8_decode('DETALLES DE LA SOLICITUD Ó TRÁMITE'),1,1,'C',0);
    $pdf->Cell(0,25,'',1,1,'C',0);
    
    $pdf->SetY(65);
    $pdf->SetFontSize(11);
    $pdf->SetFont('');
    $pdf->MultiCell(0, 4, utf8_decode($resultados_ticket['descripcion_ticket']),0, 'J',0,5);
    
    $pdf->SetY(84);
    $pdf->Cell(50,5,utf8_decode('Monto asociado a la Solicitud:'),0,0,'L',0);
    $pdf->Cell(20,5,utf8_decode($resultados_ticket['monto_solicitud']),0,0,'L',0);
    $pdf->SetY(89);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(120,4,utf8_decode('TIPO TRÁMITE'),1,0,'C',0);
    $pdf->Cell(0,4,utf8_decode('CATEGORIA'),1,1,'C',0);
    $pdf->SetFontSize(11);
    $pdf->SetFont('');
    $pdf->Cell(120,6,substr(utf8_decode($resultados_ticket['nombre_tramite']),0, 50),1,0,'L',0);
    $pdf->Cell(0,6,utf8_decode($resultados_ticket['descripcion_categoria']),1,1,'C',0);
    
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(120,4,utf8_decode('UNIDAD A LA CUAL SE LE ASIGNO INICIALMENTE EL TRÁMITE'),1,0,'C',0);
    $pdf->Cell(0,4,utf8_decode('RESPONSABLE DE LA UNIDAD'),1,1,'C',0);
    $pdf->SetFontSize(11);
    $pdf->SetFont('');
    if ($resultados_ticket[cod_subticket]!=""){
        $pdf->Cell(120,6,utf8_decode($resultados_ticket['nombre_unidad']),1,0,'C',0);
        $pdf->Cell(0,6,utf8_decode($resultados_ticket['responsable_unidad']),1,1,'C',0);
    }else{
        $pdf->Cell(120,6,'',1,0,'C',0);
        $pdf->Cell(0,6,'',1,1,'C',0);
    }
    
    $pdf->Ln(1);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(110,4,utf8_decode('UNIDAD ALTUALMENTE RESPONSABLE DE ATENDER EL TRÁMITE'),1,0,'C',0);
    $pdf->Cell(25,4,utf8_decode('ASIGNACIÓN'),1,0,'C',0);
    $pdf->Cell(0,4,utf8_decode('ESTADO ACTUAL DEL TRAMITE'),1,1,'C',0);
    
    $pdf->SetFontSize(11);
    $pdf->SetFont('');
    
    if ($resultados_ticket[cod_subticket]!=""){
        $pdf->Cell(110,6,utf8_decode($resultados_detalle_ticket[nombre_unidad]),1,0,'C',0);
        $pdf->Cell(25,6,utf8_decode(date_format(date_create($resultados_detalle_ticket['fecha_registro']), 'd/m/Y')),1,0,'C',0);

        if($resultados_detalle_ticket['tipo_estado_tramite']==1) $estado_tramite='ASIGNADO'; elseif($resultados_detalle_ticket['tipo_estado_tramite']==2) $estado_tramite='COMPLETADO'; else $estado_tramite='CANCELADO'; 
        $pdf->Cell(0,6,utf8_decode($estado_tramite),1,1,'C',0);
    }else{
        $pdf->Cell(110,6,'',1,0,'C',0);
        $pdf->Cell(25,6,'',1,0,'C',0);

        if($resultados_detalle_ticket['tipo_estado_tramite']==1) $estado_tramite='ASIGNADO'; elseif($resultados_detalle_ticket['tipo_estado_tramite']==2) $estado_tramite='COMPLETADO'; else $estado_tramite='CANCELADO'; 
        $pdf->Cell(0,6,'NUEVO - SIN ASIGNAR',1,1,'C',0);
    }
    
    $pdf->Ln(2);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(0,4,utf8_decode('SITUACIÓN ACTUAL'),1,1,'C',0);
    $pdf->Cell(0,25,'',1,1,'C',0);
    
    $pdf->SetY(128);
    $pdf->SetFontSize(11);
    $pdf->SetFont('');
    $pdf->MultiCell(0, 4, utf8_decode($resultados_detalle_ticket['situacion_actual']),0, 'J',0,5);
    
    
    $pdf->SetY(152);
    
    $pdf->Ln(1);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(0,4,utf8_decode('ACTUACIÓN'),1,1,'C',0);
    $pdf->Cell(0,25,'',1,1,'C',0);
    
    $pdf->SetY(158);
    $pdf->SetFontSize(11);
    $pdf->SetFont('');
    $pdf->MultiCell(0, 4, utf8_decode($resultados_detalle_ticket['actuacion']),0, 'J',0,5);
    $pdf->SetY(182);
    
    
    
    
    $pdf->Ln(1);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(20,10,'',1,0,'C',0);
    $pdf->Cell(25,4,'FECHA',1,0,'C',0);
    $pdf->Cell(20,4,'HORA',1,0,'C',0);
    $pdf->Cell(45);
    
    $pdf->SetFontSize(8);
    $pdf->SetFont('');
    $pdf->Cell(0,4,'Impreso el '.date("d/m/Y g:i A."),0,1,'R',0);
    
    $pdf->SetFontSize(11);
    $pdf->Cell(20);
    if($resultados_detalle_ticket['fecha_cita_programada']==NULL){
        $pdf->Cell(25,6,'',1,0,'C',0);
    }else{
        $pdf->Cell(25,6,date_format(date_create($resultados_detalle_ticket['fecha_cita_programada']), 'd/m/Y'),1,0,'C',0);
    }
    if($resultados_detalle_ticket['hora_cita_programada']==NULL){
        $pdf->Cell(20,6,'',1,1,'C',0);
    }else{
        $pdf->Cell(20,6,date_format(date_create($resultados_detalle_ticket['hora_cita_programada']), 'g:i A.'),1,1,'C',0);
    }
    
    $pdf->Ln(-8);
    $pdf->SetFontSize(11);
    $pdf->SetFont('');
    $pdf->MultiCell(20, 6, utf8_decode('CITA'),0, 'C',0,0);
    
    ////// MITAD DE PAGINA
    
//    $pdf->SetFont('Arial','B',16);
//    $pdf->Image('./logo/logo.jpg',10,135,'40','','jpg','http://www.alcaldiaguanare.gob.ve');
//    
//    //$pdf->SetFillColor(200,220,255); //AZUL
//    $pdf->SetFillColor(200,200,200);//GRIS
//    $pdf->SetTextColor(0);
//    $pdf->SetDrawColor(0,0,0);
//    $pdf->SetLineWidth(.1);
//    
//    $pdf->SetFont('Arial','B',14);
//    $pdf->SetLeftMargin(55);
//    
//    $pdf->SetY(145);
//    $pdf->Cell(110,6,utf8_decode('TICKET DE ATENCIÓN AL SOBERANO '),0,0,'L',1);//
//    
//    $pdf->SetLeftMargin(10);
//    $pdf->SetX(10);
//    $pdf->SetY(163);
//        
//    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
//    $pdf->SetFont('Times','B',11);
//    $pdf->SetLeftMargin(10);
//
//    
//    $pdf->SetFontSize(8);
//    $pdf->SetFont('');
//    $pdf->Cell(50,5,utf8_decode('REF:R-SGM-200-170a'),0,0,'L',0);
//    $pdf->SetFontSize(9);
//    $pdf->SetFont('','B');
//    $pdf->Cell(0,5,utf8_decode('DATOS DEL SOLICITANTE'),1,1,'C',0);
//    $pdf->Cell(25,4,utf8_decode('TAC Nro.'),1,0,'C',0);
//    $pdf->Cell(25,4,utf8_decode('EMISIÓN'),1,0,'C',0);
//    $pdf->Cell(30,4,utf8_decode('CI/RIF Nro.'),1,0,'C',0);
//    $pdf->Cell(0,4,utf8_decode('NOMBRE DEL SOLICITANTE'),1,1,'C',0);
//    
//    
//    $pdf->SetFontSize(11);
//    $pdf->Cell(25,6,utf8_decode(str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT)),1,0,'C',0);
//    $pdf->SetFont('');
//    $pdf->Cell(25,6,utf8_decode(date_format(date_create($resultados_ticket['fecha_registro']), 'd/m/Y')),1,0,'C',0);
//    $pdf->Cell(30,6,utf8_decode(substr_replace($resultados_ticket['cedula_rif'],'-',1,0)),1,0,'C',0);
//    $pdf->Cell(0,6,utf8_decode($resultados_ticket['nombre_solicitante']),1,1,'C',0);
//    
//    $pdf->Cell(50,6,'',1,0,'C',1);
//    $pdf->Cell(0,6,'',1,1,'C',0);
//    $pdf->Ln(-5);
//    $pdf->SetFontSize(6);
//    $pdf->SetFont('','B');
//    $pdf->MultiCell(50, 2, utf8_decode('SUMINISTRE SIEMPRE EL NUMERO DE TAC'),0, 'C',0);
//    $pdf->MultiCell(50, 2, utf8_decode('PARA INFORMARLE SOBRE SU SOLICITUD'),0, 'C',0);
//    
//    $pdf->Ln(3);
//    $pdf->SetFontSize(9);
//    $pdf->SetFont('','B');
//    $pdf->Cell(0,4,utf8_decode('DETALLES DE LA SOLICITUD Ó TRÁMITE'),1,1,'C',0);
//    $pdf->Cell(0,25,'',1,1,'C',0);
//    
//    $pdf->SetY(191);
//    $pdf->SetFontSize(11);
//    $pdf->SetFont('');
//    $pdf->MultiCell(0, 4, utf8_decode($resultados_ticket['descripcion_ticket']),0, 'J',0,5);
//    
//    $pdf->SetY(210);
//    $pdf->Cell(50,5,utf8_decode('Monto asociado a la Solicitud:'),0,0,'L',0);
//    $pdf->Cell(20,5,utf8_decode($resultados_ticket['monto_solicitud']),0,0,'L',0);
//    $pdf->SetY(215);
//    
//    $pdf->SetFontSize(9);
//    $pdf->SetFont('','B');
//    $pdf->Cell(120,4,utf8_decode('TIPO TRÁMITE'),1,0,'C',0);
//    $pdf->Cell(0,4,utf8_decode('CATEGORIA'),1,1,'C',0);
//    $pdf->SetFontSize(11);
//    $pdf->SetFont('');
//    $pdf->Cell(120,6,substr(utf8_decode($resultados_ticket['nombre_tramite']),0, 50),1,0,'L',0);
//    $pdf->Cell(0,6,utf8_decode($resultados_ticket['descripcion_categoria']),1,1,'C',0);
//    
//    $pdf->SetFontSize(9);
//    $pdf->SetFont('','B');
//    $pdf->Cell(120,4,utf8_decode('UNIDAD A LA CUAL SE LE ASIGNO INICIALMENTE EL TRÁMITE'),1,0,'C',0);
//    $pdf->Cell(0,4,utf8_decode('RESPONSABLE DE LA UNIDAD'),1,1,'C',0);
//    $pdf->SetFontSize(11);
//    $pdf->SetFont('');
//    if ($resultados_ticket[cod_subticket]!=""){
//        $pdf->Cell(120,6,utf8_decode($resultados_ticket['nombre_unidad']),1,0,'C',0);
//        $pdf->Cell(0,6,utf8_decode($resultados_ticket['responsable_unidad']),1,1,'C',0);
//    }else{
//        $pdf->Cell(120,6,'',1,0,'C',0);
//        $pdf->Cell(0,6,'',1,1,'C',0);
//    }
//    $pdf->Ln(1);
//    $pdf->SetFontSize(9);
//    $pdf->SetFont('','B');
//    $pdf->Cell(110,4,utf8_decode('UNIDAD ALTUALMENTE RESPONSABLE DE ATENDER EL TRÁMITE'),1,0,'C',0);
//    $pdf->Cell(25,4,utf8_decode('ASIGNACIÓN'),1,0,'C',0);
//    $pdf->Cell(0,4,utf8_decode('ESTADO ACTUAL DEL TRAMITE'),1,1,'C',0);
//    
//    $pdf->SetFontSize(11);
//    $pdf->SetFont('');
//    
//    if ($resultados_ticket[cod_subticket]!=""){
//        $pdf->Cell(110,6,utf8_decode($resultados_detalle_ticket[nombre_unidad]),1,0,'C',0);
//        $pdf->Cell(25,6,utf8_decode(date_format(date_create($resultados_detalle_ticket['fecha_registro']), 'd/m/Y')),1,0,'C',0);
//
//        if($resultados_detalle_ticket['tipo_estado_tramite']==1) $estado_tramite='ASIGNADO'; elseif($resultados_detalle_ticket['tipo_estado_tramite']==2) $estado_tramite='COMPLETADO'; else $estado_tramite='CANCELADO'; 
//        $pdf->Cell(0,6,utf8_decode($estado_tramite),1,1,'C',0);
//    }else{
//        $pdf->Cell(110,6,'',1,0,'C',0);
//        $pdf->Cell(25,6,'',1,0,'C',0);
//
//        if($resultados_detalle_ticket['tipo_estado_tramite']==1) $estado_tramite='ASIGNADO'; elseif($resultados_detalle_ticket['tipo_estado_tramite']==2) $estado_tramite='COMPLETADO'; else $estado_tramite='CANCELADO'; 
//        $pdf->Cell(0,6,'NUEVO - SIN ASIGNAR',1,1,'C',0);
//    }
//    
//    $pdf->Ln(1);
//    $pdf->SetFontSize(9);
//    $pdf->SetFont('','B');
//    $pdf->Cell(20,10,'',1,0,'C',0);
//    $pdf->Cell(25,4,'FECHA',1,0,'C',0);
//    $pdf->Cell(20,4,'HORA',1,0,'C',0);
//    $pdf->Cell(45);
//    
//    $pdf->SetFontSize(8);
//    $pdf->SetFont('');
//    $pdf->Cell(0,4,'Impreso el '.date("d/m/Y g:i A."),0,1,'R',0);
//    
//    $pdf->SetFontSize(11);
//    $pdf->Cell(20);
//    if($resultados_detalle_ticket['fecha_cita_programada']==NULL){
//        $pdf->Cell(25,6,'',1,0,'C',0);
//    }else{
//        $pdf->Cell(25,6,date_format(date_create($resultados_detalle_ticket['fecha_cita_programada']), 'd/m/Y'),1,0,'C',0);
//    }
//    if($resultados_detalle_ticket['hora_cita_programada']==NULL){
//        $pdf->Cell(20,6,'',1,1,'C',0);
//    }else{
//        $pdf->Cell(20,6,date_format(date_create($resultados_detalle_ticket['hora_cita_programada']), 'g:i A.'),1,1,'C',0);
//    }
//    
//    $pdf->Ln(-8);
//    $pdf->SetFontSize(11);
//    $pdf->SetFont('');
//    $pdf->MultiCell(20, 6, utf8_decode('CITA'),0, 'C',0,0);
//    

    pg_close();
    $pdf->Output("TAC-".$cod_ticket.".pdf","I");
?>
