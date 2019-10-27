<?php 
// Motor autentificaci� usuarios.

// Cargar datos conexion y otras variables.
require ("conexion/aut_config.inc.php");


// chequear p�ina que lo llama para devolver errores a dicha p�ina.

$url = explode("?",$_SERVER['HTTP_REFERER']);
$pag_referida=$url[0];
$redir=$pag_referida;
// chequear si se llama directo al script.
if ($_SERVER['HTTP_REFERER'] == "")
{
die(header ("Location:  index.php?error_login=7"));
//die ("Error cod.:1 - Acceso incorrecto!");
exit;
}


// Chequeamos si se est�autentificandose un usuario por medio del formulario
if (isset($_POST['user']) && isset($_POST['pass'])) {

// Conexi� base de datos.
// si no se puede conectar a la BD salimos del scrip con error 0 y
// redireccionamos a la pagina de error.
//$db_conexion= mysql_connect("$sql_host", "$sql_usuario", "$sql_pass") or die(header ("Location:  $redir?error_login=0"));
//mysql_select_db("$sql_db");

//agregada por mi
$con=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

// realizamos la consulta a la BD para chequear datos del Usuario.
//$usuario_consulta = mysql_query("SELECT ID,usuario,pass,nivel_acceso FROM $sql_tabla WHERE usuario='".$_POST['user']."'") or die(header ("Location:  $redir?error_login=1"));

//agregada por mi
$usuario_consulta=pg_query($con, "SELECT * FROM $sql_tabla WHERE usuario='".$_POST['user']."'") or die(header ("Location:  $redir?error_login=1"));

 // miramos el total de resultado de la consulta (si es distinto de 0 es que existe el usuario)
 //if (mysql_num_rows($usuario_consulta) != 0) {

//agregada por mi
if (pg_num_rows($usuario_consulta) != 0) {

    // eliminamos barras invertidas y dobles en sencillas
	$login = stripslashes($_POST['user']);
	// encriptamos el password en formato md5 irreversible.
	$password = md5($_POST['pass']);
	
	// almacenamos datos del Usuario en un array para empezar a chequear.
	//agregada por mi
	$usuario_datos=pg_fetch_array($usuario_consulta);
    
    // liberamos la memoria usada por la consulta, ya que tenemos estos datos en el Array.
    //agregada por mi
    pg_free_result($usuario_consulta);
    
    // cerramos la Base de dtos.
    //mysql_close($db_conexion);
    
    //agregada por mi
    pg_close($con);
    
    // chequeamos el nombre del usuario otra vez contrastandolo con la BD
    // esta vez sin barras invertidas, etc ...
    // si no es correcto, salimos del script con error 4 y redireccionamos a la
    // p�ina de error.
	if ($login != $usuario_datos['usuario']) 
	{         
       	Header ("Location: $redir?error_login=4");
		exit;
	}
	
	if ($usuario_datos['status'] == 0) 
	{         
       	Header ("Location: $redir?error_login=8");
		exit;
	}

    // si el password no es correcto ..
    // salimos del script con error 3 y redireccinamos hacia la p�ina de error
    if ($password != $usuario_datos['pass']) 
    {
		Header ("Location: $redir?error_login=3");
		exit;
	}

    // Paranoia: destruimos las variables login y password usadas
    unset($login);
    unset($password);

    // En este punto, el usuario ya esta validado.
    // Grabamos los datos del usuario en una sesion.
    
     // le damos un mobre a la sesion.
    session_name($usuarios_sesion);
     // incia sessiones
    session_start();

    // Paranoia: decimos al navegador que no "cachee" esta p�ina.
    session_cache_limiter('nocache,private');
    
    // Asignamos variables de sesi� con datos del Usuario para el uso en el
    // resto de p�inas autentificadas.

	// definimos usuarios_id como IDentificador del usuario en nuestra BD de usuarios
	$_SESSION['usuario_id']=$usuario_datos['cedula'];
	 
	// definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
	$_SESSION['usuario_nivel']=$usuario_datos['nivel_acceso'];
	 
	//definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
	$_SESSION['usuario_login']=$usuario_datos['usuario'];
	
	//definimos usuario_password con el password del usuario de la sesi� actual (formato md5 encriptado)
	$_SESSION['usuario_password']=$usuario_datos['pass'];
	 
	//definimos usuario_status con el status del usuario de la sesi� actual 
	$_SESSION['usuario_nombre']=$usuario_datos['nombre_usuario'];
	 
	$_SESSION['usuario_apellido']=$usuario_datos['apellido_usuario'];
    
	$_SESSION['cedula']=$usuario_datos['cedula'];


    // Hacemos una llamada a si mismo (scritp) para que queden disponibles
    // las variables de session en el array asociado $HTTP_...
    $pag=$_SERVER['PHP_SELF'];
    Header ("Location: $pag?");
    exit;
    
   } else {
      // si no esta el nombre de usuario en la BD o el password ..
      // se devuelve a pagina q lo llamo con error
      Header ("Location: $redir?error_login=2");
      exit;}
} else {

// -------- Chequear sesi� existe -------

// usamos la sesion de nombre definido.
session_name($usuarios_sesion);
// Iniciamos el uso de sesiones
session_start();

// Chequeamos si estan creadas las variables de sesi� de identificaci� del usuario,
// El caso mas comun es el de una vez "matado" la sesion se intenta volver hacia atras
// con el navegador.

if (!isset($_SESSION['usuario_login']) && !isset($_SESSION['usuario_password'])){
// Borramos la sesion creada por el inicio de session anterior
session_destroy();
die(header ("Location:  index.php?error_login=7"));
//die ("Error cod.: 2 - Acceso incorrecto!");
exit;
}
}
?>
