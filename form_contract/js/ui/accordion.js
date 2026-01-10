/**
 * ============================================
 * ACCORDION.JS - Control de Secciones Colapsables
 * ============================================
 * Este archivo hace que las secciones se desplieguen/colapsen
 * ============================================
 */

(function() {
    'use strict';
    
    console.log('📂 Loading accordion module...');
    
    // Esperar a que el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAccordion);
    } else {
        initAccordion();
    }
    
    function initAccordion() {
        console.log('🎨 Initializing accordion...');
        
        // Obtener todos los títulos de sección
        const sectionTitles = document.querySelectorAll('.section-title');
        
        if (sectionTitles.length === 0) {
            console.warn('⚠️ No section titles found');
            return;
        }
        
        console.log(`✅ Found ${sectionTitles.length} sections`);
        
        // Agregar evento click a cada título
        sectionTitles.forEach((title, index) => {
            const sectionNumber = title.getAttribute('data-section');
            const content = document.querySelector(`[data-section-content="${sectionNumber}"]`);
            
            if (!content) {
                console.error(`❌ Content not found for section ${sectionNumber}`);
                return;
            }
            
            // Click handler
            title.addEventListener('click', function() {
                toggleSection(this, content);
            });
            
            // Expandir la primera sección por defecto
            if (index === 0) {
                content.classList.remove('hidden');
                title.classList.remove('collapsed');
                console.log(`✅ Section ${sectionNumber} expanded by default`);
            }
        });
        
        console.log('✅ Accordion initialized successfully');
        
        // Hacer disponible globalmente
        window.initAccordion = initAccordion;
    }
    
    function toggleSection(titleElement, contentElement) {
        const isHidden = contentElement.classList.contains('hidden');
        
        if (isHidden) {
            // Expandir
            contentElement.classList.remove('hidden');
            titleElement.classList.remove('collapsed');
            
            // Animación suave
            contentElement.style.opacity = '0';
            contentElement.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                contentElement.style.transition = 'all 0.3s ease';
                contentElement.style.opacity = '1';
                contentElement.style.transform = 'translateY(0)';
            }, 10);
            
            console.log('📂 Section expanded');
        } else {
            // Colapsar
            contentElement.style.transition = 'all 0.3s ease';
            contentElement.style.opacity = '0';
            contentElement.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                contentElement.classList.add('hidden');
                titleElement.classList.add('collapsed');
                contentElement.style.opacity = '';
                contentElement.style.transform = '';
            }, 300);
            
            console.log('📁 Section collapsed');
        }
    }
    
})();