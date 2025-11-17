<!-- ================================ -->
<!-- 🧩 Section 1: Commission Form    -->
<!-- ================================ -->

<div class="section-title">Section 1: Commission Form</div>

<!-- 1️⃣ Job / Work Order Number -->
<div class="question-block" id="cc1">
  <label for="Job_Number" class="question-label">1. Job / Work Order Number*</label>
  <input type="text" id="Job_Number" name="Job_Number" required placeholder="Enter Job or Work Order number">
</div>

<!-- 2️⃣ Salesperson -->
<div class="question-block" id="cc2">
  <label for="Salesperson" class="question-label">2. Salesperson*</label>
  <input type="text" id="Salesperson" name="Salesperson" required placeholder="Enter salesperson name">
</div>

<!-- 3️⃣ Order Paid -->
<div class="question-block" id="cc3">
  <label for="Order_Paid" class="question-label">3. Order Paid?*</label>
  <select id="Order_Paid" name="Order_Paid" required>
    <option value="">-- Select an option --</option>
    <option value="Yes">Yes</option>
    <option value="No">No</option>
  </select>
</div>

<!-- 4️⃣ Bill Rate -->
<div class="question-block" id="cc4">
  <label for="Bill_Rate" class="question-label">4. Bill Rate*</label>
  <input type="number" id="Bill_Rate" name="Bill_Rate" required step="0.01" placeholder="Example: 3500.00">
</div>

<!-- 5️⃣ Operating Cost -->
<div class="question-block" id="cc5">
  <label for="Operating_Cost" class="question-label">5. Operating Cost*</label>
  <input type="number" id="Operating_Cost" name="Operating_Cost" required step="0.01" placeholder="Example: 2100.00">
</div>

<!-- 6️⃣ Supplies -->
<div class="question-block" id="cc6">
  <label for="Supplies" class="question-label">6. Supplies*</label>
  <input type="number" id="Supplies" name="Supplies" required step="0.01" placeholder="Example: 250.00">
</div>

<!-- 7️⃣ Operational & Fixed Expenses -->
<div class="question-block" id="cc7">
  <label for="Fixed_Expenses" class="question-label">7. Operational &amp; Fixed Expenses</label>
  <input type="number" id="Fixed_Expenses" name="Fixed_Expenses" readonly>
</div>

<!-- 8️⃣ Net Utility -->
<div class="question-block" id="cc8">
  <label for="Net_Utility" class="question-label">8. Net Utility</label>
  <input type="number" id="Net_Utility" name="Net_Utility" readonly>
</div>

<!-- 9️⃣ Commission Percentage (Slider) -->
<div class="question-block" id="cc9">
  <label for="Commission_Percentage" class="question-label">9. Commission Percentage (0% - 30%)</label>

  <input
    type="range"
    id="Commission_Percentage"
    name="Commission_Percentage"
    min="0"
    max="30"
    step="1"
    value="0"
    oninput="document.getElementById('Commission_Label').innerText = this.value + '%';"
  >

  <span id="Commission_Label">0%</span>
</div>

<!-- 🔟 Total Commission -->
<div class="question-block" id="cc10">
  <label for="Commission_Amount" class="question-label">10. Total Commission Assigned to Salesperson</label>
  <input type="number" id="Commission_Amount" name="Commission_Amount" readonly>
</div>
