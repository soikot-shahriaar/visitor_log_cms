<?php
/**
 * Admin Class
 * Handles admin authentication and management
 * Visitor Log System CMS
 */

class Admin {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Authenticate admin user
     */
    public function login($username, $password) {
        try {
            $sql = "SELECT id, username, email, password_hash, full_name, is_active 
                    FROM admins 
                    WHERE (username = ? OR email = ?) AND is_active = 1";
            
            $admin = $this->db->fetch($sql, [$username, $username]);
            
            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Start session and store admin data
                session_start();
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['login_time'] = time();
                
                // Update last login time
                $this->updateLastLogin($admin['id']);
                
                return [
                    'success' => true,
                    'message' => 'Login successful',
                    'admin' => $admin
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid username/email or password'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Check if admin is logged in
     */
    public function isLoggedIn() {
        session_start();
        
        if (!isset($_SESSION['admin_id']) || !isset($_SESSION['login_time'])) {
            return false;
        }
        
        // Check session timeout
        if (time() - $_SESSION['login_time'] > SESSION_TIMEOUT) {
            $this->logout();
            return false;
        }
        
        return true;
    }
    
    /**
     * Get current admin data
     */
    public function getCurrentAdmin() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['admin_id'],
            'username' => $_SESSION['admin_username'],
            'name' => $_SESSION['admin_name'],
            'email' => $_SESSION['admin_email']
        ];
    }
    
    /**
     * Logout admin
     */
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        
        return [
            'success' => true,
            'message' => 'Logged out successfully'
        ];
    }
    
    /**
     * Create new admin
     */
    public function createAdmin($username, $email, $password, $fullName) {
        try {
            // Check if username or email already exists
            $sql = "SELECT id FROM admins WHERE username = ? OR email = ?";
            $existing = $this->db->fetch($sql, [$username, $email]);
            
            if ($existing) {
                return [
                    'success' => false,
                    'message' => 'Username or email already exists'
                ];
            }
            
            // Validate password length
            if (strlen($password) < PASSWORD_MIN_LENGTH) {
                return [
                    'success' => false,
                    'message' => 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long'
                ];
            }
            
            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new admin
            $sql = "INSERT INTO admins (username, email, password_hash, full_name) 
                    VALUES (?, ?, ?, ?)";
            
            $result = $this->db->execute($sql, [$username, $email, $passwordHash, $fullName]);
            
            if ($result > 0) {
                return [
                    'success' => true,
                    'message' => 'Admin created successfully',
                    'admin_id' => $this->db->lastInsertId()
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to create admin'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating admin: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update admin password
     */
    public function updatePassword($adminId, $currentPassword, $newPassword) {
        try {
            // Get current admin data
            $sql = "SELECT password_hash FROM admins WHERE id = ?";
            $admin = $this->db->fetch($sql, [$adminId]);
            
            if (!$admin) {
                return [
                    'success' => false,
                    'message' => 'Admin not found'
                ];
            }
            
            // Verify current password
            if (!password_verify($currentPassword, $admin['password_hash'])) {
                return [
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ];
            }
            
            // Validate new password length
            if (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
                return [
                    'success' => false,
                    'message' => 'New password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long'
                ];
            }
            
            // Hash new password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update password
            $sql = "UPDATE admins SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $result = $this->db->execute($sql, [$newPasswordHash, $adminId]);
            
            if ($result > 0) {
                return [
                    'success' => true,
                    'message' => 'Password updated successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update password'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error updating password: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update last login time
     */
    private function updateLastLogin($adminId) {
        $sql = "UPDATE admins SET updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $this->db->execute($sql, [$adminId]);
    }
    
    /**
     * Get all admins
     */
    public function getAllAdmins() {
        $sql = "SELECT id, username, email, full_name, created_at, updated_at, is_active 
                FROM admins 
                ORDER BY created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Generate CSRF token
     */
    public function generateCSRFToken() {
        session_start();
        $token = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_NAME] = $token;
        return $token;
    }
    
    /**
     * Verify CSRF token
     */
    public function verifyCSRFToken($token) {
        session_start();
        return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }
}
?>

