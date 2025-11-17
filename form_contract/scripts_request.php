<script>
// ===========================================================
// 🧩 scripts_request.php — Motor dinámico con referencias q1–q28
// ===========================================================

// =========================
// 🔹 CONFIGURACIÓN GLOBAL
// =========================
const formConfig = {
  requestTypeOptions: [
    { category: "Janitorial",  requestTypes: ["Contract", "Proposal"] },
    { category: "Hospitality", requestTypes: ["Quote", "Contract", "Proposal", "Kitchen & Hoodvent JWO"] },
  ],

  // === Reglas dinámicas (type + service)
formConditions: [
    // === HOSPITALITY: QUOTE ===
    { type: "Quote", service: "Kitchen Cleaning",               hide: [18,20,21,22,23,24,28] },
    { type: "Quote", service: "Hood Vent",                      hide: [19,20,21,22,23,24,28] },
    { type: "Quote", service: "Kitchen Cleaning & Hood Vent",   hide: [20,21,22,23,24,28] },

    // === HOSPITALITY: CONTRACT ===
    { type: "Contract", service: "Kitchen Cleaning",            hide: [12,18,20,25] },
    { type: "Contract", service: "Hood Vent",                   hide: [12,19,20,25] },
    { type: "Contract", service: "Kitchen Cleaning & Hood Vent",hide: [12,25] },
    { type: "Contract", service: "Staff",                       hide: [12,16,17,18,19,25] },

    // === HOSPITALITY: PROPOSAL ===
    { type: "Proposal", service: "Kitchen Cleaning",            hide: [12,18,21,20,23,24,25,29] },
    { type: "Proposal", service: "Hood Vent",                   hide: [12,19,20,22,23,24,25,29] },
    { type: "Proposal", service: "Kitchen Cleaning & Hood Vent",hide: [12,21,22,23,24,25,29] },
    { type: "Proposal", service: "Staff",                       hide: [12,16,17,18,19,21,22,23,24,25] },

    // === HOSPITALITY: KITCHEN & HOODVENT JWO ===
    { type: "Kitchen & Hoodvent JWO", service: "Kitchen Cleaning",             hide: [12,18,20,25] },
    { type: "Kitchen & Hoodvent JWO", service: "Hood Vent",                    hide: [12,19,20,25] },
    { type: "Kitchen & Hoodvent JWO", service: "Kitchen Cleaning & Hood Vent", hide: [12,25,20,29] },

    // === JANITORIAL: CONTRACT (comodín) ===
    { type: "Contract", service: "*", hide: [] },

    // === JANITORIAL: PROPOSAL STAFF ===
    { type: "Proposal", service: "Staff",   hide: [18,19,21,22,23,24,25,29] },

    // === JANITORIAL: PROPOSAL PACKAGE ===
    { type: "Proposal", service: "Package", hide: [18,19,20] },
  ],
};


// ===========================================================
// 🔹 UTILIDADES GENERALES
// ===========================================================
function normalize(str) {
  return (str || "").replace(/&amp;/g, "&").replace(/\s+/g, " ").trim().toLowerCase();
}

// Encuentra la sección de una pregunta
function findSectionEl(num) {
  return (
    document.getElementById(`q${num}`) ||                // ✅ nuevo formato estándar
    document.querySelector(`[data-question="${num}"]`) || // soporte alternativo
    document.getElementById(`section${num}`) ||
    document.getElementById(`label${num}`)?.parentElement ||
    document.getElementById(`input${num}`)?.parentElement
  );
}

// Ocultar campo (y limpiar sus inputs internos)
function hideField(num) {
  const section = findSectionEl(num);
  if (section) {
    section.style.display = "none";
    section.querySelectorAll("input, select, textarea").forEach(el => {
      if (el.type === "checkbox" || el.type === "radio") el.checked = false;
      else el.value = "";
    });
  }
}

// Mostrar campo
function showField(num) {
  const section = findSectionEl(num);
  if (section) section.style.display = "block";
}

