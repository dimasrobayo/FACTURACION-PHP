<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');

class PDF extends FPDF
{
var $widths;
var $aligns;

var $B;
var $I;
var $U;
var $HREF;

function PDF($orientation, $unit, $size)
{
    // Llama al constructor de la clase padre
    $this->FPDF($orientation,$unit,$size);
    // Iniciaci�n de variables
    $this->B = 0;
    $this->I = 0;
    $this->U = 0;
    $this->HREF = '';
}

/*
function Header()
{
//consulta sql
$consulta = "SELECT * FROM empresa order by empresa.rif_empresa";
$query = pg_query($consulta);
$row = pg_fetch_array($query);
    //Logo
    $this->Image('./logo/logo.jpg',10,6,196);
    $this->Ln(30);	
}
*/
//Pie de p�gina
//function Footer()
//{
//    //Posici�n: a 1,5 cm del final
//    $this->SetY(-25);
//    //Arial italic 8
//    $this->SetFont('Arial','',8);
//    //N�mero de p�gina
//    $this->Cell(0,10,utf8_decode('Visión Hannah c.a'),0,0,'C');	
//	
//	//Posici�n: a 1,5 cm del final
//    $this->SetY(-21);
//    //Arial italic 8
//    $this->SetFont('Arial','',8);
//    //N�mero de p�gina
//    $this->Cell(0,10,utf8_decode('Sistema de Faturación'),0,0,'C');	
//	
//
//    //Posici�n: a 1,5 cm del final
//    $this->SetY(-15);
//    //Arial italic 8
//    $this->SetFont('Arial','',8);
//    //N�mero de p�gina
//    $this->Cell(0,10,utf8_decode('-- Página '.$this->PageNo().'/{nb} --'),0,0,'C');
//}

//Pie de pgina Modificaciones ING. Eulicer G.
function Footer()
{
	setlocale(LC_TIME, "es_VE.ISO-8859-1");// aplico la configuracion del idioma a espanol venezolano
	$dias = date("d") ;
	$anno = date("Y");
	$mes = strftime ("%B");// Imprimo el nombre largo del mes.  	
   
	 //Posicion: a 2 cm del final
//	 $this->SetY(-20);    
//	 $this->SetFont('Arial','',7);
//	 $this->Cell(0,10,utf8_decode('Convención Nacional Bautista de Venezuela'),0,0,'C');
//	 
        
        $this->SetY(-16); 
//        $this->Cell(0,5,'','T',0,'',0);
	
	 //Posicion: a 1,5 cm del final
	 $this->SetY(-15);    
	 $this->SetFont('Arial','',8);    
	 $this->Cell(60,5,utf8_decode('Fecha de Impresión: '.$dias.' de '.$mes.' del '.$anno.'.'),'T',0,'L');	
	 
         //Posicion: a 1,5 cm del final
	 $this->SetY(-15);
         $this->Cell(60);
	 $this->SetFont('Arial','',8);    
	 $this->Cell(80,5,'','T',0,'C');	
	 
	 //Posicion: a 1,5 cm del final
	 $this->SetY(-15);
	 $this->Cell(140);
	 $this->SetFont('Arial','',8);    
	 $this->Cell(0,5,utf8_decode('-- Página '.$this->PageNo().'/{nb} --'),'T',0,'R');
}

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function fill($f)
{
	//juego de arreglos de relleno
	$this->fill=$f;	
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    $fill=$this->fill;//modificacion del original
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=6*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        //$this->MultiCell($w,5,$data[$i],0,$a); //ORIGINAL LRTB
        $this->MultiCell($w,6,$data[$i],'LRT',$a,$fill); //MODIFICACION AL ORIGINAL
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h+10>$this->PageBreakTrigger) {
        $this->AddPage($this->CurOrientation);
		//$this->SetXY(33,60); //modificacion a la original
	}
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
function Justify($text,$w,$h)
{
	$tab_paragraphe = explode("\n", $text);
	$nb_paragraphe = count($tab_paragraphe);
	$j=0;

	while ($j<$nb_paragraphe) {
		$paragraphe = $tab_paragraphe[$j];
		$tab_mot = explode(' ', $paragraphe);
		$nb_mot = count($tab_mot);

		// *** Handle strings longer than paragraph width
		$k=0;
		$l=0;
		while ($k<$nb_mot) {

			$len_mot = strlen ($tab_mot[$k]);
			if ($len_mot< ($w-5) )
			{
				$tab_mot2[$l] = $tab_mot[$k];
				$l++;	
			} else {
				$m=0;
				$chaine_lettre='';
				while ($m<$len_mot) {

					$lettre = substr($tab_mot[$k], $m, 1);
					$len_chaine_lettre = $this->GetStringWidth($chaine_lettre.$lettre);

					if ($len_chaine_lettre>($w-7)) {
						$tab_mot2[$l] = $chaine_lettre . '-';
						$chaine_lettre = $lettre;
						$l++;
					} else {
						$chaine_lettre .= $lettre;
					}
					$m++;
				}
				if ($chaine_lettre) {
					$tab_mot2[$l] = $chaine_lettre;
					$l++;
				}

			}
			$k++;
		}

		// *** Justified lines
		$nb_mot = count($tab_mot2);
		$i=0;
		$ligne = '';
		
		while ($i<$nb_mot) {
			$this->SetX(25); //modificacion a la original
			$mot = $tab_mot2[$i];
			$len_ligne = $this->GetStringWidth($ligne . ' ' . $mot);

			if ($len_ligne>($w-5)) {
				$len_ligne = $this->GetStringWidth($ligne);
				$nb_carac = strlen ($ligne);
				$ecart = (($w-2) - $len_ligne) / $nb_carac;
				$this->_out(sprintf('BT %.3f Tc ET',$ecart*$this->k));
				$this->MultiCell($w,$h,$ligne);
				$ligne = $mot;

			} else {

				if ($ligne)
				{
					$ligne .= ' ' . $mot;
				} else {
					$ligne = $mot;
				}

			}
			$i++;
		}

		// *** Last line
		$this->_out('BT 0 Tc ET');
		//$this->SetX(25); //Modificaciones al Original
		$this->MultiCell($w,$h,$ligne);
		$tab_mot = '';
		$tab_mot2 = '';
		$j++;
	}
}

function WriteHTML($html)
{
    // Intérprete de HTML
    $html = str_replace("\n",' ',$html);
    $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            // Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            // Etiqueta
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                // Extraer atributos
                $a2 = explode(' ',$e);
                $tag = strtoupper(array_shift($a2));
                $attr = array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])] = $a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag, $attr)
{
    // Etiqueta de apertura
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF = $attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    // Etiqueta de cierre
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF = '';
}

function SetStyle($tag, $enable)
{
    // Modificar estilo y escoger la fuente correspondiente
    $this->$tag += ($enable ? 1 : -1);
    $style = '';
    foreach(array('B', 'I', 'U') as $s)
    {
        if($this->$s>0)
            $style .= $s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
    // Escribir un hiper-enlace
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}


}
?>
