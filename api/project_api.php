<?php
header('Content-Type: application/json');
require_once '../class/Database.php';
require_once '../class/Project.php';

// Initialize database connection
$database = new Database();
$db = $database->connect();
$project = new Project($db);

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Check if specific project ID is requested
            if (isset($_GET['id'])) {
                $result = $project->readOne($_GET['id']);
            } else {
                // Get all projects
                $result = $project->read();
            }
            echo json_encode($result);
            break;

        case 'POST':
            // Create new project
            $data = [
                'project_title' => $_POST['title'] ?? null,
                'project_description' => $_POST['description'] ?? null,
                'project_tech' => $_POST['technologies'] ?? null,
                'project_link' => $_POST['demo_url'] ?? null,
                'github_link' => $_POST['github_url'] ?? null
            ];

            $result = $project->create($data, $_FILES['image'] ?? null);
            echo json_encode($result);
            break;

        case 'PUT':
            // Update existing project
            parse_str(file_get_contents("php://input"), $putData);
            $id = $putData['id'] ?? null;

            if (!$id) {
                throw new Exception('Project ID is required');
            }

            $result = $project->update($id, $putData, $_FILES['image'] ?? null);
            echo json_encode($result);
            break;

        case 'DELETE':
            // Delete project
            parse_str(file_get_contents("php://input"), $deleteData);
            $id = $deleteData['id'] ?? $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception('Project ID is required');
            }

            $result = $project->delete($id);
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
