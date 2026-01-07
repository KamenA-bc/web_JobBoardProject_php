<?php
include_once '../../base-model.php';

class ApplicationModel extends BaseModel
{
    const STATUS_APPLIED = 1;
    const STATUS_SCREENING = 2;
    const STATUS_INTERVIEW = 3;
    const STATUS_OFFER = 4;
    const STATUS_HIRED = 5;
    const STATUS_REJECTED = 6;

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
            'status_id'   => self::STATUS_APPLIED 
        ];

        return $this->insertRow($data);
    }


    public function saveDocument($applicationId, $file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $uploadDir = '../../uploads/'; 
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueName = uniqid('cv_', true) . '.' . $extension;
        $destination = $uploadDir . $uniqueName;
        $dbPath = 'uploads/' . $uniqueName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $sql = "INSERT INTO documents (application_id, file_path, uploaded_at) VALUES (:app_id, :path, NOW())";
            $stmt = $this->dbConn->prepare($sql);
            $stmt->execute([':app_id' => $applicationId, ':path' => $dbPath]);
            return true;
        }
        return false;
    }

    public function getUserApplications($userId, $start, $limit, $companyId = null)
    {
        $sql = "SELECT 
                    a.id, a.applied_on, a.status_id,
                    p.title_id, t.name as job_title, 
                    c.name as company_name, p.location, 
                    s.name as status_name,
                    d.file_path  -- Get the file path
                FROM application a
                INNER JOIN positions p ON a.position_id = p.id
                INNER JOIN title t ON p.title_id = t.id
                INNER JOIN company c ON p.company_id = c.id
                INNER JOIN application_status s ON a.status_id = s.id
                LEFT JOIN documents d ON a.id = d.application_id -- Join Documents
                WHERE a.user_id = :user_id";
        
        if ($companyId) { $sql .= " AND p.company_id = :company_id"; }
        $sql .= " ORDER BY a.applied_on DESC LIMIT :start, :limit";

        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        if ($companyId) { $stmt->bindValue(':company_id', $companyId, PDO::PARAM_INT); }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getOwnerApplications($ownerId)
    {
        $sql = "SELECT 
                    a.id as app_id, a.applied_on, a.status_id, 
                    u.username as applicant_name, 
                    t.name as job_title, 
                    c.name as company_name, 
                    s.name as status_name,
                    d.file_path -- Get the file path
                FROM application a
                INNER JOIN positions p ON a.position_id = p.id
                INNER JOIN company c ON p.company_id = c.id
                INNER JOIN users u ON a.user_id = u.id
                INNER JOIN title t ON p.title_id = t.id
                INNER JOIN application_status s ON a.status_id = s.id
                LEFT JOIN documents d ON a.id = d.application_id -- Join Documents
                WHERE c.owner_id = :owner_id 
                ORDER BY a.applied_on DESC";

        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute([':owner_id' => $ownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function getApplicationCount($userId, $companyId = null) 
    { 
        $sql = "SELECT COUNT(*) as total FROM application a INNER JOIN positions p ON a.position_id = p.id WHERE a.user_id = :user_id";
        if ($companyId) { $sql .= " AND p.company_id = :company_id"; }
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        if ($companyId) { $stmt->bindValue(':company_id', $companyId, PDO::PARAM_INT); }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getAppliedCompanies($userId) {
        $sql = "SELECT DISTINCT c.id, c.name FROM company c JOIN positions p ON c.id = p.company_id JOIN application a ON p.id = a.position_id WHERE a.user_id = :user_id ORDER BY c.name ASC";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllStatuses() {
        $sql = "SELECT * FROM application_status ORDER BY id ASC";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function isStatusChangeValid($currentStatusId, $newStatusId) {
        if ($currentStatusId == $newStatusId) return true;
        if ($newStatusId == self::STATUS_REJECTED) return true;
        if ($currentStatusId == self::STATUS_HIRED || $currentStatusId == self::STATUS_REJECTED) return false; 
        if ($newStatusId > $currentStatusId) return true;
        return false;
    }

    public function updateStatus($appId, $newStatusId) {
        $sqlGet = "SELECT status_id FROM application WHERE id = :id";
        $stmtGet = $this->dbConn->prepare($sqlGet);
        $stmtGet->execute([':id' => $appId]);
        $currentStatusId = $stmtGet->fetchColumn();

        if (!$currentStatusId) return false;
        if (!$this->isStatusChangeValid($currentStatusId, $newStatusId)) return false; 

        $sql = "UPDATE application SET status_id = :status_id WHERE id = :id";
        try {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->execute([':status_id' => $newStatusId, ':id' => $appId]);
            return true;
        } catch (PDOException $e) { return false; }
    }
}
?>