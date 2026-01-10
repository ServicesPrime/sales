<?php
/**
 * ============================================================
 * RESCHEDULE EVENT - DRAG & DROP HANDLER
 * Simple and effective rescheduling
 * ============================================================
 */

header('Content-Type: application/json');

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

if (!isset($input['event_id']) || !isset($input['new_date'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$eventId = intval($input['event_id']);
$newDate = $input['new_date'];
$userId = getCurrentUserId();

// Validate date format (YYYY-MM-DD)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $newDate)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid date format. Use YYYY-MM-DD']);
    exit;
}

try {
    $event = new Event();
    
    // Get current event to verify ownership and calculate duration
    $currentEvent = $event->getById($eventId);
    
    if (!$currentEvent) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Event not found']);
        exit;
    }
    
    // Verify ownership
    if ($currentEvent['user_id'] != $userId) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Access denied']);
        exit;
    }
    
    // Calculate event duration in days
    $startTimestamp = strtotime($currentEvent['start_date']);
    $endTimestamp = strtotime($currentEvent['end_date']);
    $durationDays = ($endTimestamp - $startTimestamp) / 86400;
    
    // Calculate new end date
    $newEndDate = date('Y-m-d', strtotime($newDate . " +{$durationDays} days"));
    
    // Prepare update data
    $updateData = [
        'category_id' => $currentEvent['category_id'],
        'title' => $currentEvent['title'],
        'description' => $currentEvent['description'],
        'location' => $currentEvent['location'],
        'start_date' => $newDate,
        'end_date' => $newEndDate,
        'start_time' => $currentEvent['start_time'],
        'end_time' => $currentEvent['end_time'],
        'is_all_day' => $currentEvent['is_all_day'],
        'status' => $currentEvent['status'],
        'priority' => $currentEvent['priority'],
        'document_date' => $currentEvent['document_date'],
        'execution_date' => $newDate, // Update execution date
        'frequency_months' => $currentEvent['frequency_months'],
        'frequency_years' => $currentEvent['frequency_years'],
        'is_reschedulable' => $currentEvent['is_reschedulable'],
        'original_date' => $currentEvent['original_date']
    ];
    
    // Update the event
    $success = $event->update($eventId, $updateData);
    
    if ($success) {
        // Get updated event
        $updatedEvent = $event->getById($eventId);
        
        echo json_encode([
            'success' => true,
            'message' => 'Event rescheduled successfully',
            'event' => [
                'event_id' => $eventId,
                'title' => $updatedEvent['title'],
                'start_date' => $updatedEvent['start_date'],
                'end_date' => $updatedEvent['end_date'],
                'category_name' => $updatedEvent['category_name'],
                'color_hex' => $updatedEvent['color_hex']
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to update event']);
    }
    
} catch (Exception $e) {
    error_log("Error in reschedule_event.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error',
        'message' => ENVIRONMENT === 'development' ? $e->getMessage() : 'An error occurred'
    ]);
}