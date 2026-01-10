<?php
/**
 * ============================================================
 * TOGGLE WORK STATUS ACTION
 * Changes status of work items (events) via AJAX
 * ============================================================
 */

require_once '../config.php';

// Verify authentication
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Get JSON data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['event_id']) || !isset($input['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

$eventId = intval($input['event_id']);
$newStatus = sanitize($input['status']);

// Validate status
$validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
if (!in_array($newStatus, $validStatuses)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid status']);
    exit;
}

try {
    $event = new Event();
    
    // Update event status
    $success = $event->changeStatus($eventId, $newStatus);
    
    if ($success) {
        echo json_encode([
            'success' => true,
            'event_id' => $eventId,
            'status' => $newStatus
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error updating work item']);
    }
    
} catch (Exception $e) {
    error_log("Error in toggle_work_status.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
}