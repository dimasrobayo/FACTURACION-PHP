<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    
    //RECIBIENDO VALORES
    if (isset($_POST['fecha_ini'])){
        $fecha_ini=$_POST["fecha_ini"]; 
        $fecha_fin=$_POST["fecha_fin"];
    }
    $dias_vigencia_ticket=21;

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
    $pdf->Cell(150,6,utf8_decode('ESTADISTICO DE TICKET ASIGNADOS POR FECHA'),0,1,'C',0);//
    
    $pdf->SetY(32);
    $pdf->SetFont('Times','B',12);
    $pdf->MultiCell(0,6,'FECHA DESDE '.implode('/',array_reverse(explode('-',$fecha_ini))).' HASTA '.implode('/',array_reverse(explode('-',$fecha_fin))),0,'R',0);//
    
    $pdf->SetFontSize(9);
    $pdf->Line(10,38,269,38);
    $pdf->Ln(15);
    
    $pdf->SetLeftMargin(10);
    $pdf->SetX(10);
    $pdf->SetY(40);
    
    
//    $fecha_ini=date_format(date_create($fecha_ini),'Y-m-d');
//    $fecha_fin=date_format(date_create($fecha_fin), 'Y-m-d');
//    
    //Consulta
    $query = "SELECT * FROM unidades WHERE status_unidad=1 order by nombre_unidad ASC";
    $result_detalle = pg_query($query);
    $total_registros=  pg_num_rows($result_detalle);
    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->SetFont('Times','B',11);
    $pdf->SetLeftMargin(10);

    
    $pdf->SetFillColor(200,220,255);	
    $pdf->SetFont('Times','B',8);	
    $pdf->SetWidths(array(9,65,12,17,10,22,9,17,10,22,9,17,10,22,9));	//336 total ancho PAGINA HORIZONTAL
    $pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C'));    
    
    $pdf->Cell(86);//
    $pdf->Cell(58,5,utf8_decode('TICKES PENDIENTES'),1,0,'C',1);//
    $pdf->Cell(58,5,utf8_decode('TICKES COMPLETADOS'),1,0,'C',1);//
    $pdf->Cell(58,5,utf8_decode('TICKES CANCELADOS'),1,1,'C',1);//
    
    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("COD"),utf8_decode("NOMBRE DE UNIDAD / DEPENDENCIA"),utf8_decode("TOTAL"),utf8_decode("VENCIDOS"),utf8_decode("%"),utf8_decode("NO VENCIDOS"),utf8_decode("%"),utf8_decode("VENCIDOS"),utf8_decode("%"),utf8_decode("NO VENCIDOS"),utf8_decode("%"),utf8_decode("VENCIDOS"),utf8_decode("%"),utf8_decode("NO VENCIDOS"),utf8_decode("%")));
    $fill = false;
    $pdf->fill("$fill");
    $fila=1;
    
    $num=0;
    
