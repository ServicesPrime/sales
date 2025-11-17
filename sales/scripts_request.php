<script>
// ===========================================================
// 🧩 scripts_request.php — (VERSIÓN LIMPIA SOLO PARA 10 PREGUNTAS)
// ===========================================================

// -----------------------------------------------------------
// 🔹 CALCULOS DEL FORMULARIO DE COMISIÓN
// -----------------------------------------------------------

function calcularValores() {

    let billRate = parseFloat(document.getElementById("Bill_Rate").value) || 0;
    let operatingCost = parseFloat(document.getElementById("Operating_Cost").value) || 0;
    let supplies = parseFloat(document.getElementById("Supplies").value) || 0;
    let commissionPercent = parseFloat(document.getElementById("Commission_Percentage").value) || 0;

    // Margen general
    let margin = billRate - (operatingCost + supplies);
    if (margin < 0) margin = 0;

    // 44% gastos fijos
    let fixedExpenses = margin * 0.44;

    // 56% utilidad neta
    let netUtility = margin * 0.56;

    // Comisión final
    let commissionAmount = netUtility * (commissionPercent / 100);

    // Asignar valores
    document.getElementById("Fixed_Expenses").value = fixedExpenses.toFixed(2);
    document.getElementById("Net_Utility").value = netUtility.toFixed(2);
    document.getElementById("Commission_Amount").value = commissionAmount.toFixed(2);
}

// -----------------------------------------------------------
// 🔹 EVENTOS PARA CALCULAR DINÁMICAMENTE
// -----------------------------------------------------------

document.addEventListener("input", function () {
    calcularValores();
});

// Slider
document.getElementById("Commission_Percentage").addEventListener("input", function () {
    document.getElementById("Commission_Label").innerText = this.value + "%";
    calcularValores();
});

// Inicializar valores
document.addEventListener("DOMContentLoaded", function () {
    calcularValores();
});
</script>
