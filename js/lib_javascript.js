//-------------------------------------------------------------------------------------------------//
//-----------------------------------funciones para los formularios -------------------------------//
//-------------------------------------------------------------------------------------------------//
var cursor;
if (document.all) {
// Está utilizando EXPLORER
cursor='hand';
} else {
// Está utilizando MOZILLA/NETSCAPE
cursor='pointer';
}


function activar_stock(){	
    if(document.QForm.status_stock.checked==true){
//        document.QForm.stock.value=0;
//        document.QForm.stock_minimo.value=1;
        Effect.Appear('contenedor_stock');
    }else{		
        document.QForm.stock.value=0;
        document.QForm.stock_minimo.value=1;
        Effect.Fade('contenedor_stock');
    }	
}

function submit_ticket_load() {         
  /*document.dir_public.action="?option=dir_public";*/
   /*document.dir_public.action="directorio.php";*/
    document.ticket_load.submit(); 
}

function verinsertados(){
    var contenedor;
    contenedor = document.getElementById('div_datos');
    ajax=nuevoAjax();
    ajax.open("GET", "procesos.php?ver=1",true);
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
           contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null)
}
		
function cargarContenidoPersona2(){
	var d1,d2,contenedor;
	contenedor = document.getElementById('ContenedorPersonas');
	d1 = document.facturacion.cedula_solicitante.value;
	d2=location.href;
	ajax=objetoAjax();
	ajax.open("GET", "procesos.php?accion=consultarpersona&cedula="+d1+"&dir="+d2,true);
	contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
			contenedor.innerHTML = ajax.responseText
                        setTimeout ("ver_msgload()", 5000); //tiempo de espera en milisegundos
		}
	}
	ajax.send(null)
}
function cargarContenidoPersona3(){
	var d1,d2,contenedor;
	contenedor = document.getElementById('ContenedorPersonas');
	d1 = document.facturacion.cedula_solicitante.value;
	d2=location.href;
	ajax=objetoAjax();
	ajax.open("GET", "procesos.php?accion=consultarpersona&cedula="+d1+"&dir="+d2,true);
	contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
			contenedor.innerHTML = ajax.responseText
                        setTimeout ("ver_msgload()", 5000); //tiempo de espera en milisegundos
		}
	}
	ajax.send(null)
}
function cargarContenidoPersona(){
    var d1,d2,contenedor,contenedor2;
    contenedor = document.getElementById('ContenedorPersonas');
    contenedor2 = document.getElementById('ContenedorPersonaAdd');
    d1 = document.facturacion.cedula_solicitante.value;
    d2=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=consultarpersona&cedula="+d1+"&dir="+d2,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
            ajax2=objetoAjax();
            ajax2.open("GET", "procesos.php?accion=consultarpersona&load=1&cedula="+d1+"&dir="+d2,true);
            contenedor2.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
            ajax2.onreadystatechange=function(){
                if (ajax2.readyState==4){
                    contenedor2.innerHTML = ajax2.responseText
                    setTimeout ("ver_msgload_pers()", 5000); //tiempo de espera en milisegundos
                }
            }
            ajax2.send(null)
        }
    }
    ajax.send(null)
}
function ver_msgload_pers(){
    Effect.Fade('msgloadpers');
}

function cargarContenedorConcepto(){
	var d1,d2,d3,contenedor;
	contenedor = document.getElementById('ContenedorConcepto');
	d1 = document.formulario_lineas.codigo_concepto.value;
	d2 = document.formulario_lineas.codfacturatmp.value;
	d3=location.href;
	ajax=objetoAjax();
	ajax.open("GET", "procesos.php?accion=consultarconcepto&codigo="+d1+"&n_facturatmp="+d2+"&dir="+d3,true);
	contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
			contenedor.innerHTML = ajax.responseText
            setTimeout ("ver_msgload()", 5000); //tiempo de espera en milisegundos
            validar();
		}
	}
	ajax.send(null)
}

