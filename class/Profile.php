<?php
class Profile
{
    private $conn;
    private $table = 'profile';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get profile information
    public function getProfile()
    {
        try {
            // Get the latest profile record
            $query = "SELECT * FROM {$this->table} WHERE name IS NOT NULL AND name != '' ORDER BY id DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $profile = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$profile) {
                return [
                    'status' => 'error',
                    'message' => 'No profile found'
                ];
            }

            return [
                'status' => 'success',
                'profile' => $profile
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    // Update profile
    public function update($data)
    {
        try {
            // Get the latest profile ID
            $query = "SELECT id FROM {$this->table} WHERE name IS NOT NULL AND name != '' ORDER BY id DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $profileId = $result ? $result['id'] : null;

            // Prepare the query and parameters dynamically
            $params = [
                ':name' => $data['name'],
                ':title' => $data['title'],
                ':about' => $data['about']
            ];

            if (!$profileId) {
                // Insert new profile if none exists
                $query = "INSERT INTO {$this->table} (name, title, about";
                $values = "VALUES (:name, :title, :about";

                if (!empty($data['profile_picture'])) {
                    $query .= ", profile_picture";
                    $values .= ", :profile_picture";
                    $params[':profile_picture'] = $data['profile_picture'];
                }

                $query .= ") " . $values . ")";
            } else {
                // Update existing profile
                $query = "UPDATE {$this->table} SET name = :name, title = :title, about = :about";

                if (!empty($data['profile_picture'])) {
                    $query .= ", profile_picture = :profile_picture";
                    $params[':profile_picture'] = $data['profile_picture'];
                }

                $query .= " WHERE id = :id";
                $params[':id'] = $profileId;
            }

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            return [
                'status' => 'success',
                'message' => 'Profile updated successfully'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
?>