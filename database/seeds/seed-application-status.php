<?php
include "../../config.php";

try {
    $statuses = [
        ['name' => 'applied'],
        ['name' => 'screening'],
        ['name' => 'interview'],
        ['name' => 'offer'],
        ['name' => 'hired'],
        ['name' => 'rejected']
    ];

    $sqlInsert = "INSERT INTO application_status (name) 
                  VALUES (:status_name)";
    
    $stmt = $dbConn->prepare($sqlInsert);

    foreach ($statuses as $status) {
        $stmt->execute([
            ':status_name' => $status['name']
        ]);
    }

    echo "<br>Application statuses seeded successfully!";

} catch (PDOException $e) {
    die("Error seeding application statuses: " . $e->getMessage());
}
?>