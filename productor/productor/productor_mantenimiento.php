<?php //ENRUTTADOR

    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?> 

<?php 
    $query="select * from ticket where cedula_rif=''";
    $result_ticket = pg_query($query)or die(pg_last_error());
    $total=0;
    
    while($resultados = pg_fetch_array($result_ticket)) {
        if ($resultados[0]){

            $query="SELECT * FROM solicitantes WHERE id='$resultados[id_cedula_rif]'";
            $result = pg_query($query) or die(pg_last_error());
            $result_solicitantes=pg_fetch_array($result);
            pg_free_result($result);

            $query="update ticket set cedula_rif='$result_solicitantes[cedula_rif]' where cod_ticket='$resultados[cod_ticket]'";
            $result = pg_query($query) or die(pg_last_error());
            
            $total++;
        }
    }
    
?> 

<?php if($div_menssage) { ?>					
    <script type="text/javascript">
            function ver_msg(){
                Effect.Fade('msg');
            }  
            setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
    </script>
 <?php } ?>

<div align="center" class="centermain">
    <div class="main">  
        <table class="adminclientes" width="100%">
            <tr>
                <th>
                    SOLICITANTE MANTENIMIENTO DE TICKETS
                </th>
            </tr>
        </table>
        
        <table class="adminform" border="0" width="100%">
            <tr>
                <th colspan="2" align="center">
                    <img src="images/delete.png" width="16" height="16" alt="Eliminar Registro">
                    EJCUTANDO MANTENIMIENTO DE TICKETS TMP
                </th>
            </tr>
            
            <tr>
                <td colspan="2" align="center">
                    <div align="center"> 
                        <h3 class="info">	
                            <font size="2">
                                <?php
                                    echo 'Se ejecutaron '.$total.' Modificaciones de Mantenimiento en Tickets';
                                ?>
                                <br />
                                <script type="text/javascript">
                                    function redireccionar(){
                                        window.location="?view=home";
                                    }  
                                    setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                </script> 						
                                [<a href="?view=home" name="Continuar"> Continuar </a>]
                            </font>							
                        </h3>
                    </div> 
                </td>
            </tr>
        </table>
        <br>
    </div>
</div>
