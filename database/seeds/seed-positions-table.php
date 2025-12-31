<?php
include "../../config.php";

try {
    $positions = [
        ['company_id' => 1, 'title_id' => 1, 'location' => 'Sofia', 'seniority_id' => 1, 'status_id' => 1], // Active
        ['company_id' => 1, 'title_id' => 2, 'location' => 'Remote', 'seniority_id' => 2, 'status_id' => 1], // Active
        ['company_id' => 1, 'title_id' => 3, 'location' => 'Plovdiv', 'seniority_id' => 3, 'status_id' => 1], // Active
        ['company_id' => 1, 'title_id' => 4, 'location' => 'Varna', 'seniority_id' => 2, 'status_id' => 2], // Inactive (or whatever 2 is)
        ['company_id' => 1, 'title_id' => 5, 'location' => 'Remote', 'seniority_id' => 3, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 1, 'location' => 'Burgas', 'seniority_id' => 1, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 6, 'location' => 'Sofia', 'seniority_id' => 2, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 7, 'location' => 'Remote', 'seniority_id' => 3, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 2, 'location' => 'London', 'seniority_id' => 2, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 3, 'location' => 'Berlin', 'seniority_id' => 1, 'status_id' => 1]
    ];

    $sqlInsert = "INSERT INTO positions (company_id, title_id, location, seniority_id, status_id) 
                  VALUES (:company_id, :title_id, :location, :seniority_id, :status_id)";
    
    $stmt = $dbConn->prepare($sqlInsert);

    echo "<br>Positions seeded successfully!";

} catch (PDOException $e) {
    die("Error preparing query: " . $e->getMessage());
}
?>