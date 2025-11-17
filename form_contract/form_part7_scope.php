<!-- ======================================= -->
<!-- 📋 Section 7: Scope of Work (Dynamic) -->
<!-- ======================================= -->

<div class="section-title">Section 7: Scope of Work</div>

<!-- 28️⃣ Scope Of Work -->
<div class="question-block" id="q28">
  <label for="Scope_Of_Work" class="question-label">
    28. Scope of Work (select applicable tasks)
  </label>

  <!-- Contenedor dinámico -->
  <div id="scopeOfWorkContainer" class="checkbox-group" style="display:none;"></div>
</div>

<script>
// ===============================
// 🔹 MAPA DE OPCIONES POR SERVICIO
// ===============================
const scopeOfWorkOptions = {
  "Schools and Universities": [
    "Classrooms and Lecture Halls",
    "Restrooms",
    "Offices and Administrative Areas",
    "Common Areas and Hallways",
    "Cafeterias, Dining Halls, and Kitchens",
    "Gymnasiums, Auditoriums, and Sports Facilities",
    "Libraries and Study Areas",
    "Laboratories and Specialized Areas",
    "Exterior Cleaning and Grounds",
    "Floors and Carpets",
    "Waste and Recycling Areas"
  ],
  "Corporate Offices": [
    "Workstations and Offices",
    "Conference Rooms and Meeting Spaces",
    "Reception and Lobby Areas",
    "Restrooms",
    "Break Rooms and Office Kitchens",
    "Common Areas and Hallways",
    "Floors and Carpets",
    "Waste and Recycling Rooms",
    "Exterior Entryways and Sidewalks"
  ],
  "Airports": [
    "Terminals and Gate Areas",
    "Security Checkpoints",
    "Baggage Claim Areas",
    "Restrooms",
    "Lounges and VIP Areas",
    "Food Courts and Concessions",
    "Common Circulation Spaces and Concourses",
    "Baggage Handling and Operational Zones",
    "Exterior Drop-off and Pick-up Zones",
    "Aircraft Hangars and Maintenance Areas"
  ],
  "Churches": [
    "Sanctuary and Worship Areas",
    "Classrooms and Meeting Rooms",
    "Restrooms",
    "Fellowship Halls and Event Spaces",
    "Kitchens and Food Preparation Areas",
    "Offices and Administrative Areas",
    "Common Areas and Hallways",
    "Exterior Entryways and Grounds"
  ],
  "Stadiums and Sports Arenas": [
    "Seating Areas and Grandstands",
    "Concessions and Food Service Areas",
    "Restrooms",
    "VIP Suites, Lounges, and Hospitality Areas",
    "Locker Rooms and Player Facilities",
    "Corridors, Concourses, and Common Areas",
    "Exterior and Grounds (including plazas and parking)",
    "Waste and Recycling Zones"
  ],
  "Warehouses and Industrial Facilities": [
    "Warehouse Floors and Storage Areas",
    "Loading Docks and Receiving Zones",
    "Restrooms"
  ],
  "Kitchen Cleaning": [
    "Food Preparation Surfaces",
    "Equipment (exteriors of ovens, grills, fryers, etc.)",
    "Sinks and Dishwashing Areas",
    "Kitchen Floors",
    "Storage Areas",
    "Trash and Recycling Zones"
  ],
  "Hood Vent": [
    "Hood and Canopy",
    "Filters",
    "Ductwork",
    "Exhaust Fans and Motors",
    "Floors, Walls, and Surrounding Areas"
  ],
  // ✅ Nueva opción combinada
  "Kitchen Cleaning & Hood Vent": [
    "Food Preparation Surfaces",
    "Equipment (exteriors of ovens, grills, fryers, etc.)",
    "Sinks and Dishwashing Areas",
    "Kitchen Floors",
    "Storage Areas",
    "Trash and Recycling Zones",
    "Hood and Canopy",
    "Filters",
    "Ductwork",
    "Exhaust Fans and Motors",
    "Floors, Walls, and Surrounding Areas"
  ]
};

// ===============================
// 🔹 FUNCIÓN PRINCIPAL
// ===============================
document.addEventListener("DOMContentLoaded", () => {
  const serviceSelect = document.getElementById("Requested_Service");
  const scopeContainer = document.getElementById("scopeOfWorkContainer");

  // Escucha cambios en la pregunta 4
  serviceSelect.addEventListener("change", function () {
    const selectedService = this.value;
    scopeContainer.innerHTML = ""; // limpiar contenido previo

    if (scopeOfWorkOptions[selectedService]) {
      scopeContainer.style.display = "grid";
      scopeContainer.style.gridTemplateColumns = "repeat(auto-fit, minmax(250px, 1fr))";
      scopeContainer.style.gap = "10px";
      scopeContainer.style.marginTop = "10px";

      // Crear los checkboxes dinámicos
      scopeOfWorkOptions[selectedService].forEach(item => {
        const label = document.createElement("label");
        label.style.display = "flex";
        label.style.alignItems = "center";
        label.style.gap = "8px";
        label.style.padding = "6px 10px";
        label.style.background = "#f9f9f9";
        label.style.border = "1px solid #ddd";
        label.style.borderRadius = "6px";
        label.style.cursor = "pointer";
        label.style.fontSize = "14px";

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.name = "Scope_Of_Work[]";
        checkbox.value = item;
        checkbox.style.transform = "scale(1.3)";
        checkbox.style.cursor = "pointer";

        label.appendChild(checkbox);
        label.appendChild(document.createTextNode(item));
        scopeContainer.appendChild(label);
      });
    } else {
      // Si no hay coincidencia, ocultar contenedor
      scopeContainer.style.display = "none";
    }
  });
});
</script>
