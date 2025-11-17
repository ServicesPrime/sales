<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
/**
 * enviar_correo.php
 * Recibe datos, genera PDF y muestra “Request Form Generated”
 */

// ==============================
// Capturar datos del formulario
// ==============================
$service_type = $_POST['Service_Type'] ?? '';
$request_type = $_POST['Request_Type'] ?? '';
$priority = $_POST['Priority'] ?? '';
$requested_service = $_POST['Requested_Service'] ?? '';

$client_name = $_POST['client_name'] ?? '';
$client_title = $_POST['Client_Title'] ?? '';
$email = $_POST['Email'] ?? '';
$number_phone = $_POST['Number_Phone'] ?? '';
$company_name = $_POST['Company_Name'] ?? '';
$company_address = $_POST['Company_Address'] ?? '';
$is_new_client = $_POST['Is_New_Client'] ?? '';

$site_visit_conducted = $_POST['Site_Visit_Conducted'] ?? '';
$service_frequency = isset($_POST['Service_Frequency']) ? implode(', ', $_POST['Service_Frequency']) : '';
$invoice_frequency = $_POST['Invoice_Frequency'] ?? '';
$contract_duration = $_POST['Contract_Duration'] ?? '';

$subcontractor_price = $_POST['Subcontractor_Price'] ?? '';
$prime_quoted_price = $_POST['Prime_Quoted_Price'] ?? '';
$include_hood_vent = $_POST['includeHoodVent'] ?? '';
$include_kitchen = $_POST['includeKitchen'] ?? '';
$include_staff = $_POST['includeStaff'] ?? '';

$inflation_adjustment = $_POST['inflationAdjustment'] ?? '';
$total_area = $_POST['totalArea'] ?? '';
$buildings_included = $_POST['buildingsIncluded'] ?? '';
$start_date_services = $_POST['startDateServices'] ?? '';

$site_observation = $_POST['Site_Observation'] ?? '';
$additional_comments = $_POST['Additional_Comments'] ?? '';

$scope_of_work = isset($_POST['Scope_Of_Work']) ? implode(', ', $_POST['Scope_Of_Work']) : '';

