<!-- ========================================= -->
<!-- MODAL DE PREVISUALIZACIÓN DEL FORMULARIO -->
<!-- ========================================= -->

<div id="previewModal" class="preview-modal">
    <div class="preview-modal-backdrop"></div>
    
    <div class="preview-modal-content">
        
        <!-- Header del Modal -->
        <div class="preview-modal-header">
            <h2 class="preview-title">
                🧾 <?= t('form_preview', $lang) ?>
            </h2>
            <button 
                type="button" 
                id="closePreviewModal" 
                class="modal-close-btn"
                aria-label="<?= t('close', $lang) ?>"
            >
                ×
            </button>
        </div>
        
        <!-- Contenido del Preview -->
        <div class="preview-modal-body">
            <div id="previewContent" class="preview-content">
                <!-- El contenido se genera dinámicamente con JavaScript -->
            </div>
        </div>
        
        <!-- Footer con Acciones -->
        <div class="preview-modal-footer">
            
            <!-- Botón: Confirmar y Enviar -->
            <button
                type="submit"
                form="main_form"
                class="btn-confirm"
                id="btnConfirmSend"
            >
                ✅ <?= t('confirm_send', $lang) ?>
            </button>
            
            <!-- Botón: Cancelar -->
            <button
                type="button"
                id="cancelPreview"
                class="btn-cancel"
            >
                ❌ <?= t('cancel', $lang) ?>
            </button>
            
        </div>
        
    </div>
</div>

<style>
/* ========================================= */
/* MODAL DE PREVIEW - ESTILOS */
/* ========================================= */

.preview-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10000;
    justify-content: center;
    align-items: center;
    padding: 20px;
    overflow: auto;
}

.preview-modal.active {
    display: flex;
}

.preview-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    animation: fadeIn 0.3s ease;
}

.preview-modal-content {
    position: relative;
    background: white;
    border-radius: 16px;
    max-width: 850px;
    width: 100%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
    z-index: 10001;
}

.preview-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 35px;
    border-bottom: 2px solid #e1e8ed;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 16px 16px 0 0;
}

.preview-title {
    color: var(--primary-color);
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.modal-close-btn {
    background: none;
    border: none;
    font-size: 32px;
    color: #6c757d;
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
    line-height: 1;
}

.modal-close-btn:hover {
    background: #f8f9fa;
    color: #dc3545;
    transform: rotate(90deg);
}

.preview-modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 35px;
}

.preview-content {
    text-align: left;
    font-size: 14px;
    line-height: 1.6;
}

.preview-modal-footer {
    display: flex;
    justify-content: center;
    gap: 15px;
    padding: 25px 35px;
    border-top: 2px solid #e1e8ed;
    background: #f8f9fa;
    border-radius: 0 0 16px 16px;
}

/* ========================================= */
/* BOTONES DEL MODAL */
/* ========================================= */

.btn-confirm {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    color: white;
    padding: 14px 32px;
    border: none;
    border-radius: 50px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
}

.btn-confirm:active {
    transform: translateY(0);
}

.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 14px 32px;
    border: none;
    border-radius: 50px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.btn-cancel:active {
    transform: translateY(0);
}

/* ========================================= */
/* TABLA DE PREVIEW */
/* ========================================= */

.preview-content table {
    width: 100%;
    border-collapse: collapse;
    border: 2px solid #e1e8ed;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
}

.preview-content tr {
    border-bottom: 1px solid #e1e8ed;
}

.preview-content tr:last-child {
    border-bottom: none;
}

.preview-content td:first-child {
    font-weight: 600;
    padding: 12px;
    background: #f4f7fc;
    width: 40%;
    color: var(--primary-color);
}

.preview-content td:last-child {
    padding: 12px;
    background: white;
}

/* ========================================= */
/* ANIMACIONES */
/* ========================================= */

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ========================================= */
/* SCROLLBAR PERSONALIZADO */
/* ========================================= */

.preview-modal-body::-webkit-scrollbar {
    width: 8px;
}

.preview-modal-body::-webkit-scrollbar-track {
    background: #f4f7fc;
    border-radius: 4px;
}

.preview-modal-body::-webkit-scrollbar-thumb {
    background: rgba(0, 31, 84, 0.3);
    border-radius: 4px;
}

.preview-modal-body::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 31, 84, 0.5);
}

/* ========================================= */
/* RESPONSIVE */
/* ========================================= */

@media (max-width: 768px) {
    .preview-modal {
        padding: 10px;
    }
    
    .preview-modal-content {
        max-height: 95vh;
    }
    
    .preview-modal-header {
        padding: 20px;
    }
    
    .preview-title {
        font-size: 20px;
    }
    
    .preview-modal-body {
        padding: 20px;
    }
    
    .preview-modal-footer {
        flex-direction: column;
        padding: 20px;
    }
    
    .btn-confirm,
    .btn-cancel {
        width: 100%;
        justify-content: center;
    }
    
    .preview-content td:first-child {
        width: 100%;
        display: block;
        border-bottom: none;
    }
    
    .preview-content td:last-child {
        width: 100%;
        display: block;
    }
}

/* ========================================= */
/* ESTADO: LOADING */
/* ========================================= */

.preview-content.loading {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 200px;
}

.preview-content.loading::after {
    content: "";
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
/* ========================================= */
/* MODAL DE PREVIEW - FUNCIONALIDAD */
/* ========================================= */
(function() {
    'use strict';
    
    // Wait for DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPreviewModal);
    } else {
        initPreviewModal();
    }
    
    function initPreviewModal() {
        const modal = document.getElementById('previewModal');
        const btnPreview = document.getElementById('btnPreview');
        const btnCancel = document.getElementById('cancelPreview');
        const btnClose = document.getElementById('closePreviewModal');
        const previewContent = document.getElementById('previewContent');
        
        if (!modal || !btnPreview || !previewContent) {
            console.error('Preview modal elements not found');
            return;
        }
        
        // Abrir modal
        btnPreview?.addEventListener('click', function() {
            openPreview();
        });
        
        // Cerrar modal - Botón cancelar
        btnCancel?.addEventListener('click', function() {
            closePreview();
        });
        
        // Cerrar modal - Botón X
        btnClose?.addEventListener('click', function() {
            closePreview();
        });
        
        // Cerrar modal - Click fuera del contenido
        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target.classList.contains('preview-modal-backdrop')) {
                closePreview();
            }
        });
        
        // Cerrar modal - Tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closePreview();
            }
        });
        
        function openPreview() {
            const form = document.getElementById('main_form');
            
            if (!form) {
                console.error('Form not found');
                return;
            }
            
            // Validar formulario antes de mostrar preview
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Generar contenido del preview
            generatePreviewContent(form);
            
            // Mostrar modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            console.log('📋 Preview modal opened');
        }
        
        function closePreview() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
            
            console.log('📋 Preview modal closed');
        }
        
        function generatePreviewContent(form) {
            const formData = new FormData(form);
            
            let html = '<table>';
            
            // Iterar sobre todos los campos del formulario
            for (let [key, value] of formData.entries()) {
                // Saltar archivos y campos vacíos
                if (value instanceof File) continue;
                if (typeof value === 'string' && value.trim() === '') continue;
                
                // Formatear el nombre del campo
                const fieldName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                
                html += `
                    <tr>
                        <td>${fieldName}</td>
                        <td>${escapeHtml(value)}</td>
                    </tr>
                `;
            }
            
            html += '</table>';
            
            previewContent.innerHTML = html;
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        console.log('✅ Preview modal initialized');
    }
})();
</script>