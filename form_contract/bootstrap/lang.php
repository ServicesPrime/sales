<?php
/**
 * ============================================
 * CONTROL DE IDIOMA (INTERNACIONALIZACIÓN)
 * ============================================
 * Maneja el cambio entre inglés y español
 * ============================================
 */

// ============================================
// 1. CAMBIAR IDIOMA SI SE SOLICITA
// ============================================
if (isset($_GET["lang"]) && in_array($_GET["lang"], ['en', 'es'])) {
    $_SESSION["lang"] = $_GET["lang"];
    
    // Log del cambio (opcional, solo en desarrollo)
    if (defined('BASE_PATH')) {
        log_message("Language changed to: {$_GET['lang']}", 'INFO');
    }
}

// ============================================
// 2. ESTABLECER IDIOMA POR DEFECTO
// ============================================
if (!isset($_SESSION["lang"])) {
    // Detectar idioma del navegador
    $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
    $_SESSION["lang"] = in_array($browserLang, ['en', 'es']) ? $browserLang : 'en';
}

$lang = $_SESSION["lang"];

// ============================================
// 3. DICCIONARIO DE TRADUCCIONES
// ============================================
$translations = [
    'en' => [
        // ============================================
        // GENERAL
        // ============================================
        'registration_form' => 'Registration Form',
        'complete_info' => 'Complete all required information',
        'home' => 'Home',
        'back' => 'Back',
        'next' => 'Next',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'view' => 'View',
        'close' => 'Close',
        'loading' => 'Loading...',
        'success' => 'Success',
        'error' => 'Error',
        'warning' => 'Warning',
        'info' => 'Information',
        
        // ============================================
        // NAVEGACIÓN
        // ============================================
        'pending' => 'Pending',
        'calculator' => 'Calculator',
        'pending_forms' => 'Pending Forms',
        'no_pending_forms' => 'No pending forms',
        
        // ============================================
        // FORMULARIO - ACCIONES
        // ============================================
        'save_draft' => 'Save as Draft',
        'submit' => 'Submit',
        'confirm_send' => 'Confirm and Send',
        'form_preview' => 'Form Preview',
        'draft_saved' => 'Draft saved successfully',
        'form_submitted' => 'Form submitted successfully',
        'confirm_delete' => 'Are you sure you want to delete this draft?',
        
        // ============================================
        // SECCIONES DEL FORMULARIO
        // ============================================
        'section_1' => 'Section 1: Request Information',
        'section_2' => 'Section 2: Client Information',
        'section_3' => 'Section 3: Operational / Service Details',
        'section_4' => 'Section 4: Economic Information',
        'section_5' => 'Section 5: Contract Information',
        'section_6' => 'Section 6: Observations',
        'section_7' => 'Section 7: Scope of Work',
        'section_8' => 'Section 8: Photos',
        
        // ============================================
        // CAMPOS DEL FORMULARIO
        // ============================================
        'service_type' => 'Service Type',
        'request_type' => 'Request Type',
        'requested_service' => 'Requested Service',
        'facility_type' => 'Facility Type',
        'size_sqft' => 'Size (sqft)',
        'client_name' => 'Client Name',
        'client_phone' => 'Phone',
        'client_email' => 'Email',
        'client_address' => 'Address',
        'client_city' => 'City',
        'client_state' => 'State',
        'client_zip' => 'ZIP Code',
        'start_date' => 'Start Date',
        'service_frequency' => 'Service Frequency',
        'service_days' => 'Service Days',
        'service_time' => 'Service Time',
        'staff_count' => 'Staff Count',
        'monthly_cost' => 'Monthly Cost',
        'rate_type' => 'Rate Type',
        'discount' => 'Discount',
        'total_cost' => 'Total Cost',
        'observations' => 'Observations',
        'scope_of_work' => 'Scope of Work',
        
        // ============================================
        // FOTOS
        // ============================================
        'add_photos' => 'Add Photos',
        'upload_photos' => 'Upload or take photos',
        'upload_instructions' => 'Click the button below to upload photos related to the service request',
        'no_photos' => 'No photos uploaded',
        'photo_uploaded' => 'Photo uploaded successfully',
        'max_photos_reached' => 'Maximum number of photos reached',
        'invalid_file_type' => 'Invalid file type. Only images are allowed.',
        'file_too_large' => 'File is too large. Maximum size is 5MB.',
        
        // ============================================
        // VALIDACIÓN
        // ============================================
        'required_field' => 'This field is required',
        'invalid_email' => 'Please enter a valid email address',
        'invalid_phone' => 'Please enter a valid phone number',
        'invalid_date' => 'Please enter a valid date',
        'invalid_number' => 'Please enter a valid number',
        'form_incomplete' => 'Please complete all required fields',
        
        // ============================================
        // MENSAJES DE ESTADO
        // ============================================
        'status_draft' => 'Draft',
        'status_sent' => 'Sent',
        'status_archived' => 'Archived',
        'created_at' => 'Created',
        'updated_at' => 'Updated',
        'sent_at' => 'Sent',
        
        // ============================================
        // ERRORES
        // ============================================
        'error_loading_data' => 'Error loading data',
        'error_saving_draft' => 'Error saving draft',
        'error_sending_form' => 'Error sending form',
        'error_deleting_draft' => 'Error deleting draft',
        'error_uploading_photo' => 'Error uploading photo',
        'connection_error' => 'Connection error. Please try again.',
    ],
    
    'es' => [
        // ============================================
        // GENERAL
        // ============================================
        'registration_form' => 'Formulario de Registro',
        'complete_info' => 'Complete toda la información requerida',
        'home' => 'Inicio',
        'back' => 'Atrás',
        'next' => 'Siguiente',
        'save' => 'Guardar',
        'cancel' => 'Cancelar',
        'delete' => 'Eliminar',
        'edit' => 'Editar',
        'view' => 'Ver',
        'close' => 'Cerrar',
        'loading' => 'Cargando...',
        'success' => 'Éxito',
        'error' => 'Error',
        'warning' => 'Advertencia',
        'info' => 'Información',
        
        // ============================================
        // NAVEGACIÓN
        // ============================================
        'pending' => 'Pendientes',
        'calculator' => 'Calculadora',
        'pending_forms' => 'Formularios Pendientes',
        'no_pending_forms' => 'No hay formularios pendientes',
        
        // ============================================
        // FORMULARIO - ACCIONES
        // ============================================
        'save_draft' => 'Guardar Borrador',
        'submit' => 'Enviar',
        'confirm_send' => 'Confirmar y Enviar',
        'form_preview' => 'Previsualización del Formulario',
        'draft_saved' => 'Borrador guardado exitosamente',
        'form_submitted' => 'Formulario enviado exitosamente',
        'confirm_delete' => '¿Está seguro de eliminar este borrador?',
        
        // ============================================
        // SECCIONES DEL FORMULARIO
        // ============================================
        'section_1' => 'Sección 1: Información de Solicitud',
        'section_2' => 'Sección 2: Información del Cliente',
        'section_3' => 'Sección 3: Detalles Operativos / del Servicio',
        'section_4' => 'Sección 4: Información Económica',
        'section_5' => 'Sección 5: Información del Contrato',
        'section_6' => 'Sección 6: Observaciones',
        'section_7' => 'Sección 7: Alcance del Trabajo',
        'section_8' => 'Sección 8: Fotos',
        
        // ============================================
        // CAMPOS DEL FORMULARIO
        // ============================================
        'service_type' => 'Tipo de Servicio',
        'request_type' => 'Tipo de Solicitud',
        'requested_service' => 'Servicio Solicitado',
        'facility_type' => 'Tipo de Establecimiento',
        'size_sqft' => 'Tamaño (pies cuadrados)',
        'client_name' => 'Nombre del Cliente',
        'client_phone' => 'Teléfono',
        'client_email' => 'Correo Electrónico',
        'client_address' => 'Dirección',
        'client_city' => 'Ciudad',
        'client_state' => 'Estado',
        'client_zip' => 'Código Postal',
        'start_date' => 'Fecha de Inicio',
        'service_frequency' => 'Frecuencia del Servicio',
        'service_days' => 'Días de Servicio',
        'service_time' => 'Horario del Servicio',
        'staff_count' => 'Cantidad de Personal',
        'monthly_cost' => 'Costo Mensual',
        'rate_type' => 'Tipo de Tarifa',
        'discount' => 'Descuento',
        'total_cost' => 'Costo Total',
        'observations' => 'Observaciones',
        'scope_of_work' => 'Alcance del Trabajo',
        
        // ============================================
        // FOTOS
        // ============================================
        'add_photos' => 'Agregar Fotos',
        'upload_photos' => 'Subir o tomar fotos',
        'upload_instructions' => 'Haz clic en el botón de abajo para subir fotos relacionadas con la solicitud de servicio',
        'no_photos' => 'No se han subido fotos',
        'photo_uploaded' => 'Foto subida exitosamente',
        'max_photos_reached' => 'Se alcanzó el número máximo de fotos',
        'invalid_file_type' => 'Tipo de archivo inválido. Solo se permiten imágenes.',
        'file_too_large' => 'El archivo es muy grande. El tamaño máximo es 5MB.',
        
        // ============================================
        // VALIDACIÓN
        // ============================================
        'required_field' => 'Este campo es requerido',
        'invalid_email' => 'Por favor ingrese un correo electrónico válido',
        'invalid_phone' => 'Por favor ingrese un número de teléfono válido',
        'invalid_date' => 'Por favor ingrese una fecha válida',
        'invalid_number' => 'Por favor ingrese un número válido',
        'form_incomplete' => 'Por favor complete todos los campos requeridos',
        
        // ============================================
        // MENSAJES DE ESTADO
        // ============================================
        'status_draft' => 'Borrador',
        'status_sent' => 'Enviado',
        'status_archived' => 'Archivado',
        'created_at' => 'Creado',
        'updated_at' => 'Actualizado',
        'sent_at' => 'Enviado',
        
        // ============================================
        // ERRORES
        // ============================================
        'error_loading_data' => 'Error al cargar los datos',
        'error_saving_draft' => 'Error al guardar el borrador',
        'error_sending_form' => 'Error al enviar el formulario',
        'error_deleting_draft' => 'Error al eliminar el borrador',
        'error_uploading_photo' => 'Error al subir la foto',
        'connection_error' => 'Error de conexión. Por favor intente nuevamente.',
    ]
];

