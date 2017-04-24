<?php defined('BASEPATH') OR exit('No direct script access allowed');


/* Site configuration */
$lang['show_nav']                                                   = "Desplegar navegación";
$lang['site_name']                                                  = "Nombre del Sitio";
$lang['default_currency']                                           = "Moneda Por Omisión";
$lang['accounting_method']                                          = "Método de Costeo";
$lang['default_email']                                              = "Correo Por Omisión";
$lang['default_customer_group']                                     = "Grupo de Clientes Por Omisión";
$lang['maintenance_mode']                                           = "Modo Mantenimiento";
$lang['theme']                                                      = "Tema";
$lang['login_captcha']                                              = "Captcha en Login";
$lang['rows_per_page']                                              = "Filas por Página";
$lang['dateformat']                                                 = "Formato de Fecha";
$lang['timezone']                                                   = "Zona horaria";
$lang['reg_ver']                                                    = "Verificación del Registro";
$lang['allow_reg']                                                  = "Permitir Registro";
$lang['reg_notification']                                           = "Notificación del Registro";
$lang['calendar']                                                   = "Calendario";
$lang['private']                                                    = "Privado";
$lang['shared']                                                     = "Compartido";
$lang['default_warehouse']                                          = "Almacén Por Omisión";
$lang['restrict_user']                                              = "Restringir Usuario";


/* Header */
$lang['my_orders'] = "Mis Ordenes";
$lang['new_notifications'] = "¡Tienes nuevos mensajes!";
$lang['see_notifications'] = "Ver Todas";
$lang['back'] = "Volver";
$lang['search_table'] = "Buscar mesa";



/* Date */
$lang['today_title'] = "Hoy es";

$lang['days'] =  array(
    0 => "Domingo",
    1 => "Lunes",
    2 => "Martes",
    3 => "Miercoles",
    4 => "Jueves",
    5 => "Viernes",
    6 => "Sabado"
);

$lang['months'] =  array(
    0 => "Enero",
    1 => "Febrero",
    2 => "Marzo",
    3 => "Abril",
    4 => "Mayo",
    5 => "Junio",
    6 => "Julio",
    7 => "Agosto",
    8 => "Septiembre",
    9 => "Octubre",
    10 => "Noviembre",
    11 => "Diciembre"    
);

/*Navigation Buttons */
$lang['sales'] = "Ventas";
$lang['stock'] = "Stock";
$lang['customers'] = "Clientes";
$lang['guests'] = "Personas";
$lang['pagos'] = "Pagos";
$lang['kitchen'] = "Cocina";
$lang['drinks'] = "Bebidas";
$lang['admin'] = "Administracion";
$lang['notifications'] = "Notificaciones";

/* Waiter */
$lang['waiter']  = "Mesero";
$lang['table']  = "Mesa";
$lang['tables']  = "Mesas";
$lang['orders']  = "Ordenes";
$lang['table_free']  = "Libre";
$lang['table_busy']  = "Ocupada";
$lang['table_awating']  = "En Espera";
$lang['unavailable']  = "Agotado";
$lang['product-unavailable']  = "No Disponible";
$lang['view-product-unavailable']  = "Producto no disponible";
$lang['change_table']  = "Cambiar Mesa";

/* Chef */
$lang['chef']  = "Cocinero";
$lang['chef_message']  = "El pedido con el producto <b>%s</b> con una cantidad de <b>%u</b> paso los <b>%u minutos</b>";

/* Chef - Barman */
$lang['chef_barman_quantity'] = "Cantidad";
$lang['chef_barman_table'] = "Mesa";
$lang['chef_barman_product'] = "Producto";
$lang['chef_barman_order_date'] = "Hora de Pedido";
$lang['chef_barman_order_dispatched'] = "Hora de Despacho";
$lang['chef_barman_minutes'] = "Tiempo transcurrido";
$lang['chef_barman_state'] = "Estado";
$lang['chef_barman_waiter'] = "Mozo";
$lang['chef_barman_hold'] = "En espera...";
$lang['chef_barman_ready'] = "Listo";

/* Barman */
$lang['barman']  = "Barman";

/* General */
$lang['customer'] = "Cliente";
$lang['select_item'] = "Selecciona un ítem";
$lang['book_table'] = "Reservar Mesa";
$lang['comments'] = "Comentarios";
$lang['guests'] = "Personas";
$lang['add_comment'] = "Agrega un comentario aquí...";
$lang['confirm'] = "Confirmar";
$lang['cancel'] = "Cancelar";
$lang['total'] = "Total";
$lang['total_pay'] = "Total a pagar";
$lang['free_table'] = "Liberar Mesa";
$lang['request_bill'] = "Solicitar Cuenta";
$lang['no_table'] = "Ninguna";
$lang['add_customer'] = "Agregar Cliente";
$lang['load_customer'] = "Seleccionar Cliente";
$lang['new_customer'] = "Crear Nuevo";
$lang['variants'] = "Variaciones";
$lang['change_waiter'] = "Cambiar Mesero";
$lang['dispatched_items_list'] = "Lista de productos despachados";
$lang['chef_barman_dispatched'] = "Despachado";
$lang['receivable'] = "Por cobrar";
$lang['paid'] = "Cobrado";
$lang['table'] = "Mesa";
$lang['tip'] = "Propina";

/* Page Titles */
$lang['title_new_order']  = "Crear orden";
$lang['title_edit_order'] = "Editar orden";
$lang['title_view_category'] = "Lista de productos";
$lang['title_view_product'] = "Ver Producto";

/* Pages Sub-titles */
$lang['subtitle_categories'] = 'Categorias de Productos';

/* Scopes */
$lang['scope_order_create'] = "La cantidad de personas es obligatoria porque permite luego obtener el ticket promedio. Siempre conviene completar este campo con un dato verdadero. Puede usar las flechas del teclado y luego ENTER para agilizar la creación de la venta.";
$lang['scope_item_advice'] = "Producto que ha sido agregado por admin o cajero";

/* Orders */
$lang['order_items'] = "Productos de la Orden";
$lang['products_search'] = "Buscar productos";

/* Notifications */
$lang['notifications'] = "Notificaciones";
$lang['subject'] = "Asunto";
$lang['date'] = "Fecha";
$lang['message_notify'] = "El producto %product% ha sido confirmado por el cocinero en la mesa %table%";

$lang['minutes_res'] = "Minutos";
$lang['minute'] = "Minuto";

$lang['title_bill'] = "Detalles de su orden, esto no es una factura, la factura sera generada posteriormente.<br> Favor exigirla";