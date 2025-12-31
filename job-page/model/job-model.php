<?php
include_once '../../base-model.php';

class JobModel extends BaseModel
{
    public function __construct(PDO $dbConn)
    {
        parent::__construct($dbConn);
        $this->table = 'positions';
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
    

    public function getActiveJobCount()
    {
        $sql = "SELECT COUNT(*) as total FROM positions WHERE status_id = 1"; // 1 = Active
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getActiveJobs($start, $limit)
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
                WHERE p.status_id = 1
                ORDER BY p.id DESC
                LIMIT :start, :limit";

        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}