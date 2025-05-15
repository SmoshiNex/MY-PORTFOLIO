<?php
class Project
{
    private $conn;
    private $table = 'projects';
    private $upload_dir = '../img/';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new project
    public function create($data, $file)
    {
        try {
            // Handle image upload
            $image_path = $this->handleImageUpload($file);

            $query = "INSERT INTO {$this->table} (title, description, image, technologies, project_link, github_link) 
                     VALUES (:title, :description, :image, :technologies, :project_link, :github_link)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':title' => $data['title'] ?? '',
                ':description' => $data['description'] ?? '',
                ':image' => $image_path,
                ':technologies' => $data['technologies'] ?? '',
                ':project_link' => $data['project_link'] ?? '',
                ':github_link' => $data['github_link'] ?? ''
            ]);

            return [
                'status' => 'success',
                'message' => 'Project added successfully'
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Read all projects
    public function read()
    {
        try {
            $query = "SELECT * FROM {$this->table} ORDER BY id DESC";
            $stmt = $this->conn->query($query);
            return [
                'status' => 'success',
                'projects' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Read single project
    public function readOne($id)
    {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            $project = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$project) {
                throw new Exception('Project not found');
            }

            return [
                'status' => 'success',
                'project' => $project
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }    // Update a project
    public function update($id, $data, $file = null)
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            // Handle image upload if new image is provided
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $image_path = $this->handleImageUpload($file);
                $fields[] = "image = :image";
                $params[':image'] = $image_path;

                // Delete old image
                $old_image = $this->getProjectImage($id);
                if ($old_image) {
                    $this->deleteImage($old_image);
                }
            }

            // Add other fields if they exist and are not null
            if (!empty($data['title'])) {
                $fields[] = "title = :title";
                $params[':title'] = $data['title'];
            }
            if (isset($data['description'])) {
                $fields[] = "description = :description";
                $params[':description'] = $data['description'];
            }
            if (isset($data['technologies'])) {
                $fields[] = "technologies = :technologies";
                $params[':technologies'] = $data['technologies'];
            }
            if (isset($data['demo_url'])) {
                $fields[] = "project_link = :project_link";
                $params[':project_link'] = $data['demo_url'];
            }
            if (isset($data['github_url'])) {
                $fields[] = "github_link = :github_link";
                $params[':github_link'] = $data['github_url'];
            }

            // Also check for direct project_link and github_link fields
            if (isset($data['project_link']) && !isset($data['demo_url'])) {
                $fields[] = "project_link = :project_link";
                $params[':project_link'] = $data['project_link'];
            }
            if (isset($data['github_link']) && !isset($data['github_url'])) {
                $fields[] = "github_link = :github_link";
                $params[':github_link'] = $data['github_link'];
            }

            // Only proceed with update if there are fields to update
            if (empty($fields)) {
                return [
                    'status' => 'success',
                    'message' => 'No changes to update'
                ];
            }

            $query = "UPDATE {$this->table} SET " . implode(", ", $fields) . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                throw new Exception('Project not found or no changes made');
            }

            return [
                'status' => 'success',
                'message' => 'Project updated successfully'
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Delete a project
    public function delete($id)
    {
        try {
            // Delete project image first
            $image_path = $this->getProjectImage($id);
            if ($image_path) {
                $this->deleteImage($image_path);
            }

            // Delete project from database
            $query = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() === 0) {
                throw new Exception('Project not found');
            }

            return [
                'status' => 'success',
                'message' => 'Project deleted successfully'
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Helper method to handle image upload
    private function handleImageUpload($file)
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Project image is required.');
        }

        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_extension, $allowed_extensions)) {
            throw new Exception('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.');
        }

        $new_filename = 'project_' . time() . '.' . $file_extension;
        $upload_path = $this->upload_dir . $new_filename;

        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception('Failed to upload image.');
        }

        return 'img/' . $new_filename;
    }

    // Helper method to get project image path
    private function getProjectImage($id)
    {
        $query = "SELECT image FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['image'] : null;
    }

    // Helper method to delete image file
    private function deleteImage($image_path)
    {
        $full_path = '../' . $image_path;
        if (file_exists($full_path)) {
            unlink($full_path);
        }
    }
}
