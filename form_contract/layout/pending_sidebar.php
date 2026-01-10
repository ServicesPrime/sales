<!-- ========================================= -->
<!-- SIDEBAR DE FORMULARIOS PENDIENTES -->
<!-- ========================================= -->

<aside class="pending-forms-sidebar" id="pendingFormsSidebar">
    <!-- Header del Sidebar -->
    <div class="sidebar-header">
        <h3>
            📋 <?= t('pending_forms', $lang) ?>
        </h3>
        <button 
            class="sidebar-toggle" 
            id="sidebarToggle" 
            title="Toggle Sidebar"
            aria-label="Toggle sidebar"
        >
            <span>◀</span>
        </button>
    </div>
    
    <!-- Contenido del Sidebar -->
    <div class="sidebar-content" id="pendingFormsContent">
        
        <!-- Indicador de carga -->
        <div class="loading-indicator" style="display:none;">
            <div class="loader"></div>
            <p><?= t('loading', $lang) ?></p>
        </div>
        
        <!-- Lista de formularios -->
        <div class="pending-forms-list" id="pendingFormsList">
            <!-- Los formularios se cargarán dinámicamente aquí vía JavaScript -->
        </div>
        
        <!-- Mensaje cuando no hay formularios -->
        <div class="no-forms-message" style="display:none;">
            <p>📭 <?= t('no_pending_forms', $lang) ?></p>
        </div>
        
    </div>
</aside>

<!-- Template para tarjetas de formulario (hidden, usado por JavaScript) -->
<template id="formCardTemplate">
    <div class="form-card" data-form-id="">
        <div class="form-card-header">
            <div class="form-card-id">#<span class="id-number"></span></div>
            <div class="form-card-date"></div>
        </div>
        <div class="form-card-client"></div>
        <div class="form-card-service"></div>
        <div class="form-card-footer">
            <span class="form-card-status"></span>
            <div class="form-card-actions">
                <button class="form-card-btn edit" title="<?= t('edit', $lang) ?>" aria-label="Edit form">
                    ✏️
                </button>
                <button class="form-card-btn delete" title="<?= t('delete', $lang) ?>" aria-label="Delete form">
                    🗑️
                </button>
            </div>
        </div>
    </div>
</template>