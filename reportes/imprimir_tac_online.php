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
    $pdf->Image('./logo/logo.jpg',10,10,'32','','jpg','http://www.portuguesa.gob.ve');
    
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
    
    $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,tipo_solicitantes,estados_tramites,unidades,categorias". 
            " WHERE ticket.cod_ticket='$cod_ticket' AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND ticket.cedula_rif=solicitantes.cedula_rif AND solicitantes.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante AND ticket.cod_tramite=tramites.cod_tramite ".
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
    
    $query="SELECT * FROM comunidades,parroquias,municipios,estados WHERE comunidades.idcom='$resultados_ticket[idcom]' and   comunidades.codpar=parroquias.codpar and comunidades.codmun=parroquias.codmun and comunidades.codest=parroquias.codest and parroquias.codest=municipios.codest and parroquias.codmun=municipios.codmun and municipios.codest=estados.codest";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_localidad=pg_fetch_array($result);	
    pg_free_result($result);
    
    $localidad=$resultados_localidad[desmun].' - '.$resultados_localidad[despar].' - '.$resultados_localidad[descom];
    
    $pdf->SetFont('Times','B',9);
    $pdf->SetLeftMargin(175);
    $pdf->SetY(15);
    
    $pdf->Cell(0,4,utf8_decode('TICKET NRO.'),1,1,'C',1);
    $pdf->SetFontSize(11);
    $pdf->Cell(0,5,utf8_decode(str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT)),1,1,'C',0);
    $pdf->Ln(1);
    $pdf->SetFont('Times','B',9);
    $pdf->Cell(0,4,utf8_decode('FECHA EMISIÓN'),1,1,'C',1);
    $pdf->SetFontSize(11);
    $pdf->Cell(0,5,utf8_decode(date_format(date_create($resultados_ticket['fecha_registro_ticket']), 'd/m/Y')),1,0,'C',0);
    
    
     
    $pdf->SetLeftMargin(10);
    $pdf->SetX(10);
    $pdf->SetY(38);
    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->SetFont('Times','B',11);
    $pdf->SetLeftMargin(10);

    
