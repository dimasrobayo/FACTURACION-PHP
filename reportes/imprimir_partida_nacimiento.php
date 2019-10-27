<?php
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='../index.php'</script>";
        exit;
    }

    require ("../conexion_r/aut_config.inc.php"); //consultar datos de variable
    require ("../conexion_r/connect.php");                 //conexion a la DB
    include("fpdf17/funciones_partida.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    include ('fpdf17/php-barcode.php');
    
     //RECIBIENDO VALORES
    if (isset($_GET['idactanatal'])){
        $idactanatal=$_GET['idactanatal'];
        $user=$_GET['user'];
    }


    //QUERY
    ######    NACIMIENTOS    #######
    $query= "SELECT * FROM nacimientos, nacimientospresentados, librosnacimientos WHERE nacimientos.idactanatal=nacimientospresentados.idactanatal  AND librosnacimientos.idlibronatal=nacimientos.idlibronatal AND nacimientos.idactanatal='$idactanatal'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $resultados_nacimientos=mysql_fetch_array($result);
    mysql_free_result($result);

    ######    NACIMIENTOSFORANEOS   #######
    $query= "SELECT * FROM nacimientosforaneos, paises WHERE nacimientosforaneos.idactanatal='$resultados_nacimientos[idactanatal]' AND nacimientosforaneos.idpais=paises.idpais ";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $resultados_nacimientosforaneos=mysql_fetch_array($result);
    mysql_free_result($result);
    
     ######    REGISTRADOR   #######
    $query= "SELECT * FROM registradores WHERE idregistrador='$resultados_nacimientos[idregistrador]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $resultados_registradores=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM registradorescargos WHERE idcargo='$resultados_nacimientos[idcargoregistrador]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $resultados_registradorescargos=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM registradoresprofesiones WHERE idprofesion='$resultados_nacimientos[idprofesionregistrador]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $resultados_registradoresprofesiones=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM registradoresresoluciones WHERE idregistradorresolucion='$resultados_nacimientos[idregistradorresolucion]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $resultados_registradoresresoluciones=mysql_fetch_array($result);
    mysql_free_result($result);

    //ESTRUCTURA DE LA PAGE
    $pdf=new PDF('P','mm','Legal');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
        
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'40','','jpg','http://www.alcaldiaguanare.gob.ve');
    
    $pdf->Image('./logo/logo_agua.png',30,60,'150','','png','http://www.alcaldiaguanare.gob.ve');

    //$pdf->SetFillColor(200,220,255); //AZUL
    //$pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetFillColor(247,247,247);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFont('Arial','',14);
    $pdf->SetLeftMargin(55);


    $pdf->SetFontSize(7);
    $pdf->MultiCell(90,3,utf8_decode('REPUBLICA BOLIVARIANA DE VENEZUELA'),0,'C',0);//
    $pdf->Ln(0);
    $pdf->SetFontSize(9);
    $pdf->MultiCell(90,3,utf8_decode('ALCALDIA DEL MUNICIPIO GUANARE'),0,'C',0);//
    $pdf->Ln(0);
    $pdf->SetFontSize(8);
    $pdf->MultiCell(90,3,utf8_decode('GUANARE ESTADO PORTUGUESA'),0,'C',0);//
    $pdf->Ln(0);
    $pdf->SetFontSize(7);
    $pdf->MultiCell(90,3,utf8_decode('OFICINA DE REGISTRO MUNICIPAL'),0,'C',0);//
    $pdf->Ln(0);
    $pdf->SetFont('Arial','BU',14);
    $pdf->SetLeftMargin(65);
    $pdf->MultiCell(70,5,utf8_decode('PARTIDA DE NACIMIENTO'),0,'C',0);
    $pdf->Ln(0);
    
    $pdf->RoundedRect(10, 40, 135, 4, 2,'12','DF');
    $pdf->RoundedRect(147, 40, 35, 4, 2,'12','DF');
    $pdf->RoundedRect(184, 40, 22, 4, 2,'12','DF');

    $pdf->SetFillColor(255,255,255); // SIN COLOR
    $pdf->RoundedRect(10, 44, 135, 7, 2,'34','DF');
    $pdf->RoundedRect(147, 44, 35, 7, 2,'34','DF');
    $pdf->RoundedRect(184, 44, 22, 7, 2,'34','DF');


    $pdf->RoundedRect(10, 51, 196, 14, 2,'1234','DF');

    $pdf->SetFillColor(247,247,247);//GRIS


    $pdf->SetFont('Arial','U',12);
    $pdf->SetLeftMargin(145);
    $pdf->SetY(15);
    
    $pdf->Cell(60,4,utf8_decode('EXPEDICIÓN GRATUITA'),0,1,'C',0);
   
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(60,4,utf8_decode('PROHIBIDA LA VENTA DE ESTE DOCUMENTO'),0,0,'C',0);//
    
    $pdf->SetY(40);
    $pdf->SetX(10);
    $pdf->SetLeftMargin(10);

    
    
    //NACIMIENTOS
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(135,4,utf8_decode('Titulo I. a-DATOS REGISTRALES DEL ACTA'),'B',0,'C',0);
    $pdf->Cell(2);
    $pdf->Cell(35,4,utf8_decode('FECHA EXPEDICIÓN'),'B',0,'C',0);
    $pdf->Cell(2);
    $pdf->Cell(0,4,utf8_decode('ESTE FOLIO'),B,1,'C',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(30,3,utf8_decode('1.- Fecha de presentación'),'L',0,'C',0);
    $pdf->Cell(15,3,utf8_decode('2.- Libro No.'),'L',0,'C',0);
    $pdf->Cell(15,3,utf8_decode('3.- Acta No.'),'L',0,'C',0);
    $pdf->Cell(12,3,utf8_decode('4.- Folio'),'L',0,'C',0);
    $pdf->Cell(12,3,utf8_decode('No.'),0,0,'C',0);
    $pdf->Cell(12,3,utf8_decode('Vto.'),0,0,'C',0);
    $pdf->Cell(12,3,utf8_decode('5.- Folio'),'L',0,'C',0);
    $pdf->Cell(12,3,utf8_decode('No. '),0,0,'C',0);
    $pdf->Cell(15,3,utf8_decode('Vto. '),'R',0,'C',0);
    $pdf->Cell(2);
    $pdf->Cell(35,3,utf8_decode(''),'LR',0,'C',0);
    $pdf->Cell(2);
    $pdf->Cell(0,3,utf8_decode(' Nro.'),'LR',1,'L',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(30,4,utf8_decode(utf8_decode(date_format(date_create($resultados_nacimientos['fechaacta']), 'd/m/Y'))),0,0,'C',0);
    $pdf->Cell(15,4,utf8_decode($resultados_nacimientos['tomo']),'LB',0,'C',0);
    $pdf->Cell(15,4,utf8_decode($resultados_nacimientos['numeroacta']),'LB',0,'C',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(12,4,utf8_decode('Inicial'),'LB',0,'C',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(12,4,utf8_decode($resultados_nacimientos['folio']),'B',0,'C',0);
    $pdf->Cell(12,4,utf8_decode("N/A"),'BR',0,'C',0);
//    $pdf->Cell(12,4,utf8_decode($resultados_nacimientos['vuelto']),'BR',0,'C',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(12,4,utf8_decode('Final'),'B',0,'C',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(12,4,utf8_decode($resultados_nacimientos['foliofinal']),'B',0,'C',0);
    $pdf->Cell(15,4,utf8_decode("N/A"),0,0,'C',0);
//    $pdf->Cell(15,4,utf8_decode($resultados_nacimientos['vueltofinal']),0,0,'C',0);
    $pdf->Cell(2);
    $pdf->Cell(35,4,date('d/m/Y'),0,0,'C',0);
    $pdf->Cell(2);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientos['folio']),0,1,'C',0);

    //REGISTRADOR
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(25,3,'6.-',0,0,'L',0);
    $pdf->Cell(70,3,'Nombres y Apellidos','LT',0,'L',0);
    $pdf->Cell(52,3,'Cargo','T',0,'L',0);
    $pdf->Cell(0,3,utf8_decode('Caracter con que Actúa'),0,1,'L',0);
    
    $pdf->Cell(25,4,' Registrador(a)','LR',0,'L',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(70,4,utf8_decode($resultados_registradores['nombreregistrador']),'LB',0,'L',0);
    $pdf->Cell(52,4,utf8_decode($resultados_registradorescargos['cargo']),'B',0,'L',0);
    
    switch ($resultados_nacimientos['cualidadregistrador']) {
        case '1':
            $cualidadregistrador='Titular';
            break;
        case '2':
            $cualidadregistrador='Interino';
            break;
        
        default:
            $cualidadregistrador='Encargado';
            break;
    }
    
    $pdf->Cell(0,4,utf8_decode($cualidadregistrador),'RB',1,'L',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(25,3,' ','LR',0,'L',0);
    $pdf->Cell(25,3,utf8_decode('Resolucion Nº.'),'L',0,'L',0);
    $pdf->Cell(25,3,'De Fecha.','R',0,'L',0);
    $pdf->Cell(30,3,utf8_decode('Gaceta Municipal Nº.'),'L',0,'L',0);
    $pdf->Cell(25,3,'De Fecha','R',0,'L',0);
    $pdf->Cell(0,3,' ','LR',1,'L',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(25,4,'',0,0,'L',0);
    $resolucion = ($resultados_registradoresresoluciones['resolucion']!='') ? $resultados_registradoresresoluciones['resolucion'] : 'N/A' ;
    $fecharesolucion = ($resultados_registradoresresoluciones['fecharesolucion']!='') ?  date_format(date_create($resultados_registradoresresoluciones['fecharesolucion']), 'd/m/Y') : 'N/A' ;
    $gaceta = ($resultados_registradoresresoluciones['gaceta']!='') ? $resultados_registradoresresoluciones['gaceta'] : 'N/A' ;
    $fechagaceta = ($resultados_registradoresresoluciones['fechagaceta']!='') ? date_format(date_create($resultados_registradoresresoluciones['fechagaceta']), 'd/m/Y')  : 'N/A' ;
    $pdf->Cell(25,4,$resolucion,'L',0,'L',0);
    $pdf->Cell(25,4,$fecharesolucion,'R',0,'L',0);
    $pdf->Cell(30,4,$gaceta,'L',0,'L',0);
    $pdf->Cell(25,4,$fechagaceta,'R',0,'L',0);
    $pdf->Cell(0,4,'',0,1,'L',0);
    
    //REPRESENTADO
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,4,'Titulo II - DEL PRESENTADO',1,1,'C',1);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(20,3,'1.- Nombres','L',0,'L',0);
    $pdf->Cell(50,3,'Primero','',0,'L',0);
    $pdf->Cell(50,3,'Segundo','',0,'L',0);
    $pdf->Cell(0,3,'Apellidos','R',1,'L',0);
    $pdf->SetFont('Arial','BI',10);
    $pdf->Cell(20,4,'','L',0,'L',0);
    $pdf->Cell(50,4,utf8_decode($resultados_nacimientos['primernombre']),'',0,'L',0);
    $pdf->Cell(50,4,utf8_decode($resultados_nacimientos['segundonombre']),'',0,'L',0);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientos['primerapellido'].' '.$resultados_nacimientos['segundoapellido']),'R',1,'L',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(28,3,'2.- Fecha Nacimiento','LTR',0,'L',0);
    $pdf->Cell(28,3,'3.- Sexo','LTR',0,'L',0);
    $pdf->Cell(28,3,'4.- Hora Nacimiento','LTR',0,'L',0);
    $pdf->Cell(13,3,'5.-','T',0,'L',0);
    $pdf->Cell(25,3,'Tipo de Parto','T',0,'L',0);
    $pdf->Cell(28,3,'Orden de Nacimiento','T',0,'L',0);
    $pdf->Cell(0,3,'6.- Pais de Nacimiento','LTR',1,'L',0);
    $pdf->SetFont('Arial','BI',10);
    $pdf->Cell(28,4,utf8_decode(date_format(date_create($resultados_nacimientos['fechanatal']), 'd/m/Y')),'LRB',0,'C',0);
    $sexo = ($resultados_nacimientos['sexo']==2) ? 'Masculino' : 'Femenino' ;
    $pdf->Cell(28,4,$sexo,'LRB',0,'C',0);
//    $pdf->Cell(28,4,utf8_decode(date_format(date_create($resultados_nacimientos['horanatal']), 'g:s A')),'LRB',0,'C',0);
    $pdf->Cell(28,4,utf8_decode("N/A"),'LRB',0,'C',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(13,4,'Pluralidad','B',0,'C',0);
    $pdf->SetFont('Arial','BI',10);
    switch ($resultados_nacimientos['tipoparto']) { // 1=Simple,2=Mellizos,3=Trillizos,4=Cuatrillizos,5=Quintillizo
        case '1':
            $tipoparto='Simple';
            break;
        case '2':
            $tipoparto='Mellizos';
            break;
        case '3':
            $tipoparto='Trillizos';
            break;
        case '4':
            $tipoparto='Cuatrillizos';
            break;
        case '5':
            $tipoparto='Quintillizo';
            break;
    }
    $pdf->Cell(25,4,$tipoparto,'B',0,'L',0);
    switch ($resultados_nacimientos['ordennatal']) {
        case '1':
            # code...
            if ($resultados_nacimientos['sexo']==2) { 
            $ordernatal='Primero';
            }else{$ordernatal='Primera';}
            break;
        case '2':
            # code...
            $ordernatal='Segundo';
            break;
        case '3':
            # code...
            $ordernatal='Tercero';
            break;
        case '4':
            # code...
            $ordernatal='Cuarto';
            break;
        case '5':
            # code...
            $ordernatal='Quinto';
            break;
        
        default:
            # code...
            break;
    }
    $pdf->Cell(28,4,$ordernatal,'B',0,'L',0);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientosforaneos['pais']),'LRB',1,'L',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3,'7.- Lugar','L',0,'L',0);
    $pdf->Cell(45,3,'Estado o Categoria Politico-Terri.','',0,'L',0);
    $pdf->Cell(45,3,'Municipio o Categoria Politico-Terri.','',0,'L',0);
    $pdf->Cell(45,3,'Parroquia o Categoria Politico-Terri.','',0,'L',0);
    $pdf->Cell(0,3,'Ciudad o Categoria Politico-Terri.','R',1,'L',0);
    $pdf->Cell(15,4,' Nacimiento','LB',0,'L',0);
    $pdf->SetFont('Arial','I',9);

    ######    LOCALIDADES NACIMIENTO FORANEOS   #######
    $query= "SELECT * FROM venezuelaestados WHERE idestado='$resultados_nacimientosforaneos[idestado]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $estado_nf=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM venezuelamunicipios WHERE  idmunicipio='$resultados_nacimientosforaneos[idmunicipio]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $municipio_nf=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM venezuelaparroquias WHERE  idparroquia='$resultados_nacimientosforaneos[idparroquia]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $parroquia_nf=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM venezuelaciudades WHERE  idciudad='$resultados_nacimientosforaneos[idciudad]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $ciudad_nf=mysql_fetch_array($result);
    mysql_free_result($result);

    if ($resultados_nacimientosforaneos['venezuela']==1) {

        $pdf->Cell(45,4,utf8_decode("ESTADO " .$estado_nf['estado']),'B',0,'L',0);

        $mun_nf = ($resultados_nacimientosforaneos['idmunicipio']=="") ? $resultados_nacimientosforaneos['municipio2'] : $municipio_nf['municipio'] ;
        $par_nf = ($resultados_nacimientosforaneos['idparroquia']=="") ? $resultados_nacimientosforaneos['parroquia2'] : $parroquia_nf['parroquia'] ;
        $ciu_nf = ($resultados_nacimientosforaneos['idciudad']=="") ? $resultados_nacimientosforaneos['ciudad2'] : $ciudad_nf['ciudad'] ;

        $pdf->Cell(45,4,utf8_decode("MUNICIPIO " .$mun_nf),'B',0,'L',0);
        $pdf->Cell(45,4,utf8_decode($par_nf),'B',0,'L',0);
        $pdf->Cell(0,4,utf8_decode($ciu_nf),'RB',1,'L',0);

    } else {
        // realizar aqui las modificaciones para el tipoactanatal=2

        $pdf->Cell(45,4,utf8_decode($resultados_nacimientosforaneos['estado']),'B',0,'L',0);
        $pdf->Cell(45,4,utf8_decode($resultados_nacimientosforaneos['municipio']),'B',0,'L',0);
        $pdf->Cell(45,4,utf8_decode($resultados_nacimientosforaneos['parroquia']),'B',0,'L',0);
        $pdf->Cell(0,4,utf8_decode($resultados_nacimientosforaneos['ciudad']),'RB',1,'L',0);
    }


    // CENTRO SALUD 

    ######    NACIMIENTOSCENTROSALUD #######
    $query= "SELECT * FROM nacimientoscentrosalud WHERE nacimientoscentrosalud.idactanatal='$resultados_nacimientos[idactanatal]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $resultados_nacimientos_centrosalud=mysql_fetch_array($result);
    mysql_free_result($result);

    ######    CENTROSALUD #######
    $query= "SELECT * FROM centrossalud WHERE centrossalud.idcentrosalud='$resultados_nacimientos_centrosalud[idcentrosalud]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $resultados_centrosalud=mysql_fetch_array($result);
    mysql_free_result($result);



    $pdf->SetFont('Arial','',6);
    $pdf->Cell(26,3,'8.- Centro de Salud','LR',0,'L',0);
    $pdf->Cell(130,3,utf8_decode('Nombre de la Institución, Hospital, Centro de Salud o Establecimiento'),'LR',0,'L',0);
    $pdf->Cell(16,3,'Certificado de','L',0,'L',0);
    $pdf->Cell(0,3,' ','R',1,'L',0);

    $pdf->Cell(26,4,' o Establecimiento','LR',0,'L',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(130,4,utf8_decode($resultados_centrosalud['nombrecentrosalud']),'LRB',0,'L',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(16,4,utf8_decode('Nacimiento Nº.'),'LB',0,'L',0);
    $pdf->SetFont('Arial','I',9);
    $certificacionmedica = ($resultados_nacimientos_centrosalud['certificacionmedica']!='') ? $resultados_nacimientos_centrosalud['certificacionmedica'] : 'N/A' ;
    $pdf->Cell(0,4,utf8_decode($certificacionmedica),'RB',1,'L',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(26,3,' ','LR',0,'L',0);
    $pdf->Cell(0,3,' Direccion','LR',1,'L',0);
    $pdf->Cell(26,4,' ','LRB',0,'L',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(0,4,utf8_decode($resultados_centrosalud['direccion']),'LRB',1,'L',0);


    $pdf->SetFont('Arial','',9);
    $pdf->Cell(80,4,utf8_decode('Titulo III - DEL PRESENTANTE'),'LTRB',1,'C',1);
    $pdf->SetFontSize(6);
    $pdf->Cell(40,3,utf8_decode('1.- Filiacion con el presentado'),'LR',0,'L',0);
    $pdf->Cell(40,3,utf8_decode('2.- Facultad con la que Actua'),'LR',1,'L',0);
    $pdf->SetFont('Arial','I',9);
    switch ($resultados_nacimientos['presentante']) {
        case '1':
            $presentante='Padre';
            break;
        case '2':
            $presentante='Madre';
            break;
        case '3':
            $presentante='Mandatorio(a) Especial';
            break;
    }
    switch ($resultados_nacimientos['facultad_pte']) {
        case '1':
            $facultad_pte='N/A';
            break;
        case '2':
            $facultad_pte='N/A';
            break;
        case '2':
            $facultad_pte='N/A';
            break;

        
        default:
            $facultad_pte='N/A';
            break;
    }

    $pdf->Cell(40,4,$presentante,'LR',0,'L',0);
    $pdf->Cell(40,4,utf8_decode($facultad_pte),'LR',1,'L',0);

    // INFORMACION DE LA MADRE CON O SIN IDENTIDAD CIUDADANA
    ######    NACIMIENTOSMADRES   #######
    if ($resultados_nacimientos[madreconid]==1) {
        
        $query="SELECT * FROM nacimientosmadres, ciudadanos, paises WHERE nacimientosmadres.idactanatal='$resultados_nacimientos[idactanatal]' AND  nacimientosmadres.idpersona=ciudadanos.idpersona AND ciudadanos.idpaisnacionalidad=paises.idpais";
        $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
        $resultados_nacimientos_madres=mysql_fetch_array($result);
        mysql_free_result($result);
    } else {
        $query="SELECT * FROM nacimientosmadres_sid,tiposdocumentosidentidad, paises WHERE nacimientosmadres_sid.idactanatal='$resultados_nacimientos[idactanatal]' and nacimientosmadres_sid.idtipodocumento=tiposdocumentosidentidad.idTipoDocumento AND nacimientosmadres_sid.idpaisnacionalidad=paises.idpais";
        $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
        $resultados_nacimientos_madres=mysql_fetch_array($result);
        mysql_free_result($result);
    }
    
    
   

    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,4,'Titulo IV - INFORMACION DE LA MADRE','LTRB',1,'C',1);
    $pdf->SetFontSize(6);
    $pdf->Cell(10,3,utf8_decode('1.- Dctos'),'L',0,'L',0);
    $pdf->Cell(20,3,utf8_decode('Cédula Vzlana Nº.'),'',0,'L',0);
    $pdf->Cell(21,3,utf8_decode('Tipo de Documento'),'',0,'L',0);
    $pdf->Cell(21,3,utf8_decode('Tipo Documento Nº.'),'R',0,'L',0);
    $pdf->Cell(14,3,utf8_decode('2.- Nombres'),'L',0,'L',0);
    $pdf->Cell(28,3,utf8_decode('Primero'),'',0,'L',0);
    $pdf->Cell(28,3,utf8_decode('Segundo'),'',0,'L',0);
    $pdf->Cell(0,3,utf8_decode('Apellidos'),'R',1,'L',0);
    
    $pdf->Cell(10,4,'Indent.','LB',0,'L',0);
    $pdf->SetFont('Arial','I',9);
    $cedula = ($resultados_nacimientos[madreconid]==1) ? $resultados_nacimientos_madres['cedula'] : 'N/A' ;
    $pdf->Cell(20,4,utf8_decode($cedula),'B',0,'L',0);
    $tipo_documento = ($resultados_nacimientos[madreconid]==1) ? 'N/A': $resultados_nacimientos_madres['tipodocumento'] ;
    $pdf->SetFont('Arial','I',6);
    $pdf->Cell(21,4,utf8_decode($tipo_documento),'B',0,'L',0);
    $nro_documento = ($resultados_nacimientos[madreconid]==1) ? 'N/A': $resultados_nacimientos_madres['numerodoc'] ;
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(21,4,utf8_decode($nro_documento),'RB',0,'L',0);
    $pdf->Cell(14,4,' ','LB',0,'L',0);
    $pdf->Cell(28,4,utf8_decode($resultados_nacimientos_madres['primernombre']),'B',0,'L',0);
    $pdf->Cell(28,4,utf8_decode($resultados_nacimientos_madres['segundonombre']),'B',0,'L',0);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientos_madres['primerapellido'].' '.$resultados_nacimientos_madres['segundoapellido']),'RB',1,'L',0);

    $pdf->SetFont('Arial','',6);
    $pdf->Cell(40,3,' 3.- Nacionalidad','LR',0,'L',0);
    $pdf->Cell(28,3,' 4.- Sexo','LR',0,'L',0);
    $pdf->Cell(16,3,' 5.- Edad','LR',0,'L',0);
    // $pdf->Cell(28,3,' 6.- Estado Civil','LR',0,'L',0);
    $pdf->Cell(28,3,' 6.- Estado Civil','LR',0,'L',0);
    $pdf->Cell(18,3,' 7.- Sabe Firmar','LR',0,'L',0);
    $pdf->Cell(0,3,' 8.- Profecion u Ocupacion','LR',1,'L',0);

    $pdf->SetFont('Arial','I',9);
//    $nacionalidad = ($resultados_nacimientos_madres['venezolano']==1) ? 'Venezolana' : 'Extranjera' ;
    $sexo = ($resultados_nacimientos_madres['sexo']==2) ? 'Masculino' : 'Femenino' ;
    $sabeleer = ($resultados_nacimientos_madres['sabeleer']==0) ? 'No' : 'Si' ;
    $pdf->Cell(40,4,$resultados_nacimientos_madres['nacionalidad'],'LRB',0,'L',0);
    $pdf->Cell(28,4,$sexo,'LRB',0,'L',0);
    $edad = ($resultados_nacimientos[madreconid]==1) ? CalculaEdad($resultados_nacimientos_madres['fechanatal']) : $resultados_nacimientos_madres['edad'] ;
    $pdf->Cell(16,4,$edad,'LRB',0,'L',0);
    switch ($resultados_nacimientos_madres['estadocivil']) {
        case '1':
            $estadocivilmadre='Soltera';
            break;
        case '2':
            $estadocivilmadre='Casada';
            break;
        case '3':
            $estadocivilmadre='Divorciada';
            break;
        case '4':
            $estadocivilmadre='Viuda';
            break;
    }
    
    $pdf->Cell(28,4,$estadocivilmadre,'LRB',0,'L',0);
    $pdf->Cell(18,4,$sabeleer,'LRB',0,'L',0);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientos_madres['ocupacion']),'LRB',1,'L',0);

    
    ######    LOCALIDADES NACIMIENTO MADRES   #######
    $query= "SELECT * FROM venezuelaestados WHERE idestado='$resultados_nacimientos_madres[idestado]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $estado_madres=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM venezuelamunicipios WHERE  idmunicipio='$resultados_nacimientos_madres[idmunicipio]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $municipio_madres=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM venezuelaparroquias WHERE  idparroquia='$resultados_nacimientos_madres[idparroquia]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $parroquia_madres=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM venezuelaciudades WHERE  idciudad='$resultados_nacimientos_madres[idciudad]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $ciudad_madres=mysql_fetch_array($result);
    mysql_free_result($result);

    if ($resultados_nacimientos_madres['venezolano']==1) {
        $est_madres = "ESTADO " .$estado_madres['estado'];
        $mun_madres= ($resultados_nacimientos_madres['idmunicipio']=="") ? $resultados_nacimientos_madres['municipio2'] : $municipio_madres['municipio'] ;
        $par_madres= ($resultados_nacimientos_madres['idparroquia']=="") ? $resultados_nacimientos_madres['parroquia2'] : $parroquia_madres['parroquia'] ;
        $ciu_madres= ($resultados_nacimientos_madres['idciudad']=="") ? $resultados_nacimientos_madres['ciudad2'] : $ciudad_madres['ciudad'] ;
        
        $mun_madres= ($mun_madres=="") ? 'N/A' : $mun_madres ;
        $par_madres= ($par_madres=="") ? 'N/A' : $par_madres ;
        $ciu_madres= ($ciu_madres=="") ? 'N/A' : $ciu_madres ;
    } else {
        // realizar aqui las modificaciones para el tipoactanatal=2
        $est_madres="ESTADO " .$resultados_nacimientos_madres['estado'];
        $mun_madres=$resultados_nacimientos_madres['municipio'];
        $par_madres=$resultados_nacimientos_madres['parroquia'];
        $ciu_madres=$resultados_nacimientos_madres['ciudad'];
    }
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(0,4,' 9.- Direccion','LR',1,'L',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientos_madres['direccion']),'LRB',1,'L',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3,'7.- Lugar','L',0,'L',0);
    $pdf->Cell(45,3,'Estado o Categoria Politico-Terri.','',0,'L',0);
    $pdf->Cell(45,3,'Municipio o Categoria Politico-Terri.','',0,'L',0);
    $pdf->Cell(45,3,'Parroquia o Categoria Politico-Terri.','',0,'L',0);
    $pdf->Cell(0,3,'Ciudad, Pueblo, Caserio o Asentamiento','R',1,'L',0);
    $pdf->Cell(15,4,' Nacimiento','LB',0,'L',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(45,4,utf8_decode($est_madres),'B',0,'L',0);
    $pdf->Cell(45,4,utf8_decode($mun_madres),'B',0,'L',0);
    $pdf->Cell(45,4,utf8_decode($par_madres),'B',0,'L',0);
    $pdf->Cell(0,4,utf8_decode($ciu_madres),'RB',1,'L',0);
    
     // INFORMACION DEL PADRE CON O SIN IDENTIDAD CIUDADANA
    ######    NACIMIENTOSPADRES   #######
    if ($resultados_nacimientos[padreconid]==1) {
        
        $query="SELECT * FROM nacimientospadres, ciudadanos,paises WHERE nacimientospadres.idactanatal='$resultados_nacimientos[idactanatal]' AND  nacimientospadres.idpersona=ciudadanos.idpersona AND ciudadanos.idpaisnacionalidad=paises.idpais";
        $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
        $resultados_nacimientos_padres=mysql_fetch_array($result);
        mysql_free_result($result);
    } else {
        $query="SELECT * FROM nacimientospadres_sid,tiposdocumentosidentidad, paises WHERE nacimientospadres_sid.idactanatal='$resultados_nacimientos[idactanatal]' and nacimientospadres_sid.idtipodocumento=tiposdocumentosidentidad.idTipoDocumento AND nacimientospadres_sid.idpaisnacionalidad=paises.idpais";
        $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
        $resultados_nacimientos_padres=mysql_fetch_array($result);
        mysql_free_result($result);
    }
    
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,4,'Titulo IV - INFORMACION DEL PADRE','LTRB',1,'C',1);
    $pdf->SetFontSize(6);
    $pdf->Cell(10,3,utf8_decode('1.- Dctos'),'L',0,'L',0);
    $pdf->Cell(20,3,utf8_decode('Cédula Vzlana Nº.'),'',0,'L',0);
    $pdf->Cell(21,3,utf8_decode('Tipo de Documento'),'',0,'L',0);
    $pdf->Cell(21,3,utf8_decode('Tipo Documento Nº.'),'R',0,'L',0);
    $pdf->Cell(14,3,utf8_decode('2.- Nombres'),'L',0,'L',0);
    $pdf->Cell(28,3,utf8_decode('Primero'),'',0,'L',0);
    $pdf->Cell(28,3,utf8_decode('Segundo'),'',0,'L',0);
    $pdf->Cell(0,3,utf8_decode('Apellidos'),'R',1,'L',0);
    
    $pdf->Cell(10,4,'Indent.','LB',0,'L',0);
    $pdf->SetFont('Arial','I',9);
    $cedula = ($resultados_nacimientos[padreconid]==1) ? $resultados_nacimientos_padres['cedula'] : 'N/A' ;
    $pdf->Cell(20,4,utf8_decode($cedula),'B',0,'L',0);
    $tipo_documento = ($resultados_nacimientos[padreconid]==1) ? 'N/A': $resultados_nacimientos_padres['tipodocumento'] ;
    $pdf->SetFont('Arial','I',6);
    $pdf->Cell(21,4,utf8_decode($tipo_documento),'B',0,'L',0);
    $nro_documento = ($resultados_nacimientos[padreconid]==1) ? 'N/A': $resultados_nacimientos_padres['numerodoc'] ;
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(21,4,utf8_decode($nro_documento),'RB',0,'L',0);
    $pdf->Cell(14,4,' ','LB',0,'L',0);
    $pdf->Cell(28,4,utf8_decode($resultados_nacimientos_padres['primernombre']),'B',0,'L',0);
    $pdf->Cell(28,4,utf8_decode($resultados_nacimientos_padres['segundonombre']),'B',0,'L',0);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientos_padres['primerapellido'].' '.$resultados_nacimientos_padres['segundoapellido']),'RB',1,'L',0);

    $pdf->SetFont('Arial','',6);
    $pdf->Cell(40,3,' 3.- Nacionalidad','LR',0,'L',0);
    $pdf->Cell(28,3,' 4.- Sexo','LR',0,'L',0);
    $pdf->Cell(16,3,' 5.- Edad','LR',0,'L',0);
    // $pdf->Cell(28,3,' 6.- Estado Civil','LR',0,'L',0);
    $pdf->Cell(28,3,' 6.- Estado Civil','LR',0,'L',0);
    $pdf->Cell(18,3,' 7.- Sabe Firmar','LR',0,'L',0);
    $pdf->Cell(0,3,' 8.- Profecion u Ocupacion','LR',1,'L',0);

    $pdf->SetFont('Arial','I',9);
