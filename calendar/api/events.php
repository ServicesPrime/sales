<?php
/**
 * ============================================================
 * EVENTS API
 * Endpoint para obtener datos de eventos (AJAX)
 * ============================================================
 */

header('Content-Type: application/json');

require_once '../config.php';

// Verificar autenticación
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$userId = getCurrentUserId();

// Obtener ID del evento
$eventId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$eventId) {
    http_response_code(400);
    echo json_encode(['error' => 'Event ID required']);
    exit;
}

try {
    $event = new Event();
    $eventData = $event->getById($eventId);
    
    if (!$eventData) {
        http_response_code(404);
        echo json_encode(['error' => 'Event not found']);
        exit;
    }
    
    // Verificar que el evento pertenece al usuario
    if ($eventData['user_id'] != $userId) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        exit;
    }
    
    // Devolver datos del evento
    echo json_encode($eventData);
    
} catch (Exception $e) {
    error_log("Error loading event: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'message' => $e->getMessage()]);
}