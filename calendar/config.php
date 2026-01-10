<?php
/**
 * ============================================================
 * ARCHIVO DE CONFIGURACIÓN PRINCIPAL - FIXED VERSION
 * Busca clases en la raíz Y en classes/
 * ============================================================
 */

// ============================================================
// CONFIGURACIÓN DE SESIONES
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// ZONA HORARIA
// ============================================================
date_default_timezone_set('America/Chicago');

// ============================================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'calendar_system');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ============================================================
// CONFIGURACIÓN DEL CALENDARIO
// ============================================================
define('CALENDAR_START_YEAR', 2015);
define('CALENDAR_END_YEAR', 2100);

// ============================================================
// RUTAS DEL SISTEMA
// ============================================================
define('BASE_PATH', __DIR__);
define('CLASSES_PATH', BASE_PATH . '/classes');
define('VIEWS_PATH', BASE_PATH . '/views');
define('ASSETS_PATH', BASE_PATH . '/assets');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// ============================================================
// CONFIGURACIÓN DE ERRORES (Desarrollo vs Producción)
// ============================================================
define('ENVIRONMENT', 'development'); // 'development' o 'production'

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', BASE_PATH . '/logs/php-errors.log');
}

// ============================================================
// AUTOLOAD DE CLASES - FIXED VERSION
// Busca en múltiples ubicaciones
// ============================================================
spl_autoload_register(function($class_name) {
    // Posibles ubicaciones de archivos
    $locations = [
        // 1. En carpeta classes/ con mayúscula (estructura correcta)
        CLASSES_PATH . '/' . $class_name . '.php',
        
        // 2. En raíz con mayúscula
        BASE_PATH . '/' . $class_name . '.php',
        
        // 3. En raíz con minúscula (compatibilidad)
        BASE_PATH . '/' . strtolower($class_name) . '.php',
        
        // 4. En carpeta classes/ con minúscula
        CLASSES_PATH . '/' . strtolower($class_name) . '.php',
    ];
    
    // Intentar cargar desde cada ubicación
    foreach ($locations as $file) {
        if (file_exists($file)) {
            require_once $file;
            return; // Salir después de cargar
        }
    }
    
    // Debug en modo desarrollo
    if (ENVIRONMENT === 'development') {
        error_log("AUTOLOAD WARNING: Class '$class_name' not found in any location");
    }
});

// ============================================================
// FUNCIONES HELPER DE SESIÓN
// ============================================================

/**
 * Verificar si el usuario está logueado
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Obtener ID del usuario actual
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Obtener datos del usuario actual
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'user_id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'full_name' => $_SESSION['full_name'] ?? null,
        'timezone' => $_SESSION['timezone'] ?? 'America/Chicago'
    ];
}

/**
 * Iniciar sesión de usuario
 */
function loginUser($user) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['timezone'] = $user['timezone'];
}

/**
 * Cerrar sesión
 */
function logoutUser() {
    session_unset();
    session_destroy();
}

/**
 * Redirigir a otra página
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Requerir autenticación
 */
function requireAuth() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

// ============================================================
// FUNCIONES HELPER DE FECHAS
// ============================================================

/**
 * Formatear fecha en español
 */
function formatDateES($date) {
    $meses = [
        1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    
    $timestamp = strtotime($date);
    $dia = date('j', $timestamp);
    $mes = $meses[(int)date('n', $timestamp)];
    $anio = date('Y', $timestamp);
    
    return "$dia de $mes de $anio";
}

/**
 * Formatear hora en formato 12h
 */
function formatTime12h($time) {
    if (empty($time)) return '';
    return date('g:i A', strtotime($time));
}

/**
 * Formatear hora en formato 24h
 */
function formatTime24h($time) {
    if (empty($time)) return '';
    return date('H:i', strtotime($time));
}

/**
 * Obtener días del mes
 */
function getDaysInMonth($month, $year) {
    return cal_days_in_month(CAL_GREGORIAN, $month, $year);
}

/**
 * Obtener primer día del mes (0=Domingo, 6=Sábado)
 */
function getFirstDayOfMonth($month, $year) {
    return date('w', strtotime("$year-$month-01"));
}

// ============================================================
// FUNCIONES HELPER DE VALIDACIÓN
// ============================================================

/**
 * Sanitizar entrada
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validar email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validar fecha
 */
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Validar hora
 */
function validateTime($time) {
    return preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $time);
}

// ============================================================
// FUNCIONES HELPER DE UI
// ============================================================

/**
 * Mostrar mensaje flash
 */
function setFlashMessage($message, $type = 'info') {
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type // success, error, warning, info
    ];
}

/**
 * Obtener y eliminar mensaje flash
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Escapar HTML
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// ============================================================
// CONSTANTES DE PRIORIDAD
// ============================================================
define('PRIORITY_LOW', 'low');
define('PRIORITY_NORMAL', 'normal');
define('PRIORITY_HIGH', 'high');
define('PRIORITY_URGENT', 'urgent');

// ============================================================
// CONSTANTES DE ESTADO
// ============================================================
define('STATUS_PENDING', 'pending');
define('STATUS_CONFIRMED', 'confirmed');
define('STATUS_CANCELLED', 'cancelled');
define('STATUS_COMPLETED', 'completed');