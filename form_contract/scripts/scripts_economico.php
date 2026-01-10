/**
 * ============================================
 * SCRIPTS_ECONOMICO.PHP - JavaScript para Sección 4
 * ============================================
 * Funciones para cálculos económicos y scope
 * ============================================
 */
?>

<script>
// ============================================
// FUNCIÓN: updateScopeOfWork
// ============================================
function updateScopeOfWork() {
    console.log('📋 Updating Scope of Work...');
    
    const serviceType = document.getElementById('Service_Type')?.value;
    const scopeContainer = document.getElementById('scopeOfWorkContainer');
    
    if (!scopeContainer) {
        console.warn('⚠️ Scope container not found');
        return;
    }
    
    if (!serviceType) {
        console.warn('⚠️ Service Type not selected');
        return;
    }
    
    console.log('Service Type:', serviceType);
    
    // Definir tareas según tipo de servicio
    let tasks = [];
    
    if (serviceType === 'Janitorial') {
        tasks = [
            'Floor cleaning and maintenance',
            'Restroom sanitization',
            'Trash removal',
            'Window cleaning',
            'Carpet cleaning',
            'Disinfection services',
            'Supply restocking'
        ];
    } else if (serviceType === 'Hospitality') {
        tasks = [
            'Room cleaning',
            'Linen service',
            'Lobby maintenance',
            'Event space setup',
            'Kitchen cleaning',
            'Pool maintenance',
            'Laundry service'
        ];
    }
    
    // Limpiar contenedor
    scopeContainer.innerHTML = '';
    
    // Crear checkboxes
    tasks.forEach((task, index) => {
        const div = document.createElement('div');
        div.className = 'scope-item';
        div.style.marginBottom = '10px';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `scope_${index}`;
        checkbox.name = 'Scope_Of_Work[]';
        checkbox.value = task;
        checkbox.style.marginRight = '8px';
        
        const label = document.createElement('label');
        label.htmlFor = `scope_${index}`;
        label.textContent = task;
        label.style.cursor = 'pointer';
        
        div.appendChild(checkbox);
        div.appendChild(label);
        scopeContainer.appendChild(div);
    });
    
    console.log('✅ Scope of work updated:', tasks.length, 'tasks');
}

// ============================================
// FUNCIÓN: calculateTotal (Opcional)
// ============================================
function calculateTotal() {
    console.log('💰 Calculating total...');
    
    const priceInput = document.getElementById('PriceInput');
    const includeStaff = document.getElementById('includeStaff');
    
    if (!priceInput) {
        console.warn('⚠️ Price input not found');
        return;
    }
    
    let basePrice = parseFloat(priceInput.value) || 0;
    let staffCost = 0;
    
    // Si incluye personal, agregar costo adicional (ejemplo: 20%)
    if (includeStaff && includeStaff.checked) {
        staffCost = basePrice * 0.20;
    }
    
    const total = basePrice + staffCost;
    
    console.log('Base:', basePrice, 'Staff:', staffCost, 'Total:', total);
    
    // Mostrar en algún elemento si existe
    const totalDisplay = document.getElementById('totalCostDisplay');
    if (totalDisplay) {
        totalDisplay.textContent = '$' + total.toFixed(2);
    }
    
    return total;
}

// ============================================
// Event Listeners
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('💰 Initializing economico scripts...');
    
    // Event: Service Type change (actualiza scope)
    const serviceTypeSelect = document.getElementById('Service_Type');
    if (serviceTypeSelect) {
        serviceTypeSelect.addEventListener('change', function() {
            console.log('🔄 Service Type changed (economico):', this.value);
            updateScopeOfWork();
        });
        
        // Ejecutar al inicio si ya hay valor
        if (serviceTypeSelect.value) {
            updateScopeOfWork();
        }
    }
    
    // Event: Price change (calcular total)
    const priceInput = document.getElementById('PriceInput');
    if (priceInput) {
        priceInput.addEventListener('input', calculateTotal);
    }
    
    const includeStaff = document.getElementById('includeStaff');
    if (includeStaff) {
        includeStaff.addEventListener('change', calculateTotal);
    }
    
    console.log('✅ Economico scripts initialized');
});
</script>

<?php