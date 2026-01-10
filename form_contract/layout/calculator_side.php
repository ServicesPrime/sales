<!-- ========================================= -->
<!-- PANEL LATERAL DE CALCULADORA -->
<!-- ========================================= -->

<div class="calculator-container">
    <!-- Botón para cerrar la calculadora -->
    <button 
        class="calculator-close" 
        id="closeCalculator" 
        title="<?= t('close', $lang) ?>"
        aria-label="Close calculator"
    >
        ×
    </button>
    
    <!-- iFrame con la calculadora externa -->
    <iframe 
        id="calculatorIframe" 
        class="calculator-iframe" 
        src="../calculator/index.php"
        title="<?= t('calculator', $lang) ?>"
        loading="lazy"
        sandbox="allow-scripts allow-same-origin allow-forms"
    >
        <!-- Fallback para navegadores sin soporte de iframe -->
        <p>
            <?= $lang == 'en' 
                ? 'Your browser does not support iframes.' 
                : 'Tu navegador no soporta iframes.'; ?>
        </p>
    </iframe>
    
    <!-- Mensaje de error si la calculadora no carga -->
    <div class="calculator-error" id="calculatorError" style="display:none;">
        <div class="error-content">
            <span class="error-icon">⚠️</span>
            <p>
                <?= $lang == 'en' 
                    ? 'Calculator could not be loaded.' 
                    : 'No se pudo cargar la calculadora.'; ?>
            </p>
            <button 
                class="btn-retry" 
                onclick="document.getElementById('calculatorIframe').src = document.getElementById('calculatorIframe').src"
            >
                <?= $lang == 'en' ? 'Retry' : 'Reintentar' ?>
            </button>
        </div>
    </div>
</div>

<script>
// Manejar errores de carga del iframe
(function() {
    const iframe = document.getElementById('calculatorIframe');
    const errorDiv = document.getElementById('calculatorError');
    
    if (iframe && errorDiv) {
        iframe.addEventListener('error', function() {
            iframe.style.display = 'none';
            errorDiv.style.display = 'flex';
        });
        
        // Timeout para detectar carga lenta
        setTimeout(function() {
            if (!iframe.contentWindow || !iframe.contentDocument) {
                // Puede ser que esté bloqueado o no cargue
                console.warn('Calculator iframe may not have loaded properly');
            }
        }, 5000);
    }
})();
</script>

<style>
.calculator-error {
    display: none;
    justify-content: center;
    align-items: center;
    height: 100%;
    padding: 40px;
    background: #f8f9fa;
}

.error-content {
    text-align: center;
}

.error-icon {
    font-size: 48px;
    display: block;
    margin-bottom: 20px;
}

.error-content p {
    color: #6c757d;
    margin-bottom: 20px;
}

.btn-retry {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-retry:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
}
</style>