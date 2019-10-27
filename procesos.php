<?php
//    define('INCLUDE_CHECK',true);
    require("conexion/aut_config.inc.php");
    require ("funciones.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	
								
    $acciones=$_GET["accion"]; 
	
//-------------------------------------------------------------------------------------------------//
//--------------------------- inicio funciones para los municipios y parroquias--------------------//
//-------------------------------------------------------------------------------------------------//
    
    if(($acciones=="municipios")){
        $estado=$_GET["codest"];
        $url=$_GET["dir"];
        if (($estado!="")&&($estado!="null")){
            echo '<select name="codmun" id="codmun" onChange="cargarContenidoParroquia();" onClick="cargarContenidoParroquia();">';
            echo '<option value="">----</option>';
            $consultax1="SELECT * from municipios where codest='$estado' order by codmun";
//                    $ejec_consultax1=mysql_query($consultax1);
//                    while($vector=mysql_fetch_array($ejec_consultax1)){
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                    echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
            }
            echo '</select>';
//                    mysql_free_result($ejec_consultax1);	
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="codmun" id="codmun" style="width:180px"  onChange="cargarContenidoParroquia();" onClick="cargarContenidoParroquia();">';
            echo '<option value="">----</option>';
            echo '</select>';
        }	
    }

    if(($acciones=="parroquias")){
        $municipio=$_GET["codmun"];
        $estado=$_GET["codestado"];
        $url=$_GET["dir"];

        if (($municipio!="")&&($estado!="")){
            echo '<select name="codpar" id="codpar" onChange="cargarContenidoComunidad();" onClick="cargarContenidoComunidad();">';
            echo '<option value="">----</option>';
            $consultax1="SELECT * from parroquias where codest='$estado' and codmun='$municipio' order by codpar";
//			$ejec_consultax1=mysql_query($consultax1);
//			while($vector=mysql_fetch_array($ejec_consultax1)){
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
            }
            echo '</select>';
//			mysql_free_result($ejec_consultax1);	
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="codpar" id="codpar" style="width:180px" onChange="cargarContenidoComunidad();" onClick="cargarContenidoComunidad();">';
            echo '<option value="">----</option>';
            echo '</select>';			
        }	
    }
    
    if(($acciones=="comunidades")){
        $parroquia=$_GET["codpar"];
        $municipio=$_GET["codmun"];
        $estado=$_GET["codest"];
        $url=$_GET["dir"];

        if (($municipio!="")&&($estado!="")&&($parroquia!="")){
            echo '<select name="codcom" id="codcom" class="validate[required]" >';
            echo '<option value="">----</option>';
            $consultax1="SELECT * from comunidades where codpar='$parroquia' and codest='$estado' and codmun='$municipio' order by descom";
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                    echo '<option value="'.$vector[0].'">'.$vector[5].'</option>';
            }
            echo '</select>';
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="codcom" id="codcom" class="validate[required]" style="width:180px" >';
            echo '<option value="">----</option>';
            echo '</select>';			
        }
        echo '<a href="javascript: ue_comunidad_add();"><img src="images/agregar.png" alt="Buscar" title="Registrar Comunidad" width="20" height="20" border="0"></a>';
    }
    
