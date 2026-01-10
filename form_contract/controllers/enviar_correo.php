<?php
/**
 * ============================================
 * ENVIAR_CORREO.PHP - Versión con Fotos
 * ============================================
 * Envía el formulario por email con fotos adjuntas
 * Usa PHPMailer para SMTP
 * ============================================
 */

// Cargar bootstrap
require_once __DIR__ . '/../bootstrap/init.php';
require_once __DIR__ . '/../bootstrap/lang.php';

// Importar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Si usas Composer
// require __DIR__ . '/../vendor/autoload.php';

// Si descargaste PHPMailer manualmente, descomentar:
// require __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
// require __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
// require __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';

try {
    log_message("=== ENVIAR CORREO INICIADO ===", 'INFO', 'email.log');
    
    // Obtener form_id
    $form_id = isset($_POST['form_id']) ? (int)$_POST['form_id'] : null;
    
    if (!$form_id) {
        throw new Exception("Form ID no proporcionado");
    }
    
    log_message("Form ID: $form_id", 'INFO', 'email.log');
    
    // ============================================
    // PASO 1: OBTENER DATOS DEL FORMULARIO
    // ============================================
    
    $stmt = $pdo->prepare("SELECT * FROM forms WHERE form_id = ?");
    $stmt->execute([$form_id]);
    $form = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$form) {
        throw new Exception("Formulario no encontrado");
    }
    
    log_message("Formulario encontrado: " . $form['client_name'], 'INFO', 'email.log');
    
    // ============================================
    // PASO 2: OBTENER FOTOS ADJUNTAS
    // ============================================
    
    $stmt_photos = $pdo->prepare("SELECT * FROM form_photos WHERE form_id = ?");
    $stmt_photos->execute([$form_id]);
    $photos = $stmt_photos->fetchAll(PDO::FETCH_ASSOC);
    
    log_message("Fotos encontradas: " . count($photos), 'INFO', 'email.log');
    
    // ============================================
    // PASO 3: OBTENER SCOPE OF WORK
    // ============================================
    
    $stmt_scope = $pdo->prepare("SELECT * FROM scope_of_work WHERE form_id = ?");
    $stmt_scope->execute([$form_id]);
    $scope_tasks = $stmt_scope->fetchAll(PDO::FETCH_ASSOC);
    
    // ============================================
    // PASO 4: CONFIGURAR PHPMAILER
    // ============================================
    
    $mail = new PHPMailer(true);
    
    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';  // Servidor SMTP de Gmail
    $mail->SMTPAuth   = true;
    $mail->Username   = 'tu_email@gmail.com';  // ⚠️ CAMBIAR
    $mail->Password   = 'tu_app_password';      // ⚠️ CAMBIAR (App Password de Gmail)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    
    log_message("SMTP configurado", 'INFO', 'email.log');
    
    // ============================================
    // PASO 5: CONFIGURAR REMITENTE Y DESTINATARIO
    // ============================================
    
    $mail->setFrom('tu_email@gmail.com', APP_NAME);  // ⚠️ CAMBIAR
    
    // Destinatario principal
    $mail->addAddress('destinatario@empresa.com', 'Departamento de Ventas');  // ⚠️ CAMBIAR
    
    // Copia (opcional)
    // $mail->addCC('copia@empresa.com');
    
    // Copia oculta (opcional)
    // $mail->addBCC('gerencia@empresa.com');
    
    // Reply-To (email del cliente si existe)
    if (!empty($form['email'])) {
        $mail->addReplyTo($form['email'], $form['client_name']);
    }
    
    log_message("Destinatarios configurados", 'INFO', 'email.log');
    
    // ============================================
    // PASO 6: ADJUNTAR FOTOS
    // ============================================
    
    $photos_attached = 0;
    
    foreach ($photos as $photo) {
        if (file_exists($photo['photo_path'])) {
            $mail->addAttachment(
                $photo['photo_path'],
                $photo['photo_filename']
            );
            $photos_attached++;
            log_message("Foto adjunta: " . $photo['photo_filename'], 'INFO', 'email.log');
        } else {
            log_message("Foto no encontrada: " . $photo['photo_path'], 'WARNING', 'email.log');
        }
    }
    
    log_message("Total fotos adjuntas: $photos_attached", 'INFO', 'email.log');
    
    // ============================================
    // PASO 7: CONSTRUIR CONTENIDO DEL EMAIL (HTML)
    // ============================================
    
    $mail->isHTML(true);
    $mail->Subject = "Nuevo Formulario de Servicio - " . $form['client_name'];
    
    // Template HTML del email
    $email_body = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 800px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #001f54 0%, #003080 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background: #f4f7fc; padding: 30px; }
            .section { background: white; margin-bottom: 20px; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .section h2 { color: #001f54; margin-top: 0; border-bottom: 2px solid #001f54; padding-bottom: 10px; }
            .field { margin-bottom: 15px; }
            .field-label { font-weight: bold; color: #001f54; display: inline-block; min-width: 200px; }
            .field-value { color: #333; }
            .footer { background: #e1e8ed; padding: 20px; text-align: center; border-radius: 0 0 8px 8px; font-size: 12px; color: #6c757d; }
            .badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
            .badge-draft { background: #ffc107; color: #92400e; }
            .badge-priority { background: #dc3545; color: white; }
            ul { list-style: none; padding: 0; }
            ul li { padding: 8px 0; border-bottom: 1px solid #e1e8ed; }
            ul li:last-child { border-bottom: none; }
        </style>
    </head>
    <body>
        <div class="container">
            
            <!-- Header -->
            <div class="header">
                <h1>📋 ' . ($lang == 'en' ? 'Service Request Form' : 'Formulario de Solicitud de Servicio') . '</h1>
                <p>' . ($lang == 'en' ? 'Form ID' : 'ID de Formulario') . ': <strong>#' . $form_id . '</strong></p>
            </div>
            
            <div class="content">
                
                <!-- Section 1: Request Information -->
                <div class="section">
                    <h2>📄 ' . t('section_1', $lang) . '</h2>
                    <div class="field">
                        <span class="field-label">' . t('service_type', $lang) . ':</span>
                        <span class="field-value">' . htmlspecialchars($form['service_type'] ?? 'N/A') . '</span>
                    </div>
                    <div class="field">
                        <span class="field-label">' . t('request_type', $lang) . ':</span>
                        <span class="field-value">' . htmlspecialchars($form['request_type'] ?? 'N/A') . '</span>
                    </div>
                    <div class="field">
                        <span class="field-label">' . ($lang == 'en' ? 'Priority' : 'Prioridad') . ':</span>
                        <span class="field-value">' . htmlspecialchars($form['priority'] ?? 'N/A') . '</span>
                    </div>
                </div>
                
                <!-- Section 2: Client Information -->
                <div class="section">
                    <h2>👤 ' . t('section_2', $lang) . '</h2>
                    <div class="field">
                        <span class="field-label">' . t('client_name', $lang) . ':</span>
                        <span class="field-value"><strong>' . htmlspecialchars($form['client_name'] ?? 'N/A') . '</strong></span>
                    </div>
                    <div class="field">
                        <span class="field-label">' . ($lang == 'en' ? 'Company' : 'Empresa') . ':</span>
                        <span class="field-value">' . htmlspecialchars($form['company_name'] ?? 'N/A') . '</span>
                    </div>
                    <div class="field">
                        <span class="field-label">' . t('client_phone', $lang) . ':</span>
                        <span class="field-value">' . htmlspecialchars($form['phone'] ?? 'N/A') . '</span>
                    </div>
                    <div class="field">
                        <span class="field-label">' . t('client_email', $lang) . ':</span>
                        <span class="field-value">' . htmlspecialchars($form['email'] ?? 'N/A') . '</span>
                    </div>
                    <div class="field">
                        <span class="field-label">' . t('client_address', $lang) . ':</span>
                        <span class="field-value">' . htmlspecialchars($form['address'] ?? 'N/A') . ', ' . htmlspecialchars($form['city'] ?? '') . ', ' . htmlspecialchars($form['state'] ?? '') . '</span>
                    </div>
                </div>
                
                <!-- Section 4: Economic Information -->
                <div class="section">
                    <h2>💰 ' . t('section_4', $lang) . '</h2>
                    <div class="field">
                        <span class="field-label">' . t('total_cost', $lang) . ':</span>
                        <span class="field-value"><strong>$' . number_format($form['total_cost'] ?? 0, 2) . '</strong></span>
                    </div>
                    <div class="field">
                        <span class="field-label">' . ($lang == 'en' ? 'Payment Terms' : 'Términos de Pago') . ':</span>
                        <span class="field-value">' . htmlspecialchars($form['payment_terms'] ?? 'N/A') . '</span>
                    </div>
                </div>';
    
    // Agregar Scope of Work si existe
    if (!empty($scope_tasks)) {
        $email_body .= '
                <!-- Section 7: Scope of Work -->
                <div class="section">
                    <h2>📋 ' . t('section_7', $lang) . '</h2>
                    <ul>';
        
        foreach ($scope_tasks as $task) {
            $email_body .= '<li>✓ ' . htmlspecialchars($task['task_name']) . '</li>';
        }
        
        $email_body .= '
                    </ul>
                </div>';
    }
    
    // Agregar nota sobre fotos
    if (count($photos) > 0) {
        $email_body .= '
                <!-- Photos Info -->
                <div class="section">
                    <h2>📸 ' . t('section_8', $lang) . '</h2>
                    <p>' . count($photos) . ' ' . ($lang == 'en' ? 'photo(s) attached' : 'foto(s) adjunta(s)') . '</p>
                </div>';
    }
    
    $email_body .= '
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p>' . ($lang == 'en' ? 'This email was generated automatically by' : 'Este email fue generado automáticamente por') . ' <strong>' . APP_NAME . '</strong></p>
                <p>' . ($lang == 'en' ? 'Date' : 'Fecha') . ': ' . date('Y-m-d H:i:s') . '</p>
            </div>
            
        </div>
    </body>
    </html>
    ';
    
    $mail->Body = $email_body;
    
    // Versión texto plano (fallback)
    $mail->AltBody = strip_tags($email_body);
    
    log_message("Email HTML construido", 'INFO', 'email.log');
    
    // ============================================
    // PASO 8: ENVIAR EMAIL
    // ============================================
    
    $mail->send();
    
    log_message("Email enviado exitosamente", 'INFO', 'email.log');
    
    // ============================================
    // PASO 9: ACTUALIZAR ESTADO EN BD
    // ============================================
    
    $stmt_update = $pdo->prepare("UPDATE forms SET status = ?, sent_at = NOW() WHERE form_id = ?");
    $stmt_update->execute([STATUS_SENT, $form_id]);
    
    log_message("Estado actualizado a SENT", 'INFO', 'email.log');
    log_message("=== CORREO ENVIADO EXITOSAMENTE ===", 'INFO', 'email.log');
    
    // Redirigir con mensaje de éxito
    $_SESSION['message'] = t('form_submitted', $lang);
    $_SESSION['message_type'] = 'success';
    
    header('Location: ../index.php?sent=1');
    exit;
    
} catch (Exception $e) {
    log_message("ERROR al enviar email: " . $e->getMessage(), 'ERROR', 'email.log');
    
    $_SESSION['message'] = t('error_sending_form', $lang) . ': ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
    
    header('Location: ../index.php?error=1');
    exit;
}