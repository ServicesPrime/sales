<?php
/**
 * ============================================================
 * TOGGLE TASK ACTION
 * Marca/desmarca tareas como completadas (AJAX)
 * ============================================================
 */

require_once '../config.php';

// Verificar autenticación
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

// Sólo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

// Obtener datos JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['task_id']) || !isset($input['is_completed'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

$taskId = intval($input['task_id']);
$isCompleted = (bool)$input['is_completed'];

try {
    $task = new Task();
    
    if ($isCompleted) {
        $success = $task->complete($taskId);
    } else {
        $success = $task->uncomplete($taskId);
    }
    
    if ($success) {
        echo json_encode([
            'success' => true,
            'task_id' => $taskId,
            'is_completed' => $isCompleted
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al actualizar la tarea']);
    }
    
} catch (Exception $e) {
    error_log("Error en toggle_task.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error del servidor']);
}