function cargarContenedorProducto(){
	var d1,d2,d3,contenedor;
	contenedor = document.getElementById('ContenedorConcepto');
	d1 = document.formulario_lineas.codigo_concepto.value;
	d2 = document.formulario_lineas.codfacturatmp.value;
	d3=location.href;
	ajax=objetoAjax();
	ajax.open("GET", "../procesos.php?accion=consultarproducto&codigo="+d1+"&n_facturatmp="+d2+"&dir="+d3,true);
	contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
			contenedor.innerHTML = ajax.responseText
                        validarproducto();
                        setTimeout ("ver_msgload()", 5000); //tiempo de espera en milisegundos
		}
	}
	ajax.send(null)
}
function ver_msgload(){
    Effect.Fade('msgload');
}

function validar() {
    var mensaje="";
    var entero=0;
    var cant=0;
    var existencia=0;
    var status_stock=0;
    var cant_factura=0;

    if (document.getElementById("codigo_concepto").value=="") mensaje="  - Codigo Concepto\n";
    if (document.getElementById("descripcion_concepto").value=="") mensaje+="  - Descripcion\n";
    entero=parseInt(document.getElementById("cantidad").value);
    cant=parseFloat(document.getElementById("cantidad").value);
    existencia=parseFloat(document.getElementById("stock").value);
    status_stock=parseFloat(document.getElementById("status_stock").value);
    cant_factura=parseFloat(document.getElementById("cantidad_factura").value);
    
    if (document.getElementById("costo_unitario").value=="") { 
       mensaje+="  - Falta el precio\n"; 
    } else {
        if (isNaN(document.getElementById("costo_unitario").value)==true) {
                mensaje+="  - El precio debe ser numerico\n";
        }
    }

    if (document.getElementById("cantidad").value==""){ 
        mensaje+="  - Falta la cantidad\n";
    }else {
        if (isNaN(entero)==true) {
                mensaje+="  - La cantidad debe ser numerica\n";
        }
    }

    if (cant < 1){
        mensaje+="  - La cantidad debe ser MAYOR de 0(Cero)\n";
    }

    if (status_stock==1){
        if ((cant+cant_factura) > existencia) {
            mensaje+="  - La Cantidad solicitada: "+(cant+cant_factura)+" excede a lo Disponible: "+existencia+"\n";
        }else{
            document.getElementById("cantidad").value=entero;
        }
    }
    
    if (document.getElementById("total").value=="") mensaje+="  - Falta el total\n";

    if (mensaje!="") {
            alert("Atencion, se han detectado los siguientes Errores:\n\n"+mensaje);
    } else {
        cambio_iva();
        monto_letras();
        document.getElementById("formulario_lineas").submit();
        document.getElementById("codigo_concepto").value="";
        document.getElementById("descripcion_concepto").value="";
        document.getElementById("costo_unitario").value="";
        document.getElementById("iva_producto").value="";
        document.getElementById("cantidad").value=1;
        document.getElementById("total").value="";
    }
}
function validarproducto() {
    var mensaje="";
    var entero=0;
    var cant=0;
    var existencia=0;
    var status_stock=0;
    var cant_factura=0;

    if (document.getElementById("codigo_concepto").value=="") mensaje="  - Codigo Concepto\n";
    if (document.getElementById("descripcion_concepto").value=="") mensaje+="  - Descripcion\n";
    entero=parseInt(document.getElementById("cantidad").value);
    cant=parseFloat(document.getElementById("cantidad").value);
    existencia=parseFloat(document.getElementById("stock").value);
    status_stock=parseFloat(document.getElementById("status_stock").value);
    cant_factura=parseFloat(document.getElementById("cantidad_factura").value);
    
    if (document.getElementById("costo_unitario").value=="") { 
       mensaje+="  - Falta el precio\n"; 
    } else {
        if (isNaN(document.getElementById("costo_unitario").value)==true) {
                mensaje+="  - El precio debe ser numerico\n";
        }
    }
    if (document.getElementById("cantidad").value==""){ 
        mensaje+="  - Falta la cantidad\n";
    }else {
        if (isNaN(entero)==true) {
                mensaje+="  - La cantidad debe ser numerica\n";
        }
    }
    if (cant < 1){
        mensaje+="  - La cantidad debe ser diferente de 0(Cero)\n";
    }
    if (status_stock==1){
        if ((cant+cant_factura) > existencia) {
            mensaje+="  - La Cantidad solicitada: "+(cant+cant_factura)+" excede a lo Disponible: "+existencia+"\n";
        }else{
            document.getElementById("cantidad").value=entero;
        }
    }
    
    if (document.getElementById("total").value=="") mensaje+="  - Falta el total\n";

    if (mensaje!="") {
            alert("Atencion, se han detectado los siguientes Errores:\n\n"+mensaje);
    } else {
            document.getElementById("control").value=0;
            document.getElementById("subtotal").value=parseFloat(document.getElementById("subtotal").value) + parseFloat(document.getElementById("total").value);	
            cambio_iva_producto();
//            monto_letras_producto();
            document.getElementById("formulario_lineas").submit();
            document.getElementById("codigo_concepto").value="";
            document.getElementById("descripcion_concepto").value="";
            document.getElementById("costo_unitario").value="";
            document.getElementById("iva_producto").value="";
            document.getElementById("cantidad").value=1;
            document.getElementById("total").value="";
    }
}
function validarCargarProducto() {
    document.getElementById("control").value=1;
    document.getElementById("subtotal").value=parseFloat(document.getElementById("subtotal").value);	
    cambio_iva_producto();
//    monto_letras();
    document.getElementById("formulario_lineas").submit();
}
function validarCargarProductoFactura() {
    document.getElementById("control").value=1;
    document.getElementById("subtotal").value=parseFloat(document.getElementById("subtotal").value);	
    cambio_iva_productoFactura();
    monto_letras();
    document.getElementById("formulario_lineas").submit();  
}

