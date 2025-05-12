<?php
class Account
{
    private $conn;
    private $table = 'admin';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Login method for admin only
    public function login($username, $password)
    {
        try {
            $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':username' => $username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && $password === $admin['password']) {
                session_start();
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];

                return [
                    'status' => 'success',
                    'message' => 'Login successful'
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Invalid credentials'
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Logout method
    public function logout()
    {
        session_start();
        session_destroy();
        return [
            'status' => 'success',
            'message' => 'Logged out successfully'
        ];
    }

    // Check if user is logged in as admin
    public function isAdminLoggedIn()
    {
        session_start();
        return isset($_SESSION['admin_id']);
    }
}
