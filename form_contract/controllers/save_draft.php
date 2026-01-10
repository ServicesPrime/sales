<?php
/**
 * ============================================
 * SAVE_DRAFT.PHP - Versión Refactorizada
 * ============================================
 * Guarda borradores de formularios con fotos
 * Compatible con arquitectura modular
 * ============================================
 */

// Cargar bootstrap
require_once __DIR__ . '/../bootstrap/init.php';
require_once __DIR__ . '/../bootstrap/lang.php';

// JSON response
header('Content-Type: application/json');

try {
    // Iniciar transacción
    $pdo->beginTransaction();
    
    log_message("=== SAVE DRAFT INICIADO ===", 'INFO', 'drafts.log');
    
    // Obtener datos del formulario
    $form_id = isset($_POST['form_id']) ? (int)$_POST['form_id'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : STATUS_DRAFT;
    
    log_message("Form ID: " . ($form_id ?: 'NEW'), 'INFO', 'drafts.log');
    log_message("Status: $status", 'INFO', 'drafts.log');
    
    // ============================================
    // PASO 1: GUARDAR/ACTUALIZAR FORMULARIO
    // ============================================
    
    if ($form_id) {
        // UPDATE
        $sql = "UPDATE forms SET
            service_type = :service_type,
            request_type = :request_type,
            priority = :priority,
            requested_service = :requested_service,
            client_name = :client_name,
            company_name = :company_name,
            contact_name = :contact_name,
            phone = :phone,
            email = :email,
            address = :address,
            city = :city,
            state = :state,
            is_new_client = :is_new_client,
            site_visit_conducted = :site_visit_conducted,
            invoice_frequency = :invoice_frequency,
            contract_duration = :contract_duration,
            total_cost = :total_cost,
            payment_terms = :payment_terms,
            inflation_adjustment = :inflation_adjustment,
            total_area = :total_area,
            buildings_included = :buildings_included,
            start_date_services = :start_date_services,
            site_observation = :site_observation,
            additional_comments = :additional_comments,
            email_information_sent = :email_information_sent,
            seller = :seller,
            include_staff = :include_staff,
            status = :status,
            updated_at = NOW()
        WHERE form_id = :form_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':form_id', $form_id);
        
        log_message("Modo: UPDATE", 'INFO', 'drafts.log');
        
    } else {
        // INSERT
        $sql = "INSERT INTO forms (
            service_type, request_type, priority, requested_service,
            client_name, company_name, contact_name, phone, email, address, city, state, is_new_client,
            site_visit_conducted, invoice_frequency, contract_duration,
            total_cost, payment_terms,
            inflation_adjustment, total_area, buildings_included, start_date_services,
            site_observation, additional_comments, email_information_sent,
            seller, include_staff,
            status, submitted_by, created_at
        ) VALUES (
            :service_type, :request_type, :priority, :requested_service,
            :client_name, :company_name, :contact_name, :phone, :email, :address, :city, :state, :is_new_client,
            :site_visit_conducted, :invoice_frequency, :contract_duration,
            :total_cost, :payment_terms,
            :inflation_adjustment, :total_area, :buildings_included, :start_date_services,
            :site_observation, :additional_comments, :email_information_sent,
            :seller, :include_staff,
            :status, :submitted_by, NOW()
        )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':submitted_by', get_client_ip());
        
        log_message("Modo: INSERT", 'INFO', 'drafts.log');
    }
    
    // Helper function
    function emptyToNull($value) {
        return (isset($value) && $value !== '') ? $value : null;
    }
    
    // Bind parameters
    $stmt->bindValue(':service_type', emptyToNull($_POST['Service_Type'] ?? null));
    $stmt->bindValue(':request_type', emptyToNull($_POST['Request_Type'] ?? null));
    $stmt->bindValue(':priority', emptyToNull($_POST['Priority'] ?? null));
    $stmt->bindValue(':requested_service', emptyToNull($_POST['Requested_Service'] ?? null));
    $stmt->bindValue(':client_name', emptyToNull($_POST['Client_Name'] ?? null));
    $stmt->bindValue(':company_name', emptyToNull($_POST['Company_Name'] ?? null));
    $stmt->bindValue(':contact_name', emptyToNull($_POST['Client_Title'] ?? null));
    $stmt->bindValue(':phone', emptyToNull($_POST['Number_Phone'] ?? null));
    $stmt->bindValue(':email', emptyToNull($_POST['Email'] ?? null));
    $stmt->bindValue(':address', emptyToNull($_POST['Company_Address'] ?? null));
    $stmt->bindValue(':city', emptyToNull($_POST['City'] ?? null));
    $stmt->bindValue(':state', emptyToNull($_POST['State'] ?? null));
    $stmt->bindValue(':is_new_client', emptyToNull($_POST['Is_New_Client'] ?? null));
    $stmt->bindValue(':site_visit_conducted', emptyToNull($_POST['Site_Visit_Conducted'] ?? null));
    $stmt->bindValue(':invoice_frequency', emptyToNull($_POST['Invoice_Frequency'] ?? null));
    $stmt->bindValue(':contract_duration', emptyToNull($_POST['Contract_Duration'] ?? null));
    $stmt->bindValue(':total_cost', emptyToNull($_POST['PriceInput'] ?? null));
    $stmt->bindValue(':payment_terms', emptyToNull($_POST['payment_terms'] ?? null));
    $stmt->bindValue(':seller', emptyToNull($_POST['Seller'] ?? null));
    $stmt->bindValue(':include_staff', emptyToNull($_POST['includeStaff'] ?? null));
    $stmt->bindValue(':inflation_adjustment', emptyToNull($_POST['inflationAdjustment'] ?? null));
    $stmt->bindValue(':total_area', emptyToNull($_POST['totalArea'] ?? null));
    $stmt->bindValue(':buildings_included', emptyToNull($_POST['buildingsIncluded'] ?? null));
    $stmt->bindValue(':start_date_services', emptyToNull($_POST['startDateServices'] ?? null));
    $stmt->bindValue(':site_observation', emptyToNull($_POST['Site_Observation'] ?? null));
    $stmt->bindValue(':additional_comments', emptyToNull($_POST['Additional_Comments'] ?? null));
    $stmt->bindValue(':email_information_sent', emptyToNull($_POST['email_information_sent'] ?? null));
    $stmt->bindValue(':status', $status);
    
    $stmt->execute();
    
    // Obtener form_id guardado
    $saved_form_id = $form_id ?: $pdo->lastInsertId();
    
    log_message("Form guardado. ID: $saved_form_id", 'INFO', 'drafts.log');
    
    // ============================================
    // PASO 2: GUARDAR SCOPE OF WORK
    // ============================================
    
    if (isset($_POST['Scope_Of_Work']) && is_array($_POST['Scope_Of_Work'])) {
        // Eliminar anterior
        $pdo->prepare("DELETE FROM scope_of_work WHERE form_id = ?")->execute([$saved_form_id]);
        
        // Insertar nuevo
        $sql_scope = "INSERT INTO scope_of_work (form_id, task_name) VALUES (?, ?)";
        $stmt_scope = $pdo->prepare($sql_scope);
        
        foreach ($_POST['Scope_Of_Work'] as $task) {
            $stmt_scope->execute([$saved_form_id, $task]);
        }
        
        log_message("Scope of work guardado: " . count($_POST['Scope_Of_Work']) . " tasks", 'INFO', 'drafts.log');
    }
    
    // ============================================
    // PASO 3: GUARDAR FOTOS
    // ============================================
    
    if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
        log_message("Procesando fotos...", 'INFO', 'drafts.log');
        
        // Verificar/crear carpeta de uploads
        if (!is_dir(UPLOAD_PATH)) {
            mkdir(UPLOAD_PATH, 0755, true);
        }
        
        // Eliminar fotos anteriores si es update
        if ($form_id) {
            $pdo->prepare("DELETE FROM form_photos WHERE form_id = ?")->execute([$saved_form_id]);
            log_message("Fotos anteriores eliminadas", 'INFO', 'drafts.log');
        }
        
        $sql_photo = "INSERT INTO form_photos (form_id, photo_filename, photo_path, photo_size, photo_type, uploaded_at) 
                      VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt_photo = $pdo->prepare($sql_photo);
        
        $photos_saved = 0;
        $total_files = count($_FILES['photos']['name']);
        
        for ($i = 0; $i < $total_files; $i++) {
            if ($_FILES['photos']['error'][$i] === UPLOAD_ERR_OK) {
                $photo_tmp = $_FILES['photos']['tmp_name'][$i];
                $photo_name = basename($_FILES['photos']['name'][$i]);
                $photo_size = $_FILES['photos']['size'][$i];
                $photo_type = $_FILES['photos']['type'][$i];
                
                // Validar tipo de archivo
                if (!is_valid_file_extension($photo_name)) {
                    log_message("Archivo rechazado (extensión inválida): $photo_name", 'WARNING', 'drafts.log');
                    continue;
                }
                
                // Validar tamaño
                if ($photo_size > MAX_UPLOAD_SIZE) {
                    log_message("Archivo rechazado (muy grande): $photo_name", 'WARNING', 'drafts.log');
                    continue;
                }
                
                // Generar nombre único
                $unique_name = generate_unique_filename($photo_name);
                $photo_path = UPLOAD_PATH . '/' . $unique_name;
                
                // Mover archivo
                if (move_uploaded_file($photo_tmp, $photo_path)) {
                    // Guardar en BD
                    $stmt_photo->execute([
                        $saved_form_id,
                        $photo_name,
                        $photo_path,
                        $photo_size,
                        $photo_type
                    ]);
                    
                    $photos_saved++;
                    log_message("Foto guardada: $photo_name", 'INFO', 'drafts.log');
                } else {
                    log_message("Error moviendo archivo: $photo_name", 'ERROR', 'drafts.log');
                }
            }
        }
        
        log_message("Total fotos guardadas: $photos_saved de $total_files", 'INFO', 'drafts.log');
    } else {
        log_message("No se detectaron fotos", 'INFO', 'drafts.log');
    }
    
    // ============================================
    // CONFIRMAR TRANSACCIÓN
    // ============================================
    
    $pdo->commit();
    
    log_message("=== DRAFT GUARDADO EXITOSAMENTE ===", 'INFO', 'drafts.log');
    
    json_response(true, [
        'form_id' => $saved_form_id
    ], $form_id ? t('draft_updated') : t('draft_saved'));
    
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    log_message("ERROR PDO: " . $e->getMessage(), 'ERROR', 'drafts.log');
    
    json_response(false, null, 'Database error: ' . $e->getMessage());
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    log_message("ERROR: " . $e->getMessage(), 'ERROR', 'drafts.log');
    
    json_response(false, null, 'Error: ' . $e->getMessage());
}