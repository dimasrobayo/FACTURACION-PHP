<?php
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$type;

    //Conexion a la base de datos
    include("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    $year=date("Y");
    $tipo_estado_tramite=1;
    $unidad_inicial=1;
    if (isset($_POST['year'])){
        $year=$_POST['year'];	
        $tipo_estado_tramite=$_POST['tipo_estado_tramite'];	
        $unidad_inicial=$_POST['unidad_inicial'];
        $cod_unidad=$_POST['cod_unidad'];
    }
    
    if($_SESSION['nivel']==0){
        $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades". 
            " WHERE date_part('year',ticket.fecha_registro)= '$year' AND  ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite".
            " AND estados_tramites.tipo_estado_tramite::text like  '%$tipo_estado_tramite%' AND ticket.cedula_rif=solicitantes.cedula_rif ".
            " AND ticket.cod_tramite=tramites.cod_tramite AND tramites.cod_unidad::text like  '%$cod_unidad%' AND tramites.cod_unidad=unidades.cod_unidad";
        $result = pg_query($query)or die(pg_last_error());
    }elseif($_SESSION['nivel']==2){
        $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades". 
            " WHERE date_part('year',ticket.fecha_registro)= '$year' AND  ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite".
            " AND estados_tramites.tipo_estado_tramite::text like  '%$tipo_estado_tramite%' AND ticket.cedula_rif=solicitantes.cedula_rif ".
            " AND ticket.cod_tramite=tramites.cod_tramite AND tramites.cod_unidad::text like  '%$cod_unidad%' AND tramites.cod_unidad=unidades.cod_unidad";
        $result = pg_query($query)or die(pg_last_error());
    }else{
        if ($unidad_inicial==1){
            $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades". 
                " WHERE date_part('year',ticket.fecha_registro)= '$year' AND  ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite".
                " AND estados_tramites.tipo_estado_tramite::text like  '%$tipo_estado_tramite%' ".
                " AND ticket.cod_tramite=tramites.cod_tramite AND tramites.cod_unidad=unidades.cod_unidad".
                " AND unidades.cod_unidad='$_SESSION[cod_unidad]' AND ticket.cedula_rif=solicitantes.cedula_rif";
            $result = pg_query($query)or die(pg_last_error());
        }else{
            $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket,  detalles_ticket.fecha_registro AS fecha_registro_detalles_ticket FROM ticket,tramites,unidades,solicitantes,detalles_ticket,estados_tramites WHERE".
                    " ticket.cod_tramite=tramites.cod_tramite AND  tramites.cod_unidad=unidades.cod_unidad AND ticket.cedula_rif=solicitantes.cedula_rif AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite  AND estados_tramites.tipo_estado_tramite::text like  '%$tipo_estado_tramite%'".
                    " AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket  AND tramites.cod_unidad<>detalles_ticket.cod_unidad AND detalles_ticket.cod_unidad='$_SESSION[cod_unidad]' AND date_part('year',ticket.fecha_registro)= '$year'";
            $result = pg_query($query)or die(pg_last_error());
        }
    }
    
    
    
?>
<!-- funciones javascript  -->
<script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function(){
        jQuery('#tabla_desc').dataTable({
            "aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "Todos"]],
            "aaSorting": [[ 0, "desc" ]],
            "sPaginationType": "full_numbers"          		
        });
    });
</script>

