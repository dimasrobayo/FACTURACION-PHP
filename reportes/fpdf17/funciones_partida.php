<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');

/************************************/
/* global functions                 */
/************************************/
function hex2dec($couleur = "#000000"){
	$R = substr($couleur, 1, 2);
	$rouge = hexdec($R);
	$V = substr($couleur, 3, 2);
	$vert = hexdec($V);
	$B = substr($couleur, 5, 2);
	$bleu = hexdec($B);
	$tbl_couleur = array();
	$tbl_couleur['R']=$rouge;
	$tbl_couleur['V']=$vert;
	$tbl_couleur['B']=$bleu;
	return $tbl_couleur;
}

function px2mm($px){
	return $px*25.4/72;
}

function txtentities($html){
	$trans = get_html_translation_table(HTML_ENTITIES);
	$trans = array_flip($trans);
	return strtr($html, $trans);
}

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
    $h=5*$nb;
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
        $this->MultiCell($w,5,$data[$i],'LRT',$a,$fill); //MODIFICACION AL ORIGINAL
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
			$this->SetX(10); //modificacion a la original
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
		$this->MultiCell($w,$h,$ligne);
		$tab_mot = '';
		$tab_mot2 = '';
		$j++;
	}
}

function Justify2($text,$w,$h)
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
		$this->SetX(25);
		$this->MultiCell($w,$h,$ligne);
		$tab_mot = '';
		$tab_mot2 = '';
		$j++;
	}
}

//Pie de pgina Modificaciones ING. Eulicer G.
function Footer()
{
         //Posicion: a 1,5 cm del final
	 $this->SetY(-10);
         
	 $this->SetFont('Arial','',6);    
     $this->Cell(0,3,utf8_decode('Este formato de partida de nacimiento entró en vigencia según Decreto Nº 048-2006, de fecha 25/04/2006. Publicado en Gaceta Municipal Extraordinaria Nº 034, de fecha 04/05/2006.'),0,1,'C',0); 
     $this->SetFont('Arial','B',9);
     $this->SetFillColor(0,0,0);//GRIS
    $this->SetTextColor(255);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.1);
	 $this->Cell(0,5,utf8_decode('ADVERTENCIA: ES ILEGAL HACER ENMENDADURAS SOBRE ESTA ACTA, ASI COMO EFECTUAR COPIAS CON FINES ILÍCITOS'),0,0,'C',1);	
	 
	
}

// funcion para mostrar codigo html
function WriteHTML($html)
{
	//HTML parser
	$html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
	$html=str_replace("\n",' ',$html); //remplace retour � la ligne par un espace
        
        $html = str_replace('&copy;','©',$html);
        $html = str_replace('&nbsp;',' ',$html);
        $html = str_replace('&oacute;','ó',$html);
        $html = str_replace('&Oacute;','Ó',$html);
        $html = str_replace('&eacute;','é',$html);
        $html = str_replace('&Eacute;','É',$html);
        $html = str_replace('&aacute;','á',$html);
        $html = str_replace('&Aacute;','Á',$html);
        $html = str_replace('&iacute;','í',$html);
        $html = str_replace('&Iacute;','Í',$html);
        $html = str_replace('&uacute;','ú',$html);
        $html = str_replace('&Uacute;','Ú',$html);        
        
	$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //�clate la cha�ne avec les balises
	foreach($a as $i=>$e)
	{
		if($i%2==0)
		{
			//Text
			if($this->HREF)
				$this->PutLink($this->HREF,$e);
			else
				$this->Write(5,utf8_decode(stripslashes(txtentities($e))));
		}
		else
		{
			//Tag
			if($e[0]=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else
			{
				//Extract attributes
				$a2=explode(' ',$e);
				$tag=strtoupper(array_shift($a2));
				$attr=array();
				foreach($a2 as $v)
				{
					if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])]=$a3[2];
				}
				$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag, $attr)
{
	//Opening tag
	switch($tag){
		case 'STRONG':
			$this->SetStyle('B',true);
			break;
		case 'EM':
			$this->SetStyle('I',true);
			break;
		case 'B':
		case 'I':
		case 'U':
			$this->SetStyle($tag,true);
			break;
		case 'A':
			$this->HREF=$attr['HREF'];
			break;
		case 'IMG':
			if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
				if(!isset($attr['WIDTH']))
					$attr['WIDTH'] = 0;
				if(!isset($attr['HEIGHT']))
					$attr['HEIGHT'] = 0;
				$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
			}
			break;
		case 'TR':
		case 'BLOCKQUOTE':
		case 'BR':
			$this->Ln(2);
			break;
		case 'P':
			$this->Ln(5);
			break;
		case 'FONT':
			if (isset($attr['COLOR']) && $attr['COLOR']!='') {
				$coul=hex2dec($attr['COLOR']);
				$this->SetTextColor($coul['R'],$coul['G'],$coul['B']);
				$this->issetcolor=true;
			}
			if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
				$this->SetFont(strtolower($attr['FACE']));
				$this->issetfont=true;
			}
			break;
	}
}

function CloseTag($tag)
{
	//Closing tag
	if($tag=='STRONG')
		$tag='B';
	if($tag=='EM')
		$tag='I';
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF='';
	if($tag=='FONT'){
		if ($this->issetcolor==true) {
			$this->SetTextColor(0);
		}
		if ($this->issetfont) {
			$this->SetFont('arial');
			$this->issetfont=false;
		}
	}
}

function SetStyle($tag, $enable)
{
	//Modify style and select corresponding font
	$this->$tag+=($enable ? 1 : -1);
	$style='';
	foreach(array('B','I','U') as $s)
	{
		if($this->$s>0)
			$style.=$s;
	}
	$this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
	//Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}

/////// FUNCIONES PARA GENERAR CODIGOS DE BARRA ///////////////

function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
 {
     $font_angle+=90+$txt_angle;
     $txt_angle*=M_PI/180;
     $font_angle*=M_PI/180;
 
     $txt_dx=cos($txt_angle);
     $txt_dy=sin($txt_angle);
     $font_dx=cos($font_angle);
     $font_dy=sin($font_angle);
 
     $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
     if ($this->ColorFlag)
         $s='q '.$this->TextColor.' '.$s.' Q';
     $this->_out($s);
 }
 
 function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
            $k = $this->k;
            $hp = $this->h;
            if($style=='F')
                    $op='f';
            elseif($style=='FD' || $style=='DF')
                    $op='B';
            else
                    $op='S';
            $MyArc = 4/3 * (sqrt(2) - 1);
            $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

            $xc = $x+$w-$r;
            $yc = $y+$r;
            $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
            if (strpos($corners, '2')===false)
                    $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
            else
                    $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

            $xc = $x+$w-$r;
            $yc = $y+$h-$r;
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
            if (strpos($corners, '3')===false)
                    $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
            else
                    $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

            $xc = $x+$r;
            $yc = $y+$h-$r;
            $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
            if (strpos($corners, '4')===false)
                    $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
            else
                    $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

            $xc = $x+$r ;
            $yc = $y+$r;
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
            if (strpos($corners, '1')===false)
            {
                    $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
                    $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
            }
            else
                    $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
            $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
            $h = $this->h;
            $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
                    $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
}
?>
