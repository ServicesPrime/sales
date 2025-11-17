<?php
/**
 * db_config.php
 * Configuración de conexión a la base de datos MySQL
 * Proyecto: Formulario de Registro Prime Facility Services
 */

// ==============================
// 1️⃣ Datos de conexión
// ==============================
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "form";

// ==============================
// 2️⃣ Crear conexión
// ==============================
$conexion = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// ==============================
// 3️⃣ Verificar conexión
// ==============================
if ($conexion->connect_errno) {
  die("❌ Error de conexión a la base de datos ({$conexion->connect_errno}): {$conexion->connect_error}");
}

// ==============================
// 4️⃣ Configurar charset
// ==============================
if (!$conexion->set_charset("utf8mb4")) {
  die("❌ Error al establecer el charset UTF-8: " . $conexion->error);
}

// ✅ Conexión exitosa
// echo "Conexión exitosa a la base de datos.";
?>
