/**
 * CORE CONFIG - Configuración Global
 */
window.APP_CONFIG = {
    debug: true,
    endpoints: {
        saveDraft: 'controllers/save_draft.php',
        loadDrafts: 'controllers/load_drafts.php',
        loadForm: 'controllers/load_form_data.php',
        deleteDraft: 'controllers/delete_draft.php',
        sendEmail: 'controllers/enviar_correo.php'
    },
    photos: {
        maxCount: 10,
        maxSize: 5 * 1024 * 1024, // 5MB
        allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
    },
    timeouts: {
        notification: 3000,
        ajax: 30000
    }
};

window.logger = {
    log: function(message, data) {
        if (window.APP_CONFIG.debug) {
            console.log(`[LOG] ${message}`, data || '');
        }
    },
    error: function(message, error) {
        console.error(`[ERROR] ${message}`, error || '');
    },
    warn: function(message) {
        console.warn(`[WARN] ${message}`);
    }
};

console.log('✅ Core config loaded');