function cambio_iva() {
    var cantidad=parseFloat(document.getElementById("cantidad").value);
    var costo_unitario=parseFloat(document.getElementById("costo_unitario").value);
    var iva_producto=parseFloat(document.getElementById("iva_producto").value);
    var subtotal= parseFloat(document.getElementById("subtotal").value);

//calculo del subtotal
    var multisubtotal=parseFloat(cantidad*costo_unitario);
    var result_subtotal=parseFloat(multisubtotal+subtotal);
    document.getElementById("subtotal").value=result_subtotal.toFixed(2);

//calculo del iva
    var result_iva=parseFloat(document.getElementById("totalimpuestos").value) + parseFloat(document.getElementById("iva_producto").value);   
    document.getElementById("totalimpuestos").value=result_iva.toFixed(2);

//calculo del precio total
    var preciototal=parseFloat(result_subtotal+result_iva);
    document.getElementById("preciototal").value=preciototal.toFixed(2);
}

function cambio_iva_producto() {
    var original=parseFloat(document.getElementById("subtotal").value);
    var result=Math.round(original*100)/100 ;
    document.getElementById("subtotal").value=result.toFixed(2);

    document.getElementById("totalimpuestos").value=parseFloat(result * parseFloat(document.getElementById("iva").value / 100));
    var original1=parseFloat(document.getElementById("totalimpuestos").value);
    var result1=Math.round(original1*100)/100 ;
    document.getElementById("totalimpuestos").value=result1.toFixed(2);
    var original2=parseFloat(result + result1);
    var result2=Math.round(original2*100)/100 ;
    document.getElementById("preciototal").value=result2.toFixed(2);     
}

function cambio_iva_productoFactura() {
    var original=parseFloat(document.getElementById("subtotal").value);
    var result=Math.round(original*100)/100 ;
    document.getElementById("subtotal").value=result.toFixed(2);

    document.getElementById("totalimpuestos").value=parseFloat(result * parseFloat(document.getElementById("iva").value / 100));
    var original1=parseFloat(document.getElementById("totalimpuestos").value);
    var result1=Math.round(original1*100)/100 ;
    document.getElementById("totalimpuestos").value=result1.toFixed(2);
    var original2=parseFloat(result + result1);
    var result2=Math.round(original2*100)/100 ;
    document.getElementById("preciototal").value=result2.toFixed(2);   
}


function monto_letras(){
    var d1,d2,contenedor;
    contenedor = document.getElementById('div_total_letras');
    d1 = document.getElementById("preciototal").value;
    d2=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=montoletras&monto="+d1+"&dir="+d2,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
            if (ajax.readyState==4){
                    contenedor.innerHTML = ajax.responseText
            }
    }
    ajax.send(null)
}