<div align="center" class="centermain">
    <div>  
        <div align="center">
            <font color="red" style="text-decoration:blink;">
                <?php $error_accion_ms[$error_cod]?>
            </font>
        </div>

        <table class="ticketsgestion">
            <tr>
                <th>
                    TICKETS:                    
                </th>
            </tr>
        </table>
        
        <form  name="ticket_load" method="POST" action="" enctype="multipart/form-data">
            <table class="adminlist"  width="100%" border="0" >		
                <tr>	        
                    <td width="20%" >	        		
                        <div>
                            <strong>AÑO:</strong>    
                            <select name="year" id="year" onchange="javascript: submit_ticket_load();" >
                                <?php 
                                $consulta_sql=pg_query("SELECT date_part('year',fecha_registro) AS year FROM ticket  group by date_part('year',fecha_registro) order by date_part('year',fecha_registro) DESC");								
                                while ($array_consulta=pg_fetch_array($consulta_sql)){
                                    if ($year!=""){
                                        if ($array_consulta[0]==$year){
                                            echo '<option value="'.$array_consulta[0].'" selected="selected">'.$array_consulta[0].'</option>';
                                        }else {
                                            echo '<option value="'.$array_consulta[0].'">'.$array_consulta[0].'</option>';
                                        }
                                    }else {
                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[0].'</option>';
                                    }
                                }
                                pg_free_result($consulta_sql);									
                                ?>
                            </select> 
                        </div> 					
                    </td>
                    
                    <?php if($_SESSION['nivel']==0){ ?>
                    <td width="50%" >	        		
                        <div>
                            <strong>Unidad/Dependencia:</strong>    
                            <select name="cod_unidad" id="cod_unidad"   onchange="javascript: submit_ticket_load();">
                                <option selected="selected" value="">TODAS LAS UNIDADES/DEPENDENCIAS</option>
                                <?php 
                                    $consulta_sql=pg_query("SELECT * FROM unidades where status_unidad=1 order by nombre_unidad");
                                    while ($array_consulta=pg_fetch_array($consulta_sql)){
                                        if ($cod_unidad==$array_consulta[0]){ 
                                            echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';
                                        }else{
                                            echo '<option value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';
                                        }
                                    }																																						
                                    pg_free_result($consulta_sql);								
                                ?>				
                            </select> 
                        </div> 					
                    </td>
                    <?php }elseif($_SESSION['nivel']==2){ ?>
                    <td width="50%" >	        		
                        <div>
                            <strong>Unidad/Dependencia:</strong>    
                            <select name="cod_unidad" id="cod_unidad"   onchange="javascript: submit_ticket_load();">
                                <option selected="selected" value="">TODAS LAS UNIDADES/DEPENDENCIAS</option>
                                <?php 
                                    $consulta_sql=pg_query("SELECT * FROM unidades where status_unidad=1 order by nombre_unidad");
                                    while ($array_consulta=pg_fetch_array($consulta_sql)){
                                        if ($cod_unidad==$array_consulta[0]){ 
                                            echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';
                                        }else{
                                            echo '<option value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';
                                        }
                                    }																																						
                                    pg_free_result($consulta_sql);								
                                ?>				
                            </select> 
                        </div> 					
                    </td>
                    <?php }else{ ?>
                    <td width="30%" >	        		
                        <div>
                            <strong>Asignación Unidad:</strong>    
                            <select name="unidad_inicial" id="unidad_inicial" onchange="javascript: submit_ticket_load();" >
                                <?php 
                                    if ($unidad_inicial!=""){
                                        if ($unidad_inicial==1){
                                            echo '<option value="1" selected="selected">INICIAL</option>';
                                            echo '<option value="2" >ESCALADA</option>';
                                        }else {
                                            echo '<option value="1" >INICIAL</option>';
                                            echo '<option value="2" selected="selected">ESCALADA</option>';
                                        }
                                    }else {
                                        echo '<option value="1" selected="selected">INICIAL</option>';
                                        echo '<option value="2" >ESCALADA</option>';
                                    }
                                ?>
                            </select> 
                        </div> 					
                    </td>
                    <?php }?>
                    <td align="right">	        		
                        <div>
                            <strong>ESTADO DEL TICKET:</strong>
                            <select name="tipo_estado_tramite" id="tipo_estado_tramite" onchange="javascript: submit_ticket_load();" >
                                <?php
                                    if($tipo_estado_tramite=="1") {
                                        echo '<option value="1" selected="selected">Pendiente</option>';
                                        echo '<option value="2" >Completado</option>';
                                        echo '<option value="3" >Cancelado</option>';
                                    }elseif($tipo_estado_tramite=="2") {
                                        echo '<option value="1" >Pendiente</option>';
                                        echo '<option value="2" selected="selected">Completado</option>';
                                        echo '<option value="3" >Cancelado</option>';
                                    }else{
                                        echo '<option value="1" >Pendiente</option>';
                                        echo '<option value="2" >Completado</option>';
                                        echo '<option value="3" selected="selected">Cancelado</option>';
                                    }																				
                                ?>
                            </select> 
                        </div> 					
                    </td>
                </tr>
            </table>
        </form>

        <br>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
        <table class="display" id="tabla_desc">
        <thead>
            <tr bgcolor="#55baf3">
                <th align="center" width="10%">
                    Nº TICKET
                </th>
                <th width="14%" align="center">
                    FECHA REGISTRO
                </th>

                <th width="35%" align="center">
                    SOLICITANTE
                </th>
                
                <th width="25%" align="center">
                    UNIDAD INICIAL ASIGNADA
                </th>
                <th width="20%" align="center">
                    TIPO TRAMITE
                </th>
                <th width="11%" align="center">
                    ESTADO
                </th>
		
                
            </tr>
        </thead>

