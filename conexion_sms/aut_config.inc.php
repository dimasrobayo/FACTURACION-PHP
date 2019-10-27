<?php 
//  Autentificator
//  Gesti� de Usuarios PHP+Mysql
//  by Pedro Noves V. (Cluster) adaptado a postgres por dimas robayo
//  clus@hotpop.com
//  ------------------------------

// Configuracion

// Nombre de la session (puede dejar este mismo)
$usuarios_sesion="autentificator_hannah";

// Datos conexion a la Base de datos (MySql)
$sql_host_sms="localhost";  // Host, nombre del servidor o IP del servidor Mysql.
$sql_usuario_sms="dimas";        // Usuario de Mysql
$sql_pass_sms="hannah3868";           // contrase� de Mysql

$sql_db_sms="mensajeria";     // Base de datos que se usar�
$sql_tabla_sms="usuarios"; // Nombre de la tabla que contendr�los datos de los usuarios
$sistema_name_sms="SMS MASIVO"; // Nombre de la empresa
$empresa_sms="HannaH c.a"; // Nombre de la empresa
$enlace_logo_sms="http://www.visionhannah.com"; // Nombre de la empresa

$status_dispositivo=1; // 1 si el dispositivo esta conectado en el servidor base.
$puerto_dev="ttyACM0"; // Nombre del Puerto de Ubicacion del Dispositivo
$title_sms="De: #VisiónHannah"; //Titulo inicial del sms
?>
