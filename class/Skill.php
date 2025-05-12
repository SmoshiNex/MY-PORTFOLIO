<?php
class Skill
{
    private $conn;
    private $table = 'skills';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new skill
    public function create($data)
    {
        try {
            $query = "INSERT INTO {$this->table} (name, icon) VALUES (:name, :icon)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':name' => $data['name'] ?? '',
                ':icon' => $data['icon_url'] ?? ''
            ]);

            return [
                'status' => 'success',
                'message' => 'Skill added successfully'
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Read all skills
    public function read()
    {
        try {
            $query = "SELECT * FROM {$this->table} ORDER BY id DESC";
            $stmt = $this->conn->query($query);
            return [
                'status' => 'success',
                'skills' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Read single skill
    public function readOne($id)
    {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            $skill = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$skill) {
                throw new Exception('Skill not found');
            }

            return [
                'status' => 'success',
                'skill' => $skill
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Update a skill
    public function update($id, $data)
    {
        try {
            $query = "UPDATE {$this->table} SET name = :name, icon = :icon WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'] ?? '',
                ':icon' => $data['icon_url'] ?? ''
            ]);

            if ($stmt->rowCount() === 0) {
                throw new Exception('Skill not found or no changes made');
            }

            return [
                'status' => 'success',
                'message' => 'Skill updated successfully'
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Delete a skill
    public function delete($id)
    {
        try {
            $query = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() === 0) {
                throw new Exception('Skill not found');
            }

            return [
                'status' => 'success',
                'message' => 'Skill deleted successfully'
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