<?php
$xxx=0;
while($resultados_ticket = pg_fetch_array($result)) {
	$xxx=$xxx+1;
?>

            <tr class="row0">
                <td align="center">
                    <!--<a title="Gestionar Ticket" href="?view=gestion_tickets&cod_ticket=$resultados_ticket[cod_ticket]"><font color="black">'.str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT).'</font></a>-->
                    <?php
                        if($resultados_ticket[prioridad_ticket]==1){
                            echo '<a title="Gestionar Ticket" href="?view=gestion_tickets&cod_ticket='.$resultados_ticket[cod_ticket].'"><font color="black">'.str_pad($resultados_ticket[cod_ticket],10,"0",STR_PAD_LEFT).'</font></a>';
                        }elseif($resultados_ticket[prioridad_ticket]==2){
                            echo '<a title="Gestionar Ticket" href="?view=gestion_tickets&cod_ticket='.$resultados_ticket[cod_ticket].'"><font color="ffba00">'.str_pad($resultados_ticket[cod_ticket],10,"0",STR_PAD_LEFT).'</font></a>';
                        }else{
                            echo '<a title="Gestionar Ticket" href="?view=gestion_tickets&cod_ticket='.$resultados_ticket[cod_ticket].'"><font color="red">'.str_pad($resultados_ticket[cod_ticket],10,"0",STR_PAD_LEFT).'</font></a>';
                        }
                        
                    ?>
                </td>													
                <td align="center"><?php echo date_format(date_create($resultados_ticket['fecha_registro_ticket']), 'd/m/Y g:i A.') ;?> </td>
                <td><?php echo $resultados_ticket['cedula_rif'].' - '.$resultados_ticket['nombre_solicitante']?> </td>
                <td><?php echo $resultados_ticket['nombre_unidad']?> </td>
                <td><?php echo $resultados_ticket['nombre_tramite']?> </td>
                <td><?php echo $resultados_ticket['siglas_estado_tramite']; if($resultados_ticket['online']==1) echo '/<font color="green">OnLine</font>'?></td>
                
            </tr>
<?php
}
?>

            <tfoot>
                <tr align="center">
                    <th colspan="6" align="center">
                        <div id="cpanel">
                            <div style="float:right;">
                                <div class="icon">
                                    <a href="index2.php?view=home">
                                        <img src="images/cpanel.png" alt="salir" align="middle"  border="0" />
                                        <span>Salir</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php
pg_free_result($datos_consulta);
pg_close();
?>
