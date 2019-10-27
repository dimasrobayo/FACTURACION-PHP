<?php 
 function valid_rif($type, $ci) {
        $ci = $ci."";
        if(strlen($ci)>9 || strlen($ci)<=3) return false;
        $count_digits = strlen($ci);
        if($count_digits==9)
            $count_digits--;
        $calc = array(0,0,0,0,0,0,0,0,0,0);
        $constants = array(4,3,2,7,6,5,4,3,2);
        $type = strtoupper($type);
        if($type=="V")             $calc[0] = 1;
        else if($type=="E")     $calc[0] = 2;
        else if($type=="J")     $calc[0] = 3;
        else if($type=="P")        $calc[0] = 4;
        else if($type=="G")     $calc[0] = 5;
        else return false;
        $sum = $calc[0]*$constants[0];
        $index = count($constants)-1;
        for($i=$count_digits-1;$i>=0;$i--){
            $digit = $calc[$index] = intval($ci[$i]);
            $sum += $digit*$constants[$index--];
        }
       
        $final_digit = $sum%11;
        if($final_digit>0) {
            $final_digit = 11 - $final_digit;

            if ($final_digit>9) {
                $final_digit = 0;
            }
        } else {
            $final_digit_legal = intval($ci[8]);
        }

        if ($final_digit>9) {
            $final_digit = 0;
        }

        if(strlen($ci)==9 && ($final_digit_legal!=$final_digit && $final_digit_legal!=0))
            return false;
        $calc[9] = strlen($ci)==9?$final_digit_legal:$final_digit;
        $rif = $type;
        for($i = 1; $i < count($calc); ++$i)
            $rif .= $calc[$i];
        return $rif;
    }
    echo valid_rif($_REQUEST['nacionalidad'],$_REQUEST['cedula']);
 ?>