<?php
include_once '../../base-model.php';

class AdminModel extends BaseModel
{
    public function getDashboardStats()
    {
        $stats = [];


        $stmt = $this->dbConn->query("SELECT COUNT(*) FROM users");
        $stats['total_users'] = $stmt->fetchColumn();


        $stmt = $this->dbConn->query("SELECT COUNT(*) FROM positions WHERE status_id = 1");
        $stats['total_active_jobs'] = $stmt->fetchColumn();


        $stmt = $this->dbConn->query("SELECT COUNT(*) FROM company");
        $stats['total_companies'] = $stmt->fetchColumn();

        return $stats;
    }

    public function getRecentUsers($limit = 5)
    {

        $sql = "SELECT id, first_name, last_name, email 
                FROM users 
                ORDER BY id DESC 
                LIMIT :limit";
        
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAuditLogs($limit = 10)
    {
        $sql = "SELECT 
                    al.created_at, 
                    al.entity, 
                    al.entity_id,
                    u.username 
                FROM audit_logs al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.created_at DESC 
                LIMIT :limit";

        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}