function ventanaconceptos() //esta es la funcion para abrir el catalogo de conceptos para facturar.
{  
    var mensaje="";
    var codfacturatmp=document.facturacion.codfacturatmp.value;
    
    miPopup = window.open("facturacion/factura/catalogo_concepto.php?codfacturatmp="+codfacturatmp,"miwin","width=1100,height=500,scrollbars=yes");
    miPopup.focus();
}

function ventanaproductos() //esta es la funcion para abrir el catalogo de conceptos para facturar.
{  
    var mensaje="";
    var codfacturatmp=document.formulario_lineas.codfacturatmp.value;
    
    miPopup = window.open("../facturacion/factura/catalogo_concepto.php?codfacturatmp="+codfacturatmp,"miwin","width=1100,height=500,scrollbars=yes");
    miPopup.focus();
}

function ventanaconceptos_detalles() //esta es la funcion para abrir el catalogo de conceptos para facturar.
{  
    var mensaje="";
    var codigo_nota=document.facturacion.codigo_nota.value;
    
    miPopup = window.open("facturacion/detalle_nota/catalogo_concepto.php?codigo_nota="+codigo_nota,"miwin","width=1100,height=500,scrollbars=yes");
    miPopup.focus();
}

function ventanaconceptoscompras() //esta es la funcion para abrir el catalogo de conceptos para facturar.
{  
    var mensaje="";
    var codigo_compra=document.facturacion.codigo_compra.value;
    
    miPopup = window.open("facturacion/detalle_compra/catalogo_concepto.php?codigo_compra="+codigo_compra,"miwin","width=1100,height=500,scrollbars=yes");
    miPopup.focus();
}

function submit_facturas() {         
    document.QForm.submit(); 
}

function ventanacliente() //esta es la funcion para abrir el catalogo de clientes para facturar.
{
    miPopup = window.open("presupuestos/catalogo_cliente.php","miwin","width=1100,height=500,scrollbars=yes");
    miPopup.focus();
}

function ventanaanalisiscliente() //esta es la funcion para abrir el catalogo de clientes para facturar.
{
    miPopup = window.open("muestras/catalogo_cliente_analisis.php","miwin","width=1100,height=500,scrollbars=yes");
    miPopup.focus();
}

function actualizar_importe(){
    var precio=document.getElementById("costo_unitario").value;
    var cantidad=document.getElementById("cantidad").value;
    total=precio*cantidad; //aqui es donde se ase el calculo de la 
    var original=parseFloat(total);
    document.getElementById("total").value=original.toFixed(2);
}

function CalcularPrecioVenta(){
    var excento=parseFloat(document.getElementById("excento_iva").value);
    if (isNaN(excento)==true) {
            excento=0;
        }

    if (excento=="1"){
        var precio=parseFloat(document.getElementById("costo_unitario").value);
        if (isNaN(precio)==true) {
            precio=0;
        }
        
        var alicuota=parseFloat(document.getElementById("var_alicuota").value);
        if (isNaN(alicuota)==true) {
            alicuota=0;
        }

        var iva=parseFloat(document.getElementById("var_iva").value);
        if (isNaN(alicuota)==true) {
            alicuota=0;
        }

        var total_alicuota=Math.round(precio*alicuota)/100; //aqui es donde se ase el calculo de alicuota
        var total_iva=Math.round(precio*iva)/100; //aqui es donde se ase el calculo de alicuota

        document.getElementById("alicuota").value=total_alicuota.toFixed(2);
        document.getElementById("iva").value="0.00";

        var precio_venta=parseFloat(precio+total_alicuota);
        var precio_venta=Math.round(precio_venta*100)/100 ; 
        document.getElementById("precio_venta").value=precio_venta.toFixed(2);
    }else if(excento=="0"){
        var precio=parseFloat(document.getElementById("costo_unitario").value);
        if (isNaN(precio)==true) {
            precio=0;
        }
        
        var alicuota=parseFloat(document.getElementById("var_alicuota").value);
        if (isNaN(alicuota)==true) {
            alicuota=0;
        }

        var iva=parseFloat(document.getElementById("var_iva").value);
        if (isNaN(alicuota)==true) {
            alicuota=0;
        }

        var total_alicuota=Math.round(precio*alicuota)/100; //aqui es donde se ase el calculo de alicuota
        var total_iva=Math.round(precio*iva)/100; //aqui es donde se ase el calculo de alicuota

        document.getElementById("alicuota").value=total_alicuota.toFixed(2);
        document.getElementById("iva").value=total_iva.toFixed(2);

        var precio_venta=parseFloat(precio+total_alicuota+total_iva);
        var precio_venta=Math.round(precio_venta*100)/100 ; 
        document.getElementById("precio_venta").value=precio_venta.toFixed(2);
    }
}

