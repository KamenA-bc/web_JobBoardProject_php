<?php
include '../base-model.php';

class companyRegisterModel extends BaseModel
{
    public function __construct(PDO $dbConn) 
    {
        $this->table = 'company';
        parent::__construct($dbConn);
    }

    private function validateURL($companyURL)
    {
        return filter_var($companyURL, FILTER_VALIDATE_URL) !== false;
    }
    
    public function registerCompany($companyName, $companyURL)
    {
        if(!$this->validateURL($companyURL))
        {
            return [
                'success' => false,
                'error' => "Invalid URL. Please enter a valid website URL (e.g., https://example.com)."
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
}

