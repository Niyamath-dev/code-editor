<?php
/**
 * Code History API
 * Handles saving and loading code history for users
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
requireAuth();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$db = Database::getInstance()->getConnection();

switch ($method) {
    case 'POST':
        // Save code to history
        saveCodeHistory($db);
        break;
    case 'GET':
        // Get user's code history
        getCodeHistory($db);
        break;
    case 'DELETE':
        // Delete a history item
        deleteCodeHistory($db);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function saveCodeHistory($db) {
    $userId = $_SESSION['user_id'];
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['title'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Title is required']);
        return;
    }

    $title = trim($data['title']);
    $htmlCode = $data['html'] ?? '';
    $cssCode = $data['css'] ?? '';
    $jsCode = $data['js'] ?? '';

    if (empty($title)) {
        http_response_code(400);
        echo json_encode(['error' => 'Title cannot be empty']);
        return;
    }

    try {
        $stmt = $db->prepare("INSERT INTO code_history (user_id, title, html_code, css_code, js_code) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $title, $htmlCode, $cssCode, $jsCode]);

        echo json_encode([
            'success' => true,
            'id' => $db->lastInsertId(),
            'message' => 'Code saved to history'
        ]);
    } catch (PDOException $e) {
        error_log("Save history error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save code history']);
    }
}

function getCodeHistory($db) {
    $userId = $_SESSION['user_id'];
    $id = $_GET['id'] ?? null;

    try {
        if ($id) {
            // Get single history item
            $stmt = $db->prepare("SELECT id, title, html_code, css_code, js_code, created_at FROM code_history WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $userId]);
            $history = $stmt->fetchAll();
        } else {
            // Get all history items with code
            $stmt = $db->prepare("SELECT id, title, html_code, css_code, js_code, created_at FROM code_history WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
            $history = $stmt->fetchAll();
        }

        echo json_encode([
            'success' => true,
            'history' => $history
        ]);
    } catch (PDOException $e) {
        error_log("Get history error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Failed to load code history']);
    }
}

function deleteCodeHistory($db) {
    $userId = $_SESSION['user_id'];
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'History ID is required']);
        return;
    }

    try {
        $stmt = $db->prepare("DELETE FROM code_history WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'History item deleted'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'History item not found']);
        }
    } catch (PDOException $e) {
        error_log("Delete history error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete code history']);
    }
}
