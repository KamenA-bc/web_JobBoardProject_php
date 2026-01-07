<?php
include_once '../../base-model.php';

class CompanyModel extends BaseModel
{
    public function __construct(PDO $dbConn) 
    {
        parent::__construct($dbConn);
        $this->table = 'company';
    }

    private function validateURL($companyURL)
    {
        return filter_var($companyURL, FILTER_VALIDATE_URL) !== false;
    }
    
    public function getCompanyCountByOwner($ownerId)
    {
        $sql = "SELECT COUNT(*) FROM company WHERE owner_id = :owner_id";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':owner_id', $ownerId, PDO::PARAM_INT);
        $stmt->execute();
        

        return $stmt->fetchColumn(); 
    }


    private function hasActivePositions($companyId)
    {
        $sql = "SELECT COUNT(*) FROM positions WHERE company_id = :company_id"; 
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    public function registerCompany($companyName, $companyURL)
    {
        if(!$this->validateURL($companyURL))
        {
            return [
                'success' => false,
                'error' => "Invalid URL. Please enter a valid website URL"
            ];
        }
        
        $conditions = [
            'name' => $companyName
        ];
        
        if($this->rowExists($conditions))
        {
            return [
                'success' => false,
                'error' => "This company already exists."
            ];
        }
        
        $data = [
            'owner_id' => $_SESSION['user_id'],
            'name' => $companyName,
            'site_url' => $companyURL,
        ];
        
        try 
        {
            $companyId = $this->insertRow($data);
            
            if($companyId)
            {
                return [
                    'success' => true,
                    'company_id' => $companyId,
                    'message' => "Company successfully registered!"
                ];
            }
            else
            {
                return [
                    'success' => false,
                    'error' => "Failed to register company. Please try again."
                ];
            }
        } 
        catch (PDOException $e) 
        {
            error_log("Company registration error: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => "Failed to register company. Please try again."
            ];
        }
    }
    
    public function getCompaniesByOwner($ownerId)
    {
        $sql = "SELECT * FROM company WHERE owner_id = :owner_id";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':owner_id', $ownerId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteCompany($companyId, $requesterUserId)
    {
        $company = $this->selectById($companyId);

        if (!$company) {
            return [
                'success' => false,
                'error' => "Company not found."
            ];
        }

        if ($company['owner_id'] != $requesterUserId) {
            return [
                'success' => false,
                'status' => 'unauthorized'
            ];
        }

        if ($this->hasActivePositions($companyId)) {
            return [
                'success' => false,
                'error' => "Cannot delete company. There are active job positions associated with it."
            ];
        }

        if ($this->deleteRow($companyId)) {
            return [
                'success' => true,
                'message' => "Company deleted successfully."
            ];
        }

        return [
            'success' => false,
            'error' => "Database error: Could not delete."
        ];
    }
    
    public function getCompaniesByOwnerPaginated($ownerId, $start, $limit)
    {
        $sql = "SELECT * FROM company WHERE owner_id = :owner_id LIMIT :start, :limit";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':owner_id', $ownerId, PDO::PARAM_INT);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}