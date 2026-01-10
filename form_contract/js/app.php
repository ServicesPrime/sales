<?php
/**
 * ============================================
 * APP.PHP - Cargador de JavaScript Modular
 * ============================================
 * Carga todos los módulos JS en el orden correcto
 * ============================================
 */
?>

<!-- ============================================ -->
<!-- CORE SCRIPTS -->
<!-- ============================================ -->
<script src="js/core/config.js"></script>
<script src="js/core/utils.js"></script>
<script src="js/core/init.js"></script>
<script src="js/core/fix_validation.js"></script>

<!-- ============================================ -->
<!-- UI SCRIPTS -->
<!-- ============================================ -->
<script src="js/ui/accordion.js"></script>
<script src="js/ui/navbar.js"></script>
<script src="js/ui/sidebar.js"></script>
<script src="js/ui/calculator.js"></script>
<script src="js/ui/theme.js"></script>

<!-- ============================================ -->
<!-- FORM SCRIPTS -->
<!-- ============================================ -->
<script src="js/form/validation.js"></script>
<script src="js/form/preview.js"></script>

<!-- ============================================ -->
<!-- DRAFTS SCRIPTS -->
<!-- ============================================ -->
<script src="js/drafts/save.js"></script>
<script src="js/drafts/load.js"></script>
<script src="js/drafts/delete.js"></script>
<script src="js/drafts/populate.js"></script>

<script>
// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Application scripts loaded');
    console.log('📱 Current language:', window.currentLang);
    
    // Verificar que todas las funciones estén disponibles
    if (typeof window.initAccordion === 'function') {
        console.log('✅ Accordion module loaded');
    }
    if (typeof window.initNavbar === 'function') {
        console.log('✅ Navbar module loaded');
    }
    if (typeof window.initSidebar === 'function') {
        console.log('✅ Sidebar module loaded');
    }
});
</script>