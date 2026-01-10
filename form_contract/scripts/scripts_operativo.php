/**
 * ============================================
 * SCRIPTS_OPERATIVO.PHP - JavaScript para Sección 3
 * ============================================
 * Funciones para manejo dinámico de opciones
 * ============================================
 */
?>

<script>
// ============================================
// FUNCIÓN: updateOptions
// ============================================
function updateOptions() {
    console.log('📝 Updating options...');
    
    const serviceType = document.getElementById('Service_Type')?.value;
    const invoiceFrequency = document.getElementById('Invoice_Frequency');
    const contractDuration = document.getElementById('Contract_Duration');
    
    if (!serviceType) {
        console.warn('⚠️ Service Type not selected');
        return;
    }
    
    console.log('Service Type:', serviceType);
    
    // Actualizar opciones basadas en Service Type
    if (serviceType === 'Janitorial') {
        updateInvoiceFrequencyOptions([
            'Monthly',
            'Bi-weekly',
            'Weekly',
            'One-time'
        ]);
    } else if (serviceType === 'Hospitality') {
        updateInvoiceFrequencyOptions([
            'Monthly',
            'Quarterly',
            'Annual',
            'Per Event'
        ]);
    }
    
    console.log('✅ Options updated');
}

// ============================================
// FUNCIÓN: updateInvoiceFrequencyOptions
// ============================================
function updateInvoiceFrequencyOptions(options) {
    const select = document.getElementById('Invoice_Frequency');
    
    if (!select) {
        console.error('❌ Invoice_Frequency select not found');
        return;
    }
    
    // Guardar valor actual
    const currentValue = select.value;
    
    // Limpiar opciones
    select.innerHTML = '<option value="">-- Select an option --</option>';
    
    // Agregar nuevas opciones
    options.forEach(option => {
        const opt = document.createElement('option');
        opt.value = option;
        opt.textContent = option;
        select.appendChild(opt);
    });
    
    // Restaurar valor si existe
    if (currentValue && options.includes(currentValue)) {
        select.value = currentValue;
    }
    
    console.log('✅ Invoice frequency options updated:', options.length);
}

// ============================================
// Event Listeners
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initializing operativo scripts...');
    
    // Event: Service Type change
    const serviceTypeSelect = document.getElementById('Service_Type');
    if (serviceTypeSelect) {
        serviceTypeSelect.addEventListener('change', function() {
            console.log('🔄 Service Type changed:', this.value);
            updateOptions();
        });
        
        // Ejecutar al inicio si ya hay valor
        if (serviceTypeSelect.value) {
            updateOptions();
        }
    }
    
    console.log('✅ Operativo scripts initialized');
});
</script>

<?php