//-------------------------------------------------------------------------------------------------//
//--------------------------- inicio funciones para los municipios y parroquias Catalogos--------------------//
//-------------------------------------------------------------------------------------------------//
    if(($acciones=="municipios_cat")){
        $estado=$_GET["codest"];
        $url=$_GET["dir"];
        if (($estado!="")&&($estado!="null")){
            echo '<select name="codmun" id="codmun" onChange="cargarContenidoParroquiaCat();" onClick="cargarContenidoParroquiaCat();">';
            echo '<option value="">----</option>';
            $consultax1="SELECT * from municipios where codest='$estado' order by codmun";
//                    $ejec_consultax1=mysql_query($consultax1);
//                    while($vector=mysql_fetch_array($ejec_consultax1)){
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                    echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
            }
            echo '</select>';
//                    mysql_free_result($ejec_consultax1);	
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="codmun" id="codmun" style="width:180px"  onChange="cargarContenidoParroquiaCat();" onClick="cargarContenidoParroquiaCat();">';
            echo '<option value="">----</option>';
            echo '</select>';
        }	
    }

    if(($acciones=="parroquias_cat")){
        $municipio=$_GET["codmun"];
        $estado=$_GET["codestado"];
        $url=$_GET["dir"];

        if (($municipio!="")&&($estado!="")){
            echo '<select name="codpar" id="codpar" onChange="cargarContenidoComunidadCat();" onClick="cargarContenidoComunidadCat();">';
            echo '<option value="">----</option>';
            $consultax1="SELECT * from parroquias where codest='$estado' and codmun='$municipio' order by codpar";
//			$ejec_consultax1=mysql_query($consultax1);
//			while($vector=mysql_fetch_array($ejec_consultax1)){
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
            }
            echo '</select>';
//			mysql_free_result($ejec_consultax1);	
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="codpar" id="codpar" style="width:180px" onChange="cargarContenidoComunidadCat();" onClick="cargarContenidoComunidadCat();">';
            echo '<option value="">----</option>';
            echo '</select>';			
        }	
    }
    
    if(($acciones=="comunidades_cat")){
        $parroquia=$_GET["codpar"];
        $municipio=$_GET["codmun"];
        $estado=$_GET["codest"];
        $url=$_GET["dir"];

        if (($municipio!="")&&($estado!="")&&($parroquia!="")){
            echo '<select name="codcom" id="codcom" class="validate[required]" >';
            echo '<option value="">----</option>';
            $consultax1="SELECT * from comunidades where codpar='$parroquia' and codest='$estado' and codmun='$municipio' order by descom";
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                    echo '<option value="'.$vector[0].'">'.$vector[5].'</option>';
            }
            echo '</select>';
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="codcom" id="codcom" class="validate[required]" style="width:180px" >';
            echo '<option value="">----</option>';
            echo '</select>';			
        }	
    }
    
    
    // --- SOLICITUDES EN LINEA -- //
    if(($acciones=="tramites")){
        $categoria=$_GET["cod_categoria"];
        $url=$_GET["dir"];

        if (($categoria!="")){
            echo '<select name="cod_tramite" id="cod_tramite" class="validate[required]"  onchange="cargarContenidoTramiteUnidadSol();">';
            echo '<option value="">----</option>';
            $consultax1="SELECT nombre_tramite from tramites where cod_categoria='$categoria' and status_tramite=1 and status_tramite_online=1  group by nombre_tramite";
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                    echo '<option value="'.$vector[nombre_tramite].'">'.$vector[nombre_tramite].'</option>';
            }
            echo '</select>';
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="cod_tramite" id="cod_tramite" class="validate[required]" style="width:180px" >';
            echo '<option value="">----</option>';
            echo '</select>';			
        }	
    }
    
    if(($acciones=="tramites_categorias_unidades")){
        $categoria=$_GET["cod_categoria"];
        $tramite=$_GET["cod_tramite"];
        $url=$_GET["dir"];

        if (($categoria!="" && $tramite!="")){
            echo '<select name="cod_unidad" id="cod_unidad" class="validate[required]" >';
            echo '<option value="">----</option>';
            $consultax1="SELECT * from tramites,unidades where tramites.cod_categoria='$categoria' and tramites.nombre_tramite='$tramite' and tramites.status_tramite=1 and tramites.status_tramite_online=1 and tramites.cod_unidad=unidades.cod_unidad order by nombre_unidad DESC";
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                    echo '<option value="'.$vector[cod_unidad].'">'.$vector[nombre_unidad].'</option>';
            }
            echo '</select>';
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="cod_unidad" id="cod_unidad" class="validate[required]" style="width:180px" >';
            echo '<option value="">----</option>';
            echo '</select>';			
        }	
    }
    
    // --- FIN SOLICITUDES EN LINEA -- //
    
    // --- SOLCITUD EN LINEA --//
    if(($acciones=="unidades_tramites")){
        $categoria=$_GET["cod_categoria"];
        $url=$_GET["dir"];

        if (($categoria!="")){
            echo '<select name="cod_unidad" id="cod_unidad" class="validate[required]" onchange="cargarContenidoTramiteUnidadCategoria();"  >';
            echo '<option value="">----</option>';
            $consultax1="SELECT * from  unidades where status_unidad=1 and cod_unidad IN (select cod_unidad from tramites  where cod_categoria='$categoria' and status_tramite=1 and status_tramite_online=1) order by nombre_unidad DESC";
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                echo '<option value="'.$vector[cod_unidad].'">'.$vector[nombre_unidad].'</option>';
            }
            echo '</select>';
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="cod_unidad" id="cod_unidad" class="validate[required]" style="width:180px" >';
            echo '<option value="">----</option>';
            echo '</select>';			
        }	
    }
    
        
    if(($acciones=="tramites_unidades_categorias")){
        $unidad=$_GET["cod_unidad"];
        $categoria=$_GET["cod_categoria"];
        $url=$_GET["dir"];

        if (($unidad!="" && $categoria!="")){
            echo '<select name="cod_tramite" id="cod_tramite" class="validate[required]" >';
            echo '<option value="">----</option>';
            $consultax1="SELECT * from tramites where cod_unidad='$unidad' and cod_categoria='$categoria' and status_tramite=1 and status_tramite_online=1  order by nombre_tramite";
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                    echo '<option value="'.$vector[0].'">'.$vector[2].'</option>';
            }
            echo '</select>
                  <a href="javascript: ue_vehiculo_add();"><img src="images/group_add.png" alt="Buscar" title="Registrar Vehiulo" width="15" height="15" border="0"></a>';
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="cod_tramite" id="cod_tramite" class="validate[required]" style="width:180px" >';
            echo '<option value="">----</option>';
            echo '</select>';			
        }	
    }
    
    
    if(($acciones=="vehiculos")){
        $cedula_consultar=strtoupper($_GET['ced']);
        $url=$_GET["dir"];
        
        $cedula_rif_buscar = preg_replace("/\s+/", "", $cedula_consultar);
        $cedula_rif_buscar = str_replace("-", "", $cedula_rif_buscar);
        
        echo '<div id="vehiculos" >
                    <select name="cod_vehiculo" id="cod_vehiculo" class="validate[required]"  >
                        <option  selected="selected" value="">Seleccione el Vehículo</option>';

                            $consultax2="SELECT * from vehiculo_solicitante where cedula_rif='$cedula_rif_buscar'";
                            $ejec_consultax2=pg_query($consultax2);
                            while($vector2=pg_fetch_array($ejec_consultax2)){
                                    echo '<option value="'.$vector2[0].'">Placa:'.$vector2[4].' - S/C:'.$vector2[7].'</option>';
                            }


        echo '</select>
                 <a href="javascript: ue_vehiculo_add();"><img src="images/agregar.png" alt="Buscar" title="Registrar Vehiulo" width="20" height="20" border="0"></a>
            </div>';
    }
    
    if(($acciones=="marca_vehiculo")){
        $url=$_GET["dir"];
        
        echo '<select id="marca" name="marca" class="inputbox validate[required]" size="1">
                        <option value="">---</option>';

                            $consultax2="SELECT * FROM marca_vehiculo ";
                            $ejec_consultax2=pg_query($consultax2);
                            while($vector2=pg_fetch_array($ejec_consultax2)){
                                    echo '<option value="'.$vector2[0].'">'.$vector2[1].'</option>';
                            }


        echo '</select>';
    }
    
    // --- FIN SOLICITUDES EN LINEA
    
    
    // -- REGISTRO DE TICKET -- //
    
    if(($acciones=="tramites_unidades")){
        $unidad=$_GET["cod_unidad"];
        $url=$_GET["dir"];
        
        $cedula_consultar=strtoupper($_GET['ced']);
        $cedula_rif_buscar = preg_replace("/\s+/", "", $cedula_consultar);
        $cedula_rif_buscar = str_replace("-", "", $cedula_rif_buscar);
        
        if (($unidad!="")){
            echo '<select name="cod_tramite" id="cod_tramite" class="validate[required]" >';
            echo '<option value="">----</option>';
            $consultax1="SELECT * from tramites where cod_unidad='$unidad' and status_tramite=1 order by nombre_tramite";
            $ejec_consultax1=pg_query($consultax1);
            while($vector=pg_fetch_array($ejec_consultax1)){
                    echo '<option value="'.$vector[0].'">'.$vector[2].'</option>';
            }
            echo '</select>';
            pg_free_result($ejec_consultax1);	
        }else {
            echo '<select name="cod_tramite" id="cod_tramite" class="validate[required]" style="width:180px" >';
            echo '<option value="">----</option>';
            echo '</select>';			
        }	
    }
    
    // -- FIN REGISTRO DE TICKET -- //
    
   // -- INICIO CONSULTA DE VALIDACION-- //
    
    
    if($acciones=="montoletras"){
        $monto=$_GET["monto"];
        $url=$_GET["dir"];
        
        if ($monto!="0.00"){
            echo '<strong>TOTAL LETRAS:</strong> '.numtoletras($monto);
        }else{
            echo '<strong>TOTAL LETRAS:</strong> ';
        }
        
    }
    
    
    if(($acciones=="validarcodigo")){
        
            require("conexion_r/aut_config.inc.php");
            require ("conexion_r/connect.php");
            
            $codigo= intval($_GET["codigo"]);
            $url=$_GET["dir"];

         // Verificar si existe el Registro
            $query= "SELECT * FROM nacimientos, nacimientospresentados, librosnacimientos WHERE nacimientos.idactanatal=nacimientospresentados.idactanatal  AND librosnacimientos.idlibronatal=nacimientos.idlibronatal AND nacimientos.idactanatal='$codigo'";
            $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
            $total_result= mysql_num_rows($result);
//            $resultados = mysql_fetch_array($result);
            

            if ($total_result==0){
                $div_menssage='<div align="left"><h3 class="pending"><font size="2" color="black" style="text-decoration:blink;">Disculpe, El Numero de Cédula: <font color="blue">'.$codigo.'</font>; que intenta buscar no tiene canaima asignada, debes esperar el censo de asignación de la segunda avanzada del programa Canaimas Universitarias</font></h3></div>';		
            }

            if ($total_result!=0){
                echo '<table class="gen_table_form" cellspacing="1" cellpadding="2" width="700" align="center" border="1">
                    <tbody>
                        <tr>
                            <th class="section_name" colspan="8">DATOS DE LA CONSULTA RALIZADA</th>
                        </tr>
                        <tr>											
                            <td class="item_text" width="15%"  align="center">Nº Acta Natal</td>											
                            <td class="item_text" width="15%" align="center">Nº Acta</td>																					
                            <td class="item_text" width="15%" align="center">Fecha Acta</td>											
                            <td class="item_text" width="15%" align="center">Nº Folio</td>
                            <td class="item_text" width="30%" align="center">Nombres</td>
                                <td class="item_text" width="15%" align="center">Fecha Natal</td>
                            <td class="item_text" width="5%" align="center">Acción</td>											
                        </tr>';

                            $xxx=0;
                            while($resultados = mysql_fetch_array($result)) {	
                                $xxx=$xxx+1;			
                                if (($xxx %2)==0) $i='item_claro'; else $i='item_oscuro';
                            echo '<tr class='.$i.'>		
                                <td align="center">'.$resultados['idactanatal'].'</td>
                                <td align="center">'.$resultados['numeroacta'].'</td>
                                <td align="center">'.date_format(date_create($resultados['fechaacta']), 'd/m/Y').'</td>

                                <td align="center">'.$resultados['folio'].'</td>
                                <td>'.$resultados['primernombre'].' '.$resultados['segundonombre'].'</td>
                                <td align="center">'.date_format(date_create($resultados['fechanatal']), 'd/m/Y').'</td>

                                <td align="center">	
                                    <a title="Imprimir" target="_Blank" href="reportes/imprimir_partida_nacimiento.php?idactanatal='.$resultados['idactanatal'].'">
                                    <img  border="0" name="Image_Encab" src="images/printer28.png">
                                    </a>

                                </td>												
                            </tr>';
                            } //fin del while
                    echo '</tbody>
                </table>
                <br>
                <br>';
            }else{
                echo '<table border="0" width="550px" align="center">
                        <tbody>         
                            <tr>
                                <td id="msgload" align="center">    
                                    <div align="left"><h3 class="pending"><font size="2" color="black" style="text-decoration:blink;">Disculpe, El Numero de Solicitud: <font color="blue">'.$codigo.'</font>; que intenta buscar -NO- fue Encontrado!; Intente de Nuevo</font></h3></div>                    
                                </td>
                            </tr>
                        </tbody>
                    </table>';
            }
		
	}
        

   // -- INICIO CONSULTA DE CLIENTE EN EL MODULO DE FACTURACION-- //        
        if(($acciones=="consultarpersona")){
            $cedula_consultar=strtoupper($_GET['cedula']);
            $cedula_rif_buscar = preg_replace("/\s+/", "", $cedula_consultar);
            $cedula_rif_buscar = str_replace("-", "", $cedula_rif_buscar);
            
            $load=$_GET["load"];
            $url=$_GET["dir"];
            
            if (($cedula_consultar!="")&&($cedula_consultar!="null")){
                $consulta_query=pg_query("SELECT * FROM solicitantes,tipo_solicitantes WHERE solicitantes.cedula_rif='$cedula_rif_buscar' AND solicitantes.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante order by cedula_rif");
                $resultados=pg_fetch_array($consulta_query);
                pg_free_result($consulta_query);
                
                if ($load!=1){
                    if ($resultados[0]){

                        echo '<table class="adminform"  border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td width="15%">
                                            NOMBRE DEL CLIENTE:
                                        </td>

                                        <td width="85%">
                                            <input readonly="true" type="text" id="nombre_solicitante" name="nombre_solicitante" value="'.$resultados[nombre_solicitante].'" maxlength="50" size="50" />																	

                                            TIPO CLIENTE:

                                            <input readonly="true" type="text" id="tipo_solicitante" name="tipo_solicitante" value="'.$resultados[descripcion_tipo_solicitante].'"  maxlength="50" size="50" />																	
                                        </td>
                                    </tr> 

                                    <tr>
                                        <td>
                                            DIRECCION:
                                        </td>

                                        <td>
                                            <input readonly="true" type="text" id="direccion" name="direccion" value="'.$resultados[direccion_habitacion].'"  maxlength="140" size="140" />																	
                                        </td>
                                    </tr>
                                </tbody>
                            </table>';
                    }else{

                        echo '<table class="adminform"  border="0" width="100%" >
                                 <tbody>
                                    <tr id="msg">
                                        <td width="15%">
                                            NOMBRE DEL CLIENTE:
                                        </td>

                                        <td width="85%">
                                            <input readonly="true" type="text" id="nombre_solicitante" name="nombre_solicitante" maxlength="50" size="50" />																	

                                            TIPO CLIENTE:

                                            <input readonly="true" type="text" id="tipo_solicitante" name="tipo_solicitante"  maxlength="50" size="50" />																	
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            DIRECCION:
                                        </td>
                                        <td>
                                            <input readonly="true" type="text" id="direccion" name="direccion" maxlength="140" size="140" />																	
                                        </td>
                                    </tr>
                                </tbody>
                            </table>';
                    } // FIN IF RESULTADOS
                }else {
                    if (($resultados[0])){
                        echo '';
                    }else{
//                        <div align="left"><font size="2" color="#CC0000" style="text-decoration:blink;"><strong>No Existe!</strong></font></div>						
                        if (strlen($cedula_rif_buscar) >= 2){
                            echo '<table width="100%"  border="0" >
                                    <tbody>
                                        <tr>
                                            <td width="5%">
                                                <div align="left">
                                                    <a href="javascript: ue_cliente_add();"><img src="images/group_add.png" alt="Buscar" title="Registrar Cliente" width="15" height="15" border="0"></a>						
                                                </div>    
                                            </td>
                                            <td id="msgloadpers">
                                                <h3 class="error">El Registro no Existe</h3>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>';
                        }else{
                           echo ''; 
                        }
                    }
                }
            }else {
                if ($load!=1){
                    echo '<table class="adminform"  border="0" width="100%" >
                            <tbody>
                                <tr>
                                    <td width="15%">
                                        NOMBRE DEL CLIENTE:
                                    </td>
                                    <td width="85%">
                                        <input readonly="true" type="text" id="nombre_solicitante" name="nombre_solicitante" maxlength="50" size="50" />																	
                                    
                                        TIPO CLIENTE:
                                    
                                        <input readonly="true" type="text" id="tipo_solicitante" name="tipo_solicitante"   maxlength="50" size="50" />																	
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        DIRECCION:
                                    </td>
                                    <td>
                                        <input readonly="true" type="text" id="direccion" name="direccion" maxlength="90" size="90" />																	
                                    </td>
                                </tr>
                            </tbody>
                        </table>';
                }else{
                    echo '';
                }
            }	
	}
        
    if(($acciones=="consultarconcepto")){
        $codigo_consultar=$_GET["codigo"];
        $codfacturatmp=$_GET["n_facturatmp"];
        $url=$_GET["dir"];
        
        if (($codigo_consultar!="")&&($codigo_consultar!="null")){
            $consulta_query=pg_query("SELECT * FROM concepto_factura where codigo_concepto='$codigo_consultar'");
            $resultados=pg_fetch_array($consulta_query);
            pg_free_result($consulta_query);
            $total=($resultados[precio_venta]*1);
            if ($resultados[0]){
                $query="SELECT * FROM detalle_facturatmp WHERE n_factura='$codfacturatmp' and codigo_concepto='$resultados[codigo_concepto]'";				
                $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());	
                $resultados_facturatmp=pg_fetch_array($result);
                if($resultados_facturatmp[0]){
                    $cant_facturatmp=$resultados_facturatmp[cantidad];
                }else{
                    $cant_facturatmp=0;
                }
                
                echo '<input id="stock" name="stock" value="'.$resultados[stock].'" type="hidden">
                <input id="status_stock" name="stock_stock" value="'.$resultados[status_stock].'" type="hidden">
                <input id="cantidad_factura" name="cantidad_factura" value="'.$cant_facturatmp.'" type="hidden">
                
                <table>
                    <tr>
                        <td>
                            <center>CODIGO:</center>
                            <input type="text" placeholder="Código Concepto" id="codigo_concepto" name="codigo_concepto" maxlength="20" size="20" onblur="cargarContenedorConcepto()" value="'.$resultados[codigo_concepto].'"/>
                            <a href="javascript: ventanaconceptos();">
                                <img src="images/busqueda.png" alt="Buscar" title="Buscar Concepto" width="15" height="15" border="0">
                            </a>
                        </td>

                        <td>
                            <center>DESCRIPCION:</center>
                            <input type="text" id="descripcion_concepto" name="descripcion_concepto" value="'.$resultados[nombre_concepto].'" readonly="true"  size="98"/>   
                        </td> 

                        <td>
                            <center>CANTIDAD:</center>
                            <input type="text" id="cantidad" style="text-align:center" name="cantidad" size="10" value="1" onfocus="actualizar_importe()" onkeyup="actualizar_importe()"> 
                        </td>

                        <td>
                            <center>C / U:</center>
                            <input type="text" id="costo_unitario" style="text-align:right" name="costo_unitario" value="'.$resultados[costo_unitario].'" readonly="true" size="25">	
                        </td>

                        <td>
                            <center>IVA:</center>
                            <input type="text" id="iva_producto" placeholder="0,00" style="text-align:right" name="iva_producto" readonly="true" size="25" value="'.$resultados[iva_producto].'">
                        </td>

                        <td>
                            <center>TOTAL PRODUCTO:</center>
                            <input type="text" style="text-align:right" id="total" name="total" value="'.$total.'" size="25" readonly="true">	
                        </td>

                        <td align="center">
                            <input  class="button" type="button" onClick="validar()" value="Agregar" name="agregar" />
                        </td>
                    </tr>
                </table>' ;
                                
            }else{
                echo '<table id="msgload" class="adminform" width="100%" cellspacing=0 cellpadding=0  border="0" >
                    <tr >
                        <td>
                            <h3 class="error">Registro no Existe</h3>
                        </td>
                    </tr>
                </table>

                <input id="stock" name="stock" value="'.$resultados[stock].'" type="hidden">
                <input id="status_stock" name="stock_stock" value="'.$resultados[status_stock].'" type="hidden">
                <input id="cantidad_factura" name="cantidad_factura" value="'.$cant_facturatmp.'" type="hidden">
                
                <table>
                    <tr>
                        <td>
                            <center>CODIGO:</center>
                            <input type="text" placeholder="Código Concepto" id="codigo_concepto" name="codigo_concepto" maxlength="20" size="20" onblur="cargarContenedorConcepto()" value="'.$resultados[codigo_concepto].'"/>
                            <a href="javascript: ventanaconceptos();">
                                <img src="images/busqueda.png" alt="Buscar" title="Buscar Concepto" width="15" height="15" border="0">
                            </a>
                        </td>

                        <td>
                            <center>DESCRIPCION:</center>
                            <input type="text" id="descripcion_concepto" name="descripcion_concepto" value="'.$resultados[nombre_concepto].'" readonly="true"  size="98"/>   
                        </td> 

                        <td>
                            <center>CANTIDAD:</center>
                            <input type="text" id="cantidad" style="text-align:center" name="cantidad" size="10" value="1" onfocus="actualizar_importe()" onkeyup="actualizar_importe()"> 
                        </td>

                        <td>
                            <center>C / U:</center>
                            <input type="text" id="costo_unitario" style="text-align:center" name="costo_unitario" value="'.$resultados[costo_unitario].'" readonly="true" size="25">  
                        </td>

                        <td>
                            <center>IVA:</center>
                            <input type="text" id="iva_producto" placeholder="0,00" style="text-align:right" name="iva_producto" readonly="true" size="25" value="'.$resultados[iva_producto].'">
                        </td>

                        <td>
                            <center>TOTAL PRODUCTO:</center>
                            <input type="text" style="text-align:right" id="total" name="total" value="'.$total.'" size="25" readonly="true">   
                        </td>

                        <td align="center">
                            <input  class="button" type="button" onClick="validar()" value="Agregar" name="agregar" />
                        </td>
                    </tr>
                </table>' ;
            }
        }else {
            echo '<input id="stock" name="stock" type="hidden">
                <input id="status_stock" name="stock_stock" type="hidden">
                <input id="cantidad_factura" name="cantidad_factura" type="hidden">
                
                <table>
                    <tr>
                        <td>
                            <center>CODIGO:</center>
                            <input type="text" placeholder="Código Concepto" id="codigo_concepto" name="codigo_concepto" maxlength="20" size="20" onblur="cargarContenedorConcepto()" value="'.$resultados[codigo_concepto].'"/>
                            <a href="javascript: ventanaconceptos();">
                                <img src="images/busqueda.png" alt="Buscar" title="Buscar Concepto" width="15" height="15" border="0">
                            </a>
                        </td>

                        <td>
                            <center>DESCRIPCION:</center>
                            <input type="text" id="descripcion_concepto" name="descripcion_concepto"  readonly="true" value="'.$resultados[nombre_concepto].'" size="98"/>   
                        </td> 

                        <td>
                            <center>CANTIDAD:</center>
                            <input type="text" id="cantidad" style="text-align:center" name="cantidad" size="10" value="1" onfocus="actualizar_importe()" onkeyup="actualizar_importe()"> 
                        </td>

                        <td>
                            <center>C / U:</center>
                            <input type="text" id="costo_unitario" style="text-align:right" name="costo_unitario" readonly="true" value="'.$resultados[costo_unitario].'" size="25">  
                        </td>

                        <td>
                            <center>IVA:</center>
                            <input type="text" id="iva_producto" placeholder="0,00" style="text-align:right" name="iva_producto" readonly="true" size="25" value="'.$resultados[iva_producto].'">
                        </td>

                        <td>
                           <center>TOTAL PRODUCTO:</center>
                            <input type="text" style="text-align:right" id="total" name="total" size="25" readonly="true" value="'.$total.'">   
                        </td>

                        <td align="center">
                            <input  class="button" type="button" onClick="validar()" value="Agregar" name="agregar" />
                        </td>
                    </tr>
                </table>' ;
        }	
    } 

        if(($acciones=="consultarproducto")){
            $codigo_consultar=$_GET["codigo"];
            $codfacturatmp=$_GET["n_facturatmp"];
            $url=$_GET["dir"];
            
            if (($codigo_consultar!="")&&($codigo_consultar!="null")){
                $consulta_query=pg_query("SELECT * FROM concepto_factura where codigo_concepto='$codigo_consultar'");
                $resultados=pg_fetch_array($consulta_query);
                pg_free_result($consulta_query);
                $total=($resultados[precio_venta]*1);
                if ($resultados[0]){
                    $query="SELECT * FROM detalle_facturatmp WHERE n_factura='$codfacturatmp' and codigo_concepto='$resultados[codigo_concepto]'";				
                    $result=pg_query($query) or die('La consulta fall&oacute;:' . pg_last_error());	
                    $resultados_facturatmp=pg_fetch_array($result);
                    if($resultados_facturatmp[0]){
                        $cant_facturatmp=$resultados_facturatmp[cantidad];
                    }else{
                        $cant_facturatmp=0;
                    }
                    
                    echo '<input id="stock" name="stock" value="'.$resultados[stock].'" type="hidden">
                          <input id="status_stock" name="stock_stock" value="'.$resultados[status_stock].'" type="hidden">
                          <input id="cantidad_factura" name="cantidad_factura" value="'.$cant_facturatmp.'" type="hidden">
                        CONCEPTO: <font color="Red">(*)</font>
                       <input type="text" id="descripcion_concepto" name="descripcion_concepto" value="'.$resultados[nombre_concepto].'" readonly="true"  size="50"/>       		
                       &nbsp;&nbsp;&nbsp;
                       PRECIO:
                       <input type="text" id="costo_unitario" style="text-align:right" name="costo_unitario" value="'.$resultados[precio_venta].'" readonly="true" size="12">	
                       &nbsp;&nbsp;&nbsp;
                       CANTIDAD:
                       <input type="text" id="cantidad" style="text-align:right" name="cantidad" size="6" value="1" onfocus="actualizar_importe()" onkeyup="actualizar_importe()">	
                       &nbsp;&nbsp;&nbsp;
                       MONTO:
                       <input type="text" style="text-align:right" id="total" name="total" value="'.$total.'" size="12" readonly="true">	
                       &nbsp;&nbsp;&nbsp;' ;
                                    
                }else{
                    
                    echo '<table id="msgload" class="adminform" width="100%" cellspacing=0 cellpadding=0  border="0" >
                            <tr >
                                <td>
                                    <h3 class="error">Registro no Existe</h3>
                                </td>
                            </tr>
                          </table>
                          <input id="stock" name="stock" value="" type="hidden">
                          <input id="status_stock" name="stock_stock" value="" type="hidden">
                          <input id="cantidad_factura" name="cantidad_factura" value="" type="hidden">
                            <input type="text" id="descripcion_concepto" name="descripcion_concepto" readonly="true"  size="50"/>       		
                            
                            PRECIO:
                            <input type="text" id="costo_unitario" style="text-align:right" name="costo_unitario" readonly="true" size="8">	
                            &nbsp;&nbsp;&nbsp;
                            CANTIDAD:
                            <input type="text" id="cantidad" style="text-align:right" name="cantidad" size="6" value="1" onfocus="actualizar_importe()" onkeyup="actualizar_importe()">	
                            
                            MONTO:
                            <input type="text" style="text-align:right" id="total" name="total" size="10" readonly="true">';
                }
            }else {
                echo '<input id="stock" name="stock" value="" type="hidden">
                      <input id="status_stock" name="stock_stock" value="" type="hidden">
                      <input id="cantidad_factura" name="cantidad_factura" value="" type="hidden">
                      <input type="text" id="descripcion_concepto" name="descripcion_concepto" readonly="true"  size="50"/>       		
                      &nbsp;&nbsp;&nbsp;
                      PRECIO:
                      <input type="text" id="costo_unitario" style="text-align:right" name="costo_unitario" readonly="true" size="8">	
                      &nbsp;&nbsp;&nbsp;
                      CANTIDAD:
                      <input type="text" id="cantidad" style="text-align:right" name="cantidad" size="6" value="1" onfocus="actualizar_importe()" onkeyup="actualizar_importe()">	
                      &nbsp;&nbsp;&nbsp;
                      MONTO:
                      <input type="text" style="text-align:right" id="total" name="total" size="10" readonly="true">	
                      &nbsp;&nbsp;&nbsp;';
            }	
	} 
        
        if(($acciones=="bancos")){
		$cod_banco=$_GET["cod_banco"];
		$url=$_GET["dir"];
		if (($cod_banco!="")){
			echo '<select name="cod_cuenta_banco" class="validate[required]" class="inputbox">';
			echo '<option selected="selected" value="">Seleccione...</option>';
			$consultax1="SELECT * from cuenta where codigo_banco='$cod_banco'";
			$ejec_consultax1=pg_query($consultax1);
			while($vector=pg_fetch_array($ejec_consultax1)){
                    	  	echo '<option value="'.$vector[0].'">'.$vector[0].' - '.$vector[4].' </option>';
			}
			echo '</select>';
			pg_free_result($ejec_consultax1);	
		}else {
			echo '<select name="cod_cuenta_banco" class="inputbox" class="validate[required]">';
			echo '<option selected="selected" value="">Seleccione...</option>';
			echo '</select>';
			}	
	}

//-------------------------------------------------------------------------------------------------//			
//-------------------------------------------------------------------------------------------------//
//	mysql_close($db_connect);
	pg_close($db_conexion);
?>
