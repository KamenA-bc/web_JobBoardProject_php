<?php
include_once '../../base-model.php';

class ApplicationModel extends BaseModel
{
    public function __construct(PDO $dbConn)
    {
        parent::__construct($dbConn);
        $this->table = 'application';
    }

public function applyForJob($userId, $positionId)
    {
        $data = [
            'user_id'     => $userId,
            'position_id' => $positionId,
            'applied_on'  => date('Y-m-d'), 
            'status_id'   => 1 // Default status = Applied
        ];

        $insertId = $this->insertRow($data);

        if ($insertId) {
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => "Failed to submit application."];
        }
    }
    
    public function checkIfApplied($userId, $positionId)
    {
        $sql = "SELECT COUNT(*) FROM application 
                WHERE user_id = :user_id AND position_id = :position_id";
        
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':position_id' => $positionId
        ]);
        
        return $stmt->fetchColumn() > 0;
    }


    public function getAppliedCompanies($userId)
    {
        $sql = "SELECT DISTINCT c.id, c.name 
                FROM company c
                JOIN positions p ON c.id = p.company_id
                JOIN application a ON p.id = a.position_id
                WHERE a.user_id = :user_id
                ORDER BY c.name ASC";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getApplicationCount($userId, $companyId = null)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM application a
                INNER JOIN positions p ON a.position_id = p.id
                WHERE a.user_id = :user_id";
        
        if ($companyId) {
            $sql .= " AND p.company_id = :company_id";
        }

        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        
        if ($companyId) {
            $stmt->bindValue(':company_id', $companyId, PDO::PARAM_INT);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getUserApplications($userId, $start, $limit, $companyId = null)
    {
        $sql = "SELECT 
                    a.id,
                    a.applied_on,
                    p.title_id, 
                    t.name as job_title,
                    c.name as company_name,
                    p.location,
                    s.name as status_name,
                    s.id as status_id
                FROM application a
                INNER JOIN positions p ON a.position_id = p.id
                INNER JOIN title t ON p.title_id = t.id
                INNER JOIN company c ON p.company_id = c.id
                INNER JOIN application_status s ON a.status_id = s.id 
                WHERE a.user_id = :user_id";
        
        if ($companyId) {
            $sql .= " AND p.company_id = :company_id";
        }

        $sql .= " ORDER BY a.applied_on DESC LIMIT :start, :limit";

        $stmt = $this->dbConn->prepare($sql);
        
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        
        if ($companyId) {
            $stmt->bindValue(':company_id', $companyId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOwnerApplications($ownerId)
    {
        $sql = "SELECT a.id as app_id, a.applied_on, a.status_id, u.username as applicant_name, t.name as job_title, c.name as company_name, s.name as status_name
                FROM application a
                INNER JOIN positions p ON a.position_id = p.id
                INNER JOIN company c ON p.company_id = c.id
                INNER JOIN users u ON a.user_id = u.id
                INNER JOIN title t ON p.title_id = t.id
                INNER JOIN application_status s ON a.status_id = s.id
                WHERE c.owner_id = :owner_id ORDER BY a.applied_on DESC";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute([':owner_id' => $ownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStatuses() {
        $sql = "SELECT * FROM application_status ORDER BY id ASC";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($appId, $newStatusId) {
        $sql = "UPDATE application SET status_id = :status_id WHERE id = :id";
        try {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->execute([':status_id' => $newStatusId, ':id' => $appId]);
            return true;
        } catch (PDOException $e) { return false; }
    }
}
?>
