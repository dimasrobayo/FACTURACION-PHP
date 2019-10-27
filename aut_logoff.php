<?php
    define('INCLUDE_CHECK',true); // Activamos como Principal, para verificar los de mas formularios como dependientes.
//    require("conexion/aut_verifica.inc.php"); //validar sessiones del usuario

    if (isset($_GET["user"])){
        $cedula_usuario=$_GET["user"];		
    }	
    // Error reporting:
    error_reporting(E_ALL^E_NOTICE);
        
    $con=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass")or die(pg_last_error());
    $usuario_consulta=pg_query($con, "SELECT * FROM $sql_tabla WHERE usuario='".$_GET['user']."'") or die(pg_last_error());
    $update_usuario=pg_query($con, "UPDATE $sql_tabla SET fecha_logout='NOW', status_login='off' WHERE cedula_usuario='$cedula_usuario'") or die(pg_last_error());	
    
    $query="SELECT * FROM usuarios WHERE cedula_usuario = '$cedula_usuario'";
    $result = pg_query($query) or die(pg_last_error());
    $usuario=  pg_fetch_array($result);
    pg_free_result($result);
    pg_close($con);
?>
<table align="center" border="0" cellpadding="20" cellspacing="20" width="100%">
    <tbody>
        <tr>			
            <td> 
                <div align="center"> 
                    <h3 class="info">
                        <font size="3">Estimado <font size="3" color="blue" ><?php echo $usuario[usuario];?></font>, Usted ha sido desconectado correctamente del Sistema.
                            <br>
                            Click <a href="index.php?view=login">aqu&iacute;</a> para ingresar de nuevo.
                        </font>
                    </h3>
                </div> 	
            </td>
        </tr> 			
    </tbody>
</table>
