<?php
require_once '../config.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$userId = getCurrentUserId();
$event = new Event();

$eventData = [
    'user_id' => $userId,
    'category_id' => !empty($_POST['category_id']) ? intval($_POST['category_id']) : null,
    'title' => sanitize($_POST['title']),
    'description' => sanitize($_POST['description'] ?? ''),
    'location' => sanitize($_POST['location'] ?? ''),
    'start_date' => $_POST['start_date'],
    'end_date' => $_POST['end_date'],
    'start_time' => !empty($_POST['start_time']) ? $_POST['start_time'] : null,
    'end_time' => !empty($_POST['end_time']) ? $_POST['end_time'] : null,
    'is_all_day' => isset($_POST['is_all_day']),
    'is_recurring' => false,
    'status' => $_POST['status'] ?? 'pending',
    'priority' => $_POST['priority'] ?? 'normal',
    'document_date' => !empty($_POST['document_date']) ? $_POST['document_date'] : null,
    'execution_date' => !empty($_POST['execution_date']) ? $_POST['execution_date'] : null,
    'frequency_months' => !empty($_POST['frequency_months']) ? intval($_POST['frequency_months']) : null,
    'frequency_years' => !empty($_POST['frequency_years']) ? intval($_POST['frequency_years']) : 1,
    'is_reschedulable' => 1,
    'original_date' => $_POST['start_date']
];

if ($eventData['is_all_day']) {
    $eventData['start_time'] = null;
    $eventData['end_time'] = null;
}

try {
    $eventId = !empty($_POST['event_id']) ? intval($_POST['event_id']) : null;
    
    if ($eventId) {
        unset($eventData['user_id']);
        $success = $event->update($eventId, $eventData);
        setFlashMessage($success ? 'Actualizado' : 'Error', $success ? 'success' : 'error');
    } else {
        $newEventId = $event->create($eventData);
        
        if (!$newEventId) {
            setFlashMessage('Error al crear', 'error');
            header('Location: ../index.php');
            exit;
        }
        
        $eventsCreated = 1;
        $freq = $eventData['frequency_months'];
        $dur = $eventData['frequency_years'];
        
        if ($freq && $freq > 0 && $dur > 0) {
            $total = floor(($dur * 12) / $freq);
            $base = $eventData['start_date'];
            $baseObj = new DateTime($base);
            $origDay = (int)$baseObj->format('d');
            
            for ($i = 1; $i <= $total; $i++) {
                $add = $i * $freq;
                $new = new DateTime($base);
                $new->modify("+$add months");
                
                $y = (int)$new->format('Y');
                $m = (int)$new->format('m');
                $last = (int)$new->format('t');
                
                $day = ($origDay > $last) ? $last : $origDay;
                $new->setDate($y, $m, $day);
                
                $rd = $eventData;
                $rd['start_date'] = $new->format('Y-m-d');
                $rd['end_date'] = $new->format('Y-m-d');
                $rd['execution_date'] = null;
                
                if ($event->create($rd)) {
                    $eventsCreated++;
                }
            }
            
            setFlashMessage("Creados $eventsCreated eventos", 'success');
        } else {
            setFlashMessage('Evento creado', 'success');
        }
    }
} catch (Exception $e) {
    setFlashMessage('Error: ' . $e->getMessage(), 'error');
}

header("Location: ../index.php");
exit;