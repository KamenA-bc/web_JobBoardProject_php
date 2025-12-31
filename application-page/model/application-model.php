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

    public function getUserApplications($userId)
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
                WHERE a.user_id = :user_id
                ORDER BY a.applied_on DESC";

        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
