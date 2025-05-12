<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'class/Database.php';
require_once 'class/Profile.php';

try {
    $database = new Database();
    $db = $database->connect();
    
    if (!$db) {
        error_log("Database connection failed");
        throw new Exception("Could not connect to database");
    }
    
    $profile = new Profile($db);
    $result = $profile->getProfile();
    
    error_log("Profile result: " . print_r($result, true));
    
    if ($result['status'] === 'success' && !empty($result['profile'])) {
        $profileData = $result['profile'];
    } else {
        error_log("No profile data found or error: " . ($result['message'] ?? 'Unknown error'));
        $profileData = [
            'name' => '',
            'title' => '',
            'about' => '',
            'profile_picture' => 'img/pic.jpg'
        ];
    }
} catch (Exception $e) {
    error_log("Error in get_profile.php: " . $e->getMessage());
    $profileData = [
        'name' => '',
        'title' => '',
        'about' => '',
        'profile_picture' => 'img/pic.jpg'
    ];
}
?>