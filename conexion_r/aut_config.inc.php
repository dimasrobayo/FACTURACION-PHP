<?php 
    //  Autentificator
    //  Gestión de Usuarios PHP+Mysql ó PostgreSQL
    //  ------------------------------

    // Configuracion General del Sistema
    $sesion_name="autentificator_sac"; // Nombre de la session

    // Datos conexion a la Base de datos
    $db_host="localhost";   // Host, nombre del servidor o IP del servidor BD
    $db_user="root";        // Usuario de BD
    $db_pass="hannah3868"; //          // contraseña de BD
    $db_database="registro_civil"; //registrocivil       // Base de datos que se usuarios
    $db_tabla_user="usuarios"; // Nombre de la tabla que contendra los datos de los usuarios
    
    // Datos del Sistema
    $sistema_name="Registro Civil"; // Nombre de la empresa
    $empresa="Gobernación del Estado Portuguesa"; // Nombre de la empresa
    $enlace_logo="http://www.alcaldiaguanare.gob.ve"; // Nombre de la empresa
    
    // Datos de localidad
    $cod_pais='058';
    $cod_estado='018';
    $cod_municipio='004';
    
    //Datos de Email
    $name_email="atencion.soberanoportuguesa@gmail.com";  //    "atencion.sac@gmail.com";
    $send_email=1;    // Activar el envio de email al Usuario
    $ip_server="201.249.48.61"; // Host, Ip Servidor de Alojamiento
    $dir_name="registro_civil"; // Nombre del Directorio de Alojamiento del Sistema
    
    // Datos de Mensajeria
    $send_sms=1;    // Activar el envio de sms al Usuario
    
    
?>
