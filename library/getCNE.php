<?php
error_reporting(E_ALL ^ E_NOTICE);
/**
 * Clase encargada de gestionar diferentes paginas mediantes consumo sea por curl
 * hay que tener claro que podemos ejercer el consumo del html en caso que alla un cambio del resultado del
 * html del CNE hay que modificar las clases porque cambian las posiciones
 */
class SearchCurl {

    /**
     * Permite consumir e interpretar la informacion del resultado del curl para solo extraer los datos necesarios
     * @author Gregorio Jose Bolivar Bolivar <elalconxvii@gmail.com>
     * @param string $nac Nacionalidad de la persona
     * @param integer $ci Cedula de la persona
     * @return string Json del resultado consultado de los datos asociados a la persona
     */
    public static function SearchCNE($nac, $ci) {
        $url = "http://www.cne.gov.ve/web/registro_electoral/ce.php?nacionalidad=$nac&cedula=$ci";
        $resource = self::geUrl($url);
        $text = strip_tags($resource);
        $findme = 'SERVICIO ELECTORAL'; // Identifica que si es población Votante
        $pos = strpos($text, $findme);

        $findme2 = 'ADVERTENCIA'; // Identifica que si es población Votante
        $pos2 = strpos($text, $findme2);

        if ($pos == TRUE AND $pos2 == FALSE) {
            // Codigo buscar votante
            $rempl = array('Cédula:', 'Nombre:', 'Estado:', 'Municipio:', 'Parroquia:', 'Centro:', 'Dirección:', 'SERVICIO ELECTORAL', 'Mesa:');
            $r = trim(str_replace($rempl, '|', self::limpiarCampo($text)));
            $resource = explode("|", $r);
            $datos = explode(" ", self::limpiarCampo($resource[2]));
            $datoJson = array('error' => 0, 'nacionalidad' => $nac, 'cedula' => $ci, 'nombres' => $datos[0] . ' ' . $datos[1], 'apellidos' => $datos[2] . ' ' . $datos[3], 'inscrito' => 'SI', 'cvestado' => self::limpiarCampo($resource[3]), 'cvmunicipio' => self::limpiarCampo($resource[4]), 'cvparroquia' => self::limpiarCampo($resource[5]), 'centro' => self::limpiarCampo($resource[6]), 'direccion' => self::limpiarCampo($resource[7]));
        } elseif ($pos == FALSE AND $pos2 == FALSE) {
            // Codigo buscar votante
            $rempl = array('Cédula:', 'Primer Nombre:', 'Segundo Nombre:', 'Primer Apellido:', 'Segundo Apellido:', 'ESTATUS');
            $r = trim(str_replace($rempl, '|', $text));
            $resource = explode("|", $r);
            $datoJson = array('error' => 0, 'nacionalidad' => $nac, 'cedula' => $ci, 'nombres' => self::limpiarCampo($resource[2]) . ' ' . self::limpiarCampo($resource[3]), 'apellidos' => self::limpiarCampo($resource[4]) . ' ' . self::limpiarCampo($resource[5]), 'inscrito' => 'NO');
        } elseif ($pos == FALSE AND $pos2 == TRUE) {
            $datoJson = array('error' => 1, 'nacionalidad' => $nac, 'cedula' => $ci, 'nombres' => NULL, 'apellidos' => NULL, 'inscrito' => 'NO');
        }
        return json_encode($datoJson);
    }

    /**
     * Permite consultar cualquier pagina mediante curl
     * @author Gregorio Jose Bolivar Bolivar <elalconxvii@gmail.com>
     * @param string $url url al cual desea consultar
     * @return string HTML del resultado consultado
     */
    public static function geUrl($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // almacene en una variable
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if (curl_exec($curl) === false) {
            echo 'Curl error: ' . curl_error($curl);
        } else {
            $return = curl_exec($curl);
        }
        curl_close($curl);

        return $return;
    }

    /**
     * Permite limpiar los valores del renorno del carro (\n \r \t) 
     * @author Gregorio Jose Bolivar Bolivar <elalconxvii@gmail.com>
     * @param string $valor Valor que queremos limpiar de caracteres no permitidos
     * @return string Te devuelve los mismo valores pero sin los valores del renorno del carro
     */
    public static function limpiarCampo($valor) {
        $rempl = array('\n', '\t');
        $r = trim(str_replace($rempl, ' ', $valor));
        return str_replace("\r", "", str_replace("\n", "", str_replace("\t", "", $r)));
    }

}

$curls = new SearchCurl();
echo $var = $curls->SearchCNE($_REQUEST['nacionalidad'], $_REQUEST['cedula']);

//Descodificamos para leer
//echo $getVimeo = json_decode($var,true);

//Asociamos los campos del JSON a variables
//echo $getVimeo['nombres'];
?>