//    $pdf->SetFontSize(8);
//    $pdf->SetFont('');
//    $pdf->Cell(50,4,utf8_decode('REF:R-SGM-200-170a'),0,0,'L',0);
//    $pdf->SetFontSize(9);
    $pdf->SetFont('Times','B',9);
    $pdf->Cell(0,4,utf8_decode('DATOS DEL SOLICITANTE'),1,1,'C',1);
    $pdf->Cell(20,4,utf8_decode('CI/RIF Nro.'),1,0,'C',0);
    $pdf->Cell(80,4,utf8_decode('NOMBRE DEL SOLICITANTE'),1,0,'C',0);
    $pdf->Cell(10,4,utf8_decode('SEXO'),1,0,'C',0);
    $pdf->Cell(25,4,utf8_decode('TELEFONO'),1,0,'C',0);
    $pdf->Cell(0,4,utf8_decode('EMAIL'),1,1,'C',0);
    
    
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->Cell(20,5,utf8_decode(substr_replace($resultados_ticket['cedula_rif'],'-',1,0)),1,0,'C',0);
    $pdf->Cell(80,5,utf8_decode($resultados_ticket['nombre_solicitante']),1,0,'L',0);
    if($resultados_ticket['sexo_solicitante']==1) $sexo='M'; elseif($resultados_ticket['sexo_solicitante']==2) $sexo='F'; else $sexo='N/A';
    $pdf->Cell(10,5,utf8_decode($sexo),1,0,'C',0);
    $pdf->Cell(25,5,utf8_decode($resultados_ticket['telefono_movil']),1,0,'C',0);
    $pdf->Cell(0,5,utf8_decode($resultados_ticket['email']),1,1,'L',0);
    
    $pdf->SetFont('Times','B',9);
    $pdf->Cell(75,4,utf8_decode('TIPO DE SOLICITANTE'),1,0,'C',0);
    $pdf->Cell(0,4,utf8_decode('LOCALIDAD (MUNICIPIO - PARROQUIA - COMUNIDAD)'),1,1,'C',0);
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->Cell(75,5,utf8_decode($resultados_ticket[descripcion_tipo_solicitante]),1,0,'L',0);
    $pdf->Cell(0,5,utf8_decode($localidad),1,1,'L',0);
    $pdf->Cell(0,5,utf8_decode('DIRECCIÓN HAB.: '.$resultados_ticket[direccion_habitacion]),1,1,'L',0);
    
  
   
    $pdf->Ln(1);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(0,4,utf8_decode('DETALLES DE LA SOLICITUD Ó TRÁMITE'),1,1,'C',1);
    $pdf->Cell(0,20,'',1,1,'C',0);
    
    $pdf->SetY(71);
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->MultiCell(0, 4, utf8_decode($resultados_ticket['descripcion_ticket']),0, 'J',0,5);
    
    $pdf->SetY(85);
    $pdf->Cell(45,5,utf8_decode('Monto asociado a la Solicitud:'),0,0,'L',0);
    $pdf->SetFont('','B');
    $pdf->Cell(20,5,utf8_decode($resultados_ticket['monto_solicitud']),0,0,'L',0);
    
    $pdf->SetY(90);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(120,4,utf8_decode('TIPO TRÁMITE'),1,0,'C',1);
    $pdf->Cell(0,4,utf8_decode('CATEGORIA'),1,1,'C',1);
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->Cell(120,6,substr(utf8_decode($resultados_ticket['nombre_tramite']),0, 50),1,0,'L',0);
    $pdf->Cell(0,6,utf8_decode($resultados_ticket['descripcion_categoria']),1,1,'C',0);
    
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(120,4,utf8_decode('UNIDAD A LA CUAL SE LE ASIGNO INICIALMENTE EL TRÁMITE'),1,0,'C',1);
    $pdf->Cell(0,4,utf8_decode('RESPONSABLE DE LA UNIDAD'),1,1,'C',1);
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->Cell(120,6,utf8_decode($resultados_ticket['nombre_unidad']),1,0,'C',0);
    $pdf->Cell(0,6,utf8_decode($resultados_ticket['responsable_unidad']),1,1,'C',0);
    
    $pdf->Ln(1);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(110,4,utf8_decode('UNIDAD ALTUALMENTE RESPONSABLE DE ATENDER EL TRÁMITE'),1,0,'C',1);
    $pdf->Cell(25,4,utf8_decode('ASIGNACIÓN'),1,0,'C',1);
    $pdf->Cell(0,4,utf8_decode('ESTADO ACTUAL DEL TRAMITE'),1,1,'C',1);
    
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->Cell(110,6,utf8_decode($resultados_detalle_ticket[nombre_unidad]),1,0,'C',0);
    $pdf->Cell(25,6,utf8_decode(date_format(date_create($resultados_detalle_ticket['fecha_registro']), 'd/m/Y')),1,0,'C',0);
    
    if($resultados_detalle_ticket['tipo_estado_tramite']==1) $estado_tramite='ASIGNADO'; elseif($resultados_detalle_ticket['tipo_estado_tramite']==2) $estado_tramite='COMPLETADO'; else $estado_tramite='CANCELADO'; 
    $pdf->Cell(0,6,utf8_decode($resultados_detalle_ticket[siglas_estado_tramite].' - '.$estado_tramite),1,1,'C',0);
    
    $pdf->Ln(2);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(0,4,utf8_decode('SITUACIÓN ACTUAL'),1,1,'C',1);
    $pdf->Cell(0,25,'',1,1,'C',0);
    
    $pdf->SetY(128);
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->MultiCell(0, 4, utf8_decode($resultados_detalle_ticket['situacion_actual']),0, 'J',0,5);
    
    
    $pdf->SetY(152);
    
    $pdf->Ln(1);
    $pdf->SetFontSize(9);
    $pdf->SetFont('','B');
    $pdf->Cell(0,4,utf8_decode('ACTUACIÓN'),1,1,'C',1);
    $pdf->Cell(0,25,'',1,1,'C',0);
    
    $pdf->SetY(158);
    $pdf->SetFontSize(10);
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
    
    $pdf->SetFontSize(10);
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
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->MultiCell(20, 6, utf8_decode('CITA'),0, 'C',0,0);
    
    if($resultados_ticket['prioridad_ticket']==1){
        $pdf->Image('./logo/nivel_1.png',80,183,'11','','png','');
    }elseif($resultados_ticket['prioridad_ticket']==2){
        $pdf->Image('./logo/nivel_2.png',80,183,'11','','png','');
    }else{
        $pdf->Image('./logo/nivel_3.png',80,183,'11','','png','');
    }
    pg_close();
    $pdf->Output("TAC-".$cod_ticket.".pdf","I");
?>