// ==============================
// Generar contenido HTML del PDF
// ==============================
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background-color:#fff; color:#333; }
  h1 { text-align:center; color:#a30000; }
  .section { margin-top:20px; border-left:4px solid #a30000; padding-left:10px; }
  .section h2 { color:#a30000; font-size:18px; margin-bottom:10px; }
  .field { margin-bottom:6px; }
  .label { font-weight:bold; display:inline-block; min-width:220px; }
</style>
</head>
<body>
  <h1> Request Form - Prime Facility Services</h1>

  <div class="section">
    <h2>Section 1: Request Information</h2>
    <div class="field"><span class="label">Service Type:</span><?= htmlspecialchars($service_type) ?></div>
    <div class="field"><span class="label">Request Type:</span><?= htmlspecialchars($request_type) ?></div>
    <div class="field"><span class="label">Priority:</span><?= htmlspecialchars($priority) ?></div>
    <div class="field"><span class="label">Requested Service:</span><?= htmlspecialchars($requested_service) ?></div>
  </div>

  <div class="section">
    <h2>Section 2: Client Information</h2>
    <div class="field"><span class="label">Client Name:</span><?= htmlspecialchars($client_name) ?></div>
    <div class="field"><span class="label">Client Title:</span><?= htmlspecialchars($client_title) ?></div>
    <div class="field"><span class="label">Email:</span><?= htmlspecialchars($email) ?></div>
    <div class="field"><span class="label">Phone:</span><?= htmlspecialchars($number_phone) ?></div>
    <div class="field"><span class="label">Company:</span><?= htmlspecialchars($company_name) ?></div>
    <div class="field"><span class="label">Address:</span><?= htmlspecialchars($company_address) ?></div>
    <div class="field"><span class="label">New Client:</span><?= htmlspecialchars($is_new_client) ?></div>
  </div>

<!-- ============================== -->
<!-- Section 3: Operational Details -->
<!-- ============================== -->
<div class="section">
  <h2>Section 3: Operational Details</h2>

  <div class="field">
    <span class="label">Site Visit Conducted:</span>
    <span class="value"><?= htmlspecialchars($site_visit_conducted) ?></span>
  </div>

  <div class="field">
    <span class="label">Service Frequency:</span><br>

    <?php
      // 🔹 Capturamos datos individuales del formulario
      $frequency_period = $_POST['period'] ?? '';
      $week_days = $_POST['week_days'] ?? [];
      $one_time = $_POST['one_time'] ?? '';

      echo "<div style='margin-left:20px; line-height:1.6;'>";

      // 1️⃣ Periodo general (ej. “Every 2 Weeks”)
      if ($frequency_period) {
        echo "<strong>Period:</strong> " . htmlspecialchars($frequency_period) . "<br>";
      }

      // 2️⃣ Días seleccionados (array)
      if (!empty($week_days)) {
        echo "<strong>Selected Days:</strong> " . htmlspecialchars(implode(', ', $week_days)) . "<br>";
      }

      // 3️⃣ Si marcó “One Time”
      if ($one_time) {
        echo "<strong>One Time:</strong> " . htmlspecialchars($one_time) . "<br>";
      }

      // 4️⃣ Resumen si no llenó nada
      if (!$frequency_period && empty($week_days) && !$one_time) {
        echo "<em>No frequency data provided.</em>";
      }

      echo "</div>";
    ?>
  </div>

  <div class="field">
    <span class="label">Invoice Frequency:</span>
    <span class="value"><?= htmlspecialchars($invoice_frequency) ?></span>
  </div>

  <div class="field">
    <span class="label">Contract Duration:</span>
    <span class="value"><?= htmlspecialchars($contract_duration) ?></span>
  </div>
</div>

<div class="section">
  <h2>Section 4: Economic Information</h2>

  <div class="field">
    <span class="label">Subcontractor Price:</span><?= htmlspecialchars($subcontractor_price) ?>
  </div>

  <div class="field">
    <span class="label">Prime Quoted Price:</span><?= htmlspecialchars($prime_quoted_price) ?>
  </div>

  <div class="field">
    <span class="label">Include Hood Vent:</span><?= htmlspecialchars($include_hood_vent) ?>
  </div>

  <?php
  // -------- helpers robustos para leer $_POST con distintos patrones ------
  function pget(...$keys) {
    foreach ($keys as $k) {
      if ($k === null) continue;
      if (isset($_POST[$k])) return $_POST[$k];
      if (strpos($k, '[') !== false) {
        if (preg_match('/^([^\[]+)\[([^\]]+)\](?:\[([^\]]+)\])?$/', $k, $m)) {
          $root = $m[1];
          $i = $m[2] ?? null;
          $j = $m[3] ?? null;
          if (isset($_POST[$root]) && is_array($_POST[$root])) {
            if ($j !== null && isset($_POST[$root][$i][$j])) return $_POST[$root][$i][$j];
            if ($j === null && isset($_POST[$root][$i])) return $_POST[$root][$i];
          }
        }
      }
    }
    return null;
  }

  // Render de las tablas de cocinas
  if ($include_hood_vent === 'Yes') {
    echo "<div style='margin-left:25px; margin-top:10px;'>";

    $k = 1; // índice de cocina
    $grandTotal = 0.0;

    while (
      pget("kitchen_name_$k","kitchenName_$k","kitchen{$k}_name","kitchen_name[$k]") !== null
      || pget("total_$k","kitchen_total_$k","total[$k]") !== null
    ) {
      $kitchenName = pget("kitchen_name_$k","kitchenName_$k","kitchen{$k}_name","kitchen_name[$k]") ?? "Kitchen $k";
      $totalKitchenRaw = pget("total_$k","kitchen_total_$k","total[$k]") ?? 0;
      $totalKitchen = floatval(str_replace(['$',','],'', (string)$totalKitchenRaw));

      echo "<h3 style='color:#a30000; margin-top:15px;'>Kitchen $k: " . htmlspecialchars($kitchenName) . "</h3>";

      echo "<table style='width:100%; border-collapse:collapse; margin-top:5px; font-size:14px;'>
              <thead>
                <tr style='background:#a30000; color:white; text-align:center;'>
                  <th style='padding:6px;'>Description</th>
                  <th style='padding:6px;'>Qty</th>
                  <th style='padding:6px;'>Unit</th>
                  <th style='padding:6px;'>Amount</th>
                </tr>
              </thead>
              <tbody>";

      $r = 1;
      $printedAnyRow = false;
      while (true) {
        $service = pget("service{$k}{$r}","service_{$k}_{$r}","service{$k}_{$r}","service[$k][$r]");
        $qty     = pget("qty{$k}{$r}","qty_{$k}_{$r}","qty{$k}_{$r}","qty[$k][$r]");
        $unit    = pget("unit{$k}{$r}","unit_{$k}_{$r}","unit{$k}_{$r}","unit[$k][$r]");
        $amount  = pget("amount{$k}{$r}","amount_{$k}_{$r}","amount{$k}_{$r}","amount[$k][$r]");

        if ($service === null && $qty === null && $unit === null && $amount === null) break;

        if (trim((string)$service) !== '' || trim((string)$qty) !== '' || trim((string)$unit) !== '' || trim((string)$amount) !== '') {
          $printedAnyRow = true;
          echo "<tr>
                  <td style='border:1px solid #ccc; padding:6px;'>" . htmlspecialchars((string)$service) . "</td>
                  <td style='border:1px solid #ccc; padding:6px; text-align:center;'>" . htmlspecialchars((string)$qty) . "</td>
                  <td style='border:1px solid #ccc; padding:6px; text-align:center;'>" . (trim((string)$unit) !== '' ? '$'.htmlspecialchars((string)$unit) : '') . "</td>
                  <td style='border:1px solid #ccc; padding:6px; text-align:center;'>" . (trim((string)$amount) !== '' ? '$'.htmlspecialchars((string)$amount) : '') . "</td>
                </tr>";
        }

        $r++;
        if ($r > 50) break; // seguridad
      }

      echo "</tbody></table>";

      if ($printedAnyRow || $totalKitchen > 0) {
        echo "<div style='margin-top:8px; font-weight:bold; text-align:right;'>Total: $" . number_format($totalKitchen,2) . "</div>";
        $grandTotal += $totalKitchen;
      }

      $k++;
      if ($k > 20) break;
    }

    if ($grandTotal > 0) {
      echo "<div style='margin-top:15px; font-weight:bold; font-size:15px; color:#a30000; text-align:right;'>
              GRAND TOTAL: $" . number_format($grandTotal, 2) . "
            </div>";
    }

    echo "</div>";
  } else {
    echo "<div style='margin-left:25px;'><em>No kitchen details provided.</em></div>";
  }
  ?>

<?php
// ================================
// 🔹 Include Kitchen Equipment (alineado a la izquierda)
// ================================
echo "<div class='section'>";
echo "<h2>Section 4: Kitchen Equipment</h2>";
echo "<div class='field'><span class='label'>Include Kitchen Equipment:</span> " . htmlspecialchars($include_kitchen) . "</div>";

if ($include_kitchen === 'Yes') {
  echo "<div style='margin-left:25px; margin-top:10px;'>";

  // ====== ESTILO GLOBAL DE TABLAS ======
  echo "<style>
    table {
      max-width: 75%;              /* 🔹 Más angosto (ajustable) */
      margin-left: 0;              /* 🔹 Alineado a la izquierda */
      margin-right: 0;
      border-collapse: collapse;
    }
    th, td {
      font-size: 13px;
      padding: 5px 8px;
    }
    th {
      background: #a30000;
      color: white;
      text-align: center;
    }
    td {
      border: 1px solid #ccc;
    }
    h3 {
      color: #a30000;
      text-align: left;            /* 🔹 Alinea títulos también a la izquierda */
      margin-top: 20px;
      margin-bottom: 8px;
    }
  </style>";

  // ----- Total Surface -----
$total_surface = $_POST['total_surface'] ?? '';
if (!empty($total_surface) && $total_surface != "0") {
  echo "<h3>Total Surface</h3>";
  echo "<table>
          <thead>
            <tr><th>Area</th><th>Square Feet</th></tr>
          </thead>
          <tbody>
            <tr><td>Total Surface</td><td style='text-align:center;'>" . htmlspecialchars($total_surface) . "</td></tr>
          </tbody>
        </table>";
}

// ----- Surface Elements -----
$surfaces = [
  "Floor (Piso)" => $_POST['include_floor'] ?? '',
  "Wall (Pared)" => $_POST['include_wall'] ?? '',
  "Ceiling (Techo)" => $_POST['include_ceiling'] ?? ''
];

$hasSurface = false;
foreach ($surfaces as $v) {
  if (!empty($v) && strtolower($v) === 'on') {
    $hasSurface = true;
    break;
  }
}

if ($hasSurface) {
  echo "<h3>Surface Elements</h3>";
  echo "<table style='width:50%; border-collapse:collapse;'>
          <thead>
            <tr>
              <th style='background:#a30000; color:white; padding:5px;'>Surface Element</th>
              <th style='background:#a30000; color:white; padding:5px;'>Include</th>
            </tr>
          </thead>
          <tbody>";
  foreach ($surfaces as $name => $include) {
    $value = (!empty($include) && strtolower($include) === 'on') ? 'Yes' : 'No';
    echo "<tr>
            <td style='padding:5px; border:1px solid #ccc;'>$name</td>
            <td style='text-align:center; border:1px solid #ccc;'>$value</td>
          </tr>";
  }
  echo "</tbody></table>";
} else {
  echo "<p style='margin-left:25px; color:#555;'><em>No surface elements selected.</em></p>";
}

  // ====== FUNCION AUXILIAR ======
  function renderEquipmentCategory($title, $items, $prefix) {
    $rows = "";
    foreach ($items as $item) {
      $var_name = 'qty_' . strtolower(str_replace([' ', '/', '(', ')', '-'], '_', $item));
      $qty = $_POST[$var_name] ?? '';
      if (!empty($qty) && $qty != "0") {
        $rows .= "<tr>
                    <td>" . htmlspecialchars($item) . "</td>
                    <td style='text-align:center;'>" . htmlspecialchars($qty) . "</td>
                  </tr>";
      }
    }

    if (!empty($rows)) {
      echo "<h3>" . htmlspecialchars($title) . "</h3>";
      echo "<table>
              <thead>
                <tr><th>Equipment</th><th>Quantity</th></tr>
              </thead>
              <tbody>$rows</tbody>
            </table>";
    }
  }

  // ====== CATEGORÍAS ======
  renderEquipmentCategory("HEAT", ["Stoves","Ovens","Fryers","Griddle / Grill","Salamander / Broiler"], "heat");
  renderEquipmentCategory("COLD", ["Refrigerators","Freezers","Beverage Coolers"], "cold");
  renderEquipmentCategory("PREPARATION", ["Food Processors","Industrial Blenders","Mixers","Cutters / Slicers"], "prep");
  renderEquipmentCategory("SERVICE", ["Hot Tables","Service Carts","Food Warmers (Bain-Marie)"], "service");
  renderEquipmentCategory("WASHING", ["Industrial Dishwashers","Grease Traps (external cleaning only)","Sinks and Wash Stations"], "wash");
  renderEquipmentCategory("ELECTRONIC EXTRAS", ["Touch Screens","Speakers","Electronic Controls","Digital Clocks / Order Systems"], "extras");

  echo "</div>";
} else {
  echo "<div style='margin-left:25px;'><em>No kitchen equipment details provided.</em></div>";
}

echo "</div>";
?>


  <?php
// ================================
// 🔹 Section 4: Include Staff (Final Version)
// ================================
echo "<div class='section'>";
echo "<h2>Section 4: Include Staff</h2>";
echo "<div class='field'><span class='label'>Include Staff:</span> " . htmlspecialchars($include_staff) . "</div>";

if ($include_staff === 'Yes') {
  echo "<div style='margin-left:25px; margin-top:8px;'>";

  // ====== ESTILO GLOBAL ======
  echo "<style>
    table {
      max-width: 70%;               /* 🔹 Más angosto para mejor estética */
      margin-left: 0;
      border-collapse: collapse;
      margin-bottom: 10px;
    }
    th, td {
      font-size: 12px;
      padding: 4px 6px;
    }
    th {
      background: #a30000;
      color: white;
      text-align: center;
    }
    td {
      border: 1px solid #ccc;
    }
    h3 {
      color: #a30000;
      text-align: left;
      margin-top: 10px;
      margin-bottom: 5px;
      font-size: 14px;
    }
  </style>";

  // ====== FUNCIÓN PARA CATEGORÍAS ======
  function renderStaffCategoryFull($title, $roles, $prefix) {
    $rows = "";
    foreach ($roles as $role) {
      // 🔧 Normalización total del nombre (para que coincida con los del formulario)
      $slug = strtolower($role);
      $slug = str_replace(
        [' ', '/', '(', ')', '-', '&', '.', ':'],
        '_',
        $slug
      );
      $slug = preg_replace('/_+/', '_', $slug); // 🔹 reemplaza múltiples underscores por uno solo
      $slug = trim($slug, '_'); // 🔹 limpia extremos

      // Leer los valores del formulario
      $base = $_POST["base_{$prefix}_{$slug}"] ?? '';
      $increase = $_POST["increase_{$prefix}_{$slug}"] ?? '';
      $bill = $_POST["bill_{$prefix}_{$slug}"] ?? '';

      // Solo mostrar si hay valores
      if ($base !== '' || $increase !== '' || $bill !== '') {
        $rows .= "<tr>
                    <td>" . htmlspecialchars($role) . "</td>
                    <td style='text-align:center;'>" . htmlspecialchars($base) . "</td>
                    <td style='text-align:center;'>" . htmlspecialchars($increase) . "</td>
                    <td style='text-align:center;'>" . htmlspecialchars($bill) . "</td>
                  </tr>";
      }
    }

    if (!empty($rows)) {
      echo "<h3>" . htmlspecialchars($title) . "</h3>";
      echo "<table>
              <thead>
                <tr>
                  <th>Position</th>
                  <th>Base Rate</th>
                  <th>% Increase</th>
                  <th>Bill Rate</th>
                </tr>
              </thead>
              <tbody>$rows</tbody>
            </table>";
    }
  }

  // ====== CATEGORÍAS ======
  renderStaffCategoryFull("HOUSEKEEPING", [
    "Housekeeper / GRA",
    "Housekeeping Inspector / GRA Supervisor",
    "Laundry Attendant",
    "Houseman",
    "Public Areas Attendant",
    "Lobby Attendant",
    "Lobby Runner (AM/PM/Overnight)",
    "Turndown Attendant"
  ], "housekeeping");

  renderStaffCategoryFull("FOOD & BEVERAGE", [
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
  ], "food_beverage");

  renderStaffCategoryFull("MAINTENANCE", [
    "Maintenance Helper",
    "Movers"
  ], "maintenance");

  renderStaffCategoryFull("RECREATION & POOL", [
    "Pool Attendant",
    "Recreation Slide Attendant",
    "Recreation Supervisor"
  ], "recreation_pool");

  renderStaffCategoryFull("SECURITY", [
    "Security Guard (Noncommissioned)"
  ], "security");

  renderStaffCategoryFull("VALET PARKING", [
    "Valet Attendant (AM/PM/Overnight)"
  ], "valet_parking");

  renderStaffCategoryFull("FRONT DESK", [
    "Front Desk Attendant",
    "Night Auditor"
  ], "front_desk");

  echo "</div>"; // cierre del contenedor interno
} else {
  echo "<div style='margin-left:25px;'><em>No staff details provided.</em></div>";
}

echo "</div>"; // cierre de sección principal
?>

  <div class="section">
    <h2>Section 5: Contract Information</h2>
    <div class="field"><span class="label">Inflation Adjustment:</span><?= htmlspecialchars($inflation_adjustment) ?></div>
    <div class="field"><span class="label">Total Area (sq ft):</span><?= htmlspecialchars($total_area) ?></div>
    <div class="field"><span class="label">Buildings Included:</span><?= htmlspecialchars($buildings_included) ?></div>
    <div class="field"><span class="label">Start Date of Services:</span><?= htmlspecialchars($start_date_services) ?></div>
  </div>

<div class="section">
  <h2>Section 6: Observations</h2>
  <div class="field">
    <span class="label">Site Observation:</span> <?= htmlspecialchars($site_observation) ?>
  </div>
  <div class="field">
    <span class="label">Additional Comments:</span> <?= htmlspecialchars($additional_comments) ?>
  </div>
  <div class="field">
    <span class="label">Email Information Sent:</span> <?= htmlspecialchars($_POST['Email_Information_Sent'] ?? '') ?>
  </div>
</div>


  <div class="section">
  <h2>Section 7: Scope of Work</h2>
  <?php if (!empty($_POST['Scope_Of_Work'])): ?>
    <ul style="margin-left: 25px; line-height: 1.6; color: #333;">
      <?php foreach ($_POST['Scope_Of_Work'] as $item): ?>
        <li><?= htmlspecialchars(trim($item)) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p style="color: #777;">No scope of work items were selected.</p>
  <?php endif; ?>
</div>


  <div style="text-align:center; font-size:12px; margin-top:30px; color:#777;">
    Generated on <?= date('d/m/Y H:i:s') ?> | IP: <?= $_SERVER['REMOTE_ADDR'] ?>
  </div>
</body>
</html>
<?php
$html = ob_get_clean();


// ==============================
// Generar PDF con DOMPDF y enviar por correo
// ==============================
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load email configuration
$mail_config = require_once '../mail_config.php';

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Save PDF to temporary file
$pdf_filename = "RequestForm_" . date('Ymd_His') . ".pdf";
$pdf_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $pdf_filename;
file_put_contents($pdf_path, $dompdf->output());

// ==============================
// Send Email via Brevo (PHPMailer)
// ==============================
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = $mail_config['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $mail_config['smtp_username'];
    $mail->Password   = $mail_config['smtp_password'];
    $mail->SMTPSecure = $mail_config['smtp_encryption'];
    $mail->Port       = $mail_config['smtp_port'];
    $mail->CharSet    = 'UTF-8';

    // Recipients
    $mail->setFrom($mail_config['from_email'], $mail_config['from_name']);
    $mail->addAddress($mail_config['to_email'], $mail_config['to_name']);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'New Contract Request Form - ' . htmlspecialchars($company_name);

    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: #a30000; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .section { margin: 20px 0; padding: 15px; background: #f9fafb; border-left: 4px solid #a30000; }
            .section h3 { color: #a30000; margin-top: 0; }
            .info-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
            .info-table td { padding: 8px; border-bottom: 1px solid #ddd; }
            .info-table td:first-child { font-weight: bold; width: 200px; }
            .footer { background: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>New Contract Request Form Submitted</h2>
        </div>
        <div class='content'>
            <p>A new contract request form has been submitted. Please find the details below:</p>

            <div class='section'>
                <h3>Request Information</h3>
                <table class='info-table'>
                    <tr><td>Service Type:</td><td>" . htmlspecialchars($service_type) . "</td></tr>
                    <tr><td>Request Type:</td><td>" . htmlspecialchars($request_type) . "</td></tr>
                    <tr><td>Priority:</td><td>" . htmlspecialchars($priority) . "</td></tr>
                </table>
            </div>

            <div class='section'>
                <h3>Client Information</h3>
                <table class='info-table'>
                    <tr><td>Client Name:</td><td>" . htmlspecialchars($client_name) . "</td></tr>
                    <tr><td>Email:</td><td>" . htmlspecialchars($email) . "</td></tr>
                    <tr><td>Phone:</td><td>" . htmlspecialchars($number_phone) . "</td></tr>
                    <tr><td>Company:</td><td>" . htmlspecialchars($company_name) . "</td></tr>
                    <tr><td>Address:</td><td>" . htmlspecialchars($company_address) . "</td></tr>
                </table>
            </div>

            <div class='section'>
                <h3>Economic Information</h3>
                <table class='info-table'>
                    <tr><td>Subcontractor Price:</td><td>" . htmlspecialchars($subcontractor_price) . "</td></tr>
                    <tr><td>Prime Quoted Price:</td><td>" . htmlspecialchars($prime_quoted_price) . "</td></tr>
                </table>
            </div>

            <p style='margin-top: 20px;'>The complete PDF document with all details is attached to this email.</p>
        </div>
        <div class='footer'>
            <p>Submitted on " . date('F j, Y, g:i a') . "</p>
            <p>Sales Management System</p>
        </div>
    </body>
    </html>
    ";

    $mail->Body = $email_body;
    $mail->AltBody = "New Contract Request Form\n\n" .
                     "Company: $company_name\n" .
                     "Client: $client_name\n" .
                     "Email: $email\n\n" .
                     "Please see attached PDF for complete details.";

    // Attach PDF
    $mail->addAttachment($pdf_path, $pdf_filename);

    // Send email
    $mail->send();
    $email_status = 'success';
    $email_message = 'Form submitted and email sent successfully!';

} catch (Exception $e) {
    $email_status = 'error';
    $email_message = "Form could not be sent. Error: {$mail->ErrorInfo}";
}

// Clean up temporary PDF file
if (file_exists($pdf_path)) {
    unlink($pdf_path);
}

// ==============================
// Show confirmation page
// ==============================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submitted</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Inter', sans-serif;
            padding: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .confirmation-box {
            background: white;
            padding: 50px 60px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        .icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        h1 {
            color: #333;
            margin-bottom: 15px;
            font-size: 2rem;
        }
        p {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.5);
        }
        .details {
            background: #f9fafb;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }
        .details h3 {
            color: #a30000;
            margin-top: 0;
        }
        .detail-item {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #374151;
            display: inline-block;
            min-width: 180px;
        }
    </style>
</head>
<body>
    <div class="confirmation-box">
        <div class="icon <?php echo $email_status; ?>">
            <?php echo $email_status === 'success' ? '✓' : '✗'; ?>
        </div>

        <h1><?php echo $email_status === 'success' ? 'Form Submitted!' : 'Submission Error'; ?></h1>

        <p><?php echo $email_message; ?></p>

        <?php if ($email_status === 'success'): ?>
        <div class="details">
            <h3>Submission Details</h3>
            <div class="detail-item">
                <span class="detail-label">Company:</span>
                <span><?php echo htmlspecialchars($company_name); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Client:</span>
                <span><?php echo htmlspecialchars($client_name); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email:</span>
                <span><?php echo htmlspecialchars($email); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Sent to:</span>
                <span><?php echo htmlspecialchars($mail_config['to_email']); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <a href="index.php" class="btn">← Back to Form</a>
    </div>
</body>
</html>


