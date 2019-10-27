<?php
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    
    switch ($view){
        // SECCION ENTRADA
        CASE "inicio": 
            include("inicio.php");
            break;
        
        // SECCION DE DATOS DE LA EMPRESA Y DE CONFIGURACION
        CASE "empresa":
            include ("empresa/aut_gestion_empresa.php");
            break;
        CASE "empresa_add":
            include ("empresa/empresa_add.php");
            break;
        CASE "empresa_update":
            include ("empresa/empresa_update.php");
            break;
        CASE "empresa_drop":
            include ("empresa/empresa_drop.php");
            break;
        
        // SECCION DE DATOS DE LA MARCA VEHICULOS
        CASE "marcas":
            include ("marcas/aut_gestion_marcas.php");
            break;
        CASE "marca_add":
            include ("marcas/marca_add.php");
            break;
        CASE "marca_update":
            include ("marcas/marca_update.php");
            break;
        CASE "marca_drop":
            include ("marcas/marca_drop.php");
            break;
        
        ///////////// COOPERATIVAS TRANSPORTE /////////////////		
        case "cooperativas_transporte": 
            include("cooperativa_transporte/aut_gestion_cooperativa_transporte.php");
            break;
        case "cooperativas_transporte_add":
            include("cooperativa_transporte/cooperativa_transporte_add.php");
            break;
        case "cooperativas_transporte_update": 
            include("cooperativa_transporte/cooperativa_transporte_update.php");
            break;
        case "cooperativas_transporte_drop": 
            include("cooperativa_transporte/cooperativa_transporte_drop.php");
            break;
	case "cooperativas_transporte_status": 
            include("cooperativa_transporte/cooperativa_transporte_status.php");
            break;
        
        // SECCION DE DATOS DE LA MARCA CONCEPTOS
        CASE "marcas_conceptos":
            include ("facturacion/marcas_conceptos/aut_gestion_marcas.php");
            break;
        CASE "marca_concepto_add":
            include ("facturacion/marcas_conceptos/marca_add.php");
            break;
        CASE "marca_concepto_update":
            include ("facturacion/marcas_conceptos/marca_update.php");
            break;
        CASE "marca_concepto_drop":
            include ("facturacion/marcas_conceptos/marca_drop.php");
            break;

        // SECCION DEL MANTENIMIENTO DEL SISTEMA
        case "solicitante_mantenimiento": 
            include("solicitantes/solicitante_mantenimiento.php");
            break;
        case "solicitante_mantenimiento_phone": 
            include("solicitantes/solicitante_mantenimiento_phone_fijo.php");
            break;
        
        // SECCION DEL SISTEMA
        case "home": 
            include("view_panel.php");
            break;
        case "login": 
            include("autentificacion.php");
            break;			
        case "logoff": 
            include("aut_logoff.php");
            break;
        
        // GESTION DE REPORTES
        case "gestion_reporte_ticket_fecha": 
            include("gestion_reportes/gestion_ticket_fecha.php");
            break;
        case "gestion_reporte_est_fecha": 
            include("gestion_reportes/gestion_estadistico_fecha.php");
            break;
        case "gestion_reporte_est_year": 
            include("gestion_reportes/gestion_estadistico_year.php");
            break;
        case "gestion_reporte_est_unidad_fecha": 
            include("gestion_reportes/gestion_estadistico_unidad_fecha.php");
            break;
        case "gestion_reporte_est_unidad_year": 
            include("gestion_reportes/gestion_estadistico_unidad_year.php");
            break;
        

        ///////////// USUARIOS /////////////////		
        case "usuarios": 
            include("usuarios/aut_gestion_usuario.php");
            break;
        case "usuarios_add": 
            include("usuarios/usuarios_add.php");
            break;
        case "usuarios_update": 
            include("usuarios/usuarios_update.php");
            break;
        case "usuarios_update_clave": 
            include("usuarios/usuarios_update_clave.php");
            break;	
        case "usuarios_update_nivel": 
            include("usuarios/usuarios_update_nivel.php");
            break;
        case "usuarios_drop": 
            include("usuarios/usuarios_drop.php");
            break;
        case "usuarios_unlock": 
            include("usuarios/usuarios_unlock.php");
            break;
        case "usuarios_update_perfil": 
            include("usuarios/usuarios_update_perfil.php");
            break;
        case "usuarios_update_perfil_clave": 
            include("usuarios/usuarios_update_perfil_clave.php");
            break;
        
        ///////////// SUSCRIBE /////////////////
        case "suscribe": 
            include("suscribe/usuario_add.php");
            break;

        ///////////// CATEGORIAS /////////////////		
        case "categorias": 
            include("categorias/aut_gestion_categorias.php");
            break;
        case "categoria_add": 
            include("categorias/categoria_add.php");
            break;
        case "categoria_update": 
            include("categorias/categoria_update.php");
            break;
        case "categoria_drop": 
            include("categorias/categoria_drop.php");
            break;
	 case "categoria_status": 
            include("categorias/categoria_status.php");
            break;
        
	///////////// COMUNIDADES /////////////////		
        case "comunidades": 
            include("comunidades/aut_gestion_comunidades.php");
            break;
        case "comunidad_add": 
            include("comunidades/comunidad_add.php");
            break;
        case "comunidad_update": 
            include("comunidades/comunidad_update.php");
            break;
        case "comunidad_drop": 
            include("comunidades/comunidad_drop.php");
            break;

	///////////// SOLICITANTES /////////////////		
        case "solicitante_load_view": 
            include("solicitantes/solicitante_load_view.php");
            break;
        case "solicitantes": 
            include("solicitantes/aut_gestion_solicitantes.php");
            break;
        case "solicitante_add": 
            include("solicitantes/solicitante_add.php");
            break;
        case "solicitante_update": 
            include("solicitantes/solicitante_update.php");
            break;
        case "solicitante_drop": 
            include("solicitantes/solicitante_drop.php");
            break;

	///////////// TIPOS DE SOLICITANTES /////////////////		
        case "tipo_solicitantes": 
            include("tipo_solicitantes/aut_gestion_tipo_solicitantes.php");
            break;
        case "tipo_solicitante_add": 
            include("tipo_solicitantes/tipo_solicitante_add.php");
            break;
        case "tipo_solicitante_update": 
            include("tipo_solicitantes/tipo_solicitante_update.php");
            break;
        case "tipo_solicitante_drop": 
            include("tipo_solicitantes/tipo_solicitante_drop.php");
            break;

	///////////// DEPENDENCIAS /////////////////		
        case "unidades": 
            include("unidades/aut_gestion_unidades.php");
            break;
        case "unidad_add": 
            include("unidades/unidad_add.php");
            break;
        case "unidad_update": 
            include("unidades/unidad_update.php");
            break;
        case "unidad_drop": 
            include("unidades/unidad_drop.php");
            break;
        case "unidad_ver": 
            include("unidades/unidad_ver.php");
            break;
	 case "unidad_status": 
            include("unidades/unidad_status.php");
            break;

	///////////// ESTADOS DE TRAMITES /////////////////		
        case "edos_tramites": 
            include("edos_tramites/aut_gestion_edos_tramites.php");
            break;
        case "edo_tramite_add": 
            include("edos_tramites/edo_tramite_add.php");
            break;
        case "edo_tramite_update": 
            include("edos_tramites/edo_tramite_update.php");
            break;
        case "edo_tramite_drop": 
            include("edos_tramites/edo_tramite_drop.php");
            break;

	///////////// TRAMITES /////////////////		
        case "tramites": 
            include("tramites/aut_gestion_tramites.php");
            break;
        case "tramite_add":
            include("tramites/tramite_add.php");
            break;
        case "tramite_update": 
            include("tramites/tramite_update.php");
            break;
        case "tramite_drop": 
            include("tramites/tramite_drop.php");
            break;
	case "tramite_status": 
            include("tramites/tramite_status.php");
            break;
        
	///////////// TICKETS /////////////////		
        case "gestion_tickets_load": 
            include("ticket/aut_gestion_tickets.php");
            break;
        case "tickets": 
            include("ticket/ticket_load_add.php");
            break;
        case "ticket_add": 
            include("ticket/ticket_add.php");
            break;
        case "ticket_update": 
            include("ticket/ticket_update.php");
            break;
        case "gestion_tickets":
            include("ticket/ticket_load_view.php");
            break;
        case "ticket_status_update":
            include("ticket/ticket_status_update.php");
            break;
        case "ticket_status_reprogramar":
            include("ticket/ticket_status_reprogramar.php");
            break;
        case "ticket_status_escalar":
            include("ticket/ticket_status_escalar.php");
            break;
        case "ticket_status_completar":
            include("ticket/ticket_status_completar.php");
            break;
        case "ticket_status_cancelar":
            include("ticket/ticket_status_cancelar.php");
            break;
        case "ticket_status_anular":
            include("ticket/ticket_status_anular.php");
            break;

	///////////// SOLICITUDES /////////////////		
        case "solicitud_online": 
            include("solicitudes/solicitud_online.php");
            break;
	case "solicitud_online_add": 
            include("solicitudes/solicitud_online_add.php");
            break;
	case "consultar_solicitud": 
            include("solicitudes/solicitud_online_view.php");
            break;
        
        ///////////// TICKETS /////////////////		
        case "solicitante_ticket_add": 
            include("ticket/ticket_solicitante_add.php");
            break;

        //INFORMACION Y CREDITOS DEL SISTEMA		
        case "credit": 
            include("creditos.php");
            break;
         case "system_info": 
            include("system_info.php");
            break;
        
        //MODULO DE REGISTRO CIVIL
        case "registro_online": 
            include("registro/partida_online.php");
            break;
        case "verifica_online": 
            include("registro/verificar_online_view.php");
            break;
        case "registro_online2": 
            include("registro/partida_online.php");
            break;
        
        //MODULO DE SMS	        
        case "sms_grupo": 
            include("mensajeria/sms_masivo/sms_masivo_add.php");
            break;
        case "sms_recibidos": 
            include("mensajeria/recibido/aut_gestion_recibido.php");
            break;
        case "sms_por_enviar": 
            include("mensajeria/por_enviar/aut_gestion_por_enviar.php");
            break;
        case "sms_enviados": 
            include("mensajeria/enviados/aut_gestion_enviado.php");
            break;
        case "solicitante_sms":
            include("mensajeria/sms_masivo/solicitante_sms.php");
            break;
        
        //MODULO DE FACTURACION
        case "banco":
            include("facturacion/banco/aut_gestion_banco.php");
            break;
        case "banco_add":
            include("facturacion/banco/banco_add.php");
            break;
        case "banco_drop":
            include("facturacion/banco/banco_drop.php");
            break;
        case "banco_update":
            include("facturacion/banco/banco_update.php");
            break;

        //MODULO DE NOTA DE ENTREGA
        case "nota_entrega":
            include("facturacion/nota_entrega/aut_gestion_nota_entrega.php");
            break;
        case "nota_entrega_add":
            include("facturacion/nota_entrega/nota_entrega_add.php");
            break;
        case "nota_entrega_drop":
            include("facturacion/nota_entrega/nota_entrega_drop.php");
            break;
        case "nota_entrega_update":
            include("facturacion/nota_entrega/nota_entrega_update.php");
            break;
        case "detalle_nota_add":
            include("facturacion/detalle_nota/cargar_concepto_add.php");
            break;
        case "detalle_nota_drop":
            include("facturacion/detalle_nota/cargar_concepto_drop.php");
            break;


        
        //MODULO DE TIPO DE CUENTAS
        case "tipo_cuenta":
            include("facturacion/tipo_cuenta/aut_gestion_tipo_cuenta.php");
            break;
        case "tipo_cuenta_add":
            include("facturacion/tipo_cuenta/tipo_cuenta_add.php");
            break;
        case "tipo_cuenta_drop":
            include("facturacion/tipo_cuenta/tipo_cuenta_drop.php");
            break;
        case "tipo_cuenta_update":
            include("facturacion/tipo_cuenta/tipo_cuenta_update.php");
            break;
        
        //MODULO DE CUENTAS
        case "cuenta":
            include("facturacion/cuenta/aut_gestion_cuenta.php");
            break;
        case "cuenta_add":
            include("facturacion/cuenta/cuenta_add.php");
            break;
        case "cuenta_drop":
            include("facturacion/cuenta/cuenta_drop.php");
            break;
        case "cuenta_update":
            include("facturacion/cuenta/cuenta_update.php");
            break; 

        //MODULO DE CATEGORIA
        case "categoria_concepto":
            include("facturacion/categoria/aut_gestion_categoria.php");
            break;
        case "categoria_concepto_add":
            include("facturacion/categoria/categoria_add.php");
            break;
        case "categoria_concepto_drop":
            include("facturacion/categoria/categoria_drop.php");
            break;
        case "categoria_concepto_update":
            include("facturacion/categoria/categoria_update.php");
            break;   

        //MODULO DE REGISTRO DE ALMACEN
        case "almacen":
            include("facturacion/almacen/aut_gestion_almacen.php");
            break;
        case "almacen_add":
            include("facturacion/almacen/almacen_add.php");
            break;
        case "almacen_drop":
            include("facturacion/almacen/almacen_drop.php");
            break;
        case "almacen_update":
            include("facturacion/almacen/almacen_update.php");
            break; 
        
        //MODULO DE CONCEPTOS
        case "concepto":
            include("facturacion/concepto/aut_gestion_concepto.php");
            break;
        case "concepto_add":
            include("facturacion/concepto/concepto_add.php");
            break;
        case "concepto_drop":
            include("facturacion/concepto/concepto_drop.php");
            break;
        case "concepto_update":
            include("facturacion/concepto/concepto_update.php");
            break;  
        case "concepto_status":
            include("facturacion/concepto/concepto_status.php");
            break;  
        
        //MODULO DE FACTURAS DE COMPRAS VENTAS
        case "factura":
            include("facturacion/factura/aut_gestion_factura.php");
            break;
        case "factura_add":
            include("facturacion/factura/factura_add.php");
            break;
        case "factura_save": 
            include("facturacion/factura/guardar_factura.php");
            break;
        case "factura_anular": 
            include("facturacion/factura/factura_anular.php");
            break;

        case "compra":
            include("facturacion/compra/aut_gestion_compra.php");
            break;
        case "compra_add":
            include("facturacion/compra/compra_add.php");
            break;
        case "compra_drop":
            include("facturacion/compra/compra_drop.php");
            break;
        case "compra_update":
            include("facturacion/compra/compra_update.php");
            break;
        case "detalle_compra_add":
            include("facturacion/detalle_compra/cargar_concepto_add.php");
            break;
        case "detalle_compra_drop":
            include("facturacion/detalle_compra/cargar_concepto_drop.php");
            break;
        
        
        //MODULO DE TIPO DE ACTIVIDAD
        case "tipo_actividad":
            include("productor/tipo/aut_gestion_tipo_actividad.php");
            break;
        case "tipo_actividad_add":
            include("productor/tipo/tipo_actividad_add.php");
            break;
        case "tipo_actividad_drop":
            include("productor/tipo/tipo_actividad_drop.php");
            break;
        case "tipo_actividad_update":
            include("productor/tipo/tipo_actividad_update.php");
            break;  
        
        //MODULO DE RUBRO
        case "rubro":
            include("productor/rubro/aut_gestion_rubro.php");
            break;
        case "rubro_add":
            include("productor/rubro/rubro_add.php");
            break;
        case "rubro_drop":
            include("productor/rubro/rubro_drop.php");
            break;
        case "rubro_update":
            include("productor/rubro/rubro_update.php");
            break;  
        
        //MODULO DE PRODUCTOR
        case "productor":
            include("productor/productor/aut_gestion_productor.php");
            break;
        case "productor_load_view":
            include("productor/productor/productor_load_view.php");
            break;
        case "productor_load_web":
            include("productor/productor/productor_load_web.php");
            break;
        case "productor_add":
            include("productor/productor/productor_add.php");
            break;
        case "productor_add_web":
            include("productor/productor/productor_add_web.php");
            break;
        case "productor_drop":
            include("productor/productor/productor_drop.php");
            break;
        case "productor_update":
            include("productor/productor/productor_update.php");
            break; 
        
        //MODULO DE ACTIVIDADES DEL PRODUCTOR
        case "actividad_productor_add":
            include("productor/actividad/actividad_productor_add.php");
            break; 
        case "actividad_productor_add_web":
            include("productor/actividad/actividad_productor_add_web.php");
            break; 
        case "actividad_drop":
            include("productor/actividad/actividad_drop.php");
            break; 
        
        // POR DEFECTO CUANDO VIEW NO POSEE VALOR SE LLAMA AL FORMULARIO DE AUNTENTICACION
        default: 
            include("inicio.php");
//            include("panel.php");
	
        
    }
?>
