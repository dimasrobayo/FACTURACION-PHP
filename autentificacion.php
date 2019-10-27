<?php if (isset($_GET['msg_login'])){ ?>
    <script type="text/javascript">
        function ver_msg(){
            Effect.Fade('msg');
        }  
        setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
    </script>
<?php } ?>
<div id="ctr" align="center">
    <div class="loginform">
        <div align="center">	
            <?php					
                // Mostrar error de Autentificaci�n.
                include ("aut_mensaje_login.inc.php");
                if (isset($_GET['msg_login'])){
                    $msg=$_GET['msg_login'];
                    //echo '<div class="system-message" align="left"><h2>Error: '. $msg_login[$msg].'</h2></div>';
                    echo '<div align="left"><h3 class="error">Error: '.$msg_login[$msg].'</h3></div>';						
                }
            ?>
        </div>
        
	<div class="login-form">
            <div align="center">
		<font color="#C64934" size="+1" face="verdana"><strong>Acceso al Sistema</strong></font>
            </div>	
            <form action="index2.php?view=home" method="POST" name="loginForm" id="loginForm">
                <div class="form-block">
                    <div class="inputlabel">
			Usuario
                    </div>
	
                    <div>
			<input name="user" type="text" class="inputbox" size="15" />
                    </div>
                    <br />
	
                    <div class="inputlabel">
			Contrase&ntilde;a
                    </div>
	
                    <div>
			<input name="pass" type="password" class="inputbox" size="15" />
                    </div>
                    <br />
				
                    <div class="inputlabel1">
			C&oacute;digo:
                    </div>
					
                    <table border="0">
                        <tr>
                            <td>
                                <input name="code" type="text" class="inputboxc" size="15" />
                            </td>
                            <td>
                                <img id="siimage" align="left" width="100" height="30" vspace="3" style="padding-right: 0px; padding-top: 0px; padding-bottom: 0px; padding-left: 1px; vertical-align: middle;	margin-top: 0px; border: 0" src="captcha/securimage_show.php?sid=<?php echo md5(time()) ?>"/>
                            </td>
                            <td><!-- pass a session id to the query string of the script to prevent ie caching -->
                                <a tabindex="-1" style="border-style: none" href="#" title="Actualizar Imagen" onClick="document.getElementById('siimage').src = 'captcha/securimage_show.php?sid=' + Math.random(); return false"><img src="images/refresh.png" alt="Actualizar Imagen" border="0" onClick="this.blur()" align="bottom" style="padding-right: 0px; padding-top: 0px; padding-bottom: 0px; padding-left: 0px; vertical-align: middle;	margin-top: 6px; border: 0" /></a>
                            </td>
			</tr>
                    </table>
		
                    <div  class="button-blok" align="left">
                        <input type="submit" name="submit"  class="buttonn" value="Entrar" />
                    </div>
					
		</div>
            </form>
	</div>

        <div class="login-text">
            <div class="ctr">
		<img src="css/images/security.png" width="64" height="64" alt="security" />
            </div>
	
            <p>Bienvenid&#064;!</p>
            <p>Utilice un nombre de usuario la contrase&ntilde;a y el C&oacute;digo v&aacute;lidos para accesar al Sistema.</p>

            <?php
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
		$fecha=date("d")." de ".$mes." de ".date("Y");
		//$hora=date("h").":".date("m").":".date("s")." ".date("a");
		echo '<p>'.$fecha.'.</p>'; 
		//echo "<br>";
		//echo $hora;
			
		/*Esto es para colocar correctamente la fecha despues de consultarla
		$fecha=$vector[fecha_inicio];
		$dia=substr( $fecha,8,2);
		$mes=substr( $fecha,5,2);
		$ano=substr( $fecha,0,4);
		$fechabien=$dia-$mes-$ano;
		*/
            ?>

	</div>
	<div class="clr"></div>
    </div>
</div>

<noscript>
	!Advertencia! Javascript debe estar habilitado para el correcto funcionamiento del Sistema
</noscript>
