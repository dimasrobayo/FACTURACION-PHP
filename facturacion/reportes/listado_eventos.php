<?php
    if ($_SERVER['HTTP_REFERER'] == "")	{
            echo "<script type='text/javascript'>window.location.href='../index.php'</script>";
            exit;
    }
define('INCLUDE_CHECK',true);
require ('../aut_sys_config.inc.php');	
require ('../connect.php');
require ("../funciones.php"); // llamado de funciones de la pagina
include('fpdf17/funciones.php');
include('fpdf17/php-barcode.php');
  
//RECIBIENDO VALORES

//CONSULTAR DATOS DE EMPRESA
$query= "SELECT * FROM empresa WHERE cod_empresa=1";
$result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
$consulta_empresa=mysql_fetch_array($result);
mysql_free_result($result);

//CONSULTAR DATOS DEL EVENTO
$query="SELECT *, (select count(*)   from inscripciones where inscripciones.cod_evento=eventos.cod_evento and inscripciones.status_inscripcion=0) AS total_preinscritos,".
        " (select count(*)   from inscripciones where inscripciones.cod_evento=eventos.cod_evento and inscripciones.status_inscripcion=1) AS total_inscritos,".
        " (select count(*)   from inscripciones where inscripciones.cod_evento=eventos.cod_evento) AS total_registrados, (nro_cupos - (select count(*)   from inscripciones where inscripciones.cod_evento=eventos.cod_evento)) AS cupos_disponibles ".
        " FROM eventos,organizaciones WHERE eventos.cod_organizacion=organizaciones.cod_organizacion ORDER BY eventos.fecha_registro_evento";				
$result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());	
$total_evento= mysql_num_rows($result);


$pdf=new PDF('L','mm','Letter'); // P=VERTICAL  (196); L=HORIZONTAL (259)
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Image('imagenes/logo_cnbv.png',10,5,'50','','png','http://www.cnbv.org.ve');
$pdf->SetLeftMargin(65);
//$pdf->SetFillColor(200,220,255); //AZUL
$pdf->SetFillColor(200,200,200);//GRIS
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.1);

$pdf->MultiCell(0,6,utf8_decode($consulta_empresa['nombre_empresa']),0,'C',0);//
$pdf->Ln(0);
$pdf->SetFontSize(8);
//Apdo. 61152, Caracas 1060- A. Av. Santiago de Chile Nº 12-14, Urb. Los Caobos, Caracas 1050.
$html = '<b>Direcci&oacute;n: </b>'.$consulta_empresa['direccion'].'<b> Tlf: </b>'.$consulta_empresa['telefono'].'  <b>Fax: </b>'.$consulta_empresa['fax'].'  <b>Email:</b> <a href="mailto:'.$consulta_empresa['correoe'].'" target="_blank">'.$consulta_empresa['correoe'].'</a>';
$pdf->SetLeftMargin(90);
$pdf->SetRightMargin(30);
$pdf->WriteHTML(utf8_decode($html));
$pdf->Line(65,27,269,27);

$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(10);
$pdf->SetY(30);
$html='<b>RIF:</b>'.$consulta_empresa['rif'];
$pdf->WriteHTML(utf8_decode($html));

$pdf->SetFont('Arial','B',12);
$pdf->SetLeftMargin(65);
$pdf->SetY(28);
$pdf->Cell(0,6,utf8_decode('LISTADO DE EVENTOS REGISTRADOS'),0,0,'C',0);//
$pdf->SetY(35);
$pdf->SetX(10);
$pdf->SetLeftMargin(10);
$pdf->Line(10,35,269,35);
$pdf->Ln(4);

	
$pdf->SetFont('Arial','B',7);	
$pdf->SetWidths(array(15,75,27,22,47,18,22,16,17));	
$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));

$fill = true;
$pdf->fill("$fill");
$pdf->Row(array(utf8_decode("CÓDIGO"),"NOMBRES DEL EVENTO","FECHA DEL EVENTO",utf8_decode("ORGANIZACIÓN"),utf8_decode("NOMBRE COORDINADOR"),utf8_decode("TÉLEFONO"),"PRE-INSCRITOS","INSCRITOS","TOTAL REG"));
$fill = false;
$pdf->fill("$fill");
while ($row2 = mysql_fetch_array($result)){ //DATOS QUE PERTENECEN AL GRUPO DE ESTADOS
        $codigo=$row2['cod_evento'];
        $nombre_evento=$row2['nombre_evento'];
        $periodo = implode('/',array_reverse(explode('-',$row2['fecha_ini_evento'])));
        $periodo .= " - ". implode('/',array_reverse(explode('-',$row2['fecha_fin_evento'])));
        $nombre_organizacion=utf8_decode($row2['nombre_organizacion']);
        $nombre_coordinador=utf8_decode($row2['nombre_coordinador']);
        $telefono_coordinador=utf8_decode($row2['telefono_coordinador']);
        $total_preinscritos=utf8_decode($row2['total_preinscritos']);
        $total_inscritos=utf8_decode($row2['total_inscritos']);
        $total_registrados=utf8_decode($row2['total_registrados']);
        
        
        $pdf->SetFillColor(230,235,255);
        $pdf->SetFont('Arial','',6);	
        $pdf->SetWidths(array(15,75,27,22,47,18,22,16,17));	
        $pdf->SetAligns(array('C','L','C','L','L','C','C','C','C'));
//        $pdf->fill("$fill");
        $pdf->Row(array($codigo,$nombre_evento,$periodo,$nombre_organizacion,$nombre_coordinador,$telefono_coordinador,$total_preinscritos,$total_inscritos,$total_registrados));
//        $fill = !$fill;		


}

$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,5,utf8_decode('TOTAL REGISTROS: '.$total_evento),0,1,'L',0);//
$pdf->Ln();
	
// cerramos conexion a la Base de datos.
mysql_close($db_connect);
$pdf->Output("Listado de Eventos.pdf","I");
//echo "<script language='javascript'>window.open('ejemplo.pdf','_self','');</script>";
//exit;
?> 
