<!-- ========================================= -->
<!-- CONTENEDOR PRINCIPAL DEL FORMULARIO -->
<!-- ========================================= -->

<div class="container">

    <!-- ========================================= -->
    <!-- LOGO DINÁMICO (Cambia según tipo de servicio) -->
    <!-- ========================================= -->
    <div id="dynamicLogo" class="dynamic-logo" style="display: none;">
        <img id="logoImage" src="" alt="Logo" class="logo-image">
    </div>

    <!-- ========================================= -->
    <!-- ENCABEZADO DEL FORMULARIO -->
    <!-- ========================================= -->
    <div class="form-header" id="formHeader">
        <h2>📄 <?= t('registration_form', $lang) ?></h2>
        <p><?= t('complete_info', $lang) ?></p>
    </div>

    <!-- ========================================= -->
    <!-- CONTENIDO DEL FORMULARIO -->
    <!-- ========================================= -->
    <div class="form-content">
        
        <form id="main_form" action="controllers/enviar_correo.php" method="POST" enctype="multipart/form-data">
            
            <?php
            /**
             * =========================================
             * SECCIONES DEL FORMULARIO
             * =========================================
             * Se cargan dinámicamente desde /forms/
             */
            
            $sections = [
                1 => 'form_part1_request.php',
                2 => 'form_part2_client.php',
                3 => 'form_part3_operativo.php',
                4 => 'form_part4_economico.php',
                5 => 'form_part5_contrato.php',
                6 => 'form_part6_observaciones.php',
                7 => 'form_part7_scope.php',
                8 => 'form_part8_photo.php',
            ];
            
            foreach ($sections as $num => $file) {
                $sectionKey = "section_$num";
                ?>
                
                <!-- Título de la Sección (Colapsable) -->
                <div class="section-title collapsible" data-section="<?= $num ?>">
                    <?= t($sectionKey, $lang) ?>
                    <span class="toggle-icon">▼</span>
                </div>
                
                <!-- Contenido de la Sección (Hidden por defecto) -->
                <div class="section-content hidden" data-section-content="<?= $num ?>">
                    <?php 
                    $filePath = __DIR__ . '/../forms/' . $file;
                    if (file_exists($filePath)) {
                        include $filePath;
                    } else {
                        echo '<p style="color:red;">⚠️ File not found: ' . $file . '</p>';
                    }
                    ?>
                </div>
                
                <?php
            }
            ?>
            
            <!-- ========================================= -->
            <!-- BOTONES DE ACCIÓN -->
            <!-- ========================================= -->
            <div class="form-actions">
                
                <!-- Botón: Guardar Borrador -->
                <button type="button" id="btnSaveDraft" class="btn-draft">
                    💾 <?= t('save_draft', $lang) ?>
                </button>
                
                <!-- Botón: Preview / Submit -->
                <button type="button" id="btnPreview" class="btn-submit">
                    📧 <?= t('submit', $lang) ?>
                </button>
                
            </div>
            
        </form>
    </div>
    
    <!-- ========================================= -->
    <!-- MODAL DE PREVISUALIZACIÓN -->
    <!-- ========================================= -->
    <?php include __DIR__ . '/preview_modal.php'; ?>
    
</div>

<!-- ========================================= -->
<!-- SCRIPTS ESPECÍFICOS DE SECCIONES -->
<!-- ========================================= -->
<?php 
/**
 * Estos scripts contienen lógica específica de negocio
 * para cada sección del formulario (dropdowns dinámicos, etc.)
 */
$scriptFiles = [
    'scripts_request.php',
    'scripts_operativo.php',
    'scripts_economico.php'
];

foreach ($scriptFiles as $scriptFile) {
    $scriptPath = __DIR__ . '/../scripts/' . $scriptFile;
    if (file_exists($scriptPath)) {
        include $scriptPath;
    }
}
?>

