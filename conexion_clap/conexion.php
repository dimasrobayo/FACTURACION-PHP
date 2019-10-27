<?php
// Report runtime errors
//error_reporting(E_ERROR /*| E_WARNING */| E_PARSE);
// Conectando, seleccionando la base de datos
//$link = mysql_connect('localhost', 'root', '')
  //  or die('' . mysql_error());
//mysql_select_db('ssemg') or die();
    //  Autentificator
    //  Gestión de Usuarios PHP+Mysql ó PostgreSQL
    //  ------------------------------

    // Configuracion General del Sistema
    $usuarios_sesion="autenticador_sac"; // Nombre de la session

    // Datos conexion a la Base de datos
    $sql_host="localhost";  // Host, nombre del servidor o IP del servidor DB
    $sql_usuario="root";        // Usuario DB
    $sql_pass="hannah3868";           // contraseña DB
    $sql_db="ssemg";     // Base de datos que se usará
    $sql_tabla="usuaio_sistema"; // Nombre de la tabla que contendrá los datos de los usuarios
    
    // Datos del Sistema
    $sistema_name="SALA SITUACIONAL"; // Nombre de la empresa
    $empresa="Alcaldia de Guanare"; // Nombre de la empresa
    $enlace_logo="http://www.alcaldiaguanare.gob.ve"; // Nombre de la empresa
    

?>