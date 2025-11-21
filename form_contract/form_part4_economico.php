<!-- ==================================== -->
<!-- 💰 Section 4: Economic Information -->
<!-- ==================================== -->

<div class="section-title">Section 4: Economic Information</div>

<!-- 16️⃣ Subcontractor Price -->
<div class="question-block" id="q16">
  <label for="Subcontractor_Price" class="question-label">16. Subcontractor Price</label>
  <input type="text" name="Subcontractor_Price" id="Subcontractor_Price" placeholder="Enter subcontractor price">
</div>

<!-- 17️⃣ Prime Quoted Price -->
<div class="question-block" id="q17">
  <label for="Prime_Quoted_Price" class="question-label">17. Prime Quoted Price</label>
  <input type="text" name="Prime_Quoted_Price" id="Prime_Quoted_Price" placeholder="Enter Prime quoted price">
</div>

<!-- 18️⃣ JANITORIAL SERVICES -->
<div class="question-block" id="q18">
  <label for="includeJanitorial" class="question-label">
    18. Janitorial Services
  </label>

  <select id="includeJanitorial" name="includeJanitorial" onchange="toggleSection18()">
    <option value="">-- Select an option --</option>
    <option value="No">No</option>
    <option value="Yes">Yes</option>
  </select>

  <div id="section18Container" style="display:none; margin-top:20px;">

    <!-- ADD / REMOVE -->
    <div style="margin-bottom:15px;">
      <button type="button" class="btn18 addRow18" onclick="addRow18()">➕ Add Row</button>
      <button type="button" class="btn18 removeRow18" onclick="removeRow18()">🗑 Remove</button>
    </div>

    <!-- TABLE -->
    <table class="service-table18">
      <thead>
        <tr>
          <th>Type of Services</th>
          <th>Service Time</th>
          <th>Frequency</th>
          <th>Service Description</th>
          <th>SUBTOTAL</th>
        </tr>
      </thead>

      <tbody id="table18body">

        <!-- ONLY ONE INITIAL ROW -->
        <tr>

          <!-- TYPE OF SERVICES (WITH WRITE...) -->
          <td>
            <select class="type18" onchange="toggleWriteOption18(this)">
              <option value="__write__">✍️ Write...</option>
              <option value="">-- Select Service --</option>

              <!-- JANITORIAL OPTIONS -->
              <option>Window Cleaning</option>
              <option>Window Tint</option>
              <option>Carpet Cleaning</option>
              <option>Painting</option>
              <option>Powerwashing Facade</option>
              <option>Furniture Upholstery Cleaning</option>
              <option>Restroom Cleaning</option>
            </select>

            <input type="text" class="write-field-18" style="display:none; margin-top:5px;" placeholder="Write service...">
          </td>

          <!-- SERVICE TIME -->
          <td>
            <select class="time18">
              <option value="">-- Select Time --</option>
              <option>1 Day</option>
              <option>1-2 Days</option>
              <option>3 Days</option>
              <option>4 Days</option>
              <option>5 Days</option>
              <option>6 Days</option>
              <option>7 Days</option>
            </select>
          </td>

          <!-- FREQUENCY -->
          <td>
            <select class="freq18">
              <option value="">-- Select Period --</option>
              <option>One Time</option>
              <option>Every 2 Weeks</option>
              <option>Every 3 Weeks</option>
              <option>Monthly</option>
              <option>Bimonthly</option>
              <option>Quarterly</option>
              <option>Every 4 Months</option>
              <option>Semiannual</option>
              <option>Annual</option>
            </select>
          </td>

          <!-- DESCRIPTION (FREE INPUT) -->
          <td>
            <input type="text" class="desc18" placeholder="Write description...">
          </td>

          <!-- SUBTOTAL (FREE INPUT) -->
          <td>
            <input type="number" step="0.01" class="subtotal18" placeholder="0.00" oninput="calcTotals18()">
          </td>

        </tr>

      </tbody>
    </table>

    <!-- TOTAL BOXES -->
    <div class="totals18-container">

      <div class="tot-box-18">
        <div class="tot-header-18">TOTAL</div>
        <input type="text" id="total18" readonly>
      </div>

      <div class="tot-box-18">
        <div class="tot-header-18">TAXES (8.25%)</div>
        <input type="text" id="taxes18" readonly>
      </div>

      <div class="tot-box-18">
        <div class="tot-header-18">GRAND TOTAL</div>
        <input type="text" id="grand18" readonly>
      </div>

    </div>

  </div>
</div>