<!-- ========================================= -->
<!-- SCRIPT: LOGO DINÁMICO -->
<!-- ========================================= -->
<script>
(function() {
    'use strict';
    
    // Wait for DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDynamicLogo);
    } else {
        initDynamicLogo();
    }
    
    function initDynamicLogo() {
        const serviceTypeSelect = document.getElementById("Service_Type");
        const dynamicLogo = document.getElementById("dynamicLogo");
        const logoImage = document.getElementById("logoImage");
        const formHeader = document.getElementById("formHeader");
        
        if (!serviceTypeSelect || !dynamicLogo || !logoImage || !formHeader) {
            console.warn('Dynamic logo elements not found');
            return;
        }
        
        function updateTheme() {
            const serviceType = serviceTypeSelect.value;
            
            if (serviceType === "Hospitality") {
                // 🔴 HOSPITALITY - RED THEME
                logoImage.src = "Images/Hospitality.png";
                dynamicLogo.style.display = "block";
                formHeader.style.borderRadius = "0";
                
                // Change colors to red theme
                document.documentElement.style.setProperty('--primary-color', '#c70734');
                document.documentElement.style.setProperty('--primary-light', '#ff0844');
                
                // Update header gradient
                formHeader.style.background = 'linear-gradient(135deg, #c70734 0%, #a30000 100%)';
                
                // Update all section titles
                document.querySelectorAll('.section-title').forEach(title => {
                    title.style.background = 'linear-gradient(135deg, #c70734 0%, #a30000 100%)';
                });
                
                console.log('🔴 Theme changed to Hospitality (Red)');
                
            } else if (serviceType === "Janitorial") {
                // 🔵 JANITORIAL - BLUE THEME
                logoImage.src = "Images/Facility.png";
                dynamicLogo.style.display = "block";
                formHeader.style.borderRadius = "0";
                
                // Restore original blue colors
                document.documentElement.style.setProperty('--primary-color', '#001f54');
                document.documentElement.style.setProperty('--primary-light', '#003080');
                
                // Restore header gradient
                formHeader.style.background = 'linear-gradient(135deg, #001f54 0%, #003080 100%)';
                
                // Restore section titles
                document.querySelectorAll('.section-title').forEach(title => {
                    title.style.background = 'linear-gradient(135deg, #001f54 0%, #003080 100%)';
                });
                
                console.log('🔵 Theme changed to Janitorial (Blue)');
                
            } else {
                // No selection - hide logo
                dynamicLogo.style.display = "none";
                formHeader.style.borderRadius = "24px 24px 0 0";
                
                // Restore defaults
                document.documentElement.style.setProperty('--primary-color', '#001f54');
                document.documentElement.style.setProperty('--primary-light', '#003080');
                formHeader.style.background = 'linear-gradient(135deg, #001f54 0%, #003080 100%)';
                
                console.log('⚪ Theme reset to default');
            }
        }
        
        // Listen for changes in Service Type
        serviceTypeSelect.addEventListener("change", updateTheme);
        
        // Initialize on page load if value exists
        if (serviceTypeSelect.value) {
            updateTheme();
        }
        
        console.log('✅ Dynamic logo initialized');
    }
})();
</script>

<style>
/* ========================================= */
/* ESTILOS ESPECÍFICOS DEL LOGO DINÁMICO */
/* ========================================= */
.dynamic-logo {
    text-align: center;
    padding: 30px 40px 20px;
    background: white;
    border-radius: 24px 24px 0 0;
    transition: all 0.5s ease;
}

.logo-image {
    max-width: 280px;
    height: auto;
    transition: all 0.5s ease;
}

/* Cuando el logo está visible, ajustar header */
#dynamicLogo:not([style*="display: none"]) ~ .form-header {
    border-radius: 0 !important;
}

/* ========================================= */
/* RESPONSIVE */
/* ========================================= */
@media (max-width: 768px) {
    .dynamic-logo {
        padding: 20px 20px 15px;
    }
    
    .logo-image {
        max-width: 200px;
    }
}
</style>