// ============================================
// 4. FUNCIÓN HELPER PARA TRADUCCIONES
// ============================================

/**
 * Obtener traducción
 * @param string $key Clave de traducción
 * @param string|null $language Idioma (null = usar idioma actual)
 * @return string Texto traducido o la clave si no existe
 */
function t($key, $language = null) {
    global $translations, $lang;
    
    $currentLang = $language ?? $lang;
    
    if (isset($translations[$currentLang][$key])) {
        return $translations[$currentLang][$key];
    }
    
    // Si no existe la traducción, retornar la clave
    // En desarrollo, log de traducciones faltantes
    if (defined('BASE_PATH') && function_exists('log_message')) {
        log_message("Missing translation: $key [$currentLang]", 'WARNING');
    }
    
    return $key;
}

/**
 * Alias corto para traducción
 */
function __($key, $language = null) {
    return t($key, $language);
}

/**
 * Obtener idioma actual
 * @return string Código de idioma (en/es)
 */
function get_current_language() {
    global $lang;
    return $lang;
}

/**
 * Verificar si el idioma es inglés
 * @return bool
 */
function is_english() {
    global $lang;
    return $lang === 'en';
}

/**
 * Verificar si el idioma es español
 * @return bool
 */
function is_spanish() {
    global $lang;
    return $lang === 'es';
}

