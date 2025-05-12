<?php
require_once 'class/Database.php';
require_once 'class/Skill.php';

$database = new Database();
$db = $database->connect();
$skill = new Skill($db);

$result = $skill->read();
if ($result['status'] === 'success' && !empty($result['skills'])) {
    foreach ($result['skills'] as $skillItem) {
        echo '
        <div class="skill-card">
            <div class="skill-icon">
                <i class="' . htmlspecialchars($skillItem['icon']) . '"></i>
            </div>
            <h3 class="skill-name">' . htmlspecialchars($skillItem['name']) . '</h3>
        </div>';
    }
}
?>