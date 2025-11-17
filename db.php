<?php
// ==========================================
//  CONEXIÓN A LA BASE DE DATOS (MySQL)
// ==========================================

$host = "localhost";    // Servidor MySQL
$user = "root";         // Usuario
$pass = "";             // SIN contraseña
$db   = "form";         // Nombre de tu base de datos

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// Para UTF-8 (evita problemas con acentos)
$conn->set_charset("utf8");

?>