<?php
class PDF extends FPDF
{
//Cabecera de pagina
function Header()
{
//consulta sql
$consulta = "SELECT * FROM empresa order by empresa.rif_empresa";
$query = pg_query($consulta);
$row = pg_fetch_array($query);
    //Logo
    $this->Image('../../logo/logo.jpg',20,8,180);
    $this->Ln(30);	
}

//Pie de p�gina
function Footer()
{
    //Posici�n: a 1,5 cm del final
    $this->SetY(-21);
    //Arial italic 8
    $this->SetFont('Arial','',7);
    //N�mero de p�gina
    $this->Cell(0,10,utf8_decode('Visión Hannah c.a'),0,0,'C');	
	
	//Posici�n: a 1,5 cm del final
    $this->SetY(-18);
    //Arial italic 8
    $this->SetFont('Arial','',7);
    //N�mero de p�gina
    $this->Cell(0,10,utf8_decode('Sistema de Nomina'),0,0,'C');	
	

    //Posici�n: a 1,5 cm del final
    $this->SetY(-10);
    //Arial italic 8
    $this->SetFont('Arial','',8);
    //N�mero de p�gina
    $this->Cell(0,10,'-- '.$this->PageNo().' --',0,0,'C');
}


//Pie de pgina Modificaciones ING. Eulicer G.
/*function Footer()
{
	setlocale(LC_TIME, "es_VE.ISO-8859-1");// aplico la configuracion del idioma a espanol venezolano
	$dias = date("d") ;
	$anno = date("Y");
	$mes = strftime ("%B");// Imprimo el nombre largo del mes.  	
   
	 //Posicion: a 2 cm del final
	 /*$this->SetY(-20);    
	 $this->SetFont('Arial','',7);
	 $this->Cell(0,10,utf8_decode('Convención Nacional Bautista de Venezuela'),0,0,'C');
	 */	/*
	
	 //Posicion: a 1,5 cm del final
	 $this->SetY(-15);    
	 $this->SetFont('Arial','',8);    
	 $this->Cell(150,5,utf8_decode('Generado el '.$dias.' de '.$mes.' del '.$anno.'.'),0,1,'L');	
	 
	 //Posicion: a 1,5 cm del final
	 $this->SetY(-15);
	 $this->Cell(150);
	 $this->SetFont('Arial','',8);    
	 $this->Cell(0,5,utf8_decode('-- Página '.$this->PageNo().'/{nb} --'),0,0,'R');
}
*/


}
?>
