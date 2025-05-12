<?php
require_once 'class/Database.php';
require_once 'class/Project.php';

try {
    $database = new Database();
    $db = $database->connect();
    $project = new Project($db);

    $result = $project->read();
    $projects = $result['projects'] ?? [];
} catch (Exception $e) {
    $projects = [];
}
?>