//    $nacionalidad = ($resultados_nacimientos_padres['venezolano']==1) ? 'Venezolana' : 'Extranjera' ;
    $sexo = ($resultados_nacimientos[padreconid]==1) ? ($resultados_nacimientos_padres['sexo']==2) ? 'Masculino' : 'Femenino': 'N/A' ;
    $sabeleer = ($resultados_nacimientos_padres['sabeleer']==0) ? 'No' : 'Si' ;
    $pdf->Cell(40,4,$resultados_nacimientos_padres['nacionalidad'],'LRB',0,'L',0);
    $pdf->Cell(28,4,'Masculino','LRB',0,'L',0);
    $edad = ($resultados_nacimientos[padreconid]==1) ? CalculaEdad($resultados_nacimientos_padres['fechanatal']) : $resultados_nacimientos_padres['edad'] ;
    $pdf->Cell(16,4,$edad,'LRB',0,'L',0);
    switch ($resultados_nacimientos_padres['estadocivil']) {
        case '1':
            $estadocivilpadre='Soltero';
            break;
        case '2':
            $estadocivilpadre='Casado';
            break;
        case '3':
            $estadocivilpadre='Divorciado';
            break;
        case '4':
            $estadocivilpadre='Viudo';
            break;
    }
    
    $pdf->Cell(28,4,$estadocivilpadre,'LRB',0,'L',0);
    $pdf->Cell(18,4,$sabeleer,'LRB',0,'L',0);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientos_padres['ocupacion']),'LRB',1,'L',0);

    
    ######    LOCALIDADES NACIMIENTO MADRES   #######
    $query= "SELECT * FROM venezuelaestados WHERE idestado='$resultados_nacimientos_padres[idestado]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $estado_padres=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM venezuelamunicipios WHERE  idmunicipio='$resultados_nacimientos_padres[idmunicipio]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $municipio_padres=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM venezuelaparroquias WHERE  idparroquia='$resultados_nacimientos_padres[idparroquia]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $parroquia_padres=mysql_fetch_array($result);
    mysql_free_result($result);

    $query= "SELECT * FROM venezuelaciudades WHERE  idciudad='$resultados_nacimientos_padres[idciudad]'";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $ciudad_padres=mysql_fetch_array($result);
    mysql_free_result($result);

    if ($resultados_nacimientos_padres['venezolano']==1) {
        $est_padres = "ESTADO " .$estado_madres['estado'];
        $mun_padres= ($resultados_nacimientos_padres['idmunicipio']=="") ? $resultados_nacimientos_padres['municipio2'] : $municipio_padres['municipio'] ;
        $par_padres= ($resultados_nacimientos_padres['idparroquia']=="") ? $resultados_nacimientos_padres['parroquia2'] : $parroquia_padres['parroquia'] ;
        $ciu_padres= ($resultados_nacimientos_padres['idciudad']=="") ? $resultados_nacimientos_padres['ciudad2'] : $ciudad_padres['ciudad'] ;
        
        $mun_padres= ($mun_padres=="") ? 'N/A' : $mun_padres ;
        $par_padres= ($par_padres=="") ? 'N/A' : $par_padres ;
        $ciu_padres= ($ciu_padres=="") ? 'N/A' : $ciu_padres ;
    } else {
        // realizar aqui las modificaciones para el tipoactanatal=2
        $est_padres="ESTADO " .$resultados_nacimientos_padres['estado'];
        $mun_padres=$resultados_nacimientos_padres['municipio'];
        $par_padres=$resultados_nacimientos_padres['parroquia'];
        $ciu_mpadres=$resultados_nacimientos_padres['ciudad'];
    }
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(0,4,' 9.- Direccion','LR',1,'L',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientos_padres['direccion']),'LRB',1,'L',0);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(15,3,'7.- Lugar','L',0,'L',0);
    $pdf->Cell(45,3,'Estado o Categoria Politico-Terri.','',0,'L',0);
    $pdf->Cell(45,3,'Municipio o Categoria Politico-Terri.','',0,'L',0);
    $pdf->Cell(45,3,'Parroquia o Categoria Politico-Terri.','',0,'L',0);
    $pdf->Cell(0,3,'Ciudad, Pueblo, Caserio o Asentamiento','R',1,'L',0);
    $pdf->Cell(15,4,' Nacimiento','LB',0,'L',0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(45,4,utf8_decode($est_padres),'B',0,'L',0);
    $pdf->Cell(45,4,utf8_decode($mun_padres),'B',0,'L',0);
    $pdf->Cell(45,4,utf8_decode($par_padres),'B',0,'L',0);
    $pdf->Cell(0,4,utf8_decode($ciu_padres),'RB',1,'L',0);
    
    

   // INFORMACION DE LOS TESTIGOS 1 CON O SIN IDENTIDAD CIUDADANA
    ######    NACIMIENTOSTESTIGO 1  #######
    if ($resultados_nacimientos[testigo1conid]==1) {
        
        $query="SELECT * FROM nacimientostestigos, ciudadanos, paises WHERE nacimientostestigos.idactanatal='$resultados_nacimientos[idactanatal]' AND  nacimientostestigos.idpersona=ciudadanos.idpersona AND nacimientostestigos.ordentestigo=1 AND ciudadanos.idpaisnacionalidad=paises.idpais";
        $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
        $resultados_nacimientos_testigo1=mysql_fetch_array($result);
        mysql_free_result($result);
    } else {
        $query="SELECT * FROM nacimientostestigos_sid,tiposdocumentosidentidad,paises WHERE nacimientostestigos_sid.idactanatal='$resultados_nacimientos[idactanatal]' and nacimientostestigos_sid.idtipodocumento=tiposdocumentosidentidad.idTipoDocumento AND nacimientostestigos_sid.ordentestigo=1 AND nacimientostestigos_sid.idpaisnacionalidad=paises.idpais";
        $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
        $resultados_nacimientos_testigo1=mysql_fetch_array($result);
        mysql_free_result($result);
    }
    
    
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,4,'Titulo VI.a - INFORMACION TESTIGO Nro. 1','LTRB',1,'C',1);
    $pdf->SetFontSize(6);
    $pdf->Cell(10,3,utf8_decode('1.- Dctos'),'L',0,'L',0);
    $pdf->Cell(20,3,utf8_decode('Cédula Vzlana Nº.'),'',0,'L',0);
    $pdf->Cell(21,3,utf8_decode('Tipo de Documento'),'',0,'L',0);
    $pdf->Cell(21,3,utf8_decode('Tipo Documento Nº.'),'R',0,'L',0);
    $pdf->Cell(14,3,utf8_decode('2.- Nombres'),'L',0,'L',0);
    $pdf->Cell(28,3,utf8_decode('Primero'),'',0,'L',0);
    $pdf->Cell(28,3,utf8_decode('Segundo'),'',0,'L',0);
    $pdf->Cell(0,3,utf8_decode('Apellidos'),'R',1,'L',0);

    $pdf->Cell(10,4,'Indent.','LB',0,'L',0);
    $pdf->SetFont('Arial','I',9);
    $cedula = ($resultados_nacimientos[testigo1conid]==1) ? $resultados_nacimientos_testigo1['cedula'] : 'N/A' ;
    $pdf->Cell(20,4,utf8_decode($cedula),'B',0,'L',0);
    $tipo_documento = ($resultados_nacimientos[testigo1conid]==1) ? 'N/A': $resultados_nacimientos_testigo1['tipodocumento'] ;
    $pdf->SetFont('Arial','I',6);
    $pdf->Cell(21,4,utf8_decode($tipo_documento),'B',0,'L',0);
    $nro_documento = ($resultados_nacimientos[testigo1conid]==1) ? 'N/A': $resultados_nacimientos_testigo1['numerodoc'] ;
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(21,4,utf8_decode($nro_documento),'RB',0,'L',0);
    $pdf->Cell(14,4,' ','LB',0,'L',0);
    $pdf->Cell(28,4,utf8_decode($resultados_nacimientos_testigo1['primernombre']),'B',0,'L',0);
    $pdf->Cell(28,4,utf8_decode($resultados_nacimientos_testigo1['segundonombre']),'B',0,'L',0);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientos_testigo1['primerapellido'].' '.$resultados_nacimientos_testigo1['segundoapellido']),'RB',1,'L',0);

    $pdf->SetFont('Arial','',6);
    $pdf->Cell(40,3,' 3.- Nacionalidad','LR',0,'L',0);
    $pdf->Cell(28,3,' 4.- Sexo','LR',0,'L',0);
    $pdf->Cell(16,3,' 5.- Edad','LR',0,'L',0);
    // $pdf->Cell(28,3,' 6.- Estado Civil','LR',0,'L',0);
    $pdf->Cell(28,3,' 6.- Estado Civil','LR',0,'L',0);
    $pdf->Cell(18,3,' 7.- Sabe Firmar','LR',0,'L',0);
    $pdf->Cell(0,3,' 8.- Profecion u Ocupacion','LR',1,'L',0);

    $pdf->SetFont('Arial','I',9);
