<?php

return [
    'settings' => 'Ajustes',
    'name' => 'Comercio electrónico',
    'phone' => 'Teléfono',
    'email' => 'Correo electrónico',
    'country' => 'País',
    'state' => 'Estado o Provincia',
    'city' => 'Ciudad',
    'address' => 'Dirección',
    'company' => 'Compañía',
    'tax_id' => 'Identificación del impuesto',
    'store_address' => 'Dirección de la tienda',
    'store_phone' => 'Teléfono de la tienda',
    'order_id' => 'ID Orden',
    'order_token' => 'Token de pedido',
    'customer_name' => 'Nombre',
    'customer_email' => 'Correo electrónico del cliente',
    'customer_phone' => 'Teléfono del cliente',
    'customer_address' => 'Dirección',
    'product_list' => 'Productos de la orden',
    'order_note' => 'Nota del pedido',
    'payment_detail' => 'Detalles de pago',
    'shipping_method' => 'Método de envío',
    'payment_method' => 'Método de pago',
    'select_state' => 'Seleccione estado...',
    'select_city' => 'Ciudad selecta...',
    'theme_options' => [
        'name' => 'Comercio',
        'description' => 'Opciones de diseño para comercio electrónico',
        'number_products_per_page' => 'Número de productos por página',
        'max_price_filter' => 'Precio máximo para filtrar',
        'logo_in_invoices' => 'Logotipo en facturas (el predeterminado es el logotipo principal)',
        'logo_in_the_checkout_page' => 'Logotipo en la página de pago (el predeterminado es el logotipo principal)',
        'number_of_cross_sale_product' => 'Número de productos de venta cruzada en la página de detalles del producto',
        'page_slugs' => [
            'customer_address' => 'Dirección del cliente',
            'login' => 'Acceso',
            'register' => 'Registro',
            'reset_password' => 'Restablecer la contraseña',
            'product_listing' => 'Listado de productos',
            'cart' => 'Carro',
            'checkout' => 'Verificar',
            'order_tracking' => 'Rastreo de orden',
            'wishlist' => 'Lista de deseos',
            'compare' => 'Comparar',
            'customer_overview' => 'Resumen del cliente',
            'customer_change_password' => 'Cliente cambiar contraseña',
            'customer_downloads' => 'Descargas de clientes',
            'customer_edit_account' => 'Cuenta de edición del cliente',
            'customer_order_returns' => 'Devoluciones de pedidos de clientes',
            'customer_orders' => 'Pedidos de los clientes',
            'customer_product_reviews' => 'Reseñas de productos de clientes',
        ],
        'slug_name' => 'URL de comercio electrónico',
        'slug_description' => 'Personalice los slugs utilizados para las páginas de comercio electrónico. Tenga cuidado al modificar, ya que puede afectar el SEO y la experiencia del usuario. Si algo sale mal, puede restablecer los valores predeterminados escribiendo el valor predeterminado o dejándolo en blanco.',
        'page_slug_name' => ':page page slug',
        'page_slug_description' => 'It will look like :slug when you access the page. Default value is :default.',
        'page_slug_already_exists' => 'The :slug page slug is already in use. Please choose another one.',
        'login_background_image' => 'Imagen de fondo de inicio de sesión',
        'register_background_image' => 'Registrar imagen de fondo',
        'term_and_privacy_policy_url' => 'URL de términos y política de privacidad',
    ],
    'basic_settings' => 'Ajustes básicos',
    'general_settings' => 'General',
    'general_setting_description' => 'Ver y actualizar su configuración general',
    'advanced_settings' => 'Ajustes avanzados',
    'product_review_list' => 'Lista de revisión de productos',
    'forms' => [
        'duplicate' => 'Duplicar',
        'duplicate_success_message' => '¡Producto duplicado exitosamente!',
    ],
    'duplicate_modal' => 'Duplicar producto',
    'duplicate_modal_description' => '¿Está seguro de que desea duplicar este producto?',
    'wishlist' => 'Lista de deseos',
    'product' => 'Producto',
    'sort_order' => 'Orden de clasificación',
    'order_link' => 'Enlace de detalles del pedido',
    'payment_link' => 'Enlace de detalles de pago',
    'or' => 'o',
    'update' => 'Actualizar',
    'download_link' => 'Enlace de descarga',
    'update_time' => 'Hora de actualizar',
    'product_files' => 'Archivos de producto',
    'setting' => [
        'email' => [
            'title' => 'Comercio electrónico',
            'description' => 'Configuración de correo',
            'order_confirm_subject' => 'Asunto del correo electrónico de confirmación del pedido',
            'order_confirm_subject_placeholder' => 'El asunto del correo electrónico de confirmación enviado al cliente.',
            'order_confirm_content' => 'Contenido del correo electrónico de confirmación del pedido',
            'order_status_change_subject' => 'Asunto del correo electrónico al cambiar el estado del pedido',
            'order_status_change_subject_placeholder' => 'Asunto del correo electrónico al cambiar el estado del pedido enviado al cliente',
            'order_status_change_content' => 'Contenido del correo electrónico al cambiar el estado del pedido',
            'from_email' => 'Email de',
            'from_email_placeholder' => 'Correo electrónico para mostrar, ejemplo: contacto@nombredelatienda.com',
        ],
        'title' => 'Información básica',
        'state' => 'Estado',
        'city' => 'Ciudad',
        'country' => 'País',
        'select country' => 'Seleccione país...',
        'weight_unit_gram' => 'Gramos (g)',
        'weight_unit_kilogram' => 'Kilogramos (kg)',
        'height_unit_cm' => 'Centímetros (cm)',
        'height_unit_m' => 'Metros (m)',
        'store_locator_title' => 'Localización de tiendas',
        'store_locator_description' => 'Todas las listas de sus cadenas, tiendas principales, sucursales, etc. Las ubicaciones se pueden utilizar para realizar un seguimiento de las ventas y ayudarnos a configurar las tasas de impuestos a cobrar al vender productos.',
        'phone' => 'Teléfono',
        'address' => 'Dirección',
        'is_primary' => 'Primario?',
        'add_new' => 'Agregar',
        'or' => 'O',
        'change_primary_store' => 'Localiza la tienda',
        'other_settings' => 'Otros ajustes',
        'other_settings_description' => 'Configuración para carrito, revisión ...',
        'enable_cart' => '¿Habilitar carrito de compras?',
        'enable_tax' => '¿Habilitar impuestos?',
        'display_product_price_including_taxes' => '¿Mostrar el precio del producto con impuestos incluidos?',
        'enable_review' => '¿Habilitar respuestas de compra?',
        'enable_quick_buy_button' => '¿Habilitar el botón de compra rápida?',
        'quick_buy_target' => 'Activar compra rápida',
        'checkout_page' => 'Pagina de pago',
        'cart_page' => 'Página del carrito',
        'add_location' => 'Añadir lugar',
        'edit_location' => 'Editar ubicación',
        'delete_location' => 'Eliminar ubicación',
        'change_primary_location' => 'Cambiar la ubicación principal',
        'delete_location_confirmation' => '¿Está seguro de que desea eliminar esta ubicación? Esta acción no se puede deshacer.',
        'save_location' => 'Guardar dirección',
        'accept' => 'Aceptar',
        'select_country' => 'Seleccione un país...',
        'zip_code_enabled' => '¿Habilitar código postal?',
        'thousands_separator' => 'Separadora de miles',
        'decimal_separator' => 'Separador decimal',
        'separator_period' => 'Periodo (.)',
        'separator_comma' => 'Coma (,)',
        'separator_space' => 'Espacio ( )',
        'available_countries' => 'Países disponibles',
        'all' => 'Todo',
        'verify_customer_email' => '¿Verificar el correo electrónico del cliente?',
        'enable_guest_checkout' => '¿Habilitar el pago de invitado?',
        'height_unit_inch' => 'Pulgada',
        'minimum_order_amount' => 'Cantidad mínima para realizar un pedido :currency',
        'weight_unit_lb' => 'Libra (lb)',
        'weight_unit_oz' => 'Onza (oz)',
    ],
    'standard_and_format' => 'Formato estandar',
    'standard_and_format_description' => 'Los estándares y formatos se utilizan para calcular cosas como precios de productos, pesos de envío y tiempos de pedido.',
    'change_order_format' => 'Editar formato del código de pedido (opcional)',
    'change_order_format_description' => 'El código de pedido predeterminado comienza en :number. Puede cambiar la cadena inicial o final para crear el código de pedido que desee, por ejemplo: "DH-: numero" o ": numero-A"',
    'start_with' => 'Empezar con',
    'end_with' => 'Terminar con',
    'order_will_be_shown' => 'Se mostrará su código de pedido',
    'weight_unit' => 'Unidad de peso',
    'height_unit' => 'Unidad de longitud / altura',
];