<?php
/**
 * Visitor Class
 * Handles visitor management operations
 * Visitor Log System CMS
 */

class Visitor {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Add new visitor
     */
    public function addVisitor($data) {
        try {
            $sql = "INSERT INTO visitors (
                        visitor_name, visitor_email, visitor_phone, visitor_company,
                        visit_date, visit_time, purpose_of_visit, host_person, host_department,
                        id_document_type, id_document_number, vehicle_number, notes, created_by
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $data['visitor_name'],
                $data['visitor_email'] ?? null,
                $data['visitor_phone'] ?? null,
                $data['visitor_company'] ?? null,
                $data['visit_date'],
                $data['visit_time'],
                $data['purpose_of_visit'],
                $data['host_person'],
                $data['host_department'] ?? null,
                $data['id_document_type'] ?? 'national_id',
                $data['id_document_number'] ?? null,
                $data['vehicle_number'] ?? null,
                $data['notes'] ?? null,
                $data['created_by']
            ];
            
            $result = $this->db->execute($sql, $params);
            
            if ($result > 0) {
                $visitorId = $this->db->lastInsertId();
                
                // Log the check-in action
                $this->logVisitorAction($visitorId, 'check_in', $data['created_by'], 'Visitor checked in');
                
                return [
                    'success' => true,
                    'message' => 'Visitor added successfully',
                    'visitor_id' => $visitorId
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to add visitor'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error adding visitor: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update visitor information
     */
    public function updateVisitor($visitorId, $data, $updatedBy) {
        try {
            $sql = "UPDATE visitors SET 
                        visitor_name = ?, visitor_email = ?, visitor_phone = ?, visitor_company = ?,
                        visit_date = ?, visit_time = ?, purpose_of_visit = ?, host_person = ?, 
                        host_department = ?, id_document_type = ?, id_document_number = ?, 
                        vehicle_number = ?, notes = ?, updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?";
            
            $params = [
                $data['visitor_name'],
                $data['visitor_email'] ?? null,
                $data['visitor_phone'] ?? null,
                $data['visitor_company'] ?? null,
                $data['visit_date'],
                $data['visit_time'],
                $data['purpose_of_visit'],
                $data['host_person'],
                $data['host_department'] ?? null,
                $data['id_document_type'] ?? 'national_id',
                $data['id_document_number'] ?? null,
                $data['vehicle_number'] ?? null,
                $data['notes'] ?? null,
                $visitorId
            ];
            
            $result = $this->db->execute($sql, $params);
            
            if ($result > 0) {
                // Log the update action
                $this->logVisitorAction($visitorId, 'update', $updatedBy, 'Visitor information updated');
                
                return [
                    'success' => true,
                    'message' => 'Visitor updated successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No changes made or visitor not found'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error updating visitor: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Check out visitor
     */
    public function checkOutVisitor($visitorId, $checkoutTime, $performedBy, $notes = null) {
        try {
            $sql = "UPDATE visitors SET 
                        checkout_time = ?, status = 'checked_out', updated_at = CURRENT_TIMESTAMP
                    WHERE id = ? AND status = 'checked_in'";
            
            $result = $this->db->execute($sql, [$checkoutTime, $visitorId]);
            
            if ($result > 0) {
                // Log the check-out action
                $this->logVisitorAction($visitorId, 'check_out', $performedBy, $notes ?? 'Visitor checked out');
                
                return [
                    'success' => true,
                    'message' => 'Visitor checked out successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Visitor not found or already checked out'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error checking out visitor: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete visitor
     */
    public function deleteVisitor($visitorId, $performedBy) {
        try {
            // First check if visitor exists
            $visitor = $this->getVisitorById($visitorId);
            if (!$visitor) {
                return [
                    'success' => false,
                    'message' => 'Visitor not found'
                ];
            }
            
            // Log the cancellation action before deletion
            $this->logVisitorAction($visitorId, 'cancel', $performedBy, 'Visitor record cancelled/deleted');
            
            // Delete visitor (logs will be deleted due to CASCADE)
            $sql = "DELETE FROM visitors WHERE id = ?";
            $result = $this->db->execute($sql, [$visitorId]);
            
            if ($result > 0) {
                return [
                    'success' => true,
                    'message' => 'Visitor deleted successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to delete visitor'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error deleting visitor: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get visitor by ID
     */
    public function getVisitorById($visitorId) {
        $sql = "SELECT v.*, a.full_name as created_by_name 
                FROM visitors v 
                LEFT JOIN admins a ON v.created_by = a.id 
                WHERE v.id = ?";
        return $this->db->fetch($sql, [$visitorId]);
    }
    
    /**
     * Get all visitors with pagination and filtering
     */
    public function getVisitors($page = 1, $limit = RECORDS_PER_PAGE, $filters = []) {
        $offset = ($page - 1) * $limit;
        $whereConditions = [];
        $params = [];
        
        // Build WHERE conditions based on filters
        if (!empty($filters['search'])) {
            $whereConditions[] = "(v.visitor_name LIKE ? OR v.visitor_email LIKE ? OR v.visitor_company LIKE ? OR v.host_person LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($filters['date_from'])) {
            $whereConditions[] = "v.visit_date >= ?";
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $whereConditions[] = "v.visit_date <= ?";
            $params[] = $filters['date_to'];
        }
        
        if (!empty($filters['status'])) {
            $whereConditions[] = "v.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['host_person'])) {
            $whereConditions[] = "v.host_person LIKE ?";
            $params[] = '%' . $filters['host_person'] . '%';
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM visitors v {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'];
        
        // Get visitors data
        $sql = "SELECT v.*, a.full_name as created_by_name 
                FROM visitors v 
                LEFT JOIN admins a ON v.created_by = a.id 
                {$whereClause}
                ORDER BY v.visit_date DESC, v.visit_time DESC 
                LIMIT {$limit} OFFSET {$offset}";
        
        $visitors = $this->db->fetchAll($sql, $params);
        
        return [
            'visitors' => $visitors,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ];
    }
    
    /**
     * Get today's visitors
     */
    public function getTodayVisitors() {
        $today = date('Y-m-d');
        $sql = "SELECT v.*, a.full_name as created_by_name 
                FROM visitors v 
                LEFT JOIN admins a ON v.created_by = a.id 
                WHERE v.visit_date = ? 
                ORDER BY v.visit_time DESC";
        return $this->db->fetchAll($sql, [$today]);
    }
    
    /**
     * Get visitor statistics
     */
    public function getVisitorStats() {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');
        
        // Today's visitors
        $todayCount = $this->db->fetch(
            "SELECT COUNT(*) as count FROM visitors WHERE visit_date = ?", 
            [$today]
        )['count'];
        
        // This month's visitors
        $monthCount = $this->db->fetch(
            "SELECT COUNT(*) as count FROM visitors WHERE visit_date LIKE ?", 
            [$thisMonth . '%']
        )['count'];
        
        // Currently checked in
        $checkedInCount = $this->db->fetch(
            "SELECT COUNT(*) as count FROM visitors WHERE status = 'checked_in'", 
            []
        )['count'];
        
        // Total visitors
        $totalCount = $this->db->fetch(
            "SELECT COUNT(*) as count FROM visitors", 
            []
        )['count'];
        
        return [
            'today' => $todayCount,
            'this_month' => $monthCount,
            'checked_in' => $checkedInCount,
            'total' => $totalCount
        ];
    }
    
    /**
     * Log visitor action
     */
    private function logVisitorAction($visitorId, $action, $performedBy, $notes = null) {
        $sql = "INSERT INTO visitor_logs (visitor_id, action, performed_by, notes) 
                VALUES (?, ?, ?, ?)";
        $this->db->execute($sql, [$visitorId, $action, $performedBy, $notes]);
    }
    
    /**
     * Get visitor logs
     */
    public function getVisitorLogs($visitorId) {
        $sql = "SELECT vl.*, a.full_name as performed_by_name 
                FROM visitor_logs vl 
                LEFT JOIN admins a ON vl.performed_by = a.id 
                WHERE vl.visitor_id = ? 
                ORDER BY vl.action_time DESC";
        return $this->db->fetchAll($sql, [$visitorId]);
    }
    
    /**
     * Search visitors
     */
    public function searchVisitors($searchTerm, $limit = 10) {
        $sql = "SELECT v.*, a.full_name as created_by_name 
                FROM visitors v 
                LEFT JOIN admins a ON v.created_by = a.id 
                WHERE v.visitor_name LIKE ? OR v.visitor_email LIKE ? OR v.visitor_company LIKE ? 
                ORDER BY v.visit_date DESC, v.visit_time DESC 
                LIMIT ?";
        
        $searchPattern = '%' . $searchTerm . '%';
        return $this->db->fetchAll($sql, [$searchPattern, $searchPattern, $searchPattern, $limit]);
    }
}
?>

