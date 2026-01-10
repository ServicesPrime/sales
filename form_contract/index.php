<?php
/**
 * ============================================
 * INDEX.PHP - DIRECTOR DE ORQUESTA
 * ============================================
 * Este archivo SOLO orquesta el flujo.
 * NO contiene lógica de negocio ni HTML extenso.
 * ============================================
 * 
 * Versión: 2.0
 * Arquitectura: Modular
 * Última actualización: 2026-01-08
 */

// ============================================
// 1️⃣ BOOTSTRAP - Inicialización Global
// ============================================
require_once 'bootstrap/init.php';
require_once 'bootstrap/lang.php';

// ============================================
// 2️⃣ LAYOUT - Estructura Base HTML
// ============================================
require_once 'layout/head.php';
?>

<body>
    
    <?php 
    // ============================================
    // 3️⃣ NAVEGACIÓN SUPERIOR
    // ============================================
    require_once 'layout/navbar.php'; 
    ?>
    
    <!-- ============================================ -->
    <!-- 4️⃣ CONTENEDOR PRINCIPAL - SPLIT SCREEN -->
    <!-- ============================================ -->
    <div class="split-screen-wrapper" id="splitScreenWrapper">
        
        <?php 
        // ============================================
        // 5️⃣ SIDEBAR DE FORMULARIOS PENDIENTES
        // ============================================
        require_once 'layout/pending_sidebar.php'; 
        ?>
        
        <!-- ============================================ -->
        <!-- 6️⃣ ÁREA PRINCIPAL DEL FORMULARIO -->
        <!-- ============================================ -->
        <div class="form-side" id="formSide">
            <?php require_once 'views/form_container.php'; ?>
        </div>
        
        <!-- ============================================ -->
        <!-- 7️⃣ PANEL LATERAL DE CALCULADORA -->
        <!-- ============================================ -->
        <div class="calculator-side" id="calculatorSide">
            <?php require_once 'layout/calculator_side.php'; ?>
        </div>
        
    </div>
    
    <?php 
    // ============================================
    // 8️⃣ SCRIPTS JAVASCRIPT MODULARES
    // ============================================
    require_once 'js/app.php'; 
    ?>
    
</body>
</html>