//-- INICIO EXCENTO IVA
function excento_iva()
{

}

//-- INICIO MODULO FACTURACION
function validarconcepto() 
{
    var mensaje="";
    var entero=0;
    var cant=0;

    if (document.getElementById("codigo_concepto").value=="") mensaje="  - Codigo del Concepto\n";
    if (document.getElementById("descripcion_concepto").value=="") mensaje+="  - Descripcion\n del Concepto";
    entero=parseInt(document.getElementById("cantidad").value);
    cant=parseFloat(document.getElementById("cantidad").value);
    
    if (document.getElementById("costo_unitario").value=="") { 
       mensaje+="  - Falta el precio\n"; 
    } else {
        if (isNaN(document.getElementById("costo_unitario").value)==true) {
            mensaje+="  - El precio debe ser numerico\n";
        }
    }
    if (document.getElementById("cantidad").value==""){ 
        mensaje+="  - Falta la cantidad\n";
    } else {
        if (isNaN(entero)==true) {
            mensaje+="  - La cantidad debe ser numerica\n";
        }
    }
    if (cant < 1){
        mensaje+="  - La cantidad debe ser diferente de 0(Cero)\n";
    }

    if (document.getElementById("total").value=="") mensaje+="  - Falta el total\n";

    if (mensaje!="") {
        alert("Atención, se han detectado los siguientes Errores:\n\n"+mensaje);
    } else {
        cambio_iva();
        document.getElementById("formulario_lineas").submit();
        document.getElementById("id_parametro").value="";
        document.getElementById("parametro").value="";
        document.getElementById("costo_unitario").value="";
        document.getElementById("cantidad").value=1;
        document.getElementById("total").value="";
    }
}

function validar_cabecera(){
    
    var mensaje="";
    var total_aporte=0;
    var total_pago=0;
    if (document.getElementById("cedula_solicitante").value=="") mensaje+="  - Cedula/Rif\n";
    if (document.getElementById("nombre_solicitante").value=="") mensaje+="  - Nombre Solicitante\n";
    if (document.getElementById("preciototal").value==0.00) mensaje+="  - Debe Registrar un Concepto\n";
    total_aporte=parseFloat(document.getElementById("preciototal").value);
    total_pago=parseFloat(document.getElementById("total_pago").value);
    if (total_pago == 0){
        mensaje+="  - Debe Registrar la Forma de Pago\n";
    }
    if (total_pago < total_aporte){
        mensaje+="  - El total de pago es menor al Total Facturado\n  - Verificar Forma de Pago\n";
    }
    if (mensaje!="") {
        alert("Atención, se han detectado los siguientes Errores:\n\n"+mensaje);
    } else {
        document.getElementById("facturacion").submit();
    }
}
function validar_cabecera_producto(){
    document.QForm.monto_total.value=document.formulario_lineas.preciototal.value;
    document.getElementById("QForm").submit();
}

function validar_form_enviado() {
    var cuenta=0;
    if (cuenta == 0){
        cuenta++;
        return true;
    }else{
        alert("Formulario ya enviado");
        return false;
    }
}

function activar_forma_pago_efectivo(){
	if(document.QForm.status_fp_efectivo.checked==true){
            if(document.QForm.status_fp_deposito.checked==false){
                Effect.Fade('fp_pago_deposito');
            }
            if( document.QForm.status_fp_cheque.checked==false){
               Effect.Fade('fp_pago_cheque');
            }
            Effect.Appear('fp_pago_efectivo');
            actualizar_total_pago();
	}else{
            document.QForm.monto_efectivo.value="";
            Effect.Fade('fp_pago_efectivo');
            actualizar_total_pago();
	}
}