/**
 * Obtener lista de idiomas disponibles
 * @return array Array de códigos de idioma
 */
function get_available_languages() {
    return ['en', 'es'];
}

/**
 * Obtener nombre del idioma
 * @param string $code Código del idioma
 * @return string Nombre del idioma
 */
function get_language_name($code) {
    $names = [
        'en' => 'English',
        'es' => 'Español'
    ];
    return $names[$code] ?? $code;
}

// ============================================
// 5. HACER DISPONIBLES LAS TRADUCCIONES EN JS
// ============================================

/**
 * Exportar traducciones para JavaScript
 * Usar en el HTML: <?php export_translations_to_js(); ?>
 */
function export_translations_to_js() {
    global $translations, $lang;
    
    $jsTranslations = json_encode($translations[$lang]);
    
    echo "<script>";
    echo "window.translations = $jsTranslations;";
    echo "window.currentLang = '$lang';";
    echo "window.t = function(key) { return window.translations[key] || key; };";
    echo "</script>";
}

// ============================================
// SISTEMA DE IDIOMAS LISTO PARA USAR
// ============================================
// Después de incluir este archivo, puedes usar:
// - t('key') o __('key') para traducir
// - $lang para el idioma actual
// - get_current_language() para obtener el idioma
// - export_translations_to_js() para usar traducciones en JS
// ============================================