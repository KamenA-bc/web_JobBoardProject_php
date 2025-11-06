
        <?php
        $host="localhost";
        $dbUser="root";
        $dbPass="";
        $dbName="job_board";
        try
        {
            $dbConn = new PDO("mysql:host=$host;dbname=$dbName", $dbUser, $dbPass);
            
            $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE IF NOT EXISTS job_board ";
            $dbConn->exec($sql);
        }
        catch(PDOException $e) 
        {
            die("Connection failed" . $e->getMessage());
        }


