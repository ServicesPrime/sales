<?php
/**
 * ============================================================
 * DELETE EVENT - WITH SERIES OPTION
 * Permite borrar un evento individual o toda la serie
 * ============================================================
 */

header('Content-Type: application/json');

require_once '../config.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['event_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing event_id']);
    exit;
}

$eventId = intval($input['event_id']);
$deleteSeries = isset($input['delete_series']) && $input['delete_series'] === true;
$userId = getCurrentUserId();

try {
    $event = new Event();
    
    // Obtener evento para verificar ownership
    $currentEvent = $event->getById($eventId);
    
    if (!$currentEvent) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Event not found']);
        exit;
    }
    
    // Verificar ownership
    if ($currentEvent['user_id'] != $userId) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Access denied']);
        exit;
    }
    
    if ($deleteSeries) {
        // ============================================================
        // BORRAR TODA LA SERIE
        // ============================================================
        
        // Identificar la serie por título
        $seriesTitle = $currentEvent['title'];
        
        // Obtener conexión directa
        $db = Database::getInstance()->getConnection();
        
        // Contar eventos en la serie
        $countQuery = "SELECT COUNT(*) as total 
                       FROM events 
                       WHERE user_id = :user_id 
                       AND title = :title 
                       AND is_active = TRUE";
        
        $countStmt = $db->prepare($countQuery);
        $countStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $countStmt->bindParam(':title', $seriesTitle);
        $countStmt->execute();
        $count = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Soft delete de toda la serie
        $deleteQuery = "UPDATE events 
                        SET is_active = FALSE, updated_at = NOW()
                        WHERE user_id = :user_id 
                        AND title = :title 
                        AND is_active = TRUE";
        
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $deleteStmt->bindParam(':title', $seriesTitle);
        
        if ($deleteStmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => "Series deleted: {$count} events removed",
                'deleted_count' => $count,
                'delete_type' => 'series'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to delete series']);
        }
        
    } else {
        // ============================================================
        // BORRAR SOLO ESTE EVENTO
        // ============================================================
        
        $success = $event->delete($eventId);
        
        if ($success) {
            echo json_encode([
                'success' => true,
                'message' => 'Event deleted successfully',
                'deleted_count' => 1,
                'delete_type' => 'single'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to delete event']);
        }
    }
    
} catch (Exception $e) {
    error_log("Error in delete_event.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error',
        'message' => ENVIRONMENT === 'development' ? $e->getMessage() : 'An error occurred'
    ]);
}