<?php
/**
 * guardar.php
 * Recibe los datos del formulario modular y los guarda en la base de datos.
 */

require 'db_config.php'; // Conexión MySQL

// ==============================
// 1️⃣ Recibir datos del formulario
// ==============================
$service_type             = $_POST['service_type']             ?? '';
$request_type             = $_POST['request_type']             ?? '';
$priority                 = $_POST['priority']                 ?? '';
$requested_service         = $_POST['requested_service']        ?? '';

$client_name              = $_POST['client_name']              ?? '';
$client_title             = $_POST['client_title']             ?? '';
$email                    = $_POST['email']                    ?? '';
$number_phone             = $_POST['number_phone']             ?? '';
$company_name             = $_POST['company_name']             ?? '';
$company_address          = $_POST['company_address']          ?? '';
$is_new_client            = $_POST['is_new_client']            ?? '';

$site_visit_conducted     = $_POST['site_visit_conducted']     ?? '';
$service_frequency        = $_POST['service_frequency']        ?? '';
$invoice_frequency        = $_POST['invoice_frequency']        ?? '';
$contract_duration        = $_POST['contract_duration']        ?? '';

$subcontractor_price      = $_POST['subcontractor_price']      ?? '';
$prime_quoted_price       = $_POST['prime_quoted_price']       ?? '';
$hood_vent                = $_POST['hood_vent']                ?? '';
$kitchen_cleaning         = $_POST['kitchen_cleaning']         ?? '';
$staff_required           = $_POST['staff_required']           ?? '';

$price_increase_rate      = $_POST['price_increase_rate']      ?? '';
$total_area               = $_POST['total_area']               ?? '';
$included_buildings       = $_POST['included_buildings']       ?? '';
$service_start_date       = $_POST['service_start_date']       ?? '';

$site_observation         = $_POST['site_observation']         ?? '';
$additional_comments      = $_POST['additional_comments']      ?? '';
$email_information_sent   = $_POST['email_information_sent']   ?? '';

$scope_of_work            = isset($_POST['scope_items']) 
                              ? implode(', ', $_POST['scope_items']) 
                              : ''; // checkboxes dinámicos


// ==============================
// 2️⃣ Validaciones básicas
// ==============================
if (empty($service_type) || empty($request_type) || empty($priority)) {
  die("⚠️ Los campos 'Tipo de servicio', 'Tipo de solicitud' y 'Prioridad' son obligatorios. <a href='index.php'>Volver</a>");
}

if (!in_array($service_type, ['Janitorial', 'Hospitality'])) {
  die("⚠️ Valor inválido para Tipo de servicio. <a href='index.php'>Volver</a>");
}


// ==============================
// 3️⃣ Insertar datos en la base
// ==============================
$sql = "
INSERT INTO form (
  service_type,
  request_type,
  priority,
  requested_service,
  client_name,
  client_title,
  email,
  number_phone,
  company_name,
  company_address,
  is_new_client,
  site_visit_conducted,
  service_frequency,
  invoice_frequency,
  contract_duration,
  subcontractor_price,
  prime_quoted_price,
  hood_vent,
  kitchen_cleaning,
  staff_required,
  price_increase_rate,
  total_area,
  included_buildings,
  service_start_date,
  site_observation,
  additional_comments,
  email_information_sent,
  scope_of_work
)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
  die("❌ Error al preparar la consulta: " . $conexion->error);
}


// ==============================
// 4️⃣ Vincular parámetros
// ==============================
$stmt->bind_param(
  "ssssssssssssssssssssssssssss",
  $service_type,
  $request_type,
  $priority,
  $requested_service,
  $client_name,
  $client_title,
  $email,
  $number_phone,
  $company_name,
  $company_address,
  $is_new_client,
  $site_visit_conducted,
  $service_frequency,
  $invoice_frequency,
  $contract_duration,
  $subcontractor_price,
  $prime_quoted_price,
  $hood_vent,
  $kitchen_cleaning,
  $staff_required,
  $price_increase_rate,
  $total_area,
  $included_buildings,
  $service_start_date,
  $site_observation,
  $additional_comments,
  $email_information_sent,
  $scope_of_work
);


// ==============================
// 5️⃣ Ejecutar e informar resultado
// ==============================
if ($stmt->execute()) {
  header("Location: confirmacion.html");
  exit;
} else {
  echo "❌ Error al guardar los datos: " . $stmt->error;
}


// ==============================
// 6️⃣ Cerrar conexión
// ==============================
$stmt->close();
$conexion->close();
?>
