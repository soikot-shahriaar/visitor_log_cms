<?php
/**
 * Utility Class
 * Common functions and helpers
 * Visitor Log System CMS
 */

class Utils {
    
    /**
     * Sanitize input data
     */
    public static function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeInput'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate email address
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate phone number (basic validation)
     */
    public static function validatePhone($phone) {
        $phone = preg_replace('/[^0-9+\-\s\(\)]/', '', $phone);
        return strlen($phone) >= 10;
    }
    
    /**
     * Format date for display
     */
    public static function formatDate($date, $format = DISPLAY_DATE_FORMAT) {
        if (empty($date)) return '';
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        return $dateObj ? $dateObj->format($format) : $date;
    }
    
    /**
     * Format time for display
     */
    public static function formatTime($time, $format = DISPLAY_TIME_FORMAT) {
        if (empty($time)) return '';
        $timeObj = DateTime::createFromFormat('H:i:s', $time);
        return $timeObj ? $timeObj->format($format) : $time;
    }
    
    /**
     * Format datetime for display
     */
    public static function formatDateTime($datetime, $format = DISPLAY_DATETIME_FORMAT) {
        if (empty($datetime)) return '';
        $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        return $dateObj ? $dateObj->format($format) : $datetime;
    }
    
    /**
     * Generate random string
     */
    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    /**
     * Upload file
     */
    public static function uploadFile($file, $allowedTypes = ALLOWED_IMAGE_TYPES, $maxSize = MAX_FILE_SIZE) {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return ['success' => false, 'message' => 'No file uploaded'];
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'File upload error: ' . $file['error']];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File size exceeds maximum allowed size'];
        }
        
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedTypes)) {
            return ['success' => false, 'message' => 'File type not allowed'];
        }
        
        // Generate unique filename
        $fileName = self::generateRandomString(20) . '.' . $fileExtension;
        $uploadPath = UPLOAD_PATH . $fileName;
        
        // Create upload directory if it doesn't exist
        if (!is_dir(UPLOAD_PATH)) {
            mkdir(UPLOAD_PATH, 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return [
                'success' => true,
                'message' => 'File uploaded successfully',
                'filename' => $fileName,
                'path' => $uploadPath
            ];
        } else {
            return ['success' => false, 'message' => 'Failed to move uploaded file'];
        }
    }
    
    /**
     * Delete file
     */
    public static function deleteFile($filename) {
        $filePath = UPLOAD_PATH . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
    
    /**
     * Get file URL
     */
    public static function getFileUrl($filename) {
        if (empty($filename)) return '';
        return APP_URL . '/uploads/' . $filename;
    }
    
    /**
     * Redirect to URL
     */
    public static function redirect($url) {
        header("Location: $url");
        exit();
    }
    
    /**
     * Set flash message
     */
    public static function setFlashMessage($type, $message) {
        session_start();
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Get and clear flash message
     */
    public static function getFlashMessage() {
        session_start();
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $message;
        }
        return null;
    }
    
    /**
     * Generate pagination HTML
     */
    public static function generatePagination($currentPage, $totalPages, $baseUrl, $params = []) {
        if ($totalPages <= 1) return '';
        
        $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
        
        // Previous button
        if ($currentPage > 1) {
            $prevParams = array_merge($params, ['page' => $currentPage - 1]);
            $prevUrl = $baseUrl . '?' . http_build_query($prevParams);
            $html .= '<li class="page-item"><a class="page-link" href="' . $prevUrl . '">Previous</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }
        
        // Page numbers
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        if ($start > 1) {
            $firstParams = array_merge($params, ['page' => 1]);
            $firstUrl = $baseUrl . '?' . http_build_query($firstParams);
            $html .= '<li class="page-item"><a class="page-link" href="' . $firstUrl . '">1</a></li>';
            if ($start > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        for ($i = $start; $i <= $end; $i++) {
            $pageParams = array_merge($params, ['page' => $i]);
            $pageUrl = $baseUrl . '?' . http_build_query($pageParams);
            if ($i == $currentPage) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . $pageUrl . '">' . $i . '</a></li>';
            }
        }
        
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $lastParams = array_merge($params, ['page' => $totalPages]);
            $lastUrl = $baseUrl . '?' . http_build_query($lastParams);
            $html .= '<li class="page-item"><a class="page-link" href="' . $lastUrl . '">' . $totalPages . '</a></li>';
        }
        
        // Next button
        if ($currentPage < $totalPages) {
            $nextParams = array_merge($params, ['page' => $currentPage + 1]);
            $nextUrl = $baseUrl . '?' . http_build_query($nextParams);
            $html .= '<li class="page-item"><a class="page-link" href="' . $nextUrl . '">Next</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }
        
        $html .= '</ul></nav>';
        return $html;
    }
    
    /**
     * Log error
     */
    public static function logError($message, $file = null) {
        $logFile = __DIR__ . '/../logs/error.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message";
        if ($file) {
            $logMessage .= " in $file";
        }
        $logMessage .= PHP_EOL;
        
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Check if request is AJAX
     */
    public static function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Return JSON response
     */
    public static function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
?>

