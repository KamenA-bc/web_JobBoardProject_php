    <?php
    include '../../base-model.php';
    define('START', 0);
    define('ROWS_PER_PAGE', 10);
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

    public function selectWithLimit($start)
    {
        $sql = "SELECT * FROM {$this->table} LIMIT :start, :rows";
        $stmt = $this->dbConn->prepare($sql);

        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':rows', ROWS_PER_PAGE, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

