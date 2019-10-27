<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    
    //RECIBIENDO VALORES
    if (isset($_POST['year'])){
        $year=$_POST["year"]; 
        $cod_unidad=$_POST["cod_unidad"];
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
    $pdf->Cell(150,6,utf8_decode('ESTADISTICO DE TAC POR DEPENDENCIA/UNIDAD'),0,0,'C',0);//
    
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
    $pdf->Cell(0,6,utf8_decode('DATOS DEL DEPARTAMENTO/UNIDAD:'),1,1,'',1);//
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
    $pdf->Ln(5);
    
    $pdf->SetFontSize(10);
    $pdf->SetFont('','B');
    $pdf->Cell(0,6,utf8_decode('ESTADISTICO AÑO: '.$year),0,1,'C',1);
    $pdf->SetFont('');
    $pdf->Ln(5);
    
    $query = "SELECT * FROM estados_tramites ORDER BY cod_estado_tramite";
    $result_detalle = pg_query($query);
    $total_registros=  pg_num_rows($result_detalle);    
    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->SetFont('Times','B',11);
    $pdf->SetLeftMargin(10);
    

    $pdf->SetFillColor(200,220,255);	
    $pdf->SetFont('Times','B',10);	
    $pdf->SetWidths(array(100,40));	//196 total ancho
    $pdf->SetAligns(array('C','C'));   
    $pdf->SetLeftMargin(60);
    
    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("DESCRIPCIÓN ESTADO DEL TAC"),utf8_decode("TOTALES")));
    $fill = false;
    $pdf->fill("$fill");
////////////////////////////////////////////////////////////////////////////////////
    while ($row = pg_fetch_array($result_detalle)){
        $pdf->SetFillColor(230,235,255);
        $pdf->SetFont('Times','',10);	
        $pdf->SetWidths(array(100,40));	//196 total ancho	
        $pdf->SetAligns(array('L','C'));
        $pdf->fill("$fill");
        
         $query="SELECT COUNT(*) FROM ticket,detalles_ticket". 
                " WHERE ticket.cod_estado_tramite='$row[cod_estado_tramite]' ".
                " AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
                " AND date_part('year',detalles_ticket.fecha_registro)=$year AND detalles_ticket.cod_unidad='$cod_unidad' ";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_total_ticket=pg_fetch_row($result);	
        pg_free_result($result);
        
       
        
        
        
        $pdf->Row(array(utf8_decode($row[descripcion_estado_tramite]),utf8_decode($resultados_total_ticket[0])));

    } 
    $pdf->Ln(2);
    $pdf->SetFont('Times','B',10);
    
    
    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=1 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND date_part('year',detalles_ticket.fecha_registro)=$year AND detalles_ticket.cod_unidad='$cod_unidad' ";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_ticket_pendientes=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=2 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND date_part('year',detalles_ticket.fecha_registro)=$year AND detalles_ticket.cod_unidad='$cod_unidad' ";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_ticket_completados=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=3 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND date_part('year',detalles_ticket.fecha_registro)=$year AND detalles_ticket.cod_unidad='$cod_unidad' ";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_ticket_cancelados=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket,tramites". 
            " WHERE  date_part('year',ticket.fecha_registro)=$year ".
            " AND ticket.cod_tramite=tramites.cod_tramite ".
            " AND tramites.cod_unidad='$cod_unidad'";
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket=pg_fetch_row($result);	
    pg_free_result($result);
    
    $pdf->SetFillColor(200,220,255);	
    $pdf->SetFont('','B');
    $pdf->Cell(100,5,utf8_decode('DESCRIPCIÓN DEL TIPO DE ESTADO DEL TAC'),1,0,'C',1);
    $pdf->Cell(40,5,utf8_decode('TOTALES'),1,1,'C',1);
    $pdf->SetFont('');
    
    
    $pdf->Cell(100,5,utf8_decode('TICKETS PENDIENTES'),1,0,'L',0);
    $pdf->Cell(40,5,$resultados_ticket_pendientes[0],1,1,'C',0);
    
    $pdf->Cell(100,5,utf8_decode('TICKETS COMPLETADOS'),1,0,'L',0);
    $pdf->Cell(40,5,$resultados_ticket_completados[0],1,1,'C',0);
    
    $pdf->Cell(100,5,utf8_decode('TICKETS CANCELADOS'),1,0,'L',0);
    $pdf->Cell(40,5,$resultados_ticket_cancelados[0],1,1,'C',0);
    
    $pdf->Ln(2);
    $pdf->SetFont('','B');
    $pdf->Cell(100,5,utf8_decode('TOTAL TICKETS REGISTRADOS EN AC'),1,0,'L',0);
    $pdf->SetFont('');
    $pdf->Cell(40,5,$resultados_ticket[0],1,1,'C',0);
    
    
    $pdf->SetLeftMargin(10);
    
    pg_close();
    $pdf->Output("estadistica_tac_unidad_year.pdf","I");
?>
