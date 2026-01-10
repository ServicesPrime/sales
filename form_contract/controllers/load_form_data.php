<?php
/**
 * ============================================
 * LOAD_FORM_DATA.PHP - Versión con Fotos
 * ============================================
 * Carga datos de un formulario guardado
 * Incluye información de fotos
 * ============================================
 */

// Cargar bootstrap
require_once __DIR__ . '/../bootstrap/init.php';
require_once __DIR__ . '/../bootstrap/lang.php';

// JSON response
header('Content-Type: application/json');

try {
    // Obtener form_id
    $form_id = isset($_GET['form_id']) ? (int)$_GET['form_id'] : null;
    
    if (!$form_id) {
        throw new Exception("Form ID no proporcionado");
    }
    
    log_message("Cargando form_id: $form_id", 'INFO', 'forms.log');
    
    // ============================================
    // PASO 1: OBTENER DATOS DEL FORMULARIO
    // ============================================
    
    $stmt = $pdo->prepare("SELECT * FROM forms WHERE form_id = ?");
    $stmt->execute([$form_id]);
    $form = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$form) {
        throw new Exception("Formulario no encontrado");
    }
    
    log_message("Formulario encontrado: " . $form['client_name'], 'INFO', 'forms.log');
    
    // ============================================
    // PASO 2: OBTENER SCOPE OF WORK
    // ============================================
    
    $stmt_scope = $pdo->prepare("SELECT task_name FROM scope_of_work WHERE form_id = ? ORDER BY scope_id");
    $stmt_scope->execute([$form_id]);
    $scope_tasks = $stmt_scope->fetchAll(PDO::FETCH_COLUMN);
    
    log_message("Scope tasks: " . count($scope_tasks), 'INFO', 'forms.log');
    
    // ============================================
    // PASO 3: OBTENER FOTOS
    // ============================================
    
    $stmt_photos = $pdo->prepare("
        SELECT 
            photo_id,
            photo_filename,
            photo_path,
            photo_size,
            photo_type,
            uploaded_at
        FROM form_photos 
        WHERE form_id = ? 
        ORDER BY uploaded_at DESC
    ");
    
    $stmt_photos->execute([$form_id]);
    $photos_db = $stmt_photos->fetchAll(PDO::FETCH_ASSOC);
    
    log_message("Fotos en BD: " . count($photos_db), 'INFO', 'forms.log');
    
    // ============================================
    // PASO 4: PROCESAR FOTOS PARA RESPUESTA
    // ============================================
    
    $photos = [];
    
    foreach ($photos_db as $photo) {
        // Verificar que el archivo exista
        $file_exists = file_exists($photo['photo_path']);
        
        // Generar URL relativa para mostrar en el navegador
        // Asumiendo que UPLOAD_PATH es /home/user/project/Uploads/photos
        // Y necesitamos convertirlo a URL: /Uploads/photos/filename
        $photo_url = null;
        if ($file_exists) {
            // Extraer solo la parte relativa de la ruta
            $relative_path = str_replace(BASE_PATH, '', $photo['photo_path']);
            $photo_url = $relative_path;
        }
        
        $photos[] = [
            'photo_id' => $photo['photo_id'],
            'filename' => $photo['photo_filename'],
            'size' => (int)$photo['photo_size'],
            'size_formatted' => format_file_size($photo['photo_size']),
            'type' => $photo['photo_type'],
            'uploaded_at' => $photo['uploaded_at'],
            'exists' => $file_exists,
            'url' => $photo_url,
            'thumbnail' => $photo_url  // Podrías generar thumbnails aquí
        ];
        
        if (!$file_exists) {
            log_message("ADVERTENCIA: Foto en BD pero archivo no existe: " . $photo['photo_path'], 'WARNING', 'forms.log');
        }
    }
    
    log_message("Fotos procesadas: " . count($photos), 'INFO', 'forms.log');
    
    // ============================================
    // PASO 5: CONSTRUIR RESPUESTA
    // ============================================
    
    $response = [
        'success' => true,
        'form' => [
            // Form metadata
            'form_id' => $form['form_id'],
            'status' => $form['status'],
            'created_at' => $form['created_at'],
            'updated_at' => $form['updated_at'],
            'sent_at' => $form['sent_at'],
            
            // Section 1: Request Information
            'Service_Type' => $form['service_type'],
            'Request_Type' => $form['request_type'],
            'Priority' => $form['priority'],
            'Requested_Service' => $form['requested_service'],
            
            // Section 2: Client Information
            'Client_Name' => $form['client_name'],
            'Company_Name' => $form['company_name'],
            'Client_Title' => $form['contact_name'],
            'Number_Phone' => $form['phone'],
            'Email' => $form['email'],
            'Company_Address' => $form['address'],
            'City' => $form['city'],
            'State' => $form['state'],
            'Is_New_Client' => $form['is_new_client'],
            
            // Section 3: Operational Details
            'Site_Visit_Conducted' => $form['site_visit_conducted'],
            'Invoice_Frequency' => $form['invoice_frequency'],
            'Contract_Duration' => $form['contract_duration'],
            
            // Section 4: Economic Information
            'PriceInput' => $form['total_cost'],
            'payment_terms' => $form['payment_terms'],
            'Seller' => $form['seller'],
            'includeStaff' => $form['include_staff'],
            
            // Section 5: Contract Information
            'inflationAdjustment' => $form['inflation_adjustment'],
            'totalArea' => $form['total_area'],
            'buildingsIncluded' => $form['buildings_included'],
            'startDateServices' => $form['start_date_services'],
            
            // Section 6: Observations
            'Site_Observation' => $form['site_observation'],
            'Additional_Comments' => $form['additional_comments'],
            'email_information_sent' => $form['email_information_sent'],
            
            // Section 7: Scope of Work
            'Scope_Of_Work' => $scope_tasks,
            
            // Section 8: Photos
            'photos' => $photos,
            'photos_count' => count($photos)
        ]
    ];
    
    log_message("Respuesta construida exitosamente", 'INFO', 'forms.log');
    
    // ============================================
    // PASO 6: ENVIAR RESPUESTA JSON
    // ============================================
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    
    log_message("=== FORM DATA CARGADO EXITOSAMENTE ===", 'INFO', 'forms.log');
    
} catch (PDOException $e) {
    log_message("ERROR PDO: " . $e->getMessage(), 'ERROR', 'forms.log');
    
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
    
} catch (Exception $e) {
    log_message("ERROR: " . $e->getMessage(), 'ERROR', 'forms.log');
    
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}