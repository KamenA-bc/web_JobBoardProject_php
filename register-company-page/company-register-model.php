<?php
include '../base-model.php';

class companyRegisterModel extends BaseModel
{
    public function __construct(PDO $dbConn) 
    {
        $this->table = 'company';
        parent::__construct($dbConn);
    }

    public function validateURL($companyURL)
    {
        if(filter_var($companyURL, FILTER_VALIDATE_URL))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function registerCompany($companyName, $companyURL)
    {
        
        if(!$this->validateURL($companyURL))
        {
                return [
                    'success' => false,
                    'error' => "Invalid URL."
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
            $this->insertRow($data);
            return [
                'success' => true,
                'message' => "Company successfuly registered"
            ];
        } 
        catch (PDOException $e) 
        {
            return [
                'success' => false,
                'error' => "Database error: " . $e->getMessage()
            ];
        }
    }
    
}

