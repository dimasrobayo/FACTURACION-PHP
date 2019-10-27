<?php 

// Motor autentificaci� usuarios.

// Cargar datos conexion y otras variables.
require ("conexion/conexion.php");


// chequear p�ina que lo llama para devolver errores a dicha p�ina.

$url = explode("?",$_SERVER['HTTP_REFERER']);
$pag_referida=$url[0];
$redir=$pag_referida;
// chequear si se llama directo al script.
if ($_SERVER['HTTP_REFERER'] == "") {
    header ("Location: index.php?view=login&msg_login=5");
    //die ("Error cod.:1 - Acceso incorrecto!");
    exit;
}

// Chequeamos si se est�autentificandose un usuario por medio del formulario
if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['code'])) {

 // chequeamos que se introdujo el codigo aleatorio.
    $cod_aleatorio = stripslashes($_POST['code']);
    if($cod_aleatorio == ''){
        header ("Location: $redir?view=login&msg_login=7");
        exit;
    }
    // Conexi� base de datos.
    // si no se puede conectar a la BD salimos del scrip con error 0 y
    // redireccionamos a la pagina de error.
    //$db_conexion= mysql_connect("$sql_host", "$sql_usuario", "$sql_pass") or die(header ("Location:  $redir?error_login=0"));
    //mysql_select_db("$sql_db");

    //agregada por mi
    $con= mysql_connect("$sql_host", "$sql_usuario", "$sql_pass") or die(header ("Location:  $redir?error_login=0"));
    mysql_select_db("$sql_db");

    // realizamos la consulta a la BD para chequear datos del Usuario.
    //$usuario_consulta = mysql_query("SELECT ID,usuario,pass,nivel_acceso FROM $sql_tabla WHERE usuario='".$_POST['user']."'") or die(header ("Location:  $redir?error_login=1"));

    //agregada por mi
    $usuario_consulta = mysql_query("SELECT * FROM usuario_sistema WHERE usuario='".$_POST['user']."'");
     // miramos el total de resultado de la consulta (si es distinto de 0 es que existe el usuario)
     //if (mysql_num_rows($usuario_consulta) != 0) {

    //agregada por mi
    if (mysql_num_rows($usuario_consulta) != 0) {

        // eliminamos barras invertidas y dobles en sencillas
	$login = stripslashes($_POST['user']);
	// encript5amos el password en formato md5 irreversible.
	$password = md5($_POST['pass']);
	
	// almacenamos datos del Usuario en un array para empezar a chequear.
	$usuario_datos=mysql_fetch_array($usuario_consulta);
    
        // liberamos la memoria usada por la consulta, ya que tenemos estos datos en el Array.
        mysql_free_result($usuario_consulta);
        
        
        // llamamos a las funciones para validar el codigo aleatorio con el que introdujo el usuario.
        include("captcha/securimage.php");
        $img = new Securimage();
        $valid = $img->check($_POST['code']);

        // si el codigo no es correcto ..
        // salimos del script con error 9 y redireccinamos hacia la p�ina de error
        if($valid != true){
            header ("Location: $redir?view=login&msg_login=8");
            exit;
        }
        
        // chequeamos el nombre del usuario otra vez contrastandolo con la BD
        // esta vez sin barras invertidas, etc ...
        // si no es correcto, salimos del script con error 4 y redireccionamos a la
        // p�ina de error.
        if ($login != $usuario_datos['usuario']) {         
            header ("Location: $redir?view=login&msg_login=3");
            exit;
        }
		
        /*if ($usuario_datos['status'] == 0) {         
            header ("Location: $redir?view=login&msg_login=9");
            exit;
        }*/
        // si el password no es correcto ..
        // salimos del script con error 3 y redireccinamos hacia la p�ina de error
        if ($password != $usuario_datos['clave']) {
            header ("Location: $redir?view=login&msg_login=11");
            exit;
        }
        
        // Borramos la sesion creada por el inicio de session anterior
        session_destroy();

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
        $_SESSION['id']=$usuario_datos['cedula_usu'];

        // definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
        $_SESSION['nivel']=$usuario_datos['nivel'];

        //definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
        $_SESSION['user']=$usuario_datos['usuario'];

        //definimos usuario_password con el password del usuario de la sesi� actual (formato md5 encriptado)
        $_SESSION['pass']=$usuario_datos['clave'];
        
        $_SESSION['username']=$usuario_datos['nombre']." ".$usuario_datos['apellido'];
        
        //definimos codigo_aleatorio con el codigo de acceso del usuario.
        $_SESSION['cedula_usu']=$cod_aleatorio;
       
        //definimos unidad del usuario.
        //$_SESSION['cod_unidad']=$usuario_datos['cod_unidad'];
        
        //definimos fecha de ultimo acceso del usuario.
        //$_SESSION['fecha_ultimoacceso']=$usuario_datos['fecha_ultimoacceso'];
        
       // $ced_usuario=$usuario_datos['cedula_usu'];
        //$fecha_acceso=date("Y-m-d H:i:s");
        //$update_usuario=mysql_query($con, "UPDATE $sql_tabla SET fecha_ultimoacceso='NOW' WHERE cedula_usuario='$ced_usuario'") or die(mysql_last_error());
			
        mysql_close($con);

        // Paranoia: destruimos las variables login, password y codigo_aleatorio usadas
        unset($login);
        unset($password);
        unset($cod_aleatorio); 
        
        // Hacemos una llamada a si mismo (scritp) para que queden disponibles
        // las variables de session en el array asociado $HTTP_...
        $pag=$_SERVER['PHP_SELF'];			
        $view=$_GET["view"];
        header ("Location: $pag?view=$view");
        exit;
    
    } else {
      // si no esta el nombre de usuario en la BD o el password ..
      // se devuelve a pagina q lo llamo con error
      header ("Location: $redir?view=login&msg_login=1");
      exit; 
    }
} else {

    // -------- Chequear sesi� existe -------

    // usamos la sesion de nombre definido.
    session_name($usuarios_sesion);
    // Iniciamos el uso de sesiones
    session_start();

    // Chequeamos si estan creadas las variables de sesi� de identificaci� del usuario,
    // El caso mas comun es el de una vez "matado" la sesion se intenta volver hacia atras
    // con el navegador.

  
}
?>
