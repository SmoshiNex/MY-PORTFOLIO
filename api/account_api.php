<?php
header('Content-Type: application/json');
require_once '../class/Database.php';
require_once '../class/Account.php';

// Initialize database connection
$database = new Database();
$db = $database->connect();
$account = new Account($db);

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method !== 'GET') {
        throw new Exception('Only GET method is supported for admin authentication');
    }

    if (!isset($_GET['action'])) {
        throw new Exception('Action is required');
    }

    switch ($_GET['action']) {
        case 'login':
            if (!isset($_GET['username']) || !isset($_GET['password'])) {
                throw new Exception('Username and password are required');
            }
            $result = $account->login($_GET['username'], $_GET['password']);
            break;

        case 'logout':
            $result = $account->logout();
            break;

        case 'check':
            $result = [
                'status' => 'success',
                'isLoggedIn' => $account->isAdminLoggedIn()
            ];
            break;

        default:
            throw new Exception('Invalid action');
    }

    echo json_encode($result);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