////////////////////////////////////////////////////////////////////////////////////
    while ($row = pg_fetch_array($result_detalle)){
        $num++;
        $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
                " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND estados_tramites.tipo_estado_tramite=1 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
                " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin' AND detalles_ticket.cod_unidad='$row[cod_unidad]' ".
                " AND date_part('DAY',NOW()-ticket.fecha_registro)<='$dias_vigencia_ticket'";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_ticket_pendientes=pg_fetch_row($result);	
        pg_free_result($result);
//        date_format(date_create($resultados_ticket['fecha_registro_ticket']), 'd/m/Y g:i A.')
        
        //    SELECT ticket.cod_ticket,ticket.fecha_registro, date_part('DAY',NOW()-ticket.fecha_registro) as dia FROM ticket,detalles_ticket,estados_tramites WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite  AND estados_tramites.tipo_estado_tramite=1 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='2015-02-02' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='2015-02-06' AND date_part('DAY',NOW()-ticket.fecha_registro)<=75;
        $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
                " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND estados_tramites.tipo_estado_tramite=1 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
                " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin' AND detalles_ticket.cod_unidad='$row[cod_unidad]' ".
                " AND date_part('DAY',NOW()-ticket.fecha_registro)>'$dias_vigencia_ticket'";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_ticket_pendientes_vencidos=pg_fetch_row($result);	
        pg_free_result($result);
        
        $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
                " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND estados_tramites.tipo_estado_tramite=2 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
                " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin' AND detalles_ticket.cod_unidad='$row[cod_unidad]' ".
                " AND date_part('DAY',detalles_ticket.fecha_registro-ticket.fecha_registro)<='$dias_vigencia_ticket'";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_ticket_completados=pg_fetch_row($result);	
        pg_free_result($result);
        
        $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
                " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND estados_tramites.tipo_estado_tramite=2 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
                " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin' AND detalles_ticket.cod_unidad='$row[cod_unidad]' ".
                " AND date_part('DAY',detalles_ticket.fecha_registro-ticket.fecha_registro)>'$dias_vigencia_ticket'";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_ticket_completados_vencidos=pg_fetch_row($result);	
        pg_free_result($result);
        
        $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
                " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND estados_tramites.tipo_estado_tramite=3 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
                " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin' AND detalles_ticket.cod_unidad='$row[cod_unidad]' ".
                " AND date_part('DAY',detalles_ticket.fecha_registro-ticket.fecha_registro)<='$dias_vigencia_ticket'";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_ticket_cancelados=pg_fetch_row($result);	
        pg_free_result($result);
        
        $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
                " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND estados_tramites.tipo_estado_tramite=3 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
                " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin' AND detalles_ticket.cod_unidad='$row[cod_unidad]' ".
                " AND date_part('DAY',detalles_ticket.fecha_registro-ticket.fecha_registro)>'$dias_vigencia_ticket'";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_ticket_cancelados_vencidos=pg_fetch_row($result);	
        pg_free_result($result);
                
//        $query="SELECT COUNT(*) FROM ticket,tramites". 
//                " WHERE  ticket.fecha_registro>='$fecha_ini' AND ticket.fecha_registro<='$fecha_fin' ".
//                " AND ticket.cod_tramite=tramites.cod_tramite ".
//                " AND tramites.cod_unidad='$row[cod_unidad]'";
        $query="SELECT COUNT(*) FROM ticket,detalles_ticket". 
            " WHERE ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin' AND detalles_ticket.cod_unidad='$row[cod_unidad]' ";
        $result = pg_query($query)or die(pg_last_error());	
        $resultados_ticket=pg_fetch_row($result);	
        pg_free_result($result);
        
        $p_pendiente=number_format((($resultados_ticket_pendientes[0]/$resultados_ticket[0])*100), 2, '.', '');
        $p_pendiente_vencido=number_format((($resultados_ticket_pendientes_vencidos[0]/$resultados_ticket[0])*100), 2, '.', '');
        $p_completado=number_format((($resultados_ticket_completados[0]/$resultados_ticket[0])*100), 2, '.', '');
        $p_completado_vencido=number_format((($resultados_ticket_completados_vencidos[0]/$resultados_ticket[0])*100), 2, '.', '');
        $p_cancelado=number_format((($resultados_ticket_cancelados[0]/$resultados_ticket[0])*100), 2, '.', '');
        $p_cancelado_vencido=number_format((($resultados_ticket_cancelados_vencidos[0]/$resultados_ticket[0])*100), 2, '.', '');
            
        
        $pdf->SetFillColor(230,235,255);
        $pdf->SetFont('Times','',7);	
        $pdf->SetWidths(array(9,65,12,17,10,22,9,17,10,22,9,17,10,22,9));	//196 total ancho	
        $pdf->SetAligns(array('C','L','C','C','C','C','C','C','C','C','C','C','C','C','C'));
        $pdf->fill("$fill");
        
        $pdf->Row(array(utf8_decode($num),  utf8_decode($row[cod_unidad].' - '.$row['nombre_unidad']),utf8_decode($resultados_ticket[0]),utf8_decode($resultados_ticket_pendientes_vencidos[0]),$p_pendiente_vencido,$resultados_ticket_pendientes[0],$p_pendiente,utf8_decode($resultados_ticket_completados_vencidos[0]),$p_completado_vencido,$resultados_ticket_completados[0],$p_completado,utf8_decode($resultados_ticket_cancelados_vencidos[0]),$p_cancelado_vencido,$resultados_ticket_cancelados[0],$p_cancelado));
    }  
    $pdf->Ln(1);
    $pdf->SetFont('Times','B',9);
    