//    $nacionalidad = ($resultados_nacimientos_testigo1['venezolano']==1) ? 'Venezolana' : 'Extranjera' ;
    $sexo = ($resultados_nacimientos_testigo1['sexo']==2) ? 'Masculino' : 'Femenino' ;
    $sabeleer = ($resultados_nacimientos_testigo1['sabeleer']=="") ? "N/A": (($resultados_nacimientos_testigo1['sabeleer']==0) ? 'No' : 'Si') ;
    $pdf->Cell(40,4,$resultados_nacimientos_testigo1['nacionalidad'],'LRB',0,'L',0);
    $pdf->Cell(28,4,$sexo,'LRB',0,'L',0);
    $edad = ($resultados_nacimientos[testigo1conid]==1) ? CalculaEdad($resultados_nacimientos_testigo1['fechanatal']) : $resultados_nacimientos_testigo1['edad'] ;
    $edad = ($edad=="") ? 'N/A' : $edad ;
    $pdf->Cell(16,4,$edad,'LRB',0,'L',0);
    switch ($resultados_nacimientos_testigo1['estadocivil']) {
        case '1':
            $estadociviltestigo1='Soltero(a)';
            break;
        case '2':
            $estadociviltestigo1='Casado(a)';
            break;
        case '3':
            $estadociviltestigo1='Divorciado(a)';
            break;
        case '4':
            $estadociviltestigo1='Viudo(a)';
            break;
    }
    $estadociviltestigo1 = ($estadociviltestigo1=="") ? 'N/A' : $estadociviltestigo1 ;
    $pdf->Cell(28,4,$estadociviltestigo1,'LRB',0,'L',0);
    $pdf->Cell(18,4,$sabeleer,'LRB',0,'L',0);
    $ocupacion_testigo1 = ($resultados_nacimientos_testigo1['ocupacion']=="") ? 'N/A' : $resultados_nacimientos_testigo1['ocupacion'] ;
    $pdf->Cell(0,4,utf8_decode($ocupacion_testigo1),'LRB',1,'L',0);
    
   // INFORMACION DE LOS TESTIGOS 2 CON O SIN IDENTIDAD CIUDADANA
    ######    NACIMIENTOSTESTIGO 2  #######
    if ($resultados_nacimientos[testigo2conid]==1) {
        
        $query="SELECT * FROM nacimientostestigos, ciudadanos, paises WHERE nacimientostestigos.idactanatal='$resultados_nacimientos[idactanatal]' AND  nacimientostestigos.idpersona=ciudadanos.idpersona AND nacimientostestigos.ordentestigo=2 AND ciudadanos.idpaisnacionalidad=paises.idpais";
        $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
        $resultados_nacimientos_testigo2=mysql_fetch_array($result);
        mysql_free_result($result);
    } else {
        $query="SELECT * FROM nacimientostestigos_sid,tiposdocumentosidentidad,paises WHERE nacimientostestigos_sid.idactanatal='$resultados_nacimientos[idactanatal]' and nacimientostestigos_sid.idtipodocumento=tiposdocumentosidentidad.idTipoDocumento AND nacimientostestigos_sid.ordentestigo=2 AND nacimientostestigos_sid.idpaisnacionalidad=paises.idpais";
        $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
        $resultados_nacimientos_testigo2=mysql_fetch_array($result);
        mysql_free_result($result);
    }
    
    
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,4,'Titulo VI.b - INFORMACION TESTIGO Nro. 2','LTRB',1,'C',1);
    $pdf->SetFontSize(6);
    $pdf->Cell(10,3,utf8_decode('1.- Dctos'),'L',0,'L',0);
    $pdf->Cell(20,3,utf8_decode('Cédula Vzlana Nº.'),'',0,'L',0);
    $pdf->Cell(21,3,utf8_decode('Tipo de Documento'),'',0,'L',0);
    $pdf->Cell(21,3,utf8_decode('Tipo Documento Nº.'),'R',0,'L',0);
    $pdf->Cell(14,3,utf8_decode('2.- Nombres'),'L',0,'L',0);
    $pdf->Cell(28,3,utf8_decode('Primero'),'',0,'L',0);
    $pdf->Cell(28,3,utf8_decode('Segundo'),'',0,'L',0);
    $pdf->Cell(0,3,utf8_decode('Apellidos'),'R',1,'L',0);

    $pdf->Cell(10,4,'Indent.','LB',0,'L',0);
    $pdf->SetFont('Arial','I',9);
    $cedula = ($resultados_nacimientos[testigo2conid]==1) ? $resultados_nacimientos_testigo2['cedula'] : 'N/A' ;
    $pdf->Cell(20,4,utf8_decode($cedula),'B',0,'L',0);
    $tipo_documento = ($resultados_nacimientos[testigo2conid]==1) ? 'N/A': $resultados_nacimientos_testigo2['tipodocumento'] ;
    $pdf->SetFont('Arial','I',6);
    $pdf->Cell(21,4,utf8_decode($tipo_documento),'B',0,'L',0);
    $nro_documento = ($resultados_nacimientos[testigo2conid]==1) ? 'N/A': $resultados_nacimientos_testigo2['numerodoc'] ;
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(21,4,utf8_decode($nro_documento),'RB',0,'L',0);
    $pdf->Cell(14,4,' ','LB',0,'L',0);
    $pdf->Cell(28,4,utf8_decode($resultados_nacimientos_testigo2['primernombre']),'B',0,'L',0);
    $pdf->Cell(28,4,utf8_decode($resultados_nacimientos_testigo2['segundonombre']),'B',0,'L',0);
    $pdf->Cell(0,4,utf8_decode($resultados_nacimientos_testigo2['primerapellido'].' '.$resultados_nacimientos_testigo2['segundoapellido']),'RB',1,'L',0);

    $pdf->SetFont('Arial','',6);
    $pdf->Cell(40,3,' 3.- Nacionalidad','LR',0,'L',0);
    $pdf->Cell(28,3,' 4.- Sexo','LR',0,'L',0);
    $pdf->Cell(16,3,' 5.- Edad','LR',0,'L',0);
    // $pdf->Cell(28,3,' 6.- Estado Civil','LR',0,'L',0);
    $pdf->Cell(28,3,' 6.- Estado Civil','LR',0,'L',0);
    $pdf->Cell(18,3,' 7.- Sabe Firmar','LR',0,'L',0);
    $pdf->Cell(0,3,' 8.- Profecion u Ocupacion','LR',1,'L',0);

    $pdf->SetFont('Arial','I',9);
