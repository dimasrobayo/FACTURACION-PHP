<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    //RECIBIENDO VALORES
    if (isset($_POST['year'])){
        $year=$_POST["year"]; 
    }


    $pdf=new PDF('P','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
        
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'32','','jpg','http://www.portuguesa.gob.ve');
    $pdf->Image('./logo/logo_busportuguesa.png',140,10,'70','','png','http://busportuguesa.com.ve/');
    $pdf->Image('./logo/logo_proveduria_busportuguesa.png',150,20,'50','','png','http://busportuguesa.com.ve/');
    
    //$pdf->SetFillColor(200,220,255); //AZUL
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->SetLeftMargin(55);
    
    $pdf->SetY(20);
    $pdf->Cell(90,6,utf8_decode('ESTADISTICO DE TICKET POR AÑO'),0,0,'C',0);//
    
    
    
    
    $pdf->SetFontSize(10);
    $pdf->Line(10,38,206,38);
    $pdf->Ln(10);
    
    $pdf->SetLeftMargin(10);
    $pdf->SetX(10);
    $pdf->SetY(40);
    
    
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
    $pdf->SetLeftMargin(40);
    
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
                " AND date_part('year',detalles_ticket.fecha_registro)=$year";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_total_ticket=pg_fetch_row($result);	
        pg_free_result($result);
        
       
        
        
        
        $pdf->Row(array(utf8_decode($row[descripcion_estado_tramite]),utf8_decode($resultados_total_ticket[0])));

    } 
    $pdf->Ln(2);
    $pdf->SetFont('Times','B',10);
    
    $query="SELECT COUNT(*) FROM ticket WHERE date_part('year',fecha_registro)=$year";				
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_ticket_year=pg_fetch_row($result);	
    pg_free_result($result);
    
    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=1 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND date_part('year',detalles_ticket.fecha_registro)=$year";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_ticket_pendientes=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=2 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND date_part('year',detalles_ticket.fecha_registro)=$year";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_ticket_completados=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=3 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND date_part('year',detalles_ticket.fecha_registro)=$year";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_ticket_cancelados=pg_fetch_row($result);	
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
    $pdf->Cell(40,5,$resultados_ticket_year[0],1,1,'C',0);
    
    $pdf->SetLeftMargin(10);

    
    

    pg_close();
    $pdf->Output("estadistica_tac_year.pdf","I");
?>
