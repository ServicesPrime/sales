/**
 * NAVBAR - Control de Navegación
 */
(function() {
    'use strict';
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavbar);
    } else {
        initNavbar();
    }
    
    function initNavbar() {
        console.log('🧭 Initializing navbar...');
        
        const togglePending = document.getElementById('togglePendingPanel');
        const toggleCalc = document.getElementById('toggleCalculator');
        
        if (togglePending) {
            togglePending.addEventListener('click', function() {
                const sidebar = document.getElementById('pendingFormsSidebar');
                if (sidebar) {
                    sidebar.classList.toggle('collapsed');
                }
            });
        }
        
        if (toggleCalc) {
            toggleCalc.addEventListener('click', function() {
                const wrapper = document.getElementById('splitScreenWrapper');
                if (wrapper) {
                    wrapper.classList.toggle('calculator-visible');
                }
                this.classList.toggle('active');
            });
        }
        
        console.log('✅ Navbar initialized');
        window.initNavbar = initNavbar;
    }
})();