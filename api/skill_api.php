<?php
header('Content-Type: application/json');
require_once '../class/Database.php';
require_once '../class/Skill.php';

// Initialize database connection
$database = new Database();
$db = $database->connect();
$skill = new Skill($db);

// Get the HTTP method, supporting method override
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $method = 'PUT';
}

try {
    switch ($method) {
        case 'GET':
            // Check if specific skill ID is requested
            if (isset($_GET['id'])) {
                $result = $skill->readOne($_GET['id']);
            } else {
                // Get all skills
                $result = $skill->read();
            }
            echo json_encode($result);
            break;

        case 'POST':
            // Create new skill
            $data = [
                'name' => $_POST['name'] ?? null,
                'icon' => $_POST['icon'] ?? null
            ];

            if (!$data['name'] || !$data['icon']) {
                throw new Exception('Skill name and Font Awesome icon class are required');
            }

            $result = $skill->create($data);
            echo json_encode($result);
            break;

        case 'PUT':
            // Update existing skill
            $data = [
                'id' => $_POST['id'] ?? null,
                'name' => $_POST['name'] ?? null,
                'icon' => $_POST['icon'] ?? null
            ];

            if (!$data['id'] || !$data['name'] || !$data['icon']) {
                throw new Exception('Skill ID, name, and icon are required for updates');
            }

            $result = $skill->update($data['id'], $data);
            echo json_encode($result);
            break;

        case 'DELETE':
            // Delete skill
            $id = $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception('Skill ID is required');
            }

            $result = $skill->delete($id);
            echo json_encode($result);
            break;

        default:
            throw new Exception('Method not supported');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
