<?php 
//    if(!defined('INCLUDE_CHECK')){
//        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
//        //die('Usted no está autorizado a ejecutar este archivo directamente');
//        exit;
//    }
// Establecer Fechas
    $m=date("n");
    switch($m) {
       case 1:
          $mes="Enero"; 
          break;
       case 2:
          $mes="Febrero"; 
          break;
       case 3:
          $mes="Marzo"; 
          break;
       case 4:
          $mes="Abril"; 
          break;
       case 5:
          $mes="Mayo"; 
          break;
       case 6:
          $mes="Junio"; 
          break;
       case 7:
          $mes="Julio"; 
          break;
       case 8:
          $mes="Agosto"; 
          break;
       case 9:
          $mes="Septiembre"; 
          break;
       case 10:
          $mes="Octubre"; 
          break;
       case 11:
          $mes="Noviembre"; 
          break;
       case 12:
          $mes="Diciembre"; 
          break; 

    }	
    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");	
    $hora=date("h").":".date("m").":".date("s")." ".date("a");
    $fecha=$dias[date("w")].", ". date("d")." de ".$mes." de ".date("Y");	
    // fin Establecimiento de Fechas

    //calcular edad
    function CalculaEdad($fecha) {
        list($Y,$m,$d) = explode("-",$fecha);
        return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
    }

    function simple_date($datetime){
        list($date, $time) = explode(' ', $datetime);
        list($year, $month, $day) = explode('-', $date);		
        return $day.'/'.$month.'/'.$year.' '.$time;
    }

    function get_hour(){
        for($i=0;$i<24;$i++){
            $hour = $i;
            if($hour<10) $hour = "0".$hour;
            echo "<option value=\"".$hour."\">".$hour."</option>"; 
        }
    }

    function get_minute(){
        for($i=0;$i<60;$i=$i+5){
            $min = $i;
            if($min<10) $min = "0".$min;
            echo "<option value=\"".$min."\">".$min."</option>"; 
        }
    } 

    if ( ! function_exists('nbs')){
        function nbs($num = 1){
            return str_repeat("&nbsp;", $num);
        }
    }


    function _get_message_length($message=NULL){
        $msg_length = 0;
        $msg_char = _string_split($message);
        foreach($msg_char as $char){
            if(_is_special_char($char)){
                $msg_length += 2;
            }else{
                $msg_length += 1;
            }
        }
        return $msg_length;
    }

    function _is_special_char($char){
        // GSM Default 7-bit special character (count as 2 char)
        $special_char = array('^','{','}','[',']','~','|','€','\\');

        // GSM Default 7-bit character (count as 1 char)
        $default = array('@', '£', '$', '¥', 'è', 'é', 'ù', 'ì', 'ò', 'Ç', 'Ø', 'ø', 'Å', 'å', 'Δ', '_', 'Φ', 'Γ', 'Λ',
                                'Ω', 'Π', 'Ψ', 'Σ', 'Θ', 'Ξ', 'Æ', 'æ', 'É', '!', '"', '#', '¤', '%', '&', '\'', '(',')', '*', '+', 
                                ',', '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', '>', '?', 
                                '¡', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 
                                'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Ñ', '§', '¿', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 
                                'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ä', 'ñ', 'à');

        if(in_array($char, $special_char)){
            return TRUE;
        }else{
            return FALSE;
        }
    }	

    function _string_split($string, $length=1){
        $len = mb_strlen($string);
        $result = array();
        for($i=0;$i<$len;$i+=$length){
            $char = mb_substr($string, $i, $length);
            array_push($result, $char);
        }
        return $result;
    }

    function _get_message_multipart($message=NULL, $multipart_length=NULL){
        $char = _string_split($message);
        $string = "";
        $left = 153;
        $char_taken = 0;
        $msg = array();

        while (list($key, $val) = each($char)){
            if($left>0){
                if(_is_special_char($val)){
                    if($left>1){
                            $string .= $val;
                            $left -= 2;
                    }else{
                            $left = 0;
                            prev($char);
                            $char_taken--;
                    }
                }else{
                    $string .= $val;
                    $left -= 1;
                }
            }
            $char_taken++;

            if($left==0 OR $char_taken==mb_strlen($message)){
                $msg[] = $string;
                $string = "";
                $left = 153;
            }
        }
        return $msg;
    }

    // --------------------------------------------------------------------

    function numtoletras($xcifra)
    { 
    $xarray = array(0 => "Cero",
    1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", 
    "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", 
    "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA", 
    100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
    //
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false))
            {
            if ($xpos_punto == 0)
                    {
                    $xcifra = "0".$xcifra;
                    $xpos_punto = strpos($xcifra, ".");
                    }
            $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
            $xdecimales = substr($xcifra."00", $xpos_punto + 1, 2); // obtengo los valores decimales
            }

    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for($xz = 0; $xz < 3; $xz++)
            {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = 0; $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
            $xexit = true; // bandera para controlar el ciclo del While	
            while ($xexit)
                    {
                    if ($xi == $xlimite) // si ya llegó al límite m&aacute;ximo de enteros
                            {
                            break; // termina el ciclo
                            }

                    $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
                    $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
                    for ($xy = 1; $xy < 4; $xy++) // ciclo para revisar centenas, decenas y unidades, en ese orden
                        {
                        switch ($xy) {
                            case 1: // checa las centenas
                                if (substr($xaux, 0, 3) < 100) // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                                    {
                                    }
                                else {
                                    $xseek = $xarray[substr($xaux, 0, 3)]; // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                    if ($xseek) {
                                        $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                        if (substr($xaux, 0, 3) == 100) 
                                            $xcadena = " ".$xcadena." CIEN ".$xsub;
                                        else
                                            $xcadena = " ".$xcadena." ".$xseek." ".$xsub;
                                        $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                                    }
                                    else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                        $xseek = $xarray[substr($xaux, 0, 1) * 100]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                        $xcadena = " ".$xcadena." ".$xseek;
                                    } // ENDIF ($xseek)
                                } // ENDIF (substr($xaux, 0, 3) < 100)
                                break;
                            case 2: // checa las decenas (con la misma lógica que las centenas)
                                    if (substr($xaux, 1, 2) < 10)
                                        {
                                        }
                                    else {
                                        $xseek = $xarray[substr($xaux, 1, 2)];
                                        if ($xseek) {
                                            $xsub = subfijo($xaux);
                                            if (substr($xaux, 1, 2) == 20)
                                                $xcadena = " ".$xcadena." VEINTE ".$xsub;
                                            else
                                                $xcadena = " ".$xcadena." ".$xseek." ".$xsub;
                                            $xy = 3;
                                        }
                                        else {
                                            $xseek = $xarray[substr($xaux, 1, 1) * 10];
                                            if (substr($xaux, 1, 1) * 10 == 20)
                                                $xcadena = " ".$xcadena." ".$xseek;
                                            else	
                                                $xcadena = " ".$xcadena." ".$xseek." Y ";
                                        } // ENDIF ($xseek)
                                    } // ENDIF (substr($xaux, 1, 2) < 10)
                                    break;
                            case 3: // checa las unidades
                                if (substr($xaux, 2, 1) < 1) // si la unidad es cero, ya no hace nada
                                    {
                                    }
                                else {
                                    $xseek = $xarray[substr($xaux, 2, 1)]; // obtengo directamente el valor de la unidad (del uno al nueve)
                                    $xsub = subfijo($xaux);
                                    $xcadena = " ".$xcadena." ".$xseek." ".$xsub;
                                } // ENDIF (substr($xaux, 2, 1) < 1)
                                break;
                            } // END SWITCH
                        } // END FOR
                        $xi = $xi + 3;
                } // ENDDO

                if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
                    $xcadena.= " DE";

                if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
                    $xcadena.= " DE";

                // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
                if (trim($xaux) != "") {
                    switch ($xz) {
                        case 0:
                            if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                                $xcadena.= "UN BILLON ";
                            else
                                $xcadena.= " BILLONES ";
                            break;
                        case 1:
                            if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                                $xcadena.= "UN MILLON ";
                            else
                                $xcadena.= " MILLONES ";
                            break;
                        case 2:
                            if ($xcifra < 1 ) {
                                $xcadena = "CERO BOLIVARES CON $xdecimales/100 CÉNTIMOS.";
                            }
                            if ($xcifra >= 1 && $xcifra < 2) {
                                $xcadena = "UN BOLIVAR CON $xdecimales/100 CÉNTIMOS. ";
                            }
                            if ($xcifra >= 2) {
                                $xcadena.= " BOLIVARES CON $xdecimales/100 CÉNTIMOS. "; // 
                            }
                            break;
                        } // endswitch ($xz)
                    } // ENDIF (trim($xaux) != "")
                    // ------------------      en este caso, para México se usa esta leyenda     ----------------
                    $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
                    $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles 
                    $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
                    $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles 
                    $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
                    $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
                    $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
            } // ENDFOR	($xz)
            return trim($xcadena);
    } // END FUNCTION


    function subfijo($xx)
    { // esta función regresa un subfijo para la cifra
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
        //	
        if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";
        //
        return $xsub;
    } // END FUNCTION


?>