//    $nacionalidad = ($resultados_nacimientos_testigo1['venezolano']==1) ? 'Venezolana' : 'Extranjera' ;
    $sexo = ($resultados_nacimientos_testigo2['sexo']==2) ? 'Masculino' : 'Femenino' ;
    $sabeleer = ($resultados_nacimientos_testigo2['sabeleer']=="") ? "N/A": (($resultados_nacimientos_testigo2['sabeleer']==0) ? 'No' : 'Si') ;
    $pdf->Cell(40,4,$resultados_nacimientos_testigo2['nacionalidad'],'LRB',0,'L',0);
    $pdf->Cell(28,4,$sexo,'LRB',0,'L',0);
    $edad = ($resultados_nacimientos[testigo2conid]==1) ? CalculaEdad($resultados_nacimientos_testigo2['fechanatal']) : $resultados_nacimientos_testigo2['edad'] ;
    $edad = ($edad=="") ? 'N/A' : $edad ;
    $pdf->Cell(16,4,$edad,'LRB',0,'L',0);
    switch ($resultados_nacimientos_testigo2['estadocivil']) {
        case '1':
            $estadociviltestigo2='Soltero(a)';
            break;
        case '2':
            $estadociviltestigo2='Casado(a)';
            break;
        case '3':
            $estadociviltestigo2='Divorciado(a)';
            break;
        case '4':
            $estadociviltestigo2='Viudo(a)';
            break;
    }
    $estadociviltestigo2 = ($estadociviltestigo2=="") ? 'N/A' : $estadociviltestigo2 ;
    $pdf->Cell(28,4,$estadociviltestigo2,'LRB',0,'L',0);
    $pdf->Cell(18,4,$sabeleer,'LRB',0,'L',0);
    $ocupacion_testigo2 = ($resultados_nacimientos_testigo2['ocupacion']=="") ? 'N/A' : $resultados_nacimientos_testigo2['ocupacion'] ;
    $pdf->Cell(0,4,utf8_decode($ocupacion_testigo2),'LRB',1,'L',0);
    
    // FIN DE LA INFORMACION DE TESTIGOS
    
    $pdf->Ln(1);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(0,4,utf8_decode('Certifico que la información contenida en ésta acta ha sido tomada de forma fiel y exacta del acta original que reposa en los libros de nacimiento llevados por ésta oficina.'),0,1,'L',0);

    ######    CONFIGURACIONES    #######
    $query= "SELECT * FROM registroconfiguracion, registradores, registradorescargos, registradoresprofesiones, registradoresresoluciones WHERE registroconfiguracion.idconfiguracion='1' AND registroconfiguracion.idregistrador=registradores.idregistrador AND registroconfiguracion.idcargo=registradorescargos.idcargo AND registroconfiguracion.idregistradorresolucion=registradoresresoluciones.idregistradorresolucion AND registroconfiguracion.idprofesionregistrador=registradoresprofesiones.idregistrador";
    $result = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
    $resultados_configuraciones=mysql_fetch_array($result);
    mysql_free_result($result);

    $pdf->Ln(15);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(120);
    $pdf->Cell(0,4,utf8_decode($resultados_configuraciones['profesion'].' '.$resultados_configuraciones['nombreregistrador']),0,1,'L',0);
    $pdf->Cell(120);
    $pdf->Cell(0,4,utf8_decode($resultados_configuraciones['cargo']),0,1,'L',0);
    $pdf->Cell(120);
    $pdf->Cell(0,4,utf8_decode('Guanare, Portuguesa'),0,1,'L',0);
    $pdf->Cell(120);
    $pdf->Cell(0,4,utf8_decode('Resolución Nº. '.$resultados_configuraciones['resolucion'].' de fecha '.date_format(date_create($resultados_configuraciones['fecharesolucion']), 'd/m/Y')),0,1,'L',0);
    
    // -------------------------------------------------- //
    //         PROPIEDADES DE LOS CODIGOS DE BARRAS
    // -------------------------------------------------- //

    $fontSize = 8;
    $marge    = -5;    // between barcode and hri in pixel
    $x        = 175;  // barcode center
    $y        = 29;  // barcode center
    $height   = 10;   // barcode height in 1D ; module size in 2D
    $width    = 0.4;    // barcode height in 1D ; not use in 2D
    $angle    = 0;    // rotation in degrees

    $code     = $idactanatal; // barcode, of course ;)  
    $type     = 'code39';   // 
    $black    = '000000'; // color in hexa


    // -------------------------------------------------- //
    //                      BARCODE
    // -------------------------------------------------- //

    $data = Barcode::fpdf($pdf, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
    $pdf->SetFont('Arial','B',$fontSize);
    $pdf->SetTextColor(0, 0, 0);
    $len = $pdf->GetStringWidth($data['hri']);
    Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
    $pdf->TextWithRotation($x + $xt, $y + $yt,$data['hri'], $angle);
    $pdf->TextWithRotation($x + $xt, $y + $yt,'', $angle);
    
    // -------------------- FIN BARCODE -------------------------- //

    mysql_close();
    $pdf->Output("PART-".$idactanatal.".pdf","I");
?>

