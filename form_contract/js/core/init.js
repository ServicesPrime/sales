/**
 * CORE INIT - Inicialización Global
 */
(function() {
    'use strict';
    
    console.log('🚀 Initializing application...');
    
    // Verificar dependencias
    if (typeof window.APP_CONFIG === 'undefined') {
        console.error('❌ APP_CONFIG not loaded');
    }
    
    if (typeof window.translations === 'undefined') {
        console.warn('⚠️ Translations not loaded');
    }
    
    console.log('✅ Core init loaded');
})();