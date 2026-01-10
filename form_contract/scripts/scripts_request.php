/**
 * ============================================
 * SCRIPTS_REQUEST.PHP - JavaScript para Sección 1
 * ============================================
 * Funciones para Request Information
 * ============================================
 */
?>

<script>
// ============================================
// FUNCIÓN: Cambio de tema según Service Type
// ============================================
function changeTheme() {
    console.log('🎨 Changing theme...');
    
    const serviceType = document.getElementById('Service_Type')?.value;
    const body = document.body;
    
    if (!serviceType) {
        console.warn('⚠️ Service Type not selected');
        return;
    }
    
    console.log('Service Type:', serviceType);
    
    // Remover clases de tema
    body.classList.remove('theme-hospitality', 'theme-janitorial');
    
    // Agregar clase según tipo
    if (serviceType === 'Hospitality') {
        body.classList.add('theme-hospitality');
        console.log('🎨 Theme: Hospitality (Red)');
    } else if (serviceType === 'Janitorial') {
        body.classList.add('theme-janitorial');
        console.log('🎨 Theme: Janitorial (Blue)');
    }
    
    // Llamar a otras funciones que dependen de Service Type
    if (typeof updateOptions === 'function') {
        updateOptions();
    }
    if (typeof updateScopeOfWork === 'function') {
        updateScopeOfWork();
    }
    
    console.log('✅ Theme changed');
}

// ============================================
// Event Listeners
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 Initializing request scripts...');
    
    // Event: Service Type change
    const serviceTypeSelect = document.getElementById('Service_Type');
    if (serviceTypeSelect) {
        serviceTypeSelect.addEventListener('change', function() {
            console.log('🔄 Service Type changed (request):', this.value);
            changeTheme();
        });
        
        // Ejecutar al inicio si ya hay valor
        if (serviceTypeSelect.value) {
            changeTheme();
        }
    }
    
    console.log('✅ Request scripts initialized');
});
</script>

<?php