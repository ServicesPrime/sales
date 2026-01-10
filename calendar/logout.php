<?php
/**
 * ============================================================
 * LOGOUT
 * Cierra la sesión del usuario
 * ============================================================
 */

require_once 'config.php';

// Cerrar sesión
logoutUser();

// Redirigir a login
redirect('login.php');