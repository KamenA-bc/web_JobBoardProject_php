<?php
include_once '../../base-model.php';

class JobModel extends BaseModel
{
    public function __construct(PDO $dbConn)
    {
        parent::__construct($dbConn);
        $this->table = 'positions';
    }
    
    public function createJob($companyId, $titleId, $location, $seniorityId)
    {
        $sql = "INSERT INTO positions (company_id, title_id, location, seniority_id, status_id) 
                VALUES (:company_id, :title_id, :location, :seniority_id, :status_id)";
        
        try {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->execute([
                ':company_id' => $companyId,
                ':title_id' => $titleId,
                ':location' => $location,
                ':seniority_id' => $seniorityId,
                ':status_id' => 1
            ]);

            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Error posting job: " . $e->getMessage());
            return ['success' => false, 'error' => "Database error. .". $e->getMessage()];
        }
    }

    public function getAllTitles()
    {
        $stmt = $this->dbConn->prepare("SELECT * FROM title ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllSeniorities()
    {
        $stmt = $this->dbConn->prepare("SELECT * FROM seniority ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveJobCount($seniorityId = null)
    {
        $sql = "SELECT COUNT(*) as total FROM positions WHERE status_id = 1";
        $params = [];

        if ($seniorityId) {
            $sql .= " AND seniority_id = :seniority_id";
            $params[':seniority_id'] = $seniorityId;
        }

        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getActiveJobs($start, $limit, $seniorityId = null)
    {
        $sql = "SELECT 
                    p.id, 
                    p.location, 
                    c.name AS company_name, 
                    t.name AS title_name, 
                    s.name AS seniority_name
                FROM positions p
                INNER JOIN company c ON p.company_id = c.id
                INNER JOIN title t ON p.title_id = t.id
                INNER JOIN seniority s ON p.seniority_id = s.id
                WHERE p.status_id = 1";
        
        if ($seniorityId) {
            $sql .= " AND p.seniority_id = :seniority_id";
        }

        $sql .= " ORDER BY p.id DESC LIMIT :start, :limit";

        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        
        if ($seniorityId) {
            $stmt->bindValue(':seniority_id', $seniorityId, PDO::PARAM_INT);
        }

        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPositionsByOwner($userId)
    {
        $sql = "SELECT 
                    p.id,
                    p.location,
                    c.name as company_name,
                    t.name as title_name,
                    s.name as seniority_name,
                    ps.name as status_name
                FROM positions p
                JOIN company c ON p.company_id = c.id
                JOIN title t ON p.title_id = t.id
                JOIN seniority s ON p.seniority_id = s.id
                JOIN position_status ps ON p.status_id = ps.id
                WHERE c.owner_id = :user_id
                ORDER BY p.id DESC";
                
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function hasPendingApplications($positionId)
    {
        $sql = "SELECT COUNT(*) FROM application 
                WHERE position_id = :position_id 
                AND status_id NOT IN ('5' , '6')";
                
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':position_id', $positionId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }
    
    public function deletePosition($positionId, $requesterUserId)
    {
        $sql = "SELECT c.owner_id 
                FROM positions p
                JOIN company c ON p.company_id = c.id
                WHERE p.id = :position_id";
                
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':position_id', $positionId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return ['success' => false, 'error' => "Position not found."];
        }

        if ($result['owner_id'] != $requesterUserId) {
            return ['success' => false, 'status' => 'unauthorized'];
        }


        if ($this->hasPendingApplications($positionId)) {
            return [
                'success' => false, 
                'error' => "Cannot delete: This position has active applications pending review."
            ];
        }


        try {
            $this->dbConn->beginTransaction();


            $sqlApps = "DELETE FROM application WHERE position_id = :position_id";
            $stmtApps = $this->dbConn->prepare($sqlApps);
            $stmtApps->bindParam(':position_id', $positionId, PDO::PARAM_INT);
            $stmtApps->execute();


            $sqlPos = "DELETE FROM positions WHERE id = :id";
            $stmtPos = $this->dbConn->prepare($sqlPos);
            $stmtPos->bindParam(':id', $positionId, PDO::PARAM_INT);
            $stmtPos->execute();

            $this->dbConn->commit();
            
            return ['success' => true, 'message' => "Position and history deleted successfully."];

        } catch (PDOException $e) {
            $this->dbConn->rollBack();
            return ['success' => false, 'error' => "Database error: Could not delete position."];
        }
    }
}