//    SELECT date_part('DAY','2015-04-15'-fecha_registro) AS dia, fecha_registro from ticket where  cod_ticket=101000 and date_part('DAY',NOW()-fecha_registro)<=50;
    
    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=1 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin' ".
            " AND date_part('DAY',NOW()-ticket.fecha_registro)<='$dias_vigencia_ticket'";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_total_ticket_pendientes=pg_fetch_row($result);	
    pg_free_result($result);
    
    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=1 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin' ".
            " AND date_part('DAY',NOW()-ticket.fecha_registro)>'$dias_vigencia_ticket'";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_total_ticket_pendientes_vencidos=pg_fetch_row($result);	
    pg_free_result($result);
    

    

    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=2 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin'".
            " AND date_part('DAY',detalles_ticket.fecha_registro-ticket.fecha_registro)<='$dias_vigencia_ticket'";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_total_ticket_completados=pg_fetch_row($result);	
    pg_free_result($result);
    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=2 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin'".
            " AND date_part('DAY',detalles_ticket.fecha_registro-ticket.fecha_registro)>'$dias_vigencia_ticket'";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_total_ticket_completados_venidos=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=3 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin'".
            " AND date_part('DAY',detalles_ticket.fecha_registro-ticket.fecha_registro)<='$dias_vigencia_ticket'";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_total_ticket_cancelados=pg_fetch_row($result);	
    pg_free_result($result);
    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=3 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin'".
            " AND date_part('DAY',detalles_ticket.fecha_registro-ticket.fecha_registro)>'$dias_vigencia_ticket'";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_total_ticket_cancelados_vencidos=pg_fetch_row($result);	
    pg_free_result($result);

//    $query="SELECT COUNT(*) FROM ticket". 
//            " WHERE  ticket.fecha_registro>='$fecha_ini' AND ticket.fecha_registro<='$fecha_fin'";
//    
    $query="SELECT COUNT(*) FROM ticket,detalles_ticket". 
            " WHERE ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')>='$fecha_ini' AND to_date(to_char(detalles_ticket.fecha_registro,'YYYY-MM-DD'),'YYYY-MM-DD')<='$fecha_fin'";
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_total_ticket=pg_fetch_row($result);	
    pg_free_result($result);
    
    $pt_pendiente=number_format((($resultados_total_ticket_pendientes[0]/$resultados_total_ticket[0])*100), 2, '.', '');
    $pt_pendiente_vencido=number_format((($resultados_total_ticket_pendientes_vencidos[0]/$resultados_total_ticket[0])*100), 2, '.', '');
    $pt_completado=number_format((($resultados_total_ticket_completados[0]/$resultados_total_ticket[0])*100), 2, '.', '');
    $pt_completado_vencido=number_format((($resultados_total_ticket_completados_venidos[0]/$resultados_total_ticket[0])*100), 2, '.', '');
    $pt_cancelado=number_format((($resultados_total_ticket_cancelados[0]/$resultados_total_ticket[0])*100), 2, '.', '');
    $pt_cancelado_vencido=number_format((($resultados_total_ticket_cancelados_vencidos[0]/$resultados_total_ticket[0])*100), 2, '.', '');
        
    
    
    $pdf->Cell(74,5,utf8_decode('TOTAL GENERAL DE TAC:'),1,0,'L',0);//
    $pdf->Cell(12,5,$resultados_total_ticket[0],1,0,'C',0);//
    $pdf->Cell(17,5,$resultados_total_ticket_pendientes_vencidos[0],1,0,'C',0);//
    $pdf->Cell(10,5,$pt_pendiente_vencido,1,0,'C',0);//
    $pdf->Cell(22,5,$resultados_total_ticket_pendientes[0],1,0,'C',0);//
    $pdf->Cell(9,5,$pt_pendiente,1,0,'C',0);//
    
    $pdf->Cell(17,5,$resultados_total_ticket_completados_venidos[0],1,0,'C',0);//
    $pdf->Cell(10,5,$pt_completado_vencido,1,0,'C',0);//
    $pdf->Cell(22,5,$resultados_total_ticket_completados[0],1,0,'C',0);//
    $pdf->Cell(9,5,$pt_completado,1,0,'C',0);//
    
    $pdf->Cell(17,5,$resultados_total_ticket_cancelados_vencidos[0],1,0,'C',0);//
    $pdf->Cell(10,5,$pt_cancelado_vencido,1,0,'C',0);//
    $pdf->Cell(22,5,$resultados_total_ticket_cancelados[0],1,0,'C',0);//
    $pdf->Cell(9,5,$pt_cancelado,1,1,'C',0);//
    
    pg_close();
    $pdf->Output("estadistico_tac_fecha.pdf","I");
?>
