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

<!-- 18️⃣ Include Hood Vent Cleaning -->
<div class="question-block" id="q18">
  <label for="includeHoodVent" id="hoodVentLabel" class="question-label">
    18. Include Hood Vent Cleaning?
  </label>
  <select id="includeHoodVent" name="includeHoodVent" onchange="toggleHoodVentTables()">
    <option value="">-- Select an option --</option>
    <option value="No">No</option>
    <option value="Yes">Yes</option>
  </select>

  <div id="hoodVentTablesContainer" style="display:none; margin-top:10px;"></div>

  <div style="margin-top:10px;">
    <button type="button" id="addKitchenBtn" onclick="addKitchenTable()" style="
      display:none;
      padding:6px 14px;
      background-color:#c00;
      color:white;
      border:none;
      border-radius:6px;
      cursor:pointer;
      margin-right:10px;">
      ➕ Add Kitchen
    </button>
  </div>
</div>

<style>
  .kitchen-block {
    border-top: 3px solid #c00;
    border-radius: 8px;
    margin-top: 15px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  .kitchen-header {
    background-color: #c00;
    color: #fff;
    padding: 10px 15px;
    font-weight: bold;
    text-transform: uppercase;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .kitchen-header:hover {
    background-color: #a00000;
  }

  .toggle-icon {
    font-weight: bold;
    font-size: 18px;
    transition: transform 0.3s ease;
  }

  .expanded .toggle-icon {
    transform: rotate(180deg);
  }

  .kitchen-content {
    display: none;
    background-color: #fff;
    padding: 10px;
  }

  .expanded .kitchen-content {
    display: block;
  }

  .kitchen-table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
    font-size: 14px;
    table-layout: fixed;
  }

  .kitchen-table th {
    background-color: #c00;
    color: #fff;
    padding: 6px;
  }

  .kitchen-table td {
    padding: 6px;
    border-top: 1px solid #ddd;
  }

  .amount, .total {
    font-weight: bold;
  }
</style>

<script>
let kitchenCount = 0;

function toggleHoodVentTables() {
  const select = document.getElementById("includeHoodVent");
  const container = document.getElementById("hoodVentTablesContainer");
  const addBtn = document.getElementById("addKitchenBtn");

  if (select.value === "Yes") {
    container.style.display = "block";
    addBtn.style.display = "inline-block";
    if (container.childElementCount === 0) addKitchenTable();
  } else {
    container.innerHTML = "";
    container.style.display = "none";
    addBtn.style.display = "none";
    kitchenCount = 0;
  }
}

function addKitchenTable() {
  kitchenCount++;
  const container = document.getElementById("hoodVentTablesContainer");

  const kitchenName =
    kitchenCount === 1 ? "Main Kitchen" :
    kitchenCount === 2 ? "Pool Kitchen" :
    kitchenCount === 3 ? "Third Kitchen" :
    "Kitchen " + kitchenCount;

  const kitchenBlock = document.createElement("div");
  kitchenBlock.classList.add("kitchen-block");
  kitchenBlock.innerHTML = `
    <div class="kitchen-header" onclick="this.parentElement.classList.toggle('expanded')">
      ${kitchenName}
      <span class="toggle-icon">▼</span>
    </div>
    <div class="kitchen-content">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
        <div>
          <label style="font-weight:bold; margin-right:8px;">Kitchen Name:</label>
          <input type="text" name="kitchen_name_${kitchenCount}" placeholder="${kitchenName}" 
                 style="width:250px; border:1px solid #ccc; padding:6px; border-radius:6px;">
        </div>
        <div>
          <button type="button" onclick="addServiceRow(this)" style="
            background-color:#008c4a;
            color:white;
            border:none;
            padding:5px 10px;
            border-radius:4px;
            margin-right:8px;
            cursor:pointer;">➕ Add Row</button>
          <button type="button" onclick="removeKitchenTable(this)" style="
            background-color:#777;
            color:white;
            border:none;
            padding:5px 10px;
            border-radius:4px;
            cursor:pointer;">🗑 Remove</button>
        </div>
      </div>

      <table class="kitchen-table">
        <thead>
          <tr>
            <th>Services</th>
            <th>Qty</th>
            <th>Unit Amount</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="text" name="service_${kitchenCount}_1" value="Hood Cleaning" readonly></td>
            <td><input type="number" name="qty_${kitchenCount}_1" value="0" min="0" oninput="updateAmount(this)"></td>
            <td><input type="number" name="unit_${kitchenCount}_1" value="850" min="0" oninput="updateAmount(this)"></td>
            <td><input type="text" class="amount" name="amount_${kitchenCount}_1" readonly></td>
          </tr>
          <tr>
            <td><input type="text" name="service_${kitchenCount}_2" value="Access Panel Cleaning" readonly></td>
            <td><input type="number" name="qty_${kitchenCount}_2" value="0" min="0" oninput="updateAmount(this)"></td>
            <td><input type="number" name="unit_${kitchenCount}_2" value="350" min="0" oninput="updateAmount(this)"></td>
            <td><input type="text" class="amount" name="amount_${kitchenCount}_2" readonly></td>
          </tr>
          <tr>
            <td><input type="text" name="service_${kitchenCount}_3" value="PCU Filter Replacement" readonly></td>
            <td><input type="number" name="qty_${kitchenCount}_3" value="0" min="0" oninput="updateAmount(this)"></td>
            <td><input type="number" name="unit_${kitchenCount}_3" value="1000" min="0" oninput="updateAmount(this)"></td>
            <td><input type="text" class="amount" name="amount_${kitchenCount}_3" readonly></td>
          </tr>
          <tr>
            <td><input type="text" name="service_${kitchenCount}_4" placeholder="Add custom service..."></td>
            <td><input type="number" name="qty_${kitchenCount}_4" value="0" min="0" oninput="updateAmount(this)"></td>
            <td><input type="number" name="unit_${kitchenCount}_4" value="0" min="0" oninput="updateAmount(this)"></td>
            <td><input type="text" class="amount" name="amount_${kitchenCount}_4" readonly></td>
          </tr>
          <tr style="background-color:#f5f5f5;">
            <td colspan="3" style="text-align:right; font-weight:bold;">TOTAL</td>
            <td><input type="text" class="total" name="total_${kitchenCount}" readonly></td>
          </tr>
        </tbody>
      </table>
    </div>
  `;
  container.appendChild(kitchenBlock);
}

function addServiceRow(button) {
  const table = button.closest(".kitchen-content").querySelector("table");
  const tbody = table.querySelector("tbody");
  const newRow = document.createElement("tr");
  newRow.innerHTML = `
    <td><input type="text" placeholder="New service..."></td>
    <td><input type="number" value="0" min="0" oninput="updateAmount(this)"></td>
    <td><input type="number" value="0" min="0" oninput="updateAmount(this)"></td>
    <td><input type="text" class="amount" readonly></td>`;
  const totalRow = tbody.querySelector("tr:last-child");
  tbody.insertBefore(newRow, totalRow);
}

function removeKitchenTable(button) {
  const kitchenBlock = button.closest(".kitchen-block");
  if (kitchenBlock && confirm("Remove this kitchen section?")) {
    kitchenBlock.remove();
  }
}

function updateAmount(input) {
  const row = input.closest("tr");
  const qty = parseFloat(row.querySelectorAll("input")[1].value) || 0;
  const unit = parseFloat(row.querySelectorAll("input")[2].value) || 0;
  const amount = row.querySelector(".amount");
  amount.value = "$" + (qty * unit).toFixed(2);
  recalcTableTotal(row.closest("table"));
}

function recalcTableTotal(table) {
  let total = 0;
  table.querySelectorAll(".amount").forEach(a => {
    const val = parseFloat(a.value.replace("$", "")) || 0;
    total += val;
  });
  table.querySelector(".total").value = "$" + total.toFixed(2);
}
</script>


<!-- 19️⃣ Include Kitchen Equipment Section -->
<div class="question-block" id="q19">
  <label for="includeKitchen" class="question-label">
    19. Include Kitchen Equipment?
  </label>

  <select id="includeKitchen" name="includeKitchen" onchange="toggleKitchenTables()">
    <option value="">-- Select an option --</option>
    <option value="No">No</option>
    <option value="Yes">Yes</option>
  </select>

  <div id="kitchenTablesContainer" style="display:none; margin-top: 10px;"></div>
</div>

<style>
  .kitchen-category {
    margin-top: 20px;
    border-top: 3px solid #c00;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  .kitchen-header {
    background-color: #c00;
    color: white;
    padding: 10px 15px;
    font-size: 15px;
    font-weight: bold;
    text-transform: uppercase;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
  }

  .kitchen-header:hover {
    background-color: #a00000;
  }

  .toggle-icon {
    font-weight: bold;
    font-size: 18px;
    transition: transform 0.3s ease;
  }

  .kitchen-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 5px;
    font-size: 14px;
    display: none;
    background-color: #fff;
  }

  .kitchen-table th {
    background-color: #c00;
    color: white;
    padding: 6px;
    text-align: center;
  }

  .kitchen-table td {
    padding: 8px;
    text-align: center;
  }

  .expanded .kitchen-table {
    display: table;
  }

  .expanded .toggle-icon {
    transform: rotate(180deg);
  }

  .surface-check {
    transform: scale(1.3);
    cursor: pointer;
  }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const container = document.getElementById("kitchenTablesContainer");

  window.toggleKitchenTables = function () {
    const select = document.getElementById("includeKitchen");
    if (select.value === "Yes") {
      container.style.display = "block";
      if (container.childElementCount === 0) loadKitchenSections();
    } else {
      container.innerHTML = "";
      container.style.display = "none";
    }
  };

  function loadKitchenSections() {
    container.innerHTML = `
      ${createCategory("TOTAL SURFACE", `
        <table class="kitchen-table">
          <thead><tr><th>Area</th><th>Square Feet</th></tr></thead>
          <tbody><tr><td>Total Surface</td><td><input type="number" step="1" name="total_surface" placeholder="0"></td></tr></tbody>
        </table>
        <table class="kitchen-table" style="margin-top:10px;">
          <thead><tr><th>Surface Element</th><th>Include</th></tr></thead>
          <tbody>
            <tr><td style="text-align:left;">Floor (Piso)</td><td><input type="checkbox" name="include_floor" class="surface-check"></td></tr>
            <tr><td style="text-align:left;">Wall (Pared)</td><td><input type="checkbox" name="include_wall" class="surface-check"></td></tr>
            <tr><td style="text-align:left;">Ceiling (Techo)</td><td><input type="checkbox" name="include_ceiling" class="surface-check"></td></tr>
          </tbody>
        </table>
      `)}

      ${createCategory("HEAT", createRows(["Stoves","Ovens","Fryers","Griddle / Grill","Salamander / Broiler"]))}
      ${createCategory("COLD", createRows(["Refrigerators","Freezers","Beverage Coolers"]))}
      ${createCategory("PREPARATION", createRows(["Food Processors","Industrial Blenders","Mixers","Cutters / Slicers"]))}
      ${createCategory("SERVICE", createRows(["Hot Tables","Service Carts","Food Warmers (Bain-Marie)"]))}
      ${createCategory("WASHING", createRows(["Industrial Dishwashers","Grease Traps (external cleaning only)","Sinks and Wash Stations"]))}
      ${createCategory("ELECTRONIC EXTRAS", createRows(["Touch Screens","Speakers","Electronic Controls","Digital Clocks / Order Systems"]))}
    `;
  }

  function createCategory(title, tableHTML) {
    return `
      <div class="kitchen-category">
        <div class="kitchen-header" onclick="this.parentElement.classList.toggle('expanded')">
          ${title}
          <span class="toggle-icon">▼</span>
        </div>
        ${tableHTML}
      </div>
    `;
  }

  function createRows(items) {
    return `
      <table class="kitchen-table">
        <thead><tr><th>Equipment</th><th>Quantity</th></tr></thead>
        <tbody>
          ${items.map(item => {
            const slug = slugify(item);
            return `<tr><td>${item}</td><td><input type="number" name="qty_${slug}" min="0" step="1" placeholder="0"></td></tr>`;
          }).join("")}
        </tbody>
      </table>
    `;
  }

  function slugify(text) {
    return text.toLowerCase().replace(/[’']/g, "").replace(/[^a-z0-9]+/g, "_").replace(/^_+|_+$/g, "");
  }
});
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
