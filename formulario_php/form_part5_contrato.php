<!-- ========================================= -->
<!-- 📆 Section 5: Contract-Specific Information -->
<!-- ========================================= -->

<div class="section-title">Section 5: Contract Information</div>

<!-- 🔸 Se muestra solo si Request Type = Contract -->
<div id="contractFields" style="display: none;">

  <!-- 21️⃣ Inflation Adjustment / Price Increase Rate -->
  <div class="question-block" id="q21">
    <label for="inflationAdjustment" class="question-label">
      21. Inflation Adjustment / Price Increase Rate
    </label>
    <input
      type="text"
      name="inflationAdjustment"
      id="inflationAdjustment"
      placeholder="Enter inflation adjustment or % increase"
    >
  </div>

  <!-- 22️⃣ Total Area (sq ft) -->
  <div class="question-block" id="q22">
    <label for="totalArea" class="question-label">
      22. Total Area (sq ft)
    </label>
    <input
      type="text"
      name="totalArea"
      id="totalArea"
      placeholder="Enter total area in square feet"
    >
  </div>

  <!-- 23️⃣ Buildings Included -->
  <div class="question-block" id="q23">
    <label for="buildingsIncluded" class="question-label">
      23. Buildings Included
    </label>
    <input
      type="text"
      name="buildingsIncluded"
      id="buildingsIncluded"
      placeholder="List buildings covered by the contract"
    >
  </div>

  <!-- 24️⃣ Start Date of Services -->
  <div class="question-block" id="q24">
    <label for="startDateServices" class="question-label">
      24. Start Date of Services
    </label>
    <input
      type="date"
      name="startDateServices"
      id="startDateServices"
    >
  </div>

</div>

<!-- ====================================================== -->
<!-- 🧠 SCRIPT: Mostrar Section 5 solo cuando es “Contract” -->
<!-- ====================================================== -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const requestTypeSelect = document.getElementById("Request_Type");
    const contractFields = document.getElementById("contractFields");

    if (!requestTypeSelect || !contractFields) return;

    function toggleContractFields() {
      const value = requestTypeSelect.value.trim().toLowerCase();

      if (value === "contract") {
        contractFields.style.display = "block";
      } else {
        contractFields.style.display = "none";
        // Limpia los campos cuando no aplica
        contractFields.querySelectorAll("input").forEach(input => input.value = "");
      }
    }

    toggleContractFields();
    requestTypeSelect.addEventListener("change", toggleContractFields);
  });
</script>