function activar_forma_pago_deposito(){
	if(document.QForm.status_fp_deposito.checked==true){            
            if(document.QForm.status_fp_efectivo.checked==false){
                Effect.Fade('fp_pago_efectivo');
            }
            if( document.QForm.status_fp_cheque.checked==false){
               Effect.Fade('fp_pago_cheque');
            }
            Effect.Appear('fp_pago_deposito');
            actualizar_total_pago();
	}else{
            document.QForm.nro_deposito.value="";
            document.QForm.fecha_deposito.value="";
            document.QForm.monto_deposito.value="";
            document.QForm.cod_banco.selectedIndex=0;
            document.QForm.cod_cuenta_banco.selectedIndex=0;
            Effect.Fade('fp_pago_deposito');
            actualizar_total_pago();
	}
}


function activar_forma_pago_cheque(){
	if(document.QForm.status_fp_cheque.checked==true){            
            if(document.QForm.status_fp_efectivo.checked==false){
                Effect.Fade('fp_pago_efectivo');
            }
            if( document.QForm.status_fp_deposito.checked==false){
               Effect.Fade('fp_pago_deposito');
            }
            Effect.Appear('fp_pago_cheque');
            actualizar_total_pago();
	}else{
            document.QForm.cod_banco_cheque.selectedIndex=0;
            document.QForm.nro_cheque.value="";
            document.QForm.monto_cheque.value="";
            Effect.Fade('fp_pago_cheque');
            actualizar_total_pago();
	}
}


function actualizar_total_pago(){
    var v_monto_total, v_monto_efectivo, v_monto_deposito, v_monto_cheque, v_monto_pago, v_total_cambio;
    if (document.QForm.monto_total.value==""){
        v_monto_total =0
    }else{
        v_monto_total = parseFloat(document.QForm.monto_total.value);
    }
    if (document.QForm.monto_efectivo.value==""){
        v_monto_efectivo =0
    }else{
        v_monto_efectivo = parseFloat(document.QForm.monto_efectivo.value);
    }
    if (document.QForm.monto_deposito.value==""){
        v_monto_deposito =0
    }else{
        v_monto_deposito = parseFloat(document.QForm.monto_deposito.value);
    }
    if (document.QForm.monto_cheque.value==""){
        v_monto_cheque =0
    }else{
        v_monto_cheque = parseFloat(document.QForm.monto_cheque.value);
    }
    
    v_monto_pago=v_monto_efectivo+v_monto_deposito+v_monto_cheque;
    
    if (v_monto_pago==0){
        document.QForm.total_pago.value=0.00.toFixed(2);
    }else{	
        document.QForm.total_pago.value=v_monto_pago.toFixed(2);
        if (v_monto_pago>v_monto_total){
            v_total_cambio=v_monto_pago-v_monto_total;
            document.QForm.total_cambio.value=v_total_cambio.toFixed(2);
        }else{
            document.QForm.total_cambio.value=0.00.toFixed(2);
        }
    }    
}

function cargarContenidoCuentasBancoDep(){
	var d1,d2,contenedor;
	contenedor = document.getElementById('contenedor_cuenta_banco');
	d1 = document.QForm.cod_banco.options[document.QForm.cod_banco.selectedIndex].value;
	d2=location.href;
	ajax=objetoAjax();
	ajax.open("GET", "../../procesos.php?accion=bancos&cod_banco="+d1+"&dir="+d2,true);
	contenedor.innerHTML ='<div><img src="../images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

// -- FIN MODULO FACTURACION


//--------- llenar combos de municipios y parroquias----------//						
function cargarContenidoMunicipio(){
    var d1,contenedor;
    contenedor = document.getElementById('contenedor2');
    d1 = document.QForm.codest.options[document.QForm.codest.selectedIndex].value;
    d2=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=municipios&codest="+d1+"&dir="+d2,true);
    contenedor.innerHTML ='<div><img src="../images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
            cargarContenidoParroquia();
        }
    }
    ajax.send(null)
}		
		
function cargarContenidoParroquia(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('contenedor3');
    d1 = document.QForm.codmun.options[document.QForm.codmun.selectedIndex].value;
    d2 = document.QForm.codest.value;
    d3=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=parroquias&codmun="+d1+"&codestado="+d2+"&dir="+d3,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
            cargarContenidoComunidad();
        }
    }
    ajax.send(null)
}

