<?php
/**
 * ============================================
 * BOOTSTRAP - INICIALIZACIÓN GLOBAL
 * ============================================
 * Configura el entorno de la aplicación
 * Se carga PRIMERO antes que cualquier otro archivo
 * ============================================
 */

// ============================================
// 1. INICIAR SESIÓN
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// 2. CONFIGURAR ZONA HORARIA
// ============================================
date_default_timezone_set('America/Chicago'); // Houston, Texas

// ============================================
// 3. CONFIGURAR MANEJO DE ERRORES
// ============================================
// Detectar si estamos en desarrollo o producción
$isDevelopment = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1', '::1']);

if ($isDevelopment) {
    // DESARROLLO: Mostrar todos los errores
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    // PRODUCCIÓN: Ocultar errores, solo log
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../Logs/php_errors.log');
}

// ============================================
// 4. CARGAR CONFIGURACIÓN DE BASE DE DATOS
// ============================================
require_once __DIR__ . '/../config/db_config.php';

// ============================================
// 5. DEFINIR CONSTANTES DE LA APLICACIÓN
// ============================================
define('APP_NAME', 'Sistema de Registro de Servicios');
define('APP_VERSION', '2.0');
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_PATH', BASE_PATH . '/Uploads/photos');
define('LOG_PATH', BASE_PATH . '/Logs');

// Límites de upload
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB por foto
define('MAX_PHOTO_COUNT', 10); // Máximo 10 fotos por formulario

// Estados de formularios
define('STATUS_DRAFT', 'draft');
define('STATUS_SENT', 'sent');
define('STATUS_ARCHIVED', 'archived');

// ============================================
// 6. VERIFICAR Y CREAR CARPETAS NECESARIAS
// ============================================
$requiredDirs = [
    UPLOAD_PATH,
    LOG_PATH,
];

foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// ============================================
// 7. FUNCIONES HELPER GLOBALES
// ============================================

/**
 * Redirigir a otra página
 * @param string $url URL de destino
 * @param int $statusCode Código HTTP (default 303)
 */
function redirect($url, $statusCode = 303) {
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 * Sanitizar input del usuario
 * @param string $data Dato a limpiar
 * @return string Dato limpio
 */
function clean_input($data) {
    if (is_array($data)) {
        return array_map('clean_input', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Registrar mensaje en log
 * @param string $message Mensaje a registrar
 * @param string $level Nivel: INFO, WARNING, ERROR
 * @param string $file Archivo de log (default: app.log)
 */
function log_message($message, $level = 'INFO', $file = 'app.log') {
    $logFile = LOG_PATH . '/' . $file;
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

/**
 * Respuesta JSON para AJAX
 * @param bool $success Éxito o error
 * @param mixed $data Datos a retornar
 * @param string $message Mensaje opcional
 */
function json_response($success, $data = null, $message = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'message' => $message,
        'timestamp' => time()
    ]);
    exit;
}

/**
 * Validar formato de email
 * @param string $email Email a validar
 * @return bool
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Formatear tamaño de archivo
 * @param int $bytes Tamaño en bytes
 * @return string Tamaño formateado
 */
function format_file_size($bytes) {
    if ($bytes < 1024) return $bytes . ' B';
    if ($bytes < 1048576) return round($bytes / 1024, 2) . ' KB';
    return round($bytes / 1048576, 2) . ' MB';
}

/**
 * Validar extensión de archivo
 * @param string $filename Nombre del archivo
 * @param array $allowedExtensions Extensiones permitidas
 * @return bool
 */
function is_valid_file_extension($filename, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp']) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, $allowedExtensions);
}

/**
 * Generar nombre de archivo único
 * @param string $originalName Nombre original
 * @return string Nombre único
 */
function generate_unique_filename($originalName) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $timestamp = time();
    $random = bin2hex(random_bytes(8));
    return "photo_{$timestamp}_{$random}.{$extension}";
}

/**
 * Obtener IP del cliente
 * @return string IP address
 */
function get_client_ip() {
    $ipAddress = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddress;
}

/**
 * Debug helper - Solo en desarrollo
 * @param mixed $data Datos a mostrar
 * @param bool $die Detener ejecución
 */
function dd($data, $die = true) {
    global $isDevelopment;
    if ($isDevelopment) {
        echo '<pre style="background:#1a1a1a;color:#00ff00;padding:15px;border-radius:8px;font-family:monospace;font-size:12px;">';
        print_r($data);
        echo '</pre>';
        if ($die) exit;
    }
}

// ============================================
// 8. CONFIGURACIÓN DE SEGURIDAD
// ============================================

// Prevenir clickjacking
header('X-Frame-Options: SAMEORIGIN');

// Prevenir XSS
header('X-XSS-Protection: 1; mode=block');

// Prevenir MIME sniffing
header('X-Content-Type-Options: nosniff');

// Configurar Content Security Policy (opcional, comentado por compatibilidad)
// header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");

// ============================================
// 9. REGISTRAR INICIO DE SESIÓN
// ============================================
if ($isDevelopment) {
    log_message("Application initialized - IP: " . get_client_ip(), 'INFO');
}

// ============================================
// 10. VARIABLES GLOBALES DISPONIBLES
// ============================================
// Después de incluir este archivo, están disponibles:
// - $pdo (conexión a base de datos)
// - Todas las funciones helper
// - Todas las constantes definidas
// - Sesión iniciada
// - Configuración de errores
// ============================================