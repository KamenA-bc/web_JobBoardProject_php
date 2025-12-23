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
}