function cargarContenidoComunidad(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('contenedor4');
    d1 = document.QForm.codpar.options[document.QForm.codpar.selectedIndex].value;
    d2 = document.QForm.codest.value;
    d3 = document.QForm.codmun.value;
    d4=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=comunidades&codpar="+d1+"&codest="+d2+"&codmun="+d3+"&dir="+d4,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null)
}

//--------- llenar combos de municipios y parroquias Catalogos----------//						
function cargarContenidoMunicipioCat(){
    var d1,contenedor;
    contenedor = document.getElementById('contenedor2');
    d1 = document.QForm.codest.options[document.QForm.codest.selectedIndex].value;
    d2=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "../../procesos.php?accion=municipios_cat&codest="+d1+"&dir="+d2,true);
    contenedor.innerHTML ='<div><img src="../images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
            cargarContenidoParroquiaCat();
        }
    }
    ajax.send(null)
}		
		
function cargarContenidoParroquiaCat(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('contenedor3');
    d1 = document.QForm.codmun.options[document.QForm.codmun.selectedIndex].value;
    d2 = document.QForm.codest.value;
    d3=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "../../procesos.php?accion=parroquias_cat&codmun="+d1+"&codestado="+d2+"&dir="+d3,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
            cargarContenidoComunidadCat();
        }
    }
    ajax.send(null)
}

function cargarContenidoComunidadCat(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('contenedor4');
    d1 = document.QForm.codpar.options[document.QForm.codpar.selectedIndex].value;
    d2 = document.QForm.codest.value;
    d3 = document.QForm.codmun.value;
    d4=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "../../procesos.php?accion=comunidades_cat&codpar="+d1+"&codest="+d2+"&codmun="+d3+"&dir="+d4,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null)
}

function cargarContenidoTramite(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('tramites');
    d1 = document.QForm.cod_categoria.options[document.QForm.cod_categoria.selectedIndex].value;
    d2=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=tramites&cod_categoria="+d1+"&dir="+d2,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
            cargarContenidoTramiteUnidadSol();
        
        }
    }
    ajax.send(null)
}


function cargarContenidoUnidadTramite(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('unidades');
    d1 = document.QForm.cod_categoria.options[document.QForm.cod_categoria.selectedIndex].value;
    d2=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=unidades_tramites&cod_categoria="+d1+"&dir="+d2,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
            cargarContenidoTramiteUnidadCategoria();
        
        }
    }
    ajax.send(null)
}

function cargarContenidoTramiteUnidadSol(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('unidades');
    d1 = document.QForm.cod_categoria.options[document.QForm.cod_categoria.selectedIndex].value;
    d2 = document.QForm.cod_tramite.options[document.QForm.cod_tramite.selectedIndex].value;
    d3=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=tramites_categorias_unidades&cod_categoria="+d1+"&cod_tramite="+d2+"&dir="+d3,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
            
        }
    }
    ajax.send(null)
}

function cargarContenidoTramiteUnidadCategoria(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('tramites');
    d1 = document.QForm.cod_categoria.options[document.QForm.cod_categoria.selectedIndex].value;
    d2 = document.QForm.cod_unidad.options[document.QForm.cod_unidad.selectedIndex].value;
    d3=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=tramites_unidades_categorias&cod_unidad="+d2+"&cod_categoria="+d1+"&dir="+d3,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null)
}

function cargarContenidoTramiteUnidad(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('tramites');
    d1 = document.QForm.cod_unidad.options[document.QForm.cod_unidad.selectedIndex].value;
    d2 = document.QForm.cedula_rif.value;
    d3=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=tramites_unidades&cod_unidad="+d1+"&ced="+d2+"&dir="+d3,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null)
}
function cargarContenidoVehiculo(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('vehiculos');
    d1 = document.QForm.cedula_rif.value;
    d2=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "procesos.php?accion=vehiculos&ced="+d1+"&dir="+d2,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null)
}
function cargarContenidoMarcaVehiculo(){
    var d1,d2,d3,contenedor;
    contenedor = document.getElementById('marca_vehiculo');
    d2=location.href;
    ajax=objetoAjax();
    ajax.open("GET", "../procesos.php?accion=marca_vehiculo&dir="+d2,true);
    contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null)
}

