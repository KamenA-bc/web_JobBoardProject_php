<?php

class companyRegisterModel
{
    private PDO $dbConn;
    
    public function __construct(PDO $dbConn) 
    {
        $this->dbConn = $dbConn;
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
    
    public function companyExists($companyName) 
    {
        $sql = "SELECT id FROM company 
                WHERE name = :company_name";
        try 
        {
            $stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(':company_name', $companyName);
            $stmt->execute();

            $comapny = $stmt->fetch(PDO::FETCH_ASSOC);

            return $comapny !== false;
        } 
        catch (PDOException $e) 
        {
            include 'comapny-register-view.php';
            echo "Database error: " . $e->getMessage();
            return false;
        }
    }
    
    public function registerCompany($companyName, $companyURL)
    {
        
        if(!$this->validateURL($companyURL))
        {
            $errorMessage = "Invalid URL. Please enter a valid URL for your company.";
            include 'company-register-view.php';
            return false;
        }
        
        if($this->companyExists($companyName))
        {
            $errorMessage = "This company has already been registered.";
            include 'company-register-view.php';
            return false;
        }
        
        try
        {
            $sql = "INSERT INTO company (name, site_url)
                    VALUES (:company_name, :company_site_url)";
            
            $stmt = $this->dbConn->prepare($sql);
            
            $stmt->bindParam(":company_name", $companyName);
            $stmt->bindParam(":company_site_url", $companyURL);
            
            $stmt->execute();
            
            $successMessage = "Successful registration!";
            include 'company-register-view.php';
            return true;
        }
        catch (PDOException $e)
        {
            $errorMessage = "Database error: " . $e->getMessage();
            include 'company-register-view.php';
            return false;
        }
    }
    
}

