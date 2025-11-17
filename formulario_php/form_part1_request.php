<!-- ================================ -->
<!-- 🧩 Section 1: Request Information -->
<!-- ================================ -->

<div class="section-title">Section 1: Request Information</div>

<!-- 1️⃣ Service Type -->
<div class="question-block" id="q1">
  <label for="Service_Type" class="question-label">1. Service Type*</label>
  <select name="Service_Type" id="Service_Type" required onchange="updateOptions()">
    <option value="">-- Select an option --</option>
    <option value="Janitorial">Janitorial</option>
    <option value="Hospitality">Hospitality</option>
  </select>
</div>

<!-- 2️⃣ Request Type -->
<div class="question-block" id="q2">
  <label for="Request_Type" class="question-label">2. Request Type*</label>
  <select name="Request_Type" id="Request_Type" required>
    <option value="">-- Select an option --</option>
    <!-- Opciones se generan dinámicamente en scripts_request.php -->
  </select>
</div>

<!-- 3️⃣ Priority -->
<div class="question-block" id="q3">
  <label for="Priority" class="question-label">3. Priority*</label>
  <select name="Priority" id="Priority" required>
    <option value="">-- Select an option --</option>
    <option value="Standard">Standard</option>
    <option value="Rush">Rush</option>
  </select>
</div>

<!-- 4️⃣ Requested Service -->
<div class="question-block" id="q4">
  <label for="Requested_Service" class="question-label">4. Requested Service</label>
  <select name="Requested_Service" id="Requested_Service" onchange="updateScopeOfWork()">
    <option value="">-- Select an option --</option>
    <!-- Opciones se cargan dinámicamente según Service Type -->
  </select>
</div>