//ACTIVANDO FOCUS DE INPUT
function setFocusLogin(){
    document.loginForm.user.select();
    document.loginForm.user.focus();
}
function setFocusUser(){
    document.QForm.cedula.select();
    document.QForm.cedula.focus();
}
function setFocusCedulaRif(){
    document.QForm.cedula_rif.select();
    document.QForm.cedula_rif.focus();
}
function setFocusCodTicket(){
    document.QForm.cod_ticket.select();
    document.QForm.cod_ticket.focus();
}
function setFocusUserName(){
    document.QForm.nombreapellido.select();
    document.QForm.nombreapellido.focus();
}


// funciones de la ayuda //
if(navigator.userAgent.indexOf("MSIE")>=0) navegador=0;
else navegador=1;

function colocaAyuda(event){
    if(navegador==0)	{
        var corX=window.event.clientX+document.documentElement.scrollLeft;
        var corY=window.event.clientY+document.documentElement.scrollTop;
    }else{
        var corX=event.clientX+window.scrollX;
        var corY=event.clientY+window.scrollY;
    }
    cAyuda.style.top=corY+20+"px";
    cAyuda.style.left=corX+15+"px";
}

function ocultaAyuda(){
    cAyuda.style.display="none";
    if(navegador==0){
        document.detachEvent("onmousemove", colocaAyuda);
        document.detachEvent("onmouseout", ocultaAyuda);
    }else{
        document.removeEventListener("mousemove", colocaAyuda, true);
        document.removeEventListener("mouseout", ocultaAyuda, true);
    }
}

function muestraAyuda(event, campo, mensaje, oblig){
    cAyuda=document.getElementById("mensajesAyuda");
    cNombre=document.getElementById("ayudaTitulo");
    cTex=document.getElementById("ayudaTexto");
    colocaAyuda(event);
    if(navegador==0){ 
        document.attachEvent("onmousemove", colocaAyuda); 
        document.attachEvent("onmouseout", ocultaAyuda); 
    }else{
        document.addEventListener("mousemove", colocaAyuda, true);
        document.addEventListener("mouseout", ocultaAyuda, true);
    }
    var o = (oblig==null)?('<br>'):(oblig);
    cNombre.innerHTML = campo+o;
    cTex.width = 'auto';
    cTex.innerHTML = msj = (mensaje==null)?('<br>'):(mensaje);
    cAyuda.style.display="block";
}

function ventanavehiculos() {
    rifci  = facturacion.rifci.value;    
    if (rifci ==""){
        alert("Debe Identificar a la Persona Antes de Agregar un Analisis !!!");
        parent.window.close();
    }
    else {
        miPopup = window.open("presupuestos/catalogo_personas.php","miwin","width=1100,height=500,scrollbars=yes");
        miPopup.focus();
    }
}

function validarCodigo() {
       if ((isNaN(document.QForm.codigo.value)) || (document.QForm.codigo.value == '')) {
            alert('Debe introducir un valor.');
            document.QForm.codigo.focus();
       } else {
            cargarContenidoAsignacion();
       }
}

//--------- llenar combo de Eventos Preinscritos del Participante----------//						
function cargarContenidoAsignacion(){
	var d1,d2,contenedor;
	contenedor = document.getElementById('contenedor_informacion');
	d1 = document.QForm.codigo.value;
	d2=location.href;
	ajax=objetoAjax();
	ajax.open("GET", "procesos.php?accion=validarcodigo&codigo="+d1+"&dir="+d2,true);
        contenedor.innerHTML ='<div><img src="images/loading.gif" width="16" height="16" alt="Cargando..." title="Cargando..." /> Cargando</div>';
	var msj_x = document.getElementById("mensaje_espera");
	var msj_y = document.getElementById("mensaje_espera_texto");
	msj_x.style.visibility = 'visible';
	msj_y.style.visibility = 'visible';
	
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
			var msj_x = document.getElementById("mensaje_espera");
			var msj_y = document.getElementById("mensaje_espera_texto");
			msj_x.style.visibility = 'hidden';
			msj_y.style.visibility = 'hidden';		
			contenedor.innerHTML = ajax.responseText
                        setTimeout ("ver_msgload()", 8000); //tiempo de espera en milisegundos
		}
	}
	ajax.send(null)
}
function ver_msgload(){
    Effect.Fade('msgload');
}








					


		
