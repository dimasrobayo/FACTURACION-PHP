<?php
	if(!defined('INCLUDE_CHECK')){
		echo ('<div align="center"><img  src="images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
		//die('Usted no está autorizado a ejecutar este archivo directamente');
		exit;
	} 
?>

<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="js/tabs/tabpane.css" /><script type="text/javascript" src="js/tabs/tabpane_mini.js"></script>		
<div align="center" class="centermain">
	<div class="main">
		<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="js/tabs/tabpane.css" /><script type="text/javascript" src="js/tabs/tabpane_mini.js"></script>
		<table class="adminheading" border="0">
		<tr>
			<th>
				Informaci&oacute;n
			</th>
		</tr>
		</table>
		
		<div class="tab-page" id="sysinfo"><script type="text/javascript">
	var tabPane1 = new WebFXTabPane( document.getElementById( "sysinfo" ), 0 )
</script>
<div class="tab-page" id="system-page"><h2 class="tab">Info Sistema</h2><script type="text/javascript">
  tabPane1.addTabPage( document.getElementById( "system-page" ) );</script>			
                <table class="adminform" width="100%">

			<tr>
				<th colspan="2">
					Informaci&oacute;n del Sistema
				</th>
			</tr>
			<tr>
				<td valign="top" width="200">
					<strong>PHP instalado en:</strong>
				</td>

				<td>
				<?php  echo $_SERVER['HOST_NAME']; ?>
				<?php echo php_uname(); ?>

				</td>
			</tr>			
			<tr>
				<td>
					<strong>Versi&oacute;n de PHP :</strong>
				</td>
				<td>
					<?php  echo phpversion();?>				</td>

			</tr>
			<tr>
				<td>
					<strong>Servidor Web:</strong>
				</td>
				<td>
					<?php  echo $_SERVER['SERVER_SOFTWARE']; ?>	
				</td>
			</tr>

			<tr>
				<td>
					<strong>Interfaz del Servidor Web a PHP:</strong>
				</td>
				<td>
					<?php echo php_sapi_name(); ?>
				</td>
			</tr>
			<tr>

				<td>
					<strong>Version del sistema:</strong>
				</td>
				<td>
					versi&oacute;n 1.0
				</td>
			</tr>
			<tr>
				<td>

					<strong>Navegador:</strong>
				</td>
				<td>
				<?php  //echo $_SERVER['HTTP_USER_AGENT']; ?>
				<?php echo phpversion() <= '4.2.1' ? getenv( 'HTTP_USER_AGENT' ) : $_SERVER['HTTP_USER_AGENT'];?>
					
				</td>
			</tr>
<?php 
function get_php_setting($val) {
	$r =  (ini_get($val) == '1' ? 1 : 0);
	return $r ? 'ON' : 'OFF';
}
?>			<tr>
				<td valign="top">
					<strong>Caracter&iacute;sticas relevantes de PHP:</strong>

				</td>
				<td >
					<table>
					<tr>
						<td>
							Safe Mode:
						</td>
						<td>
						<?php echo get_php_setting('safe_mode'); ?>
						</td>

					</tr>
					<tr>
						<td>
							Open basedir:
						</td>
						<td>
						<?php echo (($ob = ini_get('open_basedir')) ? $ob : 'none'); ?>
						</td>
					</tr>
					<tr>

						<td>
							Display Errors:
						</td>
						<td>
						<?php echo get_php_setting('display_errors'); ?>					
						</td>
					</tr>
					<tr>
						<td>
							Short Open Tags:
						</td>

						<td>
						<?php echo get_php_setting('short_open_tag'); ?>	
						</td>
					</tr>
					<tr>
						<td>
							File Uploads:
						</td>
						<td>
						<?php echo get_php_setting('file_uploads'); ?>
						</td>

					</tr>
					<tr>
						<td>
							Magic Quotes:
						</td>
						<td>
						<?php echo get_php_setting('magic_quotes_gpc'); ?>
						</td>
					</tr>
					<tr>

						<td>
							Register Globals:
						</td>
						<td>
						<?php echo get_php_setting('register_globals'); ?>
						</td>
					</tr>
					<tr>
						<td>
							Output Buffering:
						</td>

						<td>
						<?php echo get_php_setting('output_buffering'); ?>
						</td>
					</tr>
					<tr>
						<td>
							Session save path:
						</td>
						<td>
						<?php echo (($sp=ini_get('session.save_path'))?$sp:'none'); ?>
						</td>

					</tr>
					<tr>
						<td>
							Session auto start:
						</td>
						<td>
						<?php echo intval( ini_get( 'session.auto_start' ) ); ?>
						</td>
					</tr>
					<tr>

						<td>
							XML Habilitado:
						</td>
						<td>
						<?php echo extension_loaded('xml')?'Si':'No'; ?>
						</td>
					</tr>
					<tr>
						<td>
							Zlib Habilitado:
						</td>

						<td>
						<?php echo extension_loaded('zlib')?'Si':'No'; ?>
						</td>
					</tr>
<!--					<tr>
						<td>
							Disabled Functions:
						</td>
						<td>
                                                    <?php // echo (($df=ini_get('disable_functions'))?$df:'none'); ?>
																	</td>

					</tr>				-->
					</table>

				</td>
			</tr>
			 
			</table>
		</div><div class="tab-page" id="php-page"><h2 class="tab">Info PHP</h2><script type="text/javascript">
  tabPane1.addTabPage( document.getElementById( "php-page" ) );</script>			
                    <table class="adminform">
			<tr>
				<th >
					Informaci&oacute;n de PHP
				</th>

			</tr>
			<tr>				
				<td>
				<?php
				ob_start();
				phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
				$phpinfo = ob_get_contents();
				ob_end_clean();
				preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output);
				$output = preg_replace('#<table#', '<table class="adminlist" align="center"', $output[1][0]);
				$output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
				$output = preg_replace('#border="0" cellpadding="3" width="600"#', 'border="0" cellspacing="1" cellpadding="4" width="95%"', $output);
				$output = preg_replace('#<hr />#', '', $output);
				echo $output;
				?>
				</td>
			</tr>

			</table>
		</div><div class="tab-page" id="perms"><h2 class="tab">Permisos</h2><script type="text/javascript">
  tabPane1.addTabPage( document.getElementById( "perms" ) );</script>			
                    <table class="adminform">
			<tr>
				<th colspan="2">
					Permisos de Directorios
				</th>
			</tr>
			<tr>

				<td colspan="2">
					<strong>Para el correcto funcionamiento del sistema es necesario que los siguientes directorios tengan permisos de escritura:</strong>
                                </td>   
                        </tr>
			<tr> 
                            <td class="item">images/</td>
                            <td align="left"><b><?if (is_writeable('images')) {?><font color="green">Escribible</font><?} else {?><font color="red">No Escribible</font><?}?></b></td></tr>		
				
			</table>
		</div>
	</div>			
</div>
</div>