// ===========================================================
// 🔹 P1 → P2 (Service Type → Request Type)
// ===========================================================
function updateRequestTypeOptions() {
  const serviceTypeEl = document.getElementById("Service_Type");
  const requestTypeEl = document.getElementById("Request_Type");
  const requestedServiceEl = document.getElementById("Requested_Service");
  if (!serviceTypeEl || !requestTypeEl || !requestedServiceEl) return;

  requestTypeEl.innerHTML = '<option value="">-- Select an option --</option>';
  requestedServiceEl.innerHTML = '<option value="">-- Select an option --</option>';

  const cfg = formConfig.requestTypeOptions.find(c => c.category === serviceTypeEl.value);
  if (!cfg) return;

  cfg.requestTypes.forEach(rt => {
    const opt = document.createElement("option");
    opt.value = rt;
    opt.textContent = rt;
    requestTypeEl.appendChild(opt);
  });
}

// ===========================================================
// 🔹 P2 → P4 (Request Type → Requested Service)
// ===========================================================
function updateRequestedServiceOptions() {
  const st = document.getElementById("Service_Type")?.value;
  const rt = document.getElementById("Request_Type")?.value;
  const rs = document.getElementById("Requested_Service");
  if (!st || !rt || !rs) return;

  rs.innerHTML = '<option value="">-- Select an option --</option>';

  let services = [];
  if (st === "Hospitality") {
    if (rt === "Quote" || rt === "Kitchen & Hoodvent JWO")
      services = ["Kitchen Cleaning", "Hood Vent", "Kitchen Cleaning & Hood Vent"];
    else if (rt === "Contract" || rt === "Proposal")
      services = ["Kitchen Cleaning", "Hood Vent", "Kitchen Cleaning & Hood Vent", "Staff"];
  } else if (st === "Janitorial") {
    if (rt === "Contract")
      services = ["Schools and Universities", "Corporate Offices", "Airports", "Churches", "Stadiums and Sports Arenas", "Warehouses and Industrial Facilities"];
    else if (rt === "Proposal")
      services = ["Staff", "Package"];
  }

  services.forEach(s => {
    const opt = document.createElement("option");
    opt.value = s;
    opt.textContent = s;
    rs.appendChild(opt);
  });
}

// ===========================================================
// 🔹 P12 Site Visit Toggle
// ===========================================================
function toggleSiteVisitField() {
  const reqType = document.getElementById("Request_Type")?.value;
  const label = document.getElementById("siteVisitLabel");
  const select = document.getElementById("siteVisitSelect");
  if (!label || !select) return;

  if (reqType === "Quote") {
    label.style.display = "block";
    select.style.display = "block";
  } else {
    label.style.display = "none";
    select.style.display = "none";
    select.value = "";
  }
}

// ===========================================================
// 🔹 Evaluación de reglas (motor principal)
// ===========================================================
function evaluateRules() {
  const reqType = normalize(document.getElementById("Request_Type")?.value);
  const reqService = normalize(document.getElementById("Requested_Service")?.value);

  // Mostrar todo (12–29)
  for (let i = 12; i <= 29; i++) showField(i);

  // Buscar coincidencia exacta
  let match = formConfig.formConditions.find(r =>
    normalize(r.type) === reqType && normalize(r.service) === reqService
  );

  // Si no hay match exacto, busca comodín (service="*")
  if (!match) {
    match = formConfig.formConditions.find(r =>
      normalize(r.type) === reqType && (r.service === "*" || !r.service)
    );
  }

  // Aplicar ocultamientos
  if (match && match.hide?.length) match.hide.forEach(h => hideField(h));

  // Condición global: ocultar P15 si P2 = Quote o Kitchen & Hoodvent JWO
  if (reqType === "quote" || reqType === "kitchen & hoodvent jwo") hideField(15);
}

// ===========================================================
// 🔹 Inicialización
// ===========================================================
document.addEventListener("DOMContentLoaded", () => {
  const serviceTypeEl = document.getElementById("Service_Type");
  const requestTypeEl = document.getElementById("Request_Type");
  const requestedServiceEl = document.getElementById("Requested_Service");

  if (serviceTypeEl)
    serviceTypeEl.addEventListener("change", () => {
      updateRequestTypeOptions();
      updateRequestedServiceOptions();
      toggleSiteVisitField();
      evaluateRules();
    });

  if (requestTypeEl)
    requestTypeEl.addEventListener("change", () => {
      updateRequestedServiceOptions();
      toggleSiteVisitField();
      evaluateRules();
    });

  if (requestedServiceEl)
    requestedServiceEl.addEventListener("change", () => evaluateRules());

  // Inicialización al cargar
  updateRequestTypeOptions();
  updateRequestedServiceOptions();
  toggleSiteVisitField();
  evaluateRules();
});
</script>
