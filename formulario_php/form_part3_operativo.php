<!-- ========================================== -->
<!-- ⚙️ Section 3: Operational / Service Details -->
<!-- ========================================== -->

<div class="section-title">Section 3: Operational / Service Details</div>

<!-- 12️⃣ Site Visit Conducted -->
<div class="question-block" id="q12">
  <label for="Site_Visit_Conducted" id="siteVisitLabel" class="question-label">12. Site Visit Conducted</label>
  <select name="Site_Visit_Conducted" id="siteVisitSelect">
    <option value="">-- Select an option --</option>
    <option value="Yes">Yes</option>
    <option value="No">No</option>
  </select>
</div>

<!-- 13️⃣ Service Frequency -->
<div class="question-block" id="q13">
  <label for="Service_Frequency" class="question-label">13. Service Frequency</label>

  <div class="simple-frequency-inline">
    <!-- 🔽 Selector de periodo -->
    <div class="period-select">
      <select id="period" name="period" class="period-dropdown">
        <option value="">-- Select Period --</option>
        <option value="one_time">One Time</option>
        <option value="two_weeks">Every 2 Weeks</option>
        <option value="three_weeks">Every 3 Weeks</option>
        <option value="monthly">Monthly</option>
        <option value="bimonthly">Bimonthly</option>
        <option value="quarterly">Quarterly</option>
        <option value="four_months">Every 4 Months</option>
        <option value="semiannual">Semiannual</option>
        <option value="annual">Annual</option>
      </select>
    </div>

    <!-- 📅 Bloque de días -->
    <div class="week-block">
      <span class="week-title">Weekly</span>
      <div class="days">
        <label class="day-box"><input type="checkbox" name="week_days[]" value="1"><span>1</span></label>
        <label class="day-box"><input type="checkbox" name="week_days[]" value="2"><span>2</span></label>
        <label class="day-box"><input type="checkbox" name="week_days[]" value="3"><span>3</span></label>
        <label class="day-box"><input type="checkbox" name="week_days[]" value="4"><span>4</span></label>
        <label class="day-box"><input type="checkbox" name="week_days[]" value="5"><span>5</span></label>
        <label class="day-box"><input type="checkbox" name="week_days[]" value="6"><span>6</span></label>
        <label class="day-box"><input type="checkbox" name="week_days[]" value="7"><span>7</span></label>
        <!-- ✅ Nueva opción independiente -->
        <label class="day-box"><input type="checkbox" name="week_days[]" value="one_time"><span>One Time</span></label>
      </div>
    </div>
  </div>
</div>

<!-- 14️⃣ Invoice Frequency -->
<div class="question-block" id="q14">
  <label for="Invoice_Frequency" class="question-label">14. Invoice Frequency</label>
  <select name="Invoice_Frequency" id="Invoice_Frequency">
    <option value="">-- Select an option --</option>
    <option value="15">Every 15 days</option>
    <option value="30">Every 30 days</option>
  </select>
</div>

<!-- 15️⃣ Contract Duration -->
<div class="question-block" id="q15">
  <label for="Contract_Duration" class="question-label">15. Contract Duration</label>
  <select name="Contract_Duration" id="Contract_Duration" class="duration-dropdown">
    <option value="">-- Select Duration --</option>
    <option value="6_months">6 Months</option>
    <option value="1_5_years">1.5 Years (18 Months)</option>
    <option value="1_year">1 Year</option>
    <option value="2_years">2 Years</option>
    <option value="3_years">3 Years</option>
    <option value="4_years">4 Years</option>
    <option value="5_years">5 Years</option>
  </select>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const requestType = document.getElementById("Request_Type");
  const durationSelect = document.getElementById("Contract_Duration");

  // Opciones completas
  const allOptions = [
    { value: "6_months", text: "6 Months" },
    { value: "1_year", text: "1 Year" },
    { value: "1_5_years", text: "1.5 Years (18 Months)" },
    { value: "2_years", text: "2 Years" },
    { value: "3_years", text: "3 Years" },
    { value: "4_years", text: "4 Years" },
    { value: "5_years", text: "5 Years" }
  ];

  function updateContractDuration() {
    const type = requestType?.value?.toLowerCase() || "";
    let filtered = [];

    if (type === "proposal") {
      // Para PROPOSAL → solo 6 meses, 1.5 años, 1 año y 2 años
      filtered = allOptions.filter(opt =>
        ["6_months", "1_5_years", "1_year", "2_years"].includes(opt.value)
      );
    } else if (type === "contract") {
      // Para CONTRACT → desde 1.5 años hasta 5 años
      filtered = allOptions.filter(opt =>
        ["1_5_years", "1_year", "2_years", "3_years", "4_years", "5_years"].includes(opt.value)
      );
    } else {
      filtered = allOptions; // Si aún no se selecciona tipo, mostrar todas
    }

    // Limpiar y volver a generar las opciones
    durationSelect.innerHTML = '<option value="">-- Select Duration --</option>';
    filtered.forEach(opt => {
      const option = document.createElement("option");
      option.value = opt.value;
      option.textContent = opt.text;
      durationSelect.appendChild(option);
    });
  }

  // Ejecutar al cargar
  updateContractDuration();

  // Escuchar cambios en la pregunta 2
  if (requestType) {
    requestType.addEventListener("change", updateContractDuration);
  }
});
</script>


<!-- ====================== -->
<!-- 🎨 Estilos originales -->
<!-- ====================== -->
<style>
.simple-frequency-inline {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 25px;
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 10px;
  padding: 15px 20px;
  max-width: 650px;
}
.period-dropdown {
  background-color: #001f54; /* Azul oscuro */
  color: white;
  font-weight: bold;
  padding: 10px 20px;
  border-radius: 8px;
  border: none;
  font-size: 15px;
  cursor: pointer;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}
.period-dropdown option {
  background-color: white;
  color: #001f54;
  font-weight: 500;
}
.week-block {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.week-title {
  font-weight: 600;
  color: #333;
  text-transform: capitalize;
  margin-bottom: 5px;
}
.days {
  display: flex;
  gap: 10px;
}
.day-box {
  position: relative;
  width: 36px;
  height: 36px;
  border: 2px solid #001f54;
  border-radius: 6px;
  color: #001f54;
  font-weight: bold;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease-in-out;
}
.day-box:hover {
  background-color: #e6ecf5;
}
.day-box input[type="checkbox"] {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  width: 100%;
  height: 100%;
  margin: 0;
}
.day-box input[type="checkbox"]:checked + span {
  background-color: #001f54; /* Azul oscuro al seleccionar */
  color: #fff;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}
</style>

<!-- ======================== -->
<!-- ⚙️ Script funcional -->
<!-- ======================== -->
<script>
  document.querySelectorAll('.day-box input[type="checkbox"]').forEach((checkbox, index, list) => {
    checkbox.addEventListener('change', () => {
      // Ignorar "One Time" en la lógica de los días secuenciales
      if (checkbox.value === 'one_time') return;

      const currentIndex = index;
      if (checkbox.checked) {
        // Selecciona todos los días anteriores (1→7)
        for (let i = 0; i <= currentIndex; i++) {
          if (list[i].value !== 'one_time') list[i].checked = true;
        }
      } else {
        // Deselecciona todos los días posteriores (1→7)
        for (let i = currentIndex + 1; i < list.length; i++) {
          if (list[i].value !== 'one_time') list[i].checked = false;
        }
      }
    });
  });
</script>