<style>
  .service-table18 {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 14px;
  }

  .service-table18 th {
    background-color: #c00;
    color: #fff;
    padding: 8px;
    text-align: center;
  }

  .service-table18 td {
    border: 1px solid #ddd;
    padding: 8px;
  }

  .service-table18 select,
  .service-table18 input {
    width: 100%;
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .btn18 {
    padding: 6px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    margin-right: 10px;
  }

  .addRow18 { background-color:#008c4a; color:white; }
  .removeRow18 { background-color:#777; color:white; }

  .totals18-container {
    margin-top: 25px;
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
  }

  .tot-box-18 {
    width: 220px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background:white;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
  }

  .tot-header-18 {
    background-color:#c00;
    color:white;
    padding:8px;
    text-align:center;
    font-weight:bold;
  }

  .tot-box-18 input {
    width:100%;
    padding:10px;
    text-align:right;
    font-weight:bold;
    background:#f7f7f7;
    border:none;
  }
</style>

<script>
// SHOW/HIDE SECTION
function toggleSection18() {
  document.getElementById("section18Container").style.display =
    document.getElementById("includeJanitorial").value === "Yes"
    ? "block" : "none";
}

// WRITE OPTION
function toggleWriteOption18(select) {
  const input = select.parentElement.querySelector('.write-field-18');
  input.style.display = (select.value === "__write__") ? "block" : "none";
}

// ADD ROW
function addRow18() {
  const tbody = document.getElementById("table18body");
  const newRow = tbody.children[0].cloneNode(true);

  newRow.querySelectorAll("select, input").forEach(el => el.value = "");
  newRow.querySelector('.write-field-18').style.display = "none";

  tbody.appendChild(newRow);
}

// REMOVE ROW
function removeRow18() {
  const tbody = document.getElementById("table18body");
  if (tbody.children.length > 1) {
    tbody.lastElementChild.remove();
    calcTotals18();
  } else {
    alert("At least one row must remain.");
  }
}

// CALCULATE TOTALS
function calcTotals18() {
  let total = 0;

  document.querySelectorAll(".subtotal18").forEach(input => {
    const val = parseFloat(input.value);
    if (!isNaN(val)) total += val;
  });

  document.getElementById("total18").value = "$" + total.toFixed(2);

  const taxes = total * 0.0825;
  document.getElementById("taxes18").value = "$" + taxes.toFixed(2);

  document.getElementById("grand18").value = "$" + (total + taxes).toFixed(2);
}
</script>


<!-- 19️⃣ Hoodvent & Kitchen Cleaning -->
<div class="question-block" id="q19">
  <label for="includeKitchen" class="question-label">
    19. Hoodvent & Kitchen Cleaning
  </label>

  <select id="includeKitchen" name="includeKitchen" onchange="toggleSection19()">
    <option value="">-- Select an option --</option>
    <option value="No">No</option>
    <option value="Yes">Yes</option>
  </select>

  <div id="section19Container" style="display:none; margin-top:20px;">

    <!-- ADD / REMOVE -->
    <div style="margin-bottom:15px;">
      <button type="button" class="btn19 addRow19" onclick="addRow19()">➕ Add Row</button>
      <button type="button" class="btn19 removeRow19" onclick="removeRow19()">🗑 Remove</button>
    </div>

    <!-- TABLE -->
    <table class="service-table19">
      <thead>
        <tr>
          <th>Type of Services</th>
          <th>Service Time</th>
          <th>Frequency</th>
          <th>Service Description</th>
          <th>SUBTOTAL</th>
        </tr>
      </thead>

      <tbody id="table19body">

        <!-- ONLY ONE INITIAL ROW -->
        <tr>

          <!-- TYPE OF SERVICES (WITH WRITE...) -->
          <td>
            <select class="type19" onchange="toggleWriteOption(this)">
              <option value="__write__">✍️ Write...</option>
              <option value="">-- Select Service --</option>
              <option>Kitchen Cleaning</option>
              <option>Vent Hood</option>
              <option>Bar Cleaning</option>
              <option>Grease Trap Cleaning</option>
              <option>Restroom Cleaning</option>
              <option>Dinning Room Cleaning</option>
            </select>

            <input type="text" class="write-field" style="display:none; margin-top:5px;" placeholder="Write service...">
          </td>

          <!-- SERVICE TIME -->
          <td>
            <select class="time19">
              <option value="">-- Select Time --</option>
              <option>1 Day</option>
              <option>1-2 Days</option>
              <option>3 Days</option>
              <option>4 Days</option>
              <option>5 Days</option>
              <option>6 Days</option>
              <option>7 Days</option>
            </select>
          </td>

          <!-- FREQUENCY -->
          <td>
            <select class="freq19">
              <option value="">-- Select Period --</option>
              <option>One Time</option>
              <option>Every 2 Weeks</option>
              <option>Every 3 Weeks</option>
              <option>Monthly</option>
              <option>Bimonthly</option>
              <option>Quarterly</option>
              <option>Every 4 Months</option>
              <option>Semiannual</option>
              <option>Annual</option>
            </select>
          </td>

          <!-- DESCRIPTION (FREE INPUT) -->
          <td>
            <input type="text" class="desc19" placeholder="Write description...">
          </td>

          <!-- SUBTOTAL (FREE INPUT) -->
          <td>
            <input type="number" step="0.01" class="subtotal19" placeholder="0.00" oninput="calcTotals19()">
          </td>

        </tr>

      </tbody>
    </table>

    <!-- TOTAL BOXES -->
    <div class="totals19-container">

      <div class="tot-box">
        <div class="tot-header">TOTAL</div>
        <input type="text" id="total19" readonly>
      </div>

      <div class="tot-box">
        <div class="tot-header">TAXES (8.25%)</div>
        <input type="text" id="taxes19" readonly>
      </div>

      <div class="tot-box">
        <div class="tot-header">GRAND TOTAL</div>
        <input type="text" id="grand19" readonly>
      </div>

    </div>

  </div>
</div>

<style>
  .service-table19 {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 14px;
  }

  .service-table19 th {
    background-color: #c00;
    color: #fff;
    padding: 8px;
    text-align: center;
  }

  .service-table19 td {
    border: 1px solid #ddd;
    padding: 8px;
  }

  .service-table19 select,
  .service-table19 input {
    width: 100%;
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .btn19 {
    padding: 6px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    margin-right: 10px;
  }

  .addRow19 { background-color:#008c4a; color:white; }
  .removeRow19 { background-color:#777; color:white; }

  .totals19-container {
    margin-top: 25px;
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
  }

  .tot-box {
    width: 220px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background:white;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
  }

  .tot-header {
    background-color:#c00;
    color:white;
    padding:8px;
    text-align:center;
    font-weight:bold;
  }

  .tot-box input {
    width:100%;
    padding:10px;
    text-align:right;
    font-weight:bold;
    background:#f7f7f7;
    border:none;
  }
</style>

<script>
function toggleSection19() {
  document.getElementById("section19Container").style.display =
    document.getElementById("includeKitchen").value === "Yes"
    ? "block" : "none";
}

function toggleWriteOption(select) {
  const input = select.parentElement.querySelector('.write-field');

  if (select.value === "__write__") {
    input.style.display = "block";
    input.value = "";
  } else {
    input.style.display = "none";
  }
}

function addRow19() {
  const tbody = document.getElementById("table19body");
  const newRow = tbody.children[0].cloneNode(true);

  // Reset all inputs and selects in the cloned row
  newRow.querySelectorAll("select, input").forEach(el => el.value = "");
  newRow.querySelector('.write-field').style.display = "none";

  tbody.appendChild(newRow);
}

function removeRow19() {
  const tbody = document.getElementById("table19body");
  if (tbody.children.length > 1) {
    tbody.lastElementChild.remove();
    calcTotals19();
  } else {
    alert("At least one row must remain.");
  }
}

function calcTotals19() {
  let total = 0;

  document.querySelectorAll(".subtotal19").forEach(input => {
    const val = parseFloat(input.value);
    if (!isNaN(val)) total += val;
  });

  document.getElementById("total19").value = "$" + total.toFixed(2);

  const taxes = total * 0.0825;
  document.getElementById("taxes19").value = "$" + taxes.toFixed(2);

  document.getElementById("grand19").value = "$" + (total + taxes).toFixed(2);
}
</script>


<!-- 20️⃣ Include Staff Section -->
<div class="question-block" id="q20">
  <label for="includeStaff" class="question-label">
    20. Include Staff?
  </label>
  <select id="includeStaff" name="includeStaff" onchange="toggleStaffTables()">
    <option value="">-- Select an option --</option>
    <option value="No">No</option>
    <option value="Yes">Yes</option>
  </select>

  <div id="staffTablesContainer" style="display:none; margin-top: 10px;"></div>
</div>

<style>
  .staff-category {
    margin-top: 20px;
    border-top: 3px solid #c00;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  .staff-header {
    background-color: #c00;
    color: #fff;
    padding: 10px 15px;
    font-size: 15px;
    font-weight: bold;
    text-transform: uppercase;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
  }

  .staff-header:hover {
    background-color: #a00000;
  }

  .toggle-icon {
    font-weight: bold;
    font-size: 18px;
    transition: transform 0.3s ease;
  }

  .staff-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 5px;
    font-size: 14px;
    display: none; /* 🔹 Ocultas por defecto */
    background-color: #fff;
  }

  .staff-table th {
    background-color: #c00;
    color: white;
    padding: 6px;
    text-align: center;
  }

  .staff-table tr {
    background-color: #fff;
  }

  .staff-table td {
    padding: 8px;
    text-align: center;
  }

  .readonly {
    background-color: #f9f9f9;
  }

  .expanded .staff-table {
    display: table;
  }

  .expanded .toggle-icon {
    transform: rotate(180deg);
  }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const container = document.getElementById("staffTablesContainer");

  window.toggleStaffTables = function () {
    const select = document.getElementById("includeStaff");
    if (select.value === "Yes") {
      container.style.display = "block";
      if (container.childElementCount === 0) loadStaffSections();
    } else {
      container.innerHTML = "";
      container.style.display = "none";
    }
  };

  function loadStaffSections() {
    container.innerHTML = `
      ${createCategory("HOUSEKEEPING", [
        "Housekeeper / GRA",
        "Housekeeping Inspector / GRA Supervisor",
        "Laundry Attendant",
        "Houseman",
        "Public Areas Attendant",
        "Lobby Attendant",
        "Lobby Runner (AM/PM/Overnight)",
        "Turndown Attendant"
      ])}

      ${createCategory("FOOD & BEVERAGE", [
        "Dishwasher",
        "Cook (Main Kitchen / Back Table / Cool Water)",
        "Prep Cook (Main Kitchen / Back Table / Cool Water)",
        "Busser (Restaurant / Banquet)",
        "Runner (Food / Restaurant / Banquet)",
        "Server (Restaurant / Banquet)",
        "Host / Hostess",
        "Barista",
        "Bartender",
        "Barback",
        "Banquet Houseman",
        "Cashier"
      ])}

      ${createCategory("MAINTENANCE", [
        "Maintenance Helper",
        "Movers"
      ])}

      ${createCategory("RECREATION & POOL", [
        "Pool Attendant",
        "Recreation Slide Attendant",
        "Recreation Supervisor"
      ])}

      ${createCategory("SECURITY", [
        "Security Guard (Noncommissioned)"
      ])}

      ${createCategory("VALET PARKING", [
        "Valet Attendant (AM/PM/Overnight)"
      ])}

      ${createCategory("FRONT DESK", [
        "Front Desk Attendant",
        "Night Auditor"
      ])}
    `;
  }

  function createCategory(title, positions) {
    const rows = positions
      .map(pos => {
        const slug = slugify(title + "_" + pos);
        return `
          <tr>
            <td>${pos}</td>
            <td><input type="number" name="base_${slug}" step="0.01" placeholder="0.00" oninput="updateBillRate('${slug}')"></td>
            <td><input type="number" name="increase_${slug}" step="0.01" placeholder="0%" oninput="updateBillRate('${slug}')"></td>
            <td><input type="text" name="bill_${slug}" class="readonly" readonly placeholder="$0.00"></td>
          </tr>
        `;
      })
      .join("");

    return `
      <div class="staff-category">
        <div class="staff-header" onclick="this.parentElement.classList.toggle('expanded')">
          ${title}
          <span class="toggle-icon">▼</span>
        </div>
        <table class="staff-table">
          <thead>
            <tr>
              <th>Position</th>
              <th>Base Rate</th>
              <th>% Increase</th>
              <th>Bill Rate</th>
            </tr>
          </thead>
          <tbody>${rows}</tbody>
        </table>
      </div>
    `;
  }

  window.updateBillRate = function (slug) {
    const base = parseFloat(document.querySelector(`[name="base_${slug}"]`)?.value) || 0;
    const inc = parseFloat(document.querySelector(`[name="increase_${slug}"]`)?.value) || 0;
    const bill = document.querySelector(`[name="bill_${slug}"]`);
    const total = base + (base * inc / 100);
    bill.value = total > 0 ? `$${total.toFixed(2)}` : "$0.00";
  };

  function slugify(text) {
    return text.toLowerCase().replace(/[’']/g, "").replace(/[^a-z0-9]+/g, "_").replace(/^_+|_+$/g, "");
  }
});
</script>
