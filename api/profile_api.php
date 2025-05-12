<?php
// Force JSON output even on errors
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

function sendJsonResponse($status, $message, $data = null)
{
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

try {
    require_once '../class/Database.php';
    require_once '../class/Profile.php';

    $database = new Database();
    $db = $database->connect();

    if (!$db) {
        throw new Exception("Database connection failed");
    }

    $profile = new Profile($db);

    $method = $_SERVER['REQUEST_METHOD'];

    // Check for _method override (simulated PUT via POST)
    if ($method === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
        $method = 'PUT';
    }

    switch ($method) {
        case 'GET':
            $result = $profile->getProfile();
            echo json_encode($result);
            break;

        case 'PUT':
            $formData = [];

            // Use $_POST to get form data (since we're now using POST with FormData)
            $formData = array_merge($formData, $_POST);

            // Handle file upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $target_dir = "../img/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $file_extension = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($file_extension, $allowed_types)) {
                    throw new Exception('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.');
                }

                $filename = "profile_" . time() . "." . $file_extension;
                $target_file = $target_dir . $filename;

                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                    $formData['profile_picture'] = "img/" . $filename;
                } else {
                    throw new Exception('Failed to upload profile picture.');
                }
            }

            // Validate required fields
            if (empty($formData['name']) || empty($formData['title']) || empty($formData['about'])) {
                throw new Exception('Name, title, and about are required fields.');
            }

            $result = $profile->update($formData);
            echo json_encode($result);
            break;

        default:
            throw new Exception('Method not supported');
    }
} catch (Exception $e) {
    sendJsonResponse('error', $e->getMessage());
}
?>